<?php

namespace Elantha\Api\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseApiTestCase extends TestCase
{
    /** @var \Illuminate\Foundation\Application */
    protected $app;

    protected function getPackageProviders($app): array
    {
        return [
            \Elantha\Api\Providers\ApiServiceProvider::class,
            Providers\RestServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Exceptions\Handler::class
        );
    }

    public function setUp()
    {
        parent::setUp();

        $this->setUpRoutes();
    }

    protected function setUpRoutes()
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $class = new \ReflectionClass(Controllers\TestController::class);
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if (Str::is('test*', $method->getName())) {
                $router->post('test/' . $method->getName(), $class->getName() . '@' . $method->getName());
            }
        }
    }

    protected function invokeRequest(string $sourceUrl, array $params = []): Response
    {
        $request = Request::create('test/' . $sourceUrl, 'POST', $params);

        return $this->app->handle($request);
    }
}
