<?php
/**
 * Recurses over tree providing hook-points for generic display
 * 
 * @package IncludeTree
 * @subpackage Render
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Render;

use IncludeTree\Collection\File,
    IncludeTree\Collection\Tree;

use Exception;

abstract class RecursiveRenderer {
    protected $depth;
    protected $treesRendered = array();
    
    public function renderTree($tree) {   
        $this->hook(__FUNCTION__, func_get_args());
        
        foreach($tree as $file) {            
            try { 
                $this->displayFile($file); 
            } catch(Stop $s) { 
                $this->depth = 0;
                continue; 
            } 
            
            $this->depth = 0;     
            $this->treesRendered = array();
        }
    }
    
    public function displayTree(Tree $tree) {
        $this->hook(__FUNCTION__, func_get_args());
        
        if($tree->isEmpty() || in_array(spl_object_hash($tree), $this->treesRendered)) {
            return;
        } else {
            $this->treesRendered[] = spl_object_hash($tree);
            $this->displayFileArray($tree->getFiles());
        }
    }
    
    public function displayFile(File $file) {
        $this->hook(__FUNCTION__, func_get_args());
        
        if($file->isKnown()) {
            $this->displayTree($file->getIncludeTree());
        }
    }
    
    public function displayFileArray(array $files) {
        $this->hook(__FUNCTION__, func_get_args());
        
        $depth = $this->depth;
        foreach($files as $file) {
            $this->displayFile($file);
            $this->depth = $depth;
        }
    }
    
    abstract function hook($method, array $arguments);
}