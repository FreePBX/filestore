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
		if(str_contains($error, 'InvalidStorageClass')) {
			return "Invalid StorageClass";
		}
	}
	try {
		$client->deleteObject(['Bucket' => $bucket, 'Key' => "$path/$key"]);
	}
	catch(Exception $error_delete_file) {
		//Should never happen
		$error = $error_delete_file->getMessage();
		return $error;
	}
	unlink($file);
	return "OK";
}
?>
