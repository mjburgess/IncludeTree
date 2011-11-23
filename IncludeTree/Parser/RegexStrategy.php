<?php
/**
 * RegexStrategy is a functor (invokable object) for Path\Walker
 *  Produces an array of matches filtered by a reduction function passed to the constructor
 * 
 * @package IncludeTree
 * @subpackage Parser
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Parser;

use Closure;
use Exception;
use IncludeTree\Path\IFileFunctor;

class RegexStrategy implements IFileFunctor {
    private $pattern;
    private $reduce; 
    
    private $maxFileSize;
        public function getMaxFileSize() {
            return $this->maxFileSize;
        }

        public function setMaxFileSize($maxFileSize) {
            $this->maxFileSize = $maxFileSize;
        }

    public function __construct($pattern, Closure $reduce = null) {
        $this->pattern = $pattern;
        $this->reduce  = $reduce;
        $this->maxFileSize = 5E6;
    }
    
    public function __invoke($path) {
        if(filesize($path) > $this->maxFileSize) {
            return;
        }
        
        $filestring = file_get_contents($path);
        $matches    = array();
        
        preg_match_all($this->pattern, $filestring, $matches);
   
        if($this->reduce) {
            $reduce = $this->reduce;
            $result = $reduce($path, $matches);
        } else {
            $result = $matches;
        }
        
        return $result;
    }
    
}