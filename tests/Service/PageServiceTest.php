<?php

namespace BootIqTest\CmsApiVendor\Service;

use BootIq\CmsApiVendor\Adapter\AdapterInterface;
use BootIq\CmsApiVendor\DataObject\Page;
use BootIq\CmsApiVendor\Exception\ErrorResponseException;
use BootIq\CmsApiVendor\Request\Page\GetPageBySlugRequest;
use BootIq\CmsApiVendor\Request\Page\GetPageRequest;
use BootIq\CmsApiVendor\Request\Page\PageRequestFactory;
use BootIq\CmsApiVendor\Response\ResponseInterface;
use BootIq\CmsApiVendor\ResponseMapper\PageResponseMapper;
use BootIq\CmsApiVendor\Service\PageService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageServiceTest extends TestCase
{

    /**
     * @var PageRequestFactory|MockObject
     */
    private $pageRequestFactory;

    /**
     * @var AdapterInterface|MockObject
     */
    private $adapter;

    /**
     * @var PageResponseMapper|MockObject
     */
    private $pageResponseMapper;

    /**
     * @var PageService
     */
    private $instance;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->pageRequestFactory = $this->createMock(PageRequestFactory::class);
        $this->adapter = $this->createMock(AdapterInterface::class);
        $this->pageResponseMapper = $this->createMock(PageResponseMapper::class);

        $this->instance = new PageService(
            $this->pageRequestFactory,
            $this->adapter,
            $this->pageResponseMapper
        );
    }

    public function testPageByIdSuccess()
    {
        $pageId = rand(10, 9999);
        $useCache = true;
        $arrayData = [uniqid() => uniqid()];

        $request = $this->createMock(GetPageRequest::class);
        $response = $this->createMock(ResponseInterface::class);
        $page = $this->createMock(Page::class);

        $this->pageRequestFactory->expects(self::once())
            ->method('createGetPageRequest')
            ->with($pageId)
            ->willReturn($request);
        $request->expects(self::once())
            ->method('setCacheable')
            ->with($useCache);
        $this->adapter->expects(self::once())
            ->method('call')
            ->with($request)
            ->willReturn($response);
        $response->expects(self::once())
            ->method('isError')
            ->willReturn(false);
        $response->expects(self::once())
            ->method('getResponseData')
            ->willReturn(\GuzzleHttp\json_encode($arrayData));
        $this->pageResponseMapper->expects(self::once())
            ->method('map')
            ->with($arrayData)
            ->willReturn($page);

        $result = $this->instance->getPageById($pageId, $useCache);
        $this->assertEquals($page, $result);
    }

    public function testPageByIdError()
    {
        $pageId = rand(10, 9999);
        $useCache = true;

        $request = $this->createMock(GetPageRequest::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->pageRequestFactory->expects(self::once())
            ->method('createGetPageRequest')
            ->with($pageId)
            ->willReturn($request);
        $request->expects(self::once())
            ->method('setCacheable')
            ->with($useCache);
        $this->adapter->expects(self::once())
            ->method('call')
            ->with($request)
            ->willReturn($response);
        $response->expects(self::once())
            ->method('isError')
            ->willReturn(true);

        $this->expectException(ErrorResponseException::class);
        $this->instance->getPageById($pageId, $useCache);
    }

    public function testPageBySlugSuccess()
    {
        $pageSlug = rand(10, 9999);
        $useCache = true;
        $arrayData = [uniqid() => uniqid()];

        $request = $this->createMock(GetPageBySlugRequest::class);
        $response = $this->createMock(ResponseInterface::class);
        $page = $this->createMock(Page::class);

        $this->pageRequestFactory->expects(self::once())
            ->method('createGetPageBySlugRequest')
            ->with($pageSlug)
            ->willReturn($request);
        $request->expects(self::once())
            ->method('setCacheable')
            ->with($useCache);
        $this->adapter->expects(self::once())
            ->method('call')
            ->with($request)
            ->willReturn($response);
        $response->expects(self::once())
            ->method('isError')
            ->willReturn(false);
        $response->expects(self::once())
            ->method('getResponseData')
            ->willReturn(\GuzzleHttp\json_encode($arrayData));
        $this->pageResponseMapper->expects(self::once())
            ->method('map')
            ->with($arrayData)
            ->willReturn($page);

        $result = $this->instance->getPageBySlug($pageSlug, $useCache);
        $this->assertEquals($page, $result);
    }

    public function testPageBySlugError()
    {
        $pageSlug = rand(10, 9999);
        $useCache = true;

        $request = $this->createMock(GetPageBySlugRequest::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->pageRequestFactory->expects(self::once())
            ->method('createGetPageBySlugRequest')
            ->with($pageSlug)
            ->willReturn($request);
        $request->expects(self::once())
            ->method('setCacheable')
            ->with($useCache);
        $this->adapter->expects(self::once())
            ->method('call')
            ->with($request)
            ->willReturn($response);
        $response->expects(self::once())
            ->method('isError')
            ->willReturn(true);

        $this->expectException(ErrorResponseException::class);
        $this->instance->getPageBySlug($pageSlug, $useCache);
    }
}
