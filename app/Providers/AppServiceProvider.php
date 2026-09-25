<?php

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
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
        // Public website forms (connection, coverage, contact, support): throttle per IP to stop spam floods.
        RateLimiter::for('forms', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        // Coverage checks are lookups a visitor may repeat for several locations, so allow more.
        RateLimiter::for('coverage', fn (Request $request) => Limit::perMinute(20)->by($request->ip()));

        // Management panel permissions (config/roles.php) as gates: $user->can('leads.view'), can:leads.view.
        foreach (array_keys(config('roles.permissions')) as $permission) {
            Gate::define($permission, fn (User $user) => $user->hasPermission($permission));
        }

        $this->auditAuthentication();

        // Sidebar badges: new enquiries the signed-in staff member is allowed to see.
        View::composer('layouts.admin', function ($view) {
            $user = auth()->user();
            $counts = Enquiry::query()->visibleTo($user)->where('status', 'new')
                ->selectRaw('type, count(*) as total')->groupBy('type')->pluck('total', 'type');

            $view->with('navBadges', [
                'leads' => $counts->except(Enquiry::SUPPORT_TYPES)->sum(),
                'support' => $counts->only(Enquiry::SUPPORT_TYPES)->sum(),
            ]);
        });
    }

    protected function auditAuthentication(): void
    {
        Event::listen(Login::class, function (Login $event) {
            if ($event->user instanceof User) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
                AuditLog::record('login', $event->user, $event->user->email, user: $event->user);
            }
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user instanceof User) {
                AuditLog::record('logout', $event->user, $event->user->email, user: $event->user);
            }
        });

        Event::listen(Failed::class, function (Failed $event) {
            AuditLog::record('login_failed', $event->user, 'Failed sign-in for '.($event->credentials['email'] ?? 'unknown'));
        });
    }
}
