<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    protected $domainPolicies = [
        \App\Services\Lesson\CreateLessonService::class,
        \App\Services\Lesson\ConfirmLessonService::class,
        \App\Services\Lesson\CreatePeriodService::class,
        \App\Services\Lesson\ConfirmPeriodService::class,
        \App\Services\Lesson\GetLessonService::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes(function($router){
            $router->forAccessTokens();
        });
        foreach ($this->domainPolicies as $policy) {
            call_user_func("\\".$policy."::registerPolicies");
        }
    }
}
