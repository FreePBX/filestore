<div class='modal fade' id='custmodal'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h3 id='mheader' class="mr-auto">Test Connection settings</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class='modal-body'>
                <div class="row">
                    <div class="col-sm-12">
                        Checking Connection settings...<br><br><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-3 control-label">
                        AWS API Connection:
                    </div>
                    <div class="col-sm-12 col-lg-9" id="awsapiconnection">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-3 control-label">
                        AWS Credentials:
                    </div>
                    <div class="col-sm-12 col-lg-9" id="awscredentials">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-3 control-label">
                        S3 Write Test:
                    </div>
                    <div class="col-sm-12 col-lg-9" id="awswrite">
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button type="button" class="btn btn-primary" id='testcon_close'><?php echo _("Close"); ?></button>
            </div>
        </div>
    </div>
</div>
