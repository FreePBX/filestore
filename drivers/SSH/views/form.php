<?php
$disabled = (isset($readonly) && !empty($readonly)) ? ' disabled ' : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
include 'modal.testconnection.php';
?>
<div class="container-fluid">
	<h1>
		<?php echo _("SSH Server") ?>
	</h1>
	<div class="display full-border">
		<div class="row">
			<div class="col-sm-12">
				<div class="fpbx-container">
					<div class="display full-border">
						<form class="fpbx-submit" action="?display=filestore" method="post" id="server_form" name="server_form" data-fpbx-delete="?display=filestore&action=delete&id=<?php echo $id ?>">
							<input type="hidden" name="action" value="<?php echo empty($id)?'add':'edit'?>">
							<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
							<input type="hidden" name="driver" value="SSH">
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
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="name">
														<?php echo _("Server Name") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="name"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : '' ?>"
													 <?php echo $disabled ?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="name-help" class="help-block fpbx-help-block">
											<?php echo _("Provide the name for this server") ?></span>
									</div>
								</div>
							</div>
							<!--END Server Name-->
							<!--Description-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="desc">
														<?php echo _("Description") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="desc"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="desc" name="desc" value="<?php echo isset($desc) ? $desc : '' ?>"
													 <?php echo $disabled ?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="desc-help" class="help-block fpbx-help-block">
											<?php echo _("Description or notes for this server") ?></span>
									</div>
								</div>
							</div>
							<!--END Description-->
							<!--Hostname-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="host">
														<?php echo _("Hostname") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="host"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="host" name="host" value="<?php echo isset($host) ? $host : '' ?>"
													 <?php echo $disabled ?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="host-help" class="help-block fpbx-help-block">
											<?php echo _("IP address or FQDN of remote SSH server") ?></span>
									</div>
								</div>
							</div>
							<!--END Hostname-->
							<!--Port-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="port">
														<?php echo _("Port") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="port"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="port" name="port" value="<?php echo isset($port) ? $port : '22' ?>"
													 <?php echo $disabled ?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="port-help" class="help-block fpbx-help-block">
											<?php echo _("Remote SSH Port") ?></span>
									</div>
								</div>
							</div>
							<!--END Port-->
							<!--Username-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="user">
														<?php echo _("Username") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="user"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="user" name="user" value="<?php echo isset($user) ? $user : '' ?>"
													 <?php echo $disabled ?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="user-help" class="help-block fpbx-help-block">
											<?php echo _("SSH Username") ?></span>
									</div>
								</div>
							</div>
							<!--END Username-->
							<!--Key-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="key">
														<?php echo _("Key") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="key"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="key" name="key" value="<?php echo isset($key) ? $key : '' ?>"
													 <?php echo $disabled ?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="key-help" class="help-block fpbx-help-block">
											<?php echo _("Location of ssh private key to be used when connecting to a host.") ?></span>
									</div>
								</div>
							</div>
							<!--END Key-->
							<!--Path-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="path">
														<?php echo _("Path") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="path"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="path" name="path" value="<?php echo isset($path) ? htmlspecialchars($path) : '' ?>"
													 <?php echo $disabled ?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="path-help" class="help-block fpbx-help-block">
											<?php echo _("Path on remote server") ?></span>
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br />
<br />
<script type="text/javascript">
	var immortal = <?php echo (isset($immortal) && !empty($immortal)) ? 'true' : 'false'; ?>;
	$('#server_form').on('submit', function (e) {
		if ($("#name").val().length === 0) {
			warnInvalid($("#host"), _("The host cannot be empty"));
			return false;
		} else {
			return true;
		}
	});

	function testconn() {
		var req = {
			module: 'filestore',
			command: 'testconnection',
			driver: "SSH",
			host: $('#host').val(),
			port: $('#port').val(),
			user: $('#user').val(),
			key: $('#key').val(),
			path: $('#path').val(),
		};
		$.ajax({
			url: FreePBX.ajaxurl,
			data: req,
			success:function(data){
				console.log(data);
				if(data.message == "Connect failed") {
					$('#sshconnection').text("Connection failed! Please check hostname and port settings!");
					$('#sshlogin').text("Aborted");
					$('#sshchdir').text("Aborted");
					$('#sshwrite').text("Aborted");
				}
				else if(data.message == "Login failed") {
					$('#sshconnection').text("OK");
					$('#sshchdir').text("Aborted");
					$('#sshwrite').text("Aborted");
					$('#sshlogin').text("Login failed! Please verify your username and ensure that the specified key is authorized to connect to the host.");
				}
				else if(data.message == "Chdir failed") {
					$('#sshconnection').text("OK");
					$('#sshlogin').text("OK");
					$('#sshchdir').text("Entering the directory failed. Please verify the directory setting and the permissions on the server!");
					$('#sshwrite').text("Aborted");
				}
				else if(data.message == "Write failed") {
					$('#sshconnection').text("OK");
						$('#sshlogin').text("OK");
						$('#sshchdir').text("OK");
					$('#sshwrite').text("Upload of a test-file failed! Please verify the permissions on the server!");
				}
				else {
					$('#sshconnection').text("OK");
					$('#sshlogin').text("OK");
					$('#sshchdir').text("OK");
					$('#sshwrite').text("OK");
				}
			},
		});
	}

	$("#testconn").click(function(e) {
		e.preventDefault();
		$('#sshconnection').text("");
		$('#sshlogin').text("");
		$('#sshchdir').text("");
		$('#sshwrite').text("");
		$("#custmodal").modal('show');
		testconn();
	});

	$('#testcon_close').click(function(e) {
		e.preventDefault();
                $('#sshconnection').text("");
                $('#sshlogin').text("");
                $('#sshchdir').text("");
                $('#sshwrite').text("");
                $("#custmodal").modal('hide');
	});
</script>
