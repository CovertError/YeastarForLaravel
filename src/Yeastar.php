<?php

namespace Coverterror\YeastarForLaravel;

use Carbon\Carbon;
use Coverterror\YeastarForLaravel\Models\YeastarToken;
use Illuminate\Support\Facades\Http;

/**
 * Provides functionality to interact with Yeastar services.
 *
 * This class encapsulates the logic required to communicate with Yeastar, including making calls,
 * handling token management for authentication, and processing responses from the Yeastar API.
 */
class Yeastar
{
    /**
     * The base URL for the Yeastar API.
     */
    private string $baseUrl;

    /**
     * The username for authentication with the Yeastar API.
     */
    private string $username;

    /**
     * The password for authentication with the Yeastar API.
     */
    private string $password;

    /**
     * Initializes the class with necessary configuration from Laravel's config system.
     */
    public function __construct()
    {
        $this->baseUrl = config('yeastarforlaravel.url');
        $this->username = config('yeastarforlaravel.username');
        $this->password = config('yeastarforlaravel.password');
    }

    /**
     * Initiates a call between two parties using the Yeastar service.
     *
     * @param  string  $caller  The caller's identifier.
     * @param  string  $callee  The callee's identifier.
     * @param  bool  $returnOnlyStatus  Determines if only the status of the call initiation should be returned.
     * @return mixed The status of the call initiation or the full response based on $returnOnlyStatus.
     */
    public function makeCall(string $caller, string $callee, bool $returnOnlyStatus = true): mixed
    {
        $response = $this->makePostRequest([
            'path' => '/call/dial',
            'parameters' => [
                'caller' => $caller,
                'callee' => $callee,
            ],
            'access_token_needed' => true,
        ]);

        return $returnOnlyStatus ? $response['success'] : $response;
    }

    /**
     * Sends a POST request to the Yeastar API.
     *
     * @param  array  $requestInformation  Information about the request including the path, parameters, and whether an access token is needed.
     * @return array The response from the Yeastar API.
     */
    private function makePostRequest(array $requestInformation): array
    {
        $url = $this->baseUrl.$requestInformation['path'];

        // Append access token to the URL if needed
        if ($requestInformation['access_token_needed']) {
            $token = $this->manageToken();
            $url .= '?access_token='.$token;
        }

        $response = Http::post($url, $requestInformation['parameters'])->body();

        return $this->handleResponse($response);
    }

    /**
     * Manages the access token for API requests, obtaining a new token if necessary.
     *
     * @return string The access token for API requests.
     */
    private function manageToken(): string
    {
        $systemToken = (new YeastarToken())->all()->first();
        if (! $systemToken) {
            return $this->getToken();
        }

        if (Carbon::now()->greaterThan($systemToken->access_token_expire_time)) {
            if (Carbon::now()->lessThan($systemToken->refresh_token_expire_time)) {
                return $this->refreshToken($systemToken->refresh_token);
            } else {
                return $this->getToken();
            }
        } else {
            return $systemToken->token;
        }

    }

    /**
     * Retrieves a new access token from the Yeastar API.
     *
     * @return string The new access token.
     */
    private function getToken(): string
    {
        $response = $this->makePostRequest([
            'path' => 'get_token',
            'parameters' => [
                'username' => $this->username,
                'password' => $this->password,
            ],
            'access_token_needed' => false,
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

    /**
     * Refreshes the access token using the refresh token.
     *
     * @param  string  $refreshToken  The refresh token.
     * @return string The new access token.
     */
    private function refreshToken(string $refreshToken): string
    {
        $response = $this->makePostRequest([
            'path' => 'refresh_token',
            'parameters' => [
                'refresh_token' => $refreshToken,
            ],
            'access_token_needed' => false,
        ]);

        $response = json_decode($response['data']);
        if (isset($response->errcode) && $response->errcode !== 0) {
            // If refreshing the token fails, fall back to getting a new token
            return $this->getToken();
        }
        $systemToken = (new YeastarToken())->all()->first();
        $systemToken->updateOrCreate([
            'access_token' => $response->access_token,
            'access_token_expire_time' => Carbon::now()->addSeconds($response->access_token_expire_time),
            'refresh_token' => $response->refresh_token,
            'refresh_token_expire_time' => Carbon::now()->addSeconds($response->refresh_token_expire_time),
        ]);

        return $response->access_token;
    }

    /**
     * Handles the API response, decoding and interpreting the JSON payload.
     *
     * @param  string  $response  The raw JSON response from the API.
     * @return array An array containing the 'success' status and decoded data or error message.
     */
    private function handleResponse(string $response): array
    {
        $data = json_decode($response, true); // Decode the JSON response into an associative array

        // Check if the JSON response could not be decoded
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'message' => 'Invalid JSON response'];
        }

        // If the API returned an error code indicating success
        if (isset($data['errcode']) && $data['errcode'] === 0) {
            return ['success' => true, 'data' => $data];
        }

        // Default case: API returned an error
        return ['success' => false, 'data' => $data, 'message' => 'API error occurred'];

    }
}
