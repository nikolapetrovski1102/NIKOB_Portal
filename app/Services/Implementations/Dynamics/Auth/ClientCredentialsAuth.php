<?php

namespace App\Services\Implementations\Dynamics\Auth;

use App\Support\Facades\PhpRedis;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use App\Services\Contracts\Dynamics\Auth\IAuthService;
use Illuminate\Support\Facades\Auth;

class ClientCredentialsAuth implements IAuthService
{

    public function authenticate(): object
    {
        $client = new Client(['verify' => false ]);
        $data = [
            'grant_type' => Config::get('dynamics.grant_type'),
            'client_id' => Config::get('dynamics.client_id'),
            'client_secret' => Config::get('dynamics.client_secret'),
            'resource' => Config::get('dynamics.resource_base'),
        ];
        $response = $client->post(Config::get('dynamics.auth_tenant'), [
            'form_params' => $data,
        ]);
        return json_decode($response->getBody()->getContents());
    }

    public function authorize(): string
    {
        if (Auth::check()) {
            $user = Auth::user()->name;
        } else {
            $user = 'guest_'.session()->getId();
        }
        $date = date('Ymd');

        if (PhpRedis::keyExists("Session_{$user}_{$date}")) {
            $session = PhpRedis::getKey("Session_{$user}_{$date}");
            $session = json_decode(unserialize($session));
            return $session->access_token;
        }
        $newSession = $this->authenticate();
        PhpRedis::setKey("Session_{$user}_{$date}", serialize(json_encode($newSession)), intval($newSession->expires_in));
        return $newSession->access_token;

    }

    public function flushSession(): string
    {
        return '';
    }
}