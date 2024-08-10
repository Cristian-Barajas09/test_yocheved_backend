<?php

namespace App\Providers;

use App\Services\{
    ScheduleSessionsService,
    SessionService,
    StudentAvailableService,
    ReportService
};
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();

        $this->app->singleton(ScheduleSessionsService::class, function () {
            return new ScheduleSessionsService();
        });

        $this->app->singleton(SessionService::class, function () {
            return new SessionService();
        });

        $this->app->singleton(StudentAvailableService::class, function () {
            return new StudentAvailableService();
        });

        $this->app->singleton(ReportService::class, function () {
            return new ReportService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
