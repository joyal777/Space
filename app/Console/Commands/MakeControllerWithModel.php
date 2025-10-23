<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeControllerWithModel extends Command
{
    protected $signature = 'mwc';
    protected $description = 'Create a controller, model, and migration with prompts and cancel option';

    public function handle()
    {
        $this->info('--- Laravel Controller + Model + Migration Generator ---');
        $this->newLine();

        // Ask for controller name
        $controllerName = $this->ask('Enter Controller name (e.g., front/GeneralSettingsController)');

        // Cancel if user leaves blank
        if (empty($controllerName)) {
            $this->warn('❌ Cancelled: Controller name not provided.');
            return Command::SUCCESS;
        }

        // Ask for model name
        $modelName = $this->ask('Enter Model name (e.g., GeneralSettings)');

        if (empty($modelName)) {
            $this->warn('❌ Cancelled: Model name not provided.');
            return Command::SUCCESS;
        }

        // Confirm before proceeding
        if (! $this->confirm("Do you want to create:\n - Controller: $controllerName\n - Model: $modelName\nContinue?", true)) {
            $this->warn('⚠️ Operation cancelled by user.');
            return Command::SUCCESS;
        }

        // Create controller
        $this->call('make:controller', [
            'name' => $controllerName,
        ]);
        $this->info("✅ Controller created: $controllerName");

        // Create model with migration
        $this->call('make:model', [
            'name' => $modelName,
            '--migration' => true,
        ]);
        $this->info("✅ Model and migration created: $modelName");

        $this->newLine();
        $this->info('🎉 All done!');
        return Command::SUCCESS;
    }
}
