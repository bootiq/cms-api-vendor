<?php

namespace BootIqTest\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Attribute;
use BootIq\CmsApiVendor\DataObject\Block;
use BootIq\CmsApiVendor\DataObject\Setting;
use BootIq\CmsApiVendor\ResponseMapper\AttributeResponseMapper;
use BootIq\CmsApiVendor\ResponseMapper\BlockResponseMapper;
use BootIq\CmsApiVendor\ResponseMapper\SettingResponseMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BlockResponseMapperTest extends TestCase
{

    /**
     * @var AttributeResponseMapper|MockObject
     */
    private $attributeResponseMapper;

    /**
     * @var SettingResponseMapper|MockObject
     */
    private $settingResponseMapper;

    /**
     * @var BlockResponseMapper
     */
    private $instance;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->attributeResponseMapper = $this->createMock(AttributeResponseMapper::class);
        $this->settingResponseMapper = $this->createMock(SettingResponseMapper::class);

        $this->instance = new BlockResponseMapper(
            $this->attributeResponseMapper,
            $this->settingResponseMapper
        );
    }

    public function testMapper()
    {
        $code = uniqid();
        $type = uniqid();
        $content = uniqid();
        $attributeKey = uniqid();
        $attributeValue = uniqid();
        $attributeArray = [$attributeKey => $attributeValue];

        $data = [
            'code' => $code,
            'type' => $type,
        ];

        $attribute = $this->createMock(Attribute::class);
        $setting = $this->createMock(Setting::class);

        $result = $this->instance->map($data);
        $this->assertInstanceOf(Block::class, $result);
        $this->assertEquals($code, $result->getCode());
        $this->assertEquals($type, $result->getType());
        $this->assertEmpty($result->getContent());
        $this->assertEmpty($result->getAttributes());
        $this->assertEmpty($result->getSettings());

        $data['content'] = $content;
        $result = $this->instance->map($data);
        $this->assertInstanceOf(Block::class, $result);
        $this->assertEquals($code, $result->getCode());
        $this->assertEquals($type, $result->getType());
        $this->assertEquals($content, $result->getContent());
        $this->assertEmpty($result->getAttributes());
        $this->assertEmpty($result->getSettings());

        $this->attributeResponseMapper->expects(self::exactly(2))
            ->method('map')
            ->with($attributeArray)
            ->willReturn([$attribute]);
        $attribute->expects(self::exactly(2))
            ->method('getKey')
            ->willReturn($attributeKey);

        $data['attributes'] = $attributeArray;
        $result = $this->instance->map($data);
        $this->assertInstanceOf(Block::class, $result);
        $this->assertEquals($code, $result->getCode());
        $this->assertEquals($type, $result->getType());
        $this->assertEquals($content, $result->getContent());
        $this->assertEquals([$attributeKey => $attribute], $result->getAttributes());
        $this->assertEquals($attribute, $result->getAttribute($attributeKey));
        $this->assertEmpty($result->getSettings());

        $this->settingResponseMapper->expects(self::once())
            ->method('mapFromBlock')
            ->willReturn([$setting]);

        $data['settings'] = [[$attributeKey => $attributeValue]];
        $result = $this->instance->map($data);
        $this->assertInstanceOf(Block::class, $result);
        $this->assertEquals($code, $result->getCode());
        $this->assertEquals($type, $result->getType());
        $this->assertEquals($content, $result->getContent());
        $this->assertEquals([$attributeKey => $attribute], $result->getAttributes());
        $this->assertEquals($attribute, $result->getAttribute($attributeKey));
        $this->assertContains($setting, $result->getSettings());
    }

    public function testMapperFromLayout()
    {
        $this->assertEmpty($this->instance->mapFromLayout([]));
        $this->assertEmpty($this->instance->mapFromLayout([uniqid() => '']));

        $code = uniqid();
        $type = uniqid();
        $content = uniqid();

        $data = [
            uniqid() => uniqid(),
            'blocks' => [
                [
                    'code' => $code,
                    'type' => $type,
                ]
            ]
        ];

        $result = $this->instance->mapFromLayout($data);
        $result = current($result);
        $this->assertInstanceOf(Block::class, $result);
        $this->assertEquals($code, $result->getCode());
        $this->assertEquals($type, $result->getType());
        $this->assertEmpty($result->getContent());
        $this->assertEmpty($result->getAttributes());
        $this->assertEmpty($result->getSettings());

        $data['blocks'][0]['content'] = $content;
        $result = $this->instance->mapFromLayout($data);
        $result = current($result);
        $this->assertInstanceOf(Block::class, $result);
        $this->assertEquals($code, $result->getCode());
        $this->assertEquals($type, $result->getType());
        $this->assertEquals($content, $result->getContent());
        $this->assertEmpty($result->getAttributes());
        $this->assertEmpty($result->getSettings());
    }
}
