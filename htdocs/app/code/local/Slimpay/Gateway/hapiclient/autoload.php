<?php
namespace HapiClient;

require 'vendor/autoload.php';

spl_autoload_register( function ($className) {
	if (strpos($className, 'HapiClient\\') === false)
		return;
	
	$className = str_replace('HapiClient\\', '', ltrim($className, '\\'));
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
 
    require_once __DIR__ . '/' . $fileName;
} );

?>