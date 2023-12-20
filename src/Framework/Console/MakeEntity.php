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
            self::CLASSNAME_PLACEHOLDER . 'FinderInterface',
        ],
        'Domain' => [
            self::CLASSNAME_PLACEHOLDER . 'Id',
            self::CLASSNAME_PLACEHOLDER . 'RepositoryInterface'
        ],
        'Infrastructure' => [
            'Dbal' . self::CLASSNAME_PLACEHOLDER . 'Repository',
            'Dbal' . self::CLASSNAME_PLACEHOLDER . 'Finder',
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
            foreach ($things as $thing) {
                $dir = "src/{$place}/{$className}";
                $fileName = str_replace(
                    self::CLASSNAME_PLACEHOLDER,
                    $className,
                    $thing
                );
                $fullPath = "{$dir}/{$fileName}.php";
                $output->writeln("Making `{$fullPath}`");
                if (!is_dir($dir)) {
                    mkdir($dir, recursive: true);
                }
                if (!file_exists($fullPath)) {
                    $fp = fopen($fullPath, 'w');
                    fwrite($fp, "<?php\n\n");
                    fclose($fp);
                }
                touch($fullPath);
                $output->writeln("Made `{$fullPath}`");
            }
        }
        return Command::SUCCESS;
    }
    
}
