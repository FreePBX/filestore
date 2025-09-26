<?php

/**
 * Validates and sanitizes a file path to prevent command injection
 * @param string $path The path to validate
 * @return string|false Returns sanitized path or false if invalid
 */
function validate_and_sanitize_key($path) {
    if (!is_string($path)) {
        return false;
    }
    $path = trim($path);
    // Remove any null bytes
    $path = str_replace("\0", "", $path);
    // Check for command injection patterns
    $dangerous_patterns = array(
        '/[`$()]/',           // Backticks and command substitution
        '/[;&|]/',            // Command separators
        '/[<>]/',             // Redirection operators
        '/\\\s/',             // Backslash space combinations
        '/\beval\b/i',        // eval command
        '/\bexec\b/i',        // exec command (in path context)
        '/\bsh\b/',           // shell commands
        '/\bbash\b/',         // bash commands
        '/\\\x[0-9a-fA-F]/',  // Hex encoded characters
    );
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $path)) {
            return false;
        }
    }
    // Allow only safe characters: alphanumeric, forward slash, underscore, dash, dot
    if (!preg_match('/^[a-zA-Z0-9\/\._-]+$/', $path)) {
        return false;
    }
    // Prevent directory traversal
    if (strpos($path, '..') !== false) {
        return false;
    }
    return $path;
}

function check_ssh_connect($host, $port, $user, $key, $path) {
    $key = validate_and_sanitize_key($key);
    if(!$key) {
        return "Invalid key";
    }
    $keypath = dirname($key);
	$publickey = "$key.pub";
	if(!is_dir($keypath)) {
		exec("mkdir -p $keypath");
	}
	if(!file_exists($key)) {
		exec("ssh-keygen -t ecdsa -b 521 -f $key -N \"\" && chown asterisk:asterisk $key && chmod 600 $key");
	}
	if(!file_exists($publickey)) {
		exec("ssh-keygen -y -f $key > $publickey");
	}
	$connection = @ssh2_connect($host, $port);
	if(!$connection) {
		return "Connect failed";
		}
		else { // Connection to the Server could be established
		if(!@ssh2_auth_pubkey_file($connection, $user, $publickey, $key)) {
			@ssh2_disconnect($connection);
			return "Login failed";
		}
		else {
			$stream = ssh2_exec($connection,"cd $path");
			$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
			stream_set_blocking($errorStream, true);
			stream_set_blocking($stream, true);
			$error = stream_get_contents($errorStream);
			if($error != "") {
				@ssh2_disconnect($connection);
				return "Chdir failed";
			}
			else {
				$now = time();
				$file = "/tmp/freepbx_test$now.txt";
				file_put_contents($file, "FreePBX Filestore Test");
				$filename = basename($file);
				if(!@ssh2_scp_send($connection, "$file", "$path/$filename", 0644)) {
					@ssh2_disconnect($connection);
					unlink($file);
					return "Write failed";
				}
				else {
					$stream = ssh2_exec($connection,"rm $path/$file");
					unlink($file);
					return "OK";
				}
			}
		}
	}
}
?>
