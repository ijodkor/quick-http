<?php

namespace Ijodkor\QuickHttp;

use Closure;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
            ->withResponseMiddleware($this->responseMiddleware())
            ->withMiddleware($this->middleware())
            ->timeout($this->timeout)
            ->asForm()
            ->retry($this->retry, 100)
            ->acceptJson();
    }

    protected function responseMiddleware(): Closure {
        return function(ResponseInterface $response): ResponseInterface {
            return $response;
        };
    }

    protected function middleware(): Closure {
        return function(callable $handler) {
            return function(RequestInterface $request, array $options) use ($handler) {
                return $handler($request, $options);
            };
        };
    }
}
