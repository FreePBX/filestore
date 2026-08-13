<?php
namespace FreePBX\modules\Filestore\drivers;

abstract class DriverBase{
	protected $databasekey;
	protected $config = [];
	protected $FreePBX = null;
	protected $db = null;
	protected static $validKeys = [
		'enabled' => 'yes',
	];
	protected static $path = __DIR__;

	public function __construct($freepbx, $config=[]){
		$this->FreePBX = $freepbx;
		$this->db =  $freepbx->Database;
		foreach(static::$validKeys as $key => $value) {
			$this->config[$key] = (isset($config[$key])) ? $config[$key] : $value;
		}
	}

	protected function translatePath($path){
		if(preg_match("/(.*)__(.*)__(.*)/", $path, $matches) !== 1){
				return $path;
		}
		$var = $this->FreePBX->Config->get($matches[2]);
		if($var === false){
			return $path;
		}
		return $matches[1].$var.$matches[3];
	}

	//Base actions
	/**
	 * Authentication action. This will process things like oauth flows
	 * @param  array $request http request
	 * @return bool action success/fail
	 */
	public static function authAction($freepbx) {

	}

	/**
	 * Run on install/update
	 * @return null
	 */
	public static function install($freepbx) {
		return true;
	}

	/**
	 * Run on module uninstall
	 * @return null
	 */
	public static function uninstall($freepbx) {
		return true;
	}

	public static function filterConfig($freepbx, $config) {
		$tmp = [];
		foreach(static::$validKeys as $key => $value) {
			$tmp[$key] = (isset($config[$key])) ? $config[$key] : '';
		}
		return $tmp;
	}

	/**
	 * Get the configuration display of the authentication driver
	 * @param  object $userman The userman object
	 * @param  object $freepbx The FreePBX BMO object
	 * @return string          html display data
	 */
	public static function getDisplay($freepbx, $config) {
		if(empty($_GET['view'])){
			return load_view(static::$path.'/views/grid.php');
		}else{
			$config = !empty($config) ? $config : static::$validKeys;
			return load_view(static::$path.'/views/form.php',$config);
		}
	}

	public static function getActionBar() {
		if(!isset($_GET['view']) || $_GET['view'] != 'form'){
			return array();
		}
		$buttons = array(
			'delete' => array(
				'name' => 'delete',
				'id' => 'delete',
				'value' => _('Delete')
			),
			'reset' => array(
				'name' => 'reset',
				'id' => 'reset',
				'value' => _('Reset')
			),
			'submit' => array(
				'name' => 'submit',
				'id' => 'submit',
				'value' => _('Submit')
			)
		);
		if (empty($_REQUEST['id'])) {
			unset($buttons['delete']);
		}
		return $buttons;
	}

	/**
	 * Whether an implementation is supported in this driver
	 * @param  string $method the method "all,backuponly,readonly,writeonly"
	 * @return bool method is/not supported
	 */
	public function methodSupported($method) {
		return false;
	}

	/**
	 * Download file from location
	 *
	 * @param string $remote The remote file
	 * @param string $local The local file
	 * @return void
	 */
	public function download($remote, $local) {
		$resource = fopen($local, 'w');
		// Copy our read stream to the write stream
		stream_copy_to_stream($this->readStream($remote), $resource);
		fclose($resource);
	}

	/**
	 * Upload file to location
	 *
	 * @param string $local The remote file
	 * @param string $remote The local file
	 * @return void
	 */
	public function upload($local, $remote) {
		$resource = fopen($local, 'rb');
		$this->putStream($remote,$resource);
	}

	public function setEmailOptions($mailOptions) {
		$this->setEmailOptions($mailOptions);
	}
	
	//Filestore Actions
	/**
	 * Read files
	 * @param  string $path location of a file
	 * @return string file contents
	 */
	public function read($path) {
		throw new \Exception("read method is not implemented!");
	}

	/**
	 * Read files as a stream
	 * @param  string $path location of a file
	 * @return resource file stream
	 */
	public function readStream($path) {
		throw new \Exception("readStream method is not implemented!");
	}

	/**
	 * Write or Update Files
	 * @param string $path location of a file
	 * @param string $contents file contents
	 * @return bool success boolean
	 */
	public function put($path,$contents) {
		throw new \Exception("put method is not implemented!");
	}

	/**
	 * Write or Update Files using a stream
	 * @param string $path location of a file
	 * @param resource $resource file stream
	 * @return bool success boolean
	 */
	public function putStream($path,$resource) {
		throw new \Exception("putStream method is not implemented!");
	}

	/**
	 * List contents of a directory.
	 *
	 * @param string $directory
	 * @param bool   $recursive
	 *
	 * @return array
	 */
	public function listContents($directory = '', $recursive = false) {
		throw new \Exception("listContents method is not implemented!");
	}

	/**
	 * Delete Files
	 * @param  string  $path   location of a file
	 * @return boolean            success boolean
	 */
	public function delete($path) {
		throw new \Exception("delete method is not implemented!");
	}

	/**
	 * Rename files
	 * @param  string $from location of a file
	 * @param  string $to new location
	 * @return bool  was the operation successful
	 */
	public function rename($from, $to) {
		throw new \Exception("rename method is not implemented!");
	}

	/**
	 * Copy files
	 * @param  string $from location of a file
	 * @param  string $to new location
	 * @return bool  was the operation successful
	 */
	public function copy($from, $to) {
		throw new \Exception("copy method is not implemented!");
	}

	/**
	 * Get Mimetypes
	 *
	 * @param string $path location of a file
	 * @return string mime-type
	 */
	public function getMimetype($path) {
		throw new \Exception("getMimetype method is not implemented!");
	}

	/**
	 * Get Timestamps
	 *
	 * This function returns the last updated timestamp.
	 *
	 * @param string  $path location of a file
	 * @return integer timestamp of modification
	 */
	public function getTimestamps($path) {
		throw new \Exception("getTimestampes method is not implemented!");
	}

	/**
	 * Get File Sizes
	 *
	 * @param string  $path location of a file
	 * @return integer size of a file
	 */
	public function getSize($path) {
		throw new \Exception("getSize method is not implemented!");
	}

	/**
	 * Create Directories
	 *
	 * Directories are also made implicitly when writing to a deeper path.
	 * In general creating a directory is not required in order to write to it.
	 *
	 * @param string $path location of a file
	 * @return bool  was the operation successful
	 */
	public function createDir($path) {
		throw new \Exception("createDir method is not implemented!");
	}

	/**
	 * Delete Directories
	 *
	 * Deleting directories is always done recursively.
	 *
	 * @param string $path location of a file
	 * @return bool  was the operation successful
	 */
	public function deleteDir($path) {
		throw new \Exception("deleteDir method is not implemented!");
	}

	/**
	 * Check if a file exists
	 *
	 * This only has consistent behaviour for files, not directories.
	 * Directories are less important in Flysystem, they’re created
	 * implicitly and often ignored because not every adapter (filesystem type)
	 * supports directories.
	 *
	 * @param string $path location of a file
	 * @return bool whether the file exists
	 */
	public function fileExists($path){
		throw new \Exception("fileExists method is not implemented!");
	}
	
	public function isEnabled(){
		return !empty($this->config['enabled']) && $this->config['enabled'] == "no"  ? false : true;
	}
}
