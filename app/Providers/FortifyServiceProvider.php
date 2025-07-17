<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Custom login response
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                return redirect()->intended('/admin/dashboard');
            }
        });

        // Custom logout response
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/admin/login')->with('status', 'Anda telah berhasil logout.');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom authentication untuk admin
        Fortify::authenticateUsing(function (Request $request) {
            // Validasi input
            if (empty($request->username) || empty($request->password)) {
                return null;
            }

            // Cari admin berdasarkan username
            $admin = Admin::where('username', $request->username)->first();

            // Verifikasi password dan pastikan return model dengan ID
            if ($admin && Hash::check($request->password, $admin->password)) {
                // Make sure admin has an ID for session storage
                if (!$admin->id) {
                    return null;
                }
                return $admin;
            }

            return null;
        });

        // Custom login view
        Fortify::loginView(function () {
            return view('admin.login');
        });

        // Rate limiting untuk admin login
        RateLimiter::for('login', function (Request $request) {
            $username = (string) $request->username;
            return Limit::perMinute(3)->by($username . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
