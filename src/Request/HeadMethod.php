<?php

namespace BootIq\CmsApiVendor\Request;

use BootIq\CmsApiVendor\Enum\HttpMethod;

abstract class HeadMethod implements RequestInterface
{

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return HttpMethod::METHOD_HEAD;
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        return null;
    }
}
