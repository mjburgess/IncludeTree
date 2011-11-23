<?php
/**
 * File Model, leaves in the Include Tree
 * 
 * @package IncludeTree
 * @subpackage Collection
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Collection;

class File {
    private $name;
        public function getName() {
            return $this->name;
        }

        public function setName($name) {
            $this->name = $name;
        }
    
    private $path;
        public function getPath() {
            return $this->path;
        }

        public function setPath($path) {
            $this->path = $path;
        }

    private $includeTree;
        public function getIncludeTree() {
            return $this->includeTree;
        }

        public function setIncludeTree($includeTree) {
            $this->includeTree = $includeTree;
        }
    
    public function __construct($name, $path = null, Tree $includeTree = null) {
        $this->name = $name;
        $this->path = $path;
        $this->includeTree = $includeTree ?: new Tree();
    }
    
    public function isKnown() {
        return !empty($this->path) && !$this->includeTree->isEmpty();
    }
    
    public function __toString() {
        return $this->name;
    }
}