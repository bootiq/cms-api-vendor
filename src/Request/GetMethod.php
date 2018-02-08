<?php

namespace BootIq\CmsApiVendor\Request;

use BootIq\CmsApiVendor\Enum\HttpMethod;

abstract class GetMethod implements RequestInterface
{

    /**
     * @var bool
     */
    protected $cacheable = true;

    /**
     * @return bool
     */
    public function isCacheable(): bool
    {
        return $this->cacheable;
    }

    /**
     * @param bool $cacheable
     */
    public function setCacheable(bool $cacheable)
    {
        $this->cacheable = $cacheable;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return HttpMethod::METHOD_GET;
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        return null;
    }
}
