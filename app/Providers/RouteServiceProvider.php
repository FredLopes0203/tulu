<?php

namespace App\Providers;

use App\Models\Access\User\User;
use App\Models\Alert;
use App\Models\Group;
use App\Models\Subadmin\Subadmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class RouteServiceProvider.
 */
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
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Register route model bindings
         */

        /*
         * This allows us to use the Route Model Binding with SoftDeletes on
         * On a model by model basis
         */

        $this->bind('curalert', function ($value) {
            $alert = new Alert();

            return Alert::withTrashed()->where($alert->getRouteKeyName(), $value)->first();
        });

        $this->bind('deletedUser', function ($value) {
            $user = new User();

            return User::withTrashed()->where($user->getRouteKeyName(), $value)->first();
        });

        $this->bind('deletedSubadmin', function ($value) {
            $subadmin = new Subadmin();

            return Subadmin::withTrashed()->where($subadmin->getRouteKeyName(), $value)->first();
        });

        $this->bind('deletedOrganization', function ($value) {
            $subadmin = new Group();

            return Group::withTrashed()->where($subadmin->getRouteKeyName(), $value)->first();
        });

        $this->bind('manager', function ($value){
            $user = new User();
            return User::where('id', $value)->first();
        });

        $this->bind('myuser', function ($value){
            $user = new User();
            return User::where('id', $value)->first();
        });

        parent::boot();
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
