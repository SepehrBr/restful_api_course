<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeServiceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generates try-catch template for controller';

    protected function getStub()
    {
        return __DIR__ . '/Stubs/controller-service.stub';
    }
    protected function getDefaultNamespace($rootNamespace)
    {
        return "$rootNamespace/Services";
    }

    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $model = $this->option('model');

        if ($model) {
            $modelClass = class_basename($model);
            $modelVariable = Str::camel($modelClass);
            $modelNamespace = "App\\Models\\$model";
        } else {
            $modelClass = 'Model';
            $modelVariable = 'model';
            $modelNamespace = 'App\\Models\\Model';
        }

        return str_replace(
            ['{{ model }}', '{{ modelVariable }}', '{{ modelNamespace }}'],
            [$modelClass, $modelVariable, $modelNamespace],
            $stub
        );
    }
}
