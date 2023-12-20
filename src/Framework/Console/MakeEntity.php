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
use Twig\Environment;

#[AsCommand(
    name: 'app:make-entity',
    description: 'Creates a new entity',
)]
final class MakeEntity extends Command
{
    private const ARG_CLASSNAME = 'className';
    private const OPT_DRY_RUN = 'dry-run';

    private const CLASSNAME_PLACEHOLDER = '<className>';

    private const PLACES_AND_THINGS = [
        'Domain/'.self::CLASSNAME_PLACEHOLDER => [
            self::CLASSNAME_PLACEHOLDER.'Entity' => [
                'kind' => 'class',
            ],
            self::CLASSNAME_PLACEHOLDER.'FinderInterface' => [
                'kind' => 'interface',
            ],
        ],
        'Application/'.self::CLASSNAME_PLACEHOLDER => [
            self::CLASSNAME_PLACEHOLDER.'Model' => [
                'kind' => 'class',
            ],
            self::CLASSNAME_PLACEHOLDER.'RepositoryInterface' => [
                'kind' => 'interface',
            ],
        ],
        'Infrastructure/'.self::CLASSNAME_PLACEHOLDER => [
            'Dbal'.self::CLASSNAME_PLACEHOLDER.'Repository' => [
                'kind' => 'class',
            ],
            'Dbal'.self::CLASSNAME_PLACEHOLDER.'Finder' => [
                'kind' => 'class',
            ],
        ],
        'Framework/Controller' => [
            self::CLASSNAME_PLACEHOLDER.'Controller' => [
                'kind' => 'class',
            ],
        ],
    ];

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly Environment $twig
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                self::ARG_CLASSNAME,
                InputArgument::REQUIRED,
                'The name of the class'
            )
            ->addOption(
                self::OPT_DRY_RUN,
                'd',
                InputOption::VALUE_NONE,
                'Add to not actually write any files'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $className = $input->getArgument(self::ARG_CLASSNAME);
        $dryRun = (bool) $input->getOption(self::OPT_DRY_RUN);
        $io->note($dryRun ? 'Dry Run' : 'Not a Dry Run');
        foreach (self::PLACES_AND_THINGS as $place => $things) {
            $place = str_replace(self::CLASSNAME_PLACEHOLDER, $className, $place);
            $dirName = $this->kernel->getProjectDir()."/src/{$place}";
            $io->section($dirName);
            $nameSpace = 'App\\'.str_replace('/', '\\', $place);
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
            foreach ($things as $thing => $attrs) {
                $thing = str_replace(self::CLASSNAME_PLACEHOLDER, $className, $thing);
                $qualifiedFileName = "{$dirName}/{$thing}.php";
                $kind = $attrs['kind'] ?? 'class';
                $content = $this->twig->render(
                    'util/make-entity.php.twig',
                    [
                        'nameSpace' => $nameSpace,
                        'className' => $thing,
                        'kind' => $kind,
                    ]);
                $io->text($content);
                if (!$dryRun) {
                    $io->text("Making {$qualifiedFileName}");
                    touch($qualifiedFileName);
                    $fp = fopen($qualifiedFileName, 'w');
                    fwrite(
                        $fp,
                        $content
                    );
                } else {
                    $io->text("Not actually making {$qualifiedFileName}");
                }
            }
        }

        return self::SUCCESS;
    }
}
