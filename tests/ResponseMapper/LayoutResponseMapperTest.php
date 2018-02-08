<?php

namespace BootIqTest\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Block;
use BootIq\CmsApiVendor\DataObject\Layout;
use BootIq\CmsApiVendor\ResponseMapper\BlockResponseMapper;
use BootIq\CmsApiVendor\ResponseMapper\LayoutResponseMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LayoutResponseMapperTest extends TestCase
{

    /**
     * @var LayoutResponseMapper
     */
    private $instance;

    /**
     * @var BlockResponseMapper|MockObject
     */
    private $blockResponseMapper;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->blockResponseMapper = $this->createMock(BlockResponseMapper::class);

        $this->instance = new LayoutResponseMapper(
            $this->blockResponseMapper
        );
    }

    public function testMapper()
    {
        $code = uniqid();
        $blockCode = uniqid();
        $requiredComponents = [uniqid(), uniqid()];
        $data = [
            'code' => $code,
            'required_components' => $requiredComponents,
        ];

        $block = $this->createMock(Block::class);

        $this->blockResponseMapper->expects(self::once())
            ->method('mapFromLayout')
            ->with($data)
            ->willReturn([$block]);
        $block->expects(self::once())
            ->method('getCode')
            ->willReturn($blockCode);

        $result = $this->instance->map($data);
        $this->assertInstanceOf(Layout::class, $result);
        $this->assertEquals($code, $result->getCode());
        $this->assertEquals($requiredComponents, $result->getRequiredComponents());
        $this->assertEquals([$blockCode => $block], $result->getBlocks());
        $this->assertEquals($block, $result->getBlock($blockCode));
    }

    public function testEmptyComponents()
    {
        $code = uniqid();
        $blockCode = uniqid();
        $requiredComponents = [uniqid(), uniqid()];
        $data = [
            'code' => $code,
        ];

        $block = $this->createMock(Block::class);

        $this->blockResponseMapper->expects(self::once())
            ->method('mapFromLayout')
            ->with($data)
            ->willReturn([$block]);
        $block->expects(self::once())
            ->method('getCode')
            ->willReturn($blockCode);

        $result = $this->instance->map($data);
        $this->assertInstanceOf(Layout::class, $result);
        $this->assertEquals($code, $result->getCode());
        $this->assertEmpty($result->getRequiredComponents());
        $this->assertEquals([$blockCode => $block], $result->getBlocks());
        $this->assertEquals($block, $result->getBlock($blockCode));
    }
}
