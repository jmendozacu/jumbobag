<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 09/06/17
 * Time: 14:40
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_Model_System_Config_Source_AvailableLocales
{
    /**
     * Allowed countries
     */
    protected $allowedLocales = [
        'DE', 'EN', 'ES', 'FR', 'IT', 'JA', 'NL', 'PT', 'SV',
    ];


    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return $this->getOptionLocales();
    }

    /**
     * Retrieve the allowed locales
     *
     * @return array
     */
    public function getAllowedLocales()
    {
        return $this->allowedLocales;
    }

    /**
     * @return array
     */
    protected function getOptionLocales()
    {
        $locales = ResourceBundle::getLocales('') ?: [];
        $languages = (new Blackbird_Monetico_Bundle_LanguageBundle())->get('')['Languages'];

        $options = [];
        $processed = [];

        foreach ($locales as $locale) {
            $langValue = strtoupper(substr($locale, 0, 2));

            if (!in_array($langValue, $this->getAllowedLocales()) || in_array($langValue, $processed)) {
                continue;
            }
            $language = Locale::getPrimaryLanguage($locale);
            if (!$languages[$language]) {
                continue;
            }

            $options[] = [
                'value' => $langValue,
                'label' => ucwords(Locale::getDisplayLanguage($locale, $locale)) . ' / ' . $languages[$language]
            ];

            $processed[] = $langValue;
        }

        return $this->_sortOptionArray($options);
    }

    /**
     * @param array $option
     * @return array
     */
    protected function _sortOptionArray($option)
    {
        $data = [];
        foreach ($option as $item) {
            $data[$item['value']] = $item['label'];
        }
        asort($data);
        $option = [];
        foreach ($data as $key => $label) {
            $option[] = ['value' => $key, 'label' => $label];
        }
        return $option;
    }
}