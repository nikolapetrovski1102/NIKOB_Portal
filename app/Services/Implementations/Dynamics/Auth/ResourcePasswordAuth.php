<?php

namespace App\Services\Implementations\Dynamics\Auth;

use App\Services\Contracts\Dynamics\Auth\IAuthService;
use App\Support\Facades\PhpRedis;
use GuzzleHttp\Client;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;

class ResourcePasswordAuth implements IAuthService
{
    public function authenticate(): object
    {
        $client = new Client();
        $data = [
            'grant_type' => Config::get('dynamics.grant_type'),
            'client_id' => Config::get('dynamics.client_id'),
            'client_secret' => Config::get('dynamics.client_secret'),
            'username' => Config::get('dynamics.username'),
            'password' => Config::get('dynamics.password'),
            'resource' => Config::get('dynamics.resource_base'),
        ];
        $response = $client->post(Config::get('dynamics.auth_endpoint'), [
            'form_params' => $data,
        ]);
        return json_decode($response->getBody()->getContents());

    }

    public function authorize(): string
    {
        $date = date('Ymd');
        if (PhpRedis::keyExists("Session_{$date}")) {
            $session = PhpRedis::getKey("Session_{$date}");
            $session = json_decode(unserialize($session));
            return $session->access_token;
        }
        $newSession = $this->authenticate();
        PhpRedis::setKey("Session_{$date}", serialize(json_encode($newSession)), intval($newSession->expires_in));
        return $newSession->access_token;
    }

    public function flushSession(): string
    {
        return '';
    }
}
