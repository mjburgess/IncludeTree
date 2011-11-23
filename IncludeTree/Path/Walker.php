<?php
/**
 * Recurses over director structure using regex to select files, 
 *  executes callback on each file match
 * 
 * @package IncludeTree
 * @subpackage /
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Path;

use Closure;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class Walker {
    const Recursive = 1;
    
    /*
     * not implemented, set the iterator manually
     */
    const Linear    = 2;
    
    private $iterator;
        public function getIterator() {
            return $this->iterator;
        }

        public function setIterator($iterator) {
            $this->iterator = $iterator;
        }

    private $regex;
        public function getRegex() {
            return $this->regex;
        }

        public function setRegex($regex) {
            $this->regex = $regex;
        }
    
    private $extensions;
        public function getExtensions() {
            return $this->extensions;
        }

        public function setExtensions(array $extensions) {
            $this->extensions = $extensions;
        }
    
    private $path;
    
    public function __construct($path) {
        $this->path = realpath($path);
        $this->extensions = array('php', 'inc', 'phtml', 'php4', 'php5');
    }
    
    public function walk($functor, $regex = null, $mode = self::Recursive) {
        if($mode == self::Recursive) {
            $this->useRecursiveIterator();
        } 
        
        $result = array();
        foreach($this->iterator as $path => $matches) {
            $result[$path] = $functor($path);
        }
        
        return $result;
    }
    
    private function useRecursiveIterator($regex = null) {
        $regex = $regex ?:  '/^.*\.(?:' . implode('|', $this->extensions) . ')$/i';
        
        $this->iterator = new RecursiveDirectoryIterator($this->path);
        $this->iterator = new RecursiveIteratorIterator($this->iterator, RecursiveIteratorIterator::SELF_FIRST);
        
        if($regex) {
            $this->iterator = new RegexIterator($this->iterator, $regex, RegexIterator::GET_MATCH);
        }
    }
}