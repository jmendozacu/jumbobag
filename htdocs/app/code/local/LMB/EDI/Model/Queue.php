<?php

class LMB_EDI_Model_Queue {

    protected $table_name;
    protected $nb_relance_max; //integer
    protected $nb_relance; //integer - nombre de relance effectuer
    protected $class_message;
    protected $pid; //objet pid

    public function __construct($class_message, $nb_relance_max = 0) {
        $this->class_message = $class_message;

        $this->pid = call_user_func(array($class_message, "getProcess"));
        $this->nb_relance = 0;
        $this->nb_relance_max = $nb_relance_max;
        Mage::register("isSecureArea", true);
    }

    public function start() {
        if (!$this->pid->isset_pid()) {
            $this->pid->set_pid();
			
			//Si plusieurs process démarrent exactement en même temps, tous vons mettre à jour le PID, mais un seul sera "l'élu" !
			//On attends 1s que tout le monde ai fini d'ecrire.
			sleep(1);
			if($this->pid->get_sys_pid() != getmypid()){
				//mail("alerte_edi@lundimatin.fr", "Tentative process simultanes", "Tentative d'exécution de process simultané !!! (".$this->class_message.")");
				exit("Tentative d'exécution de process simultané !!! (".$this->class_message.")");
			}
			
            $this->logme("***********************************************");
            $this->logme("Début de traitement de la file d'attente (" . getmypid() . ")");

            $start_time = time();
            $wait = true;
            $return = false;
            $restart = false;
            try {
                while (!$return) {
                    if (LMB_EDI_Model_PID::forceStop()) {
                        $this->logme("Arrêt forcé de la file par fichier pid/stop !");
                        break;
                    }
                    $this->pid->majDate_pid();
                    $event = call_user_func(array($this->class_message, "loadNext"));
                    if ($event) {
                        if ($event->getEtat() == LMB_EDI_Model_Config::$OK_CODE) {
                            $this->logme("Traitement du message " . $event->getId());
                            $wait = true;
                            if ($event->exec() === true) {
                                $this->logme("OK");
                                $event->remove();
                                $this->nb_relance = 0;
                            } else {
                                $this->logme("Il y a une erreur de traitement");
                                LMB_EDI_Model_EDI::error("Il y a une erreur de traitement");
                                if ($this->nb_relance < $this->nb_relance_max) {
                                    ++$this->nb_relance;
                                    $this->logme("Attente 5s");
                                    sleep(5);
                                    $this->logme("Nouvelle tentative après échec $this->nb_relance...");
                                } else
                                    $this->stop(); /* @todo ne pas stopper la file, relancer avec un écart de plus en plus important */
                            }
                        }
                        $event = null;
                    } else {
                        $this->logme("Pas de message à traiter");
                        if ($wait) {
                            $this->logme("Attente de nouvel évenement 5s...");
                            sleep(5);
                            $wait = false;
                        } else
                            $return = true;
                    }
                    if (LMB_EDI_Model_Config::DEBUG_STEP())
                        $return = true;
                    sleep(LMB_EDI_Model_Config::DELAY_QUEUE());
                    if ((time() - $start_time) > min(55, (ini_get("max_execution_time")?ini_get("max_execution_time")-5:60))) //Temps d'execution max 55s
                        $restart = $return = true;
                }
            } catch (Exception $e) {
                $this->logme("EXCEPTION :" . $e->getMessage());
                LMB_EDI_Model_EDI::error("EXCEPTION :" . $e->getMessage());
                $this->stop();
            }

            $this->pid->unset_pid();
            $this->logme("Fin de traitement de la file d'attente!\n****************************************************");
			
            if ($restart) {
                $this_page = substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"], "/lmbedi/echanges/")+strlen("/lmbedi/echanges/"));
                LMB_EDI_Model_EDI::newProcess($this_page, $this->pid);
            }
        }
    }

    protected function stop() {
        $this->logme("ERREUR ! ARRET DE LA FILE !");
        $this->pid->setError_pid();
        exit();
    }

    protected function logme($log) {
        LMB_EDI_Model_EDI::trace($this->class_message, $log);
    }

}

?>
