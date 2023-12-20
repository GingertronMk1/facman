<?php

declare(strict_types=1);

namespace App\Framework\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:make-entity',
    description: 'Creates a new entity',
)]
final class MakeEntity extends Command
{
    private const CLASSNAME_PLACEHOLDER = '<className>';
    private const PLACES_AND_THINGS = [
        'Domain/'.self::CLASSNAME_PLACEHOLDER => [
            self::CLASSNAME_PLACEHOLDER.'Entity',
            self::CLASSNAME_PLACEHOLDER.'FinderInterface'
        ],
        'Application/'.self::CLASSNAME_PLACEHOLDER => [
            self::CLASSNAME_PLACEHOLDER.'Model',
            self::CLASSNAME_PLACEHOLDER.'RepositoryInterface'
        ],
        'Infrastructure/'.self::CLASSNAME_PLACEHOLDER => [
            self::CLASSNAME_PLACEHOLDER.'Repository',
            self::CLASSNAME_PLACEHOLDER.'Finder',
        ],
        'Framework/Controller' => [self::CLASSNAME_PLACEHOLDER.'Controller']
    ];

    public function __construct(private readonly KernelInterface $kernel)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // ...
            ->addArgument('className', InputArgument::REQUIRED, 'The name of the class')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = $input->getArgument('className');
        foreach(self::PLACES_AND_THINGS as $place => $things) {
            $place = str_replace(self::CLASSNAME_PLACEHOLDER, $className, $place);
            $dirName = $this->kernel->getProjectDir() . "/src/{$place}";
            if (!is_dir($dirName)) {
                mkdir($dirName, recursive: true);
            }
            foreach($things as $thing) {
                $thing = str_replace(self::CLASSNAME_PLACEHOLDER, $className, $thing);
                $qualifiedFileName = "{$dirName}/{$thing}.php";
                $output->writeln($qualifiedFileName);
                touch($qualifiedFileName);
            }
        }
        return self::SUCCESS;
    }
}
