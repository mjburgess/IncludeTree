<?php
/**
 * Tree Model with recursive structural analysis methods
 * 
 * @package IncludeTree
 * @subpackage Collection
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Collection;

class Tree {
    private $files; 
        public function getFiles() {
            return $this->files;
        }

        public function setFiles($files) {
            $this->files = $files;
        }
        public function addFile(File $file) {
            $this->files[] = $file;
        }
        public function isEmpty() {
            $this->files = array_filter($this->files, 'count');
            return empty($this->files);
        }
        public function has(File $f) {
            foreach($this->files as $file) {
                if($file->getName() == $f->getName()) {
                    return true;
                }
            }
            
            return false;
        }
        
    public function __construct(array $files = array()) {
        $this->files = $files;
    }
    
    public function build(array $initialMap) {
        foreach($this->files as $key => $file) {
            $this->files[$key] = !empty($initialMap[$file]) ? $initialMap[$file] : new File($file);
        }
    }
    
    public function contains(File $f) {
        if($this->isEmpty()) {
            return false;
        }
        
        if($this->has($f)) {
            return true;
        } else {
            foreach($this->files as $file) {
                if($file->getIncludeTree()->contains($f)) {
                    return true;
                }
            }
        }
    }
    
    public function length() {
        $length = 0;
        
        if(!$this->isEmpty()) {            
            $length += count($this->files);
            foreach($this->files as $file) {
                $length += $file->getIncludeTree()->length();
            }
        }
        
        return $length;
    }
    
    public function getLinear() {
        $linear = array();
        
        if(!$this->isEmpty()) {
            $linear = $this->files;
            foreach($this->files as $file) {
                $linear = array_merge($linear, $file->getIncludeTree()->getLinear());
            }
        }
        return $linear;
    }
    
    public function getIncludeSet() {
        return array_unique($this->getLinear());
    }
}