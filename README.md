# Collection of http request in Laravel/PHP

# Example

```php
/**
 * Middleware to refresh token
 * @return Closure
 */
class EDMRequestService extends HttpBearerRequestService {

    public function __construct(readonly EDMAuthService $authService) {
        parent::__construct();

        $url = config('integration.edm_api_url');
        $this->setUrl("$url/document");
        $this->setCredentials($authService->getToken());
    }

    protected function middleware(): Closure {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $promise = $handler($request, $options);
                return $promise->then(function (ResponseInterface $response) use ($handler, $request, $options) {
                    /* @var Promise $promise */

                    if (in_array($response->getStatusCode(), [401, 403])) {
                        $token = $this->authService->login();
                        $request = $request->withHeader('Authorization', "Bearer $token");

                        // Retry request after refreshing token
                        // $promise->wait();
                        return $handler($request, $options);
                    }

                    return $response;
                });
            };
        };
    }
}
```

## References

- [Middleware](https://docs.guzzlephp.org/en/stable/handlers-and-middleware.html)