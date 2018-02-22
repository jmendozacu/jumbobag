<?php

class LMB_EDI_DebugController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $ioAdapter = new Varien_Io_File();
        try {
            // Create temporary directory for api
            $dir1 = Mage::getBaseDir('var') . "/log/lmbedi/";
            $dir2 = Mage::getBaseDir('var') . "/log/lmbedi/pid/";
            $ioAdapter->checkAndCreateFolder($dir1);
            $ioAdapter->checkAndCreateFolder($dir2);
        }catch (Exception $e) {
            print_r($e);
            Mage::logException($e);
            exit;
        }
        
        $pid_s = new LMB_EDI_Model_PID("stop");

        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        if($this->getRequest()->getParam("purge_events", false)) {
            $write->raw_query("TRUNCATE TABLE `".Mage::getConfig()->getTablePrefix()."edi_events_queue`");
        }
        if($this->getRequest()->getParam("purge_mess_recu", false)) {
            $write->raw_query("TRUNCATE TABLE `".Mage::getConfig()->getTablePrefix()."edi_messages_recu_queue`");
        }
        if($this->getRequest()->getParam("purge_mess_envoi", false)) {
            $write->raw_query("TRUNCATE TABLE `".Mage::getConfig()->getTablePrefix()."edi_messages_envoi_queue`");
        }
        if($pid = $this->getRequest()->getParam("unlock_pid", false)) {
            //unlink(Mage::getBaseDir('var')."/log/lmbedi/pid/".$pid);
            $pid_o = new LMB_EDI_Model_PID($pid);
            $pid_o->unset_pid();
        }
        if($this->getRequest()->getParam("clear_logs", false)) {
            $log_dir = opendir(Mage::getBaseDir('var')."/log/");
            while($file = readdir($log_dir)) {
                $index = strpos(strtolower($file), "lmb_edi");
                if ($index !== 0) {
                    continue;
                }
                if (substr($file, -4) != ".log") {
                    continue;
                }

                if(is_file(Mage::getBaseDir('var')."/log/".$file) && $file != "." && $file != ".." && strpos($file,".")!==0) {
                    unlink(Mage::getBaseDir('var')."/log/".$file);
                }
            }
        }
        if($this->getRequest()->getParam("stop_process") !== null){
            if($this->getRequest()->getParam("stop_process"))
                //touch(Mage::getBaseDir('var')."/log/lmbedi/pid/stop");
                $pid_s->set_pid();
            else if(LMB_EDI_Model_PID::forceStop())
                //unlink(Mage::getBaseDir('var')."/log/lmbedi/pid/stop");
                $pid_s->delete_pid();
        }
        if($this->getRequest()->getParam("start_process", false)) {
            LMB_EDI_Model_EDI::newProcess("process/start/messages_envoi", LMB_EDI_Model_Liaison_MessageEnvoi::getProcess());
            LMB_EDI_Model_EDI::newProcess("process/start/events", LMB_EDI_Model_Liaison_Event::getProcess());
            LMB_EDI_Model_EDI::newProcess("process/start/messages_recu", LMB_EDI_Model_Liaison_MessageRecu::getProcess());
        }
        if($this->getRequest()->getParam("change_etat") !== null){
            $table_name = false;
            switch($this->getRequest()->getParam("change_etat")){
                case LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()->getName():
                    $table_name = Mage::getConfig()->getTablePrefix()."edi_messages_envoi_queue";
                    break;
                case LMB_EDI_Model_Liaison_MessageRecu::getProcess()->getName():
                    $table_name = Mage::getConfig()->getTablePrefix()."edi_messages_recu_queue";
                    break;
                case LMB_EDI_Model_Liaison_Event::getProcess()->getName():
                    $table_name = Mage::getConfig()->getTablePrefix()."edi_events_queue";
                    break;
            }
            if($table_name){
                $query = "UPDATE $table_name SET etat='".$this->getRequest()->getParam("etat_mess")."'
                            WHERE id='".$this->getRequest()->getParam("id_mess")."'";
                $write->raw_query($query);
            }
        }
        $params = $this->getRequest()->getParams();
        if(!empty($params)) {
            //$this->_redirect("lmbedi/debug/");
            header("Location: /lmbedi/debug/");
            exit;
        }
        $this->affiche();
    }
    
    public function majAction(){
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        if(LMB_EDI_Model_Config::$VERSION < "0.6"){
            $query="CREATE TABLE  IF NOT EXISTS `" . Mage::getConfig()->getTablePrefix() . "edi_pid` (
             `name` varchar(15) NOT NULL,
             `etat` varchar(15) NOT NULL,
             PRIMARY KEY  (`name`)

            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

            $write->raw_query($query) or die("Erreur 1");
        }
        echo "Maj 1 Effectuée<br/>";
        
        if(LMB_EDI_Model_Config::$VERSION < "0.9"){
            $query="ALTER TABLE `" . Mage::getConfig()->getTablePrefix() . "edi_pid` ADD `sys_pid` int NULL;";

            $write->raw_query($query) or die("Erreur 2");
            
            $query="ALTER TABLE `" . Mage::getConfig()->getTablePrefix() . "edi_messages_envoi_queue` CHANGE `chaine` `chaine` MEDIUMBLOB NOT NULL ;";

            $write->raw_query($query) or die("Erreur 3");
            
            $query="ALTER TABLE `" . Mage::getConfig()->getTablePrefix() . "edi_messages_recu_queue` CHANGE `chaine` `chaine` MEDIUMBLOB NOT NULL ;";

            $write->raw_query($query) or die("Erreur 4");
        }
        echo "Maj 2 Effectuée<br/>";
    }

    private function affiche() {
        header("Content-type: text/html;charset=utf-8");
        
        echo "<h1>Etat du module</h1>";

        echo "version du module EDI = ".LMB_EDI_Model_Config::$VERSION."<br/>";
        echo "table_prefix = ".Mage::getConfig()->getTablePrefix()."<br/>";
        echo "racine_URL = ".LMB_EDI_Model_ModuleLiaison::$RACINE_URL."<br/>";

        echo "id_canal = ".LMB_EDI_Model_Config::ID_CANAL()."<br/>";
        echo "id_lang = ".LMB_EDI_Model_Config::ID_LANG()."<br/>";
        echo "code = ".LMB_EDI_Model_Config::CODE_CONNECTION()."<br/>";
        echo "mail d'alerte = ".LMB_EDI_Model_Config::MAIL_ALERT()."<br/>";
        echo "Magento v".Mage::getVersion()."<br/>";
        echo "site_distant = ".LMB_EDI_Model_ModuleLiaison::$SITE_DISTANT."<br/>";
        echo "<br/>";
        if (LMB_EDI_Model_Config::TRANSFERT_DATA_PAR_CRON_DISTANT()) {
            echo "<input type=\"button\" value=\"Purger les logs\" onclick=\"document.location='".Mage::getBaseUrl()."lmbedi/debug/index/clear_logs/1'\" />";
        }
        else {
            echo "<input type=\"button\" value=\"Purger events\" onclick=\"document.location='".Mage::getBaseUrl()."lmbedi/debug/index/purge_events/1'\" />";
            echo "<input type=\"button\" value=\"Purger mess recu\" onclick=\"document.location='".Mage::getBaseUrl()."lmbedi/debug/index/purge_mess_recu/1'\" />";
            echo "<input type=\"button\" value=\"Purger mess envoi\" onclick=\"document.location='".Mage::getBaseUrl()."lmbedi/debug/index/purge_mess_envoi/1'\" />";
            echo "<input type=\"button\" value=\"Purger les logs\" onclick=\"document.location='".Mage::getBaseUrl()."lmbedi/debug/index/clear_logs/1'\" />";
            if(!LMB_EDI_Model_PID::forceStop())
                echo "<input type=\"button\" value=\"Forcer l'arret de tous les process\" onclick=\"document.location='".Mage::getBaseUrl()."lmbedi/debug/index/stop_process/1'\" />";
            else echo "<input type=\"button\" value=\"Autoriser la reprise des process\" onclick=\"document.location='".Mage::getBaseUrl()."lmbedi/debug/index/stop_process/0'\" />";
            echo "<input type=\"button\" value=\"Redemarrer tous les process\" onclick=\"document.location='".Mage::getBaseUrl()."lmbedi/debug/index/start_process/1'\" />";
        }
        echo "<br/>";
        echo "Time limit : ".ini_get("max_execution_time");

        $reader = Mage::getSingleton('core/resource')->getConnection('core_read');
        
        echo "<br/>";
        echo "<br/>";

        if (LMB_EDI_Model_Config::TRANSFERT_DATA_PAR_CRON_DISTANT()) {
            echo "<h3>Gestion pas Cron, pas de piles</h3>";
        }
        else {
            $table_name = Mage::getConfig()->getTablePrefix()."edi_messages_envoi_queue";
            $query = "SELECT COUNT(id) nb FROM $table_name WHERE etat = ".LMB_EDI_Model_Config::$OK_CODE;
            $resultset = $reader->fetchOne($query);
            echo "$table_name a traiter : ".($resultset ? (int) $resultset : 0)."<br/>";

            $table_name = Mage::getConfig()->getTablePrefix()."edi_messages_recu_queue";
            $query = "SELECT COUNT(id) nb FROM $table_name WHERE etat = ".LMB_EDI_Model_Config::$OK_CODE;
            $resultset = $reader->fetchOne($query);
            echo "$table_name a traiter : ".($resultset ? (int) $resultset : 0)."<br/>";

            $table_name = Mage::getConfig()->getTablePrefix()."edi_events_queue";
            $query = "SELECT COUNT(id) nb FROM $table_name WHERE etat = ".LMB_EDI_Model_Config::$OK_CODE;
            $resultset = $reader->fetchOne($query);
            echo "$table_name a traiter : ".($resultset ? (int) $resultset : 0)."<br/>";
        }

        echo "<br/><br/>";
        
        /* Affichage des logs et PIDs */
        $logs = $pids = array();
        $log_dir = opendir(Mage::getBaseDir('var')."/log/");
        while($file = readdir($log_dir)) {
            $index = strpos(strtolower($file), "lmb_edi");
            if ($index !== 0) {
                continue;
            }
            if (substr($file, -4) != ".log") {
                continue;
            }
            
            if(is_file(Mage::getBaseDir('var')."/log/".$file) && $file != "." && $file != ".." && strpos($file,".")!==0) {
                $logs[] = $file;
            }
        }
        sort($logs);

        $max_lignes_logs = 1000;
        
        echo "<h1>Fichiers Logs et PID</h1>";
        if (LMB_EDI_Model_Config::TRANSFERT_DATA_PAR_CRON_DISTANT()) {
            echo "<h3>Gestion pas Cron, pas de piles</h3>";
        }
        else {
            $pids = LMB_EDI_Model_PID::getList();
            
            foreach ($pids as $pid) {
                //$s = @file_get_contents(Mage::getBaseDir('var')."/log/lmbedi/pid/".$pid);
                //$n = $pid;
                $s = $pid->etat;
                $n = $pid->name;
                echo $n." : ";
                echo "<input type=\"text\" value=\"$s\"/>";
                echo '<input type="button" value="Débloquer le process" onclick="document.location=\''.Mage::getBaseUrl().'lmbedi/debug/index/unlock_pid/'.$n.'\'" /><br/>';
                echo '<form action="/lmbedi/debug/index/" method="post">';
                echo '<input type="hidden" name="change_etat" value="'.$n.'" />';
                echo '[ID message <input name="id_mess" type="text" /> état <input name="etat_mess" type="text" />';
                echo '<input type="submit" value="Modifier l\'état" />]';
                echo '</form>';
            }
        }
        
        foreach ($logs as $log) {
            $path = Mage::getBaseDir('var')."/log/".$log;
            $s = "";
            if (filesize($path) < 256*1024*1024) {
                $file_log = file($path);
                $start = max(0, count($file_log)-$max_lignes_logs);
                for ($i = $start; $i < count($file_log); $i++) {
                  $s .= $file_log[$i];
                }
            }
            else {
                $s = "Non chargé, trop volumineux";
            }
            
            echo $log."<br/>";
            echo '<div style="height: 200px; overflow: auto; border: solid 1px;">';
            echo "<pre>";
            echo $s;
            echo "</pre>";
            echo "</div><br/><br/>";
        }        
        
    }
	
    /*
	public function adminerAction(){
		include(__DIR__.'/../adminer_lmb.php');
	}
	
	public function infosAction(){
		phpinfo();
    }
    //*/
}
