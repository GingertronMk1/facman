<?php

namespace App\Framework\CliCommand;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:create-entity',
    description: 'Add a short description for your command',
)]
class CreateEntityCommand extends Command
{
    private const CLASSNAME_PLACEHOLDER = 'ClassName';
    private readonly Inflector $inflector;

    public function __construct(
        private readonly Filesystem $filesystem,
    )
    {
        parent::__construct();
        $this->inflector = InflectorFactory::create()->build();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $baseClassName = $input->getArgument('arg1');

        $twigPrefix = str_replace('\\', '/', $this->inflector->tableize(self::class));
        foreach($this->getClassNames() as $classNameRaw) {
            $className = 'App\\' . str_replace(self::CLASSNAME_PLACEHOLDER, $baseClassName, $classNameRaw);
            $twigFileNameForTwigRender = $twigPrefix . '/' . $this->inflector->tableize($classNameRaw) . '.php.twig';
            $twigFileNameForFileSystem = "templates/{$twigFileNameForTwigRender}";

            if (!$this->filesystem->exists($twigFileNameForFileSystem)) {
                $this->filesystem->dumpFile($twigFileNameForFileSystem, "<?php\n\ndeclare(strict_types=1);");
            }

            $classFileName = str_replace(['\\', 'App/'], ['/', 'src/'], $className) . '.php';

            $io->writeln($className);
            $io->writeln($classFileName);
            $io->writeln($twigFileNameForFileSystem);


        }

        return Command::SUCCESS;
    }

    private function getClassNames(): array
    {
        return [
            'Domain\\' . self::CLASSNAME_PLACEHOLDER . '\\' . self::CLASSNAME_PLACEHOLDER . 'Entity',
            'Domain\\' . self::CLASSNAME_PLACEHOLDER . '\\' . self::CLASSNAME_PLACEHOLDER . 'RepositoryInterface',
            'Domain\\' . self::CLASSNAME_PLACEHOLDER . '\\' . self::CLASSNAME_PLACEHOLDER . 'RepositoryException',
            'Domain\\' . self::CLASSNAME_PLACEHOLDER . '\\ValueObject\\' . self::CLASSNAME_PLACEHOLDER . 'Id',
            'Application\\' . self::CLASSNAME_PLACEHOLDER . '\\Command\\Create' . self::CLASSNAME_PLACEHOLDER . 'Command',
            'Application\\' . self::CLASSNAME_PLACEHOLDER . '\\Command\\Update' . self::CLASSNAME_PLACEHOLDER . 'Command',
            'Application\\' . self::CLASSNAME_PLACEHOLDER . '\\CommandHandler\\Create' . self::CLASSNAME_PLACEHOLDER . 'CommandHandler',
            'Application\\' . self::CLASSNAME_PLACEHOLDER . '\\CommandHandler\\Update' . self::CLASSNAME_PLACEHOLDER . 'CommandHandler',
            'Application\\' . self::CLASSNAME_PLACEHOLDER . '\\' . self::CLASSNAME_PLACEHOLDER . 'FinderInterface',
            'Application\\' . self::CLASSNAME_PLACEHOLDER . '\\' . self::CLASSNAME_PLACEHOLDER . 'FinderException',
            'Application\\' . self::CLASSNAME_PLACEHOLDER . '\\' . self::CLASSNAME_PLACEHOLDER . 'Model',
            'Infrastructure\\' . self::CLASSNAME_PLACEHOLDER . '\\Dbal' . self::CLASSNAME_PLACEHOLDER . 'Finder',
            'Infrastructure\\' . self::CLASSNAME_PLACEHOLDER . '\\Dbal' . self::CLASSNAME_PLACEHOLDER . 'Repository',
            'Framework\\Controller\\' . self::CLASSNAME_PLACEHOLDER . 'Controller',
            'Framework\\Form\\' . self::CLASSNAME_PLACEHOLDER . '\\Create' . self::CLASSNAME_PLACEHOLDER . 'Form',
            'Framework\\Form\\' . self::CLASSNAME_PLACEHOLDER . '\\Update' . self::CLASSNAME_PLACEHOLDER . 'Form',

        ];
    }
}
