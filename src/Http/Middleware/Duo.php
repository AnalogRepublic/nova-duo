<?php


namespace AnalogRepublic\NovaDuo\Http\Middleware;


use AnalogRepublic\NovaDuo\DuoAuthenticator;
use Closure;
use Illuminate\Support\Facades\Auth;

class Duo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function handle($request, Closure $next)
    {
        $except = [
            'nova-vendor/nova-duo/callback',
        ];
        if (!config('nova-duo.enabled') || in_array($request->path(),$except) || !$request->user(config('nova.guard'))) {
            return $next($request);
        }

        $authenticator = app(DuoAuthenticator::class);
        if ($authenticator->passedDuo($request)) {
            return $next($request);
        }

        $duoUrl = $authenticator->getRedirect($request, config('nova-duo.duo'));

        if (!$duoUrl) {
            Auth::guard(config('nova.guard'))->logout();
            return redirect(route('nova.pages.login'));
        }

        return redirect($duoUrl);
    }

}
