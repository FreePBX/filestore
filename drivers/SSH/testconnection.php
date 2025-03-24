<?php
function check_ssh_connect($host, $port, $user, $key, $path) {
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
