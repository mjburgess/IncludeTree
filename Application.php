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
    IncludeTree\Collection\TreeQuerier,
    IncludeTree\Collection\Tree,
    IncludeTree\Collection\File,
    IncludeTree\Application\Cli\ArgumentParser;

class Application extends Entry {   
    public function help() {
        echo "\n ===== IncludeTree Application ===== \n";
        
        echo PHP_EOL, '?> First,  inctree +setlocation "path/to/dir"';
        echo PHP_EOL, '?> Then,   inctree +tree $speed  (eg. zone #tree 3) ; Speed ranges from 0.1 to 100';
        echo PHP_EOL;
        echo PHP_EOL, '?> To avoid recalculating the tree use, inctree +savetree then inctree +loadtree';
        
        echo PHP_EOL;
    }
    public function tree($speed = null) {
        $tree = $this->getTree();
        $this->renderTree($tree, $speed);
    }
    
    public function saveTree($speed = null) {
        $tree = $this->getTree();
        
        file_put_contents('tree.incdb', serialize($tree));
        
        $this->renderTree($tree, $speed);
    }
    
    public function loadTree($speed = null) {
        $tree = unserialize(file_get_contents('tree.incdb'));
        $this->renderTree($tree, $speed);
    }
    
    public function setLocation($location) {
        file_put_contents('treeable_location', $location);
    }
    
    public function query(/* ... */) {
        $argParser = new ArgumentParser(func_num_args(), func_get_args());
        $command = $argParser->getCommand(false);
        $args    = $command->getArguments();
        
        $tree = $this->getTree();
        $query = new TreeQuerier($tree);

        if(!empty($args[0])) {
            $query->forFile($args[0]);
        }
        
        $this->genericPrint($query->{$command->getMethod()}(!empty($args[1]) ? $args[1] : null));
    }
    
    private function genericPrint($result) {
        echo "\n?> ";
        if($result instanceof File) { 
            printf('File (%s) is located at (%s) and includes (%d) files.', $result->getName(), $result->getPath(), $result->getIncludeTree()->uniqueLength()); 
        } else if ($result instanceof Tree) {
            printf('Tree of length (%d) has (%d) unique includes', $result->length(), $result->uniqueLength());
        } else {
            var_dump($result);
        }
        echo "\n";
    }
    
    private function getTree() {
        if(!file_exists('treeable_location') || !$loc = file_get_contents('treeable_location')) {
            $loc = 'Example';
        }
        
        $tracer = new Tracer($loc);
        return $tracer->buildTree();
    }
    
    private function renderTree($tree, $speed = null) {
        $speed = $speed ?: 2;
        $renderer = new CliRenderer($speed * 1E4);
        $renderer->renderTree($tree);
    }
}