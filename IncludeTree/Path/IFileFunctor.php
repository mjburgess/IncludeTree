<?php
/**
 * Interface for Walker consumable
 * 
 * @package IncludeTree
 * @subpackage Path
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Path;

interface IFileFunctor {
    public function __invoke($path);
}