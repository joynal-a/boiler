<?php

namespace Abedin\Boiler\Commands;

use Abedin\Boiler\Lib\Generator\Model;
use Abedin\Boiler\Lib\Generator\Repository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model {models?*} {--m}';

    /**
     * The console command description.
     *-
     * @var string
     */
    protected $description = 'If run this command create new Model/Models and ask for create with Repository/Repositories.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // get all models or ask model name
        $models = match(empty($this->argument('models'))){
            true => explode(' ', $this->ask('Enter Model/Models Name')),
            default => $this->argument('models')
        };
        
        $migrate = $this->option('m');
        // confirmation message
        $this->info("==> I just need a confirmation on whether I will create the repository with the model.\nif you agree I just create the repository with model.");

        // get an answer on Whether to create the repository
        $makeWithRepo = $this->confirm('Do you want to create repository?');

        $this->info('Thanks for your confirmation.');

        // get all exists moldes for ignore creating model
        $existsModels = Model::existsModels();
        // get all exists repositories for ignore creating repository
        $existsRepositories = Repository::existsRepositories();

        if(!is_array($models)){
            $models = [$models];
        }

        $this->output->progressStart(count($models));
        foreach($models as $model){
            $model = ucfirst($model);
            // check is exists models and ignore
            if(!in_array($model, $existsModels)){
                Model::generate($model);
            }
            // check is exists repositories and ignore
            if(!in_array($model . 'Repository', $existsRepositories) && $makeWithRepo){
                Repository::generate($model);
            }

            if($migrate){
                $this->createMigration($model);
                sleep(1);
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        $this->info('Model/Models is created successfully.');
    }

    /**
     * When run the command to create migration simple run this method.
     * @return void
     */
    protected function createMigration($modelName): void
    {
        // Replace uppercase letters with underscores and lowercase versions
        $modelName = preg_replace('/([a-z])([A-Z])/', '$1_$2', $modelName);

        // Convert the result to lowercase
        $modelName = strtolower($modelName);
        
        // make plurar model name
        $tableName = Str::plural(strtolower($modelName));
        $migrationName = "create_{$tableName}_table";

        $this->call('make:migration', [
            'name' => $migrationName,
            '--create' => $tableName,
        ]);
    }
}
