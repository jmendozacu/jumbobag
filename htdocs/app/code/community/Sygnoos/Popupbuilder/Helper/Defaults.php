<?php
class Sygnoos_Popupbuilder_Helper_Defaults extends Mage_Core_Helper_Abstract {

	public function dataArray() {

		$sgPopup = array(
			'escKey'=> true,
			'closeButton' => true,
			'scrolling'=> true,
			'opacity'=>0.8,
			'reposition' => true,
			'width' => '640px',
			'height' => '480px',
			'initialWidth' => '300',
			'initialHeight' => '100',
			'maxWidth' => false,
			'maxHeight' => false,
			'overlayClose' => true,
			'contentClick'=>false,
			'fixed' => false,
			'top' => false,
			'right' => false,
			'bottom' => false,
			'left' => false,
			'duration' => 1,
			'delay' => 0,
			'theme-close-text' => 'Close'
		);

		$popupTitles = array(

			'image' => 'Image',
			'html' => 'HTML',
			'fblike' => 'Facebook',
			'iframe' => 'Iframe',
			'video' => 'Video',
			'shortcode' => 'Shortcode',
			'ageRestriction' => 'Age Restriction',
			'countdown' => 'Countdown',
			'social' => 'Social',
			'exitIntent' => 'Exit Intent',
			'subscription' => 'Subscription',
			'contactForm' => 'Contact Form'
		);

		$popupProDefaultValues = array(
			'closeType' => false,
			'onScrolling' => false,
			'video-autoplay' => false,
			'forMobile' => false,
			'openMobile' => false,
			'repetPopup' => false,
			'disablePopup' => false,
			'disablePopupOverlay' => false,
			'autoClosePopup' => false,
			'fbStatus' => true,
			'twitterStatus' => true,
			'emailStatus' => true,
			'linkedinStatus' => true,
			'googleStatus' => true,
			'pinterestStatus' => true,
			'sgSocialLabel'=>true,
			'roundButtons'=>false,
			'sgShareUrl' => 'http://',
			'pushToBottom' => true,
			'allPages' => false,
			'allPosts' => false,
			'allCategories' => false,
			'allProducts' => false,
			'onceExpiresTime' => 7,
			'overlay-custom-classs' => 'sg-popup-overlay',
			'content-custom-classs' => 'sg-popup-content',
			'countryStatus' => false,
			'allowCountries' => 'allow',
			'countdownNumbersTextColor' => '',
			'countdownNumbersBgColor' => '',
			'countDownLang' => 'English',
			'countdown-position' => true,
			'time-zone' => 'Etc/GMT',
			'due-date' => date('M d Y', strtotime(' +1 day')),
			'exit-intent-type' => "soft",
			'exit-intent-expire-time' => '1',
			'subs-first-name-status' => true,
			'subs-last-name-status' => true,
			'subscription-email' => 'Email *',
			'subs-first-name' => 'First name',
			'subs-last-name' => 'Last name',
			'subs-button-bgcolor' => '#239744',
			'subs-button-color' => '#FFFFFF',
			'subs-text-input-bgcolor' => '#FFFFFF',
			'subs-inputs-color' => '#000000',
			'subs-placeholder-color' => '#CCCCCC',
			'subs-text-bordercolor' => '#CCCCCC',
			'subs-btn-title' => 'Subscribe',
			'subs-text-height' => '30px',
			'subs-btn-height' => '30px',
			'subs-text-width' => '200px',
			'subs-btn-width' => '200px',
			'restrcition-yes-background-color' => '#52a065',
			'restriction-no-background-color' => '#3b3e43',
			'restrcition-yes-text-color' => "#ffffff",
			'restrcition-no-text-color' => "#ffffff",
			'restrcition-yes-border-radius' => '5',
			'restrcition-no-border-radius' => '5',
			'subs-text-border-width' => '2px',
			'subs-success-message' =>'You have successfully subscribed to the newsletter.',
			'subs-validation-message' => 'This field is required.',
			'subs-btn-progress-title' => 'Please wait ...',
			'contact-name' => 'Name *',
			'contact-email' => 'E-mail *',
			'contact-message' => 'Message *',
			'contact-subject' => 'Subject *',
			'contact-success-message' => 'Your message has been successfully sent.',
			'contact-btn-title' => 'Contact',
			'contact-validate-email' => 'Please enter a valid email.',
			'contact-fail-message' => 'Unable to send.'
		);

		$radioElements = array(
			array(
				'name'=>'shareUrlType',
				'value'=>'activeUrl',
				'additionalHtml'=>''.'<span>'.'Use active URL'.'</span></span></div><div class="col-md-4">
								<span class="span-width-static"></span><span class="dashicons dashicons-info scrollingImg sameImageStyle sg-active-url"></span></div>',
				'newline' => true
			),
			array(
				'name'=>'shareUrlType',
				'value'=>'shareUrl',
				'additionalHtml'=>''.'<span class="sg-socila-share">'.'Share url'.'</span></span></div><div class="col-md-4">'.' <input class="form-control input-md sg-active-url" type="text" name="sgShareUrl" value="'.@$getData['sgShareUrl'].'"></div>',
				'newline' => true
			)
		);

		$countriesRadio = array(
			array(
				'name'=>'allowCountries',
				'value'=>'allow',
				'additionalHtml'=>'<span class="countries-radio-text allow-countries">Allow</span>',
				'newline' => false
			),
			array(
				'name'=>'allowCountries',
				'value'=>'disallow',
				'additionalHtml'=>'<span class="countries-radio-text">Disallow</span>',
				'newline' => true
			)
		);

		$radiobuttons = array(
			array(
					"title" => "Soft mode:",
					"value" => "soft",
					"info" => "<span class=\"dashicons dashicons-info repositionImg sameImageStyle\"></span>
								<span class='infoReposition samefontStyle'>
									If the user navigate away from the site the popup will appear.
								</span>"
				),
			array(
					"title" => "Aggressive mode:",
					"value" => "aggressive",
					"info" => "<span class=\"dashicons dashicons-info repositionImg sameImageStyle\"></span>
								<span class='infoReposition samefontStyle'>
									If the user try to navigate elsewhere he/she will be interrupted and forced to read the message and choose to leave or stay.
									After the alert box popup will appear.
								</span>"
				),
			array(
					"title" => "Soft and Aggressive modes:",
					"value" => "softAndAgressive",
					"info" => "<span class=\"dashicons dashicons-info repositionImg sameImageStyle\"></span>
								<span class='infoReposition samefontStyle'>
									This will enable the both modes. Depends which action will be triggered first.
								</span>"
				),
			array(
					"title" => "Aggressive without popup:",
					"value" => "agresiveWithoutPopup",
					"info" => "<span class='dashicons dashicons-info repositionImg sameImageStyle'></span>
								<span class='infoReposition samefontStyle'>
									Tha same as aggressive mode but without a popup.
								</span>"
				),

		);

		$sgPopupEffects = array(
			'No effect' => 'No Effect',
			'flip' => 'flip',
			'shake' => 'shake',
			'wobble' => 'wobble',
			'swing' => 'swing',
			'flash' => 'flash',
			'bounce' => 'bounce',
			'pulse' => 'pulse',
			'rubberBand' => 'rubberBand',
			'tada' => 'tada',
			'jello' => 'jello',
			'rotateIn' => 'rotateIn',
			'fadeIn' => 'fadeIn'
		);

		$sgPopupTheme = array(
			'colorbox1.css',
			'colorbox2.css',
			'colorbox3.css',
			'colorbox4.css',
			'colorbox5.css'
		);

		$sgFbLikeButtons = array(
			'standard' => 'Standard',
			'box_count' => 'Box with count',
			'button_count' => 'Button with count',
			'button' => 'Button'
		);

		$sgTheme = array(
			'flat' => 'flat',
			'classic' => 'classic',
			'minima' => 'minima',
			'plain' => 'plain'
		);

		$sgThemeSize = array(
			'8' => '8',
			'10' => '10',
			'12' => '12',
			'14' => '14',
			'16' => '16',
			'18' => '18',
			'20' => '20',
			'24' => '24'
		);

		$sgSocialCount = array(
			'true' => 'True',
			'false' => 'False',
			'inside' => 'Inside'
		);

		$sgCountdownType = array(
			1 => 'DD:HH:MM:SS',
			2 => 'DD:HH:MM'
		);

		$sgCountdownlang = array(
			'English' => 'English',
			'German' => 'German',
			'Spanish' => 'Spanish',
			'Arabic' => 'Arabic',
			'Italian' => 'Italian',
			'Italian' => 'Italian',
			'Dutch' => 'Dutch',
			'Norwegian' => 'Norwegian',
			'Portuguese' => 'Portuguese',
			'Russian' => 'Russian',
			'Swedish' => 'Swedish',
			'Chinese' => 'Chinese'
		);

		$sgExitIntentSelectOptions = array(
			"perSesion" => "per Session",
			"1" => "per minute",
			"10080" => "per 7 days",
			"43200" => "per month",
			"always" => "always"
		);

		$sgTextAreaResizeOptions = array(
			'both' => 'Both',
			'horizontal' => 'Horizontal',
			'vertical' => 'Vertical',
			'none' => 'None',
			'inherit' => 'Inherit'
		);

		$sgTimeZones =array(
			"Pacific/Midway"=>"(GMT-11:00) Midway",
			"Pacific/Niue"=>"(GMT-11:00) Niue",
			"Pacific/Pago_Pago"=>"(GMT-11:00) Pago Pago",
			"Pacific/Honolulu"=>"(GMT-10:00) Hawaii Time",
			"Pacific/Rarotonga"=>"(GMT-10:00) Rarotonga",
			"Pacific/Tahiti"=>"(GMT-10:00) Tahiti",
			"Pacific/Marquesas"=>"(GMT-09:30) Marquesas",
			"America/Anchorage"=>"(GMT-09:00) Alaska Time",
			"Pacific/Gambier"=>"(GMT-09:00) Gambier",
			"America/Los_Angeles"=>"(GMT-08:00) Pacific Time",
			"America/Tijuana"=>"(GMT-08:00) Pacific Time - Tijuana",
			"America/Vancouver"=>"(GMT-08:00) Pacific Time - Vancouver",
			"America/Whitehorse"=>"(GMT-08:00) Pacific Time - Whitehorse",
			"Pacific/Pitcairn"=>"(GMT-08:00) Pitcairn",
			"America/Dawson_Creek"=>"(GMT-07:00) Mountain Time - Dawson Creek",
			"America/Denver"=>"(GMT-07:00) Mountain Time",
			"America/Edmonton"=>"(GMT-07:00) Mountain Time - Edmonton",
			"America/Hermosillo"=>"(GMT-07:00) Mountain Time - Hermosillo",
			"America/Mazatlan"=>"(GMT-07:00) Mountain Time - Chihuahua, Mazatlan",
			"America/Phoenix"=>"(GMT-07:00) Mountain Time - Arizona",
			"America/Yellowknife"=>"(GMT-07:00) Mountain Time - Yellowknife",
			"America/Belize"=>"(GMT-06:00) Belize",
			"America/Chicago"=>"(GMT-06:00) Central Time",
			"America/Costa_Rica"=>"(GMT-06:00) Costa Rica",
			"America/El_Salvador"=>"(GMT-06:00) El Salvador",
			"America/Guatemala"=>"(GMT-06:00) Guatemala",
			"America/Managua"=>"(GMT-06:00) Managua",
			"America/Mexico_City"=>"(GMT-06:00) Central Time - Mexico City",
			"America/Regina"=>"(GMT-06:00) Central Time - Regina",
			"America/Tegucigalpa"=>"(GMT-06:00) Central Time - Tegucigalpa",
			"America/Winnipeg"=>"(GMT-06:00) Central Time - Winnipeg",
			"Pacific/Easter"=>"(GMT-06:00) Easter Island",
			"Pacific/Galapagos"=>"(GMT-06:00) Galapagos",
			"America/Bogota"=>"(GMT-05:00) Bogota",
			"America/Cayman"=>"(GMT-05:00) Cayman",
			"America/Guayaquil"=>"(GMT-05:00) Guayaquil",
			"America/Havana"=>"(GMT-05:00) Havana",
			"America/Iqaluit"=>"(GMT-05:00) Eastern Time - Iqaluit",
			"America/Jamaica"=>"(GMT-05:00) Jamaica",
			"America/Lima"=>"(GMT-05:00) Lima",
			"America/Montreal"=>"(GMT-05:00) Eastern Time - Montreal",
			"America/Nassau"=>"(GMT-05:00) Nassau",
			"America/New_York"=>"(GMT-05:00) Eastern Time",
			"America/Panama"=>"(GMT-05:00) Panama",
			"America/Port-au-Prince"=>"(GMT-05:00) Port-au-Prince",
			"America/Rio_Branco"=>"(GMT-05:00) Rio Branco",
			"America/Toronto"=>"(GMT-05:00) Eastern Time - Toronto",
			"America/Caracas"=>"(GMT-04:30) Caracas",
			"America/Antigua"=>"(GMT-04:00) Antigua",
			"America/Asuncion"=>"(GMT-04:00) Asuncion",
			"America/Barbados"=>"(GMT-04:00) Barbados",
			"America/Boa_Vista"=>"(GMT-04:00) Boa Vista",
			"America/Campo_Grande"=>"(GMT-04:00) Campo Grande",
			"America/Cuiaba"=>"(GMT-04:00) Cuiaba",
			"America/Curacao"=>"(GMT-04:00) Curacao",
			"America/Grand_Turk"=>"(GMT-04:00) Grand Turk",
			"America/Guyana"=>"(GMT-04:00) Guyana",
			"America/Halifax"=>"(GMT-04:00) Atlantic Time - Halifax",
			"America/La_Paz"=>"(GMT-04:00) La Paz",
			"America/Manaus"=>"(GMT-04:00) Manaus",
			"America/Martinique"=>"(GMT-04:00) Martinique",
			"America/Port_of_Spain"=>"(GMT-04:00) Port of Spain",
			"America/Porto_Velho"=>"(GMT-04:00) Porto Velho",
			"America/Puerto_Rico"=>"(GMT-04:00) Puerto Rico",
			"America/Santiago"=>"(GMT-04:00) Santiago",
			"America/Santo_Domingo"=>"(GMT-04:00) Santo Domingo",
			"America/Thule"=>"(GMT-04:00) Thule",
			"Antarctica/Palmer"=>"(GMT-04:00) Palmer",
			"Atlantic/Bermuda"=>"(GMT-04:00) Bermuda",
			"America/St_Johns"=>"(GMT-03:30) Newfoundland Time - St. Johns",
			"America/Araguaina"=>"(GMT-03:00) Araguaina",
			"America/Argentina/Buenos_Aires"=>"(GMT-03:00) Buenos Aires",
			"America/Bahia"=>"(GMT-03:00) Salvador",
			"America/Belem"=>"(GMT-03:00) Belem",
			"America/Cayenne"=>"(GMT-03:00) Cayenne",
			"America/Fortaleza"=>"(GMT-03:00) Fortaleza",
			"America/Godthab"=>"(GMT-03:00) Godthab",
			"America/Maceio"=>"(GMT-03:00) Maceio",
			"America/Miquelon"=>"(GMT-03:00) Miquelon",
			"America/Montevideo"=>"(GMT-03:00) Montevideo",
			"America/Paramaribo"=>"(GMT-03:00) Paramaribo",
			"America/Recife"=>"(GMT-03:00) Recife",
			"America/Sao_Paulo"=>"(GMT-03:00) Sao Paulo",
			"Antarctica/Rothera"=>"(GMT-03:00) Rothera",
			"Atlantic/Stanley"=>"(GMT-03:00) Stanley",
			"America/Noronha"=>"(GMT-02:00) Noronha",
			"Atlantic/South_Georgia"=>"(GMT-02:00) South Georgia",
			"America/Scoresbysund"=>"(GMT-01:00) Scoresbysund",
			"Atlantic/Azores"=>"(GMT-01:00) Azores",
			"Atlantic/Cape_Verde"=>"(GMT-01:00) Cape Verde",
			"Africa/Abidjan"=>"(GMT+00:00) Abidjan",
			"Africa/Accra"=>"(GMT+00:00) Accra",
			"Africa/Bissau"=>"(GMT+00:00) Bissau",
			"Africa/Casablanca"=>"(GMT+00:00) Casablanca",
			"Africa/El_Aaiun"=>"(GMT+00:00) El Aaiun",
			"Africa/Monrovia"=>"(GMT+00:00) Monrovia",
			"America/Danmarkshavn"=>"(GMT+00:00) Danmarkshavn",
			"Atlantic/Canary"=>"(GMT+00:00) Canary Islands",
			"Atlantic/Faroe"=>"(GMT+00:00) Faeroe",
			"Atlantic/Reykjavik"=>"(GMT+00:00) Reykjavik",
			"Etc/GMT"=>"(GMT+00:00) GMT (no daylight saving)",
			"Europe/Dublin"=>"(GMT+00:00) Dublin",
			"Europe/Lisbon"=>"(GMT+00:00) Lisbon",
			"Europe/London"=>"(GMT+00:00) London",
			"Africa/Algiers"=>"(GMT+01:00) Algiers",
			"Africa/Ceuta"=>"(GMT+01:00) Ceuta",
			"Africa/Lagos"=>"(GMT+01:00) Lagos",
			"Africa/Ndjamena"=>"(GMT+01:00) Ndjamena",
			"Africa/Tunis"=>"(GMT+01:00) Tunis",
			"Africa/Windhoek"=>"(GMT+01:00) Windhoek",
			"Europe/Amsterdam"=>"(GMT+01:00) Amsterdam",
			"Europe/Andorra"=>"(GMT+01:00) Andorra",
			"Europe/Belgrade"=>"(GMT+01:00) Central European Time - Belgrade",
			"Europe/Berlin"=>"(GMT+01:00) Berlin",
			"Europe/Brussels"=>"(GMT+01:00) Brussels",
			"Europe/Budapest"=>"(GMT+01:00) Budapest",
			"Europe/Copenhagen"=>"(GMT+01:00) Copenhagen",
			"Europe/Gibraltar"=>"(GMT+01:00) Gibraltar",
			"Europe/Luxembourg"=>"(GMT+01:00) Luxembourg",
			"Europe/Madrid"=>"(GMT+01:00) Madrid",
			"Europe/Malta"=>"(GMT+01:00) Malta",
			"Europe/Monaco"=>"(GMT+01:00) Monaco",
			"Europe/Oslo"=>"(GMT+01:00) Oslo",
			"Europe/Paris"=>"(GMT+01:00) Paris",
			"Europe/Prague"=>"(GMT+01:00) Central European Time - Prague",
			"Europe/Rome"=>"(GMT+01:00) Rome",
			"Europe/Stockholm"=>"(GMT+01:00) Stockholm",
			"Europe/Tirane"=>"(GMT+01:00) Tirane",
			"Europe/Vienna"=>"(GMT+01:00) Vienna",
			"Europe/Warsaw"=>"(GMT+01:00) Warsaw",
			"Europe/Zurich"=>"(GMT+01:00) Zurich",
			"Africa/Cairo"=>"(GMT+02:00) Cairo",
			"Africa/Johannesburg"=>"(GMT+02:00) Johannesburg",
			"Africa/Maputo"=>"(GMT+02:00) Maputo",
			"Africa/Tripoli"=>"(GMT+02:00) Tripoli",
			"Asia/Amman"=>"(GMT+02:00) Amman",
			"Asia/Beirut"=>"(GMT+02:00) Beirut",
			"Asia/Damascus"=>"(GMT+02:00) Damascus",
			"Asia/Gaza"=>"(GMT+02:00) Gaza",
			"Asia/Jerusalem"=>"(GMT+02:00) Jerusalem",
			"Asia/Nicosia"=>"(GMT+02:00) Nicosia",
			"Europe/Athens"=>"(GMT+02:00) Athens",
			"Europe/Bucharest"=>"(GMT+02:00) Bucharest",
			"Europe/Chisinau"=>"(GMT+02:00) Chisinau",
			"Europe/Helsinki"=>"(GMT+02:00) Helsinki",
			"Europe/Istanbul"=>"(GMT+02:00) Istanbul",
			"Europe/Kaliningrad"=>"(GMT+02:00) Moscow-01 - Kaliningrad",
			"Europe/Kiev"=>"(GMT+02:00) Kiev",
			"Europe/Riga"=>"(GMT+02:00) Riga",
			"Europe/Sofia"=>"(GMT+02:00) Sofia",
			"Europe/Tallinn"=>"(GMT+02:00) Tallinn",
			"Europe/Vilnius"=>"(GMT+02:00) Vilnius",
			"Africa/Addis_Ababa"=>"(GMT+03:00) Addis Ababa",
			"Africa/Asmara"=>"(GMT+03:00) Asmera",
			"Africa/Dar_es_Salaam"=>"(GMT+03:00) Dar es Salaam",
			"Africa/Djibouti"=>"(GMT+03:00) Djibouti",
			"Africa/Kampala"=>"(GMT+03:00) Kampala",
			"Africa/Khartoum"=>"(GMT+03:00) Khartoum",
			"Africa/Mogadishu"=>"(GMT+03:00) Mogadishu",
			"Africa/Nairobi"=>"(GMT+03:00) Nairobi",
			"Antarctica/Syowa"=>"(GMT+03:00) Syowa",
			"Asia/Aden"=>"(GMT+03:00) Aden",
			"Asia/Baghdad"=>"(GMT+03:00) Baghdad",
			"Asia/Bahrain"=>"(GMT+03:00) Bahrain",
			"Asia/Kuwait"=>"(GMT+03:00) Kuwait",
			"Asia/Qatar"=>"(GMT+03:00) Qatar",
			"Asia/Riyadh"=>"(GMT+03:00) Riyadh",
			"Europe/Minsk"=>"(GMT+03:00) Minsk",
			"Europe/Moscow"=>"(GMT+03:00) Moscow+00",
			"Indian/Antananarivo"=>"(GMT+03:00) Antananarivo",
			"Indian/Comoro"=>"(GMT+03:00) Comoro",
			"Indian/Mayotte"=>"(GMT+03:00) Mayotte",
			"Asia/Tehran"=>"(GMT+03:30) Tehran",
			"Asia/Baku"=>"(GMT+04:00) Baku",
			"Asia/Dubai"=>"(GMT+04:00) Dubai",
			"Asia/Muscat"=>"(GMT+04:00) Muscat",
			"Asia/Tbilisi"=>"(GMT+04:00) Tbilisi",
			"Asia/Yerevan"=>"(GMT+04:00) Yerevan",
			"Europe/Samara"=>"(GMT+04:00) Moscow+00 - Samara",
			"Indian/Mahe"=>"(GMT+04:00) Mahe",
			"Indian/Mauritius"=>"(GMT+04:00) Mauritius",
			"Indian/Reunion"=>"(GMT+04:00) Reunion",
			"Asia/Kabul"=>"(GMT+04:30) Kabul",
			"Antarctica/Mawson"=>"(GMT+05:00) Mawson",
			"Asia/Aqtau"=>"(GMT+05:00) Aqtau",
			"Asia/Aqtobe"=>"(GMT+05:00) Aqtobe",
			"Asia/Ashgabat"=>"(GMT+05:00) Ashgabat",
			"Asia/Dushanbe"=>"(GMT+05:00) Dushanbe",
			"Asia/Karachi"=>"(GMT+05:00) Karachi",
			"Asia/Tashkent"=>"(GMT+05:00) Tashkent",
			"Asia/Yekaterinburg"=>"(GMT+05:00) Moscow+02 - Yekaterinburg",
			"Indian/Kerguelen"=>"(GMT+05:00) Kerguelen",
			"Indian/Maldives"=>"(GMT+05:00) Maldives",
			"Asia/Calcutta"=>"(GMT+05:30) India Standard Time",
			"Asia/Colombo"=>"(GMT+05:30) Colombo",
			"Asia/Katmandu"=>"(GMT+05:45) Katmandu",
			"Antarctica/Vostok"=>"(GMT+06:00) Vostok",
			"Asia/Almaty"=>"(GMT+06:00) Almaty",
			"Asia/Bishkek"=>"(GMT+06:00) Bishkek",
			"Asia/Dhaka"=>"(GMT+06:00) Dhaka",
			"Asia/Omsk"=>"(GMT+06:00) Moscow+03 - Omsk, Novosibirsk",
			"Asia/Thimphu"=>"(GMT+06:00) Thimphu",
			"Indian/Chagos"=>"(GMT+06:00) Chagos",
			"Asia/Rangoon"=>"(GMT+06:30) Rangoon",
			"Indian/Cocos"=>"(GMT+06:30) Cocos",
			"Antarctica/Davis"=>"(GMT+07:00) Davis",
			"Asia/Bangkok"=>"(GMT+07:00) Bangkok",
			"Asia/Hovd"=>"(GMT+07:00) Hovd",
			"Asia/Jakarta"=>"(GMT+07:00) Jakarta",
			"Asia/Krasnoyarsk"=>"(GMT+07:00) Moscow+04 - Krasnoyarsk",
			"Asia/Saigon"=>"(GMT+07:00) Hanoi",
			"Indian/Christmas"=>"(GMT+07:00) Christmas",
			"Antarctica/Casey"=>"(GMT+08:00) Casey",
			"Asia/Brunei"=>"(GMT+08:00) Brunei",
			"Asia/Choibalsan"=>"(GMT+08:00) Choibalsan",
			"Asia/Hong_Kong"=>"(GMT+08:00) Hong Kong",
			"Asia/Irkutsk"=>"(GMT+08:00) Moscow+05 - Irkutsk",
			"Asia/Kuala_Lumpur"=>"(GMT+08:00) Kuala Lumpur",
			"Asia/Macau"=>"(GMT+08:00) Macau",
			"Asia/Makassar"=>"(GMT+08:00) Makassar",
			"Asia/Manila"=>"(GMT+08:00) Manila",
			"Asia/Shanghai"=>"(GMT+08:00) China Time - Beijing",
			"Asia/Singapore"=>"(GMT+08:00) Singapore",
			"Asia/Taipei"=>"(GMT+08:00) Taipei",
			"Asia/Ulaanbaatar"=>"(GMT+08:00) Ulaanbaatar",
			"Australia/Perth"=>"(GMT+08:00) Western Time - Perth",
			"Asia/Dili"=>"(GMT+09:00) Dili",
			"Asia/Jayapura"=>"(GMT+09:00) Jayapura",
			"Asia/Pyongyang"=>"(GMT+09:00) Pyongyang",
			"Asia/Seoul"=>"(GMT+09:00) Seoul",
			"Asia/Tokyo"=>"(GMT+09:00) Tokyo",
			"Asia/Yakutsk"=>"(GMT+09:00) Moscow+06 - Yakutsk",
			"Pacific/Palau"=>"(GMT+09:00) Palau",
			"Australia/Adelaide"=>"(GMT+09:30) Central Time - Adelaide",
			"Australia/Darwin"=>"(GMT+09:30) Central Time - Darwin",
			"Antarctica/DumontDUrville"=>"(GMT+10:00) Dumont D'Urville",
			"Asia/Magadan"=>"(GMT+10:00) Moscow+08 - Magadan",
			"Asia/Vladivostok"=>"(GMT+10:00) Moscow+07 - Yuzhno-Sakhalinsk",
			"Australia/Brisbane"=>"(GMT+10:00) Eastern Time - Brisbane",
			"Australia/Hobart"=>"(GMT+10:00) Eastern Time - Hobart",
			"Australia/Sydney"=>"(GMT+10:00) Eastern Time - Melbourne, Sydney",
			"Pacific/Chuuk"=>"(GMT+10:00) Truk",
			"Pacific/Guam"=>"(GMT+10:00) Guam",
			"Pacific/Port_Moresby"=>"(GMT+10:00) Port Moresby",
			"Pacific/Saipan"=>"(GMT+10:00) Saipan",
			"Pacific/Efate"=>"(GMT+11:00) Efate",
			"Pacific/Guadalcanal"=>"(GMT+11:00) Guadalcanal",
			"Pacific/Kosrae"=>"(GMT+11:00) Kosrae",
			"Pacific/Noumea"=>"(GMT+11:00) Noumea",
			"Pacific/Pohnpei"=>"(GMT+11:00) Ponape",
			"Pacific/Norfolk"=>"(GMT+11:30) Norfolk",
			"Asia/Kamchatka"=>"(GMT+12:00) Moscow+08 - Petropavlovsk-Kamchatskiy",
			"Pacific/Auckland"=>"(GMT+12:00) Auckland",
			"Pacific/Fiji"=>"(GMT+12:00) Fiji",
			"Pacific/Funafuti"=>"(GMT+12:00) Funafuti",
			"Pacific/Kwajalein"=>"(GMT+12:00) Kwajalein",
			"Pacific/Majuro"=>"(GMT+12:00) Majuro",
			"Pacific/Nauru"=>"(GMT+12:00) Nauru",
			"Pacific/Tarawa"=>"(GMT+12:00) Tarawa",
			"Pacific/Wake"=>"(GMT+12:00) Wake",
			"Pacific/Wallis"=>"(GMT+12:00) Wallis",
			"Pacific/Apia"=>"(GMT+13:00) Apia",
			"Pacific/Enderbury"=>"(GMT+13:00) Enderbury",
			"Pacific/Fakaofo"=>"(GMT+13:00) Fakaofo",
			"Pacific/Tongatapu"=>"(GMT+13:00) Tongatapu",
			"Pacific/Kiritimati"=>"(GMT+14:00) Kiritimati"
		);

		$countris = '<select id="sameWidthinputs" name="countris" class="selectpicker input-md sg-selectpicker"  data-role="tagsinput">
					<option value="AF">Afghanistan</option>
					<option value="AX">Åland Islands</option>
					<option value="AL">Albania</option>
					<option value="DZ">Algeria</option>
					<option value="AS">American Samoa</option>
					<option value="AD">Andorra</option>
					<option value="AO">Angola</option>
					<option value="AI">Anguilla</option>
					<option value="AQ">Antarctica</option>
					<option value="AG">Antigua and Barbuda</option>
					<option value="AR">Argentina</option>
					<option value="AM">Armenia</option>
					<option value="AW">Aruba</option>
					<option value="AU">Australia</option>
					<option value="AT">Austria</option>
					<option value="AZ">Azerbaijan</option>
					<option value="BS">Bahamas</option>
					<option value="BH">Bahrain</option>
					<option value="BD">Bangladesh</option>
					<option value="BB">Barbados</option>
					<option value="BY">Belarus</option>
					<option value="BE">Belgium</option>
					<option value="BZ">Belize</option>
					<option value="BJ">Benin</option>
					<option value="BM">Bermuda</option>
					<option value="BT">Bhutan</option>
					<option value="BO">Bolivia, Plurinational State of</option>
					<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
					<option value="BA">Bosnia and Herzegovina</option>
					<option value="BW">Botswana</option>
					<option value="BV">Bouvet Island</option>
					<option value="BR">Brazil</option>
					<option value="IO">British Indian Ocean Territory</option>
					<option value="BN">Brunei Darussalam</option>
					<option value="BG">Bulgaria</option>
					<option value="BF">Burkina Faso</option>
					<option value="BI">Burundi</option>
					<option value="KH">Cambodia</option>
					<option value="CM">Cameroon</option>
					<option value="CA">Canada</option>
					<option value="CV">Cape Verde</option>
					<option value="KY">Cayman Islands</option>
					<option value="CF">Central African Republic</option>
					<option value="TD">Chad</option>
					<option value="CL">Chile</option>
					<option value="CN">China</option>
					<option value="CX">Christmas Island</option>
					<option value="CC">Cocos (Keeling) Islands</option>
					<option value="CO">Colombia</option>
					<option value="KM">Comoros</option>
					<option value="CG">Congo</option>
					<option value="CD">Congo, the Democratic Republic of the</option>
					<option value="CK">Cook Islands</option>
					<option value="CR">Costa Rica</option>
					<option value="CI">Côte d\'Ivoire</option>
					<option value="HR">Croatia</option>
					<option value="CU">Cuba</option>
					<option value="CW">Curaçao</option>
					<option value="CY">Cyprus</option>
					<option value="CZ">Czech Republic</option>
					<option value="DK">Denmark</option>
					<option value="DJ">Djibouti</option>
					<option value="DM">Dominica</option>
					<option value="DO">Dominican Republic</option>
					<option value="EC">Ecuador</option>
					<option value="EG">Egypt</option>
					<option value="SV">El Salvador</option>
					<option value="GQ">Equatorial Guinea</option>
					<option value="ER">Eritrea</option>
					<option value="EE">Estonia</option>
					<option value="ET">Ethiopia</option>
					<option value="FK">Falkland Islands (Malvinas)</option>
					<option value="FO">Faroe Islands</option>
					<option value="FJ">Fiji</option>
					<option value="FI">Finland</option>
					<option value="FR">France</option>
					<option value="GF">French Guiana</option>
					<option value="PF">French Polynesia</option>
					<option value="TF">French Southern Territories</option>
					<option value="GA">Gabon</option>
					<option value="GM">Gambia</option>
					<option value="GE">Georgia</option>
					<option value="DE">Germany</option>
					<option value="GH">Ghana</option>
					<option value="GI">Gibraltar</option>
					<option value="GR">Greece</option>
					<option value="GL">Greenland</option>
					<option value="GD">Grenada</option>
					<option value="GP">Guadeloupe</option>
					<option value="GU">Guam</option>
					<option value="GT">Guatemala</option>
					<option value="GG">Guernsey</option>
					<option value="GN">Guinea</option>
					<option value="GW">Guinea-Bissau</option>
					<option value="GY">Guyana</option>
					<option value="HT">Haiti</option>
					<option value="HM">Heard Island and McDonald Islands</option>
					<option value="VA">Holy See (Vatican City State)</option>
					<option value="HN">Honduras</option>
					<option value="HK">Hong Kong</option>
					<option value="HU">Hungary</option>
					<option value="IS">Iceland</option>
					<option value="IN">India</option>
					<option value="ID">Indonesia</option>
					<option value="IR">Iran, Islamic Republic of</option>
					<option value="IQ">Iraq</option>
					<option value="IE">Ireland</option>
					<option value="IM">Isle of Man</option>
					<option value="IL">Israel</option>
					<option value="IT">Italy</option>
					<option value="JM">Jamaica</option>
					<option value="JP">Japan</option>
					<option value="JE">Jersey</option>
					<option value="JO">Jordan</option>
					<option value="KZ">Kazakhstan</option>
					<option value="KE">Kenya</option>
					<option value="KI">Kiribati</option>
					<option value="KP">Korea, Democratic People\'s Republic of</option>
					<option value="KR">Korea, Republic of</option>
					<option value="KW">Kuwait</option>
					<option value="KG">Kyrgyzstan</option>
					<option value="LA">Lao People\'s Democratic Republic</option>
					<option value="LV">Latvia</option>
					<option value="LB">Lebanon</option>
					<option value="LS">Lesotho</option>
					<option value="LR">Liberia</option>
					<option value="LY">Libya</option>
					<option value="LI">Liechtenstein</option>
					<option value="LT">Lithuania</option>
					<option value="LU">Luxembourg</option>
					<option value="MO">Macao</option>
					<option value="MK">Macedonia, the former Yugoslav Republic of</option>
					<option value="MG">Madagascar</option>
					<option value="MW">Malawi</option>
					<option value="MY">Malaysia</option>
					<option value="MV">Maldives</option>
					<option value="ML">Mali</option>
					<option value="MT">Malta</option>
					<option value="MH">Marshall Islands</option>
					<option value="MQ">Martinique</option>
					<option value="MR">Mauritania</option>
					<option value="MU">Mauritius</option>
					<option value="YT">Mayotte</option>
					<option value="MX">Mexico</option>
					<option value="FM">Micronesia, Federated States of</option>
					<option value="MD">Moldova, Republic of</option>
					<option value="MC">Monaco</option>
					<option value="MN">Mongolia</option>
					<option value="ME">Montenegro</option>
					<option value="MS">Montserrat</option>
					<option value="MA">Morocco</option>
					<option value="MZ">Mozambique</option>
					<option value="MM">Myanmar</option>
					<option value="NA">Namibia</option>
					<option value="NR">Nauru</option>
					<option value="NP">Nepal</option>
					<option value="NL">Netherlands</option>
					<option value="NC">New Caledonia</option>
					<option value="NZ">New Zealand</option>
					<option value="NI">Nicaragua</option>
					<option value="NE">Niger</option>
					<option value="NG">Nigeria</option>
					<option value="NU">Niue</option>
					<option value="NF">Norfolk Island</option>
					<option value="MP">Northern Mariana Islands</option>
					<option value="NO">Norway</option>
					<option value="OM">Oman</option>
					<option value="PK">Pakistan</option>
					<option value="PW">Palau</option>
					<option value="PS">Palestinian Territory, Occupied</option>
					<option value="PA">Panama</option>
					<option value="PG">Papua New Guinea</option>
					<option value="PY">Paraguay</option>
					<option value="PE">Peru</option>
					<option value="PH">Philippines</option>
					<option value="PN">Pitcairn</option>
					<option value="PL">Poland</option>
					<option value="PT">Portugal</option>
					<option value="PR">Puerto Rico</option>
					<option value="QA">Qatar</option>
					<option value="RE">Réunion</option>
					<option value="RO">Romania</option>
					<option value="RU">Russian Federation</option>
					<option value="RW">Rwanda</option>
					<option value="BL">Saint Barthélemy</option>
					<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
					<option value="KN">Saint Kitts and Nevis</option>
					<option value="LC">Saint Lucia</option>
					<option value="MF">Saint Martin (French part)</option>
					<option value="PM">Saint Pierre and Miquelon</option>
					<option value="VC">Saint Vincent and the Grenadines</option>
					<option value="WS">Samoa</option>
					<option value="SM">San Marino</option>
					<option value="ST">Sao Tome and Principe</option>
					<option value="SA">Saudi Arabia</option>
					<option value="SN">Senegal</option>
					<option value="RS">Serbia</option>
					<option value="SC">Seychelles</option>
					<option value="SL">Sierra Leone</option>
					<option value="SG">Singapore</option>
					<option value="SX">Sint Maarten (Dutch part)</option>
					<option value="SK">Slovakia</option>
					<option value="SI">Slovenia</option>
					<option value="SB">Solomon Islands</option>
					<option value="SO">Somalia</option>
					<option value="ZA">South Africa</option>
					<option value="GS">South Georgia and the South Sandwich Islands</option>
					<option value="SS">South Sudan</option>
					<option value="ES">Spain</option>
					<option value="LK">Sri Lanka</option>
					<option value="SD">Sudan</option>
					<option value="SR">Suriname</option>
					<option value="SJ">Svalbard and Jan Mayen</option>
					<option value="SZ">Swaziland</option>
					<option value="SE">Sweden</option>
					<option value="CH">Switzerland</option>
					<option value="SY">Syrian Arab Republic</option>
					<option value="TW">Taiwan, Province of China</option>
					<option value="TJ">Tajikistan</option>
					<option value="TZ">Tanzania, United Republic of</option>
					<option value="TH">Thailand</option>
					<option value="TL">Timor-Leste</option>
					<option value="TG">Togo</option>
					<option value="TK">Tokelau</option>
					<option value="TO">Tonga</option>
					<option value="TT">Trinidad and Tobago</option>
					<option value="TN">Tunisia</option>
					<option value="TR">Turkey</option>
					<option value="TM">Turkmenistan</option>
					<option value="TC">Turks and Caicos Islands</option>
					<option value="TV">Tuvalu</option>
					<option value="UG">Uganda</option>
					<option value="UA">Ukraine</option>
					<option value="AE">United Arab Emirates</option>
					<option value="GB">United Kingdom</option>
					<option value="US">United States</option>
					<option value="UM">United States Minor Outlying Islands</option>
					<option value="UY">Uruguay</option>
					<option value="UZ">Uzbekistan</option>
					<option value="VU">Vanuatu</option>
					<option value="VE">Venezuela, Bolivarian Republic of</option>
					<option value="VN">Viet Nam</option>
					<option value="VG">Virgin Islands, British</option>
					<option value="VI">Virgin Islands, U.S.</option>
					<option value="WF">Wallis and Futuna</option>
					<option value="EH">Western Sahara</option>
					<option value="YE">Yemen</option>
					<option value="ZM">Zambia</option>
					<option value="ZW">Zimbabwe</option>
				</select>';

		$dataArray = array(
			'popupValues'=>$sgPopup,
			'defaluts'=>$popupProDefaultValues,
			'radioElements'=>$radioElements,
			'countriesRadio'=>$countriesRadio,
			'radiobuttons'=>$radiobuttons,
			'sgPopupEffects'=>$sgPopupEffects,
			'sgPopupTheme'=>$sgPopupTheme,
			'sgFbLikeButtons'=>$sgFbLikeButtons,
			'sgTheme'=>$sgTheme,
			'sgThemeSize'=>$sgThemeSize,
			'sgSocialCount'=>$sgSocialCount,
			'sgCountdownType'=>$sgCountdownType,
			'sgCountdownlang'=>$sgCountdownlang,
			'sgExitIntentSelectOptions'=>$sgExitIntentSelectOptions,
			'sgTextAreaResizeOptions'=>$sgTextAreaResizeOptions,
			'sgTimeZones'=>$sgTimeZones,
			'popupTitles' => $popupTitles,
			'countris' => $countris
		);
		
		return $dataArray;
	}
}