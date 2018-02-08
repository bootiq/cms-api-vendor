<?php

namespace BootIq\CmsApiVendor\Request;

interface RequestInterface
{

    /**
     * @return bool
     */
    public function isCacheable(): bool;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return string
     */
    public function getEndpoint(): string;

    /**
     * @return array|null
     */
    public function getData();
}
