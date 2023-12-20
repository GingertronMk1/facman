<?php

declare(strict_types=1);

namespace App\Framework\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
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
            self::CLASSNAME_PLACEHOLDER.'FinderInterface',
        ],
        'Application/'.self::CLASSNAME_PLACEHOLDER => [
            self::CLASSNAME_PLACEHOLDER.'Model',
            self::CLASSNAME_PLACEHOLDER.'RepositoryInterface',
        ],
        'Infrastructure/'.self::CLASSNAME_PLACEHOLDER => [
            self::CLASSNAME_PLACEHOLDER.'Repository',
            self::CLASSNAME_PLACEHOLDER.'Finder',
        ],
        'Framework/Controller' => [self::CLASSNAME_PLACEHOLDER.'Controller'],
    ];

    public function __construct(private readonly KernelInterface $kernel)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('className', InputArgument::REQUIRED, 'The name of the class')
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'Add to not actually write any files'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $className = $input->getArgument('className');
        $dryRun = (bool) $input->getOption('dry-run');
        echo $dryRun ? 'dry run' : 'not dry run';
        foreach (self::PLACES_AND_THINGS as $place => $things) {
            $place = str_replace(self::CLASSNAME_PLACEHOLDER, $className, $place);
            $dirName = $this->kernel->getProjectDir()."/src/{$place}";
            $io->section($dirName);
            $nameSpace = 'App\\' . str_replace('/', '\\', $place);
            $io->text("Namespace is '{$nameSpace}'");
            if (!$dryRun) {
                if (!is_dir($dirName)) {
                    $io->text("Making {$dirName}");
                    mkdir($dirName, recursive: true);
                } else {
                    $io->text("{$dirName} already exists");
                }
            } else {
                $io->text("Not making {$dirName}");
            }
            foreach ($things as $thing) {
                $thing = str_replace(self::CLASSNAME_PLACEHOLDER, $className, $thing);
                $qualifiedFileName = "{$dirName}/{$thing}.php";
                if (!$dryRun) {
                    $io->text("Making {$qualifiedFileName}");
                    touch($qualifiedFileName);
                } else {
                    $io->text("Not actually making {$qualifiedFileName}");
                }
            }
        }

        return self::SUCCESS;
    }
}
