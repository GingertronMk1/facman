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

    private string $className = '';
    private bool $dryRun = false;
    private SymfonyStyle $io;

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
        $this->io = new SymfonyStyle($input, $output);
        $this->dryRun = (bool) $input->getOption(self::OPT_DRY_RUN);
        $this->className = $input->getArgument(self::ARG_CLASSNAME);
        $this->io->note($this->dryRun ? 'Dry Run' : 'Not a Dry Run');
        foreach ($this->getPlacesAndThings() as $place => $things) {
            $this->generatePlace($place, $things);
        }

        return self::SUCCESS;
    }

    private function generatePlace(
        string $place,
        array $things
    ): void {
        $place = str_replace(self::CLASSNAME_PLACEHOLDER, $this->className, $place);
        $dirName = $this->kernel->getProjectDir()."/src/{$place}";
        $this->io->section($dirName);
        $nameSpace = 'App\\'.str_replace('/', '\\', $place);
        $this->io->text("Namespace is '{$nameSpace}'");
        if (!$this->dryRun) {
            if (!is_dir($dirName)) {
                $this->io->text("Making {$dirName}");
                mkdir($dirName, recursive: true);
            } else {
                $this->io->text("{$dirName} already exists");
            }
        } else {
            $this->io->text("Not making {$dirName}");
        }
        foreach ($things as $thing => $attrs) {
            $this->generateThing(
                $thing,
                $attrs,
                $dirName,
                $nameSpace
            );
        }
    }

    private function generateThing(
        string $thing,
        array $attrs,
        string $dirName,
        string $nameSpace,
    ): void {
        $thing = str_replace(self::CLASSNAME_PLACEHOLDER, $this->className, $thing);
        $qualifiedFileName = "{$dirName}/{$thing}.php";
        $kind = $attrs['kind'] ?? 'class';
        $content = $this->twig->render(
            'util/make-entity.php.twig',
            [
                'nameSpace' => $nameSpace,
                'className' => $thing,
                'kind' => $kind,
                'comment' => $attrs['comment'] ?? null,
            ]);
        $this->io->text($content);
        if (!$this->dryRun) {
            if (!file_exists($qualifiedFileName)) {
                $this->io->text("Making {$qualifiedFileName}");
                $fp = fopen($qualifiedFileName, 'w');
                fwrite(
                    $fp,
                    $content
                );
            } else {
                $this->io->text("{$qualifiedFileName} already exists");
            }
        } else {
            $this->io->text("Not actually making {$qualifiedFileName}");
        }
    }

    private function getPlacesAndThings(): array
    {
        return [
        'Domain/'.self::CLASSNAME_PLACEHOLDER => [
            self::CLASSNAME_PLACEHOLDER.'Entity' => [
            ],
            self::CLASSNAME_PLACEHOLDER.'FinderInterface' => [
                'kind' => 'interface',
            ],
        ],
        'Application/'.self::CLASSNAME_PLACEHOLDER => [
            self::CLASSNAME_PLACEHOLDER.'Model' => [
            ],
            self::CLASSNAME_PLACEHOLDER.'RepositoryInterface' => [
                'kind' => 'interface',
            ],
            'Create'.self::CLASSNAME_PLACEHOLDER.'CommandHandler' => [],
            'Create'.self::CLASSNAME_PLACEHOLDER.'Command' => [],
            'Update'.self::CLASSNAME_PLACEHOLDER.'CommandHandler' => [],
            'Update'.self::CLASSNAME_PLACEHOLDER.'Command' => [],
        ],
        'Infrastructure/'.self::CLASSNAME_PLACEHOLDER => [
            'Dbal'.self::CLASSNAME_PLACEHOLDER.'Repository' => [
            ],
            'Dbal'.self::CLASSNAME_PLACEHOLDER.'Finder' => [
            ],
        ],
        'Framework/Controller' => [
            self::CLASSNAME_PLACEHOLDER.'Controller' => [],
        ],
        'Framework/Form' => [
            self::CLASSNAME_PLACEHOLDER.'Type' => [],
        ],
    ];
    }
}
