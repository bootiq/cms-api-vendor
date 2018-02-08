<?php

namespace BootIq\CmsApiVendor\Request\Page;

use BootIq\CmsApiVendor\Request\GetMethod;

class GetPageRequest extends GetMethod
{

    /**
     * @var int
     */
    private $pageId;

    /**
     * GetPageRequest constructor.
     * @param int $pageId
     */
    public function __construct(int $pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return 'page/' . $this->pageId;
    }
}
