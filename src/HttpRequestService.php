<?php

namespace Modules\Shared\Requests;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HttpRequestService {
    protected string $url;
    protected array $headers = [];

    public function __construct() {
    }

    public function setUrl(string $url): void {
        $this->url = $url;
    }

    public function setHeaders(array $headers): void {
        $this->headers = $headers;
    }

    protected function request(): PendingRequest {
        return Http::baseUrl($this->url)
            ->acceptJson();
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    protected function get(string $url, array $urlParams = [], array $queryParams = []): PromiseInterface|Response {
        return $this->request()
            ->withHeaders($this->headers)
            ->withUrlParameters($urlParams)
            ->withQueryParameters($queryParams)
            ->withOptions(["verify" => false])
            ->get($url)
            ->throw();
    }


    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function getJson(string $url, array $urlParams = [], array $queryParams = []): array|null {
        return $this->get($url, $urlParams, $queryParams)->json();
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function getObject(string $url, array $urlParams = [], array $queryParams = []): object|null{
        return $this->get($url, $urlParams, $queryParams)->object();
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function getBody(string $url, array $urlParams = [], array $queryParams = []): string {
        return $this->get($url, $urlParams, $queryParams)->body();
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function getContent(string $url, array $urlParams = [], array $queryParams = []): string {
        return $this->get($url, $urlParams, $queryParams)->json();
    }

    /**
     * @param array $queryParams
     * @throws ConnectionException
     * @throws RequestException
     */
    protected function post(string $url, array|string $data, array $urlParams = [], $queryParams = []): PromiseInterface|Response {
        return $this->request()
            ->withUrlParameters($urlParams)
            ->withQueryParameters($queryParams)
            ->withOptions(["verify" => false])
            ->post($url, $data)
            ->throw();
    }

    /**
     * @param array $queryParams
     * @throws ConnectionException
     * @throws RequestException
     */
    public function postJson(string $url, array $data = [], array $urlParams = [], $queryParams = []): ?array {
        return $this->post($url, $data, $urlParams, $queryParams)->json();
    }

    /**
     * @param array $queryParams
     * @throws ConnectionException
     * @throws RequestException
     */
    public function postObject(string $url, array|string $data = [], array $urlParams = [], $queryParams = []): ?object {
        return $this->post($url, $data, $urlParams, $queryParams)->object();
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function postBody(string $url, array $data = [], array $urlParams = [], array $queryParams = []): string {
        return $this->post($url, $data, $urlParams, $queryParams)->body();
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function delete(string $url, array $data = [], array $urlParams = [], array $queryParams = []): bool|object {
        return $this->request()
            ->withUrlParameters($urlParams)
            ->withQueryParameters($queryParams)
            ->withOptions(["verify" => false])
            ->delete($url, $data)
            ->throw()
            ->object();
    }
}
