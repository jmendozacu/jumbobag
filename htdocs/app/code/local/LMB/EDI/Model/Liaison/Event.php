<?php

class LMB_EDI_Model_Liaison_Event implements LMB_EDI_Model_Liaison_Process {
    protected static $process;

    private $id;
    private $date;
    private $type_event;
    private $id_element;
    private $params;
    private $etat = 0;

    /**
     * @return LMB_EDI_Model_PID
     */
    public static function getProcess() {
        if (!is_object(self::$process)) {
            self::$process = new LMB_EDI_Model_PID("peq.pid");
        }
        return self::$process;
    }

    public function  __construct($id_message) {
        $this->id = $id_message;
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $query = "SELECT * FROM ".Mage::getConfig()->getTablePrefix()."edi_events_queue WHERE id=$id_message;";
        $res = $read->query($query);
        if (is_object($res) && $me = $res->fetchObject()) {
            $this->id = $me->id;
            $this->type_event = $me->type_event;
            $this->id_element = $me->id_element;
            $this->date = $me->date;
            $this->etat = $me->etat;
        }
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
            $query = "SELECT id FROM ".Mage::getConfig()->getTablePrefix()."edi_events_queue
                        WHERE etat!='".LMB_EDI_Model_Config::$TRAITE_CODE."' ORDER BY date,id ASC LIMIT $cpt_event,1;";
            $result = $read->query($query);
            if(is_object($result) && $mes = $result->fetchObject()) {
                $message = new LMB_EDI_Model_Liaison_Event($mes->id);
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
        $writer->raw_query("UPDATE ".Mage::getConfig()->getTablePrefix()."edi_events_queue SET etat = 1 WHERE id='".$this->id."'");
        //$writer->raw_query("DELETE FROM ".Mage::getConfig()->getTablePrefix()."edi_events_queue WHERE id='".$this->id."';");
        return true;
    }

    public static function create($type_event, $id_element, $params = null) {
        if(LMB_EDI_Model_Config::TRANSFERT_DATA_PAR_CRON_DISTANT()){
            LMB_EDI_Model_EDI::trace("process","Gestion de la pile par Cron Distant");
            return false;
        }
        
        $prefixe_table = Mage::getConfig()->getTablePrefix();
        $writer = Mage::getSingleton('core/resource')->getConnection('core_write');
        //**************************************************************
        //Enregistrement dans la table edi_events et edi_events_queue
        $query = "INSERT INTO ".$prefixe_table."edi_events_queue (type_event, id_element, date, etat)
                    VALUES ('$type_event', '$id_element', NOW(), ".LMB_EDI_Model_Config::$OK_CODE.")";

        $res = $writer->raw_query($query);
        
        if(!$res) {
            self::getProcess()->setError_pid();
            mail(LMB_EDI_Model_Config::MAIL_ALERT(),"Event non inséré","Event non inséré ".$query);
            if(empty($id_event)) {
                $id_event = '_' ;
            }
            $fp = fopen(Mage::getBaseDir('var')."/log/lmbedi/EVENTS_NOT_SAVED","a");
            if ($fp) {
                fwrite($fp,"query: ".$query."\n");
                fwrite($fp,"id_event: ".$id_event."\n");
                fwrite($fp,"type_event: ".$type_event."\n");
                fwrite($fp,"id_element: ".$id_element."\n");
                fwrite($fp,"date: ".date('Y-m-d H:i:s')."\n\n");
                fclose($fp);
            }
            else mail(LMB_EDI_Model_Config::MAIL_ALERT(),"","$query");
        }
        else {
            $tentative = 0; 
            while (!LMB_EDI_Model_EDI::newProcess("process/start/events", self::getProcess())) {
                sleep(5); 
                $tentative++; 
                if ($tentative > 3) { 
                    LMB_EDI_Model_EDI::error(self::getProcess()." n'a pas pu être relancé après 3 tentatives"); 
                    break; 
                } 
            } 
        }
    }

    public function exec() {
        self::getProcess()->majDate_pid();
        $emetteur = new LMB_EDI_Model_Interface_Emetteur();

        $ret = false;
        $function = $this->type_event;
        if(method_exists($emetteur, $function)) {
            if($this->params) {
                LMB_EDI_Model_EDI::traceDebug(self::getProcess()->getName(), $function."(".$this->id_element.",".$this->params.")");
                if($emetteur->$function($this->id_element, $this->params)===true) {
                    $ret = true;
                }
            } else {
                LMB_EDI_Model_EDI::traceDebug(self::getProcess()->getName(), $function."(".$this->id_element.")");
                if($emetteur->$function($this->id_element)===true) {
                    $ret = true;
                }
            }
        }else {
            $ret = LMB_EDI_Model_EDI::error(self::getProcess()->getName(), "La méthode '$function()' n'existe pas !");
        }
        return $ret;
    }

    public function getEtat(){
        return $this->etat;
    }

    public function getId(){
        return $this->id;
    }
}

