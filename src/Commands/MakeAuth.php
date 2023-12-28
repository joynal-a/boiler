<?php

namespace Abedin\Boiler\Commands;

use Abedin\Boiler\Lib\Auth\ApiAuth;
use Abedin\Boiler\Lib\Auth\WebAuth;
use Abedin\Boiler\Lib\Generator\Repository;
use Illuminate\Console\Command;

class MakeAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:generate {auth?} {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Authincate and make AuthController';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $auth = $this->argument('auth');
        $type = $this->option('type');

        if(!$auth){
            $auth = $this->ask('Please tell me which type of authentication you need (web/api)?');
        }
        $auth = strtolower($auth);

        if($auth == 'api' && !$type){
            $type = $this->ask('Please tell me which type of authentication you prefer (passport/sanctum)?');
        }

        $existsRepositories = Repository::existsRepositories();
         // check is exists repositories and ignore
        $isCreteUserRepo = true;
        if(!in_array('UserRepository', $existsRepositories)){
            $isCreteUserRepo = $this->confirm('Did you want to create UserRepository?');
            if($isCreteUserRepo){
                Repository::generate('User');
            }
        }

        if($auth == 'api'){
            $existsMethods = ApiAuth::run($type, $isCreteUserRepo);
            if($existsMethods){
                $this->error('Already You use the ' .  $existsMethods . ' method/methods in the API/AuthController.');
                exit;
            }
        }

        if($auth == 'web'){
            $existsMethods = WebAuth::run($isCreteUserRepo);
            if($existsMethods){
                $this->error('Already You use the ' .  $existsMethods . ' method/methods in the Web/AuthController.');
                exit;
            }
        }
        $this->info('The authentication process has been completed.');
    }
}
