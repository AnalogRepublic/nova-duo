<?php

namespace AnalogRepublic\NovaDuo;

use Duo\DuoUniversal\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DuoAuthenticator
{
    public function passedDuo(Request $request): bool
    {
        return $request->session()->has('duo_passed:' . $request->user(config('nova.guard'))?->id);
    }

    public function setPassedDuo(Request $request): void
    {
        $request->session()->put('duo_passed:' . $request->user(config('nova.guard'))?->id, true);
    }

    public function getRedirect(Request $request, array $config): string|null
    {
        try {
            $client = $this->getClient($config);

            $username = $request->user(config('nova.guard'))->email;
            $state = $client->generateState();
            $this->storeState($request, $state);
            return $client->createAuthUrl($username, $state);
        } catch (\Exception $e) {
            Log::error('Duo failed');
            report($e);

            return null;
        }
    }

    public function hasState(Request $request): bool
    {
        return $request->session()->has('duo_state');
    }

    public function storeState(Request $request, string $state): void
    {
        $request->session()->put('duo_state', $state);
    }

    public function removeState(Request $request): void
    {
        $request->session()->remove('duo_state');
    }

    public function compareState(Request $request): bool
    {
        return $request->session()->get('duo_state') === $request->get('state');
    }

    public function validateCode(Request $request, array $config): bool
    {
        try {
            $client = $this->getClient($config);
            $decodedToken = $client->exchangeAuthorizationCodeFor2FAResult($request->get('duo_code'), $request->user(config('nova.guard'))->email);
            return true;
        } catch (\Exception $e) {
            Log::error('Duo failed');
            report($e);

            return false;
        }
    }

    private function getClient(array $config): Client
    {
        $client = new Client(
            $config['client_id'],
            $config['client_secret'],
            $config['api_hostname'],
            url('/nova-vendor/nova-duo/callback'),
            true
        );

        $client->healthCheck();

        return $client;
    }
}
