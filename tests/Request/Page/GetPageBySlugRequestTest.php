<?php

namespace BootIqTest\CmsApiVendor\Request\Page;

use BootIq\CmsApiVendor\Request\Page\GetPageRequest;
use BootIq\CmsApiVendor\Request\Page\GetPageBySlugRequest;
use PHPUnit\Framework\TestCase;

class GetPageBySlugRequestTest extends TestCase
{

    public function testAll()
    {
        $pageSlug = uniqid();

        $instance = new GetPageBySlugRequest($pageSlug);

        $this->assertTrue($instance->isCacheable());
        $instance->setCacheable(false);
        $this->assertFalse($instance->isCacheable());
        $this->assertEmpty($instance->getData());

        $this->assertEquals('page?slug=' . $pageSlug, $instance->getEndpoint());
    }
}
