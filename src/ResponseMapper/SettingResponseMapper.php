<?php

namespace BootIq\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Setting;

class SettingResponseMapper
{

    /**
     * @param array $responseData
     * @return array|Setting[]
     */
    public function mapFromBlock(array $responseData): array
    {
        if (!isset($responseData['settings']) || !is_array($responseData['settings'])) {
            return [];
        }

        $result = [];
        foreach ($responseData['settings'] as $item) {
            $result[] = $this->map($item);
        }
        return $result;
    }

    /**
     * @param array $responseData
     * @return Setting
     */
    public function map(array $responseData): Setting
    {
        return new Setting($responseData['type'], $responseData['data']);
    }
}
