<?php
/**
 * Renders Tree for Cli display by hooking into the generic RecursiveRenderer
 * 
 * @package IncludeTree
 * @subpackage Render
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Render;

use Exception;

class Stop extends Exception {}

class CliRenderer extends RecursiveRenderer {
    private $maxdepth;
    private $delay;
    
    public function __construct($delay = 4E4, $maxdepth = 15) {
        $this->delay = $delay;
        $this->maxdepth = $maxdepth;        
    }
    
    public function hook($method, array $args) {
        if($method == 'displayFile') {
            echo PHP_EOL . str_repeat("\t", $this->depth++) . $args[0]->getName();
        }
        
        if($this->delay > 1E4) {
            usleep($this->delay);
        }
        
        if($this->depth >= $this->maxdepth) {
            throw new Stop();
        }
    }
}