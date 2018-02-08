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
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Log\LoggerInterface;

class GuzzleSecurityTokenAdapterTest extends TestCase
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
        $uri = $this->urn . '/' . $endpoint;

        $request = $this->createMock(RequestInterface::class);
        $clientResponse = $this->createMock(PsrResponseInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects(self::once())
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::once())
            ->method('getData')
            ->willReturn(null);
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

        $result = $this->adapter->call($request);
        $this->assertEquals($response, $result);
    }

    public function testDataNotEmptySuccess()
    {
        $endpoint = uniqid();
        $uri = $this->urn . '/' . $endpoint;
        $data = uniqid();
        $jsonData = \GuzzleHttp\json_encode($data);

        $request = $this->createMock(RequestInterface::class);
        $clientResponse = $this->createMock(PsrResponseInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects(self::once())
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::once())
            ->method('getData')
            ->willReturn($data);
        $request->expects(self::once())
            ->method('getMethod')
            ->willReturn(HttpMethod::METHOD_GET);
        $options = [
            RequestOptions::TIMEOUT => AdapterInterface::DEFAULT_TIMEOUT,
        ];
        $options[RequestOptions::HEADERS][HttpHeader::HEADER_AUTH_PUBLIC_ID] = $this->apiPublicId;
        $options[RequestOptions::HEADERS][HttpHeader::HEADER_AUTH_HASH] = hash_hmac(
            GuzzleSecurityTokenAdapter::HASH_ALGORITHM,
            $jsonData,
            $this->apiSecret
        );
        $options[RequestOptions::JSON] = $data;
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

        $result = $this->adapter->call($request);
        $this->assertEquals($response, $result);
    }

    public function testNotSuccess()
    {
        $endpoint = uniqid();
        $uri = $this->urn . '/' . $endpoint;

        $request = $this->createMock(RequestInterface::class);
        $clientResponse = $this->createMock(PsrResponseInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects(self::once())
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::once())
            ->method('getData')
            ->willReturn(null);
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
            ->willReturn(HttpCode::HTTP_NOT_FOUND);
        $this->logger->expects(self::once())
            ->method('warning');
        $this->responseFactory->expects(self::once())
            ->method('createError')
            ->with($clientResponse)
            ->willReturn($response);

        $result = $this->adapter->call($request);
        $this->assertEquals($response, $result);
    }

    public function testException()
    {
        $endpoint = uniqid();
        $uri = $this->urn . '/' . $endpoint;

        $request = $this->createMock(RequestInterface::class);

        $request->expects(self::once())
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::once())
            ->method('getData')
            ->willReturn(null);
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
            ->willThrowException(new \Exception());
        $this->logger->expects(self::once())
            ->method('critical');

        $this->expectException(\Exception::class);
        $this->adapter->call($request);
    }

    public function testClientExceptionWithResponse()
    {
        $endpoint = uniqid();
        $uri = $this->urn . '/' . $endpoint;

        $request = $this->createMock(RequestInterface::class);
        $clientResponse = $this->createMock(PsrResponseInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $exception = $this->createMock(ClientException::class);

        $request->expects(self::once())
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::once())
            ->method('getData')
            ->willReturn(null);
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
            ->willThrowException($exception);
        $exception->expects(self::once())
            ->method('getResponse')
            ->willReturn($clientResponse);
        $clientResponse->expects(self::exactly(2))
            ->method('getStatusCode')
            ->willReturn(HttpCode::HTTP_NOT_FOUND);
        $this->logger->expects(self::once())
            ->method('warning');
        $this->responseFactory->expects(self::once())
            ->method('createError')
            ->with($clientResponse)
            ->willReturn($response);

        $result = $this->adapter->call($request);
        $this->assertEquals($response, $result);
    }

    public function testClientExceptionWithoutResponse()
    {
        $endpoint = uniqid();
        $uri = $this->urn . '/' . $endpoint;

        $request = $this->createMock(RequestInterface::class);
        $exception = $this->createMock(ClientException::class);

        $request->expects(self::once())
            ->method('getEndpoint')
            ->willReturn($endpoint);
        $request->expects(self::once())
            ->method('getData')
            ->willReturn(null);
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
            ->willThrowException($exception);
        $exception->expects(self::once())
            ->method('getResponse')
            ->willReturn(null);
        $this->logger->expects(self::once())
            ->method('error');

        $this->expectException(ClientException::class);
        $this->adapter->call($request);
    }
}
