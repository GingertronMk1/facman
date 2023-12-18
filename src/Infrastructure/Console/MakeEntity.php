<?php

declare(strict_types=1);

namespace App\Infrastructure\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'facman:make-entity',
    description: 'Creates a model, repository, and finder for a given class name',
    hidden: false,
)]
final class MakeEntity extends Command
{
    protected function configure()
    {
        $this
            ->addArgument('classname', InputArgument::REQUIRED, 'The name of the model class')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = $input->getArgument('classname');
        // retrieve the argument value using getArgument()
        $output->writeln("Generating `{$className}`");
        return Command::SUCCESS;
    }
    
}
