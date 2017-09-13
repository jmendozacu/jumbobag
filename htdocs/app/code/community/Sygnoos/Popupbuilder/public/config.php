<?php

define('SG_SKIN_IAMGE', "skin/adminhtml/base/default/media/PopupBuilder/img");
define('SG_SKIN_IAMGE_URL', Mage::getBaseUrl('skin')."adminhtml/base/default/media/PopupBuilder/img");
define('SG_SKIN_JAVASCRIPT_URL', Mage::getDesign()->getSkinUrl()."js/PopupBuilder/");
define('SG_SKIN_ADMIN_JS_URL', Mage::getBaseUrl('skin')."adminhtml/base/default/js/PopupBuilder/");
define('SG_SKIN_ADMIN_CSS_URL', Mage::getBaseUrl('skin')."adminhtml/base/default/css/PopupBuilder/");
define("SG_POPUP_COMMUNITY_REPO", 'Sygnoos_Popupbuilder');
define('SG_POPUP_DB_PREFIX', (string)Mage::getConfig()->getTablePrefix());
define('SG_POPUP_TABLE_LIMIT', 10);
define('SG_IP_TO_COUNTRY_SERVICE_URL', 'http://sygnoos.in/ip2data/?ip=');
define('SG_IP_TO_COUNTRY_SERVICE_THOKEN', 'd=b32e509a0c6da4147e7903f4bc0b60aa');
define('SG_POPUP_PLATINUM', 2);