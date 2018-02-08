<?php

namespace BootIq\CmsApiVendor\Service;

use BootIq\CmsApiVendor\Adapter\AdapterInterface;
use BootIq\CmsApiVendor\DataObject\Page;
use BootIq\CmsApiVendor\Exception\ErrorResponseException;
use BootIq\CmsApiVendor\Request\Page\PageRequestFactory;
use BootIq\CmsApiVendor\ResponseMapper\PageResponseMapper;

class PageService
{

    /**
     * @var PageRequestFactory
     */
    private $pageRequestFactory;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var PageResponseMapper
     */
    private $pageResponseMapper;

    /**
     * PageService constructor.
     * @param PageRequestFactory $pageRequestFactory
     * @param AdapterInterface $adapter
     * @param PageResponseMapper $pageResponseMapper
     */
    public function __construct(
        PageRequestFactory $pageRequestFactory,
        AdapterInterface $adapter,
        PageResponseMapper $pageResponseMapper
    ) {
        $this->pageRequestFactory = $pageRequestFactory;
        $this->adapter = $adapter;
        $this->pageResponseMapper = $pageResponseMapper;
    }

    /**
     * @param int $pageId
     * @param bool $useCache
     * @return Page
     * @throws ErrorResponseException
     */
    public function getPageById(int $pageId, bool $useCache): Page
    {
        $request = $this->pageRequestFactory->createGetPageRequest($pageId);
        $request->setCacheable($useCache);
        $response = $this->adapter->call($request);

        if ($response->isError()) {
            throw new ErrorResponseException($response->getResponseData(), $response->getHttpCode());
        }

        $responseData = \GuzzleHttp\json_decode($response->getResponseData(), true);
        return $this->pageResponseMapper->map($responseData);
    }

    /**
     * @param string $pageSlug
     * @param bool $useCache
     * @return Page
     * @throws ErrorResponseException
     */
    public function getPageBySlug(string $pageSlug, bool $useCache): Page
    {
        $request = $this->pageRequestFactory->createGetPageBySlugRequest($pageSlug);
        $request->setCacheable($useCache);
        $response = $this->adapter->call($request);

        if ($response->isError()) {
            throw new ErrorResponseException($response->getResponseData(), $response->getHttpCode());
        }

        $responseData = \GuzzleHttp\json_decode($response->getResponseData(), true);
        return $this->pageResponseMapper->map($responseData);
    }
}
