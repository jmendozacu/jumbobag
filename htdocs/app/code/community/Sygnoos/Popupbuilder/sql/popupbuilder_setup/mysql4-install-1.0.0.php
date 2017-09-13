<?php
require_once(Mage::getModuleDir('', 'Sygnoos_Popupbuilder').DS.'public'.DS.'boot.php');
$createtable = "CREATE TABLE IF NOT EXISTS ";
$dropTable = "DROP TABLE IF EXISTS ";
$installer = $this;

$installer->startSetup();

$imageTableName = SG_POPUP_DB_PREFIX."sg_image_popup";
$popupTableName = SG_POPUP_DB_PREFIX."sg_popup";
$htmlTableName = SG_POPUP_DB_PREFIX."sg_html_popup";
$fblikeTableName = SG_POPUP_DB_PREFIX."sg_fblike_popup";
$shortcodeTableName = SG_POPUP_DB_PREFIX."sg_shortCode_popup";
$iframeTableName = SG_POPUP_DB_PREFIX."sg_iframe_popup";
$videoTableName = SG_POPUP_DB_PREFIX."sg_video_popup";
$restrictionTableName = SG_POPUP_DB_PREFIX."sg_age_restriction_popup";
$countdowunTableName = SG_POPUP_DB_PREFIX."sg_countdown_popup";
$socialTableName = SG_POPUP_DB_PREFIX."sg_social_popup";
$exitIntentTableName = SG_POPUP_DB_PREFIX."sg_exit_intent_popup";
$subscriptionTableName = SG_POPUP_DB_PREFIX."sg_subscription_popup";
$subscripbersTableName = SG_POPUP_DB_PREFIX."sg_subscribers";
$contactFormTableName = SG_POPUP_DB_PREFIX."sg_contact_form_popup";
$popupInPagesTableName = SG_POPUP_DB_PREFIX."sg_popup_in_pages";

$installer->run(" $dropTable $imageTableName;
				$createtable $imageTableName (
					`id` int(11) NOT NULL,
					`path` varchar(255) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $popupTableName;
				$createtable $popupTableName (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`type` varchar(255) NOT NULL,
					`title` varchar(255) NOT NULL,
					`options` text NOT NULL,
					PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $htmlTableName;
				$createtable $htmlTableName (
					`id` int(11) NOT NULL,
					`content` text NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $fblikeTableName;
				$createtable $fblikeTableName (
					`id` int(11) NOT NULL,
					`content` text NOT NULL,
					`options` text NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $shortcodeTableName;
				$createtable $shortcodeTableName (
					`id` int(12) NOT NULL,
					`url` text NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $iframeTableName;
				$createtable $iframeTableName (
					`id` int(12) NOT NULL,
					`url` text NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $videoTableName;
				$createtable $videoTableName (
					`id` int(12) NOT NULL,
					`url` text NOT NULL,
					`real_url` text NOT NULL,
					`options` TEXT NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $restrictionTableName;
				$createtable $restrictionTableName (
					`id` int(12) NOT NULL,
					`content` TEXT NOT NULL,
					`yesButton` varchar(255) NOT NULL,
					`noButton` varchar(255) NOT NULL,
					`url` text NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $countdowunTableName;
				$createtable $countdowunTableName (
					`id` int(12) NOT NULL,
					`content` TEXT NOT NULL,
					`options` TEXT NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $socialTableName;
				$createtable $socialTableName (
					`id` int(12) NOT NULL,
					`socialContent` text NOT NULL,
					`buttons` TEXT NOT NULL,
					`socialOptions` text NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $exitIntentTableName;
				$createtable $exitIntentTableName (
					`id` int(12) NOT NULL,
					`content` TEXT NOT NULL,
					`options` text NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $subscriptionTableName;
				$createtable $subscriptionTableName (
					`id` int(12) NOT NULL,
					`content` TEXT NOT NULL,
					`options` text NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $subscripbersTableName;
				$createtable $subscripbersTableName (
					`id` int(12) NOT NULL AUTO_INCREMENT,
					`firstName` varchar(255),
					`lastName` varchar(255),
					`email` varchar(255),
					`subscriptionType` varchar(255),
					PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $contactFormTableName;
				$createtable $contactFormTableName (
					`id` int(12) NOT NULL AUTO_INCREMENT,
					`content` text,
					`options` text,
					PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				$dropTable $popupInPagesTableName;
				$createtable $popupInPagesTableName (
					`id` int(12) NOT NULL AUTO_INCREMENT,
					`popupId` varchar(255) DEFAULT NULL,
					`pageId` varchar(255) DEFAULT NULL,
					PRIMARY KEY (`id`),
					UNIQUE KEY `ppopup_pages` (`popupId`,`pageId`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				");
				

$installer->endSetup();