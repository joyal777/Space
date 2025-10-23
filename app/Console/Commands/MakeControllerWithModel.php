<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeControllerWithModel extends Command
{
    protected $signature = 'mwc';
    protected $description = 'Create a RESTful API controller, model, migration and add routes to api.php';

    public function handle()
    {
        $this->info('--- Laravel RESTful API Controller + Model + Migration Generator ---');
        $this->newLine();

        // Ask for controller name
        $controllerName = $this->ask('Enter Controller name (e.g., ProjectController)');

        // Cancel if user leaves blank
        if (empty($controllerName)) {
            $this->warn('❌ Cancelled: Controller name not provided.');
            return Command::SUCCESS;
        }

        // Ensure controller name ends with "Controller"
        if (!Str::endsWith($controllerName, 'Controller')) {
            $controllerName .= 'Controller';
        }

        // Ask for model name
        $modelName = $this->ask('Enter Model name (e.g., Project)');

        if (empty($modelName)) {
            $this->warn('❌ Cancelled: Model name not provided.');
            return Command::SUCCESS;
        }

        // Generate route name from model name
        $routeName = Str::kebab(Str::plural($modelName));
        $controllerClass = $controllerName;

        // Confirm before proceeding
        if (!$this->confirm("Do you want to create:\n - RESTful API Controller: $controllerClass\n - Model: $modelName\n - API routes: /api/$routeName\nContinue?", true)) {
            $this->warn('⚠️ Operation cancelled by user.');
            return Command::SUCCESS;
        }

        // Create RESTful API controller
        $this->call('make:controller', [
            'name' => $controllerClass,
            '--api' => true,
        ]);
        $this->info("✅ RESTful API Controller created: $controllerClass");

        // Create model with migration
        $this->call('make:model', [
            'name' => $modelName,
            '--migration' => true,
        ]);
        $this->info("✅ Model and migration created: $modelName");

        // Add routes to api.php
        $this->addRoutesToApi($routeName, $controllerClass, $modelName);

        $this->newLine();
        $this->info('🎉 All done! RESTful API resources created successfully.');
        return Command::SUCCESS;
    }

    protected function addRoutesToApi($routeName, $controllerClass, $modelName)
    {
        $apiRoutesPath = base_path('routes/api.php');

        if (!File::exists($apiRoutesPath)) {
            $this->error('❌ routes/api.php does not exist!');
            return;
        }

        $routeDefinition = "Route::apiResource('$routeName', \\App\\Http\\Controllers\\$controllerClass::class);";

        // Read current content
        $content = File::get($apiRoutesPath);

        // Check if route already exists
        if (Str::contains($content, $routeDefinition)) {
            $this->warn("⚠️ Route for '$routeName' already exists in api.php");
            return;
        }

        // Add the route before the closing bracket of the middleware group
        if (Str::contains($content, "Route::middleware('auth:sanctum')->group(function () {")) {
            // Add inside the auth group
            $content = preg_replace(
                "/(Route::middleware\('auth:sanctum'\)->group\(function \(\) \{)([^{]*\{)/",
                "$1$2\n    $routeDefinition",
                $content
            );
        } else {
            // Add at the end of the file before the last closing bracket
            $content = rtrim($content) . "\n\n" . $routeDefinition . "\n";
        }

        File::put($apiRoutesPath, $content);
        $this->info("✅ API routes added for: $routeName");
        $this->line("   - GET /api/$routeName - index");
        $this->line("   - POST /api/$routeName - store");
        $this->line("   - GET /api/$routeName/{{$modelName}} - show");
        $this->line("   - PUT/PATCH /api/$routeName/{{$modelName}} - update");
        $this->line("   - DELETE /api/$routeName/{{$modelName}} - destroy");
    }
}
