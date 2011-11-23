<?php
/**
 * Command Model for ArgumentParser, consumed by Entry
 * 
 * @package IncludeTree
 * @subpackage Application\Cli
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Application\Cli;

class Command {
    private $caller;
        public function getCaller() {
            return $this->caller;
        }

        public function setCaller($caller) {
            $this->caller = $caller;
        }

    private $method;
        public function getMethod() {
            return $this->method;
        }

        public function setMethod($method) {
            $this->method = $method;
        }
        
    private $arguments;
        public function getArguments() {
            return $this->arguments;
        }

        public function setArguments($arguments) {
            $this->arguments = $arguments;
        }
        
    function __construct($caller, $method = null, array $arguments = array()) {
        $this->caller = $caller;
        $this->method = $method;
        $this->arguments = $arguments;
    }
}