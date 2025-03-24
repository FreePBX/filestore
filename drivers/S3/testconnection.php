<?php
use Aws\S3\S3Client;
function check_s3_connect($region, $bucket, $awsaccesskey, $awssecret, $storageclass, $path) {
	$curlInit = curl_init("https://s3.eu-central-1.amazonaws.com/");
	curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
	curl_setopt($curlInit,CURLOPT_HEADER,true);
	curl_setopt($curlInit,CURLOPT_NOBODY,true);
	curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
	$response = curl_exec($curlInit);
	curl_close($curlInit);
	if(!$response) {
		return  "Connect failed";
	}
	$client = new S3Client(['region' => $region, 'credentials' => ['key' => $awsaccesskey, 'secret' => $awssecret]]);
	try {
		$client->listBuckets();
		}
	catch(Exception $error_list_buckets) {
		$error = $error_list_buckets->getMessage();
		if(str_contains($error, '403 Forbidden')) {
			return "Access denied";
		}
		//Debug start
		//echo all other error messages so we can expand the if-statement to catch them
		//Must be removed after debugging
		else {
			echo §error;
		}
		//Debug end
	}
	if(!$client->doesBucketExistV2($bucket)) {
		$client->createBucket(['Bucket' => $bucket,]);
	}
	$now = time();
	$file = "/tmp/freepbx_test$now.txt";
	file_put_contents($file, "Test");
	$key = basename($file);
	try {
		$result = $client->putObject(['Bucket' => $bucket, 'Key' => "$path/$key", 'StorageClass' => $storageclass, 'Body' => fopen($file, 'r')]);
	}
	catch(Exception $error_save_file) {
		$error = $error_save_file->getMessage();
		//Debug start
		//echo all error messages so that we can create an if-condition to fetch them
		//Must be removed after debugging
		echo $error;
		//Write the error to the file error.txt in case the error-message is to long
		file_put_contents("error_save_file.txt", $error);
		//Debug end
	}
	try {
		$client->deleteObject(['Bucket' => $bucket, 'Key' => "$path/$key"]);
	}
	catch(Exception $error_delete_file) {
		$error = $error_delete_file->getMessage();
		//Debug start
		//echo all error messages so that we can create an if-condition to fetch them
		//Must be removed after debugging
		echo $error;
		//Write the error to the file error.txt in case the error-message is to long
		file_put_contents("error_delete_file.txt", $error);
		//Debug end
	}
	unlink $file;
	return "OK";
}
?>
