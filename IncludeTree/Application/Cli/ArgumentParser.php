<?php
/**
 * Parses Cli arguments, building a Command object
 * 
 * @package \
 * @author Michael Burgess <michael@mjburgess.co.uk>
 */
namespace IncludeTree\Application\Cli;

class ArgumentParser {
    private $arguments;
    
    const CommandSignifier = '+';
    
    public function __construct($argc, $argv) {
        $this->arguments = $argv;
    }
    
    public function getCommand($hasCaller = true) {
        $cmd = new Command($hasCaller ? array_shift($this->arguments) : null);
        
        if(empty($this->arguments)) {
            $cmd->setMethod('help');
        }
        
        foreach($this->arguments as $key => $argument) {
            if(strpos($argument, self::CommandSignifier) !== false) {
                $cmd->setMethod(str_replace(self::CommandSignifier, '', $argument));
                unset($this->arguments[$key]);
                break;
            }
        }
        
        $cmd->setArguments($this->arguments);
        
        return $cmd;
    }
}
