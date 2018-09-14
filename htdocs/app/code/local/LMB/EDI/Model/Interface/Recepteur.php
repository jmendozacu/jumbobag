<?php

class LMB_EDI_Model_Interface_Recepteur {

    private static $product_entity_id;

    function __construct() {
        self::$product_entity_id = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
    }

    function create_categorie($tab) {
        LMB_EDI_Model_EDI::traceDebug("reception", "Début de la création d'une catégorie");

        if (empty($tab['ref_art_categ_parent'])) {
            $sites = $this->getWebSites();
            $site = Mage::getModel('core/website')->load($sites[0]);
            $tab['ref_art_categ_parent'] = $site->getDefaultStore()->getRootCategoryId();
        }

        $parentCategory = Mage::getModel('catalog/category')->load($tab['ref_art_categ_parent']);

        $data = array();
        $data['is_active'] = $tab['visible'];
        $data['display_mode'] = Mage_Catalog_Model_Category::DM_PRODUCT;
        $data['name'] = $tab['lib_art_categ'];
        $data['is_anchor'] = 1;
        $category = Mage::getModel('catalog/category');
        $category->setData($data);
        $category->setAttributeSetId($category->getDefaultAttributeSetId());

        try {
            $category->save();
            LMB_EDI_Model_EDI::traceDebug("reception", "Catégorie créée : " . $category->getName() . " (id: " . $category->getId() . ")");
        }
        catch (Exception $e) {
            LMB_EDI_Model_EDI::error("Erreur lors de la création de la catégorie : {$e->getMessage()}");
            return false;
        }

        if (!empty($parentCategory)) {
            $last_child = Mage::getModel('catalog/category')
                ->getCollection()
                ->addAttributeToFilter('parent_id', $parentCategory->getId())
                ->setOrder('position', 'desc')
                ->getFirstItem();
            
            $last_position = 0;
            if (!empty($last_child) && $last_child->getId()) {
                $last_position = $last_child->getPosition();
            }
                
            $category->setPath($parentCategory->getPath()."/".$category->getId());
            $category->setParentId($parentCategory->getId());
            $category->setLevel($parentCategory->getLevel()+1);
            $category->setPosition($last_position+1);
            
            $category->save();
        }
    
        $tab_corres['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $tab_corres['ref_lmb'] = $tab['ref_art_categ'];
        $tab_corres['ref_externe'] = $category->getId();
        $tab_corres['id_ref_type'] = 2;

        LMB_EDI_Model_Interface_Emetteur::envoi_LMB("create_correspondance", $tab_corres);

        return true;
    }

    function update_categorie($tab) {

        if (empty($tab['ref_art_categ_parent'])) {
            $sites = $this->getWebSites();
            $site = Mage::getModel('core/website')->load($sites[0]);
            $tab['ref_art_categ_parent'] = $site->getDefaultStore()->getRootCategoryId();
        }

        $parentCategory = Mage::getModel('catalog/category')->load($tab['ref_art_categ_parent']);
        $category = Mage::getModel('catalog/category')->load($tab['ref_art_categ']);
        $category->setName($tab['lib_art_categ']);
        $category->setAttributeSetId($category->getDefaultAttributeSetId());
        $currentParentId = $category->getParentCategory()->getId();

        try {
            if (!empty($parentCategory) && $parentCategory->getId() != $currentParentId) {
                $last_child = Mage::getModel('catalog/category')
                    ->getCollection()
                    ->addAttributeToFilter('parent_id', $parentCategory->getId())
                    ->setOrder('position', 'desc')
                    ->getFirstItem();
                
                $last_position = 0;
                if (!empty($last_child) && $last_child->getId()) {
                    $last_position = $last_child->getPosition();
                }
                    
                $category->setPath($parentCategory->getPath()."/".$category->getId());
                $category->setParentId($parentCategory->getId());
                $category->setLevel($parentCategory->getLevel()+1);
                $category->setPosition($last_position+1);
            }
            
            $category->save();
            LMB_EDI_Model_EDI::traceDebug("reception", "Catégorie mise à jour : " . $category->getName() . " (id: " . $category->getId() . ")");
        }
        catch (Exception $e) {
            LMB_EDI_Model_EDI::error("Erreur lors de la maj de la catégorie : {$e->getMessage()}");
        }

        return true;
    }

    function delete_categorie($infos) {
        $category = Mage::getModel('catalog/category')->load($infos['ref_categ']);
        try {
            $category->setIsActive(false);
            $category->save();
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error("Erreur lors de la suppression de la catégorie : {$e->getMessage()}");
        }
        
        return true;
    }

    function create_carac($carac) {

        LMB_EDI_Model_EDI::trace("reception", "******************************\nCREATE CARAC " . $carac['ref_carac'] . "\n***************************");
        LMB_EDI_Model_EDI::trace("debug_de_la_mort", print_r($carac, true));

        $attributes = Mage::getModel('eav/entity_attribute')->getCollection();
        $code = LMB_EDI_Helper_EDI::link_rewrite($carac['lib_carac']);

        if ($carac['variante'] == 1) {
            $code .= "_var";
        }
        $exists = !empty($carac['ref_carac_ext']);

        if (!$exists) {
            foreach ($attributes as $attrib) {
                if ($attrib->getAttributeCode() == mb_strimwidth($code, 0, 30, "")) {
                    if (($carac['variante'] == 1 && $attrib->getFrontendInput() == "select") || $carac['variante'] == 0) {
                        $exists = true;
                        $id = $attrib->getId();
                        break;
                    }
                }
            }
        }
        else {
            $attribute_load = $this->getCaracByCorresp($carac['ref_carac_ext']);
            $id = $attribute_load->getId();
        }

        $setup = new Mage_Eav_Model_Entity_Setup('eav_setup');

        if (!$exists) {
            $input = ($carac['variante']) ? "select" : "text";
            $attribute = Mage::getModel('eav/entity_attribute')
                    ->setEntityTypeId(self::$product_entity_id)
                    ->setData("is_configurable", $carac['variante'])
                    ->setData("is_user_defined", true)
                    ->setData("is_visible_on_front", true)
                    ->setData("frontend_input", $input)
                    ->setData("backend_type", "varchar")
                    ->setData("attribute_code", mb_strimwidth($code, 0, 30, ""))
                    ->setData("used_in_product_listing", false)
                    ->setData("frontend_label", array($carac['lib_carac']));

            try {

                $attribute->save();
                $id = $attribute->getId();

                if (!$carac['variante']) {
                    $attribute->setData($attribute->getDefaultValueByInput($input), $carac['default_value']);
                }
                else {
                    $options = explode(";", $carac['default_value']);
                    foreach ($options as $option) {
                        $attr['value']['ref'][0] = $option;
                    }
                    $attr['attribute_id'] = $id;

                    $setup->addAttributeOption($attr);
                    $collection = Mage::getModel('eav/entity_attribute_option')->getCollection()
                            ->setAttributeFilter($id)
                            ->setStoreFilter(Mage::app()->getStore()->getId());
                }

                $article_corres['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
                $article_corres['ref_lmb'] = $carac['ref_carac'];
                $article_corres['ref_externe'] = $id;
                $article_corres['id_ref_type'] = ($carac['variante']) ? 9 : 10;
                LMB_EDI_Model_Interface_Emetteur::envoi_LMB("create_correspondance", $article_corres);
            }
            catch (Exception $e) {
                return LMB_EDI_Model_EDI::error("[1] Erreur lors de la création de la carac : {$e->getMessage()}");
            }
        }

        $attribute = Mage::getModel('eav/entity_attribute')->setId($id)
                ->setEntityTypeId(self::$product_entity_id);

        try {
            $attribute->save();
            $setup->addAttributeToSet(self::$product_entity_id, Mage::getModel('catalog/product')->getDefaultAttributeSetId(), "General", $id);
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error("[2] Erreur lors de la liaison de la carac : {$e->getMessage()}");
        }
        
        return true;
    }

    //update carac
    function update_carac($carac) {
        LMB_EDI_Model_EDI::trace("carac", "carac :" . print_r($carac, true));

        $input = ($carac['variante']) ? "select" : "text";
        $attribute = $this->getCaracByCorresp($carac['ref_carac'])
                ->setEntityTypeId(self::$product_entity_id)
                ->setData("is_configurable", $carac['variante'])
                ->setData("frontend_input", $input)
                ->setData("frontend_label", array($carac['lib_carac']));

        try {
            $attribute->save();
            $setup = new Mage_Eav_Model_Entity_Setup('eav_setup');
            $setup->addAttributeToSet(self::$product_entity_id, Mage::getModel('catalog/product')->getDefaultAttributeSetId(), "General", $attribute->getId());
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error("Erreur lors de la mise à jour de la carac : {$e->getMessage()}");
        }
        return true;
    }

    function create_article($article) {

        LMB_EDI_Model_EDI::trace("reception", "CREATE ARTICLE :");
        LMB_EDI_Model_EDI::traceDebug("reception", print_r($article, true));
        //Test si la catégorie de l'article est active
        $isVisible = 1;
        $idManufacturer = LMB_EDI_Model_Config::GET_PARAM('marque_code');
        $idCodeBarre = LMB_EDI_Model_Config::GET_PARAM('code_barre_code');
        $idPrixAchat = LMB_EDI_Model_Config::GET_PARAM('prix_achat_code');
        $id_tva = $this->getID_taxe($article['tva']);
        
        $category = Mage::getModel('catalog/category')->load($article['ref_art_categ']);
        if ($category)
            $isVisible = ($article['visible'] && ($article['pp_ht'] != 0)) ? true : false;
        else
            return LMB_EDI_Model_EDI::error("create_article : Catégorie " . $article['ref_art_categ'] . " innexistante");

        $price = (LMB_EDI_Model_Config::TARIFS_TTC()) ? $article['pp_ttc'] : $article['pp_ht'];

        //Test anti doublons et SKU vide !!!!!!!!!!!!!!!!!!
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $article['reference']);
        if (empty($article['reference']) || ($product && $product->getId())) {
            $article['reference'] = "A" . substr($article['ref_article'], strrpos($article['ref_article'], "-") + 1);
        }

        $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore(true)->getId())
                ->setWebsiteIds($this->getWebSites())
                ->setSku($article['reference'])
                ->setData('price', $price)
                ->setData('name', $article['lib_article'])
                ->setData('description', $article['desc_longue'])
                ->setData('short_description', $article['desc_courte'])
                ->setData('tax_class_id', $id_tva)
                ->setData('meta_keyword', $article['keywords'])
                ->setData('weight', $article['poids'])
                ->setData('status', ($isVisible) ? Mage_Catalog_Model_Product_Status::STATUS_ENABLED : Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
                ->setData('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);

        $product->setAttributeSetId($product->getDefaultAttributeSetId());
        
        if ($article['variante'] == 0) {
            $type = ($article["modele"] == "materiel") ? Mage_Catalog_Model_Product_Type::TYPE_SIMPLE : Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL;
            //Ajout des éléments par défaut connus
            $product->setTypeId($type);
        }
        else if ($article['variante'] == 2) { //Article parent variante LMB
            $product->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE);
        }
        
        $datas = array();
        $attributes = $product->getTypeInstance(true)
                ->getSetAttributes($product);
        
        if ($idManufacturer && isset($attributes[$idManufacturer]) && isset($article['ref_const'])) {
            $datas[$idManufacturer] = $article['ref_const'];
        }
        if ($idCodeBarre && isset($attributes[$idCodeBarre]) && isset($article['ean13'])) {
            $datas[$idCodeBarre] = $article['ean13'];
        }
        if ($idPrixAchat && isset($attributes[$idPrixAchat]) && isset($article['prix_achat'])) {
            $datas[$idPrixAchat] = $article['prix_achat'];
        }
        
        $product->addData($datas);
        Mage::dispatchEvent('catalog_product_prepare_save', array('product' => $product));
        try {
            $product->setCategoryIds($article['ref_art_categ']);
            $product->save();
            Mage::getSingleton('catalog/url')->refreshProductRewrite($product->getId());

            LMB_EDI_Model_EDI::traceDebug("debug_url", $product->getProductUrl());
            LMB_EDI_Model_EDI::traceDebug("debug_url", $product->getUrlInStore());
            LMB_EDI_Model_EDI::traceDebug("debug_url", $product->getUrlPath());
        } catch (Exception $e) {
            return LMB_EDI_Model_EDI::error('Save product:' . $e->getMessage());
        }

        //Création de la correspondance
        $article_corres['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $article_corres['ref_lmb'] = $article['ref_article'];
        $article_corres['ref_externe'] = $product->getId();
        if ($article['variante'] == 2) {
            $article_corres['id_ref_type'] = 11;
        } else {
            $article_corres['id_ref_type'] = 1;
        }
        
        LMB_EDI_Model_EDI::trace("reception", print_r($article_corres, true));
        LMB_EDI_Model_Interface_Emetteur::envoi_LMB("create_correspondance", $article_corres);


        // Gestion des caracs de groupes dynamique
        if ($article['variante'] == 2 && isset($article["caracs_variantes"])) {
            LMB_EDI_Model_EDI::trace("variantes_parent", print_r($article["caracs_variantes"], true));

            if ($product->isConfigurable()) {
                $setup = new Mage_Eav_Model_Entity_Setup('eav_setup');
                $group = $setup->getDefaultAttributeGroupId(self::$product_entity_id, $product->getAttributeSetId());
                
                // Suppression des critères de déclinaisons existants
                // Magento ne sait qu'ajouter les critères (mise à jour impossible)
                $resource = Mage::getSingleton('core/resource');
                $write = $resource->getConnection('core_write');
                $table = $resource->getTableName('catalog/product_super_attribute');
                $write->delete($table,"product_id = " . $product->getId());

                $product->setCanSaveConfigurableAttributes(true);
                $product->setCanSaveCustomOptions(true);

                try {
                    $ids = array();
                    foreach($article["caracs_variantes"] as $carac_variante) {
                        if (!empty($carac_variante["ref_carac"])) {
                            $attribute_var = null;
                            if (is_numeric($carac_variante["ref_carac"])) {
                                $attribute_var = Mage::getModel('eav/entity_attribute')->load($carac_variante['ref_carac']);
                                $ids[] = $carac_variante["ref_carac"];
                            }
                            else {
                                $attribute_var = Mage::getModel('eav/entity_attribute')->loadByCode("catalog_product", $carac_variante["ref_carac"]);
                                if (!empty($attribute_var)) {
                                    $ids[] = $attribute_var->getId();
                                }
                            }
                            if (!empty($attribute_var)) {
                                $resave = false;
                                
                                if($attribute_var->getAttributeSetId() != $product->getAttributeSetId()){
                                    $attribute_var->setAttributeSetId($product->getAttributeSetId());
                                    $resave = true;
                                }
                                if($attribute_var->getAttributeGroupId() != $group){
                                    $attribute_var->setAttributeGroupId($group);
                                    $resave = true;
                                }
                                if ($resave) {
                                    $attribute_var->save();
                                }
                            }
                        }
                    }

                    $product->getTypeInstance()->setUsedProductAttributeIds($ids);
                    $product->getTypeInstance()->save();

                    $attributes_array = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
                    
                    foreach ($attributes_array as $key => $attribute_value) {
                        $attributes_array[$key]['label'] = $attribute_value['frontend_label'];
                    }
                }
                catch (Exception $e) {
                    LMB_EDI_Model_EDI::error("Ajout carac super-configurables à ".$product->getId()." : " . $e->getMessage());
                }

                $product->setConfigurableAttributesData($attributes_array);
                
                $product->addData(array('stock_data' => array("is_in_stock" => true)));
                
                $product->save();
            }
        }
        
        /*
        Mage::dispatchEvent('catalog_product_prepare_save', array('product' => $product));
        $product->setUrlKey($product->getUrlKey());
        Mage::getSingleton('catalog/url')->generatePath('id', $product->getId(), null);
        $product->refreshRewrites();
        //*/

        return true;
    }
    
    protected function getWebSites() {
        $magasins = Mage::app()->getStores(true);
        $sites_web = array();
        foreach ($magasins as $magasin) {
            $id_website =  $magasin->getWebsite()->getId();
            if (($id_website === 0 || !empty($id_website)) && !in_array($id_website, $sites_web)) {
                $sites_web[] = $id_website;
            }
        }

        if (empty($sites_web)) {
            $sites_web = array(Mage::app()->getStore(true)->getWebsite()->getId());
        }

        return $sites_web;
    }

    private function getIdGroupCaracSet($carac){
        $setup = new Mage_Eav_Model_Entity_Setup('eav_setup');

        if (!empty($carac['ref_groupe'])) {

            $model = Mage::getModel('eav/entity_attribute_group')->load($carac['ref_groupe']);
            if ($model->itemExists() && $model->getAttributeSetId() != $carac['ref_carac_set']) {

                try {

                    $model2 = Mage::getModel('eav/entity_attribute_group')->getCollection()
                            ->addFieldToFilter('attribute_set_id', array('eq' => $carac['ref_carac_set']))
                            ->addFieldToFilter('attribute_group_name', array('eq' => Mage::helper('catalog')->stripTags($model->getAttributeGroupName())))
                            ->getFirstItem();


                    if (!$model2->itemExists()) {
                        LMB_EDI_Model_EDI::trace("attribute_groupe", "Carac groupe non trouvé, on le recopie");
                        $model2->setAttributeSetId($carac['ref_carac_set'])
                            ->setAttributeGroupName($model->getAttributeGroupName())
                            ->save();
                    }

                    $group = $model2->getId();
                } catch (Exception $e) {
                    return LMB_EDI_Model_EDI::error("[0] Erreur lors de la creation du groupe d'attributs : {$e->getMessage()}");
                }
            } else {
                $group = $carac['ref_groupe'];
            }
        } else {
            $group = $setup->getDefaultAttributeGroupId(self::$product_entity_id, $carac['ref_carac_set']);
        }

        return $group;
    }

    //*********************************************
    // UPDATE ARTICLE + VARIANTE
    function update_article($article) {
		LMB_EDI_Model_EDI::traceDebug("reception", "Update article");
        LMB_EDI_Model_EDI::traceDebug("article", "Update : " . print_r($article, true));

        /*
        if ($article['ref_article'] == 701 || (!empty($article['ref_article_parent']) && $article['ref_article_parent'] == 701)) {
    		LMB_EDI_Model_EDI::trace("ignore_701", "Message ignoré car concerne le groupe en erreur");
            return true;
        }
        //*/
        
        //Test anti doublons et SKU vide !!!!!!!!!!!!!!!!!!
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $article['reference']);
        if (empty($article['reference']) || ($product && $product->getId())) {
            unset($article['reference']);
        }

        $product = Mage::getModel('catalog/product')->load($article['ref_article']);
        if ($product->getId()) {
            $idManufacturer = LMB_EDI_Model_Config::GET_PARAM('marque_code');
            $idCodeBarre = LMB_EDI_Model_Config::GET_PARAM('code_barre_code');
            $idPrixAchat = LMB_EDI_Model_Config::GET_PARAM('prix_achat_code');
            $id_tva = $this->getID_taxe($article['tva']);

            if (empty($article['desc_courte'])) {
                $article['desc_courte'] = $article['lib_article'];
            }
            if (empty($article['desc_longue'])) {
                $article['desc_longue'] = $article['desc_courte'];
            }

            $price = (LMB_EDI_Model_Config::TARIFS_TTC()) ? $article['pp_ttc'] : $article['pp_ht'];

            if (isset($article['reference'])) {
                $product->setSku($article['reference']);
            }
            
            $product->setStoreId(Mage::app()->getStore(true)->getId())
                    ->setWebsiteIds($this->getWebSites())
                    ->setData('_edit_mode', true)
                    ->setPrice($price)
                    ->setName($article['lib_article'])
                    ->setDescription($article['desc_longue'])
                    ->setShortDescription($article['desc_courte'])
                    ->setTaxClassId($id_tva)
                    ->setWeight($article['poids']);

            // Gestion du désarchivage
            if (!empty($article["active"]) && $product->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_DISABLED) {
                $product->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED);                
            }
            
            if (!empty($article['garantie'])) {
                $product->setData('duree_garantie', $article['garantie']);
            }

            LMB_EDI_Model_EDI::traceDebug("debug_categ", "->setCategoryIds(array(" . $article['ref_art_categ'] . "))");

            if ($article['variante'] == 0) { //Article simple LMB
                $type = ($article["modele"] == "materiel") ? Mage_Catalog_Model_Product_Type::TYPE_SIMPLE : Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL;
                $product->setTypeId($type);
            }
            else if ($article['variante'] == 1) { //Article variant LMB
                LMB_EDI_Model_EDI::trace("variantes", $article['ref_article']
                        . "<br />" . $article['lib_article']
                        . "<br />" . $article['evt_name']);
                $product->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE);

                try {
                    LMB_EDI_Model_EDI::trace("test_parent.log", "chargement du parent ".$article['ref_article_parent']);
                    $configurableProduct = Mage::getModel('catalog/product')->load($article['ref_article_parent']);
                    LMB_EDI_Model_EDI::trace("test_parent.log", "chargement OK");
                }
                catch (Exception $e) {
                    LMB_EDI_Model_EDI::trace("test_parent.log", "chargement incorrect");
                    LMB_EDI_Model_EDI::trace("backtrace_parent.log", print_r(debug_backtrace(2), true));
                    LMB_EDI_Model_EDI::error("Le chargement de l'article parent ".$article['ref_article_parent']." pour modifier l'article ".$article['ref_article']." a échoué !!");
                    
                    return true;
                }
                
                if (!$configurableProduct->getId() || $configurableProduct->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                    LMB_EDI_Model_EDI::error("Erreur VARIANTE => Enfant:" . $article['ref_article'] . ", Parent: " . $article['ref_article_parent']." n'en est pas un...");
					mail(LMB_EDI_Model_Config::GET_PARAM('alerte'), "Attention ERREUR variante Magento", "URL : " . LMB_EDI_Model_ModuleLiaison::$RACINE_URL . " Enfant:" . $article['ref_article'] . ", Parent: " . $article['ref_article_parent']." n'en est pas un...");
                    return true;
                }
                
                $result = array();
                foreach ($configurableProduct->getTypeInstance()->getConfigurableAttributes() as $attribute) {
                    $value = $product->getAttributeText($attribute->getProductAttribute()->getAttributeCode());
                    $autogenerateOptions[] = $value;
                    $result['attributes'][] = array(
                        'label' => $value,
                        'value_index' => $product->getData($attribute->getProductAttribute()->getAttributeCode()),
                        'attribute_id' => $attribute->getProductAttribute()->getId(),
                        'is_percent' => 0
                    );
                }

                $oldIds = $configurableProduct->getTypeInstance()->getUsedProductIds();
                LMB_EDI_Model_EDI::trace("variantes", "oldIds : " . print_r($oldIds, true));
                LMB_EDI_Model_EDI::trace("variantes", "IDs : " . print_r($product->getId(), true));
                LMB_EDI_Model_EDI::trace("variantes", "Res : " . print_r($result, true));

                $oldIds[] = $product->getId();
                $newids = array_unique($oldIds);

                LMB_EDI_Model_EDI::trace("variantes", "IDs_merge : " . print_r($newids, true));

                try {
                    Mage::getResourceModel('catalog/product_type_configurable')
                            ->saveProducts($configurableProduct, $newids);
                    
                    $configurableProduct->setStockData(array( 
                        'use_config_manage_stock' => 0,
                        'is_in_stock' => 1, 
                        'manage_stock' => 0
                    )); 
                    
                    $configurableProduct->save();
                }
                catch (Exception $e) {
                    LMB_EDI_Model_EDI::error('Ajout enfant au parent :' . $e->getMessage());
                }
            } else if ($article['variante'] == 2) { //Article parent variante LMB
                LMB_EDI_Model_EDI::trace("variantes_parent", $article['ref_article']
                        . "<br />" . $article['lib_article']
                        . "<br />" . $article['evt_name']);
            }

            try {
                $datas = array();
                $attributes = $product->getTypeInstance(true)
                        ->getSetAttributes($product);

                if ($idManufacturer && isset($attributes[$idManufacturer]) && isset($article['ref_const'])) {
                    $datas[$idManufacturer] = $article['ref_const'];
                }
                if ($idCodeBarre && isset($attributes[$idCodeBarre]) && isset($article['ean13'])) {
                    $datas[$idCodeBarre] = $article['ean13'];
                }
                if ($idPrixAchat && isset($attributes[$idPrixAchat]) && isset($article['prix_achat'])) {
                    $datas[$idPrixAchat] = $article['prix_achat'];
                }
                
                $product->addData($datas);
                $product->save();
            }
            catch (Exception $e) {
                return LMB_EDI_Model_EDI::error('Save product (update):' . $e->getMessage());
            }
        }

        // Gestion des caracs de groupes dynamique
        if ($article['variante'] == 2 && isset($article["caracs_variantes"])) {
            LMB_EDI_Model_EDI::trace("variantes_parent", print_r($article["caracs_variantes"], true));

            if ($product->isConfigurable()) {
                $setup = new Mage_Eav_Model_Entity_Setup('eav_setup');
                $group = $setup->getDefaultAttributeGroupId(self::$product_entity_id, $product->getAttributeSetId());
                
                // Suppression des critères de déclinaisons existants
                // Magento ne sait qu'ajouter les critères (mise à jour impossible)
                $resource = Mage::getSingleton('core/resource');
                $write = $resource->getConnection('core_write');
                $table = $resource->getTableName('catalog/product_super_attribute');
                $write->delete($table,"product_id = " . $product->getId());

                $product->setCanSaveConfigurableAttributes(true);
                $product->setCanSaveCustomOptions(true);

                try {
                    $ids = array();
                    foreach($article["caracs_variantes"] as $carac_variante) {
                        if (!empty($carac_variante["ref_carac"])) {
                            $attribute_var = null;
                            if (is_numeric($carac_variante["ref_carac"])) {
                                $attribute_var = Mage::getModel('eav/entity_attribute')->load($carac_variante['ref_carac']);
                                $ids[] = $carac_variante["ref_carac"];
                            }
                            else {
                                $attribute_var = Mage::getModel('eav/entity_attribute')->loadByCode("catalog_product", $carac_variante["ref_carac"]);
                                if (!empty($attribute_var)) {
                                    $ids[] = $attribute_var->getId();
                                }
                            }
                            if (!empty($attribute_var)) {
                                $resave = false;
                                
                                if($attribute_var->getAttributeSetId() != $product->getAttributeSetId()){
                                    $attribute_var->setAttributeSetId($product->getAttributeSetId());
                                    $resave = true;
                                }
                                if($attribute_var->getAttributeGroupId() != $group){
                                    $attribute_var->setAttributeGroupId($group);
                                    $resave = true;
                                }
                                if ($resave) {
                                    $attribute_var->save();
                                }
                            }
                        }
                    }

                    $product->getTypeInstance()->setUsedProductAttributeIds($ids);
                    $product->getTypeInstance()->save();

                    $attributes_array = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
                    
                    foreach ($attributes_array as $key => $attribute_value) {
                        $attributes_array[$key]['label'] = $attribute_value['frontend_label'];
                    }
                }
                catch (Exception $e) {
                    LMB_EDI_Model_EDI::error("Ajout carac super-configurables à ".$product->getId()." : " . $e->getMessage());
                }

                $product->setConfigurableAttributesData($attributes_array);
                
                $product->setStockData(array( 
                    'use_config_manage_stock' => 0,
                    'is_in_stock' => 1, 
                    'manage_stock' => 0
                )); 
                
                $product->save();
            }
        }
        
        //$product->refreshRewrites();
        return true;
    }

    public function update_art_tarif($article) {
        LMB_EDI_Model_EDI::traceDebug("reception", "update_art_tarif article=>" . $article['ref_article']);
        $product = Mage::getModel('catalog/product')->load($article['ref_article']);
        if ($product->getId()) {
            $product->setTypeId($product->getTypeId());
            try {
                $price = LMB_EDI_Model_Config::TARIFS_TTC() ? $article["pu_ttc"] : $article["pu_ht"];
                $product->addData(array("price" => $price));
                $product->save();
            }
            catch (Exception $e) {
                return LMB_EDI_Model_EDI::error('Save product (tarif):' . $e->getMessage());
            }
            
            $datas = array();
            //Ajout des grilles tarifiaires
            if (isset($article['tarifs'])) {
                foreach ($article['tarifs'] as $tarif) {
                    $new_price = array();
                    $type_price = (empty($tarif["qte"]) || $tarif["qte"] == 1) ? "group_price" : "tier_price";
                    $new_price["cust_group"] = !empty($tarif["client_categ"]) ? $tarif["client_categ"] : 0;
                    
                    if ($type_price == "tier_price") {
                        $new_price["price_qty"] = $tarif["qte"];
                        // Attention, id_group #0 désigne les non loggé, Tous les groupe a pour ID #32000 (utilisation de la constante Magento)
                        if (empty($new_price["cust_group"])) {
                            $new_price["cust_group"] = Mage_Customer_Model_Group::CUST_GROUP_ALL;
                        }
                    }

                    if (LMB_EDI_Model_Config::TARIFS_TTC()) {
                        $new_price["price"] = $tarif["pu_ttc"];
                    } else {
                        $new_price["price"] = $tarif["pu_ht"];
                    }

                    LMB_EDI_Model_EDI::trace("update_art_tarif", print_r($new_price, true));

                    $new_price["website_id"] = 0;
                    $new_price["delete"] = false;
                    $datas[$type_price][] = $new_price;
                }
            }
            
            // Gestion du prix spécifique
            if (isset($article["promo_ht"])) {
                $datas['special_price'] = LMB_EDI_Model_Config::TARIFS_TTC() ? $article["promo_ttc"] : $article["promo_ht"];
                $datas['special_from_date'] = !empty($article["promo_debut"]) ? $article["promo_debut"] : "";
                $datas['special_to_date'] = !empty($article["promo_fin"]) ? $article["promo_fin"] : "";
            }
            
            try {
                $product->addData($datas);
                $product->save();
            }
            catch (Exception $e) {
                return LMB_EDI_Model_EDI::error('Save product (tarif):' . $e->getMessage());
            }
        }
        return true;
    }

    public function update_art_taxes($article) {
        LMB_EDI_Model_EDI::traceDebug("reception", "update_art_taxes article=>" . $article['ref_article']);
        $product = Mage::getModel('catalog/product')->load($article['ref_article']);
        if ($product->getId()) {
            $idEcotaxe = 'deee';
            $datas = array();
            //$attributes = $product->getSetAttribute($product);
            $attributes = $product->getTypeInstance(true)->getSetAttributes($product);
            if ($idEcotaxe && isset($attributes[$idEcotaxe])) {
                if (!empty($article['ecotaxe']) && floatval($article['ecotaxe']) > 0) {
                    $taxe = array();
                    $taxe[0]['price'] = $article['ecotaxe'];
                    $taxe[0]['country'] = 'FR';
                    $taxe[0]['state'] = '';
                    $taxe[0]['website_id'] = 0;
                    $taxe[0]['delete'] = false;
                    $datas[$idEcotaxe] = $taxe;
                }
                else {
                    $product->setDeee(array(array('delete' => 1)));
                }
            }
            $product->addData($datas);
            try {
                $product->save();
            }
            catch (Exception $e) {
                return LMB_EDI_Model_EDI::error('Save product (taxe):' . $e->getMessage());
            }
        }
        
        return true;
    }

    public function update_article_categorie($article) {

        $product = Mage::getModel('catalog/product')->load($article['id_product'])
                ->setAttributeSetId(array($article['ref_carac_set']))
                ->setCategoryIds(array($article['new_id_category']));
        try {
            if ($product->getId()) {
                $product->save();
            }
        } catch (Exception $e) {
            return LMB_EDI_Model_EDI::error('Save product (update categ):' . $e->getMessage());
        }
        return true;
    }

    function delete_article($product) {
        $product = Mage::getModel('catalog/product')->load($product['id_product']);
        try {
            if ($product->getId()) {
                $product->setStatus(Mage_Catalog_Model_Product_Status::STATUS_DISABLED);
                $product->save();
            }
            return true;
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error('Delete product :' . $e->getMessage());
        }
    }

    /*
     * 	@param $ref_art_parent reference prestashop de l'article auxquels on veut associer des attributs
     * 	@param $carac un tableau de caracteristique sous la forme $tab[$ref_carac] = $valeur 
     * 	@param $price le prix TTC de la variante
     * 	@return l'id_product_attribut de la variante crï¿½er
     */

    function create_variante($variante) {
        //************************************
        //	GESTION DES VARIANTES D'ARTICLES *
        //************************************
        LMB_EDI_Model_EDI::trace("reception", "CREATE VARIANTE :");
        LMB_EDI_Model_EDI::trace("reception", print_r($variante, true));

        //Test anti doublons et SKU vide !!!!!!!!!!!!!!!!!!
        if(!isset($variante['reference'])) $variante['reference'] = "";
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $variante['reference']);
        if (empty($variante['reference']) || ($product && $product->getId())) {
            $variante['reference'] = "A" . substr($variante['ref_article_variant'], strrpos($variante['ref_article_variant'], "-") + 1);
        }

        $configurableProduct = Mage::getModel('catalog/product')->load($variante['ref_article_parent']);
        if (!$configurableProduct->getId() || $configurableProduct->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE)
            return true;
        $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore(true)->getId())
                ->setWebsiteIds($this->getWebSites())
                ->setAttributeSetId($configurableProduct->getAttributeSetId())
                ->setSku($variante['reference'])
                ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE);

        //$pu_var = $variante['pu_ht'] - $configurableProduct->getPrice();
        //$pu_var = $pu_var, $variante['tva']);

        foreach ($product->getTypeInstance()->getEditableAttributes() as $attribute) {
            if ($attribute->getIsUnique()
                    || $attribute->getAttributeCode() == 'url_key'
                    || $attribute->getIsUnique()
                    || $attribute->getFrontend()->getInputType() == 'gallery'
                    || $attribute->getFrontend()->getInputType() == 'media_image'
                    || !$attribute->getIsVisible()) {
                continue;
            }

            $product->setData(
                    $attribute->getAttributeCode(), $configurableProduct->getData($attribute->getAttributeCode())
            );
        }

        $product->setVisibility(1); //Non accessible indépendament

        LMB_EDI_Model_EDI::trace("variantes", "IDs : " . print_r($configurableProduct->getTypeInstance()->getUsedProductIds(), true));

        try {
            $product->save();
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error('Save product (variante2):' . $e->getMessage());
        }

        $article_corres['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $article_corres['ref_lmb'] = $variante['ref_article_variant'];
        $article_corres['ref_externe'] = $product->getId();
        $article_corres['id_ref_type'] = 8;
        
        LMB_EDI_Model_Interface_Emetteur::envoi_LMB("create_correspondance", $article_corres);

        return true;
    }
    
    /**
     * 
     * @param $corresp : ID ou code Magento
     * @return attribute
     */
    protected function getCaracByCorresp($corresp) {
        if (is_numeric($corresp)) {
            $attribute = Mage::getModel('eav/entity_attribute')->load($corresp);
        }
        else {
            $attribute = Mage::getModel('eav/entity_attribute')->loadByCode("catalog_product", $corresp);
        }
        
        return $attribute;
    }

    function create_art_carac($carac) {
        return $this->update_art_carac($carac);
    }

    function update_art_carac($carac) {
        /* FORMAT DE $carac
         *  $carac['ref_article'] = reference de l'article;
         * 	$carac['ref_carac'] = reference de la carac;
         * 	$carac['valeur'] = valeur de la carac;
         */
        if (!trim($carac['valeur'])) {
            $carac['valeur'] = "-";
        }
        $product = Mage::getModel('catalog/product')->load($carac['ref_article']);

        if (!$product->getId())
            return true;
        
        $attributeSetId = Mage::getModel('catalog/product')->getDefaultAttributeSetId();
        $current_attribute = $product->getAttributeSetId();
        if ($current_attribute != $attributeSetId) {
            $product->setAttributeSetId($attributeSetId);
        }
        
        $attribute = $this->getCaracByCorresp($carac['ref_carac']);
        
        $code = $attribute->getAttributeCode();
        $attribute_id = $attribute->getId();
        LMB_EDI_Model_EDI::trace("update_art_carac", "attribute code : " . print_r($code, true)." / attribute ID : " . print_r($attribute_id, true));
        
        if ($attribute->getFrontendInput() == 'boolean') {
            $valeur = !empty($carac['valeur']);
            if ($carac['valeur'] == "Non" || $carac['valeur'] == "No") {
                $valeur = false;
            }
            
            $datas = array($code => $valeur);
        }
        else if ($attribute->getFrontendInput() == 'select' || $attribute->getFrontendInput() == 'multiselect') {
            LMB_EDI_Model_EDI::trace("update_art_carac", "SELECT");

            //On cherche la valeur dans la liste
            $collection = Mage::getModel('eav/entity_attribute_option')->getCollection()
                    ->setAttributeFilter($attribute->getId())
                    ->setStoreFilter(Mage::app()->getStore()->getId());
            $option = $collection->getItemByColumnValue('value', $carac['valeur']);

            if (!$option || !$option->getId()) {
                LMB_EDI_Model_EDI::trace("update_art_carac", "option introuvable");

                $attr['value']['ref_text_unique'][0] = $carac['valeur'];
                $attr['attribute_id'] = $attribute->getId();
                //$attr['order']['ref_text_unique'] = 1;
                $setup = new Mage_Eav_Model_Entity_Setup('eav_setup');
                $setup->addAttributeOption($attr); //Ajout de l'option
                //Relecture de l'option pour obtenir son ID
                $collection = Mage::getModel('eav/entity_attribute_option')->getCollection()
                        ->setAttributeFilter($attribute->getId())
                        ->setStoreFilter(Mage::app()->getStore()->getId());
                $option = $collection->getItemByColumnValue('value', $carac['valeur']);
            }

            $datas = array($code => $option->getId());
        }
        else {
            LMB_EDI_Model_EDI::trace("update_art_carac", "NOT SELECTED");
            $datas = array($code => $carac['valeur']);
        }

        LMB_EDI_Model_EDI::trace("update_art_carac", "datas: " . print_r($datas, true));

        $product->addData($datas);
        
        try {
            $product->save();
        }
        catch (Exception $e) {
            LMB_EDI_Model_EDI::error('Save product carac (update):' . $e->getMessage());
            return true;
        }
        
        LMB_EDI_Model_EDI::trace("update_art_carac", "saved");
        return true;
    }

    private function getID_taxe($valeur_taxe) {
        $taxe_retour = "";
        
        $ref_pays = LMB_EDI_Model_Config::GET_PARAM('defaut_ref_pays');
        if (empty($ref_pays)) {
            $ref_pays = 'FR';
        }
        
        $taxe_search = Mage::getModel('tax/calculation_rate')->getCollection()
                ->addFieldToFilter('rate', $valeur_taxe)
                ->addFieldToFilter('tax_country_id', $ref_pays)
                ->getFirstItem();
        
        if (!empty($taxe_search)) {
            $taxe_rule = Mage::getModel('tax/calculation')->getCollection()
                    ->addFieldToFilter('tax_calculation_rate_id', $taxe_search->getId())
                    ->getFirstItem();
            
            if (!empty($taxe_rule)) {
                $taxe_retour = $taxe_rule->getData("product_tax_class_id");
            }
        }
        
        if (empty($taxe_retour)) {
            $defaut_taxe = LMB_EDI_Model_Config::GET_PARAM('defaut_id_taxe');

            if ($defaut_taxe) {
                $defaut_taxe = 2;
            }
            
            $taxe_retour = $defaut_taxe;
        }
        
        return $taxe_retour;
    }

    private function createTVA($valeur_taxe) {
        $tva = Mage::getModel('tax/calculation_rate')
                ->setData("rate", $valeur_taxe)
                ->setData("code", "TVA " . $valeur_taxe)
                ->setData("tax_country_id", "FR");
        try {
            $tva->save();
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error("Save TVA:" . $e->getMessage());
        }
        
        $tva_class = Mage::getModel('tax/class')
                ->setData("class_name", "TVA " . $valeur_taxe)
                ->setData("class_type", "PRODUCT");

        return $id_calss;
    }

    function update_stock_art($article) {
        return $this->update_qte_art($article);
    }

    function update_qte_art($article) {
        /* @todo gérer les id_stock, gérer les minimas */
        //foreach($article["stock"] as $stock){
        $product = Mage::getModel('catalog/product')->load($article['ref_article']);
        if ($product->getId()) {
            
            $datas = array("stock_data" => array());
            
            if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                $datas['stock_data']["is_in_stock"] = 1;
                $datas['stock_data']["manage_stock"] = 0;
                $datas['stock_data']["use_config_manage_stock"] = 0;
            }
            else {
                $datas['stock_data']["is_in_stock"] = ($article['qte'] > 0) ? 1 : 0;
                $datas['stock_data']["stock_id"] = 1;
                $datas['stock_data']["is_qty_decimal"] = 0;
                $datas['stock_data']["store_id"] = Mage::app()->getStore()->getId();
                $datas['stock_data']["manage_stock"] = 1;
                $datas['stock_data']["use_config_manage_stock"] = 1;
                $datas['stock_data']["qty"] = $article['qte'];
                $datas['stock_data']["min_sale_qty"] = 0;
                $datas['stock_data']["use_config_min_sale_qty"] = 1;
            }
            
            $product->addData($datas);

            LMB_EDI_Model_EDI::traceDebug("stock", $article['qte']);

            try {
                $product->save();
            }
            catch (Exception $e) {
                return LMB_EDI_Model_EDI::error("Save product stock :" . $e->getMessage());
            }
        }
        return true;
    }

    public function update_art_liaisons($liaisons) {
        if (!array_key_exists('ref_article', $liaisons) || empty($liaisons['ref_article'])) {
            return true;
        }
        
        $product = Mage::getModel('catalog/product')->load($liaisons['ref_article']);
        if (!$product->getId()) {
            return true;      
        }
        
        $links = array();
        $compteur = 1;
        
        foreach($liaisons['accessoires'] as $accessoire) {
            $link = Mage::getModel('catalog/product')->load($accessoire);
            if (!$link->getId()) {
                continue;
            }

            $links[$link->getId()] = array('position' => $compteur);
            $compteur++;
        }
        
        try {
            $product->setRelatedLinkData($links);
            $product->save();
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error("Update product liaisons :" . $e->getMessage());
        }
        
        return true;
    }
    
    public function update_art_images_positions($infos) {
        if (empty($infos['ref_article']) || empty($infos['ordre'])) {
            return true;
        }
        
        $product = Mage::getModel('catalog/product')->load($infos['ref_article']);
        if (!$product->getId()) {
            return true;      
        }
        $attributes = $product->getTypeInstance(true)->getSetAttributes($product);
        $media_gallery = $attributes['media_gallery'];
        $backend = $media_gallery->getBackend();
        $backend->afterLoad($product);

        $images = $product->getMediaGalleryImages();
        $ordre_length = count($infos['ordre']);
        $compteur = 1;
        
        foreach ($images as $image) {
            if (in_array($image->getFile(), $infos['ordre'])) {
                $positions = array_keys($infos['ordre'], $image->getFile());
                $position = $positions[0]+1;
            }
            else {
                $position = $compteur + $ordre_length;
                $compteur++;
            }
            $backend->updateImage(
                $product,
                $image->getFile(),
                array('position' => $position)
            );
            
            if ($position == 1) {
                $img_attrs = LMB_EDI_Model_Config::GET_PARAM('image_attributes');
                if (!empty($img_attrs)) {
                    $img_attrs = explode(";", $img_attrs);
                }
                
                if (empty($img_attrs) || !is_array($img_attrs)) {
                    $img_attrs = array('image', 'small_image', 'thumbnail');
                }
                
                $attrData = array();
                foreach($img_attrs as $img_attr) {
                    $attrData[$img_attr] = $image->getFile();
                }
                
                // L'image de position 1 devient l'image par défaut
                Mage::getSingleton('catalog/product_action')
                        ->updateAttributes(array($product->getId()), $attrData, 0);
            }
        }
        
        $product->getResource()->saveAttribute($product, 'media_gallery');
        $product->save();
        
        return true;
    }
    
    function create_art_img($image) {
        $product = Mage::getModel('catalog/product')->load($image['ref_article']);
        if (!$product->getId())
            return true;

        $ioAdapter = new Varien_Io_File();
        try {
            // Create temporary directory for api
            $tmpDirectory = Mage::getBaseDir('var') . "/tmp/";
            $ioAdapter->checkAndCreateFolder($tmpDirectory);

            $image_name = substr($image['url'], strrpos($image['url'], "/"));

            $image['url'] = str_replace(" ", "%20", $image['url']);
            LMB_EDI_Model_EDI::trace("image", "copy: " . $image['url'] . " to " . $tmpDirectory . $image_name);
            @copy($image['url'], $tmpDirectory . $image_name);
            
            sleep(2);
            
            if (!is_file($tmpDirectory . $image_name)) {
                LMB_EDI_Model_EDI::error("Save product image: Erreur 404 Image");
                return true;
            }

            $attributes = $product->getTypeInstance(true)
                    ->getSetAttributes($product);
            
            $mediaGalleryAttribute = $attributes["media_gallery"];
            $path_image = $mediaGalleryAttribute->getBackend()->addImage($product, $tmpDirectory . $image_name, null);
            
            LMB_EDI_Model_EDI::traceDebug("image", "ID=" . $path_image);
            $types = array();
            if ($image['ordre'] == 1) {
                $types = LMB_EDI_Model_Config::GET_PARAM('image_attributes');
                if (!empty($types)) {
                    $types = explode(";", $types);
                }
                
                if (empty($types) || !is_array($types)) {
                    $types = array('image', 'small_image', 'thumbnail');
                }
            }
            
            $data = array('label' => "Image de " . $product->getSku(),
                'position' => $image['ordre'],
                'types' => $types,
                'exclude' => 0);
            
            $mediaGalleryAttribute->getBackend()->updateImage($product, $path_image, $data);
            $mediaGalleryAttribute->getBackend()->setMediaAttribute($product, $data['types'], $path_image);

            $img = $mediaGalleryAttribute->getBackend()->getImage($product, $path_image);
            if (empty($img)) {
                throw new Exception("L'image '$path_image' semble ne pas avoir été crée...");
            }

            $product->save();
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error("Save product image:" . $e->getMessage());
        }


        $article_corres['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $article_corres['ref_lmb'] = $image['id_image'];
        $article_corres['ref_externe'] = $path_image;
        $article_corres['id_ref_type'] = 12;
        LMB_EDI_Model_Interface_Emetteur::envoi_LMB("create_correspondance", $article_corres);

        return true;
    }

    function delete_art_img($image) {
        LMB_EDI_Model_EDI::trace("reception", "*******************************************\nDEBUT delete_art_image " . print_r($image, true));
        if (empty($image['ref_article']))
            return true;
        $product = Mage::getModel('catalog/product')->load($image['ref_article']);
        if (!$product->getId())
            return true;
        $attributes = $product->getTypeInstance(true)
                ->getSetAttributes($product);

        $mediaGalleryAttribute = $attributes["media_gallery"];

        if (!$mediaGalleryAttribute->getBackend()->getImage($product, $image['id_img'])) {
            LMB_EDI_Model_EDI::error("L'image à supprimer n'existe pas... tant pis");
            return false;
        }
        LMB_EDI_Model_EDI::traceDebug("reception", "avant delete : media galerie = " . print_r($mediaGalleryAttribute['images'], true));
        $id_image = $mediaGalleryAttribute->getBackend()->removeImage($product, $image['id_img']);
        LMB_EDI_Model_EDI::trace("reception", "*******************************************\n FIN delete_art_image");
        $product->save();
        
        return true;
    }

    function modif_etat_cmd($command) {
        LMB_EDI_Model_EDI::trace("reception", "*******************************************\nmodif_etat_commande " . $command['ref']);
        /**
         *  2  	 Paiement accepté         payment
         * 	3	 Préparation en cours     preparation
         * 	4	 En cours de livraison    shipped
         * 	5 	 Livré
         * 	6 	 Annulé
         *
         */
		 
		LMB_EDI_Model_EDI::trace("modif_etat_commande", "*******************************************\n Commande: " . $command['ref']);
		 
        $state = Mage_Sales_Model_Order::STATE_NEW;
        if (defined("Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW")) {
            $state_paiement_acc = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
        }
        else {
            $state_paiement_acc = Mage_Sales_Model_Order::STATE_PROCESSING;
        }
        
        switch ($command['etat']) {
            case 2:
                $state = $state_paiement_acc;
                break;
            case 3:
                $state = Mage_Sales_Model_Order::STATE_PROCESSING;
                break;
            case 4:
                $state = Mage_Sales_Model_Order::STATE_NEW;
                break;
            case 5:
                $state = Mage_Sales_Model_Order::STATE_COMPLETE;
                break;
            case 6:
                $state = Mage_Sales_Model_Order::STATE_CANCELED;
                break;
            default:
                return LMB_EDI_Model_EDI::error("Etat de command invalide: " . $command['etat']);
        }
		
		LMB_EDI_Model_EDI::trace("modif_etat_commande", "etat LMB: " . $command['etat']);
		LMB_EDI_Model_EDI::trace("modif_etat_commande", "etat magento: " . $state);

        $order = Mage::getModel('sales/order')->loadByIncrementId($command['ref']);
        if (!$order->getId()) {
            LMB_EDI_Model_EDI::trace("modif_etat_commande", "*******************************************\n Commande inexistance");
            return true;
        }
        if ($state == $state_paiement_acc && $order->getState() == $state_paiement_acc) {
            return true;
        }

        $isCustomerNotified = false;
        $order->setStatus($state);
		LMB_EDI_Model_EDI::trace("modif_etat_commande", "setStatus: " . $state);
		
        if ($state == $state_paiement_acc) {
            $payment = Mage::getModel('sales/order_payment');
            $payment->setAmountPaid();
            $order->setPayment($payment);
        }
        
        $order->save();
        return true;
    }

    function create_reglement($rgmt) {
        $payment = Mage::getModel('sales/order_payment');
        $payment->setAmountPaid($rgmt['montant']);
        $order->setPayment($payment);
        return true;
    }

    function delete_reglement($id_rgmt) {
        $payment = Mage::getModel('sales/order_payment')->load($id_rgmt);
        $payment->cancel();
        return true;
    }

    public function update_client($client) {
        if (empty($client) || empty($client['ref_contact']) || empty($client['id_client_categorie'])) {
            return true;
        }
        
        LMB_EDI_Model_EDI::trace("reception", "***************DEBUT UPDATE CLIENT " . $client['ref_contact'] . "****************");

        $customer = Mage::getModel('customer/customer')
                ->load($client['ref_contact']);
        if (!$customer->getId()) {
            LMB_EDI_Model_EDI::trace("reception", "client inconnu");
            return true;
        }
        
        $customerGroup = Mage::getModel('customer/group')
                ->load($client['id_client_categorie']);
        if (!$customerGroup->getId()) {
            LMB_EDI_Model_EDI::trace("reception", "groupe de client ".$client['id_client_categorie']." inconnu");
            return true;
        }
        
        $customer->setGroupId($client['id_client_categorie']);
        
        try {
            $customer->save();
        }
        catch (Exception $e) {
            LMB_EDI_Model_EDI::trace("reception", "Impossible de mettre à jour le client : ".$e->getMessage());
            return true;
        }
        
        LMB_EDI_Model_EDI::traceDebug("envoi", "***************FIN UPDATE CLIENT " . $client['ref_contact'] . "****************");
        return true;
    }
    
    public function create_client_categ($categ) {
        $customerGroup = Mage::getModel('customer/group')
                ->setCode($categ['lib_categ'])
                ->setTaxClassId(LMB_EDI_Model_Config::TVA_CLIENT_CLASS());
        try {
            $customerGroup->save();
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error("Delete reglement:" . $e->getMessage());
        }


        $categ_corres['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $categ_corres['ref_lmb'] = $categ['id_client_categ'];
        $categ_corres['ref_externe'] = $customerGroup->getId();
        $categ_corres['id_ref_type'] = 15;
        LMB_EDI_Model_Interface_Emetteur::envoi_LMB("create_correspondance", $categ_corres);

        $tarif_corres['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $tarif_corres['ref_lmb'] = $categ['id_tarif'];
        $tarif_corres['ref_externe'] = $customerGroup->getId();
        $tarif_corres['id_ref_type'] = 19;
        LMB_EDI_Model_Interface_Emetteur::envoi_LMB("create_correspondance", $tarif_corres);
        return true;
    }
    
    public function create_constructeur($const) {
        $idManufacturer = LMB_EDI_Model_Config::GET_PARAM('marque_code');
        $attribute = Mage::getModel('eav/entity_attribute')->loadByCode(self::$product_entity_id, $idManufacturer);
        $attr['value']['ref_text_unique'][0] = $const['nom'];
        $attr['attribute_id'] = $attribute->getId();

        try {
            $setup = new Mage_Eav_Model_Entity_Setup('eav_setup');
            $setup->addAttributeOption($attr); //Ajout de l'option
            //Relecture de l'option pour obtenir son ID
            $collection = Mage::getModel('eav/entity_attribute_option')->getCollection()
                    ->setAttributeFilter($attribute->getId())
                    ->setStoreFilter(Mage::app()->getStore()->getId());
            $option = $collection->getItemByColumnValue('value', $const['nom']);
            LMB_EDI_Model_EDI::traceDebug("const", print_r($option, true));
        }
        catch (Exception $e) {
            return LMB_EDI_Model_EDI::error("Save carac constructeur:" . $e->getMessage());
        }

        $article_corres['id_edi_canal'] = LMB_EDI_Model_Config::ID_CANAL();
        $article_corres['ref_lmb'] = $const['ref_contact'];
        $article_corres['ref_externe'] = $option->getId();
        $article_corres['id_ref_type'] = 13;
        LMB_EDI_Model_Interface_Emetteur::envoi_LMB("create_correspondance", $article_corres);
        
        return true;
    }

    public function update_constructeur($const) {
        return true;
    }
}
