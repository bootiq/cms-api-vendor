<?php

namespace BootIqTest\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Setting;
use BootIq\CmsApiVendor\ResponseMapper\SettingResponseMapper;
use PHPUnit\Framework\TestCase;

class SettingResponseMapperTest extends TestCase
{

    /**
     * @var SettingResponseMapper
     */
    private $instance;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $this->instance = new SettingResponseMapper();
    }

    public function testMapper()
    {
        $type = uniqid();
        $dataArray = [uniqid() => uniqid()];
        $data = [
            'type' => $type,
            'data' => $dataArray,
        ];

        $result = $this->instance->map($data);
        $this->assertInstanceOf(Setting::class, $result);
        $this->assertEquals($type, $result->getType());
        $this->assertEquals($dataArray, $result->getData());
    }

    public function testFromBlockMapper()
    {
        $this->assertEmpty($this->instance->mapFromBlock([]));
        $this->assertEmpty($this->instance->mapFromBlock([uniqid() => uniqid()]));
        $this->assertEmpty($this->instance->mapFromBlock(['settings' => uniqid()]));

        $type = uniqid();
        $dataArray = [uniqid() => uniqid()];
        $data = [
            'settings' => [
                [
                    'type' => $type,
                    'data' => $dataArray,
                ],
            ],
        ];

        $result = $this->instance->mapFromBlock($data);
        $result = current($result);
        $this->assertInstanceOf(Setting::class, $result);
        $this->assertEquals($type, $result->getType());
        $this->assertEquals($dataArray, $result->getData());
    }
}
