<?php

class LMB_EDI_Model_EDI extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('edi/edi');
    }

    public static function newProcess($nom_process, $pid) {
        global $alert_time;

        //************************************************************
        // TEST SI UN PROCESSUS N'EST PAS DEJA EN COURS D'EXECUTION
        if(!$pid->isset_pid()) {
            //***********************************
            // CREATION D'UN NOUVEAU PROCESSUS
            LMB_EDI_Model_EDI::trace("process","Nouveau process ".$pid->getName());
			if (LMB_EDI_Model_PID::forceStop()) {
				LMB_EDI_Model_EDI::trace("process","Arrêt forcé de la file par fichier pid/stop !");
				return true;
			}
            
            $url = LMB_EDI_Model_ModuleLiaison::$RACINE_URL."/lmbedi/echanges/".$nom_process;
            // Methode avec cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch , CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch , CURLOPT_NOBODY, true);
            curl_setopt($ch , CURLOPT_MAXREDIRS, 5);    //Autorise au plus 5 redirections (par sécurité)
            //curl_setopt($ch , CURLOPT_TIMEOUT_MS, 500);      //Coupe la connexion au bout de 500ms
            curl_setopt($ch , CURLOPT_TIMEOUT, 1);      //Coupe la connexion au bout de 1s
            $reponse = curl_exec($ch);
            if(curl_errno($ch) && curl_errno($ch) != 28){
                LMB_EDI_Model_EDI::trace("process","Erreur (".curl_errno($ch).") création process $chemin_pid : ".curl_error($ch));
                return false;
            }
            curl_close($ch);

            LMB_EDI_Model_EDI::trace("process","Process ".$pid->getName()." démarré...");
            return true;
        }
        else if(!$pid->iserror_pid()) {
            /*$old_time = substr(file_get_contents($DIR_MODULE."pid/".$chemin_pid),1);
            if(time()-$old_time > $alert_time) {
                $alert_time = mktime(0,3);
                $server = LMB_EDI_Model_ModuleLiaison::$RACINE_URL;
                mail(LMB_EDI_Model_Config::MAIL_ALERT(),"Blocage PrestaSHOP : $server","Ca bloque sur new_process($nom_process, $chemin_pid)\n$server...\n$server/modules/lmb/debug.php");
            }*/
            return true;
        }
        return false;
    }

    public static function lmb_name($name) {
        $retour = $name;
        $index = strpos(strtolower($retour), "lmb_edi");
        
        if ($index !== 0) {
            $retour = "lmb_edi_".$retour;
        }
        if (substr($retour, -4) != ".log") {
            $retour .= ".log";
        }
        
        return $retour;
    }
    
    public static function trace($file_name, $message){
        Mage::log($message, Zend_Log::INFO, static::lmb_name($file_name), true);
    }

    public static function error($message){
        Mage::log($message, Zend_Log::INFO, static::lmb_name("Erreurs"), true);
        return false;
    }

    public static function traceDebug($file_name, $message){
        if(empty($file_name)) {
            $file_name = "debug";
        }
        if(LMB_EDI_Model_Config::DEBUG_MODE()) {
            Mage::log($message, Zend_Log::DEBUG, static::lmb_name($file_name));
        }
    }
}
