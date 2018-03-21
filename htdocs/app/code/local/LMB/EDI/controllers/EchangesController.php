<?php

class LMB_EDI_EchangesController extends Mage_Core_Controller_Front_Action {

    public function receiverAction() {
        LMB_EDI_Model_Config::$log_file = "transaction_pmrq.log";

        ignore_user_abort(true);
        set_time_limit(60);
        error_reporting(E_ALL);
        //ob_start();
        //register_shutdown_function(array("LMB_EDI_EchangesController", "exit_handler"));
        set_error_handler(array("LMB_EDI_EchangesController", "error_handler"));
        set_exception_handler(array("LMB_EDI_EchangesController", "exception_handler"));

        $code = $this->getRequest()->getParam("serial_code", false);
        if ($code == LMB_EDI_Model_Config::CODE_CONNECTION()) {
            $options = array(
                'uri' => 'urn:Liaison'
            );
            $server = new SoapServer(NULL, $options);
            $server->addFunction("Receiver");
            //$server->setClass("LMB_EDI_Model_SoapReceiver");
            $server->handle();
        } else {
            LMB_EDI_Model_EDI::trace("Auth_error", "ERREUR DE CODE DE CONNEXION !");
            exit("ERREUR DE CODE DE CONNEXION !");
        }
    }

    public function processAction() {
        $ioAdapter = new Varien_Io_File();
        try {
            // Create temporary directory for api
            $dir1 = Mage::getBaseDir('var') . "/log/lmbedi/";
            $dir2 = Mage::getBaseDir('var') . "/log/lmbedi/pid/";
            $ioAdapter->checkAndCreateFolder($dir1);
            $ioAdapter->checkAndCreateFolder($dir2);
        } catch (Exception $e) {
            Mage::logException($e);
        }
        ignore_user_abort(true);
        set_time_limit(0);
        error_reporting(E_ALL);
        register_shutdown_function(array($this, "exit_handler"), getcwd());
        set_error_handler(array($this, "error_handler"));
        set_exception_handler(array($this, "exception_handler"));

        /* CETTE LIGNE EST INDISPENSABLE !!!!! */
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        $process = $this->getRequest()->getParam("start", false);
        switch ($process) {
            case 'messages_envoi':
                $queue = new LMB_EDI_Model_Queue('LMB_EDI_Model_Liaison_MessageEnvoi', 5);
                break;
            case 'messages_recu':
                $queue = new LMB_EDI_Model_Queue('LMB_EDI_Model_Liaison_MessageRecu', 0);
                break;
            case 'events':
                $queue = new LMB_EDI_Model_Queue('LMB_EDI_Model_Liaison_Event', 3);
                break;
        }
        $queue->start();
    }

    public function statutAction() {
        $code = $this->getRequest()->getParam("serial_code", false);
        if ($code == LMB_EDI_Model_Config::CODE_CONNECTION()) {
            header("Content-type: application/xml");
            $bdd = Mage::getSingleton('core/resource')->getConnection('core_write');
            $doc = new DOMDocument();
            $conf = $doc->createElement('pid_statut');

			$etat = LMB_EDI_Model_Liaison_Event::getProcess()->get_pid();
            $query = "SELECT id FROM " . Mage::getConfig()->getTablePrefix() . "edi_events_queue WHERE etat = " . LMB_EDI_Model_Config::$TRAITE_CODE;
            $nb_traite = $bdd->query($query)->rowCount();
            $query = "SELECT id FROM " . Mage::getConfig()->getTablePrefix() . "edi_events_queue WHERE etat = " . LMB_EDI_Model_Config::$ERROR_CODE;
            $nb_erreur = $bdd->query($query)->rowCount();
            $events = $doc->createElement('events');
            $events->setAttribute('code', $code);
            $events->setAttribute('nb_traite', $nb_traite);
            $events->setAttribute('nb_erreur', $nb_erreur);
            $events->setAttribute('etat', $etat);
            $conf->appendChild($events);

			$etat = LMB_EDI_Model_Liaison_MessageRecu::getProcess()->get_pid();
            $query = "SELECT id FROM " . Mage::getConfig()->getTablePrefix() . "edi_messages_recu_queue WHERE etat = " . LMB_EDI_Model_Config::$TRAITE_CODE;
            $nb_traite = $bdd->query($query)->rowCount();
            $query = "SELECT id FROM " . Mage::getConfig()->getTablePrefix() . "edi_messages_recu_queue WHERE etat = " . LMB_EDI_Model_Config::$ERROR_CODE;
            $nb_erreur = $bdd->query($query)->rowCount();
            $mess_recu = $doc->createElement('messages_recu');
            $mess_recu->setAttribute('code', $code);
            $mess_recu->setAttribute('nb_traite', $nb_traite);
            $mess_recu->setAttribute('nb_erreur', $nb_erreur);
            $mess_recu->setAttribute('etat', $etat);
            $conf->appendChild($mess_recu);

			$etat = LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()->get_pid();
            $query = "SELECT id FROM " . Mage::getConfig()->getTablePrefix() . "edi_messages_envoi_queue WHERE etat = " . LMB_EDI_Model_Config::$TRAITE_CODE;
            $nb_traite = $bdd->query($query)->rowCount();
            $query = "SELECT id FROM " . Mage::getConfig()->getTablePrefix() . "edi_messages_envoi_queue WHERE etat = " . LMB_EDI_Model_Config::$ERROR_CODE;
            $nb_erreur = $bdd->query($query)->rowCount();
            $mess_envoi = $doc->createElement('messages_envoi');
            $mess_envoi->setAttribute('code', $code);
            $mess_envoi->setAttribute('nb_traite', $nb_traite);
            $mess_envoi->setAttribute('nb_erreur', $nb_erreur);
            $mess_envoi->setAttribute('etat', $etat);
            $conf->appendChild($mess_envoi);
            $doc->appendChild($conf);
            echo $doc->saveXML();
        } else {
            LMB_EDI_Model_EDI::trace("Auth_error", "ERREUR DE CODE DE CONNEXION !");
            exit("ERREUR DE CODE DE CONNEXION !");
        }
    }
		
	public function infosMarquesAction(){
		$code = $this->getRequest()->getParam("serial_code", false);
        if ($code == LMB_EDI_Model_Config::CODE_CONNECTION()) {
			header("Content-type: application/xml");
            $doc = new DOMDocument();
            $products = $doc->createElement('marques');

			$idManufacturer = 'manufacturer';
			$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $idManufacturer);
			$manufacturers = array();
			foreach ($attribute->getSource()->getAllOptions(true, true) as $option) {
				if (!empty($option['label'])) {
					$prod_xml = $doc->createElement('marque');
					$prod_xml->setAttribute("id", $option['value']);
					$ref_xml = $doc->createElement('nom');
					$ref_xml_dat = $doc->createCDATASection($option['label']);
					$ref_xml->appendChild($ref_xml_dat);
					$prod_xml->appendChild($ref_xml);
					$products->appendChild($prod_xml);
				}
			}
			
			$doc->appendChild($products);
            echo $doc->saveXML();
		} else {
            LMB_EDI_Model_EDI::trace("Auth_error", "ERREUR DE CODE DE CONNEXION !");
            exit("ERREUR DE CODE DE CONNEXION !");
        }
	}
	
	public function infosProductsAction(){
		$code = $this->getRequest()->getParam("serial_code", false);
        if ($code == LMB_EDI_Model_Config::CODE_CONNECTION()) {
			header("Content-type: application/xml");
            $doc = new DOMDocument();
            $products = $doc->createElement('products');

			$id_categ = null;
			$cat = Mage::getModel('catalog/category');
            $cat->load($id_categ);
            $prods = $cat->getProductCollection();
			foreach($prods as $prod){
				$product = Mage::getModel('catalog/product')->load($prod->getId());
				$prod_xml = $doc->createElement('product');
				$prod_xml->setAttribute("id", $product->getId());
				$prod_xml->setAttribute("statut", $product->getStatus());
				$prod_xml->setAttribute("attribute_set", $product->getAttributeSetId());
				$prod_xml->setAttribute("type", $product->getTypeId());
				$prod_xml->setAttribute("variante", ($product->loadParentProductIds()->getData('parent_product_ids'))?1:0);
				
				$ref_xml = $doc->createElement('reference');
				$ref_xml_dat = $doc->createCDATASection($product->getSku());
				$ref_xml->appendChild($ref_xml_dat);
				$prod_xml->appendChild($ref_xml);
				$ref_xml = $doc->createElement('name');
				$ref_xml_dat = $doc->createCDATASection($product->getName());
				$ref_xml->appendChild($ref_xml_dat);
				$prod_xml->appendChild($ref_xml);
				$ref_xml = $doc->createElement('prix');
				$ref_xml_dat = $doc->createCDATASection($product->getPrice());
				$ref_xml->appendChild($ref_xml_dat);
				$prod_xml->appendChild($ref_xml);

				$products->appendChild($prod_xml);
			}
			
			$doc->appendChild($products);
            echo $doc->saveXML();
		} else {
            LMB_EDI_Model_EDI::trace("Auth_error", "ERREUR DE CODE DE CONNEXION !");
            exit("ERREUR DE CODE DE CONNEXION !");
        }
	}
	
	public function infosImagesAction(){
		$code = $this->getRequest()->getParam("serial_code", false);
        if ($code == LMB_EDI_Model_Config::CODE_CONNECTION()) {
			header("Content-type: application/xml");
            $doc = new DOMDocument();
            $imgs = $doc->createElement('images');

			$id_categ = null;
			$cat = Mage::getModel('catalog/category');
            $cat->load($id_categ);
            $prods = $cat->getProductCollection();
			foreach($prods as $prod){
				if (!empty($done[$prod->getId()]))
					continue;
				$done[$prod->getId()] = $prod->getId();
				$product = Mage::getSingleton('catalog/product')->load($prod->getId());
				$images = $product->getMediaGalleryImages();
				foreach ($images as $image) {				
					$img_xml = $doc->createElement('image');
					$img_xml->setAttribute("id", $image['id']);
					$img_xml->setAttribute("id_product", $product->getId());
					$img_xml->setAttribute("position", $image['position']);
					/*foreach ($product->getMediaAttributes()->getAttributeBackend()->getImageTypes() as $typeId=>$type){
						$img_xml->setAttribute("type_".$typeId, $type);
					}*/
					$img_xml->setAttribute("file", $image['file']);

					$ref_xml = $doc->createElement('url');
					$ref_xml_dat = $doc->createCDATASection($image['url']);
					$ref_xml->appendChild($ref_xml_dat);
					$img_xml->appendChild($ref_xml);

					$imgs->appendChild($img_xml);
				}
			}
			
			$doc->appendChild($imgs);
            echo $doc->saveXML();
		} else {
            LMB_EDI_Model_EDI::trace("Auth_error", "ERREUR DE CODE DE CONNEXION !");
            exit("ERREUR DE CODE DE CONNEXION !");
        }
	}
	
	public function infosAction(){
		$code = $this->getRequest()->getParam("serial_code", false);
		$type = $this->getRequest()->getParam("type", false);
		$action = $this->getRequest()->getParam("action", false);
		$element = $this->getRequest()->getParam("element", false);
		$variante = $this->getRequest()->getParam("variante", false);
		if ($code == LMB_EDI_Model_Config::CODE_CONNECTION()) {
			if($type == "articles"){
				header("Content-type: application/xml");
				$doc = new DOMDocument();
				
				switch($action){
					case 'article_soft' :
						$price_var = 0;
						if($variante == 1){
							$query = "SELECT * FROM " . module_liaison::$TABLE_PREFIX . "product_attribute 
										WHERE id_product_attribute = ".$bdd->quote($element);
							$res = $bdd->query($query);
							$decli = $res->fetchObject();
							if($decli){
								$price_var = $decli->price;
								$qte_stock = $decli->quantity;
								$product = new Product($decli->id_product);
							} else {
								$product = new Product();
							}
						} else {
							$product = Mage::getModel('catalog/product')->load($element);
                                                        $stockData = $product->getStockData();
							$qte_stock = $stockData ? $stockData->getQty() : 0;
						}
						$prod_xml = $doc->createElement('article');
						if($product->getId()){
							$prod_xml->setAttribute("id", $element);
						} else {
							$prod_xml->setAttribute("id", "");
						}
						if($variante){
							$prod_xml->setAttribute("id_parent", $decli->id_product);
						}
						$prod_xml->setAttribute("variante", $variante);
						$prod_xml->setAttribute("statut", $product->getStatus());
						$prod_xml->setAttribute("attribute_set", $product->getAttributeSetId());
						$ref_xml = $doc->createElement('name');
						$ref_xml_dat = $doc->createCDATASection($product->getName());
						$ref_xml->appendChild($ref_xml_dat);
						$prod_xml->appendChild($ref_xml);
						$ref_xml = $doc->createElement('prix');
						$ref_xml_dat = $doc->createCDATASection($product->getPrice());
						$ref_xml->appendChild($ref_xml_dat);
						$prod_xml->appendChild($ref_xml);
						$ref_xml = $doc->createElement('stock_virtuel');
						$ref_xml_dat = $doc->createCDATASection($qte_stock);
						$ref_xml->appendChild($ref_xml_dat);
						$prod_xml->appendChild($ref_xml);
						$doc->appendChild($prod_xml);
					break;

					case 'article_complet' :
					break;
				}
				echo $doc->saveXML();
			}
		} else {
            LMB_EDI_Model_EDI::trace("Auth_error", "ERREUR DE CODE DE CONNEXION !");
            exit("ERREUR DE CODE DE CONNEXION !");
        }
	}
	
	public function testAction(){
		$path_image = "/b/c/bc73cc8cae1ef80ea7c9014b24c95f04_1.png";
		$id_product = 96;
	
		$product = Mage::getModel('catalog/product')->load($id_product);
		$attributes = $product->getTypeInstance(true)
						->getSetAttributes($product);
		//LMB_EDI_Model_EDI::traceDebug("image", print_r($attributes,true));
		$mediaGalleryAttribute = $attributes["media_gallery"];

		$i = $mediaGalleryAttribute->getBackend()->getImage($product, $path_image);
		print_r($i);
	}

    public static function receiver($sig, $str) {
        if ($str != "") {
            //$writer = Mage::getSingleton('core/resource')->getConnection('core_write');
            //$reader = Mage::getSingleton('core/resource')->getConnection('core_read');
			$cfg = Mage::getConfig()->getResourceConnectionConfig('core_write');
            try {
                $config_pdo = 'mysql:host='.$cfg->host.';dbname='.$cfg->dbname.'';
                $pdo = new PDO($config_pdo, $cfg->username, $cfg->password);
            }
            catch (Exception $e) {
                LMB_EDI_Model_EDI::trace("debug_pdo", print_r($e, true));
                throw $e;
            }
            
            LMB_EDI_Model_EDI::trace("receiver", "Reception: $sig|$str");
			
            //logme("********************** RECEPTION D'UN MESSAGE **********************");
            //test si le message a déjà été recu
            $query = "SELECT sig FROM " . Mage::getConfig()->getTablePrefix() . "edi_messages_recu_queue
                            WHERE sig='$sig'";
            $res = $pdo->query($query);
            if (is_object($res)) {
                if ($res->fetchObject()) {
                    LMB_EDI_Model_EDI::traceDebug("receiver", "Le message avait déja été recu");
                    return true;
                }
            }
            
            // Décryptage du message à l'enregistrement
            try {
                if (mb_substr($str, 0, 1, "utf-8") != "{") {
                    $decrypt = LMB_EDI_Model_Liaison_Crypto::getInstance(LMB_EDI_Model_Config::CODE_CONNECTION())->decrypt($str);
                }
                else {
                    $decrypt = $str;
                }
                
                if (mb_substr($decrypt, 0, 1, "utf-8") == "{") {
                    $str = $decrypt;
                }
            }
            catch (Exception $e) {
                // Si une erreur survient, on se contante d'enregistrer le message crypté
            }

            //sauvegarde du message recu
            $query = "INSERT INTO " . Mage::getConfig()->getTablePrefix() . "edi_messages_recu_queue
                    (chaine,date,etat,sig)
                    VALUES(" . $pdo->quote($str) . ",  NOW(), " . LMB_EDI_Model_Config::$OK_CODE . ",'$sig')";

            LMB_EDI_Model_EDI::traceDebug("receiver", $query);
			
            $stmt = $pdo->prepare($query);
            $bdd_return = $stmt->execute();
            //LMB_EDI_Model_EDI::trace("receiver", $query);
            LMB_EDI_Model_EDI::trace("receiver", "reception ".($bdd_return ? "OK" : "ERROR"));

            $tentative = 0; 
            while (!LMB_EDI_Model_EDI::newProcess("process/start/messages_recu", LMB_EDI_Model_Liaison_MessageRecu::getProcess())) {
                sleep(2); 
                $tentative++; 
                if ($tentative > 3) { 
                    LMB_EDI_Model_EDI::error(LMB_EDI_Model_Liaison_MessageRecu::getProcess()." n'a pas pu être relancé après 3 tentatives"); 
                    break; 
                } 
            } 
            
            $writer = null;
            return true;
        } else {
            return "Message vide";
        }
        return "rien";
    }

    public static function error_handler($errno, $errstr, $errfile, $errline) {
		$msg="";
        switch ($errno) {
            case E_USER_ERROR:
            case E_ERROR:
                $msg = "<b>ERREUR FATALE</b> [$errno] $errstr<br />\n";
                break;

            case E_USER_WARNING:
            case E_WARNING:
                $msg = "<b>ALERTE</b> [$errno] $errstr<br />\n";
                break;

            case E_USER_NOTICE:
            case E_NOTICE:
                $msg = "<b>AVERTISSEMENT</b> [$errno] $errstr<br />\n";
                break;

            default:
                $msg = "Erreur : [$errno] $errstr<br />\n";
                break;
        }

        $msg .= "  Erreur sur la ligne $errline dans le fichier $errfile";
        $msg .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br/>";

        $dt = debug_backtrace();
        foreach ($dt as $t) {
            $file = isset($t['file']) ? $t['file'] : "fichier inconnu";
            $line = isset($t['line']) ? $t['line'] : "ligne inconnue";
            $function = isset($t['function']) ? $t['function'] : "fonction inconnue";
            $msg .= $file . ' line ' . $line . ' calls ' . $function . "<br/>";
        }
        LMB_EDI_Model_EDI::trace("Error_PHP", $msg);

        /* Ne pas exécuter le gestionnaire interne de PHP */
        return true;
    }

    public static function exception_handler($except) {
        $msg = "Exception non capturée [" . $except->getCode() . "] : " . $except->getMessage() . "<br />\n";
        $msg .= "Erreur sur la ligne " . $except->getLine() . " dans le fichier " . $except->getFile() . "<br/>";
        $dt = debug_backtrace();
        foreach ($dt as $t) {
            $msg .= $t['file'] . ' line ' . $t['line'] . ' calls ' . $t['function'] . "()<br/>";
        }
        LMB_EDI_Model_EDI::trace("Error_PHP", $msg);
    }

    public static function exit_handler($dir = false) {
        if (!empty($dir))
            chdir($dir);
        $error = error_get_last();
        if ($error !== NULL && !preg_match("/.*Failed opening '__edi.php'.*/", $error['message'])) {
            $info = "[CRITIQUE] fichier:" . $error['file'] . " | ligne:" . $error['line'] . " | msg:" . $error['message'] . PHP_EOL . "<br/>";
            $info .= print_r(debug_backtrace(), true) . "<br/>";

            LMB_EDI_Model_EDI::trace("Error_PHP", $info);
        }
    }

}

function Receiver($id, $str) {
    return LMB_EDI_EchangesController::receiver($id, $str);
}