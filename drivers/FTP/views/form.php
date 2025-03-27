<?php
$disabled = (isset($readonly) && !empty($readonly)) ? ' disabled ' : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
include 'modal.testconnection.php';
?>
<div class="container-fluid">
	<h1><?php echo _('FTP Instance') ?></h1>
	<div class="display full-border">
		<div class="row">
			<div class="col-sm-12">
				<div class="fpbx-container">
					<div class="display full-border">
						<form class="fpbx-submit" action="?display=filestore" method="post" id="server_form" name="server_form" data-fpbx-delete="?display=filestore&action=delete&id=<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
							<input type="hidden" name="action" value="<?php echo empty($id) ? 'add' : 'edit' ?>">
							<input type="hidden" name="id" value="<?php echo $id ?>">
							<input type="hidden" name="driver" value="FTP">
							<!--Enabled-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="enabled"><?php echo _("Enabled") ?></label>
											<i class="fa fa-question-circle fpbx-help-icon" data-for="enabled"></i>
										</div>
										<div class="col-md-9">
											<span class="radioset">
												<input type="radio" name="enabled" id="enabledyes" value="yes" <?php echo $enabled != "no" ? "CHECKED" : "" ?>>
												<label for="enabledyes"><?php echo _("Yes"); ?></label>
												<input type="radio" name="enabled" id="enabledno" value="no" <?php echo $enabled == "no" ? "CHECKED" : "" ?>>
												<label for="enabledno"><?php echo _("No"); ?></label>
											</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="enabled-help" class="help-block fpbx-help-block"><?php echo _("We define if this storage is enabled or disabled.") ?></span>
									</div>
								</div>
							</div>
							<!--END Enabled-->
							<!--Server Name-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="name"><?php echo _("Server Name") ?></label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : '' ?>" <?php echo $disabled ?>>
										</div>
									</div>
								</div>
							</div>
							<!--END Server Name-->
							<!--Description-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="desc"><?php echo _("Description") ?></label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="desc" name="desc" value="<?php echo isset($desc) ? $desc : '' ?>" <?php echo $disabled ?>>
										</div>
									</div>
								</div>
							</div>
							<!--END Description-->
							<!--Hostname-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="host"><?php echo _("Hostname") ?></label>
											<i class="fa fa-question-circle fpbx-help-icon" data-for="host"></i>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="host" name="host" value="<?php echo isset($host) ? $host : '' ?>" <?php echo $disabled ?>>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="host-help" class="help-block fpbx-help-block"><?php echo _("IP address or FQDN of remote ftp host") ?></span>
									</div>
								</div>
							</div>
							<!--END Hostname-->
							<!--Port-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="port"><?php echo _("Port") ?></label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="port" name="port" value="<?php echo isset($port) ? $port : '' ?>" <?php echo $disabled ?>>
										</div>
									</div>
								</div>
							</div>
							<!--END Port-->
							<!--Use TLS-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="usetls"><?php echo _("Use TLS") ?></label>
											<i class="fa fa-question-circle fpbx-help-icon" data-for="usetls"></i>
										</div>
										<div class="col-md-9">
											<span class="radioset">
												<input type="radio" name="usetls" id="usetlsyes" value="yes" <?php echo $usetls == "yes" ? "CHECKED" : "" ?>>
												<label for="usetlsyes"><?php echo _("Yes"); ?></label>
												<input type="radio" name="usetls" id="usetlsno" value="no" <?php echo $usetls == "no" ? "CHECKED" : "" ?>>
												<label for="usetlsno"><?php echo _("No"); ?></label>
											</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="usetls-help" class="help-block fpbx-help-block"><?php echo _("Does this connection use tls? Make sure the port is set correctly.") ?></span>
									</div>
								</div>
							</div>
							<!--END Use TLS-->
							<!--Use SFTP-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="usesftp"><?php echo _("Use SFTP") ?></label>
											<i class="fa fa-question-circle fpbx-help-icon" data-for="usesftp"></i>
										</div>
										<div class="col-md-9">
											<span class="radioset">
												<input type="radio" name="usesftp" id="usesftpyes" value="yes" <?php echo (isset($usesftp) && $usesftp == "yes") ? "CHECKED" : "" ?>>
												<label for="usesftpyes"><?php echo _("Yes"); ?></label>
												<input type="radio" name="usesftp" id="usesftpno" value="no" <?php echo (!isset($usesftp) || $usesftp == "no") ? "CHECKED" : "" ?>>
												<label for="usesftpno"><?php echo _("No"); ?></label>
											</span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="usesftp-help" class="help-block fpbx-help-block"><?php echo _("Enable this option to use SFTP (Secure File Transfer Protocol), a secure method for transferring files that encrypts both commands and data. Ensure that the server supports SFTP for this connection.") ?></span>
									</div>
								</div>
							</div>
							<!--END Use SFTP-->
							<!--Username-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="user"><?php echo _("Username") ?></label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control clicktoedit" id="user" name="user" value="<?php echo isset($user) ? $user : '' ?>" <?php echo $disabled ?>>
										</div>
									</div>
								</div>
							</div>
							<!--END Username-->
							<!--Password-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="password"><?php echo _("Password") ?></label>
										</div>
										<div class="col-md-9">
											<input type="password" class="form-control clicktoedit" id="password" name="password" value="<?php echo isset($password) ? $password : '' ?>" <?php echo $disabled ?>>
										</div>
									</div>
								</div>
							</div>
							<!--END Password-->
							<!--Filesystem Type-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="fstype"><?php echo _("Filesystem Type") ?></label>
											<i class="fa fa-question-circle fpbx-help-icon" data-for="fstype"></i>
										</div>
										<div class="col-md-9 radioset">
											<input type="radio" name="fstype" id="fstypeauto" value="auto" <?php echo ($fstype == "auto" ? "CHECKED" : "") ?>>
											<label for="fstypeauto"><?php echo _("Auto"); ?></label>
											<input type="radio" name="fstype" id="fstypeunix" value="unix" <?php echo ($fstype == "unix" ? "CHECKED" : "") ?>>
											<label for="fstypeunix"><?php echo _("Unix/Linux"); ?></label>
											<input type="radio" name="fstype" id="fstypewindows" value="windows" <?php echo ($fstype == "windows" ? "CHECKED" : "") ?>>
											<label for="fstypewindows"><?php echo _("Windows"); ?></label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="fstype-help" class="help-block fpbx-help-block"><?php echo _("The FTP Server's file system type. If you are unsure set this to Auto") ?></span>
									</div>
								</div>
							</div>
							<!--END Filesystem Type-->
							<!--Path-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="path"><?php echo _("Path") ?></label>
											<i class="fa fa-question-circle fpbx-help-icon" data-for="path"></i>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="path" name="path" value="<?php echo isset($path) ? $path : '' ?>" <?php echo $disabled ?>>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="path-help" class="help-block fpbx-help-block"><?php echo _("Path on remote server. This must be a COMPLETE PATH, starting with a / - for example, <tt>/home/backups/freepbx</tt>. A path without a leading slash will not work, and will behave in unexpected ways.") ?></span>
									</div>
								</div>
							</div>
							<!--END Path-->
							<!--Transfer Mode-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="transfer"><?php echo _("Transfer Mode") ?></label>
											<i class="fa fa-question-circle fpbx-help-icon" data-for="transfer"></i>
										</div>
										<div class="col-md-9 radioset">
											<input type="radio" name="transfer" id="transferactive" value="active" <?php echo ($transfer == "active" ? "CHECKED" : "") ?><?php echo $disabled ?>>
											<label for="transferactive"><?php echo _("Active"); ?></label>
											<input type="radio" name="transfer" id="transferpassive" value="passive" <?php echo ($transfer == "active" ? "" : "CHECKED") ?><?php echo $disabled ?>>
											<label for="transferpassive"><?php echo _("Passive"); ?></label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="transfer-help" class="help-block fpbx-help-block"><?php echo _("This defaults to 'Passive'. If your FTP server is behind a seperate NAT or Firewall to this VoIP server, you should select 'Active'. In 'Active' mode, the FTP server establishes a connection back to the VoIP server to receive the data. In 'Passive' mode, the VoIP server connects to the FTP Server to send data.") ?></span>
									</div>
								</div>
							</div>
							<!--END Transfer Mode-->
							<!--timeout-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="timeout"><?php echo _("Timeout") ?></label>
											<i class="fa fa-question-circle fpbx-help-icon" data-for="timeout"></i>
										</div>
										<div class="col-md-9">
											<input type="number" class="form-control" id="timeout" name="timeout" value="<?php echo isset($timeout) ? $timeout : '' ?>" <?php echo $disabled ?>>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="timeout-help" class="help-block fpbx-help-block"><?php echo _("Timeout on remote server") ?></span>
									</div>
								</div>
							</div>
							<!--END Path-->
							<br />
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<button type='button' class='btn btn-default pull-right' id='testconn'><?php echo _("Test Connection Settings"); ?></button>
									</div>
								</div>
							</div>
						</form>
						<br />
						<br />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var immortal = <?php echo (isset($immortal) && !empty($immortal)) ? 'true' : 'false'; ?>;
	$('#server_form').on('submit', function(e) {
		if ($("#host").val().length === 0) {
			warnInvalid($("#host"), _("The host cannot be empty"));
			return false;
		}
		if ($("#timeout").val() < 1) {
			warnInvalid($("#timeout"), _("Timeout should not be empty and should greater than zero"));
			return false;
		}
		return true;
	});

	function testconn() {
		var req = {
			module: 'filestore',
			command: 'testconnection',
			driver: "FTP",
			host: $('#host').val(),
			port: $('#port').val(),
			timeout: $('#timeout').val(),
			usetls: $('input[name="usetls"]:checked').val(),
			usesftp: $('input[name="usesftp"]:checked').val(),
			user: $('#user').val(),
			password: $('#password').val(),
			path: $('#path').val(),
			transfer: $('input[name="transfer"]:checked').val(),
		};
		$.ajax({
			url: FreePBX.ajaxurl,
			data: req,
			success:function(data){
				console.log(data);
				if(data.message == "Connect failed") {
					$('#ftpconnection').text("Connection failed! Please check hostname and port settings!");
					$('#ftplogin').text("Aborted");
					$('#ftpchdir').text("Aborted");
					$('#ftpwrite').text("Aborted");
				}
				else if(data.message == "Login failed") {
					$('#ftpconnection').text("OK");
					$('#ftpchdir').text("Aborted");
					$('#ftpwrite').text("Aborted");
					$('#ftplogin').text("Login failed! Please verify your username and password. If you have activated TLS, please ensure that the server supports encryption.");
				}
				else if(data.message == "Chdir failed") {
					$('#ftpconnection').text("OK");
					$('#ftplogin').text("OK");
					$('#ftpchdir').text("Entering the directory failed. Please verify the directory setting and the permissions on the server!");
					$('#ftpwrite').text("Aborted");
				}
				else if(data.message == "Write failed") {
					$('#ftpconnection').text("OK");
					$('#ftplogin').text("OK");
					$('#ftpchdir').text("OK");
					$('#ftpwrite').text("Upload of a test-file failed! Please verify the permissions on the server!");
				}
				else {
					$('#ftpconnection').text("OK");
					$('#ftplogin').text("OK");
					$('#ftpchdir').text("OK");
					$('#ftpwrite').text("OK");
				}
			},
		});
	}

	$("#testconn").click(function(e) {
		e.preventDefault();
		$('#ftpconnection').text("");
		$('#ftplogin').text("");
		$('#ftpchdir').text("");
		$('#ftpwrite').text("");
		$("#custmodal").modal('show');
		testconn();
	});

	$('#testcon_close').click(function(e) {
		e.preventDefault();
		$('#ftpconnection').text("");
		$('#ftplogin').text("");
		$('#ftpchdir').text("");
		$('#ftpwrite').text("");
		$("#custmodal").modal('hide');
	});
</script>
