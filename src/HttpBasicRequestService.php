<?php

namespace Modules\Shared\Requests;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class HttpBasicRequestService extends HttpRequestService {
    private string $username;
    private string $password;
    protected string $url;

    protected int $timeout = 45;
    protected int $retry = 1;


    public function setUrl(string $url): void {
        $this->url = $url;
    }

    public function setCredentials(string $username, string $password): void {
        $this->username = $username;
        $this->password = $password;
    }

    protected function request(): PendingRequest {
        return Http::withBasicAuth($this->username, $this->password)
            ->baseUrl($this->url)
            ->timeout($this->timeout)
            ->asForm()
            ->retry($this->retry, 100)
            ->acceptJson();
    }

    public function postRaw(string $url, string $data, array $urlParams, array $queryParams = []): array {
        return $this->request()
            ->withBody($data)
            ->withUrlParameters($urlParams)
            ->withQueryParameters($queryParams)
            ->post($url)
            ->json();
    }
}
