<?php
/**
 * Example Cli application, uses files in Example/Sample to parse for includes
 * 
 * @package IncludeTree
 * @subpackage Application
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Application\Cli;

use IncludeTree\Tracer;
use IncludeTree\Collection\TreeQuerier;
use IncludeTree\Render\CliRenderer;

class Example {
    
    public function calcExample() {
        $tracer = new Tracer('Example');
        $tree = $tracer->buildTree();
        $renderer = new CliRenderer();
        $renderer->renderTree($tree);
    }
    
    public function queryTest() {
        $tracer = new Tracer('Example');
        $tree = $tracer->buildTree();
        
        $query = new TreeQuerier($tree);

        echo "\n\nQueries!";
        echo "\n?> F2.php includes ",               $query->forFile('F2.php')->includeCount(), " files";
        echo "\n?> Does F2.php include F3.php? ",   $query->forFile('F2.php')->isIncluding('F3.php') ? 'Yes' : 'No';
        echo "\n?> Is F3.php included by F2.php? ", $query->forFile('F2.php')->isIncludeOf('F3.php') ? 'Yes' : 'No';
        echo "\n?> Is F1.php included by F3.php? ", $query->forFile('F1.php')->isIncludeOf('F3.php') ? 'Yes' : 'No';
    }
}