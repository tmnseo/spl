<?php
/**
 * Copyright © Thomas Klein, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Zoho\Desk\OAuth;

use zcrmsdk\crm\utility\APIConstants;
use zcrmsdk\oauth\exception\ZohoOAuthException;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\oauth\ZohoOAuthClient;
use Zoho\Desk\Api\Metadata;
use Zoho\Desk\Client\ConfigProviderInterface;
use Zoho\Desk\Exception\Exception;

final class Client implements ClientInterface
{
    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * @var bool
     */
    private $isConfigured;

    public function __construct(
        ConfigProviderInterface $configProvider
    ) {
        $this->configProvider = $configProvider;
        $this->isConfigured = false;
    }

    public function getApiBaseUrl(): string
    {
        return $this->configProvider->get()[APIConstants::API_BASE_URL] ?? Metadata::API_ENDPOINT_US;
    }

    public function getApiVersion(): string
    {
        return $this->configProvider->get()[APIConstants::API_VERSION] ?? Metadata::API_VERSION;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getAccessToken(): string
    {
        try {
            $this->configure();
            /** @var ZohoOAuthClient $oauthClient */
            $oauthClient = ZohoOAuth::getClientInstance();
            $accessToken = $oauthClient->getAccessToken($this->configProvider->get()[APIConstants::CURRENT_USER_EMAIL]);
        } catch (ZohoOAuthException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        return $accessToken;
    }

    public function getOrgId(): int
    {
        return (int) $this->configProvider->get()[Metadata::ORG_ID];
    }

    private function configure(): void
    {
        if (!$this->isConfigured) {
            ZohoOAuth::initialize($this->configProvider->get());
            $this->isConfigured = true;
        }
    }
}
