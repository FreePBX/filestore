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
    
    // Check for empty path
    if (empty($path)) {
        return false;
    }
    
    // Remove any null bytes
    $path = str_replace("\0", "", $path);
    
    // Check for command injection patterns
    $dangerous_patterns = array(
        '/[`$()]/',           // Backticks and command substitution
        '/[;&|]/',            // Command separators
        '/[<>]/',             // Redirection operators
        '/\\\\s/',            // Backslash space combinations
        '/\beval\b/i',        // eval command
        '/\bexec\b/i',        // exec command (in path context)
        '/\bsh\b/',           // shell commands
        '/\bbash\b/',         // bash commands
        '/\\\\x[0-9a-fA-F]/', // Hex encoded characters
        '/\bcat\b/i',         // cat command
        '/\brm\b/i',          // rm command
        '/\bmkdir\b/i',       // mkdir command
        '/\bchmod\b/i',       // chmod command
        '/\bchown\b/i',       // chown command
        '/\bwget\b/i',        // wget command
        '/\bcurl\b/i',        // curl command
        '/\bpython\b/i',      // python command
        '/\bperl\b/i',        // perl command
        '/\bphp\b/i',         // php command
        '/\bnode\b/i',        // node command
        '/\bjava\b/i',        // java command
        '/^\/bin\//',         // /bin/ directory (but allow /bin in other contexts)
        '/^\/usr\/bin\//',    // /usr/bin/ directory
        '/^\/usr\/sbin\//',   // /usr/sbin/ directory
        '/^\/sbin\//',        // /sbin/ directory
        '/^\/var\/www\//',    // /var/www/ directory
        '/^\/var\/log\//',    // /var/log/ directory
        '/^\/etc\/passwd$/',  // /etc/passwd file
        '/^\/etc\/shadow$/',  // /etc/shadow file
        '/^\/proc\//',        // /proc/ directory
        '/^\/sys\//',         // /sys/ directory
        '/^\/dev\//',         // /dev/ directory
    );
    
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $path)) {
            return false;
        }
    }
    
    // Allow only safe characters: alphanumeric, forward slash, underscore, dash, dot, tilde
    // Note: This allows both relative and absolute paths
    if (!preg_match('/^[a-zA-Z0-9\/\._~-]+$/', $path)) {
        return false;
    }
    
    // Prevent directory traversal
    if (strpos($path, '..') !== false) {
        return false;
    }
    
    // Allow absolute paths but ensure they don't contain dangerous system directories
    if (strpos($path, '/') === 0) {
        // Check if the absolute path contains dangerous system directories
        $dangerous_abs_paths = array(
            '/bin/',
            '/usr/bin/',
            '/usr/sbin/',
            '/sbin/',
            '/etc/',
            '/var/www/',
            '/var/log/',
            '/proc/',
            '/sys/',
            '/dev/',
        );
        
        foreach ($dangerous_abs_paths as $dangerous_path) {
            if (strpos($path, $dangerous_path) === 0) {
                return false;
            }
        }
    }
    
    // Limit path length to prevent buffer overflow attacks
    if (strlen($path) > 255) {
        return false;
    }
    
    // Ensure path doesn't contain multiple consecutive slashes
    if (strpos($path, '//') !== false) {
        return false;
    }
    
    return $path;
}

/**
 * Validates and sanitizes host parameter
 * @param string $host The host to validate
 * @return string|false Returns sanitized host or false if invalid
 */
function validate_and_sanitize_host($host) {
    if (!is_string($host)) {
        return false;
    }
    
    $host = trim($host);
    
    if (empty($host)) {
        return false;
    }
    
    // Remove any null bytes
    $host = str_replace("\0", "", $host);
    
    // Check for command injection patterns
    $dangerous_patterns = array(
        '/[`$()]/',           // Backticks and command substitution
        '/[;&|]/',            // Command separators
        '/[<>]/',             // Redirection operators
        '/\\\\s/',            // Backslash space combinations
        '/\beval\b/i',        // eval command
        '/\bexec\b/i',        // exec command
        '/\bsh\b/',           // shell commands
        '/\bbash\b/',         // bash commands
    );
    
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $host)) {
            return false;
        }
    }
    
    // Allow only safe characters for hostnames: alphanumeric, dots, dashes, underscores
    if (!preg_match('/^[a-zA-Z0-9\._-]+$/', $host)) {
        return false;
    }
    
    // Limit host length
    if (strlen($host) > 253) {
        return false;
    }
    
    return $host;
}

/**
 * Validates and sanitizes user parameter
 * @param string $user The user to validate
 * @return string|false Returns sanitized user or false if invalid
 */
function validate_and_sanitize_user($user) {
    if (!is_string($user)) {
        return false;
    }
    
    $user = trim($user);
    
    if (empty($user)) {
        return false;
    }
    
    // Remove any null bytes
    $user = str_replace("\0", "", $user);
    
    // Check for command injection patterns
    $dangerous_patterns = array(
        '/[`$()]/',           // Backticks and command substitution
        '/[;&|]/',            // Command separators
        '/[<>]/',             // Redirection operators
        '/\\\\s/',            // Backslash space combinations
        '/\beval\b/i',        // eval command
        '/\bexec\b/i',        // exec command
        '/\bsh\b/',           // shell commands
        '/\bbash\b/',         // bash commands
    );
    
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $user)) {
            return false;
        }
    }
    
    // Allow only safe characters for usernames: alphanumeric, underscores, dashes
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $user)) {
        return false;
    }
    
    // Limit user length
    if (strlen($user) > 32) {
        return false;
    }
    
    return $user;
}

/**
 * Validates and sanitizes path parameter
 * @param string $path The path to validate
 * @return string|false Returns sanitized path or false if invalid
 */
function validate_and_sanitize_path($path) {
    if (!is_string($path)) {
        return false;
    }
    
    $path = trim($path);
    
    if (empty($path)) {
        return false;
    }
    
    // Remove any null bytes
    $path = str_replace("\0", "", $path);
    
    // Check for command injection patterns
    $dangerous_patterns = array(
        '/[`$()]/',           // Backticks and command substitution
        '/[;&|]/',            // Command separators
        '/[<>]/',             // Redirection operators
        '/\\\\s/',            // Backslash space combinations
        '/\beval\b/i',        // eval command
        '/\bexec\b/i',        // exec command
        '/\bsh\b/',           // shell commands
        '/\bbash\b/',         // bash commands
    );
    
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $path)) {
            return false;
        }
    }
    
    // Allow only safe characters for paths: alphanumeric, forward slash, underscore, dash, dot
    if (!preg_match('/^[a-zA-Z0-9\/\._-]+$/', $path)) {
        return false;
    }
    
    // Prevent directory traversal
    if (strpos($path, '..') !== false) {
        return false;
    }
    
    // Limit path length
    if (strlen($path) > 255) {
        return false;
    }
    
    return $path;
}

/**
 * Run one remote command and return stdout/stderr.
 * ssh2_exec does not allocate a PTY, which the restricted key requires.
 */
function ssh2_exec_output($connection, $command) {
    $stream = @ssh2_exec($connection, $command);
    if (!$stream) {
        return array('ok' => false, 'stdout' => '', 'stderr' => 'exec failed');
    }
    $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
    stream_set_blocking($errorStream, true);
    stream_set_blocking($stream, true);
    $stdout = stream_get_contents($stream);
    $stderr = stream_get_contents($errorStream);
    fclose($errorStream);
    fclose($stream);
    return array(
        'ok' => true,
        'stdout' => is_string($stdout) ? $stdout : '',
        'stderr' => is_string($stderr) ? $stderr : '',
    );
}

/**
 * Path and write check for an asterisk key wrapped by freepbx-ssh-restrict.sh.
 * Uses only RESTRICT-* commands. Does not open SFTP or run a raw shell.
 */
function check_restricted_ssh_path($connection, $path) {
    $path = rtrim($path, '/');
    if ($path === '') {
        @ssh2_disconnect($connection);
        return "Chdir failed";
    }

    $listed = ssh2_exec_output($connection, 'RESTRICT-LS-001 ' . $path);
    if (!$listed['ok']) {
        @ssh2_disconnect($connection);
        return "Chdir failed";
    }
    // Unrestricted peer has no RESTRICT-* commands. Caller uses the legacy test.
    if (stripos($listed['stderr'], 'command not found') !== false) {
        return false;
    }
    if ($listed['stderr'] !== '') {
        @ssh2_disconnect($connection);
        return "Chdir failed";
    }

    $probe = $path . '/freepbx_test' . time();
    $made = ssh2_exec_output($connection, 'RESTRICT-MKDIR-001 ' . $probe);
    if (!$made['ok'] || $made['stderr'] !== '') {
        @ssh2_disconnect($connection);
        return "Write failed";
    }
    $removed = ssh2_exec_output($connection, 'RESTRICT-RM-001 ' . $probe);
    @ssh2_disconnect($connection);
    if (!$removed['ok'] || $removed['stderr'] !== '') {
        return "Write failed";
    }
    return "OK";
}

/**
 * SFTP path and write check. Only for the chrooted freepbx-sftp user.
 */
function check_chrooted_sftp_path($connection, $path) {
    $sftp = @ssh2_sftp($connection);
    if ($sftp === false) {
        @ssh2_disconnect($connection);
        return "Chdir failed";
    }
    $path = rtrim($path, '/') ?: '/';
    if (!@ssh2_sftp_stat($sftp, $path)) {
        @ssh2_disconnect($connection);
        return "Chdir failed";
    }
    $remote = $path . '/freepbx_test' . time() . '.txt';
    $stream = @fopen('ssh2.sftp://' . intval($sftp) . $remote, 'w');
    if (!$stream) {
        @ssh2_disconnect($connection);
        return "Write failed";
    }
    fwrite($stream, "FreePBX Filestore Test");
    fclose($stream);
    @ssh2_sftp_unlink($sftp, $remote);
    @ssh2_disconnect($connection);
    return "OK";
}

function check_ssh_connect($host, $port, $user, $key, $path) {
    // Validate all input parameters
    $host = validate_and_sanitize_host($host);
    if (!$host) {
        return "Invalid host";
    }
    
    $user = validate_and_sanitize_user($user);
    if (!$user) {
        return "Invalid user";
    }
    
    $key = validate_and_sanitize_key($key);
    if (!$key) {
        return "Invalid key";
    }
    
    $path = validate_and_sanitize_path($path);
    if (!$path) {
        return "Invalid path";
    }
    
    // Validate port
    if (!is_numeric($port) || $port < 1 || $port > 65535) {
        return "Invalid port";
    }
    
    $keypath = dirname($key);
	$publickey = "$key.pub";
	
	// Use PHP's mkdir instead of exec for better security
	if(!is_dir($keypath)) {
		if (!mkdir($keypath, 0755, true)) {
			return "Failed to create key directory";
		}
	}
	
	// Use PHP's file operations instead of exec for better security
	if(!file_exists($key)) {
		// Generate SSH key using PHP's openssl functions or safe exec with proper escaping
		$escaped_key = escapeshellarg($key);
		$escaped_keypath = escapeshellarg($keypath);
		
		// Create the key using ssh-keygen with proper escaping
		$cmd = "ssh-keygen -t ecdsa -b 521 -f $escaped_key -N '' 2>/dev/null";
		exec($cmd, $output, $return_code);
		
		if ($return_code !== 0) {
			return "Failed to generate SSH key";
		}
		
		// Set proper ownership and permissions
		if (function_exists('chown') && function_exists('chmod')) {
			chown($key, 'asterisk');
			chmod($key, 0600);
		} else {
			// Fallback to exec with proper escaping
			$cmd = "chown asterisk:asterisk $escaped_key && chmod 600 $escaped_key 2>/dev/null";
			exec($cmd, $output, $return_code);
		}
	}
	
	if(!file_exists($publickey)) {
		$escaped_key = escapeshellarg($key);
		$escaped_publickey = escapeshellarg($publickey);
		$cmd = "ssh-keygen -y -f $escaped_key > $escaped_publickey 2>/dev/null";
		exec($cmd, $output, $return_code);
		
		if ($return_code !== 0) {
			return "Failed to generate public key";
		}
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
			// freepbx-sftp is the only account allowed to use SFTP, and it is chrooted.
			if ($user === 'freepbx-sftp') {
				return check_chrooted_sftp_path($connection, $path);
			}

			// asterisk keys are ForceCommand-wrapped. Test with allow-listed commands only.
			$restricted = check_restricted_ssh_path($connection, $path);
			if ($restricted !== false) {
				return $restricted;
			}

			// Legacy peer with no command restriction.
			$escaped_path = escapeshellarg($path);
			$stream = ssh2_exec($connection, "cd $escaped_path");
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
				$escaped_full_path = escapeshellarg("$path/$filename");

				if(!@ssh2_scp_send($connection, "$file", "$path/$filename", 0644)) {
					@ssh2_disconnect($connection);
					unlink($file);
					return "Write failed";
				}
				else {
					$stream = ssh2_exec($connection, "rm $escaped_full_path");
					unlink($file);
					return "OK";
				}
			}
		}
	}
}
?>
