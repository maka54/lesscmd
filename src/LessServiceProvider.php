<?php namespace Maka\Lesscmd;

use Illuminate\Support\ServiceProvider;
use Maka\Lesscmd\Console\LessBuilder;

class LessServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/lesscmd.php', 'lesscmd');
        $this->registerLessCommand();
    }
    
    /**
     * Registers the workbench:package command.
     */
    protected function registerLessCommand()
    {
        $this->app->singleton('command.lesscmd', function ($app) {
            return $app->make(LessBuilder::class);
        });
        $this->commands('command.lesscmd');
    }

}
