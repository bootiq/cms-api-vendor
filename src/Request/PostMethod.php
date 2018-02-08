<?php

namespace BootIq\CmsApiVendor\Request;

use BootIq\CmsApiVendor\Enum\HttpMethod;

abstract class PostMethod implements RequestInterface
{

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
    public function getMethod(): string
    {
        return HttpMethod::METHOD_POST;
    }
}
