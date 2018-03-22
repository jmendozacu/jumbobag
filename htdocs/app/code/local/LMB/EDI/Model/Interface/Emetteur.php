<?php

class LMB_EDI_Model_Interface_Emetteur {

    private $bdd;

    public function __construct() {
        $bdd = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->bdd = $bdd;
    }

    public static function envoi_LMB($fct, $param) {
        LMB_EDI_Model_EDI::trace($fct, print_r($param, true));
        
        $url_distant = LMB_EDI_Model_ModuleLiaison::$SITE_DISTANT . "/modules/edi/liaison/distant.php?serial_code=" . LMB_EDI_Model_Config::CODE_CONNECTION() . "&id_canal=" . LMB_EDI_Model_Config::ID_CANAL();
        $mes = LMB_EDI_Model_Liaison_MessageEnvoi::create($url_distant, $fct, $param);
        $return = $mes->save();
        LMB_EDI_Model_EDI::traceDebug("debug", "save ok");

        $tentative = 0; 
        while (!LMB_EDI_Model_EDI::newProcess("process/start/messages_envoi", LMB_EDI_Model_Liaison_MessageEnvoi::getProcess())) {
            sleep(5);
            $tentative++; 
            if ($tentative > 3) { 
                LMB_EDI_Model_EDI::error(LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()." n'a pas pu être relancé après 3 tentatives"); 
                break; 
            } 
        }

        return $return;
    }

    public function create_correspondance($params) {
        self::envoi_LMB("create_correspondance", $params);
        return true;
    }

    private function create_client($customer_id, $avec_adresse = false) {
        if (empty($customer_id)) {
            return null;
        }
        
        LMB_EDI_Model_EDI::trace("envoi", "***************DEBUT CREATE CLIENT " . $customer_id . "****************");

        $cust = Mage::getModel('customer/customer')->load($customer_id);
        $contact['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $contact['ref_contact'] = $cust->getId();
        $contact['id_categ_client'] = $cust->getGroupId();
        $contact['id_civilite'] = '';
        $contact['nom'] = $cust->getLastname() . ' ' . $cust->getFirstname();
        $contact['nom_famille'] = $cust->getLastname();
        $contact['prenom'] = $cust->getFirstname();
        $contact['email'] = $cust->getEmail();
        $contact['siret'] = '';
        $contact['tva_intra'] = '';

        if ($avec_adresse) {
            $contact["adresses"] = $this->getContactAdresses($cust);
        }

        LMB_EDI_Model_EDI::traceDebug("envoi", "creation contact " . $cust->getEmail() . " ok");

        LMB_EDI_Model_EDI::traceDebug("envoi", "***************FIN CREATE CLIENT " . $cust->getId() . "****************");
        return $contact;
    }
    
    protected function getContactAdresses(&$customer) {
        $adresses = array();
        
        $customer_adresses = $customer->getAddresses();
        foreach($customer_adresses as $customer_adresse) {
            $adresse = array();
            
            $adresse['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
            $adresse['ref_contact'] = $customer->getId();
            $adresse['ref_adresse'] = $customer_adresse->getId();
            $adresse['lib_adresse'] = $customer_adresse->getName();
            $adresse['nom_adresse'] = $customer_adresse->getLastname();
            $adresse['prenom_adresse'] = $customer_adresse->getFirstname();
            $adresse['text_adresse1'] = $customer_adresse->getStreet1();
            $adresse['text_adresse2'] = $customer_adresse->getStreet2();
            $adresse3 = $customer_adresse->getStreet3();
            $adresse4 = $customer_adresse->getStreet4();
            if (!empty($adresse3) && !empty($adresse4)) {
                $adresse3 .= " ";
            }
            $adresse3 .= $adresse4;
            $adresse['text_adresse3'] = $adresse3;
            $adresse['code_postal'] = $customer_adresse->getPostcode();
            $adresse['ville'] = $customer_adresse->getCity();
            $adresse['id_pays'] = $customer_adresse->getCountryId();
            $adresse['note'] = "";
            
            $coordonnee = array();
            
            $coordonnee['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
            $coordonnee['ref_contact'] = $customer->getId();
            $coordonnee['ref_coord'] = $customer_adresse->getId();
            $coordonnee['lib_coord'] = $customer_adresse->getName();
            $coordonnee['tel1'] = $customer_adresse->getTelephone();
            $coordonnee['tel2'] = "";
            $coordonnee['fax'] = $customer_adresse->getFax();
            $email = $customer_adresse->getCustomerEmail();
            // Si le mail est vide et que c'est la première coordonnée, on utilise le mail du Customer
            if (empty($email) && empty($adresses)) {
                $email = $customer->getEmail();
            }
            $coordonnee['email'] = $email;
            
            $adresses[] = array(
                "adresse" => $adresse,
                "coordonnee" => $coordonnee
            );
        }
        
        return $adresses;
    }

    private function create_adresse($type, &$order) {
        if ($type == "fact") {
            $address = $order->getBillingAddress();
        } else if ($type == "livr") {
            $address = $order->getShippingAddress();
        } else
            return false;
        LMB_EDI_Model_EDI::trace("envoi", "***************DEBUT CREATE ADRESSE " . $address->getId() . "****************");

        $adresse['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $adresse['ref_adresse'] = $address->getId();
        $adresse['lib_adresse'] = $address->getName();
        $adresse['nom_adresse'] = $address->getLastname();
        $adresse['prenom_adresse'] = $address->getFirstname();
        $adresse['text_adresse1'] = $address->getStreet1();
        $adresse['text_adresse2'] = $address->getStreet2();
        $adresse3 = $address->getStreet3();
        $adresse4 = $address->getStreet4();
        if (!empty($adresse3) && !empty($adresse4)) {
            $adresse3 .= " ";
        }
        $adresse3 .= $adresse4;
        $adresse['text_adresse3'] = $adresse3;
        $adresse['code_postal'] = $address->getPostcode();
        $adresse['ville'] = $address->getCity();
        $adresse['id_pays'] = $address->getCountryId();

        LMB_EDI_Model_EDI::trace("envoi", "Ref_adresse : " . $adresse['ref_adresse']);

        $coordonnee['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $coordonnee['ref_coord'] = $address->getId();
        $coordonnee['lib_coord'] = $address->getName();
        $coordonnee['tel1'] = $address->getTelephone();
        $coordonnee['tel2'] = '';
        $coordonnee['fax'] = $address->getFax();
        $coordonnee['email'] = $order->getCustomerEmail();
        $coordonnee['ref_coord_parent'] = NULL;

        LMB_EDI_Model_EDI::traceDebug("envoi", "création coordonnees ok");

        LMB_EDI_Model_EDI::traceDebug("envoi", "***************FIN CREATE ADRESSE " . $address->getId() . "****************");
        $return['adresse'] = $adresse;
        $return['coordonnee'] = $coordonnee;
        return $return;
    }

    public function getOrderInfos($id_order) {
        
        LMB_EDI_Model_EDI::trace("envoi", "***************DEBUT CREATE ORDER $id_order****************");

        $order = Mage::getModel('sales/order')->loadByIncrementId($id_order);
        if(!$order->getId()) {
            return true;
        }
        
        LMB_EDI_Model_EDI::traceDebug("debug_order", print_r($order, true));

        $taux_tva = LMB_EDI_Model_Config::GET_PARAM('taux_tva');
        if (empty($taux_tva)) {
            $taux_tva = 20;
        }
        try {
            $adresse_fact = $order->getBillingAddress();
            if (!$adresse_fact) {
                return LMB_EDI_Model_EDI::error("L'adresse de facturation de la commande n'est pas définie !");
            }
            
            $sync['contact'] = $this->create_client($order->getCustomerId());
            $sync['adresses'] = array();
            $sync['adresses']['livraison'] = $this->create_adresse("livr", $order);
            $sync['adresses']['facturation'] = $this->create_adresse("fact", $order);

            $commande['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
            $commande['ref_doc'] = $order->getIncrementId();
            $commande['ref_contact'] = $order->getCustomerId();
            $commande['ref_adresse_livraison'] = $order->getShippingAddress()->getId();
            $commande['ref_adr_contact'] = $adresse_fact->getId();
            $commande['date_creation_doc'] = $order->getCreatedAt();
            $commande['date_livraison'] = '';

            $commande['id_livraison_mode'] = $order->getShippingMethod();
            
            $commande['docs_lines'] = array();
            
            $customer_comment="";
            
            try {
                $customer_comment = $order->getCustomerNote();
            } 
            catch (Exception $e){
                    
            }
            
            if (empty($customer_comment)) {
                try {
                    $customer_comment = $order->getOnestepcheckoutCustomercomment();
                } 
                catch (Exception $e){
                        
                }
            }
            
            try {
                // Commentaire de commande de SG Distri / Pieceauto via un module particulier
                $tm_field_1 = $order->getTmField1();
                if (!empty($customer_comment)) {
                    $customer_comment .= "\n";
                }
                $customer_comment .= $tm_field_1;
            }
            catch (Exception $e){

            }
            
            $commande['commentaire'] = (!empty($customer_comment))?$customer_comment:"";
            
            $ordre = 0;
            $num_line = -1;
            $lines = $order->getAllItems();
                        
            $last_prix_parent = 0;
            $parent_line = null;
            
            foreach ($lines as $order_line) {
                $infos_options="";
                $_options = $order_line->getProductOptions();
                if(!empty($_options["options"])) {
                    foreach($_options["options"] as $option) {
                        if(empty($infos_options)) $infos_options = "Personnalisation :\n";
                        $infos_options .= $option['label']." : ".$option['print_value'] ."\n";
                    }
                }
            
                $product = Mage::getModel('catalog/product')->load($order_line->getProductId());
                
                if(!$product->getId()) continue;
                if($product->isConfigurable()){
                    //article parent
                    $last_prix_parent = $order_line->getPrice();
                    $parent_line = $order_line;
                    continue;
                }
                
                $bolenfant = false;
                $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($product->getId());
                if(!$parentIds) {
                    $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
                }
                if(isset($parentIds[0])) {
                    $parent = Mage::getModel('catalog/product')->load($parentIds[0]);
                    LMB_EDI_Model_EDI::trace("debug_HA2", "Cest un enfant");
                    $bolenfant = true;
                }
                
                ++$num_line;
                ++$ordre;
                $commande['docs_lines'][$num_line]['ref_doc_line'] = $order_line->getQuoteItemId();
                $commande['docs_lines'][$num_line]['ref_doc'] = $order->getIncrementId();
                $commande['docs_lines'][$num_line]['ref_article'] = $order_line->getProductId();
                $commande['docs_lines'][$num_line]['ref_interne'] = $order_line->getSku();
                /* @todo trouver comment savoir si article est variant */
                if($bolenfant) {
                    $commande['docs_lines'][$num_line]['variante'] = $order_line->getProductId();
                    $prix_enfant = floatval($order_line->getPrice());
                    if (empty($prix_enfant)) {
                        $prix_enfant = $last_prix_parent;
                    }
                    $commande['docs_lines'][$num_line]['pu_ht'] = $prix_enfant;
                } else {
                    $commande['docs_lines'][$num_line]['variante'] = false;
                    $commande['docs_lines'][$num_line]['pu_ht'] = $order_line->getPrice();
                }
                                
                $tva = $order_line->getTaxPercent();
                if (!empty($parent_line)) {
                    $tva = $parent_line->getTaxPercent();
                }
                if (empty($tva) || (float) $tva == 0) {
                    $tva = 0;
                }
                
                $commande['docs_lines'][$num_line]['tva'] = $tva;
                
                $discount = $order_line->getDiscountPercent();
                if (!empty($parent_line)) {
                    $discount = $parent_line->getDiscountPercent();
                }
                
                if (empty($discount) || (float) $discount == 0) {
                    $discount_amount = $order_line->getDiscountAmount();
                    if (!empty($parent_line)) {
                        $discount_amount = $parent_line->getDiscountAmount();
                    }
                    
                    if (!empty($discount_amount) && (float) $discount_amount !== 0) {
                        $pu_total = $order_line->getQtyOrdered() * $commande['docs_lines'][$num_line]['pu_ht'];
                        $remise = $discount_amount / (1+$tva/100);
                        $discount = 0;
                        if ($pu_total > 0) {
                            $discount = round((100 * $remise) / $pu_total, 2);
                        }
                    }
                }
                
                $commande['docs_lines'][$num_line]['remise'] = !empty($discount) ? $discount : 0;
                
                $commande['docs_lines'][$num_line]['lib_article'] = $order_line->getName();
                //$commande['docs_lines'][$num_line]['desc_article'] = $order_line->getDescription();
                $commande['docs_lines'][$num_line]['desc_article'] = !empty($infos_options)?$infos_options:"";
                $commande['docs_lines'][$num_line]['qte'] = $order_line->getQtyOrdered();
                
                $commande['docs_lines'][$num_line]['ordre'] = $ordre;
                $commande['docs_lines'][$num_line]['ref_doc_line_parent'] = '';

                /* @todo trouver l'écotaxe */
                $id_ecotaxe = 'deee';
                $product = Mage::getModel('catalog/product')->load($order_line->getProductId());
                $ecotaxe = $product->getData($id_ecotaxe);
                if (!empty($ecotaxe[0])) {
                    ++$num_line;
                    $commande['docs_lines'][$num_line]['ref_doc_line'] = "";
                    $commande['docs_lines'][$num_line]['ref_doc'] = $order->getIncrementId();
                    $commande['docs_lines'][$num_line]['ref_article'] = "TAXE";
                    $commande['docs_lines'][$num_line]['lib_article'] = "Ecotaxe";
                    $commande['docs_lines'][$num_line]['desc_article'] = "";
                    $commande['docs_lines'][$num_line]['qte'] = 1;
                    $commande['docs_lines'][$num_line]['pu_ht'] = $ecotaxe[0]['value']; // @todo déefinir la réegle de calcul
                    $commande['docs_lines'][$num_line]['tva'] = '0';
                    $commande['docs_lines'][$num_line]['ordre'] = $ordre;
                    $commande['docs_lines'][$num_line]['ref_doc_line_parent'] = $order_line->getQuoteItemId();
                }
                
                $parent_line = null;
            }
            
            //gestion des bon de reduction
            if ($order->getDiscountAmount() > 0) {
                ++$num_line;
                ++$ordre;
                $commande['docs_lines'][$num_line]['ref_doc_line'] = "";
                $commande['docs_lines'][$num_line]['ref_doc'] = $order->getIncrementId();
                $commande['docs_lines'][$num_line]['ref_article'] = 'bon_reduction';
                $commande['docs_lines'][$num_line]['lib_article'] = 'Bon de reduction';
                $commande['docs_lines'][$num_line]['desc_article'] = "";
                $commande['docs_lines'][$num_line]['qte'] = '1';
                $commande['docs_lines'][$num_line]['pu_ht'] = - $order->getDiscountAmount();
                $commande['docs_lines'][$num_line]['tva'] = $taux_tva;
                $commande['docs_lines'][$num_line]['ordre'] = $ordre;
                $commande['docs_lines'][$num_line]['ref_doc_line_parent'] = '';
            }
            
            $rgm_infos = "Reglements : \n";
            $paiements = $order->getPaymentsCollection();
            foreach ($paiements as $paiement) {
                $rgm_infos .= $paiement->getMethod()."\n";
                LMB_EDI_Model_EDI::trace("debug", print_r($paiement, true));
            }
            
            //gestion des frais de ports
            ++$num_line;
            ++$ordre;
            $commande['docs_lines'][$num_line]['ref_doc_line'] = "";
            $commande['docs_lines'][$num_line]['ref_doc'] = $order->getIncrementId();
            $commande['docs_lines'][$num_line]['ref_article'] = 'frais_port';
            $commande['docs_lines'][$num_line]['lib_article'] = 'Frais de ports - ' . $order->getShippingMethod();
            $commande['docs_lines'][$num_line]['desc_article'] = $order->getShippingDescription()."\n".$rgm_infos;
            $commande['docs_lines'][$num_line]['qte'] = '1';
            
            $taux_tva_shipping = LMB_EDI_Model_Config::GET_PARAM('taux_tva_shipping');
            if (empty($taux_tva_shipping)) {
                $taux_tva_shipping = ($order->getShippingAmount()!=0)?abs(round($order->getShippingTaxAmount()/$order->getShippingAmount()*100,1)):0;
            }
            //$commande['docs_lines'][$num_line]['pu_ht'] =(LMB_EDI_Model_Config::TARIFS_TTC())?$order->getShippingAmount()/(1+$taux_tva_shipping/100):$order->getShippingAmount();
            $commande['docs_lines'][$num_line]['pu_ht'] = $order->getShippingAmount();
            $commande['docs_lines'][$num_line]['tva'] = $taux_tva_shipping;
            $commande['docs_lines'][$num_line]['ordre'] = $ordre;
            $commande['docs_lines'][$num_line]['ref_doc_line_parent'] = '';

            $sync['commande'] = $commande;
        } catch (Exception $e) {
            return LMB_EDI_Model_EDI::error($e->getMessage());
        }
        LMB_EDI_Model_EDI::traceDebug("debug_commande", print_r($sync, true));
        return $sync;
    }
    
    public function create_order($id_order) {
        $delay = LMB_EDI_Model_Config::GET_PARAM('messageSendDelay');
        if (!empty($delay)){
            sleep($delay);
        }

        self::envoi_LMB("recup_commande", $this->getOrderInfos($id_order));
        LMB_EDI_Model_EDI::trace("envoi", "***************FIN CREATE ORDER $id_order****************");

        $this->create_payment($id_order);
        return true;
    }

    /**
     * Synchronisation d'un payment passée par le e-commerce
     * @param int $id_order id de la commande concerner
     * @return boolean
     *
     * envoi: create_reglement
     * $payment['id_order'] = id de la commande;
     * $payment['type'] = type de payment ( 'paypal', 'bankwire' );
     * $payment['montant'] = montant du reglement;
     * $payment['date'] = date du reglement;
     *
     */
    public function create_payment($id_order) {
        LMB_EDI_Model_EDI::trace("envoi", "***************DEBUT CREATE PAYMENT $id_order****************");
        $payment = $this->getPaymentInfos($id_order);
        if (is_array($payment)) {
            self::envoi_LMB("create_reglement", $payment);
        }
        
        LMB_EDI_Model_EDI::trace("envoi", "***************FIN CREATE PAYMENT $id_order****************");
        return true;
    }


    public function getPaymentInfos($id_order) {
        LMB_EDI_Model_EDI::trace("envoi", "***************DEBUT CREATE PAYMENT $id_order****************");

        $order = Mage::getModel('sales/order')->loadByIncrementId($id_order);
        $paiements = $order->getPaymentsCollection();

        $echeancier = array();
        $echeancier['ref_commande'] = $id_order;
        foreach ($paiements as $paiement) {
            $payment = array();
            switch ($paiement->getMethod()) {
                //Paiement contant OU crédit à la réception
                case "receiveandpay_cb": case "receiveandpay_cr":
                    $payment['mode'] = "cb";
                    $payment['type_rgmt'] = "solde";
                    break;
                //Paiement chèque
                case "checkmo":
                    $payment['mode'] = "cheque";
                    $payment['type_rgmt'] = "acompte";
                    break;
                //Paiement virement
                case "bankpayment":
                    $payment['mode'] = "virement";
                    $payment['type_rgmt'] = "acompte";
                    break;
                default:
                    $payment['mode'] = "cb";
                    $payment['type_rgmt'] = "acompte";
                    break;
            }
            $payment['module'] = $paiement->getMethod();
            $payment['montant'] = $paiement->getAmountOrdered();
            $payment['date'] = $paiement->getCreatedAt();
            if(empty($payment['date'])) $payment['date'] = $order->getCreatedAt();
            $payment['nb_jours'] = 0;
            $notes = $paiement->getAdditionalData();
            try {
                if (preg_match("/^[siaO]{1}:/", $notes)) {
                    $notes = unserialize($paiement->getAdditionalData());
                }
            }
            catch(Exception $e) {
                
            }
            $payment['notes'] = (is_array($notes)) ? implode("\n", $notes) : $notes;
            $echeancier['echeancier'][] = $payment;
        }

        if ($paiements->count() > 0) { //S'il s'agit du premier paiement
            $paiement = $paiements->getFirstItem();

            if ($paiement->getAmountPaid()) { //Tester également si le reglement est valide...
                $payment = array();
                $payment['id_order'] = $id_order;
                $payment['type'] = $paiement->getMethod();
                $payment['montant'] = $paiement->getAmountPaid();
                $payment['date'] = $paiement->getCreatedAt();
                if(empty($payment['date'])) {
                    $payment['date'] = $order->getCreatedAt();
                }
                LMB_EDI_Model_EDI::traceDebug("debug_payment", print_r($payment,true));
                
                return $payment;
            }
        } else {
            LMB_EDI_Model_EDI::trace("envoi", "Payment à ne PAS transférer");
        }
        LMB_EDI_Model_EDI::trace("envoi", "***************FIN CREATE PAYMENT $id_order****************");
        return true;
    }
    
    public function recup_commandes() {
        $orders = Mage::getResourceModel('sales/order_collection')->setOrder('created_at', 'asc');
        foreach ($orders as $order) {
            LMB_EDI_Model_Liaison_Event::create("create_order", $order->getRealOrderId());
        }
        
        return true;
    }
    
    public function recup_clients() {
        /**
         * Filter format
         * 
         * array("from"=>$fromValue, "to"=>$toValue)
         * array("like"=>$likeValue)
         * array("neq"=>$notEqualValue)
         * array("in"=>array($inValues))
         * array("nin"=>array($notInValues))
         * 
         */
        $clients = Mage::getResourceModel('customer/customer_collection')
                /*
                ->addAttributeToSelect('*')
                ->addFieldToFilter('group_id', array("in" => array(1)))
                //*/
                ->setOrder('created_at', 'asc');
        
        foreach ($clients as $client) {
            LMB_EDI_Model_Liaison_Event::create("recup_client", $client->getId());
        }
        
        return true;
    }

    public function recup_client($id_client) {
        $client = $this->create_client($id_client, true);
        if (!empty($client)) {
            self::envoi_LMB("recup_client", $client);
        }
        
        return true;
    }

    public function recup_categs() {
        $categories = Mage::getResourceModel('catalog/category_collection')
                ->setOrder('level', 'asc')
                ->setOrder('position', 'asc');
        
        foreach ($categories as $categorie) {
            LMB_EDI_Model_Liaison_Event::create("create_categorie", $categorie->getId());
        }

        return true;
    }

    public function create_categorie($id) {
        LMB_EDI_Model_EDI::trace("envoi", "***************DEBUT CREATE CATEG $id****************");
        $cat = Mage::getModel('catalog/category');
        $cat->load($id);

        if ($cat->getIsActive() && $cat->getId() != Mage::app()->getWebsite(true)->getDefaultStore()->getRootCategoryId()) {
            $parent = ($cat->getParentId() != Mage::app()->getWebsite(true)->getDefaultStore()->getRootCategoryId()) ? $cat->getParentId() : "";
            if(!empty($parent)) $parent = Mage::getModel('catalog/category')->load($parent);
            $categorie['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
            $categorie['ref_art_categ'] = $cat->getId();
            $categorie['ref_art_categ_parent'] = is_object($parent)?$parent->getId():"";
            $categorie['nom'] = $cat->getName();
            $categorie['description'] = $cat->getDescription();
            $categorie['active'] = $cat->getIsActive();

            self::envoi_LMB("create_categorie", $categorie);
        }
        LMB_EDI_Model_EDI::trace("envoi", "***************FIN CREATE CATEG $id****************");
        return true;
    }

    public function recup_marques() {
        $idManufacturer = LMB_EDI_Model_Config::GET_PARAM('marque_code');
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $idManufacturer);
        $manufacturers = array();
        foreach ($attribute->getSource()->getAllOptions(true, true) as $option) {
            if (!empty($option['label'])) {
                $const['ref_contact'] = $option['value'];
                $const['nom'] = $option['label'];
                
                self::envoi_LMB("create_constructeur", $const);
            }
        }
        return true;
    }

    public function recup_products($id_categ=false) {
        
        if ($id_categ) {
            $cat = Mage::getModel('catalog/category');
            $cat->load($id_categ);
            $prods = $cat->getProductCollection();

            foreach ($prods as $prod) {
                LMB_EDI_Model_EDI::traceDebug("recup_products", $prod->getId());
                LMB_EDI_Model_Liaison_Event::create("create_article", $prod->getId());
            }
        } else {
            $products = Mage::getModel('catalog/product')->getCollection()
                            ->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', Mage::app()->getWebsite(true)->getDefaultStore()->getId())
                            ->setOrder('type_id', 'asc')
                            ->setOrder('category_id', 'asc');

            foreach ($products as $prod) {
                LMB_EDI_Model_EDI::traceDebug("recup_products_all", $prod->getTypeId() . "|" . $prod->getId()."|".$prod->getStatus());
                if($prod->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_ENABLED){
                        LMB_EDI_Model_EDI::traceDebug("recup_products", $prod->getId());
                        LMB_EDI_Model_Liaison_Event::create("create_article", $prod->getId());
                }
            }
        }
        
        return true;
    }
    
    private function getTaxRateByProduct($product) {
        $taxClassId = $product->getTaxClassId();
        $taxRatesJSON = Mage::helper('tax/data')->getTaxRatesByProductClass();
        $taxRates = Zend_JSON::decode($taxRatesJSON);
        if(array_key_exists('value_'.$taxClassId, $taxRates)) {
            return floatval($taxRates['value_'.$taxClassId]) ;
        }
        
        return false;
    }
    
    public function create_article($id) {
        LMB_EDI_Model_EDI::trace("envoi", "***************DEBUT create_article $id ****************");
        
        $idManufacturer = LMB_EDI_Model_Config::GET_PARAM('marque_code');
        $idCodeBarre = LMB_EDI_Model_Config::GET_PARAM('code_barre_code');
        $idPrixAchat = LMB_EDI_Model_Config::GET_PARAM('prix_achat_code');
        $idEcotaxe = LMB_EDI_Model_Config::GET_PARAM('ecotaxe_code');
        if (empty($idEcotaxe)) {
            $idEcotaxe = 'deee';
        }
        $taux_tva = LMB_EDI_Model_Config::GET_PARAM('taux_tva');
        if (empty($taux_tva)) {
            $taux_tva = 20;
        }
        
        $product = Mage::getModel('catalog/product')->load($id);
        if($this->getTaxRateByProduct($product) !== false){
            $taux_tva = $this->getTaxRateByProduct($product);
        }
        $article["ref_article"] = $product->getId();
        // Pour la rétro-compatibilité
        $article['ref_article_parent'] = "";
        $article["attribute_set"] = $product->getAttributeSetId();
        $article["reference"] = $product->getSku();
        $article["lib_article"] = $product->getName();
        $article["desc_longue"] = $product->getDescription();
        $article["desc_courte"] = $product->getShortDescription();
        $article["tva"] = $taux_tva;
        $article["poids"] = $product->getWeight();
        $article["keywords"] = $product->getMetaKeywords();
        $article["pp_ht"] = (LMB_EDI_Model_Config::TARIFS_TTC())?$product->getPrice()/(1+$taux_tva/100):$product->getPrice();
        $article["pu_ht"] = (LMB_EDI_Model_Config::TARIFS_TTC())?$product->getPrice()/(1+$taux_tva/100):$product->getPrice();
        
        if($product->getSpecialPrice()) {
            $article["promo_ht"] = (LMB_EDI_Model_Config::TARIFS_TTC())?$product->getSpecialPrice()/(1+$taux_tva/100):$product->getSpecialPrice();
            $article["promo_debut"] = $product->getSpecialFromDate();
            $article["promo_fin"] = $product->getSpecialToDate();
        }
        
        if ($idManufacturer) {
            $article["id_marque"] = $product->getData($idManufacturer);
            $article["marque"] = $product->getAttributeText($idManufacturer);

        }
        $article["code_barre"] = ($idCodeBarre) ? $product->getData($idCodeBarre) : "";
        $article["pa"] = ($idPrixAchat) ? $product->getData($idPrixAchat) : "";

        /* Gestion de la typologie */
        $article["variante"] = 0;
        $article["modele"] = "materiel";
        switch ($product->getTypeId()) {
            case Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL:
                $article["modele"] = "service";
                break;
            default:
                $article["modele"] = "materiel";
                break;
        }

        $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($id);
        //Enfant de variante
        if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && !empty($parentIds[0])) {
            $article["variante"] = 1;
            $article['ref_article_parent'] = $parentIds[0];
            $parent = Mage::getModel('catalog/product')->load($parentIds[0]);
            $cat = $product->getCategoryCollection();
        }
        //Parent de variante
        else if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            $article["variante"] = 2;
            foreach ($product->getTypeInstance(true)->getUsedProductAttributes($product) as $attribute) {
                if ($attribute->getIsUnique()
                        || !$attribute->getData("is_user_defined")
                        || !$attribute->getIsVisible()) {
                    continue;
                }
                $var_codes[] = $attribute->getAttributeCode();
                $lib = $attribute->getFrontendLabel();
                $article['caracs_variantes'][$attribute->getAttributeCode()]['ref_carac'] = $attribute->getAttributeCode();
                $article['caracs_variantes'][$attribute->getAttributeCode()]['lib_carac'] = $lib;
            }
            //Un article configurable n'appartient à aucune categ.
            //On récupère donc celle d'un enfant
            $childProducts = Mage::getModel('catalog/product_type_configurable')
                        ->getUsedProducts(null,$product);
            if(empty($childProducts[0])){
                LMB_EDI_Model_EDI::trace("import", "Produit ".$product->getId()." non importable (categ non assignée et pas d'enfant)");
                return true;
            }
            $cat = $childProducts[0]->getCategoryCollection();
        } else {
            $cat = $product->getCategoryCollection();
        }
        
        $art_categ = null;
        $art_categs = array();
        
        $root_id = Mage::app()->getWebsite(true)->getDefaultStore()->getRootCategoryId();
        $category_root = Mage::getModel('catalog/category');
        $category_root->load($root_id);

        $shop_categs = $category_root->getAllChildren(true);
        
        if(is_object($cat) && $cat->count()){
            foreach($cat as $categ){
                $categ = Mage::getModel('catalog/category')->load($categ->getId());
                
                if($categ->getIsActive() && in_array($categ->getId(), $shop_categs)) {
                    if (empty($art_categ)) {
                        $art_categ = $categ->getId();
                    }
                    $art_categs[] = $categ->getId();
                }
            }
        }
        
        $article["ref_art_categ"] = $art_categ;
        $article["ref_art_categs"] = $art_categs;

        $var_codes = array();
        if ($article["variante"] == 1) {
            //Recherche des attributs non variants
            //LMB_EDI_Model_EDI::traceDebug("var", print_r($parent->getTypeInstance(true)->getUsedProductAttributes($parent), true));
            foreach ($parent->getTypeInstance(true)->getUsedProductAttributes($parent) as $attribute) {
                if ($attribute->getIsUnique()
                        || !$attribute->getData("is_user_defined")
                        || !$attribute->getIsVisible()) {
                    continue;
                }
                
                $valeur = 0;
                if (in_array($attribute->getFrontendInput(), array('boolean', 'select', 'multiselect'))) {
                    $valeur = $product->getAttributeText($attribute->getAttributeCode());
                }
                else {
                    $valeur = $product->getData($attribute->getAttributeCode());
                }
                if (!empty($valeur)) {
                    $var_codes[] = $attribute->getAttributeCode();
                    $lib = $attribute->getFrontendLabel();
                    $carac = array (
                        'ref_carac' => $attribute->getAttributeCode(),
                        'lib_carac' => $lib,
                        'val_carac' => $valeur
                    );
                    
                    $article['caracs_variantes'][] = $carac;
                }
            }
        }

        //Recherche des attributs non variants
        foreach ($product->getAttributes() as $attribute) {
            if (in_array($attribute->getAttributeCode(), $var_codes)
                    || $attribute->getIsUnique()
                    || !$attribute->getData("is_user_defined")
                    || $attribute->getAttributeCode() == 'url_key'
                    || $attribute->getAttributeCode() == $idManufacturer
                    || $attribute->getAttributeCode() == $idCodeBarre
                    || $attribute->getAttributeCode() == $idPrixAchat
                    || $attribute->getAttributeCode() == $idEcotaxe
                    || $attribute->getFrontend()->getInputType() == 'gallery'
                    || $attribute->getFrontend()->getInputType() == 'media_image'
                    || !$attribute->getIsVisible()) {
                continue;
            }
            
            $valeur = 0;
            if (in_array($attribute->getFrontendInput(), array('boolean', 'select', 'multiselect'))) {
                $valeur = $product->getAttributeText($attribute->getAttributeCode());
            }
            else {
                $valeur = $product->getData($attribute->getAttributeCode());
            }
            if (!empty($valeur)) {
                $lib = $attribute->getFrontendLabel();
                $carac = array (
                    'ref_carac' => $attribute->getAttributeCode(),
                    'lib_carac' => $lib,
                    'val_carac' => $valeur
                );

                $article['caracs_non_variantes'][] = $carac;
            }
        }

        /* Gestion des tarifs */
        $article['tarifs'] = array();        
        $article['tarifs'] = array_merge($article['tarifs'], $this->pricesToArray($product->getData('tier_price'), $article));
        $article['tarifs'] = array_merge($article['tarifs'], $this->pricesToArray($product->getData('group_price'), $article));
        
        $current_date = strftime("%Y-%m-%d");
        $from_date = $product->getSpecialFromDate();
        $to_date = $product->getSpecialToDate();
        
        $promo_exists = false;
        if (!empty($from_date) || !empty($to_date)) {
            if (($from_date <= $current_date || empty($from_date)) || ($to_date > $current_date || empty($to_date))) {
                $promo_exists = true;
            }
        }
        
        if ($promo_exists) {
            $article["promo_ht"] = (LMB_EDI_Model_Config::TARIFS_TTC())?$product->getSpecialPrice()/(1+$taux_tva/100):$product->getSpecialPrice();
            $article["promo_debut"] = $from_date;
            $article["promo_fin"] = $to_date;
        }
        
        $deee = $product->getData($idEcotaxe);
        if (!empty($deee)) {
            if (is_array($deee) && isset($deee["value"])) {
                $deee = $deee["value"];
            }
            $article["ecotax"] = $deee;
        }

        /* Gestion des stocks */
        $stockItem = Mage::getModel("cataloginventory/stock_item")
                        ->loadByProduct($id);
        $article["stock_id"] = $stockItem->getStockId();
        $article["min_stock"] = $stockItem->getMinQty();
        $article["qte"] = $stockItem->getQty();

        self::envoi_LMB("create_article", $article);
                
        LMB_EDI_Model_EDI::trace("envoi", "***************FIN create_article $id ****************");
        
        return true;
    }
    
    protected function pricesToArray($prices, $infos_article) {
        $tarifs = array();
        
        if (!is_null($prices) && is_array($prices)) {
            foreach ($prices as $price) {
                if ($price["website_id"] == 0 || $price["website_id"] == Mage::app()->getWebsite(true)->getDefaultStore()->getId()) {
                    $tarifs[] = $this->priceToArray($price, $infos_article);
                }
            }
        }
        
        return $tarifs;
    }
    
    protected function priceToArray($price, $infos_article) {
        $tarif = array();
        $taux_tva = $infos_article["tva"];
        
        $tarif['id_tarif'] = $price["cust_group"];
        // Attention, id_group #0 désigne les non loggé, Tous les groupe a pour ID #32000 (utilisation de la constante Magento)
        if ($tarif['id_tarif'] == Mage_Customer_Model_Group::CUST_GROUP_ALL) {
            $tarif['id_tarif'] = 0;
        }
        
        if (isset($price["price_qty"])) {
            $tarif['indice_qte'] = $price["price_qty"];
        }
        
        $tarif['pu_ht'] = (LMB_EDI_Model_Config::TARIFS_TTC())?$price["price"]/(1+$taux_tva/100):$price["price"];
        if (!empty($infos_article["ref_article_parent"])) {
            $tarif['ref_article_parent'] = $infos_article["ref_article_parent"];
            $tarif['id_variante'] = $infos_article["ref_article"];
            $tarif['variante'] = 1;
        }
        
        $tarif['ref_article'] = $infos_article["ref_article"];
        $tarif['prix_de_base'] = $infos_article["pu_ht"];
        $tarif['valeur_remise'] = $tarif['prix_de_base'] - $tarif['pu_ht'];
        
        return $tarif;
    }

    public function recup_images() {
        
        $imgs = array();
        $products = Mage::getModel('catalog/product')->getCollection()
                        ->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', Mage::app()->getWebsite(true)->getDefaultStore()->getId())
                        ->setOrder('type_id', 'asc')
                        ->setOrder('category_id', 'asc');

        foreach ($products as $prod) {
            $product = Mage::getModel('catalog/product')->load($prod->getId());
            if($prod->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_ENABLED) {
                $images = $product->getMediaGalleryImages();
                foreach ($images as $image) {
                    $img = array();
                    $img['id_image'] = $image['file'];
                    $img['id_product'] = $product->getId();
                    $img['url'] = Mage::getBaseUrl('media') . '/catalog/product/' . $image['file'];
                    $img['position'] = $image['position'];
                    $imgs[] = $img;
                }
            }
        }

        $tmp = array();
        for ($i = 0; $i < count($imgs); $i++) {
            $tmp[] = $imgs[$i];
            if (($i % 10 == 0 && $i != 0) || $i == count($imgs)-1) {
                LMB_EDI_Model_EDI::trace("envoi", "Envoi d'un paquet d'images");
                
                self::envoi_LMB("recup_images", $tmp);
                
                $tmp = array();
            }
        }
        
        return true;
    }
    
    protected function productToTypeVariante($product) {
        $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
        //Enfant de variante
        if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && !empty($parentIds[0])) {
            return 1;
        }
        //Parent de variante
        else if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            return 2;            
        }
        
        // Simple / Défaut
        return 0;            
    }
    
    public function recup_art_liaisons() {   
        $products = Mage::getModel('catalog/product')->getCollection()
                        ->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', Mage::app()->getWebsite(true)->getDefaultStore()->getId())
                        ->setOrder('type_id', 'asc')
                        ->setOrder('category_id', 'asc');

        foreach ($products as $prod) {
            if($prod->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_ENABLED){
                    LMB_EDI_Model_Liaison_Event::create("update_art_liaisons", $prod->getId());
            }
        }
        
        return true;
    }
    
    public function update_art_liaisons($id_product) {
        
        $product = Mage::getModel('catalog/product')->load($id_product);
        $id_load = $product->getId();
        if(empty($id_load)) {
            return true;
        }
        
        $tab = array();
        $tab["ref_article"] = $product->getId();
        $tab["variante"] = $this->productToTypeVariante($product);
        
        $tab["liaisons"] = array();
        $type = Mage_Catalog_Model_Product_Link::LINK_TYPE_RELATED;
        $ids_product_links = Mage::getModel('catalog/product_link')->getLinkCollection()
            ->addFieldToFilter('product_id', $product->getId())
            ->addFieldToFilter('link_type_id', $type)
            ->getColumnValues('linked_product_id');

        if (empty($ids_product_links)) {
            return true;
        }
        
        $liaisons = array();
        foreach ($ids_product_links as $id_product_link) {
            $product_link = Mage::getModel('catalog/product')->load($id_product_link);
            $id_load = $product_link->getId();
            if(empty($id_load)) {
                continue;
            }
            
            $liaison = array();
            $liaison["ref_article"] = $product_link->getId();
            $liaison["variante"] = $this->productToTypeVariante($product_link);
            $liaisons[] = $liaison;
        }
        
        if (empty($liaisons)) {
            return true;
        }
        
        $tab["liaisons"] = $liaisons;
        LMB_EDI_Model_EDI::trace("art_liaisons", print_r($tab, true));
        
        self::envoi_LMB("update_article_liaisons", $tab);
        
        return true;
    }
    
    public function recup_caracs() {

        return true;
    }
}
