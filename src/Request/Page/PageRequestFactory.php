<?php

namespace BootIq\CmsApiVendor\Request\Page;

class PageRequestFactory
{

    /**
     * @param int $pageId
     * @return GetPageRequest
     */
    public function createGetPageRequest(int $pageId): GetPageRequest
    {
        return new GetPageRequest($pageId);
    }

    /**
     * @param string $pageSlug
     * @return GetPageBySlugRequest
     */
    public function createGetPageBySlugRequest(string $pageSlug): GetPageBySlugRequest
    {
        return new GetPageBySlugRequest($pageSlug);
    }
}
