<?php

namespace BootIqTest\CmsApiVendor\Adapter;

use BootIq\CmsApiVendor\Adapter\AdapterInterface;
use BootIq\CmsApiVendor\Adapter\GuzzleSecurityTokenAdapter;
use BootIq\CmsApiVendor\Enum\HttpCode;
use BootIq\CmsApiVendor\Enum\HttpHeader;
use BootIq\CmsApiVendor\Enum\HttpMethod;
use BootIq\CmsApiVendor\Request\RequestInterface;
use BootIq\CmsApiVendor\Response\ResponseFactoryInterface;
use BootIq\CmsApiVendor\Response\ResponseInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class GuzzleSecurityTokenAdapterCacheTest extends TestCase
{

    /**
     * @var ClientInterface|MockObject
     */
    private $client;

    /**
     * @var string
     */
    private $urn;

    /**
     * @var string
     */
    private $apiPublicId;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * @var ResponseFactoryInterface|MockObject
     */
    private $responseFactory;

    /**
     * @var LoggerInterface|MockObject
     */
    private $logger;

    /**
     * @var GuzzleSecurityTokenAdapter
     */
    private $adapter;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = $this->createMock(ClientInterface::class);
        $this->urn = uniqid();
        $this->apiSecret = uniqid();
        $this->apiPublicId = uniqid();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->responseFactory = $this->createMock(ResponseFactoryInterface::class);

        $this->adapter = new GuzzleSecurityTokenAdapter(
            $this->client,
            $this->responseFactory,
            $this->urn,
            $this->apiPublicId,
            $this->apiSecret
        );
        $this->adapter->setLogger($this->logger);
    }

    public function testSuccess()
    {
        $endpoint = uniqid();
        $cacheKey = AdapterInterface::DEFAULT_KEY_PREFIX . md5($endpoint);

        $cache = $this->createMock(CacheInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->adapter->setCache($cache);

        $request->expects(self::once())
            ->method('isCacheable')
            ->willReturn(true);
        $request->expects(self::once())
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::once())
            ->method('getData')
            ->willReturn(null);
        $cache->expects(self::once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(true);
        $cache->expects(self::once())
            ->method('get')
            ->with($cacheKey)
            ->willReturn($response);

        $result = $this->adapter->call($request);
        $this->assertEquals($response, $result);
    }

    public function testKeyNotFound()
    {
        $endpoint = uniqid();
        $cacheKey = AdapterInterface::DEFAULT_KEY_PREFIX . md5($endpoint);
        $uri = $this->urn . '/' . $endpoint;

        $cache = $this->createMock(CacheInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $clientResponse = $this->createMock(PsrResponseInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->adapter->setCache($cache);

        // CACHE
        $request->expects(self::once())
            ->method('isCacheable')
            ->willReturn(true);
        $request->expects(self::exactly(2))
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::exactly(2))
            ->method('getData')
            ->willReturn(null);
        $cache->expects(self::once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(false);

        // API CALL
        $request->expects(self::once())
            ->method('getMethod')
            ->willReturn(HttpMethod::METHOD_GET);
        $options = [
            RequestOptions::TIMEOUT => AdapterInterface::DEFAULT_TIMEOUT,
        ];
        $options[RequestOptions::HEADERS][HttpHeader::HEADER_AUTH_PUBLIC_ID] = $this->apiPublicId;
        $options[RequestOptions::HEADERS][HttpHeader::HEADER_AUTH_HASH] = hash_hmac(
            GuzzleSecurityTokenAdapter::HASH_ALGORITHM,
            '',
            $this->apiSecret
        );
        $this->client->expects(self::once())
            ->method('request')
            ->with(HttpMethod::METHOD_GET, $uri, $options)
            ->willReturn($clientResponse);
        $clientResponse->expects(self::exactly(2))
            ->method('getStatusCode')
            ->willReturn(HttpCode::HTTP_CODE_OK);
        $this->logger->expects(self::once())
            ->method('info');
        $this->responseFactory->expects(self::once())
            ->method('createSuccess')
            ->with($clientResponse)
            ->willReturn($response);

        // CACHE
        $response->expects(self::once())
            ->method('isError')
            ->willReturn(false);
        $cache->expects(self::once())
            ->method('set')
            ->with($cacheKey, $response);

        $result = $this->adapter->call($request);
        $this->assertEquals($response, $result);
    }

    public function testResponseError()
    {
        $endpoint = uniqid();
        $cacheKey = AdapterInterface::DEFAULT_KEY_PREFIX . md5($endpoint);
        $uri = $this->urn . '/' . $endpoint;

        $cache = $this->createMock(CacheInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $clientResponse = $this->createMock(PsrResponseInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->adapter->setCache($cache);

        // CACHE
        $request->expects(self::once())
            ->method('isCacheable')
            ->willReturn(true);
        $request->expects(self::exactly(2))
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::exactly(2))
            ->method('getData')
            ->willReturn(null);
        $cache->expects(self::once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(false);

        // API CALL
        $request->expects(self::once())
            ->method('getMethod')
            ->willReturn(HttpMethod::METHOD_GET);
        $options = [
            RequestOptions::TIMEOUT => AdapterInterface::DEFAULT_TIMEOUT,
        ];
        $options[RequestOptions::HEADERS][HttpHeader::HEADER_AUTH_PUBLIC_ID] = $this->apiPublicId;
        $options[RequestOptions::HEADERS][HttpHeader::HEADER_AUTH_HASH] = hash_hmac(
            GuzzleSecurityTokenAdapter::HASH_ALGORITHM,
            '',
            $this->apiSecret
        );
        $this->client->expects(self::once())
            ->method('request')
            ->with(HttpMethod::METHOD_GET, $uri, $options)
            ->willReturn($clientResponse);
        $clientResponse->expects(self::exactly(2))
            ->method('getStatusCode')
            ->willReturn(HttpCode::HTTP_CODE_OK);
        $this->logger->expects(self::once())
            ->method('info');
        $this->responseFactory->expects(self::once())
            ->method('createSuccess')
            ->with($clientResponse)
            ->willReturn($response);

        // CACHE
        $response->expects(self::once())
            ->method('isError')
            ->willReturn(true);

        $result = $this->adapter->call($request);
        $this->assertEquals($response, $result);
    }

    public function testCacheKeyHash()
    {
        $endpoint = uniqid();
        $data = uniqid();
        $jsonData = \GuzzleHttp\json_encode($data);
        $cacheKey = AdapterInterface::DEFAULT_KEY_PREFIX . md5($endpoint . $jsonData);

        $cache = $this->createMock(CacheInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->adapter->setCache($cache);

        $request->expects(self::once())
            ->method('isCacheable')
            ->willReturn(true);
        $request->expects(self::once())
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::exactly(2))
            ->method('getData')
            ->willReturn($data);
        $cache->expects(self::once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(true);
        $cache->expects(self::once())
            ->method('get')
            ->with($cacheKey)
            ->willReturn($response);

        $result = $this->adapter->call($request);
        $this->assertEquals($response, $result);
    }
}
