<?php

namespace CryptoMarkets\Tests;

use Mockery as m;
use ReflectionObject;
use CryptoMarkets\Common\Client;
use Http\Mock\Client as MockClient;
use Psr\Http\Message\ResponseInterface;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * The HTTP client instance.
     *
     * @var \CryptoMarkets\Common\Client
     */
    protected $httpClient;

    /**
     * The HTTP mock client instance.
     *
     * @var \Http\Mock\Client
     */
    protected $mockClient;

    /**
     * Tears down the fixture.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Set a mock response for next client request.
     *
     * @param  string  $paths
     * @return void
     */
    public function setMockHttpResponse($path)
    {
        $this->getMockClient()->addResponse($this->getHttpResponse($path));
    }

    /**
     * Get a mock response for a client by mock file name
     *
     * @param  mixed  $path
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHttpResponse($path)
    {
        if ($path instanceof ResponseInterface) {
            return $path;
        }

        $ref = new ReflectionObject($this);
        $dir = dirname($ref->getFileName());

        if (!file_exists($dir.'/Mock/'.$path) && file_exists($dir.'/../Mock/'.$path)) {
            return \GuzzleHttp\Psr7\parse_response(file_get_contents($dir.'/../Mock/'.$path));
        }

        return \GuzzleHttp\Psr7\parse_response(file_get_contents($dir.'/Mock/'.$path));
    }

    /**
     * Get a instance of the HTTP mock client.
     *
     * @return \Http\Mock\Client
     */
    public function getMockClient()
    {
        if (null === $this->mockClient) {
            $this->mockClient = new MockClient;
        }

        return $this->mockClient;
    }

    /**
     * Get a instance of the HTTP client.
     *
     * @return \CryptoMarkets\Common\Client
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new Client(
                $this->getMockClient()
            );
        }

        return $this->httpClient;
    }
}
