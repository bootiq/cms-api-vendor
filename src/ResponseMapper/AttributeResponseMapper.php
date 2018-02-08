<?php

namespace BootIq\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Attribute;

class AttributeResponseMapper
{

    /**
     * @param array $responseData
     * @return array|Attribute[]
     */
    public function map(array $responseData): array
    {
        $result = [];
        foreach ($responseData as $key => $value) {
            $result[] = new Attribute($key, $value);
        }
        return $result;
    }
}
