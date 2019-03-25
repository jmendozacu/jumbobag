<?php

/**
 * Nwdthemes Revolution Slider Extension
 *
 * @package     Revslider
 * @author		Nwdthemes <mail@nwdthemes.com>
 * @link		http://nwdthemes.com/
 * @copyright   Copyright (c) 2014. Nwdthemes
 * @license     http://themeforest.net/licenses/terms/regular
 */

$css = array(
	array('.tp-caption.medium_grey',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"0px 2px 5px rgba(0, 0, 0, 0.5)","font-weight":"700","font-size":"20px","line-height":"20px","font-family":"Arial","padding":"2px 4px","margin":"0px","border-width":"0px","border-style":"none","background-color":"#888","white-space":"nowrap"}'),
	array('.tp-caption.small_text',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"0px 2px 5px rgba(0, 0, 0, 0.5)","font-weight":"700","font-size":"14px","line-height":"20px","font-family":"Arial","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap"}'),
	array('.tp-caption.medium_text',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"0px 2px 5px rgba(0, 0, 0, 0.5)","font-weight":"700","font-size":"20px","line-height":"20px","font-family":"Arial","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap"}'),
	array('.tp-caption.large_text',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"0px 2px 5px rgba(0, 0, 0, 0.5)","font-weight":"700","font-size":"40px","line-height":"40px","font-family":"Arial","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap"}'),
	array('.tp-caption.very_large_text',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"0px 2px 5px rgba(0, 0, 0, 0.5)","font-weight":"700","font-size":"60px","line-height":"60px","font-family":"Arial","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap","letter-spacing":"-2px"}'),
	array('.tp-caption.very_big_white',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"none","font-weight":"800","font-size":"60px","line-height":"60px","font-family":"Arial","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap","padding":"0px 4px","padding-top":"1px","background-color":"#000"}'),
	array('.tp-caption.very_big_black',	NULL,	NULL,	'{"position":"absolute","color":"#000","text-shadow":"none","font-weight":"700","font-size":"60px","line-height":"60px","font-family":"Arial","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap","padding":"0px 4px","padding-top":"1px","background-color":"#fff"}'),
	array('.tp-caption.modern_medium_fat',	NULL,	NULL,	'{"position":"absolute","color":"#000","text-shadow":"none","font-weight":"800","font-size":"24px","line-height":"20px","font-family":"\'Open Sans\', sans-serif","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap"}'),
	array('.tp-caption.modern_medium_fat_white',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"none","font-weight":"800","font-size":"24px","line-height":"20px","font-family":"\'Open Sans\', sans-serif","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap"}'),
	array('.tp-caption.modern_medium_light',	NULL,	NULL,	'{"position":"absolute","color":"#000","text-shadow":"none","font-weight":"300","font-size":"24px","line-height":"20px","font-family":"\'Open Sans\', sans-serif","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap"}'),
	array('.tp-caption.modern_big_bluebg',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"none","font-weight":"800","font-size":"30px","line-height":"36px","font-family":"\'Open Sans\', sans-serif","padding":"3px 10px","margin":"0px","border-width":"0px","border-style":"none","background-color":"#4e5b6c","letter-spacing":"0"}'),
	array('.tp-caption.modern_big_redbg',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"none","font-weight":"300","font-size":"30px","line-height":"36px","font-family":"\'Open Sans\', sans-serif","padding":"3px 10px","padding-top":"1px","margin":"0px","border-width":"0px","border-style":"none","background-color":"#de543e","letter-spacing":"0"}'),
	array('.tp-caption.modern_small_text_dark',	NULL,	NULL,	'{"position":"absolute","color":"#555","text-shadow":"none","font-size":"14px","line-height":"22px","font-family":"Arial","margin":"0px","border-width":"0px","border-style":"none","white-space":"nowrap"}'),
	array('.tp-caption.boxshadow',	NULL,	NULL,	'{"-moz-box-shadow":"0px 0px 20px rgba(0, 0, 0, 0.5)","-webkit-box-shadow":"0px 0px 20px rgba(0, 0, 0, 0.5)","box-shadow":"0px 0px 20px rgba(0, 0, 0, 0.5)"}'),
	array('.tp-caption.black',	NULL,	NULL,	'{"color":"#000","text-shadow":"none"}'),
	array('.tp-caption.noshadow',	NULL,	NULL,	'{"text-shadow":"none"}'),
	array('.tp-caption.thinheadline_dark',	NULL,	NULL,	'{"position":"absolute","color":"rgba(0,0,0,0.85)","text-shadow":"none","font-weight":"300","font-size":"30px","line-height":"30px","font-family":"\'Open Sans\'","background-color":"transparent"}'),
	array('.tp-caption.thintext_dark',	NULL,	NULL,	'{"position":"absolute","color":"rgba(0,0,0,0.85)","text-shadow":"none","font-weight":"300","font-size":"16px","line-height":"26px","font-family":"\'Open Sans\'","background-color":"transparent"}'),
	array('.tp-caption.largeblackbg',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"none","font-weight":"300","font-size":"50px","line-height":"70px","font-family":"\'Open Sans\'","background-color":"#000","padding":"0px 20px","-webkit-border-radius":"0px","-moz-border-radius":"0px","border-radius":"0px"}'),
	array('.tp-caption.largepinkbg',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"none","font-weight":"300","font-size":"50px","line-height":"70px","font-family":"\'Open Sans\'","background-color":"#db4360","padding":"0px 20px","-webkit-border-radius":"0px","-moz-border-radius":"0px","border-radius":"0px"}'),
	array('.tp-caption.largewhitebg',	NULL,	NULL,	'{"position":"absolute","color":"#000","text-shadow":"none","font-weight":"300","font-size":"50px","line-height":"70px","font-family":"\'Open Sans\'","background-color":"#fff","padding":"0px 20px","-webkit-border-radius":"0px","-moz-border-radius":"0px","border-radius":"0px"}'),
	array('.tp-caption.largegreenbg',	NULL,	NULL,	'{"position":"absolute","color":"#fff","text-shadow":"none","font-weight":"300","font-size":"50px","line-height":"70px","font-family":"\'Open Sans\'","background-color":"#67ae73","padding":"0px 20px","-webkit-border-radius":"0px","-moz-border-radius":"0px","border-radius":"0px"}'),
	array('.tp-caption.excerpt',	NULL,	NULL,	'{"font-size":"36px","line-height":"36px","font-weight":"700","font-family":"Arial","color":"#ffffff","text-decoration":"none","background-color":"rgba(0, 0, 0, 1)","text-shadow":"none","margin":"0px","letter-spacing":"-1.5px","padding":"1px 4px 0px 4px","width":"150px","white-space":"normal !important","height":"auto","border-width":"0px","border-color":"rgb(255, 255, 255)","border-style":"none"}'),
	array('.tp-caption.large_bold_grey',	NULL,	NULL,	'{"font-size":"60px","line-height":"60px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(102, 102, 102)","text-decoration":"none","background-color":"transparent","text-shadow":"none","margin":"0px","padding":"1px 4px 0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_thin_grey',	NULL,	NULL,	'{"font-size":"34px","line-height":"30px","font-weight":"300","font-family":"\'Open Sans\'","color":"rgb(102, 102, 102)","text-decoration":"none","background-color":"transparent","padding":"1px 4px 0px","text-shadow":"none","margin":"0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.small_thin_grey',	NULL,	NULL,	'{"font-size":"18px","line-height":"26px","font-weight":"300","font-family":"\'Open Sans\'","color":"rgb(117, 117, 117)","text-decoration":"none","background-color":"transparent","padding":"1px 4px 0px","text-shadow":"none","margin":"0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.lightgrey_divider',	NULL,	NULL,	'{"text-decoration":"none","background-color":"rgba(235, 235, 235, 1)","width":"370px","height":"3px","background-position":"initial initial","background-repeat":"initial initial","border-width":"0px","border-color":"rgb(34, 34, 34)","border-style":"none"}'),
	array('.tp-caption.large_bold_darkblue',	NULL,	NULL,	'{"font-size":"58px","line-height":"60px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(52, 73, 94)","text-decoration":"none","background-color":"transparent","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_bg_darkblue',	NULL,	NULL,	'{"font-size":"20px","line-height":"20px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(255, 255, 255)","text-decoration":"none","background-color":"rgb(52, 73, 94)","padding":"10px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_bold_red',	NULL,	NULL,	'{"font-size":"24px","line-height":"30px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(227, 58, 12)","text-decoration":"none","background-color":"transparent","padding":"0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_light_red',	NULL,	NULL,	'{"font-size":"21px","line-height":"26px","font-weight":"300","font-family":"\'Open Sans\'","color":"rgb(227, 58, 12)","text-decoration":"none","background-color":"transparent","padding":"0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_bg_red',	NULL,	NULL,	'{"font-size":"20px","line-height":"20px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(255, 255, 255)","text-decoration":"none","background-color":"rgb(227, 58, 12)","padding":"10px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_bold_orange',	NULL,	NULL,	'{"font-size":"24px","line-height":"30px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(243, 156, 18)","text-decoration":"none","background-color":"transparent","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_bg_orange',	NULL,	NULL,	'{"font-size":"20px","line-height":"20px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(255, 255, 255)","text-decoration":"none","background-color":"rgb(243, 156, 18)","padding":"10px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.grassfloor',	NULL,	NULL,	'{"text-decoration":"none","background-color":"rgba(160, 179, 151, 1)","width":"4000px","height":"150px","border-width":"0px","border-color":"rgb(34, 34, 34)","border-style":"none"}'),
	array('.tp-caption.large_bold_white',	NULL,	NULL,	'{"font-size":"58px","line-height":"60px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(255, 255, 255)","text-decoration":"none","background-color":"transparent","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_light_white',	NULL,	NULL,	'{"font-size":"30px","line-height":"36px","font-weight":"300","font-family":"\'Open Sans\'","color":"rgb(255, 255, 255)","text-decoration":"none","background-color":"transparent","padding":"0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.mediumlarge_light_white',	NULL,	NULL,	'{"font-size":"34px","line-height":"40px","font-weight":"300","font-family":"\'Open Sans\'","color":"rgb(255, 255, 255)","text-decoration":"none","background-color":"transparent","padding":"0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.mediumlarge_light_white_center',	NULL,	NULL,	'{"font-size":"34px","line-height":"40px","font-weight":"300","font-family":"\'Open Sans\'","color":"#ffffff","text-decoration":"none","background-color":"transparent","padding":"0px 0px 0px 0px","text-align":"center","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_bg_asbestos',	NULL,	NULL,	'{"font-size":"20px","line-height":"20px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(255, 255, 255)","text-decoration":"none","background-color":"rgb(127, 140, 141)","padding":"10px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.medium_light_black',	NULL,	NULL,	'{"font-size":"30px","line-height":"36px","font-weight":"300","font-family":"\'Open Sans\'","color":"rgb(0, 0, 0)","text-decoration":"none","background-color":"transparent","padding":"0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.large_bold_black',	NULL,	NULL,	'{"font-size":"58px","line-height":"60px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(0, 0, 0)","text-decoration":"none","background-color":"transparent","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.mediumlarge_light_darkblue',	NULL,	NULL,	'{"font-size":"34px","line-height":"40px","font-weight":"300","font-family":"\'Open Sans\'","color":"rgb(52, 73, 94)","text-decoration":"none","background-color":"transparent","padding":"0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.small_light_white',	NULL,	NULL,	'{"font-size":"17px","line-height":"28px","font-weight":"300","font-family":"\'Open Sans\'","color":"rgb(255, 255, 255)","text-decoration":"none","background-color":"transparent","padding":"0px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.roundedimage',	NULL,	NULL,	'{"border-width":"0px","border-color":"rgb(34, 34, 34)","border-style":"none"}'),
	array('.tp-caption.large_bg_black',	NULL,	NULL,	'{"font-size":"40px","line-height":"40px","font-weight":"800","font-family":"\'Open Sans\'","color":"rgb(255, 255, 255)","text-decoration":"none","background-color":"rgb(0, 0, 0)","padding":"10px 20px 15px","border-width":"0px","border-color":"rgb(255, 214, 88)","border-style":"none"}'),
	array('.tp-caption.mediumwhitebg',	NULL,	NULL,	'{"font-size":"30px","line-height":"30px","font-weight":"300","font-family":"\'Open Sans\'","color":"rgb(0, 0, 0)","text-decoration":"none","background-color":"rgb(255, 255, 255)","padding":"5px 15px 10px","text-shadow":"none","border-width":"0px","border-color":"rgb(0, 0, 0)","border-style":"none"}')
);
foreach ($css as $row) {
	$data = array(
		'handle'	=> $row[0],
		'settings' 	=> $row[1],
		'hover' 	=> $row[2],
		'params' 	=> $row[3]
	);
    Mage::getModel('nwdrevslider/css')->setData($data)->save();
}

$options = array(
	array('revslider-static-css',	".tp-caption a {\r\ncolor:#ff7302;\r\ntext-shadow:none;\r\n-webkit-transition:all 0.2s ease-out;\r\n-moz-transition:all 0.2s ease-out;\r\n-o-transition:all 0.2s ease-out;\r\n-ms-transition:all 0.2s ease-out;\r\n}\r\n\r\n.tp-caption a:hover {\r\ncolor:#ffa902;\r\n}\r\n.tp-caption a {\r\ncolor:#ff7302;\r\ntext-shadow:none;\r\n-webkit-transition:all 0.2s ease-out;\r\n-moz-transition:all 0.2s ease-out;\r\n-o-transition:all 0.2s ease-out;\r\n-ms-transition:all 0.2s ease-out;\r\n}\r\n\r\n.tp-caption a:hover {\r\ncolor:#ffa902;\r\n}\r\n\r\n\r\n.tp-caption.big_caption_3,\r\n.tp-caption.big_caption_2,\r\n.tp-caption.big_caption_4,\r\n.tp-caption.big_caption_5,\r\n.tp-caption.big_caption_6,\r\n.tp-caption.big_caption_7,\r\n.tp-caption.big_caption_8,\r\n.tp-caption.big_caption_9,\r\n.tp-caption.big_caption_10,\r\n.tp-caption.big_caption_11,\r\n.tp-caption.big_caption_12,\r\n.tp-caption.big_caption_13,\r\n.tp-caption.big_caption_3_white,\r\n.tp-caption.big_caption_2_white,\r\n.tp-caption.big_caption_4_white{\r\n            position: absolute; \r\n			color: #e14f4f; \r\n			text-shadow: none; \r\n			\r\n			font-size: 80px; \r\n			line-height: 80px; \r\n			 font-family: \"Oswald\";\r\n			border-width: 0px; \r\n			border-style: none; \r\n \r\n								\r\n		}\r\n    .tp-caption.big_caption_2, .tp-caption.big_caption_2_white {font-size:48px;}\r\n.tp-caption.big_caption_4, .tp-caption.big_caption_4_white {font-size:124px; color:#fff;}\r\n.tp-caption.big_caption_5  {font-size:120px;color:#94BB54;font-weight:800;}\r\n.tp-caption.big_caption_6 {font-size:84px;color:#94BB54;font-weight:800;}\r\n.tp-caption.big_caption_7 {font-size:50px;color:#333;font-weight:800;}\r\n.tp-caption.big_caption_8  {font-size:24px;color:#fff;font-family: \"Open Sans\";}\r\n.tp-caption.big_caption_9  {font-size:100px;color:#fff !important;}\r\n.tp-caption.big_caption_10  {font-size:55px;color:#2a2b2c;font-weight:bold;}\r\n.tp-caption.big_caption_11  {font-size:100px;color:#fff;}\r\n.tp-caption.big_caption_12  {font-size:200px;color:#94BB54;font-weight:800;}\r\n.tp-caption.big_caption_13 {font-size:24px;color:#aaa;font-family: \"Open Sans\";}\r\n.tp-caption.handwriting, .tp-caption.handwriting_white{\r\n            position: absolute; \r\n			color: #fff; \r\n			text-shadow: none; \r\n			 \r\n			font-size: 144px; \r\n			line-height: 44px; \r\n			 font-family: \"Dancing Script\";\r\n			padding:15px 40px 15px 40px;\r\n			margin: 0px; \r\n			border-width: 0px; \r\n			border-style: none; \r\n  \r\n								\r\n		}\r\n\r\n.tp-caption.store_button a {\r\n            position: absolute; \r\n			color: #fff; \r\n			text-shadow: none; \r\n			\r\n			font-size: 18px; \r\n			line-height: 18px; \r\n            font-weight:bold;\r\n			 font-family: \"Open Sans\";\r\n			padding:20px 35px 20px 35px !important;\r\n			margin: 0px; \r\n  			cursor:pointer;\r\n      		background:#1ABC9C;\r\n  	        border-radius:5px;\r\n            border-bottom:4px solid #16A085;\r\n 						\r\n}\r\n.tp-caption.store_button_white a{position: absolute; \r\n			color: #fff; \r\n			text-shadow: none;\r\n            font-size:14px;\r\n    		font-weight:normal;\r\n    		padding:10px 20px !important;\r\n            background:#379BDE;\r\n      		border-bottom:4px solid #2980B9;\r\n      		border-radius:5px ;\r\n    }\r\n.tp-caption.store_button_white a:hover{\r\n  	    background:#2980B9;\r\n        border-bottom:4px solid #379BDE;\r\n      }\r\n.tp-caption.store_button a, .tp-caption.store_button_white a {\r\n	color: #fff !important; \r\n}\r\n.tp-caption.store_button_white a {color:#fff !important;}\r\n\r\n\r\n.tp-caption a {\r\ncolor:#ff7302;\r\ntext-shadow:none;\r\n-webkit-transition:all 0.2s ease-out;\r\n-moz-transition:all 0.2s ease-out;\r\n-o-transition:all 0.2s ease-out;\r\n-ms-transition:all 0.2s ease-out;\r\n}\r\n\r\n.tp-caption a:hover {\r\ncolor:#ffa902;\r\n}\r\n\r\n.tp-caption.big_caption_3_white,\r\n.tp-caption.big_caption_2_white,\r\n.tp-caption.big_caption_4_white,\r\n.tp-caption.handwriting_white{\r\n    color:#fff;\r\n    \r\n    }\r\n.tp-caption a {\r\ncolor:#ff7302;\r\ntext-shadow:none;\r\n-webkit-transition:all 0.2s ease-out;\r\n-moz-transition:all 0.2s ease-out;\r\n-o-transition:all 0.2s ease-out;\r\n-ms-transition:all 0.2s ease-out;\r\n}\r\n\r\n.tp-caption a:hover {\r\ncolor:#ffa902;\r\n}\r\n.tp-caption a {\r\ncolor:#ff7302;\r\ntext-shadow:none;\r\n-webkit-transition:all 0.2s ease-out;\r\n-moz-transition:all 0.2s ease-out;\r\n-o-transition:all 0.2s ease-out;\r\n-ms-transition:all 0.2s ease-out;\r\n}\r\n\r\n.tp-caption a:hover {\r\ncolor:#ffa902;\r\n}")
);
foreach ($options as $row) {
	$data = array(
		'handle'	=> $row[0],
		'option'	=> $row[1]
	);
    Mage::getModel('nwdrevslider/options')->setData($data)->save();
}

$settings = array(
	array('a:4:{s:17:"includes_globally";s:2:"on";s:18:"pages_for_includes";s:0:"";s:15:"show_dev_export";s:3:"off";s:11:"enable_logs";s:3:"off";}',	'')
);
foreach ($settings as $row) {
	$data = array(
		'general'	=> $row[0],
		'params' 	=> $row[1]
	);
    Mage::getModel('nwdrevslider/settings')->setData($data)->save();
}
