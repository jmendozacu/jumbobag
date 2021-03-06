<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 09/06/17
 * Time: 00:00
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_Bundle_DataBundle
{
    /**
     * @var string
     */
    protected $path = 'ICUDATA';

    /**
     * @var ResourceBundle[][]
     */
    protected static $bundles = array();

    /**
     * Get resource bundle for the locale
     *
     * @param string $locale
     * @return ResourceBundle
     */
    public function get($locale)
    {
        $locale = $this->cleanLocale($locale);
        $class = get_class($this);
        if (!isset(static::$bundles[$class][$locale])) {
            $bundle = $this->createResourceBundle($locale, $this->path);
            if (!$bundle && $this->path != 'ICUDATA') {
                $bundle = $this->createResourceBundle($locale, 'ICUDATA');
            }
            static::$bundles[$class][$locale] = $bundle;
        }
        return static::$bundles[$class][$locale];
    }

    /**
     * @param string $locale
     * @param string $path
     * @return null|ResourceBundle
     */
    protected function createResourceBundle($locale, $path)
    {
        try {
            $bundle = new ResourceBundle($locale, $path);
        } catch (Exception $e) {
            // HHVM compatibility: constructor throws on invalid resource
            $bundle = null;
        }
        return $bundle;
    }

    /**
     * Clean locale leaving only language and script
     *
     * @param string $locale
     * @return string
     */
    protected function cleanLocale($locale)
    {
        $localeParts = Locale::parseLocale($locale);
        $cleanLocaleParts = array();
        if (isset($localeParts['language'])) {
            $cleanLocaleParts['language'] = $localeParts['language'];
        }
        if (isset($localeParts['script'])) {
            $cleanLocaleParts['script'] = $localeParts['script'];
        }
        return Locale::composeLocale($cleanLocaleParts);
    }
}