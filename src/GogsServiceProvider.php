<?php
namespace Clippedcode\Gogs;

use Illuminate\Support\ServiceProvider;

class GogsServiceProvider extends ServiceProvider
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
        $configPath = __DIR__ . '/config/ccgogs.php';
        $this->publishes([$configPath => config_path('ccgogs.php')], 'clippedcode_gogs_config');
        $this->mergeConfigFrom($configPath, 'ccgogs');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Clippedcode\Gogs\Gogs');

        $this->bindFacade();

    }

    private function bindFacade() {
        $this->app->bind('gogs', function($app) {
            return new Gogs();
        });
    }

}
