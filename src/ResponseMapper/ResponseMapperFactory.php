<?php

namespace BootIq\CmsApiVendor\ResponseMapper;

class ResponseMapperFactory
{

    /**
     * @return AttributeResponseMapper
     */
    public function createAttributeResponseMapper(): AttributeResponseMapper
    {
        return new AttributeResponseMapper();
    }

    /**
     * @return BlockResponseMapper
     */
    public function createBlockResponseMapper(): BlockResponseMapper
    {
        return new BlockResponseMapper(
            $this->createAttributeResponseMapper(),
            $this->createSettingResponseMapper()
        );
    }

    /**
     * @return LayoutResponseMapper
     */
    public function createLayoutResponseMapper(): LayoutResponseMapper
    {
        return new LayoutResponseMapper(
            $this->createBlockResponseMapper()
        );
    }

    /**
     * @return MetadataResponseMapper
     */
    public function createMetadataResponseMapper(): MetadataResponseMapper
    {
        return new MetadataResponseMapper();
    }

    /**
     * @return PageResponseMapper
     */
    public function createPageResponseMapper(): PageResponseMapper
    {
        return new PageResponseMapper(
            $this->createLayoutResponseMapper(),
            $this->createValidityResponseMapper(),
            $this->createMetadataResponseMapper()
        );
    }

    /**
     * @return SettingResponseMapper
     */
    public function createSettingResponseMapper(): SettingResponseMapper
    {
        return new SettingResponseMapper();
    }

    /**
     * @return ValidityResponseMapper
     */
    public function createValidityResponseMapper(): ValidityResponseMapper
    {
        return new ValidityResponseMapper();
    }
}
