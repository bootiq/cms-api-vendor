<?php

namespace BootIq\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Metadata;

class MetadataResponseMapper
{

    /**
     * @param array $responseData
     * @return array|Metadata[]
     */
    public function map(array $responseData): array
    {
        $result = [];
        foreach ($responseData as $key => $value) {
            $result[] = new Metadata($key, $value);
        }
        return $result;
    }
}
