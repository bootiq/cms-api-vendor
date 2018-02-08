<?php

namespace BootIqTest\CmsApiVendor\Request\Page;

use BootIq\CmsApiVendor\Request\Page\GetPageRequest;
use BootIq\CmsApiVendor\Request\Page\GetPageBySlugRequest;
use BootIq\CmsApiVendor\Request\Page\PageRequestFactory;
use PHPUnit\Framework\TestCase;

class PageRequestFactoryTest extends TestCase
{

    /**
     * @var PageRequestFactory
     */
    private $factory;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $this->factory = new PageRequestFactory();
    }

    public function testGetPageRequestCreator()
    {
        $pageId = rand(10, 9999);
        $request = $this->factory->createGetPageRequest($pageId);
        $this->assertInstanceOf(GetPageRequest::class, $request);
    }

    public function testGetPageBySlugRequestCreator()
    {
        $pageSlug = uniqid();
        $request = $this->factory->createGetPageBySlugRequest($pageSlug);
        $this->assertInstanceOf(GetPageBySlugRequest::class, $request);
    }
}
