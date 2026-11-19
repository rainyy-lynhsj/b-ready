<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Workshop::class, \App\Policies\WorkshopPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Module::class, \App\Policies\ModulePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Assessment::class, \App\Policies\AssessmentPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\ClassroomPackage::class, \App\Policies\ClassroomPackagePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\ClassroomImplementation::class, \App\Policies\ClassroomImplementationPolicy::class);
    }
}
