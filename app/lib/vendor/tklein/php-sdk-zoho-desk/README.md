# Zoho Desk PHP SDK

[![Latest Stable Version](https://img.shields.io/packagist/v/tklein/php-sdk-zoho-desk.svg?style=flat-square)](https://packagist.org/packages/tklein/php-sdk-zoho-desk)
[![License: MIT](https://img.shields.io/github/license/thomas-kl1/php-sdk-zoho-desk.svg?style=flat-square)](./LICENSE)
[![Packagist](https://img.shields.io/packagist/dt/tklein/php-sdk-zoho-desk.svg?style=flat-square)](https://packagist.org/packages/tklein/php-sdk-zoho-desk/stats)
[![Packagist](https://img.shields.io/packagist/dm/tklein/php-sdk-zoho-desk.svg?style=flat-square)](https://packagist.org/packages/tklein/php-sdk-zoho-desk/stats)

This SDK library 

 - [Setup](#setup)
 - [Features](#features)
 - [Settings](#settings)
 - [Documentation](#documentation)
 - [Support](#support)
 - [Authors](#authors)
 - [License](#license)

## Setup

```
composer require tklein/php-sdk-zoho-desk
```

## Features

You can execute all CRUD actions on all entities available in Zoho Desk.
Please check you have the allowed scope of operation with the proper registered OAuth access token.

## Settings

First you need to have a valid access token. First of all connect to your Zoho developer console from your region of
subscription:

for Europe the url is: [https://api-console.zoho.eu]

- Create or use an existing "Self Client" application.
- Generate the grant code, with the proper scopes.
- Generate the persistence auth token file with the Zoho SDK:

```php
include __DIR__ . /* Relative path to the vendor autoloader */ '/vendor/autoload.php';

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\exception\ZohoOAuthException;
use zcrmsdk\oauth\utility\ZohoOAuthConstants;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\oauth\ZohoOAuthClient;
use Zoho\Desk\Api\Metadata;
use Zoho\Desk\Client\ConfigProviderBuilder;

$configBuilder = ConfigProviderBuilder::getInstance();
$configBuilder->setClientId(/* Client ID */)
  ->setClientSecret(/* Client Secret */)
  ->setRedirectUrl(/* Redirect Url */)
  ->setCurrentUserEmail(/* User Email */)
  ->setApiBaseUrl(/* API Endpoint by region */)
  ->setApiVersion(Metadata::API_VERSION)
  ->setOrgId(/* Org ID */)
  ->setIsSandbox(/* Sandbox Status */)
  ->setAccountsUrl(/* Accounts Url */)
  ->setTokenPersistencePath(/* Persistence Path */);

// Add php code if the zcrm_oauthtokens.txt to create the file if it does not already exists.

ZCRMRestClient::initialize($configBuilder->create());
ZohoOAuth::getClientInstance()->generateAccessToken($grantCode);
```

Create the configuration object with your API details and credentials.

```php
include __DIR__ . /* Relative path to the vendor autoloader */ '/vendor/autoload.php';

use Zoho\Desk\Api\Metadata;
use Zoho\Desk\Client\ConfigProviderBuilder;

$configBuilder = ConfigProviderBuilder::getInstance();
$configBuilder->setClientId(/* Client ID */)
    ->setClientSecret(/* Client Secret */)
    ->setRedirectUrl(/* Redirect Url */)
    ->setCurrentUserEmail(/* User Email */)
    ->setApiBaseUrl(/* API Endpoint by region */)
    ->setApiVersion(Metadata::API_VERSION)
    ->setOrgId(/* Org ID */)
    ->setIsSandbox(/* Sandbox Status */)
    ->setAccountsUrl(/* Accounts Url */)
    ->setTokenPersistencePath(/* Persistence Path */);
```

## Documentation

The entry point of the SDK is the gateway facade:

```php
use Zoho\Desk\Gateway;

$gateway = new Gateway($configBuilder->create());
```

The facade is easy to use:

```php
use Zoho\Desk\Exception\CouldNotDeleteException;
use Zoho\Desk\Exception\CouldNotReadException;
use Zoho\Desk\Exception\CouldNotSaveException;

$ticketDataObject = $gateway->createDataObject('tickets', /* Entity values */);

try {
    $ticketDataObject = $gateway->create('tickets', $ticketDataObject);
} catch (CouldNotSaveException $e) {
    // Handle the exception...
}

try {
    $ticketDataObject = $gateway->get('tickets', 1234);
} catch (CouldNotReadException $e) {
    // Handle the exception...
}

try {
    $ticketDataObject = $gateway->update('tickets', $ticketDataObject);
} catch (CouldNotSaveException $e) {
    // Handle the exception...
}

try {
    $gateway->delete('tickets', 1234, ['resolution']);
} catch (CouldNotDeleteException $e) {
    // Handle the exception...
}
```

## Support

Raise a new [request](https://github.com/thomas-kl1/php-sdk-zoho-desk/issues) to the issue tracker.

## Authors

- **Thomas Klein** - *Maintainer* - [![GitHub followers](https://img.shields.io/github/followers/thomas-kl1.svg?style=social)](https://github.com/thomas-kl1)
- **Contributors** - *Contributor* - [![GitHub contributors](https://img.shields.io/github/contributors/thomas-kl1/php-sdk-zoho-desk.svg?style=flat-square)](https://github.com/thomas-kl1/php-sdk-zoho-desk/graphs/contributors)

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) details.

***That's all folks!***