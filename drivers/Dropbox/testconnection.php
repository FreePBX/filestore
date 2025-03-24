<?php
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
function check_dropbox_connect($token, $path) {
	//Check if the dropbox api is reachable
	$curlInit = curl_init("https://api.dropboxapi.com");
	curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
	curl_setopt($curlInit,CURLOPT_HEADER,true);
	curl_setopt($curlInit,CURLOPT_NOBODY,true);
	curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
	$response = curl_exec($curlInit);
	curl_close($curlInit);
	if(!$response) {
		return "Connect failed";
	}
	else {
		$client = new Client($token);
		$adapter = new DropboxAdapter($client, $path);
		$content = "FreePBX Filestore Test";
		try {
			$client->upload("$path"."test.txt", $content, $mode='add');
		} catch(Exception $e) {
			$error = $e->getMessage();
			if(str_contains($error, 'expired_access_token')) {
				return "Token expired";
			}
			elseif(str_contains($error, 'invalid_access_token')) {
				return "Invalid Token";
			}
			elseif(str_contains($error, 'malformed_path')) {
				return "Path malformated";
			}
			else {
				return "Unknown error";
			}
		}
		$client->delete("$path"."test.txt");
		return "OK";
	}
}
?>
