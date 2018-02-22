<?php

class LMB_EDI_Model_Config {
    public static $log_prefix = "trace_";      // prefixe des fichier de log. si cette variable n'existe pas => pas de fichier de log
    public static $ID_ORDER_STATE_CONFIRM = " '1', '10' ";  // id des order_state pour lequel il ne faut pas faire un retour vers LMB
    public static $OK_CODE = 0;
    public static $ERROR_CODE = 9;
    public static $TRAITE_CODE = 1;
    public static $VERSION = "0.9";
    public static $alert_time = 300;
    public static $intervale_mail = 900;

    /* Fin de la configuration manuelle du module */
    public static $log_file;

    private static function loadConf(){
        $DIR_MODULE = dirname(__FILE__)."/../etc/";

        $doc = new DOMDocument();
        if(!is_file($DIR_MODULE.'conf_module.xml')){
            touch($DIR_MODULE.'conf_module.xml');
            $conf = $doc->createElement('config');
            $doc->appendChild($conf);
            self::saveConf($doc);
        }
        $doc->load($DIR_MODULE.'conf_module.xml');
        $conf = $doc->getElementsByTagName('config');
        if(empty($conf)){
            $conf = $doc->createElement('config');
            $doc->appendChild($conf);
        }
        return $doc->saveXML();
    }

    private static function saveConf($doc){
        $DIR_MODULE = dirname(__FILE__)."/../etc/";

        $doc->save($DIR_MODULE.'conf_module.xml');
    }

    public static function GET_PARAM($name) {
        try {
            $doc = new DOMDocument();
            $doc->loadXML(self::loadConf());
            $conf = $doc->getElementsByTagName('config')->item(0);

            return $conf->getAttribute($name);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function ID_CANAL($id = false){
        $doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($id){
            $conf->setAttribute('idcanal',$id);
            self::saveConf($doc);
        }
        return $conf->getAttribute('idcanal');
    }

    public static function TVA_CLIENT_CLASS($id = false){
        $doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($id){
            $conf->setAttribute('clienttva',$id);
            self::saveConf($doc);
        }
        return $conf->getAttribute('clienttva');
    }

    public static function ID_LANG($id = false){
        $doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($id){
            $conf->setAttribute('idlang',$id);
            self::saveConf($doc);
        }
        $lang = $conf->getAttribute('idlang');
        if(!$lang) $lang = "2"; //Langue fr par d�faut
        return $lang;
    }

    public static function CODE_CONNECTION($code = false){
        $doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($code){
            $conf->setAttribute('code',$code);
            self::saveConf($doc);
        }
        return $conf->getAttribute('code');
    }

    public static function MAIL_ALERT($mail = false){
        $doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($mail){
            $conf->setAttribute('alerte',$mail);
            self::saveConf($doc);
        }
        return $conf->getAttribute('alerte');
    }

    public static function ALERT_TIME(){
        return 5*60;
    }

    public static function DEBUG_STEP($active = null){
        $doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($active){
            $conf->setAttribute('debugstep',$active);
            self::saveConf($doc);
        }
        return $conf->getAttribute('debugstep');
    }

    public static function DEBUG_MODE($active = null){
        $doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($active){
            $conf->setAttribute('debugmode',$active);
            self::saveConf($doc);
        }
        return $conf->getAttribute('debugmode');
    }

    public static function DELAY_QUEUE($delay = false){
        $doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($delay){
            $conf->setAttribute('delay',$delay);
            self::saveConf($doc);
        }
        return ($conf->getAttribute('delay'))?$conf->getAttribute('delay'):0;
    }
    
    public static function LAST_ALERT($time = false){
        $doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($time){
            $conf->setAttribute('lastalert',$time);
            self::saveConf($doc);
        }
        return ($conf->getAttribute('lastalert'))?$conf->getAttribute('lastalert'):0;
    }
	
	public static function TARIFS_TTC($active = null){
		$doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($active){
            $conf->setAttribute('apptarifs_ttc',$active);
            self::saveConf($doc);
        }
        return $conf->getAttribute('apptarifs_ttc');
	}
	
	public static function GEST_PROMOS($active = null){
		$doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($active){
            $conf->setAttribute('gest_promos',$active);
            self::saveConf($doc);
        }
        return $conf->getAttribute('gest_promos');
	}

	public static function TRANSFERT_DATA_PAR_CRON_DISTANT($active = null){
		$doc = new DOMDocument();
        $doc->loadXML(self::loadConf());
        $conf = $doc->getElementsByTagName('config')->item(0);
        if($active){
            $conf->setAttribute('transfert_data_par_cron_distant',$active);
            self::saveConf($doc);
        }
        return $conf->getAttribute('transfert_data_par_cron_distant');
	}

}
?>
