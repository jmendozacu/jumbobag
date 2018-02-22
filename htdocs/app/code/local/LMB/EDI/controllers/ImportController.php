<?php

class LMB_EDI_ImportController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $serial = $this->getRequest()->getParam("serial_code", false);
        $authentified = ($serial == LMB_EDI_Model_Config::CODE_CONNECTION());

        if ($authentified) {
            echo "<form action=\"/lmbedi/import/start/serial_code/" . $serial . "\" method=\"post\">";
            echo "<ul>";
            echo '<li><input type="checkbox" value="1" name="categs" />Import des catégories d\'articles';
            echo '<ul>';
            echo '<li><input type="checkbox" value="1" name="marques" />Import des marques';
            echo '<ul>';
            echo '<li><input type="checkbox" value="1" name="articles" />Import des articles<br/>';
            echo '<p>Import manuel : start/serial_code/' . $serial . '/articles/1/id_categ/xxx</p>';
            echo '<ul>';
            echo '<li><input type="checkbox" value="1" name="images_art" />Import des images d\'articles</li>';
            echo '<li><input type="checkbox" value="1" name="liaisons_art" />Import des liaisons d\'articles</li>';
            echo '</ul>';
            echo '</li>';
            echo '</ul>';
            echo '</li>';
            echo '</ul>';
            echo '</li>';
            echo '<li><input type="checkbox" value="1" name="clients" />Import des clients</li>';
            echo '<li><input type="checkbox" value="1" name="commandes" />Import des commandess</li>';
            echo '<!--<li>Import des fournisseurs</li>-->';
            echo '</ul>';
            echo '<input type="submit" value="Importer" />';
            echo '</form>';
        }
        else {
            echo "Échec d'authentification";
        }
    }

    public function startAction() {
        $serial = $this->getRequest()->getParam("serial_code", false);
        $categs = $this->getRequest()->getParam("categs", false);
        $articles = $this->getRequest()->getParam("articles", false);
        $marques = $this->getRequest()->getParam("marques", false);
        $images = $this->getRequest()->getParam("images_art", false);
        $liaisons = $this->getRequest()->getParam("liaisons_art", false);
        $commandes = $this->getRequest()->getParam("commandes", false);
        $clients = $this->getRequest()->getParam("clients", false);
        $id_categ = $this->getRequest()->getParam("id_categ", false);
        $authentified = ($serial == LMB_EDI_Model_Config::CODE_CONNECTION());

        if ($authentified) {
            ignore_user_abort(true);
            set_time_limit(0);
            ini_set("memory_limit", "512M");
            ob_implicit_flush(true);
            $start_time = time();
            $emetteur = new LMB_EDI_Model_Interface_Emetteur();

            echo '<h2>Import de données magento vers LMB</h2>';

            if ($categs) {
                echo "Import des catégories...";
                $emetteur->recup_categs();
                echo "<br/>Transmission des catégories...";
                while (LMB_EDI_Model_Liaison_MessageEnvoi::loadNext()) {
                    if (LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()->iserror_pid()) {
                        echo "<br/>Import réussi, mais la transmission n'as pas été effectuée à cause d'erreur(s)...";
                        break;
                    }
                    sleep(5);
                }
            }

            if ($marques) {
                echo "<br/>Import des marques...";
                $emetteur->recup_marques();
                echo "<br/>Transmission des marques...";
                while (LMB_EDI_Model_Liaison_MessageEnvoi::loadNext()) {
                    if (LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()->iserror_pid()) {
                        echo "<br/>Import réussi, mais la transmission n'as pas été effectuée à cause d'erreur(s)...";
                        break;
                    }
                    sleep(5);
                }
            }

            if ($articles) {
                echo "<br/>Import des produits...";
                $emetteur->recup_products($id_categ);
                echo "<br/>Transmission des produits...";
                while (LMB_EDI_Model_Liaison_MessageEnvoi::loadNext()) {
                    if (LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()->iserror_pid()) {
                        echo "<br/>Import réussi, mais la transmission n'as pas été effectuée à cause d'erreur(s)...";
                        break;
                    }
                    sleep(5);
                }
            }

            if ($images){
                echo "<br/>Import des images...";
                $emetteur->recup_images();
                echo "<br/>Transmission des images...";
                while (LMB_EDI_Model_Liaison_MessageEnvoi::loadNext()) {
                    if (LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()->iserror_pid()) {
                        echo "<br/>Import réussi, mais la transmission n'as pas été effectuée à cause d'erreur(s)...";
                        break;
                    }
                    sleep(5);
                }
            }
            
            if ($liaisons){
                echo "<br/>Import des liaisons d'articles...";
                $emetteur->recup_art_liaisons();
                echo "<br/>Transmission des liaisons d'articles...";
                while (LMB_EDI_Model_Liaison_MessageEnvoi::loadNext()) {
                    if (LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()->iserror_pid()) {
                        echo "<br/>Import réussi, mais la transmission n'as pas été effectuée à cause d'erreur(s)...";
                        break;
                    }
                    sleep(5);
                }
            }
            
            if ($commandes) {
                echo "<br/>Import des commandes...";
                $emetteur->recup_commandes();
                echo "<br/>Transmission des commandes...";
                while (LMB_EDI_Model_Liaison_MessageEnvoi::loadNext()) {
                    if (LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()->iserror_pid()) {
                        echo "<br/>Import réussi, mais la transmission n'as pas été effectuée à cause d'erreur(s)...";
                        break;
                    }
                    sleep(5);
                }
            }
            
            if ($clients) {
                echo "<br/>Import des clients...";
                $emetteur->recup_clients();
                echo "<br/>Transmission des clients...";
                while (LMB_EDI_Model_Liaison_MessageEnvoi::loadNext()) {
                    if (LMB_EDI_Model_Liaison_MessageEnvoi::getProcess()->iserror_pid()) {
                        echo "<br/>Import réussi, mais la transmission n'as pas été effectuée à cause d'erreur(s)...";
                        break;
                    }
                    sleep(5);
                }
            }
            
            echo "<br/>Fin de l'importation...";
        }
    }
}
