<?php
/**
 * Entry Point for IncludeTree Application
 * 
 * @package \
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
ini_set('display_errors', 'on');
error_reporting(E_ALL);

spl_autoload_register(function ($classname) {
    require "$classname.php";
});

$app = new Application($argc, $argv);