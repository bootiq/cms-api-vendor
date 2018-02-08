<?php

namespace BootIq\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Layout;

class LayoutResponseMapper
{

    /**
     * @var BlockResponseMapper
     */
    private $blockResponseMapper;

    /**
     * LayoutResponseMapper constructor.
     * @param BlockResponseMapper $blockResponseMapper
     */
    public function __construct(BlockResponseMapper $blockResponseMapper)
    {
        $this->blockResponseMapper = $blockResponseMapper;
    }

    /**
     * @param array $responseData
     * @return Layout
     */
    public function mapFromPage(array $responseData): Layout
    {
        return $this->map($responseData['layout']);
    }

    /**
     * @param array $responseData
     * @return Layout
     */
    public function map(array $responseData): Layout
    {
        $layout = new Layout(
            $responseData['code']
        );

        if (isset($responseData['required_components'])) {
            $layout->setRequiredComponents($responseData['required_components']);
        }

        $layout->setBlocks(
            $this->blockResponseMapper->mapFromLayout($responseData)
        );

        return $layout;
    }
}
