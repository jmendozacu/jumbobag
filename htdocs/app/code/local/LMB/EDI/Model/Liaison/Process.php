<?php
interface LMB_EDI_Model_Liaison_Process {

    static function loadNext();

    function exec();

    function estExecutable();

    static function getProcess();

    function remove();
}

?>
