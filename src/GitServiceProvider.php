<?php
namespace Clippedcode\Git;

use Illuminate\Support\ServiceProvider;

class GitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // loading the routes
        // require __DIR__ . "/Http/routes.php";
        $configPath = __DIR__ . '/config/ccgit.php';
        $this->publishes([$configPath => config_path('ccgit.php')], 'clippedcode_git_config');
        $this->mergeConfigFrom($configPath, 'ccgit');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Clippedcode\Git\Git');

        $this->bindFacade();

    }

    private function bindFacade() {
        $this->app->bind('git', function($app) {
            return new Git();
        });
    }

}
