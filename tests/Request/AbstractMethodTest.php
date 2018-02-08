<?php

namespace BootIqTest\CmsApiVendor\Request;

use BootIq\CmsApiVendor\Enum\HttpMethod;
use BootIq\CmsApiVendor\Request\GetMethod;
use BootIq\CmsApiVendor\Request\HeadMethod;
use BootIq\CmsApiVendor\Request\PostMethod;
use BootIq\CmsApiVendor\Request\PutMethod;
use PHPUnit\Framework\TestCase;

class AbstractMethodTest extends TestCase
{

    public function testGetMethod()
    {
        $class = new class extends GetMethod {

            /**
             * @return string
             */
            public function getEndpoint(): string
            {
                return '';
            }
        };

        $this->assertEquals(HttpMethod::METHOD_GET, $class->getMethod());
        $this->assertEmpty($class->getData());
        $this->assertTrue($class->isCacheable());
        $class->setCacheable(false);
        $this->assertFalse($class->isCacheable());
        $this->assertEmpty($class->getEndpoint());
    }

    public function testHeadMethod()
    {
        $class = new class extends HeadMethod {

            /**
             * @return bool
             */
            public function isCacheable(): bool
            {
                return false;
            }

            /**
             * @return string
             */
            public function getEndpoint(): string
            {
                return '';
            }
        };

        $this->assertEquals(HttpMethod::METHOD_HEAD, $class->getMethod());
        $this->assertEmpty($class->getData());
        $this->assertEmpty($class->getEndpoint());
        $this->assertFalse($class->isCacheable());
    }

    public function testPostMethod()
    {
        $class = new class extends PostMethod {

            /**
             * @return bool
             */
            public function isCacheable(): bool
            {
                return false;
            }

            /**
             * @return string
             */
            public function getEndpoint(): string
            {
                return '';
            }

            /**
             * @return array|null
             */
            public function getData()
            {
                return null;
            }
        };

        $this->assertEquals(HttpMethod::METHOD_POST, $class->getMethod());
        $this->assertEmpty($class->getEndpoint());
        $this->assertEmpty($class->getData());
        $this->assertFalse($class->isCacheable());
    }

    public function testPutMethod()
    {
        $class = new class extends PutMethod {

            /**
             * @return bool
             */
            public function isCacheable(): bool
            {
                return false;
            }

            /**
             * @return string
             */
            public function getEndpoint(): string
            {
                return '';
            }

            /**
             * @return array|null
             */
            public function getData()
            {
                return null;
            }
        };

        $this->assertEquals(HttpMethod::METHOD_PUT, $class->getMethod());
        $this->assertEmpty($class->getEndpoint());
        $this->assertEmpty($class->getData());
        $this->assertFalse($class->isCacheable());
    }
}