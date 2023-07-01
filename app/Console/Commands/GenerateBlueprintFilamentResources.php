<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class GenerateBlueprintFilamentResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-blueprint-filament-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        private Yaml $yaml
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * Gonna blueprint build and migrate,
         * then handle the filamenting
         */
        $this->call('blueprint:build', ['--overwrite-migrations' => true]);
        $this->call('migrate', ['--force' => true]);


        $yamlFile = base_path('draft.yaml');
        $yamlContents = $this->yaml->parseFile($yamlFile);
        $this->info(json_encode($yamlContents, 128));

        if (!isset($yamlContents['models'])) {
            return 1;
        }

        foreach ($yamlContents['models'] as $modelName => $modelProperties) {
            $args = [$modelName => true, '--view' => true, '--generate' => true];
            if (in_array('softDeletes', $modelProperties)) {
                $args['--soft-deletes'] = true;
            }
            $this->call('make:filament-resource', $args);
        }

    }
}
