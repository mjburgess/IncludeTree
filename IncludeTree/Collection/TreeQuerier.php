<?php
/**
 * Wraps recursive Tree model methods, providing an intuitive interface for queries
 * 
 * @package IncludeTree
 * @subpackage Collection
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Collection;

use IncludeTree\Collection\File;

class TreeQuerier {
    private $tree;
    private $file;
    
    public function __construct($treeRoot) {
        $this->tree = $treeRoot;
    }
    
    public function forFile($name) {
        if(empty($this->tree[$name])) {
             $this->file = new File($name);
        } else {       
            $this->file = $this->tree[$name];
        }
        
        return $this;
    }
    
    public function isIncludeOf($file) {
        return $this->tree[$file]->getIncludeTree()->contains($this->file);
    }
    
    public function isIncluding($file) {
        return $this->file->getIncludeTree()->contains(new File($file));
    }
    
    public function includeCount() {
        $linearTree = $this->file->getIncludeTree()->getIncludeSet();
        return count($linearTree);
    }
    
    public function includeLength() {
        return $this->file->getIncludeTree()->length();
    }
    
    public function getFile() {
        return $this->file;
    }
    
    public function getFileTree() {
        return $this->file->getIncludeTree();
    }
    
    public function getMethods() {
        return get_class_methods($this);
    }
}