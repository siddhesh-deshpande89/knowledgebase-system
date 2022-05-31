<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FactoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->changeTheDefaultDatabaseModelsFactoriesPath();
    }

    private function changeTheDefaultDatabaseModelsFactoriesPath()
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $namespace = '\Database\\Factories\\';
            $modelName = Str::afterLast($modelName, '\\');

            return $namespace . $modelName . 'Factory';
        });
    }

}
