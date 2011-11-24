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
        if(empty($this->tree[$file])) {
            return false;
        } else {
            return (bool) $this->tree[$file]->getIncludeTree()->contains($this->file);
        }
    }
    
    public function isIncluding($file) {
        return (bool) $this->file->getIncludeTree()->contains(new File($file));
    }
    
    public function filesIncluding($f) {
        $f = new File($f);
        
        $list = array();
        foreach($this->tree as $file) {
            if($file->getIncludeTree()->contains($f)) {
                $list[] = $file;
            }
        }
        
        return $list;
    }
    
    public function includeCount() {
        return $this->file->getIncludeTree()->uniqueLength();
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