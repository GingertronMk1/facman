<?php

declare(strict_types=1);

namespace App\Framework\Console;

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
    private const TYPE_INTERFACE = 'cnterface';
    private const CLASSNAME_PLACEHOLDER = '<className>';
    private const TYPE_CLASS = 'class';
    private const THINGS_AND_PLACES = [
        'Application' => [
            self::CLASSNAME_PLACEHOLDER . 'FinderInterface' => [
                'type' => self::TYPE_INTERFACE
            ]
        ],
        'Domain' => [
            self::CLASSNAME_PLACEHOLDER . 'Id' => [
                'type' => self::TYPE_CLASS
            ],
            self::CLASSNAME_PLACEHOLDER . 'RepositoryInterface' => [
                'type' => self::TYPE_INTERFACE
            ],
        ],
        'Infrastructure' => [
            'Dbal' . self::CLASSNAME_PLACEHOLDER . 'Repository' => [
                'type' => self::TYPE_CLASS
            ],
            'Dbal' . self::CLASSNAME_PLACEHOLDER . 'Finder' => [
                'type' => self::TYPE_CLASS
            ],
        ],
        'Framework' => [
            'Controller/' . self::CLASSNAME_PLACEHOLDER . 'Controller' => [
                'type' => self::TYPE_CLASS
            ]
        ]
    ];

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
        foreach(self::THINGS_AND_PLACES as $place => $things) {
            foreach ($things as $thing => $attrs) {
                $dir = "src/{$place}/{$className}";
                $fileName = str_replace(
                    self::CLASSNAME_PLACEHOLDER,
                    $className,
                    $thing
                );
                $fullPath = "{$dir}/{$fileName}.php";
                $output->writeln("Making `{$fullPath}`");
                $properClassName = preg_replace('/\bsrc\b/', 'App', $dir);
                $properClassName = preg_replace('/\//', '\\', $properClassName);

                $content = [
                    '<?php',
                    '',
                    "namespace {$properClassName};",
                    ''
                ];

                $content[] = match($attrs['type']) {
                    self::TYPE_CLASS => 'class ' . $fileName,
                    self::TYPE_INTERFACE => 'interface ' . $fileName,
                };

                $content[] = '{' . PHP_EOL . PHP_EOL . '}';


                touch($fullPath);
                $fp = fopen($fullPath, 'w');
                fwrite($fp, implode(PHP_EOL, $content));
                fclose($fp);
                $output->writeln("Made `{$fullPath}`");
            }
        }
        return Command::SUCCESS;
    }
    
}
