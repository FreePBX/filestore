<?php
namespace FreePBX\modules\Filestore;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{
        private function updateEnabledKeys(&$array) {
                foreach ($array as &$value) {
                        if (is_array($value)) {
                                $this->updateEnabledKeys($value); // Recursively check nested arrays
                        } else if (!isset($array['enabled']) || empty($array['enabled'])) {
                                $array['enabled'] = "yes"; // Set 'enabled' to 'yes' if empty
                        }
                }
        }
            
        public function runRestore(){
                $settings = $this->getConfigs();
                $clearkvstore = "truncate table kvstore_FreePBX_modules_Filestore";
                $this->FreePBX->Database->query($clearkvstore);
                $this->updateEnabledKeys($settings);
                $this->importKVStore($settings);
        }
	public function processLegacy($pdo, $data, $tables, $unknownTables){
                $this->log('Restoring only Legacy Backup FTP Servers to Filestore');
                $this->RestoreLegacyFtpFilestore($pdo);
        }

        private function RestoreLegacyFtpFilestore($pdo){
                // Check if table exists
                $serverTableExists = $this->FreePBX->Filestore->checkTableExists('backup_servers');
                $detailsTableExists = $this->FreePBX->Filestore->checkTableExists('backup_server_details');
                if ($serverTableExists && $detailsTableExists) {
                        //backup_server,backup_server_details
                        $bkserver = "SELECT id,name,desc FROM backup_servers WHERE type='ftp'";
                        $sth = $pdo->query($bkserver,\PDO::FETCH_ASSOC);
                        $serversar = $sth->fetchAll();
                        $tableExists = $this->tableExists($pdo, 'backup_server_details');
                        if(!empty($serversar) && $tableExists) {
                                foreach($serversar as $ser){
                                        $server = ['id'=>'','action'=>'add','timeout'=>30,'name'=>$ser['name'],'desc'=>$ser['desc'],'driver'=>'FTP'];
                                        $bkserverd = "SELECT key,value FROM backup_server_details WHERE server_id='".$ser['id']."'";
                                        $sth = $pdo->query($bkserverd,\PDO::FETCH_ASSOC);
                                        $res = $sth->fetchAll();
                                        foreach($res as $row) {
                                                $server[$row['key']] = $row['value'];
                                        }
                                        $this->FreePBX->Filestore->addItem('FTP',$server);
                                }
                        }
                }
        }
}
