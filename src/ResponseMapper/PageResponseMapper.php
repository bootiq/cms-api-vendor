<?php

namespace BootIq\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Page;

class PageResponseMapper
{

    /**
     * @var LayoutResponseMapper
     */
    private $layoutResponseMapper;

    /**
     * @var ValidityResponseMapper
     */
    private $validityResponseMapper;

    /**
     * @var MetadataResponseMapper
     */
    private $metadataResponseMapper;

    /**
     * PageResponseMapper constructor.
     * @param LayoutResponseMapper $layoutResponseMapper
     * @param ValidityResponseMapper $validityResponseMapper
     * @param MetadataResponseMapper $metadataResponseMapper
     */
    public function __construct(
        LayoutResponseMapper $layoutResponseMapper,
        ValidityResponseMapper $validityResponseMapper,
        MetadataResponseMapper $metadataResponseMapper
    ) {
        $this->layoutResponseMapper = $layoutResponseMapper;
        $this->validityResponseMapper = $validityResponseMapper;
        $this->metadataResponseMapper = $metadataResponseMapper;
    }

    /**
     * @param array $responseData
     * @return Page
     */
    public function map(array $responseData): Page
    {
        $layout = $this->layoutResponseMapper->mapFromPage($responseData);

        $page = new Page(
            $responseData['id'],
            $layout,
            $responseData['author'],
            new \DateTime($responseData['created']),
            new \DateTime($responseData['updated'])
        );
        $page->setValidity($this->validityResponseMapper->mapFromPage($responseData));
        $page->setMetadata($this->parseMetadata($responseData));

        return $page;
    }

    /**
     * @param array $responseData
     * @return array
     */
    private function parseMetadata(array $responseData): array
    {
        $result = [];
        if (!isset($responseData['metadata'])) {
            return $result;
        }

        return $this->metadataResponseMapper->map($responseData['metadata']);
    }
}
