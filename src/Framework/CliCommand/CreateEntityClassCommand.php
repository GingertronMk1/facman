<?php

namespace App\Framework\CliCommand;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

#[AsCommand(
    name: 'app:create-entity-class',
    description: 'Add a short description for your command',
)]
class CreateEntityClassCommand extends Command
{
    private readonly Inflector $inflector;
    private const ENTITY_CLASS_NAME = 'className';
    private const CLASSNAME_PLACEHOLDER = '<classname>';
    public function __construct(
        private readonly Filesystem $fs,
        private readonly Environment $twig,
    )
    {
        parent::__construct();
        $this->inflector = InflectorFactory::create()->build();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(self::ENTITY_CLASS_NAME, InputArgument::REQUIRED, 'The name of the class to create')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument(self::ENTITY_CLASS_NAME);

        $this->fs->mkdir(['src/Application', 'src/Infrastructure', 'src/Framework', 'src/Domain']);

        $files = [];
        foreach($this->getFilesToCreateFlat($arg1) as $file) {
            $files[$this->inflector->tableize($file)] = $this->replaceClassNamePlaceholder($arg1, $file);
        };

        foreach($files as $fileName => $className)
            $fileName = $this->inflector->tableize('templates/framework/clicommand/' . self::class) . $fileName;
            if (!$this->fs->exists($fileName)) {
                $this->fs->dumpFile($fileName, <<<EOF
<?php

declare(strict_types=1);

EOF
);
            }

        return Command::SUCCESS;
    }

    private function replaceClassNamePlaceholder(string $className, string $target): string {
        return str_replace([self::CLASSNAME_PLACEHOLDER, '/'], [$className, '\\'], $target);
    }

    private function getFilesToCreateFlat(): \Generator
    {
        return $this->flattenFilesToCreate($this->getFilesToCreate());
    }

    private function flattenFilesToCreate(array $dirs, ?string $prefix = null): \Generator
    {
        foreach($dirs as $dir => $possibleFile) {
            if (is_string($dir)) {
                if (!is_null($prefix)) {
                    $prefixedDir = "{$prefix}/{$dir}";
                } else {
                    $prefixedDir = $dir;
                }
            } else {
                $prefixedDir = $prefix;
            }
            if (is_string($possibleFile)) {
                yield "{$prefixedDir}/{$possibleFile}";
            } else if (is_array($possibleFile)) {
                yield from $this->flattenFilesToCreate($possibleFile, $prefixedDir);
            }
        }
    }

    private function getFilesToCreate(): array
    {
        return [
            'Domain' => [
                'ValueObject' => [
                    self::CLASSNAME_PLACEHOLDER . "Id",
                ],
                self::CLASSNAME_PLACEHOLDER . "Entity",
                self::CLASSNAME_PLACEHOLDER . "RepositoryInterface",
                self::CLASSNAME_PLACEHOLDER . "RepositoryException"
            ],
            'Application' => [
                'Command' => [
                    "Create" . self::CLASSNAME_PLACEHOLDER . "Command",
                    "Update" . self::CLASSNAME_PLACEHOLDER . "Command"
                ],
                'CommandHandler' => [
                    "Create" . self::CLASSNAME_PLACEHOLDER . "CommandHandler",
                    "Update" . self::CLASSNAME_PLACEHOLDER . "CommandHandler"
                ],
                self::CLASSNAME_PLACEHOLDER . "FinderInterface",
                self::CLASSNAME_PLACEHOLDER . "FinderException",
                self::CLASSNAME_PLACEHOLDER . "Model"
            ],
            'Infrastructure' => [
                "Dbal" . self::CLASSNAME_PLACEHOLDER . "Repository",
                "Dbal" . self::CLASSNAME_PLACEHOLDER . "Finder",
            ],
            'Framework' => [
                'Controller' => [
                    self::CLASSNAME_PLACEHOLDER . "Controller"
                ],
                "Form/" . self::CLASSNAME_PLACEHOLDER . "" => [
                    "Create" . self::CLASSNAME_PLACEHOLDER . "FormType",
                    "Update" . self::CLASSNAME_PLACEHOLDER . "FormType",
                ]
            ]
        ];
    }
}
