<?php
/**
 * Generic Cli entry point, dispatches to child method using ArugmentParser/Command::getMethod
 * 
 * @package IncludeTree
 * @subpackage Application
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Application\Cli;

class Entry {
    public function __construct($argc, $argv) {
        $argParser = new ArgumentParser($argc, $argv);
        $command = $argParser->getCommand();
        
        if(!method_exists($this, $command->getMethod())) {
            die('Command ('. $command->getMethod() .') Not Found!');
        }
        
        call_user_func_array(array($this, $command->getMethod()), $command->getArguments());
    }
}