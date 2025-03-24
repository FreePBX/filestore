<?php
function check_sftp_connect($host, $port, $timeout, $user, $password, $path) {
	$connection = @ssh2_connect($host, $port);
	if(!$connection) {
		return "Connect failed";
	}
	else { // Connection to the Server could be established
		if(!@ssh2_auth_password($connection, $user, $password)) {
			@ssh2_disconnect($connection);
			return "Login failed";
		}
		else {
			$sftp = @ssh2_sftp($connection);
			if(!@ssh2_sftp_stat($sftp, $path)) {
				@ssh2_disconnect($connection);
				return "Chdir failed";
			}
			else {
				$now = time();
				$file = "freepbx_test$now.txt";
				$stream = @fopen('ssh2.sftp://' . intval($sftp) . "$path/$file", 'w');
				if(!$stream) {
					@ssh2_disconnect($connection);
					return "Write failed";
				}
				else {
					ssh2_sftp_unlink($sftp, "$path/$file");
					return "OK";
				}
			}
		}
	}
}

function check_ftp_connect($host, $port, $timeout, $usetls, $user, $password, $path, $transfer) {
	$command = "ftp_connect";
	if($usetls == "yes") {
		$command = "ftp_ssl_connect";
	}
	$ftp = @$command($host, $port, $timeout);
	if(!$ftp) {
		return "Connect failed";
	}
	else { // Connection to the Server could be established
		if(!@ftp_login($ftp, $user, $password)) {
			@ftp_close($ftp);
			return "Login failed";
		}
		else {
			if(!@ftp_chdir($ftp, $path)) {
				@ftp_close($ftp);
				return "Chdir failed";
			}
			else {
				$now = time();
				$file = "/tmp/freepbx_test$now.txt";
				file_put_contents($file, "Test");
				if($transfer == "passive") {
					@ftp_pasv($ftp, true);
				}
				else {
					@ftp_pasv($ftp, false);
				}
				if(!@ftp_put($ftp, basename($file), $file, FTP_ASCII)) {
					@ftp_close($ftp);
					unlink($file);
					return "Write failed";
				}
				else {
					@ftp_delete($ftp, basename($file));
					@ftp_close($ftp);
					unlink($file);
					return "OK";
				}
			}
		}
	}
}
?>
