<?php

namespace App\Framework\CliCommand;

use App\Domain\Common\ValueObject\AbstractUuidId;
use Doctrine\DBAL\Connection;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Twig\Environment;

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
        private readonly Environment $twig,
    ) {
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

        foreach ($this->getClassNames() as $classNameRaw => $properties) {
            $className = str_replace(self::CLASSNAME_PLACEHOLDER, $baseClassName, $classNameRaw);

            $classFileName = str_replace(['\\', 'App/'], ['/', 'src/'], $className) . '.php';
            $fileMarkup = $this->getMarkupForFile($className, $properties, $baseClassName);

            $io->writeln($classFileName);
            $io->writeln($fileMarkup);

            try {
                $this->filesystem->dumpFile(
                    $classFileName, $fileMarkup);
            } catch (\Throwable $e) {
                $io->error($e->getMessage());
            }
        }

        return Command::SUCCESS;
    }

    private function getBaseNameAndNameSpace(string $className): array
    {

        $lastBackslash = strrpos($className, '\\');
        return [
            'nameSpace' => substr($className, 0, $lastBackslash),
            'classBaseName' => substr($className, $lastBackslash + 1),
        ];
    }

    private function getMarkupForFile(string $className, array $properties, string $entityName): string
    {
        $markup = ['<?php','','declare(strict_types=1);',''];
        ['nameSpace' => $nameSpace, 'classBaseName' => $className] = $this->getBaseNameAndNameSpace($className);

        $markup[] = "namespace {$nameSpace};";
        $markup[] = '';
        $kind = $properties['kind'] ?? 'class';
        $idLine = "{$kind} $className";

        if ($extends = $properties['extends'] ?? false) {
            $idLine .= " extends \\{$extends}";
        }

        if ($implements = $properties['implements'] ?? false) {
            $implements = implode(', ', array_map(fn (string $c) => "\\{$c}", $implements));
            $idLine .= " implements {$implements}";
        }

        $markup[] = $idLine;
        $markup[] = '{';
        $markup[] = 'public function __construct(';
        foreach ($properties['attributes'] ?? [] as $class => $type) {
            ['classBaseName' => $attrClassName] = $this->getBaseNameAndNameSpace($class);
            $markup[] = $type . ' ' . $class . ' $' . $this->inflector->camelize($attrClassName) . ',';
        }
        $markup[] = ') {}';
        $markup[] = '}';

        $markup = implode(PHP_EOL, $markup) . PHP_EOL;

        return str_replace(self::CLASSNAME_PLACEHOLDER, $entityName, $markup);
    }

    private function getClassNames(): array
    {
        return [
            'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'Entity' => [
                'attributes' => [
                    'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => 'public'
                ]
            ],
            'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryInterface' => [
                'kind' => 'interface',
            ],
            'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryException' => [
                'kind' => 'final class',
                'extends' => \Exception::class
            ],
            'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => [
                'kind' => 'readonly class',
                'extends' => AbstractUuidId::class
            ],
            'App\\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\Command\\Create'.self::CLASSNAME_PLACEHOLDER.'Command' => [
                'attributes' => [
                    'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => 'public'
                ]
            ],
            'App\\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\Command\\Update'.self::CLASSNAME_PLACEHOLDER.'Command' => [
                'attributes' => [
                    'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => 'public'
                ]
            ],
            'App\\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\CommandHandler\\Create'.self::CLASSNAME_PLACEHOLDER.'CommandHandler' => [
                'attributes' => [
                    'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryInterface' => 'private'
                ]
            ],
            'App\\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\CommandHandler\\Update'.self::CLASSNAME_PLACEHOLDER.'CommandHandler' => [
                'attributes' => [
                    'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryInterface' => 'private'
                ]
            ],
            'App\\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'FinderInterface' => [
                'kind' => 'interface'
            ],
            'App\\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'FinderException' =>[
                'kind' => 'final class',
                'extends' => \Exception::class
            ],
            'App\\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'Model' =>[
                'kind' => 'readonly class',
                'attributes' => [
                    'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => 'public'
                ]
            ],
            'App\\Infrastructure\\'.self::CLASSNAME_PLACEHOLDER.'\\Dbal'.self::CLASSNAME_PLACEHOLDER.'Finder' => [
                'kind' => 'readonly class',
                'attributes' => [
                    Connection::class => 'private'
                ],
                'implements' => [
                    'App\\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'FinderInterface'
                ]
            ],
            'App\\Infrastructure\\'.self::CLASSNAME_PLACEHOLDER.'\\Dbal'.self::CLASSNAME_PLACEHOLDER.'Repository' => [
                'kind' => 'readonly class',
                'attributes' => [
                    Connection::class => 'private'
                ],
                'implements' => [
                    'App\\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryInterface'
                ]
            ],
            'App\\Framework\\Controller\\'.self::CLASSNAME_PLACEHOLDER.'Controller' => [
                'extends' => AbstractController::class
            ],
            'App\\Framework\\Form\\'.self::CLASSNAME_PLACEHOLDER.'\\Create'.self::CLASSNAME_PLACEHOLDER.'Form' => [
                'extends' => FormType::class
            ],
            'App\\Framework\\Form\\'.self::CLASSNAME_PLACEHOLDER.'\\Update'.self::CLASSNAME_PLACEHOLDER.'Form' => [
                'extends' => FormType::class
            ],
        ];
    }
}
