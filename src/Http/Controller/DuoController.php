<?php

namespace AnalogRepublic\NovaDuo\Http\Controller;

use AnalogRepublic\NovaDuo\DuoAuthenticator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DuoController
{
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            Auth::guard(config('nova.guard'))->logout();
            return redirect(route('nova.pages.login'));
        }

        $authenticator = app(DuoAuthenticator::class);
        if (!$authenticator->hasState($request) || !$authenticator->compareState($request)) {
            Auth::guard(config('nova.guard'))->logout();
            return redirect(route('nova.pages.login'));
        }

        $valid = $authenticator->validateCode($request, config('nova-duo.duo'));
        if (!$valid) {
            Auth::guard(config('nova.guard'))->logout();
            return redirect(route('nova.pages.login'));
        }

        $authenticator->removeState($request);
        $authenticator->setPassedDuo($request);
        return redirect()->intended(config('nova.path'));
    }
}
