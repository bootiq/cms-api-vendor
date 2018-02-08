<?php

namespace BootIqTest\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Layout;
use BootIq\CmsApiVendor\DataObject\Metadata;
use BootIq\CmsApiVendor\DataObject\Page;
use BootIq\CmsApiVendor\DataObject\Validity;
use BootIq\CmsApiVendor\ResponseMapper\LayoutResponseMapper;
use BootIq\CmsApiVendor\ResponseMapper\MetadataResponseMapper;
use BootIq\CmsApiVendor\ResponseMapper\PageResponseMapper;
use BootIq\CmsApiVendor\ResponseMapper\ValidityResponseMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageResponseMapperTest extends TestCase
{

    /**
     * @var LayoutResponseMapper|MockObject
     */
    private $layoutResponseMapper;

    /**
     * @var ValidityResponseMapper|MockObject
     */
    private $validityResponseMapper;

    /**
     * @var MetadataResponseMapper|MockObject
     */
    private $metadataResponseMapper;

    /**
     * @var PageResponseMapper
     */
    private $instance;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->layoutResponseMapper = $this->createMock(LayoutResponseMapper::class);
        $this->validityResponseMapper = $this->createMock(ValidityResponseMapper::class);
        $this->metadataResponseMapper = $this->createMock(MetadataResponseMapper::class);

        $this->instance = new PageResponseMapper(
            $this->layoutResponseMapper,
            $this->validityResponseMapper,
            $this->metadataResponseMapper
        );
    }

    public function testAll()
    {
        $id = rand(10, 99);
        $key = uniqid();
        $author = uniqid();
        $created = (new \DateTime())->modify('-1 month');
        $updated = (new \DateTime());
        $data = [
            'id' => $id,
            'author' => $author,
            'created' => $created->format(DATE_ISO8601),
            'updated' => $updated->format(DATE_ISO8601),
            'metadata' => [],
        ];

        $layout = $this->createMock(Layout::class);
        $validity = $this->createMock(Validity::class);
        $metadata = $this->createMock(Metadata::class);

        $this->layoutResponseMapper->expects(self::once())
            ->method('mapFromPage')
            ->with($data)
            ->willReturn($layout);
        $this->validityResponseMapper->expects(self::once())
            ->method('mapFromPage')
            ->with($data)
            ->willReturn($validity);
        $this->metadataResponseMapper->expects(self::once())
            ->method('map')
            ->with($data['metadata'])
            ->willReturn([$metadata]);
        $metadata->expects(self::once())
            ->method('getKey')
            ->willReturn($key);

        $result = $this->instance->map($data);
        $this->assertInstanceOf(Page::class, $result);
        $this->assertEquals($id, $result->getId());
        $this->assertEquals($author, $result->getAuthor());
        $this->assertEquals($created->format(DATE_ISO8601), $result->getCreated()->format(DATE_ISO8601));
        $this->assertEquals($updated->format(DATE_ISO8601), $result->getUpdated()->format(DATE_ISO8601));
        $this->assertEquals($layout, $result->getLayout());
        $this->assertEquals($validity, $result->getValidity());
        $this->assertEquals([$key => $metadata], $result->getMetadata());
        $this->assertEquals($metadata, $result->getMetadataItem($key));
    }

    public function testWithoutMetadata()
    {
        $id = rand(10, 99);
        $author = uniqid();
        $created = (new \DateTime())->modify('-1 month');
        $updated = (new \DateTime());
        $data = [
            'id' => $id,
            'author' => $author,
            'created' => $created->format(DATE_ISO8601),
            'updated' => $updated->format(DATE_ISO8601),
        ];

        $layout = $this->createMock(Layout::class);
        $validity = $this->createMock(Validity::class);

        $this->layoutResponseMapper->expects(self::once())
            ->method('mapFromPage')
            ->with($data)
            ->willReturn($layout);
        $this->validityResponseMapper->expects(self::once())
            ->method('mapFromPage')
            ->with($data)
            ->willReturn($validity);

        $result = $this->instance->map($data);
        $this->assertInstanceOf(Page::class, $result);
        $this->assertEquals($id, $result->getId());
        $this->assertEquals($author, $result->getAuthor());
        $this->assertEquals($created->format(DATE_ISO8601), $result->getCreated()->format(DATE_ISO8601));
        $this->assertEquals($updated->format(DATE_ISO8601), $result->getUpdated()->format(DATE_ISO8601));
        $this->assertEquals($layout, $result->getLayout());
        $this->assertEquals($validity, $result->getValidity());
        $this->assertEmpty($result->getMetadata());
    }
}
