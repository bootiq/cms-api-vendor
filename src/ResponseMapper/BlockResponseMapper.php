<?php

namespace BootIq\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Block;

class BlockResponseMapper
{

    /**
     * @var AttributeResponseMapper
     */
    private $attributeResponseMapper;

    /**
     * @var SettingResponseMapper
     */
    private $settingResponseMapper;

    /**
     * BlockResponseMapper constructor.
     * @param AttributeResponseMapper $attributeResponseMapper
     * @param SettingResponseMapper $settingResponseMapper
     */
    public function __construct(
        AttributeResponseMapper $attributeResponseMapper,
        SettingResponseMapper $settingResponseMapper
    ) {
        $this->attributeResponseMapper = $attributeResponseMapper;
        $this->settingResponseMapper = $settingResponseMapper;
    }

    /**
     * @param array $responseData
     * @return array
     */
    public function mapFromLayout(array $responseData): array
    {
        if (!isset($responseData['blocks'])) {
            return [];
        }

        $result = [];
        foreach ($responseData['blocks'] as $block) {
            $result[] = $this->map($block);
        }

        return $result;
    }

    /**
     * @param array $responseData
     * @return Block
     */
    public function map(array $responseData): Block
    {
        $block = new Block(
            $responseData['code'],
            $responseData['type']
        );

        if (isset($responseData['content'])) {
            $block->setContent($responseData['content']);
        }
        if (isset($responseData['attributes'])) {
            $block->setAttributes(
                $this->attributeResponseMapper->map($responseData['attributes'])
            );
        }
        if (isset($responseData['settings'])) {
            $block->setSettings(
                $this->settingResponseMapper->mapFromBlock($responseData)
            );
        }

        return $block;
    }
}
