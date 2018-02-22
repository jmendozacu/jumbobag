<?php

class LMB_EDI_InstallController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $writer = Mage::getSingleton('core/resource')->getConnection('core_write');
        $query="
	CREATE TABLE IF NOT EXISTS `".Mage::getConfig()->getTablePrefix()."edi_events_queue` (
	  `id` int(11) unsigned NOT NULL auto_increment,
	  `type_event` varchar(32) NOT NULL,
	  `id_element` varchar(32) NOT NULL,
	  `date` datetime NOT NULL,
          etat tinyint NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
        $writer->query($query);

        $query="
	CREATE TABLE IF NOT EXISTS `".Mage::getConfig()->getTablePrefix()."edi_messages_envoi_queue` (
	  `id` int(11) unsigned NOT NULL auto_increment,
          `sig` varchar(32) NOT NULL,
	  `chaine` MEDIUMBLOB NOT NULL,
	  `date` datetime NOT NULL,
          etat tinyint NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
        $writer->query($query);

        $query="
	CREATE TABLE IF NOT EXISTS `".Mage::getConfig()->getTablePrefix()."edi_messages_recu_queue` (
	 `id` int(11) unsigned NOT NULL auto_increment,
         `sig` varchar(32) NOT NULL,
	 `chaine` MEDIUMBLOB NOT NULL,
	 `date` datetime NOT NULL,
         etat tinyint NOT NULL,
	 PRIMARY KEY  (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $writer->query($query);
		
	$query="CREATE TABLE  IF NOT EXISTS `" . Mage::getConfig()->getTablePrefix() . "edi_pid` (
	 `name` varchar(15) NOT NULL,
	 `etat` varchar(15) NOT NULL,
	 `sys_pid` int NULL,
	 PRIMARY KEY  (`name`)

	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
	$writer->query($query);

        /*
        $code = $this->getRequest()->getParam("code_connection", false);
        $id_lang = $this->getRequest()->getParam("id_lang", false);
        $id_canal = $this->getRequest()->getParam("id_canal", false);
        $mail = $this->getRequest()->getParam("mail_alert", false);
        LMB_EDI_Model_Config::ID_CANAL($id_canal);
        LMB_EDI_Model_Config::ID_LANG($id_lang);
        LMB_EDI_Model_Config::CODE_CONNECTION($code);
        LMB_EDI_Model_Config::MAIL_ALERT($mail);
        //unlink(__FILE__);
        //*/
    }
}
