<?php
$disabled = (isset($readonly) && !empty($readonly))?' disabled ':'';
$id = isset($_GET['id'])?$_GET['id']:'';
include 'modal.testconnection.php';
?>
<div class="container-fluid">
	<h1><?php echo _('Dropbox Directory')?></h1>
	<div class="alert alert-info">
	<a href="https://blogs.dropbox.com/developers/2014/05/generate-an-access-token-for-your-own-account/" target="_blank"><?php echo _("Access Token Information")?></a>
	</div>
	<div class = "display full-border">
		<div class="row">
			<div class="col-sm-12">
				<div class="fpbx-container">
					<div class="display full-border">
						<form class="fpbx-submit" action="?display=filestore" method="post" id="server_form" name="server_form" data-fpbx-delete="?display=filestore&action=delete&id=<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
						<input type="hidden" name="action" value="<?php echo empty($id)?'add':'edit'?>">
						<input type="hidden" name="id" value="<?php echo $id?>">
							<input type="hidden" name="driver" value="Dropbox">
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
													<label class="control-label" for="name"><?php echo _("Account Name") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="name"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name)?$name:''?>"<?php echo $disabled?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="name-help" class="help-block fpbx-help-block"><?php echo _("Provide the name for this server")?></span>
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
													<label class="control-label" for="desc"><?php echo _("Description") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="desc"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="desc" name="desc" value="<?php echo isset($desc)?$desc:''?>"<?php echo $disabled?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="desc-help" class="help-block fpbx-help-block"><?php echo _("Description or notes for this server")?></span>
									</div>
								</div>
							</div>
							<!--END Description-->
							<!--Token-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="token"><?php echo _("Token") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="token"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="token" name="token" value="<?php echo isset($token)?$token:''?>"<?php echo $disabled?>>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="token-help" class="help-block fpbx-help-block"><?php echo _("Token on remote server")?></span>
									</div>
								</div>
							</div>
							<!--END Token-->
							<!--Path-->
							<div class="element-container">
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="path"><?php echo _("Path") ?></label>
											<i class="fa fa-question-circle fpbx-help-icon" data-for="path"></i>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="path" name="path" value="<?php echo isset($path)?$path:''?>"<?php echo $disabled?>>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="path-help" class="help-block fpbx-help-block"><?php echo _("Path on remote server")?></span>
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
<script type="text/javascript">
var immortal = <?php echo (isset($immortal) && !empty($immortal))?'true':'false';?>;
$('#server_form').on('submit', function(e) {
	let name = $("#name").val();
	let desc = $("#desc").val();
	if(name.length === 0 ) {
		warnInvalid($("#host"),_("The host cannot be empty"));
		return false;
	}
	var pattern = /^[a-zA-Z0-9_.!-&@,]*$/;
	if(!pattern.test(name)){
		warnInvalid($("#host"),_("Invalid host name"));
		return false;
	}
	pattern = /^[a-zA-Z0-9_.!-&@,()#$%*+=`~]*$/;
	if(!pattern.test(desc)){
		warnInvalid($("#host"),_("Invalid description"));
		return false;
	}
});

function testconn() {
	var req = {
		module: 'filestore',
		command: 'testconnection',
		driver: "Dropbox",
		token: $('#token').val(),
		path: $('#path').val(),
	};
	$.ajax({
		url: FreePBX.ajaxurl,
		data: req,
		success:function(data){
			if(data.message == "Connect failed") {
				$('#dropboxapiconnection').text("Connection to the Dropbox API failed. Please check network settings and that no firewall is blocking access");
				$('#dropboxtoken').text("Aborted");
				$('#dropboxpath').text("Aborted");
			}
			else if(data.message == "Token expired") {
				$('#dropboxapiconnection').text("OK");
				$('#dropboxtoken').text("Token expired");
				$('#dropboxpath').text("Aborted");
			}
			else if(data.message == "Invalid Token") {
				$('#dropboxapiconnection').text("OK");
				$('#dropboxtoken').text("Invalid Token");
				$('#dropboxpath').text("Aborted");
			}
			else if(data.message == "Path malformated") {
				$('#dropboxapiconnection').text("OK");
				$('#dropboxtoken').text("OK");
				$('#dropboxpath').text("Invalid Path!");
			}
			else if(data.message == "Unknown error") {
				$('#dropboxapiconnection').text("OK");
				$('#dropboxtoken').text("Unknown error. Please review all settings (e.g. if this token has all needed permissions");
				$('#dropboxpath').text("Aborted");
			}
			else {
				$('#dropboxapiconnection').text("OK");
				$('#dropboxtoken').text("OK");
				$('#dropboxpath').text("OK");
			}
		},
	});
}

$("#testconn").click(function(e) {
	e.preventDefault();
	$('#dropboxapiconnection').text("");
	$('#dropboxtoken').text("");
	$('#dropboxpath').text("");
	$("#custmodal").modal('show');
	testconn();
});

$('#testcon_close').click(function(e) {
	e.preventDefault();
	$('#dropboxapiconnection').text("");
	$('#dropboxtoken').text("");
	$('#dropboxpath').text("");
	$("#custmodal").modal('hide');
});
</script>
