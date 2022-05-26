<?php
namespace SynoDlsPluginMaker\Builder;

use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author jackisback
 *        
 */
class PluginBuilder
{
    /** @var OutputInterface */
    private $output;
    /**
     */
    public function __construct(OutputInterface $output)
    {}

    public function build() {
        $this->output->writeln("building...");
    }
    
}

