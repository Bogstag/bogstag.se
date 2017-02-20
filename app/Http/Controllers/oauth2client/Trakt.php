<?php

namespace App\Http\Controllers\oauth2client;

use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

/**
 * Class Trakt.
 */
class Trakt extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://trakt.tv/oauth/authorize';
    }

    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://api.trakt.tv/oauth/token';
    }

    /**
     * Returns the URL for requesting the resource owner's details.
     *
     * @param AccessToken $token
     *
     * @return string
     *
     * @todo Implement getResourceOwnerDetailsUrl() method.
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
    }

    /**
     * Returns the default scopes used by this provider.
     *
     * This should only be the scopes that are required to request the details
     * of the resource owner, rather than all the available scopes.
     *
     * @return array
     *
     * @todo Implement getDefaultScopes() method.
     */
    protected function getDefaultScopes()
    {
    }

    /**
     * Checks a provider response for errors.
     *
     * @param ResponseInterface $response
     * @param array|string      $data     Parsed response data
     *
     * @throws IdentityProviderException
     *
     * @return void
     *
     * @todo Implement checkResponse() method.
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
    }

    /**
     * Generates a resource owner object from a successful resource owner
     * details request.
     *
     * @param array       $response
     * @param AccessToken $token
     *
     * @return ResourceOwnerInterface
     *
     * @todo Implement createResourceOwner() method.
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
    }
}
