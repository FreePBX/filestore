<?php

namespace FreePBX\modules\filestore\Api\Gql;

use GraphQLRelay\Relay;
use GraphQL\Type\Definition\Type;
use FreePBX\modules\Api\Gql\Base;


class Filestore extends Base {
	protected $module = 'filestore';
		
	/**
	 * mutationCallback
	 *
	 * @return void
	 */
	public function mutationCallback() {
		if($this->checkAllWriteScope()) {
			return function() {
				return [
					'addFTPInstance' => Relay::mutationWithClientMutationId([
						'name' => 'addFTPInstance',
						'description' => _('Add a new FTP instance'),
						'inputFields' => $this->getFTPInputFields(),
						'outputFields' => $this->getOutputFields(),
						'mutateAndGetPayload' => function ($input) {
							$input = $this->resolveFTPNames($input);
							return $this->addInstance('FTP',$input);
						}
					]),
					'addS3Bucket' => Relay::mutationWithClientMutationId([
						'name' => 'addS3Bucket',
						'description' => _('Add a new AWS S3 Bucket'),
						'inputFields' => $this->getS3InputFields(),
						'outputFields' => $this->getOutputFields(),
						'mutateAndGetPayload' => function ($input) {
							$input = $this->resolveS3Names($input);
							return $this->addInstance('S3',$input);
						}
					]),
					'updateFTPInstance' => Relay::mutationWithClientMutationId([
						'name' => 'updateFTPInstance',
						'description' => _('update a existing FTP instance'),
						'inputFields' => $this->getFTPUpdateFields(),
						'outputFields' => $this->getOutputFields(),
						'mutateAndGetPayload' => function ($input) {
							$input = $this->FTPUpdateFields($input);
							return $this->updateInstance($input,'FTP');
						}
					]),
					'deleteFTPInstance' => Relay::mutationWithClientMutationId([
						'name' => 'deleteFTPInstance',
						'description' => _('Delete a existing FTP instance'),
						'inputFields' => [
							'id' => [
								'type' => Type::nonNull(Type::string())
							]
						],
						'outputFields' => $this->getOutputFields(),
						'mutateAndGetPayload' => function ($input) {
							$id = ltrim($input['id'],'FTP_');
							$response = $this->freepbx->filestore->deleteItem($id);
							if(!$response){
								return ['message' => _("Successfully deleted FTP instance"), 'status'=> true];
							}else{
								return ['message' => _("Sorry, unable to process your delete request"),'status' => false];
							}
						}
					]),
				];
			};
		}
	}
	
	/**
	 * queryCallback
	 *
	 * @return void
	 */
	public function queryCallback() {
		if($this->checkAllReadScope()) {
			return function() {
				return [
					'fetchFilestoreTypes' => [
						'type' => $this->typeContainer->get('filestore')->getConnectionType(),
						'resolve' => function($root, $args) {
							$res = $this->freepbx->filestore->listLocations();
							if(!empty($res)){
								return ['message' => _("List of filestore types"), 'status' => true, 'response' => $res['filestoreTypes']];
							}else{
								return ['message' => _('Sorry unable to find the filestore types'), 'status' => false];
							}
						}
					],
					'fetchFilestoreLocations' => [
						'type' => $this->typeContainer->get('filestore')->getConnectionType(),
						'resolve' => function($root, $args) {
							$res = $this->freepbx->filestore->listLocations();
							if(!empty($res)){
								return ['message' => _("List of filestore locations"), 'status' => true, 'response' => $res['locations']];
							}else{
								return ['message' => _('Sorry unable to find the filestore locations'), 'status' => false];
							}
						}
					],
					'fetchAWSRegion' => [
						'type' => $this->typeContainer->get('filestore')->getConnectionType(),
						'resolve' => function($root, $args) {
							$res = $this->freepbx->filestore->getDisplay('S3');
							$regions = [
								'us-east-2',
								'us-east-1',
								'us-gov-east-1',
								'us-west-1',
								'us-west-2',
								'us-gov-west-1',
								'ca-central-1',
								'ap-south-1',
								'ap-northeast-3',
								'ap-northeast-2',
								'ap-southeast-1',
								'ap-southeast-2',
								'ap-northeast-1',
								'cn-north-1',
								'cn-northwest-1',
								'eu-central-1',
								'eu-west-1',
								'eu-west-2',
								'eu-west-3',
								'eu-north-1',
								'sa-east-1',
							];
							return ['message' => _("List of AWS Storage"), 'status' => true, 'regions' => json_encode($regions)];
						}
					],
					'fetchAllFilestores' => [
						'type' => $this->typeContainer->get('filestore')->getConnectionType(),
						'args' => Relay::connectionArgs(),
						'resolve' => function($root, $args) {
							$res = $this->freepbx->filestore->listLocations();
							$resultData = array();
							foreach ($res['locations'] as $key => $locations) {
								foreach ($locations as $location) {
									$resultData[] = [
										"id" =>  isset($location['id']) ? $key."_".$location['id'] : "",
										"name"=> $location['name'],
										"description"=> $location['description'],
										"filestoreType"=> $key,
									];
								}
							}
							if(isset($resultData) && $resultData != null){
								return ['message'=> _("List of all filestores"), 'response'=> $resultData,'status'=>true];
							}else{
								return ['message'=> _("Sorry, unable to find any filestore"),'status' => false];
							}
						}
					],
					'fetchFileStoreDetails' => [
						'type' => $this->typeContainer->get('filestore')->getConnectionType(),
						'args' => [
							'id' => [
								'type' => Type::nonNull(Type::string()),
								'description' => _('The id to fetch a particualr filestore details'),
							]
						],
						'resolve' => function($root, $args) {
						   $id = ltrim($args['id'],'FTP_');
							$res = $this->freepbx->filestore->getItemById($id);
							if(!empty($res)){
								return ['response' => $res, 'status' => true, 'message' => _('FTP instance found successfully')];
							}else{
								return ['status' => false, 'message' => _('FTP instance does not exists')];
							}
						}
					],
            ];
			};
	   }
	}
	
	/**
	 * getFTPInputFields
	 *
	 * @return void
	 */
	private function getFTPInputFields() {
		return [
			'enabled' => [
				'type' => Type::boolean(),
				'description' => _('Enabled FTP.')
			],
			'serverName' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Enter the name for FTP connection')
			],
			'hostName' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Enter the name for FTP Host')
			],
			'description' => [
				'type' => Type::string(),
				'description' => _('Enter a description for this conncetion.')
			],
			'port' => [
				'type' => Type::id(),
				'description' => _('FTP Port default is "22"')
			],
			'userName' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Set user name')
			],
			'password' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Set the password')
			],
			'fileStoreType' => [
				'type' => Type::string(),
				'description' => _('The FTP Servers file system type. If you are unsure set this to Auto')
			],
			'path' => [
				'type' => Type::string(),
				'description' => _('Path on remote server. This must be a COMPLETE PATH, starting with a / - for example, /home/backups/freepbx. A path without a leading slash will not work, and will behave in unexpected ways.')
			],
			'transferMode' => [
				'type' => Type::string(),
				'description' => _('This defaults to "Passive". If your FTP server is behind a seperate NAT or Firewall to this VoIP server, you should select "Active". In "Active" mode, the FTP server establishes a connection back to the VoIP server to receive the data. In "Passive" mode, the VoIP server connects to the FTP Server to send data.')
			],
			'timeout' => [
				'type' => Type::id(),
				'description' => _('Timeout on remote server, default is 30')
			]
		];
	}
		
	/**
	 * getFTPUpdateFields
	 *
	 * @return void
	 */
	private function getFTPUpdateFields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Enter the ID for FTP')
			],
			'enabled' => [
				'type' => Type::boolean(),
				'description' => _('Enabled FTP.')
			],
			'serverName' => [
				'type' => Type::string(),
				'description' => _('Enter the name for FTP connection')
			],
			'hostName' => [
				'type' => Type::string(),
				'description' => _('Enter the name for FTP Host')
			],
			'description' => [
				'type' => Type::string(),
				'description' => _('Enter a description for this conncetion.')
			],
			'port' => [
				'type' => Type::id(),
				'description' => _('FTP Port default is "22"')
			],
			'userName' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Set user name')
			],
			'password' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Set the password')
			],
			'fileStoreType' => [
				'type' => Type::string(),
				'description' => _('The FTP Servers file system type. If you are unsure set this to Auto')
			],
			'path' => [
				'type' => Type::string(),
				'description' => _('Path on remote server. This must be a COMPLETE PATH, starting with a / - for example, /home/backups/freepbx. A path without a leading slash will not work, and will behave in unexpected ways.')
			],
			'transferMode' => [
				'type' => Type::string(),
				'description' => _('This defaults to "Passive". If your FTP server is behind a seperate NAT or Firewall to this VoIP server, you should select "Active". In "Active" mode, the FTP server establishes a connection back to the VoIP server to receive the data. In "Passive" mode, the VoIP server connects to the FTP Server to send data.')
			],
			'timeout' => [
				'type' => Type::id(),
				'description' => _('Timeout on remote server, default is 30')
			]
		];
	}

	/**
	 * getS3InputFields
	 *
	 * @return void
	 */
	private function getS3InputFields() {
		return [
			'name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Local Display Name')
			],
			'bucketName' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('AWS bucket name')
			],
			'description' => [
				'type' => Type::string(),
				'description' => _('Description of the AWS S3')
			],
			'AWSRegion' => [
				'type' => Type::nonNull(Type::String()),
				'description' => _('AWS hosting Region')
			],
			'AWSAccessKey' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Set aws access key')
			],
			'AWSSecret' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Set the password')
			],
			'path' => [
				'type' => Type::string(),
				'description' => _('Path on remote server')
			]
		];
	}
	
	/**
	 * getOutputFields
	 *
	 * @return void
	 */
	private function getOutputFields(){
		return [
			'status' => [
				'type' => Type::boolean(),
				'resolve' => function ($payload) {
					return $payload['status'];
				}
			],
			'message' => [
				'type' => Type::string(),
				'resolve' => function ($payload) {
				return $payload['message'];
				}
			],
			'id' =>[
				'type' => Type::String(),
				'description' => _('UUid for the instance'),
			],
		];
	}
	
	/**
	 * initializeTypes
	 *
	 * @return void
	 */
	public function initializeTypes() {
		$filestore = $this->typeContainer->create('filestore');
		$filestore->setDescription(_(''));

		$filestore->addInterfaceCallback(function() {
			return [$this->getNodeDefinition()['nodeInterface']];
		});

	$filestore->addFieldCallback(function() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::Id()),
				'description' => _('Returns filestore id'),
			],
			'status' =>[
				'type' => Type::boolean(),
				'description' => _('Status of the request'),
			],
			'message' =>[
				'type' => Type::String(),
				'description' => _('Message for the request')
			],
			'name' => [
				'type' => Type::string(),
				'description' => _('Returns the filestore name'),
			],
			'description' => [
				'type' => Type::string(),
				'description' => _('Returns the filestore description'),
			],	
			'filestoreType' => [
				'type' => Type::string(),
				'description' => _('List the filestore type'),
			],
		];
	});

	$filestore->setConnectionFields(function() {
		return [
			'message' =>[
				'type' => Type::string(),
				'description' => _('Message for the request')
			],
			'status' =>[
				'type' => Type::boolean(),
				'description' => _('Status for the request')
			],
			'types' =>[
				'type' =>  Type::listOf(Type::String()),
				'description' => _('Types of filestore'),
				'resolve' =>  function($root, $args) {
					$data = array_map(function($row){
						return $row;
					},isset($root['response']) ? $root['response'] : []);
						return $data;
				}
			],
			'regions' => [
				'type' => Type::String(),
				'description' => _('List of regions')
			],
			'locations' => [
				'type' => Type::listOf(Type::String()),
				'description' => _('List of filestore locations'),
				'resolve' => function($root, $args) {
					$data = array_map(function($row){
						dbug($row);
						return $row;
					},isset($root['response']) ? $root['response'] : []);
					$finalList = array();
					foreach($data as $key => $value){
						foreach($value as $val){
							array_push($finalList,$key.'_'.$val['id']);
						}
					}
					return $finalList;
				}
			],
			'filestores' => [
				'type' => Type::listOf($this->typeContainer->get('filestore')->getObject()),
				'description' => _('List of filestores'),
				'resolve' => function($root, $args) {
					$data = array_map(function($row){
						return $row;
					},isset($root['response']) ? $root['response'] : []);
					return $data;
				}
			],
			'serverName' => [
				'type' => Type::string(),
				'description' => _('Name for FTP connection'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['name'] : '';
				}
			],
			'hostName' => [
				'type' => Type::string(),
				'description' => _('Hostname for FTP Host'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['host'] : '';
				}
			],
			'description' => [
				'type' => Type::string(),
				'description' => _('Description for this conncetion.'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['desc'] : '';
				}
			],
			'port' => [
				'type' => Type::id(),
				'description' => _('FTP Port'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['port'] : '';
				}
			],
			'userName' => [
				'type' => Type::string(),
				'description' => _('Set user name'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['user'] : '';
				}
			],
			'password' => [
				'type' => Type::nonNull(Type::string()),
				'description' => _('Set the password'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['password'] : '';
				}
			],
			'fileStoreType' => [
				'type' => Type::string(),
				'description' => _('The FTP Servers file system type. If you are unsure set this to Auto'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['fstype'] : '';
				}
			],
			'path' => [
				'type' => Type::string(),
				'description' => _('Path on remote server. This must be a COMPLETE PATH, starting with a / - for example, /home/backups/freepbx. A path without a leading slash will not work, and will behave in unexpected ways.'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['path'] : '';
				}
			],
			'transferMode' => [
				'type' => Type::string(),
				'description' => _('This defaults to "Passive". If your FTP server is behind a seperate NAT or Firewall to this VoIP server, you should select "Active". In "Active" mode, the FTP server establishes a connection back to the VoIP server to receive the data. In "Passive" mode, the VoIP server connects to the FTP Server to send data.'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['transfer'] : '';
				}
			],
			'timeout' => [
				'type' => Type::id(),
				'description' => _('Timeout on remote server, default is 30'),
				'resolve' => function($root, $args) {
					return isset($root['response']) ? $root['response']['timeout'] : '';
				}
			]
		];
	});
}
	
	/**
	 * addInstance
	 *
	 * @return void
	 */
	private function addInstance($driver,$values){
		$res = $this->freepbx->filestore->addItem($driver,$values);;
		if($res){
			return ['message' => _($driver." Instance is created successfully"), 'status'=> true, 'id' => $res];
		}else{
			return ['message' => _("Sorry unable to create ".$driver." Instance"), 'status' => false];
		}
	}
	
	/**
	 * resolveFTPNames
	 *
	 * @param  mixed $input
	 * @return void
	 */
	private function resolveFTPNames($input){
		$input["enabled"] = (((!isset($input["enabled"])) || (isset($input["enabled"]) && $input["enabled"] === true)) ? "yes" : "no");
		$input['name']  = $input['serverName'];
		$input['host']  = $input['hostName'];
		$input['user']  = $input['userName'];
		$input['desc']  = isset($input['description']) ? $input['description'] : '';
		$input['port']  = isset($input['port']) ? $input['port'] : 21;
		$input['path']  = isset($input['path']) ? $input['path'] : '/';
		$input['transfer']  = isset($input['transfer']) ? $input['transfer'] : 'passive';
		$input['timeout']  = isset($input['timeout']) ? $input['timeout'] : 30;
		return $input;
	}
	
	/**
	 * resolveS3Names
	 *
	 * @param  mixed $input
	 * @return void
	 */
	private function resolveS3Names($input){
		$input['bucket']  = $input['bucketName'];
		$input['desc']  = isset($input['description']) ? $input['description'] : '';
		$input['region']  = $input['AWSRegion'];
		$input['path']  = isset($input['path']) ? $input['path'] : '/';
		$input['awsaccesskey']  = $input['AWSAccessKey'];
		$input['awssecret']  = $input['AWSSecret'];

		return $input;
	}
	
	/**
	 * FTPUpdateFields
	 *
	 * @param  mixed $input
	 * @return void
	 */
	private function FTPUpdateFields($input){
		$input['id'] = ltrim($input['id'],'FTP_');
		$res = $this->freepbx->filestore->getItemById($input['id']);
		$input["enabled"] = (((!isset($input["enabled"])) || (isset($input["enabled"]) && $input["enabled"] === true)) ? "yes" : "no");
		$input['name']  = isset($input['serverName']) ? $input['serverName'] : $res['name'];
		$input['host']  = isset($input['hostName']) ? $input['hostName'] : $res['host'];
		$input['user']  = isset($input['userName']) ? $input['userName'] : $res['user'];
		$input['desc']  = isset($input['description']) ? $input['description'] : $res['desc'];
		$input['port']  = isset($input['port']) ? $input['port'] : $res['port'];
		$input['path']  = isset($input['path']) ? $input['path'] : $res['path'];
		$input['transfer']  = isset($input['transferMode']) ? $input['transferMode'] : $res['transfer'];
		$input['timeout']  = isset($input['timeout']) ? $input['timeout'] : $res['timeout'];
		$input['driver'] = 'FTP';

		return $input;
	}
	
	/**
	 * updateInstance
	 *
	 * @param  mixed $id
	 * @param  mixed $inputs
	 * @return void
	 */
	private function updateInstance($inputs,$type){
		$id = $inputs['id'];
		$res = $this->freepbx->filestore->editItem($id,$inputs);
		if($res){
			return ['message' => _($type . '_' .$id." Instance is updated successfully"), 'status'=> true];
		}else{
			return ['message' => _("Sorry unable to update " . $type . '_' .$id." Instance"), 'status' => false];
		}
	}
}
