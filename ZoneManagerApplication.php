<?php
/**
 * ZoneManager cli methods
 * 
 * call as zone.php *methodname arg1 arg2
 * or inifix,  zone.php arg1 *methodname arg2
 * 
 * 
 * @package \
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */

use IncludeTree\Application\Cli\Entry,
    IncludeTree\Tracer,
    IncludeTree\Render\CliRenderer,
    IncludeTree\TreeQuerier;

class ZoneManagerApplication extends Entry {   
    public function help() {
        echo "\n ===== IncludeTree ZoneManager Application ===== \n";
        
        echo PHP_EOL, '?> First,  inctree #setlocation "path/to/dir"';
        echo PHP_EOL, '?> Then,   inctree #tree $speed  (eg. zone #tree 3) ; Speed ranges from 0.1 to 100';
        echo PHP_EOL;
        echo PHP_EOL, '?> To avoid recalculating the tree use, inctree #savetree then inctree #loadtree';
        
        echo PHP_EOL;
    }
    public function tree($speed = null) {
        $tree = $this->getZoneTree();
        $this->renderTree($tree, $speed);
    }
    
    public function saveTree($speed = null) {
        $tree = $this->getZoneTree();
        
        file_put_contents('Store/zone.incdb', serialize($tree));
        
        $this->renderTree($tree, $speed);
    }
    
    public function loadTree($speed = null) {
        $tree = unserialize(file_get_contents('Store/zone.incdb'));
        $this->renderTree($tree, $speed);
    }
    
    public function setLocation($location) {
        file_put_contents('Store/zone_location', $location);
    }
    
    
    private function getZoneTree() {
        $tracer = new Tracer(file_get_contents('Store/zone_location'));
        return $tracer->buildTree();
    }
    
    private function renderTree($tree, $speed = null) {
        $speed = $speed ?: 4;
        $renderer = new CliRenderer($speed * 1E4);
        $renderer->renderTree($tree);
    }
}