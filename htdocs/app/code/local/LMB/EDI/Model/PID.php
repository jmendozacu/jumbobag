<?php

class LMB_EDI_Model_PID {
    
    private $name;
    /**
     * @param string $chemin_pid le chemin du fichier de pid depuis la racine du module
     */
    public function __construct($name) {
        $this->name = $name;
        $this->chemin_pid = Mage::getBaseDir('var')."/log/lmbedi/pid/".$this->name;
    }

    public function set_pid() {
        //file_put_contents($this->chemin_pid, "1".time());
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$query = "REPLACE INTO " . Mage::getConfig()->getTablePrefix() . "edi_pid (name, sys_pid, etat) VALUES (".$write->quote($this->name).", ".$write->quote(getmypid()).", ".$write->quote("1".time()).")";
		$write->raw_query($query);
    }

    public function unset_pid() {
        //file_put_contents($this->chemin_pid, "0".time());
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $query = "REPLACE INTO " . Mage::getConfig()->getTablePrefix() . "edi_pid (name, sys_pid, etat) VALUES (".$write->quote($this->name).", ".$write->quote(0).", ".$write->quote("0".time()).")";
		$write->raw_query($query);
    }
	
	public function delete_pid(){
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$query = "DELETE FROM " . Mage::getConfig()->getTablePrefix() . "edi_pid  WHERE name = ".$write->quote($this->name)."";
		$write->raw_query($query);
	}
	
	public function get_pid(){
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$query = "SELECT etat FROM " . Mage::getConfig()->getTablePrefix() . "edi_pid  WHERE name = ".$read->quote($this->name)."";
		$res = $read->query($query);
		if($pid = $res->fetchObject()){
			return $pid->etat;
		}
		return "0";
	}
	
	public function get_sys_pid(){
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$query = "SELECT sys_pid FROM " . Mage::getConfig()->getTablePrefix() . "edi_pid  WHERE name = ".$read->quote($this->name)."";
		$res = $read->query($query);
		if($pid = $res->fetchObject()){
			return $pid->sys_pid;
		}
		return "0";
	}

    public function isset_pid() {
        $return = true;
		$data = $this->get_pid();
		$return = substr($data, 0 ,1);
		if($return != "0"){
			$old_time = substr($data,1);
			$new_time = time();
			if(($new_time - $old_time) > LMB_EDI_Model_Config::$alert_time && ($new_time - LMB_EDI_Model_Config::LAST_ALERT()) > LMB_EDI_Model_Config::$intervale_mail && LMB_EDI_Model_Config::MAIL_ALERT()) {
				LMB_EDI_Model_Config::LAST_ALERT($new_time);
				$server = LMB_EDI_Model_ModuleLiaison::$RACINE_URL;
				mail(LMB_EDI_Model_Config::MAIL_ALERT(),"Blocage Module EDI LMB : $server","Ca bloque sur Queue->start(".$this->name.") depuis ".($new_time - $old_time)."\n$server...\n$server/lmbedi/debug/");
			}
		}
        return $return != "0";
    }

    public function iserror_pid() {
        $return = true;
		$val = substr($this->get_pid(), 0 ,1);

        return $val == "9";
    }

    public function setError_pid() {
        //file_put_contents($this->chemin_pid, "9".time());
		
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$query = "REPLACE INTO " . Mage::getConfig()->getTablePrefix() . "edi_pid (name, etat) VALUES (".$write->quote($this->name).", ".$write->quote("9".time()).")";
		$write->raw_query($query);
		
		if(LMB_EDI_Model_Config::MAIL_ALERT()){
			$server = LMB_EDI_Model_ModuleLiaison::$RACINE_URL;
			mail(LMB_EDI_Model_Config::MAIL_ALERT(),"Blocage Critique Module EDI LMB : $server","Alerte sur $server...\n$server/lmbedi/debug/");
		}
    }

    public function majDate_pid() {
        //file_put_contents($this->chemin_pid, "1".time());
        $this->set_pid();
    }

    public static function forceStop(){
		$pid_s = new LMB_EDI_Model_PID("stop");
		
		$val = substr($pid_s->get_pid(), 0 ,1);
        return $val != "0";
        //return is_file(Mage::getBaseDir('var')."/log/lmbedi/pid/stop");
    }
	
	public static function getList(){
		/*$pid_dir = opendir(Mage::getBaseDir('var')."/log/lmbedi/pid/");
        while($file = readdir($pid_dir)) {
            if(is_file(Mage::getBaseDir('var')."/log/lmbedi/pid/".$file) && $file != "." && $file != ".." && strpos($file,".")!==0)
                $pids[] = $file;
        }
        sort($pids);*/
		
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$query = "SELECT name, etat FROM " . Mage::getConfig()->getTablePrefix() . "edi_pid ORDER BY name";
		$res = $read->query($query);
		$pids = array();
		while($pid = $res->fetchObject()){
			$pids[] = $pid;
		}
		return $pids;
	}


    //******************************************************
    // GETTERS
    //******************************************************

    public function getChemin_pid() {
        return $this->chemin_pid;
    }
    public function getName() {
        return $this->name;
    }
}
?>
