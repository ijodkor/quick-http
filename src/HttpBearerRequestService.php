<?php

namespace Modules\Shared\Requests;

use Closure;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpBearerRequestService extends HttpRequestService {

    protected string $url;
    private string $token;
    protected int $retries = 1;

    public function setUrl(string $url): void {
        $this->url = $url;
    }

    public function setCredentials(string $token): void {
        $this->token = $token;
    }

    protected function request(): PendingRequest {
        return Http::withHeader('Authorization', "Bearer $this->token")
            ->withResponseMiddleware($this->responseMiddleware())
            ->withMiddleware($this->middleware())
            ->baseUrl($this->url)
            ->retry($this->retries)
            ->acceptJson();
    }

    protected function responseMiddleware(): Closure {
        return function (ResponseInterface $response): ResponseInterface {
            return $response;
        };
    }

    protected function middleware(): Closure {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                return $handler($request, $options);
            };
        };
    }
}
