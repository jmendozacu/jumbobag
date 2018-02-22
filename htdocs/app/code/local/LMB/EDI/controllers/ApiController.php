<?php

/**
 * Controller utilisE pour la gestion de cron via LMB - (cron distant)
 * Ainsi, aucune file d'attente ou cron n'a besoin d'Etre gErE sur le site e-commerce lui-meme
 * - elle se fait sur LMB vis des appels sur ce controller
 */
class LMB_EDI_ApiController extends Mage_Core_Controller_Front_Action {

    /**
     * modification de l'etat de la commande
     * 
     * parametres POST ou GET :
     *   ref  - id de la commande
     *   etat - statut de la commande
     */
    public function modif_etat_cmdAction(){
        
        try {
            $this->verifyConnection(); 
            
            $command['ref']  = $this->getRequest()->getParam("ref", false);
            $command['etat'] = $this->getRequest()->getParam("etat", false);
            
            $r = new LMB_EDI_Model_Interface_Recepteur();
            $r->modif_etat_cmd($command);
            
            $this->echoMessageJson(200, '');
        } catch (Exception $e) {
            $this->echoMessageJson($e->getCode(), $e->getMessage());
        }
    }

    /**
     * transmission d'un array contenant divers "messages" -
     *   chacun est soit une commmande _evt_name_ : recup_commande
     *              soit un  paiement  _evt_name_ : create_reglement
     * 
     * parametres POST ou GET :
     *   id_min  (optionel) - id minimum exclue de la commande (si id_min est 300, la commande traitée en premier sera 301)
     *   NON UTILISE : id_max  (optionel) - id max de la commande 
     */

    public function indexAction() {



        ////////////
        //
        //  get payment and order infos
        //
        ////////////

        try {
            $this->verifyConnection();

            $idMin = $this->getRequest()->getParam("id_min", false);
            $idMax = $this->getRequest()->getParam("id_max", false);

            $orders = $this->getOrderListFromAndTo($idMin, $idMax);

            $e = new LMB_EDI_Model_Interface_Emetteur();
            $resultat = array();

            foreach ($orders as $order) {
                $orderId = $order->getRealOrderId();
                
                $o = $e->getOrderInfos($orderId);
                
                if (is_array($o)) {
                    $resultat[] = array(
                        "params" => $o,
                        "nom_fonction" => "recup_commande",
                        "destination" => LMB_EDI_Model_ModuleLiaison::$SITE_DISTANT
                    );
                    
                    $p = $e->getPaymentInfos($orderId);
                    if (is_array($p)) {
                        $resultat[] = array(
                            "params" => $p,
                            "nom_fonction" => "create_reglement",
                            "destination" => LMB_EDI_Model_ModuleLiaison::$SITE_DISTANT
                        );
                    }
                }
            }
            $this->echoMessageJson(200, $resultat);
        } catch (Exception $e) {
            $this->echoMessageJson($e->getCode(), $e->getMessage());
        }
    }

    private function echoMessageJson($code, $message) {
        echo json_encode(
                array("code" => $code,
            "message" => $message)
                , JSON_PRETTY_PRINT);
    }

    private function verifyConnection() {
        $code = $this->getRequest()->getParam("serial_code", false);
        if (LMB_EDI_Model_Config::CODE_CONNECTION() !== $code) {
            Mage::log("ERREUR DE CODE DE CONNEXION !", null, "/lmbedi/Error.log", true);
            exit("ERREUR DE CODE DE CONNEXION !");
        }
    }

    /**
     * si idMin et idMax non prEcisE, alors toute la liste est renvoyEe
     * 
     * @param type $idMin
     * @param type $idMax
     * @return type
     */
    private function getOrderListFromAndTo($idMin = null, $idMax = null) {
        Mage::app();
        $resultat = array();

//        if (is_numeric($idMin) && is_numeric($idMax)) {
//            return Mage::getResourceModel('sales/order_collection')
//                           ->addFieldToFilter('increment_id', array(
//                               array('gt' => $idMin),
//                               array('lteq' => $idMax)
//                               )
//                            )
//                            ->setOrder('created_at', 'asc'); 
//        }

        if (is_numeric($idMin)) {
            return Mage::getResourceModel('sales/order_collection')
                            ->addAttributeToFilter('increment_id', array('gt' => $idMin))
                            ->setOrder('created_at', 'asc');
        }

//        if (is_numeric($idMax)) {
//            return Mage::getResourceModel('sales/order_collection')
//                            ->addAttributeToFilter('increment_id', array('lteq' => $idMax))
//                            ->setOrder('created_at', 'asc');
//        }

        return Mage::getResourceModel('sales/order_collection')->setOrder('created_at', 'asc');
    }

}
