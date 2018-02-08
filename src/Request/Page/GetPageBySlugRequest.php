<?php

namespace BootIq\CmsApiVendor\Request\Page;

use BootIq\CmsApiVendor\Request\GetMethod;

class GetPageBySlugRequest extends GetMethod
{

    /**
     * @var string
     */
    private $pageSlug;

    /**
     * GetPageBySlugRequest constructor.
     * @param string $pageSlug
     */
    public function __construct(string $pageSlug)
    {
        $this->pageSlug = $pageSlug;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return 'page?slug=' . $this->pageSlug;
    }
}
