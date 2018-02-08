BootIq - CMS API vendor
========
![BOOT!Q Logo](http://www.bootiq.io/images/footer-logo.png "BOOT!Q")

[![pipeline status](https://gitlab.mb-e.sk/platform/vendor-cms-api/badges/master/pipeline.svg)](https://gitlab.mb-e.sk/platform/vendor-cms-api/commits/master) [![coverage report](https://gitlab.mb-e.sk/platform/vendor-cms-api/badges/master/coverage.svg)](https://gitlab.mb-e.sk/platform/vendor-cms-api/commits/master)

## Installation

For installation of Boot!Q CMS API vendor use composer: 

```bash
composer require bootiq/cms-api-vendor
```

## Configuration

### Adapter
For using our adapter, simply use factory or DI in your project and provide needed services and parameters as shown below:
```php

$adapter = new \BootIq\CmsApiVendor\Adapter\GuzzleSecurityTokenAdapter(
    new \GuzzleHttp\Client(),
    $responseFactory,
    $urn,
    $apiPublicId,
    $apiSecret
);

```
  * *$responseFactory* is service for creating responses.
    You can use our default factory *BootIq\CmsApiVendor\Response\ResponseFactory*, or
    if you want use own response factory, your factory have to implement *BootIq\CmsApiVendor\Response\ResponseFactoryInterface*.
  * $urn is web address of your CMS system for example: 'https://cms.bootiq.io/api/v1/'.
  * $apiPublicId is your public id which is used for identifying our customer.
  * $apiSecret is key for hashing and authorizing our customer.  


For using own adapter, you have to implement *AdapterInterface* from *BootIq\CmsApiVendor\Adapter*.

#### Modification of our adapter

You can change following attribute or services in our adapter, simply by using setter:
  * Timeout - Timeout of calling our CMS system (default: 10s).
  * Cache - Every request and response are able to cache by setting cache service (PSR-16).
  * Logger - Logger can log every call to our CMS system (default: no loging => NullLogger; PSR-3).


## Usage

For using our CMS API you can use your own services, or our default services.

### PageService

  * getPageById - will get page from CMS by its id.
  * getPageBySlug - will get page from CMS by its slug.
 
