<?php

namespace Coverterror\YeastarForLaravel;

use Carbon\Carbon;
use Coverterror\YeastarForLaravel\Models\YeastarToken;
use Illuminate\Support\Facades\Http;

class Yeastar
{
    private string $baseUrl;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->baseUrl = config('yeastarforlaravel.url');
        $this->username = config('yeastarforlaravel.username');
        $this->password = config('yeastarforlaravel.password');
    }

    public function makeCall(string $caller, string $callee, bool $returnOnlyStatus = true): mixed
    {
        $response = $this->makePostRequest([
            "path" => "/call/dial",
            "parameters" => [
                "caller" => $caller,
                "callee" => $callee,
            ],
            'access_token_needed' => true
        ]);

        return $returnOnlyStatus ? $response['success'] : $response;
    }

    private function makePostRequest(array $requestInformation): array
    {
        $url = $this->baseUrl . $requestInformation['path'];

        // Append access token to the URL if needed
        if ($requestInformation['access_token_needed']) {
            $token = $this->manageToken();
            $url .= '?access_token=' . $token;
        }

        $response = Http::post($url, $requestInformation['parameters'])->body();
        return $this->handleResponse($response);
    }

    private function manageToken(): string
    {
        $systemToken = (new YeastarToken())->first();
        if (!$systemToken){
            return $this->getToken();
        }

        if (Carbon::now()->greaterThan($systemToken->access_token_expire_time)) {
            if (Carbon::now()->lessThan($systemToken->refresh_token_expire_time)) {
                return $this->refreshToken($systemToken->refresh_token);
            }else{
                return $this->getToken();
            }
        }else{
            return $systemToken->token;
        }

    }

    private function getToken(): string
    {
        $response = $this->makePostRequest([
            "path" => "get_token",
            "parameters" => [
                'username' => $this->username,
                'password' => $this->password
            ],
            'access_token_needed' => false
        ]);

        $response = json_decode($response['data']);
        (new YeastarToken())->updateOrCreate([
            'token' => $response->access_token,
            'access_token_expire_time' => Carbon::now()->addSeconds($response->access_token_expire_time),
            'refresh_token' => $response->refresh_token,
            'refresh_token_expire_time' => Carbon::now()->addSeconds($response->refresh_token_expire_time),
        ]);

        return $response->access_token;
    }

    private function refreshToken(string $refreshToken): string
    {
        $response = $this->makePostRequest([
            "path" => "refresh_token",
            "parameters" => [
                'refresh_token' => $refreshToken,
            ],
            'access_token_needed' => false
        ]);

        $response = json_decode($response['data']);
        if (isset($response->errcode) && $response->errcode !== 0) {
            return $this->getToken();
        }
        $systemToken = (new YeastarToken())->first();
        $systemToken->updateOrCreate([
            'access_token' => $response->access_token,
            'access_token_expire_time' => Carbon::now()->addSeconds($response->access_token_expire_time),
            'refresh_token' => $response->refresh_token,
            'refresh_token_expire_time' => Carbon::now()->addSeconds($response->refresh_token_expire_time),
        ]);

        return $response->access_token;
    }

    private function handleResponse($response): array
    {
        $data = json_decode($response);

        if (isset($data->errcode) && $data->errcode === 0) {
            return ['success' => true, 'data' => json_encode($data)];
        }

        return ['success' => false, 'data' => json_encode($data)];
    }
}
