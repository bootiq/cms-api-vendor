<?php

namespace BootIq\CmsApiVendor\Adapter;

use BootIq\CmsApiVendor\Enum\HttpCode;
use BootIq\CmsApiVendor\Enum\HttpHeader;
use BootIq\CmsApiVendor\Request\RequestInterface;
use BootIq\CmsApiVendor\Response\ResponseFactoryInterface;
use BootIq\CmsApiVendor\Response\ResponseInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;

class GuzzleSecurityTokenAdapter implements AdapterInterface
{
    const HASH_ALGORITHM = 'sha512';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $urn;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var string
     */
    private $apiPublicId;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * @var CacheInterface|null
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var float
     */
    private $timeout = self::DEFAULT_TIMEOUT;

    /**
     * GuzzleSecurityTokenAdapter constructor.
     * @param ClientInterface $client
     * @param ResponseFactoryInterface $responseFactory
     * @param string $urn
     * @param string $apiPublicId
     * @param string $apiSecret
     */
    public function __construct(
        ClientInterface $client,
        ResponseFactoryInterface $responseFactory,
        string $urn,
        string $apiPublicId,
        string $apiSecret
    ) {
        $this->client = $client;
        $this->responseFactory = $responseFactory;
        $this->urn = preg_replace('/\\/$/', '', $urn);
        $this->apiPublicId = $apiPublicId;
        $this->apiSecret = $apiSecret;
        $this->logger = new NullLogger();
    }

    /**
     * @param CacheInterface $cache
     * @return void
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param float $timeout
     * @return void
     */
    public function setTimeout(float $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ClientException
     * @throws \Exception
     */
    public function call(RequestInterface $request): ResponseInterface
    {
        if ($this->cache !== null && $request->isCacheable()) {
            return $this->loadFromCache($request);
        }
        return $this->loadFromApi($request);
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ClientException
     * @throws \Exception
     */
    private function loadFromApi(RequestInterface $request): ResponseInterface
    {
        $uri = $this->urn . '/' . $request->getEndpoint();
        $data = $request->getData();

        $options = [
            RequestOptions::TIMEOUT => $this->timeout,
        ];

        $rawBodyData = '';
        if ($data !== null) {
            $options[RequestOptions::JSON] = $data;
            $rawBodyData = \GuzzleHttp\json_encode($data);
        }

        $options[RequestOptions::HEADERS][HttpHeader::HEADER_AUTH_PUBLIC_ID] = $this->apiPublicId;
        $options[RequestOptions::HEADERS][HttpHeader::HEADER_AUTH_HASH] = hash_hmac(
            self::HASH_ALGORITHM,
            $rawBodyData,
            $this->apiSecret
        );

        $start = microtime(true);
        try {
            $response = $this->client->request($request->getMethod(), $uri, $options);
            $end = microtime(true);
        } catch (ClientException $exception) {
            $end = microtime(true);
            $response = $exception->getResponse();
            if ($response === null) {
                $this->logger->error($exception, [$request, ($end - $start)]);
                throw $exception;
            }
        } catch (\Exception $exception) {
            $end = microtime(true);
            $this->logger->critical($exception, [$request, ($end - $start)]);
            throw $exception;
        }

        $statusCode = $response->getStatusCode();
        if (!in_array($statusCode, HttpCode::SUCCESS_CODES)) {
            $this->logger->warning(((string) $response->getStatusCode()), [$request, ($end - $start)]);
            return $this->responseFactory->createError($response);
        }
        $this->logger->info(((string) $response->getStatusCode()), [$request, ($end - $start)]);
        return $this->responseFactory->createSuccess($response);
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    private function loadFromCache(RequestInterface $request): ResponseInterface
    {
        $cacheKey = $this->getCacheKey($request);

        if (null === $this->cache) {
            return $this->loadFromApi($request);
        }

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $response = $this->loadFromApi($request);
        if ($response->isError()) {
            return $response;
        }

        $this->cache->set($cacheKey, $response);
        return $response;
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    private function getCacheKey(RequestInterface $request): string
    {
        $data = $request->getData();
        $stringForHash = $request->getEndpoint();
        if ($data !== null) {
            $jsonData = \GuzzleHttp\json_encode($request->getData());
            $stringForHash .= $jsonData;
        }
        return self::DEFAULT_KEY_PREFIX . md5($stringForHash);
    }
}
