<?php

class LMB_EDI_Model_Liaison_MessageRecu implements LMB_EDI_Model_Liaison_Process {

    protected static $process;

    protected static $crypto;

    private $id;
    private $date;
    private $chaine;
    private $etat = 0;

    private $nom_fonction;
    private $params;

    public function __construct($id_message) {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $query = "SELECT * FROM ".Mage::getConfig()->getTablePrefix()."edi_messages_recu_queue
                        WHERE id=$id_message;";
        if ($res = $read->query($query)) {
            if ($me = $res->fetchObject()) {
                $this->id = $me->id;
                $this->date = $me->date;
                $this->chaine = $me->chaine;
                $this->etat = $me->etat;
                if ($this->decrypt()) {
                    $this->loaded = 1;
                }
            }
        }
    }

    private function decrypt(){
        if (!is_object(self::$crypto)) {
            self::$crypto = LMB_EDI_Model_Liaison_Crypto::getInstance(LMB_EDI_Model_Config::CODE_CONNECTION());
        }

        if(mb_substr($this->chaine, 0, 1, "utf-8") == "{" || mb_substr($this->contenu, 0, 2, "utf-8") == "a:"){
            $chaine = $this->chaine;
        } else {
            $chaine = self::$crypto->decrypt($this->chaine);
        }
		
        if(mb_substr($chaine, 0, 2, "utf-8") == "a:"){
            $tab = unserialize($chaine);
        } else {
			$tab = json_decode($chaine, true);
			if(is_null($tab) && phpversion() >= "5.3"){
				$constants = get_defined_constants(true);
				$json_errors = array();
				foreach ($constants["json"] as $name => $value) {
				 if (!strncmp($name, "JSON_ERROR_", 11)) {
				  $json_errors[$value] = $name;
				 }
				}
				$json_errors[json_last_error()];
				LMB_EDI_Model_EDI::trace(self::getProcess()->getName(), "Erreur JSON : ".$json_errors[json_last_error()]);
				throw new Exception("JSON ERROR : message_envoi");
			} else if(is_null($tab)) {
				throw new Exception("JSON ERROR : message_envoi");
			}
		}
        $this->nom_fonction = $tab["nom_fonction"];
        $this->params = $tab["params"];
        return true;
    }

    public static function getProcess() {
        if(!is_object(self::$process)) {
            self::$process = new LMB_EDI_Model_PID("pmrq.pid");
        }
        //LMB_EDI_Model_EDI::traceDebug(null, "process:".self::$process);
        return self::$process;
    }

    public function estExecutable() {

        return true;
    }

    public static function loadNext() {
        $cpt_event = -1;
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        do {
            $continue = true;
            ++$cpt_event;
            $query = "SELECT id FROM ".Mage::getConfig()->getTablePrefix()."edi_messages_recu_queue
                        WHERE etat!='".LMB_EDI_Model_Config::$TRAITE_CODE."' ORDER BY date,id ASC LIMIT $cpt_event,1;";
            $result = $read->query($query);
            if(is_object($result) && $mes = $result->fetchObject()) {
                $message = new LMB_EDI_Model_Liaison_MessageRecu($mes->id);
                if($message->estExecutable()) {
                    LMB_EDI_Model_EDI::trace(self::getProcess()->getName(), $mes->id.': est executable');
                } else {
                    LMB_EDI_Model_EDI::trace(self::getProcess()->getName(), $mes->id.': n\'est pas executable');
                }
            } else {
                $continue = false;
            }
        }while($continue && !$message->estExecutable());

        if($continue) {
            return $message;
        }
        return null;
    }

    public function getEtat() {
        return $this->etat;
    }

    public function getId(){
        return $this->id;
    }

    public function exec() {
        self::getProcess()->majDate_pid();

        $return = false;
        LMB_EDI_Model_EDI::trace(self::getProcess()->getName(), "Début du traitement du message ".$this->id);
        $this->decrypt();
        LMB_EDI_Model_EDI::trace(self::getProcess()->getName(), "fonction: ".$this->nom_fonction);

        //LMB_EDI_Model_EDI::traceDebug(null, print_r(class_exists("LMB_EDI_Model_Interface_Recepteur",false)));
        $recepteur = new LMB_EDI_Model_Interface_Recepteur();
        if(method_exists($recepteur, $this->nom_fonction)) {
            //$return = call_user_func(array($recepteur, $this->nom_fonction), $this->params);
            $func = $this->nom_fonction;
            $return = $recepteur->$func($this->params);
            return $return;
        }else {
            LMB_EDI_Model_EDI::trace(self::getProcess()->getName(), "La fonction $this->nom_fonction n'existe pas !");
            return true;
        }
        return false;
    }

    public function remove() {
        $writer = Mage::getSingleton('core/resource')->getConnection('core_write');
        $writer->raw_query("UPDATE ".Mage::getConfig()->getTablePrefix()."edi_messages_recu_queue
                            SET etat='".LMB_EDI_Model_Config::$TRAITE_CODE."' WHERE id=".$this->id);
        $writer->raw_query("DELETE FROM ".Mage::getConfig()->getTablePrefix()."edi_messages_recu_queue
                            WHERE TO_DAYS(NOW()) - TO_DAYS(date) > 4;");
        return true;
    }
}

