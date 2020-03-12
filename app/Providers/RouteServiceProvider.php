<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Vinkla\Hashids\Facades\Hashids;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::bind('user', function ($value, $route) {
            return $this->getModel(\App\User::class, $value);
        });

        Route::bind('job', function ($value, $route) {
            return $this->getModel(\App\Job::class, $value);
        });

        Route::bind('proposal', function ($value, $route) {
            return $this->getModel(\App\Proposal::class, $value);
        });

        Route::bind('offer', function ($value, $route) {
            return $this->getModel(\App\Offer::class, $value);
        });

        Route::bind('contract', function ($value, $route) {
            return $this->getModel(\App\Contract::class, $value);
        });
        Route::bind('invoice', function ($value, $route) {
            return $this->getModel(\App\Invoice::class, $value);
        });
    }

    private function getModel($model, $routeKey)
    {
        $id = Hashids::connection($model)->decode($routeKey)[0] ?? null;
        $modelInstance = resolve($model);

        return  $modelInstance->findOrFail($id);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
