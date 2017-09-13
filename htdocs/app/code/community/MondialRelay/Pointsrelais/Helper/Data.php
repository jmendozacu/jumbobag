<?php
	/**
 	* Module data helper
 	*/

	class MondialRelay_Pointsrelais_Helper_Data extends Mage_Core_Helper_Abstract		
	{
		
		protected $_statArray = array(	
"1"=>"Enseigne invalide",
"2"=>"Num�ro d'enseigne vide ou inexistant",
"3"=>"Num�ro de compte enseigne invalide",
"4"=>"",
"5"=>"Num�ro de dossier enseigne invalide",
"6"=>"",
"7"=>"Num�ro de client enseigne invalide",
"8"=>"",
"9"=>"Nom de ville non reconnu ou non unique",
"10"=>"Type de collecte invalide ou incorrect (1/D > Domicile -- 3/R > Relais)",
"11"=>"Num�ro de Point Relais de collecte invalide",
"12"=>"Pays du Point Relais de collecte invalide",
"13"=>"Type de livraison invalide ou incorrect (1/D > Domicile -- 3/R > Relais)",
"14"=>"Num�ro du Point Relais de livraison invalide",
"15"=>"Pays du Point Relais de livraison invalide",
"16"=>"Code pays invalide",
"17"=>"Adresse invalide",
"18"=>"Ville invalide",
"19"=>"Code postal invalide",
"20"=>"Poids du colis invalide",
"21"=>"Taille (Longueur + Hauteur) du colis invalide",
"22"=>"Taille du Colis invalide","23"=>"",
"24"=>"Num�ro de Colis Mondial Relay invalide",
"25"=>"",
"26"=>"",
"27"=>"",
"28"=>"Mode de collecte invalide",
"29"=>"Mode de livraison invalide",
"30"=>"Adresse (L1) de l'exp�diteur invalide",
"31"=>"Adresse (L2) de l'exp�diteur invalide",
"32"=>"",
"33"=>"Adresse (L3) de l'exp�diteur invalide",
"34"=>"Adresse (L4) de l'exp�diteur invalide",
"35"=>"Ville de l'exp�diteur invalide",
"36"=>"Code postal de l'exp�diteur invalide",
"37"=>"Pays de l'exp�diteur invalide",
"38"=>"Num�ro de t�l�phone de l'exp�diteur invalide",
"39"=>"Adresse e-mail de l'exp�diteur invalide",
"40"=>"Action impossible sans ville ni code postal",
"41"=>"Mode de livraison invalide",
"42"=>"Montant CRT invalide",
"43"=>"Devise CRT invalide",
"44"=>"Valeur du colis invalide",
"45"=>"Devise de la valeur du colis invalide",
"46"=>"Plage de num�ro d'exp�dition �puis�e",
"47"=>"Nombre de colis invalide",
"48"=>"Multi-colis en Point Relais Interdit",
"49"=>"Mode de collecte ou de livraison invalide",
"50"=>"Adresse (L1) du destinataire invalide",
"51"=>"Adresse (L2) du destinataire invalide",
"52"=>"",
"53"=>"Adresse (L3) du destinataire invalide",
"54"=>"Adresse (L4) du destinataire invalide",
"55"=>"Ville du destinataire invalide",
"56"=>"Code postal du destinataire invalide",
"57"=>"Pays du destinataire invalide",
"58"=>"Num�ro de t�l�phone du destinataire invalide",
"59"=>"Adresse e-mail du destinataire invalide",
"60"=>"Champ texte libre invalide",
"61"=>"Top avisage invalide",
"62"=>"Instruction de livraison invalide",
"63"=>"Assurance invalide ou incorrecte",
"64"=>"Temps de montage invalide",
"65"=>"Top rendez-vous invalide",
"66"=>"Top reprise invalide",
"67"=>"",
"68"=>"",
"69"=>"",
"70"=>"Num�ro de Point Relais invalide",
"71"=>"",
"72"=>"Langue exp�diteur invalide",
"73"=>"Langue destinataire invalide",
"74"=>"Langue invalide",
"75"=>"",
"76"=>"",
"77"=>"",
"78"=>"",
"79"=>"",
"80"=>"Code tracing : Colis enregistr�",
"81"=>"Code tracing : Colis en traitement chez Mondial Relay",
"82"=>"Code tracing : Colis livr�",
"83"=>"Code tracing : Anomalie",
"84"=>"(R�serv� Code Tracing)",
"85"=>"(R�serv� Code Tracing)",
"86"=>"(R�serv� Code Tracing)",
"87"=>"(R�serv� Code Tracing)",
"88"=>"(R�serv� Code Tracing)",
"89"=>"(R�serv� Code Tracing)",
"90"=>"AS400 indisponible",
"91"=>"Num�ro d'exp�dition invalide",
"92"=>"",
"93"=>"Aucun �l�ment retourn� par le plan de tri",
"94"=>"Colis Inexistant",
"95"=>"Compte Enseigne non activ�",
"96"=>"Type d'enseigne incorrect en Base",
"97"=>"Cl� de s�curit� invalide",
"98"=>"Service Indisponible",
"99"=>"Erreur g�n�rique du service"
);
		
		public function convertStatToTxt($stat){
			return $_statArray[$stat];
		}
		
	}
	
