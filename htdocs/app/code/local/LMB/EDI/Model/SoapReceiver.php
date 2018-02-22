<?php

class LMB_EDI_Model_SoapReceiver {

    public function  __construct($id, $str) {
        if ($str != "") {
            Mage::log("Reception: $id|$str", null, "/lmbedi/error.log", true);
            //logme("********************** RECEPTION D'UN MESSAGE **********************");


            //test si le message a déjà été recu
            /* @fixme à perfectionner... pb si archivage puis reactivation article par exemple... (recréation ne se fait pas) */
            /*$query = "SELECT chaine FROM ".Mage::getConfig()->getTablePrefix()."edi_messages_recu_queue
                    WHERE chaine='$str'";
        $res = $bdd->query($query);
        if(is_object($res)) {
            if($res->fetchObject()) {
                logme("Le message avait déja été recu");
                return true;
            }
            $res->closeCursor();
        }*/

            //sauvegarde du message recu
            /*$query = "INSERT INTO ".$prefixe_table."edi_messages_recu_queue
                    (chaine,date,etat)
                    VALUES('".$str."',  NOW(), ".LMB_EDI_Model_Config::$OK_CODE.")";
            logme($query);

            if(($bdd->exec($query)) == 1) {
                while(!new_process("liaison/_process_messages_recu_queue.php", message_recu::$process)) {
                    sleep(2);
                }
                return true;
            }
            return "insertion bdd Presta erreur ".print_r($res,true)."\n prefix:".$prefixe_table;
        }else {
            return "message vide";

        }
        return false;*/
            return true;
        }
    }
}
