<?php

namespace BootIqTest\CmsApiVendor\Request\Page;

use BootIq\CmsApiVendor\Request\Page\GetPageRequest;
use PHPUnit\Framework\TestCase;

class GetPageRequestTest extends TestCase
{

    public function testAll()
    {
        $pageId = rand(10, 9999);

        $instance = new GetPageRequest($pageId);

        $this->assertTrue($instance->isCacheable());
        $instance->setCacheable(false);
        $this->assertFalse($instance->isCacheable());
        $this->assertEmpty($instance->getData());

        $this->assertEquals('page/' . $pageId, $instance->getEndpoint());
    }
}
