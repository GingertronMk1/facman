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
    private const CLASSNAME_PLACEHOLDER = '<className>';
    private const THINGS_AND_PLACES = [
        'Application' => [
            self::CLASSNAME_PLACEHOLDER . 'FinderInterface' => <<<PHP
<?php

namespace <nameSpace>;

interface <fullClass> {
}

PHP
        ],
        'Domain' => [
            self::CLASSNAME_PLACEHOLDER . 'Id' => <<<PHP
<?php

namespace <nameSpace>;

final class <fullClass> {
}

PHP,
            self::CLASSNAME_PLACEHOLDER . 'RepositoryInterface' => <<<PHP
<?php

namespace <nameSpace>;

interface <fullClass> {
}

PHP,

        ],
        'Infrastructure' => [
            'Dbal' . self::CLASSNAME_PLACEHOLDER . 'Repository' => <<<PHP
<?php

namespace <nameSpace>;

final class <fullClass> implements Interface {
}

PHP,
            'Dbal' . self::CLASSNAME_PLACEHOLDER . 'Finder' => <<<PHP
<?php

namespace <nameSpace>;

final class <fullClass> implements Interface {
}

PHP,
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
            foreach ($things as $thing => $content) {
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
                $content = preg_replace('/<fullClass>/', $fileName, $content);
                $content = preg_replace('/<nameSpace>/', $properClassName, $content);

                if (!is_dir($dir)) {
                    mkdir($dir, recursive: true);
                }
                if (!file_exists($fullPath)) {
                    $fp = fopen($fullPath, 'w');
                    fwrite($fp, $content);
                    fclose($fp);
                }
                $output->writeln("Made `{$fullPath}`");
            }
        }
        return Command::SUCCESS;
    }
    
}
