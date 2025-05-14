<?php

class ApiClient
{
    private $baseUrl;
    private $token;
    private $timeout = 30;

    public function __construct($baseUrl, $token = null)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->token = $token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function get($endpoint, $params = [])
    {
        return $this->request("GET", $endpoint, $params);
    }

    public function post($endpoint, $data)
    {
        return $this->request("POST", $endpoint, [], $data);
    }

    public function put($endpoint, $data)
    {
        return $this->request("PUT", $endpoint, [], $data);
    }

    public function delete($endpoint)
    {
        return $this->request("DELETE", $endpoint);
    }

    public function patch($endpoint, $data)
    {
        return $this->request("PATCH", $endpoint, [], $data);
    }

    private function request($method, $endpoint, $params = [], $data = null)
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        if (!empty($params) && $method === "GET") {
            $url .= '?' . http_build_query($params);
        }

        $curl = curl_init();

        $headers = [
            'Accept: application/json',
            'Content-Type: application/json'
        ];

        if ($this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($data !== null && in_array($method, ["POST", "PUT", "PATCH"])) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new Exception("cURL Error: " . $err);
        }

        if ($responseCode >= 400) {
            throw new Exception("HTTP Error: " . $responseCode . " - " . $response);
        }

        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON Decode Error: " . json_last_error_msg());
        }

        return $result;
    }
}