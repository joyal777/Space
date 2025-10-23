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
        $controllerName = $this->ask('Enter Controller name (e.g., ProjectController or front/ProjectController)');

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

        // Get the controller class name for the route
        $controllerClass = $this->getControllerShortName($controllerName);

        // Confirm before proceeding
        if (!$this->confirm("Do you want to create:\n - RESTful API Controller: $controllerName\n - Model: $modelName\n - API routes: /api/$routeName\nContinue?", true)) {
            $this->warn('⚠️ Operation cancelled by user.');
            return Command::SUCCESS;
        }

        // Create RESTful API controller
        $this->call('make:controller', [
            'name' => $controllerName,
            '--api' => true,
        ]);
        $this->info("✅ RESTful API Controller created: $controllerName");

        // Create model with migration
        $this->call('make:model', [
            'name' => $modelName,
            '--migration' => true,
        ]);
        $this->info("✅ Model and migration created: $modelName");

        // Add routes to api.php with proper imports
        $this->addRoutesToApi($routeName, $controllerName, $controllerClass, $modelName);

        $this->newLine();
        $this->info('🎉 All done! RESTful API resources created successfully.');
        return Command::SUCCESS;
    }

    protected function getControllerShortName($controllerName)
    {
        // Extract the short class name (last part after / or \)
        $parts = preg_split('/[\/\\\\]/', $controllerName);
        return end($parts);
    }

    protected function getControllerNamespace($controllerName)
    {
        // Convert to full namespace
        $namespace = str_replace('/', '\\', $controllerName);
        return "App\\Http\\Controllers\\$namespace";
    }

    protected function addRoutesToApi($routeName, $controllerName, $controllerClass, $modelName)
    {
        $apiRoutesPath = base_path('routes/api.php');

        if (!File::exists($apiRoutesPath)) {
            $this->error('❌ routes/api.php does not exist!');
            return;
        }

        $fullNamespace = $this->getControllerNamespace($controllerName);
        $routeDefinition = "    Route::apiResource('$routeName', $controllerClass::class);";

        // Read current content
        $content = File::get($apiRoutesPath);

        // Check if route already exists
        if (Str::contains($content, "Route::apiResource('$routeName'")) {
            $this->warn("⚠️ Route for '$routeName' already exists in api.php");
            return;
        }

        // Check if import already exists
        $useStatement = "use $fullNamespace;";
        $hasUseStatement = Str::contains($content, $useStatement);

        // Add use statement if it doesn't exist
        if (!$hasUseStatement) {
            // Find the best place to add the use statement (after existing use statements)
            if (Str::contains($content, 'use Illuminate')) {
                // Add after the last use statement
                $content = preg_replace(
                    '/(use Illuminate\\\\.*?;\n)(?!use)/',
                    "$1$useStatement\n",
                    $content
                );
            } else {
                // Add after the opening PHP tag
                $content = preg_replace(
                    '/<\?php\n\n/',
                    "<?php\n\n$useStatement\n",
                    $content
                );
            }
        }

        // Add the route inside the auth sanctum group on the next available line
        if (Str::contains($content, "Route::middleware(['auth:sanctum'])->group(function ()")) {
            // Find the auth group and add the route before the closing });
            $content = preg_replace(
                "/(Route::middleware\(\[\'auth:sanctum\'\]\)->group\(function \(\) \{\n)(.*?)(\n\}\);)/s",
                "$1$2$routeDefinition\n$3",
                $content
            );
        } elseif (Str::contains($content, "Route::middleware('auth:sanctum')->group(function ()")) {
            // Find the auth group and add the route before the closing });
            $content = preg_replace(
                "/(Route::middleware\('auth:sanctum'\)->group\(function \(\) \{\n)(.*?)(\n\}\);)/s",
                "$1$2$routeDefinition\n$3",
                $content
            );
        } else {
            // Add at the end of the file
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
