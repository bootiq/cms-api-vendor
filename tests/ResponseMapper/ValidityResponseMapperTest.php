<?php

namespace BootIqTest\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Validity;
use BootIq\CmsApiVendor\ResponseMapper\ValidityResponseMapper;
use PHPUnit\Framework\TestCase;

class ValidityResponseMapperTest extends TestCase
{

    /**
     * @var ValidityResponseMapper
     */
    private $instance;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $this->instance = new ValidityResponseMapper();
    }

    public function testMapAll()
    {
        $issueDate = (new \DateTime())->modify('-1 month');
        $expireDate = (new \DateTime());

        $result = $this->instance->map([]);
        $this->assertInstanceOf(Validity::class, $result);
        $this->assertNull($result->getExpireDate());
        $this->assertNull($result->getIssueDate());

        $result = $this->instance->map([
            'issueDate' => $issueDate->format(DATE_ISO8601),
        ]);
        $this->assertInstanceOf(Validity::class, $result);
        $this->assertNull($result->getExpireDate());
        $this->assertEquals(
            $issueDate->format(DATE_ISO8601),
            $result->getIssueDate()->format(DATE_ISO8601)
        );

        $result = $this->instance->map([
            'expireDate' => $expireDate->format(DATE_ISO8601),
        ]);
        $this->assertInstanceOf(Validity::class, $result);
        $this->assertNull($result->getIssueDate());
        $this->assertEquals(
            $expireDate->format(DATE_ISO8601),
            $result->getExpireDate()->format(DATE_ISO8601)
        );

        $result = $this->instance->map([
            'issueDate' => $issueDate->format(DATE_ISO8601),
            'expireDate' => $expireDate->format(DATE_ISO8601),
        ]);
        $this->assertInstanceOf(Validity::class, $result);
        $this->assertEquals(
            $issueDate->format(DATE_ISO8601),
            $result->getIssueDate()->format(DATE_ISO8601)
        );
        $this->assertEquals(
            $expireDate->format(DATE_ISO8601),
            $result->getExpireDate()->format(DATE_ISO8601)
        );
    }

    public function testMapFromPage()
    {
        $issueDate = (new \DateTime())->modify('-1 month');
        $expireDate = (new \DateTime());

        $result = $this->instance->mapFromPage([]);
        $this->assertInstanceOf(Validity::class, $result);
        $this->assertNull($result->getExpireDate());
        $this->assertNull($result->getIssueDate());

        $result = $this->instance->mapFromPage([uniqid() => uniqid()]);
        $this->assertInstanceOf(Validity::class, $result);
        $this->assertNull($result->getExpireDate());
        $this->assertNull($result->getIssueDate());

        $result = $this->instance->mapFromPage([
            'validity' => [
                'issueDate' => $issueDate->format(DATE_ISO8601),
                'expireDate' => $expireDate->format(DATE_ISO8601),
            ]
        ]);
        $this->assertInstanceOf(Validity::class, $result);
        $this->assertEquals(
            $issueDate->format(DATE_ISO8601),
            $result->getIssueDate()->format(DATE_ISO8601)
        );
        $this->assertEquals(
            $expireDate->format(DATE_ISO8601),
            $result->getExpireDate()->format(DATE_ISO8601)
        );
    }
}
