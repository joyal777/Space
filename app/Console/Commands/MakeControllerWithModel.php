<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeControllerWithModel extends Command
{
    protected $signature = 'mwc';
    protected $description = 'Create a RESTful controller, model, migration and add routes to wen.php';

    public function handle()
    {
        $this->info('--- Laravel RESTful Controller + Model + Migration Generator ---');
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
        if (!$this->confirm("Do you want to create:\n - RESTful Controller: $controllerName\n - Model: $modelName\n - routes: /$routeName\nContinue?", true)) {
            $this->warn('⚠️ Operation cancelled by user.');
            return Command::SUCCESS;
        }

        // Create RESTful API controller
        $this->call('make:controller', [
            'name' => $controllerName,
        ]);
        $this->info("✅ RESTful Controller created: $controllerName");

        // Create model with migration
        $this->call('make:model', [
            'name' => $modelName,
            '--migration' => true,
        ]);
        $this->info("✅ Model and migration created: $modelName");

        // Add routes to api.php with proper imports
        $this->addRoutesToWeb($routeName, $controllerName, $controllerClass, $modelName);

        $this->newLine();
        $this->info('🎉 All done! RESTful resources created successfully.');
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

    protected function addRoutesToWeb($routeName, $controllerName, $controllerClass, $modelName)
    {
        $webRoutesPath = base_path('routes/web.php');

        if (!File::exists($webRoutesPath)) {
            $this->error('❌ routes/web.php does not exist!');
            return;
        }

        $fullNamespace = $this->getControllerNamespace($controllerName);
        $routeDefinition = "Route::resource('$routeName', $controllerClass::class);";

        // Read file content
        $content = File::get($webRoutesPath);

        // Check if route already exists
        if (Str::contains($content, "Route::resource('$routeName'")) {
            $this->warn("⚠️ Route for '$routeName' already exists in web.php");
            return;
        }

        // Prepare use statement
        $useStatement = "use $fullNamespace;";
        if (!Str::contains($content, $useStatement)) {
            // Insert use statement after last use if exists, else after <?php
            if (preg_match('/^<\?php\s*(?:\n|.)*?(use\s+.*?;(\s*\n)?)+/m', $content)) {
                $content = preg_replace(
                    '/(use\s+.*?;\s*\n)(?!use)/',
                    "$1$useStatement\n",
                    $content,
                    1
                );
            } else {
                $content = preg_replace(
                    '/<\?php\s*/',
                    "<?php\n\n$useStatement\n",
                    $content,
                    1
                );
            }
        }

        // Insert the route right after the "/" route
        $pattern = "/(Route::get\('\/',.*?\}\)->name\('home'\);)/s";
        if (preg_match($pattern, $content)) {
            $content = preg_replace(
                $pattern,
                "$1\n\n$routeDefinition",
                $content,
                1
            );
        } else {
            // If the "/" route not found, just append at the end
            $content = rtrim($content) . "\n\n" . $routeDefinition . "\n";
        }

        File::put($webRoutesPath, $content);

        $this->info("✅ Web route added for: $routeName");
        $this->line("   - GET /$routeName - index");
        $this->line("   - GET /$routeName/create - create");
        $this->line("   - POST /$routeName - store");
        $this->line("   - GET /$routeName/{{$modelName}} - show");
        $this->line("   - GET /$routeName/{{$modelName}}/edit - edit");
        $this->line("   - PUT/PATCH /$routeName/{{$modelName}} - update");
        $this->line("   - DELETE /$routeName/{{$modelName}} - destroy");
    }

}
