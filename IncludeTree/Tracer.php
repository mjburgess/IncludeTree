<?php
/**
 * Combination point for Walker and RegexStrategy, 
 *  builds the Include Tree ab initio
 * 
 * @package IncludeTree
 * @subpackage /
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree;

use IncludeTree\Path\Walker;
use IncludeTree\Parser\RegexStrategy;
use IncludeTree\Collection\File;
use IncludeTree\Collection\Tree;

class Tracer {
    private $path;
    
    public function __construct($path) {
        $this->path = $path;
        
        $delimChars = '\'"';
        $comment    = '(//|/\*)?';
        $construct  = '(?:require_once|include_once|require|include)';
        $argument   = "(?:\(|[$delimChars])([^;]+)";
        
        $this->pattern = "%^\s*$comment\s*$construct\s*$argument\s*;%m";
    }
    
    public function traceIncludes() {        
        $pathWalker  = new Walker($this->path);
        
        $regexParser = new RegexStrategy($this->pattern, function ($path, $matches) { 
            $result = array();
            list($lines, $comments, $args) = $matches; 
            
            for($i = 0, $len = count($args); $i < $len; $i++) {
                if(!empty($args[$i]) && empty($comments[$i])) {
                    $localMatch = array();
                    preg_match('%[^\'"/\s]+\.(?:php|inc)%', $args[$i], $localMatch);
                    
                    if(!empty($localMatch[0])) {
                        $result[] = $localMatch[0];
                    }
                }
            }
            return $result;
        });
        
        $matches = array_filter($pathWalker->walk($regexParser), 'count');
        
        return $matches;
    }
    
    public function buildTree() {
        $incmap = $this->traceIncludes();
        $tree   = array();
        
        foreach($incmap as $key => $value) {
            $tree[$name = basename($key)] = new File($name, $key, new Tree($value));
        }

        foreach($tree as $filename => $file) {
            $file->getIncludeTree()->build($tree);
        }
        
        return $tree;
    }
}