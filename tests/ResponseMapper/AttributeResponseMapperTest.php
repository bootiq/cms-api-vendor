<?php

namespace BootIqTest\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\ResponseMapper\AttributeResponseMapper;
use PHPUnit\Framework\TestCase;

class AttributeResponseMapperTest extends TestCase
{

    /**
     * @var AttributeResponseMapper
     */
    private $instance;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $this->instance = new AttributeResponseMapper();
    }

    public function testMapEmptyData()
    {
        $responseData = [];
        $this->assertEmpty($this->instance->map($responseData));
    }

    public function testMap()
    {
        $item1 = [uniqid() => uniqid()];
        $item2 = [uniqid() => uniqid()];
        $responseData = array_merge($item1, $item2);

        $result = $this->instance->map($responseData);
        $this->assertCount(count($responseData), $result);
        foreach ($responseData as $key => $value) {
            $found = false;
            foreach ($result as $item) {
                if ($item->getKey() === $key) {
                    $this->assertEquals($value, $item->getValue());
                    $found = true;
                }
            }
            $this->assertTrue($found);
        }
    }
}
