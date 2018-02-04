<?php

namespace CryptoMarkets\Tests;

abstract class GatewayTestCase extends TestCase
{
    public function testGetNameNotEmpty()
    {
        $name = $this->gateway->getName();

        $this->assertNotEmpty($name);

        $this->assertInternalType('string', $name);
    }

    public function testGetDefaultOptionsBeArray()
    {
        $options = $this->gateway->getDefaultOptions();

        $this->assertInternalType('array', $options);
    }
}
