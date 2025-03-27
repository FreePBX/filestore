<?php
$disabled = (isset($readonly) && !empty($readonly)) ? ' disabled ' : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($displayname)) {
	$displayname = $bucket ?? '';
}
$fstype = isset($fstype) ? $fstype : 'auto';
include 'modal.testconnection.php';
?>
<div class="container-fluid">
	<h1><?php echo _('For Use With AWS S3') ?></h1>
	<div class="display full-border">
		<div class="row">
			<div class="col-sm-12">
				<div class="fpbx-container">
					<div class="display full-border">
						<ul class="nav nav-tabs list" role="tablist">
							<li data-name="general" class="change-tab active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo _("General") ?></a></li>
							<li data-name="advanced" class="change-tab"><a href="#advanced" aria-controls="advanced" role="tab" data-toggle="tab"><?php echo _("Advanced") ?></a></li>
						</ul>
						<form class="fpbx-submit" action="?display=filestore" method="post" id="server_form" name="server_form" data-fpbx-delete="?display=filestore&action=delete&id=<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
							<input type="hidden" name="action" value="<?php echo empty($id) ? 'add' : 'edit' ?>">
							<input type="hidden" name="id" value="<?php echo $id ?>">
							<input type="hidden" name="driver" value="S3">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="general">
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
														<input type="radio" name="enabled" id="enabledyes" value="yes" <?php echo (isset($enabled) && $enabled != "no") ? "CHECKED" : "" ?>>
														<label for="enabledyes"><?php echo _("Yes"); ?></label>
														<input type="radio" name="enabled" id="enabledno" value="no" <?php echo (!isset($enabled) || $enabled == "no") ? "CHECKED" : "" ?>>
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
									<!--Local Display Name-->
									<div class="element-container">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="name"><?php echo _("Local Display Name") ?></label>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : '' ?>" <?php echo $disabled ?>>
												</div>
											</div>
										</div>
									</div>
									<!--Bucket Name-->
									<div class="element-container">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="bucket"><?php echo _("Bucket Name") ?></label>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="bucket" name="bucket" value="<?php echo isset($bucket) ? $bucket : '' ?>" <?php echo $disabled ?>>
												</div>
											</div>
										</div>
									</div>
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
									<!--AWS Region-->
									<div class="element-container">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="region"><?php echo _("AWS Region") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="region"></i>
												</div>
												<div class="col-md-9">
													<select class="form-control" id="region" name="region">
														<?php
														foreach ($regions as $value => $key) {
															$selected = (isset($region) && $key == $region) ? 'SELECTED' : '';
															echo '<option value = "' . $key . '" ' . $selected . '>' . $value . ' [' . $key . ']</option>';
														}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<span id="region-help" class="help-block fpbx-help-block"><?php echo _("Region provided in AWS") ?></span>
											</div>
										</div>
									</div>
									<!--END AWS Region-->
									<!--AWS Access Key-->
									<div class="element-container">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="awsaccesskey"><?php echo _("AWS Access Key") ?></label>
												</div>
												<div class="col-md-9">
													<div class="input-group">
														<input type="text" class="form-control" id="awsaccesskey" name="awsaccesskey" value="<?php echo isset($awsaccesskey) ? $awsaccesskey : '' ?>" <?php echo $disabled ?>>
														<span class="input-group-addon" id="awskeyaddon"><a href="http://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSGettingStartedGuide/AWSCredentials.html" target="_blank"><?php echo _("What's this?") ?></a></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--END AWS Access Key-->
									<!--AWS Secret-->
									<div class="element-container">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="awssecret"><?php echo _("AWS Secret") ?></label>
												</div>
												<div class="col-md-9">
													<div class="input-group">
														<input type="text" class="form-control" id="awssecret" name="awssecret" value="<?php echo isset($awssecret) ? $awssecret : '' ?>" <?php echo $disabled ?>>
														<span class="input-group-addon" id="awssecretaddon"><a href="http://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSGettingStartedGuide/AWSCredentials.html" target="_blank"><?php echo _("What's this?") ?></a></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--END AWS Secret-->
									<!--Storage Class-->
									<div class="element-container">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="storageclass"><?php echo _("Storage Class") ?></label>
												</div>
												<div class="col-md-9">
													<div class="input-group">
														<select class="form-control" id="storageclass" name="storageclass">
															<?php
															foreach ($storageclasses as $value => $key) {
																$selected = (isset($storageclass) && ($key == $storageclass)) ? 'SELECTED' : '';
													    		echo '<option value = "' . $key . '" ' . $selected . '>' . $value . '</option>';
															}
															?>
														</select>
														<span class="input-group-addon" id="storageclassaddon"><a href="https://aws.amazon.com/s3/storage-classes/" target="_blank"><?php echo _("What's this?") ?></a></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--END Storage Class-->
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
												<span id="path-help" class="help-block fpbx-help-block"><?php echo _("Path on remote server") ?></span>
											</div>
										</div>
									</div>
									<!--END Path-->
									<br>
									<div class="element-container">
										<div class="row">
											<div class="col-md-12">
												<button type='button' class='btn btn-default pull-right' id='testconn'><?php echo _("Test Connection Settings"); ?></button>
											</div>
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane" id="advanced">
									<div class="panel panel-warning">
										<div class="panel-heading">
											<h3 class="panel-title"><?php echo _("Danger") ?></h3>
										</div>
										<div class="panel-body">
											<?php echo _("Settings here will override settings in the general tab. Only make changes here if you know what you are doing. These settings are not validated and may break functionality!"); ?>
										</div>
									</div>
									<!--Custom Endpoint-->
									<div class="element-container">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="customendpoint"><?php echo _("Custom Endpoint") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="customendpoint"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="customendpoint" name="customendpoint" value="<?php echo $customendpoint ?? '' ?>">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<span id="customendpoint-help" class="help-block fpbx-help-block"><?php echo _("Custom S3 compatable endpoint") ?></span>
											</div>
										</div>
									</div>
									<!--END Custom Endpoint-->
									<!--Custom Region-->
									<div class="element-container">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="customregion"><?php echo _("Custom Region") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="customregion"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="customregion" name="customregion" value="<?php echo $customregion ?? '' ?>">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<span id="customregion-help" class="help-block fpbx-help-block"><?php echo _("Custom Region") ?></span>
											</div>
										</div>
									</div>
									<!--END Custom Region-->
								</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<p class="pull-right"> <a href="http://aws.amazon.com" target='_blank'>AWS&trade; and S3&trade; are trademarks of Amazon Web Services Inc</a></p>
</div>
<script type="text/javascript">
	var immortal = <?php echo (isset($immortal) && !empty($immortal)) ? 'true' : 'false'; ?>;
	$('#server_form').on('submit', function(e) {
		if ($("#bucket").val().length === 0) {
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
			driver: "S3",
			bucket:  $('#bucket').val(),
			region:  $('#region').val(),
			awsaccesskey: $('#awsaccesskey').val(),
			awssecret: $('#awssecret').val(),
			storageclass: $('#storageclass').val(),
			path: $('#path').val(),
        };
		$.ajax({
			url: FreePBX.ajaxurl,
			data: req,
			success:function(data){
				if(data.message == "Connect failed") {
					$('#awsapiconnection').text("Connection to the AWS API failed. Please check network settings and that no firewall is blocking access");
					$('#awscredentials').text("Aborted");
					$('#awswrite').text("Aborted");
				}
				else if(data.message == "Access denied") {
					$('#awsapiconnection').text("OK");
					$('#awscredentials').text("Login failed. Please check AWS Access key and AWS secret.");
					$('#awswrite').text("Aborted");
				}
				else if(data.message == "Invalid StorageClass") {
					$('#awsapiconnection').text("OK");
					$('#awscredentials').text("OK");
					$('#awswrite').text("Write failed. Error message: Invalid Storage Class.");
				}
				else {
					$('#awsapiconnection').text("OK");
					$('#awscredentials').text("OK");
					$('#awswrite').text("OK");
				}
			},
		});
	}

	$("#testconn").click(function(e) {
		e.preventDefault();
		$('#awsapiconnection').text("");
		$('#awscredentials').text("");
		$('#awswrite').text("");
		$("#custmodal").modal('show');
		testconn();
	});

	$('#testcon_close').click(function(e) {
		e.preventDefault();
		$('#awsapiconnection').text("");
		$('#awscredentials').text("");
		$('#awswrite').text("");
		$("#custmodal").modal('hide');
	});

</script>
