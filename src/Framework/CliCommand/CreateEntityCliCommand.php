<?php

namespace App\Framework\CliCommand;

use App\Application\Common\ClockInterface;
use App\Domain\Common\ValueObject\AbstractUuidId;
use Doctrine\DBAL\Connection;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\AbstractType;

#[AsCommand(
    name: 'app:create-entity',
    description: 'Create and scaffold an entity',
)]
class CreateEntityCliCommand extends Command
{
    private const CLASSNAME_PLACEHOLDER = 'className';
    private readonly Inflector $inflector;

    public function __construct(
        private readonly Filesystem $filesystem,
    ) {
        parent::__construct();
        $this->inflector = InflectorFactory::create()->build();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                self::CLASSNAME_PLACEHOLDER,
                InputArgument::OPTIONAL,
                'The name of the entity to create'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $baseClassName = $input->getArgument(self::CLASSNAME_PLACEHOLDER);

        $successful = [];
        foreach ($this->getClassNames() as $classNameRaw => $properties) {
            $className = $this->replacePlaceholder($classNameRaw, $baseClassName);

            $classFileName = str_replace(['\\', 'App/'], ['/', 'src/'], $className).'.php';
            $fileMarkup = $this->getMarkupForFile($className, $properties, $baseClassName);

            try {
                $this->filesystem->dumpFile(
                    $classFileName,
                    $fileMarkup
                );
                $successful[] = $classFileName;
            } catch (\Throwable $e) {
                $io->error($e->getMessage());
            }
        }

        $io->listing($successful);

        return Command::SUCCESS;
    }

    /**
     * @return array<string, string>
     *
     * @throws \Exception
     */
    private function getBaseNameAndNameSpace(string $className): array
    {
        $lastBackslash = strrpos($className, '\\');

        if (!$lastBackslash) {
            throw new \Exception("No backslash found in {$className}.");
        }

        return [
            'nameSpace' => substr($className, 0, $lastBackslash),
            'classBaseName' => substr($className, $lastBackslash + 1),
        ];
    }

    private function replacePlaceholder(string $target, string $className): string
    {
        return str_replace(self::CLASSNAME_PLACEHOLDER, $className, $target);
    }

    /**
     * @param array<string, mixed> $properties
     *
     * @throws \Exception
     */
    private function getMarkupForFile(string $className, array $properties, string $entityName): string
    {
        $markup = ['<?php', '', 'declare(strict_types=1);', ''];
        ['nameSpace' => $nameSpace, 'classBaseName' => $className] = $this->getBaseNameAndNameSpace($className);

        $markup[] = "namespace {$nameSpace};";
        $markup[] = '';
        $kind = $properties['kind'] ?? 'class';
        $idLine = "{$kind} {$className}";

        if ($extends = $properties['extends'] ?? false) {
            $idLine .= " extends \\{$extends}";
        }

        if ($implements = $properties['implements'] ?? false) {
            $implements = implode(
                ', ',
                array_map(
                    fn (string $c) => '\\'.$this->replacePlaceholder($c, $entityName),
                    $implements
                )
            );
            $idLine .= " implements {$implements}";
        }

        $markup[] = $idLine;
        $markup[] = '{';

        if ('interface' !== $kind && ($properties['constructor'] ?? true)) {
            $markup[] = 'public function __construct(';
            foreach ($properties['attributes'] ?? [] as $class => $type) {
                ['classBaseName' => $attrClassName] = $this->getBaseNameAndNameSpace($class);
                $attribute = "{$type} \\{$class} $";
                $attribute .= $this->inflector->camelize(
                    $this->replacePlaceholder(
                        $attrClassName,
                        $entityName
                    )
                );
                $markup[] = "{$attribute},";
            }
            $markup[] = ') {}';
        }
        $markup[] = '}';

        $markup = implode(PHP_EOL, $markup).PHP_EOL;

        return str_replace(self::CLASSNAME_PLACEHOLDER, $entityName, $markup);
    }

    /**
     * @return array<string, mixed>
     */
    private function getClassNames(): array
    {
        return [
            'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'Entity' => [
                'attributes' => [
                    'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => 'public',
                ],
            ],
            'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryInterface' => [
                'kind' => 'interface',
            ],
            'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryException' => [
                'kind' => 'final class',
                'extends' => \Exception::class,
            ],
            'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => [
                'kind' => 'readonly class',
                'extends' => AbstractUuidId::class,
                'constructor' => false,
            ],
            'App\Application\\'.self::CLASSNAME_PLACEHOLDER.'\Command\Create'.self::CLASSNAME_PLACEHOLDER.'Command' => [
                'attributes' => [
                    'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => 'public',
                ],
            ],
            'App\Application\\'.self::CLASSNAME_PLACEHOLDER.'\Command\Update'.self::CLASSNAME_PLACEHOLDER.'Command' => [
                'attributes' => [
                    'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => 'public',
                ],
            ],
            'App\Application\\'.self::CLASSNAME_PLACEHOLDER.'\CommandHandler\Create'.self::CLASSNAME_PLACEHOLDER.'CommandHandler' => [
                'kind' => 'readonly class',
                'attributes' => [
                    'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryInterface' => 'private',
                ],
            ],
            'App\Application\\'.self::CLASSNAME_PLACEHOLDER.'\CommandHandler\Update'.self::CLASSNAME_PLACEHOLDER.'CommandHandler' => [
                'kind' => 'readonly class',
                'attributes' => [
                    'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryInterface' => 'private',
                ],
            ],
            'App\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'FinderInterface' => [
                'kind' => 'interface',
            ],
            'App\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'FinderException' => [
                'kind' => 'final class',
                'extends' => \Exception::class,
            ],
            'App\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'Model' => [
                'kind' => 'readonly class',
                'attributes' => [
                    'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\ValueObject\\'.self::CLASSNAME_PLACEHOLDER.'Id' => 'public',
                ],
            ],
            'App\Infrastructure\\'.self::CLASSNAME_PLACEHOLDER.'\Dbal'.self::CLASSNAME_PLACEHOLDER.'Finder' => [
                'kind' => 'readonly class',
                'attributes' => [
                    Connection::class => 'private',
                ],
                'implements' => [
                    'App\Application\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'FinderInterface',
                ],
            ],
            'App\Infrastructure\\'.self::CLASSNAME_PLACEHOLDER.'\Dbal'.self::CLASSNAME_PLACEHOLDER.'Repository' => [
                'kind' => 'readonly class',
                'attributes' => [
                    Connection::class => 'private',
                    ClockInterface::class => 'private',
                ],
                'implements' => [
                    'App\Domain\\'.self::CLASSNAME_PLACEHOLDER.'\\'.self::CLASSNAME_PLACEHOLDER.'RepositoryInterface',
                ],
            ],
            'App\Framework\Controller\\'.self::CLASSNAME_PLACEHOLDER.'Controller' => [
                'extends' => AbstractController::class,
            ],
            'App\Framework\Form\\'.self::CLASSNAME_PLACEHOLDER.'\Create'.self::CLASSNAME_PLACEHOLDER.'FormType' => [
                'extends' => AbstractType::class,
            ],
            'App\Framework\Form\\'.self::CLASSNAME_PLACEHOLDER.'\Update'.self::CLASSNAME_PLACEHOLDER.'FormType' => [
                'extends' => AbstractType::class,
            ],
        ];
    }
}
