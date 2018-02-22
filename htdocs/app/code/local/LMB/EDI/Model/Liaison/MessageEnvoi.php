<?php

class LMB_EDI_Model_Liaison_MessageEnvoi implements LMB_EDI_Model_Liaison_Process {

    private $id;
    private $sig;
    private $destination;
    private $nom_fonction = "";
    private $params = array();
    private $etat;
    private $date;
    private $chaine;
    private $date_creation;
    private $message = "";

    private $loaded = 0;

    private static $crypto;

    protected static $process;

    /**
     * @return LMB_EDI_Model_PID
     */
    public static function getProcess() {
        if (!is_object(self::$process)) {
            self::$process = new LMB_EDI_Model_PID("pmeq.pid");
        }
        return self::$process;
    }

    public function __construct($id = null) {
        if(empty(self::$crypto))
            self::$crypto = LMB_EDI_Model_Liaison_Crypto::getInstance(LMB_EDI_Model_Config::CODE_CONNECTION());

        if (!empty($id)) {
            $this->id = $id;
            $this->load();
        }else {
            $this->date_creation = date("Y-m-d H:i:s");
            $this->etat = LMB_EDI_Model_Config::$OK_CODE;
        }
    }

    public function estExecutable() {

        return true;
    }

    /**
     * @return LMB_EDI_Model_Liaison_MessageEnvoi
     */
    public static function loadNext() {
        $cpt_event = -1;
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        do {
            $continue = true;
            ++$cpt_event;
            $query = "SELECT id FROM ".Mage::getConfig()->getTablePrefix()."edi_messages_envoi_queue
                        WHERE etat!='".LMB_EDI_Model_Config::$TRAITE_CODE."' ORDER BY date,id ASC LIMIT $cpt_event,1;";
            $result = $read->query($query);
            if(is_object($result) && $mes = $result->fetchObject()) {
                $message = new LMB_EDI_Model_Liaison_MessageEnvoi($mes->id);
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

    public function remove() {
        $writer = Mage::getSingleton('core/resource')->getConnection('core_write');
        $writer->raw_query("UPDATE ".Mage::getConfig()->getTablePrefix()."edi_messages_envoi_queue SET etat = 1 WHERE id='".$this->id."'");
        //$writer->raw_query("DELETE FROM ".Mage::getConfig()->getTablePrefix()."edi_messages_envoi_queue WHERE id='".$this->id."';");
        return true;
    }

    public function getEtat() {
        return $this->etat;
    }

    public function getId(){
        return $this->id;
    }

    public function exec() {
        self::getProcess()->majDate_pid();
        
        $return = "";
        LMB_EDI_Model_EDI::trace(self::getProcess()->getName(), "Début de l'envoi du message $this->id");
        LMB_EDI_Model_EDI::traceDebug(self::getProcess()->getName(), "$this->id - after query - ".$this->chaine);
        $msg = $this->decrypt();
        LMB_EDI_Model_EDI::traceDebug(self::getProcess()->getName(), "$this->id - after decrypt - ".$this->destination." - ".$this->nom_fonction.": ".print_r($this->params, true));

        try {
            $transport_options = array('location' => $this->destination,
                    'uri' => 'urn:Liaison',
                    'trace' => 1,
                    'style' => SOAP_RPC,
                    'use' => SOAP_ENCODED,
                    'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP);
            $transport = new SoapClient(NULL, $transport_options);
            $transport_contenu = array($this->sig, $this->chaine);
            $return  = $transport->__soapCall("Receiver", $transport_contenu);
            if($return === true){
                LMB_EDI_Model_EDI::trace(self::getProcess()->getName(), "Envoi du message $this->id réussi !");
            }
			$transport = null;
        } catch (Exception $e) {
            LMB_EDI_Model_EDI::error("!!!!!!!EXCEPTION SOAP : ".$e->getMessage());
            LMB_EDI_Model_EDI::traceDebug(self::getProcess()->getName(), $transport->__getLastResponse());
            header("HTTP/1.1 400 Bad Request");
			$transport = null;
            return false;
        }
        return $return;
    }

    private function encrypt() {
        $tab = array();
        $tab["destination"] = $this->destination;
        $tab["nom_fonction"] = $this->nom_fonction;
        $tab["params"] = $this->params;
        $tab["etat"] = $this->etat;
        $tab["message"] = $this->message;
        $chaine = self::$crypto->encrypt(json_encode($tab));
        LMB_EDI_Model_EDI::traceDebug("debug",$chaine);
        return $chaine;
    }

    private function decrypt() {
        $tab = json_decode(self::$crypto->decrypt($this->chaine), true);
        $this->nom_fonction = $tab["nom_fonction"];
        $this->destination = $tab["destination"];
        $this->params = $tab["params"];
        $this->etat = $tab["etat"];
        $this->message = $tab["message"];
        return true;
    }

    private function load() {
        $this->loaded = 0;
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $query = "SELECT id,sig,chaine,date,etat FROM ".Mage::getConfig()->getTablePrefix()."edi_messages_envoi_queue
                        WHERE id='".$this->id."'";
        $res = $read->query($query);
        if(is_object($res)){
            $me = $res->fetchObject();
            $this->id = $me->id;
            $this->date = $me->date;
            $this->chaine = $me->chaine;
            $this->etat = $me->etat;
            $this->sig = $me->sig;
            if($this->decrypt()) {
                $this->loaded = 1;
            }
            else LMB_EDI_Model_EDI::error("Impossible de décrypter le message à envoyer");
        }
    }

    public function save() {
        $this->sig = md5(uniqid());
        $writer = Mage::getSingleton('core/resource')->getConnection('core_write');
        $query = "INSERT INTO ".Mage::getConfig()->getTablePrefix()."edi_messages_envoi_queue
                    (sig, chaine, date, etat) VALUES ('$this->sig', '".$this->encrypt()."', NOW(), '".$this->etat."')";

        if($writer->raw_query($query)) {
            LMB_EDI_Model_EDI::traceDebug("debug","inserted");
            $this->id = $writer->lastInsertId();
            LMB_EDI_Model_EDI::traceDebug("debug","ok");
            return true;
        }else {
            LMB_EDI_Model_EDI::traceDebug("debug","bug");
            LMB_EDI_Model_EDI::error("Erreur de sauvegarde message_envoi !");
            return false;
        }
    }

    public static function create($destination, $fonction_name, $params) {
        LMB_EDI_Model_EDI::traceDebug("debug","envoi::create");
        $mess_envoi = new LMB_EDI_Model_Liaison_MessageEnvoi();
        $mess_envoi->set_destination($destination);
        $mess_envoi->set_fonction($fonction_name, $params);
        return $mess_envoi;
    }

    public function set_destination($destination) {
        if ($destination != "") {
            $this->destination = $destination;
            return true;
        } else {
            LMB_EDI_Model_EDI::error("La destination du message envoi n'est pas spécifiée");
            return false;
        }
    }

    public function set_fonction($fonction_name,$params) {
        if ($fonction_name != "") {
            $this->nom_fonction = $fonction_name;
        } else {
            LMB_EDI_Model_EDI::error("Le nom de la fonction message envoi n'est pas spécifié");
            return false;
        }
        if (is_array($params) && count($params) > 0 ) {
            $this->params = $params;
        } else {
            LMB_EDI_Model_EDI::error("Les paramètres du message envoi ne sont pas spécifiés");
            return false;
        }
        return true;
    }
}
?>