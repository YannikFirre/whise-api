<?php

/*
 * This file is part of the fw4/whise-api library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Whise\Api;

use Whise\Api\ApiAdapter\ApiAdapterInterface;
use Whise\Api\ApiAdapter\HttpApiAdapter;
use Whise\Api\Response\Response;
use Whise\Api\Request\Request;
use InvalidArgumentException;

final class WhiseApi
{
    /** @var ApiAdapterInterface */
    private $apiAdapter;
    
    /** @var Endpoints\Admin */
    protected $adminEndpoint;
    
    /** @var Endpoints\Estates */
    protected $estatesEndpoint;
    
    /** @var Endpoints\Contacts */
    protected $contactsEndpoint;
    
    /** @var Endpoints\Calendars */
    protected $calendarsEndpoint;
    
    /** @var Endpoints\Activities */
    protected $activitiesEndpoint;
    
    public function __construct($access_token = null)
    {
        if (!is_null($access_token)) {
            $this->setAccessToken($access_token);
        }
    }
    
    // Endpoints
    
    /**
     * Request a new access token using user credentials.
     *
     * @param array $parameters Associative array containing user credentials
     *
     * @return Response
     */
    public function token(array $parameters): Response
    {
        $request = new Request('POST', 'token', $parameters);
        return new Response($this->getApiAdapter()->request($request));
    }
    
    /**
     * Access endpoints related to general settings.
     *
     * @return Endpoints\Admin
     */
    public function admin(): Endpoints\Admin
    {
        if (is_null($this->adminEndpoint)) {
            $this->adminEndpoint = new Endpoints\Admin($this);
        }
        return $this->adminEndpoint;
    }
    
    /**
     * Access endpoints related to clients.
     *
     * @return Endpoints\AdminClients
     */
    public function clients(): Endpoints\AdminClients
    {
        return $this->admin()->clients();
    }
    
    /**
     * Access endpoints related to offices.
     *
     * @return Endpoints\AdminOffices
     */
    public function offices(): Endpoints\AdminOffices
    {
        return $this->admin()->offices();
    }
    
    /**
     * Access endpoints related to real estate properties and projects.
     *
     * @return Endpoints\Estates
     */
    public function estates(): Endpoints\Estates
    {
        if (is_null($this->estatesEndpoint)) {
            $this->estatesEndpoint = new Endpoints\Estates($this);
        }
        return $this->estatesEndpoint;
    }
    
    /**
     * Access endpoints related to contacts.
     *
     * @return Endpoints\Contacts
     */
    public function contacts(): Endpoints\Contacts
    {
        if (is_null($this->contactsEndpoint)) {
            $this->contactsEndpoint = new Endpoints\Contacts($this);
        }
        return $this->contactsEndpoint;
    }
    
    /**
     * Access endpoints related to calendar events.
     *
     * @return Endpoints\Calendars
     */
    public function calendars(): Endpoints\Calendars
    {
        if (is_null($this->calendarsEndpoint)) {
            $this->calendarsEndpoint = new Endpoints\Calendars($this);
        }
        return $this->calendarsEndpoint;
    }
    
    /**
     * Access endpoints related to history logs.
     *
     * @return Endpoints\Activities
     */
    public function activities(): Endpoints\Activities
    {
        if (is_null($this->activitiesEndpoint)) {
            $this->activitiesEndpoint = new Endpoints\Activities($this);
        }
        return $this->activitiesEndpoint;
    }
    
    // Access token
    
    /**
     * Use a previously requested access token.
     *
     * @param mixed $access_token
     */
    public function setAccessToken($access_token): self
    {
        if (is_array($access_token) && isset($access_token['token'])) {
            $access_token = strval($access_token['token']);
        } elseif (is_object($access_token) && isset($access_token->token)) {
            $access_token = strval($access_token->token);
        }
        if (!is_string($access_token)) {
            throw new InvalidArgumentException('Invalid access token provided');
        }
        $this->getApiAdapter()->setAccessToken($access_token);
        return $this;
    }
    
    /**
     * Get the current access token.
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->getApiAdapter()->getAccessToken();
    }
    
    /**
     * Request a new access token using user credentials.
     *
     * @param string $username
     * @param string $password
     *
     * @return Response
     */
    public function requestAccessToken(string $username, string $password): Response
    {
        return $this->token([
            'Username' => $username,
            'Password' => $password,
        ]);
    }
    
    /**
    * Request a new access token using client ID.
    *
    * @param int $clientId
    * @param int $officeId
    *
    * @return Response
    */
    public function requestClientToken(int $clientId, int $officeId): Response
    {
        return $this->admin()->clients()->token([
            'ClientId' => $clientId,
            'OfficeId' => $officeId,
        ]);
    }
    
    // Api adapter

    public function setApiAdapter(ApiAdapterInterface $adapter): self
    {
        $this->apiAdapter = $adapter;
        return $this;
    }

    public function getApiAdapter(): ApiAdapterInterface
    {
        if (!isset($this->apiAdapter)) {
            $this->setApiAdapter(new HttpApiAdapter());
        }
        return $this->apiAdapter;
    }
    
    /**
     * Set a callback for debugging API requests and responses.
     *
     * @param callable|null $callable Callback that accepts up to three
     * arguments - respectively the response body, request endpoint, and the
     * request body.
     *
     * @return self
     */
    public function debugResponses(?callable $callable): self
    {
        $this->getApiAdapter()->debugResponses($callable);
        return $this;
    }
}
