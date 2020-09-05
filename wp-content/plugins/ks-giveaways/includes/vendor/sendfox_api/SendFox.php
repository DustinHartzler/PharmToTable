<?php

require_once KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

/**
 * @author Garrett Grimm <garrett@kingsumo.com>
 * @package SendFox
 * @version 1.0.0
 */
class SendFox
{
	const API_URL = 'https://api.sendfox.com';

	protected $provider;


	/**
	 * @param string $apiKey
	 */
	public function __construct($accessToken = null)
	{
		$this->accessToken = $accessToken;

		$this->provider = new \League\OAuth2\Client\Provider\GenericProvider(array(
			'urlAuthorize' => 'https://sendfox.com/oauth/authorize',
            'urlAccessToken' => 'https://sendfox.com/oauth/token',
            'urlResourceOwnerDetails' => 'https://sendfox.com/oauth/resource',
		));
	}


	public function validateToken()
	{
		$request = $this->provider->getAuthenticatedRequest(
	        'GET',
	        sprintf('%s%s', self::API_URL, '/me'),
	        $this->accessToken
	    );

	    $response = $this->provider->getResponse($request);

	    if ($json = json_decode((string) $response->getBody())) {
	    	return (bool) $json->email;
	    }

	    return false;
	}


	public function getUserInfo()
	{
		$request = $this->provider->getAuthenticatedRequest(
	        'GET',
	        sprintf('%s%s', self::API_URL, '/me'),
	        $this->accessToken
	    );

	    $response = $this->provider->getResponse($request);
	    return json_decode((string) $response->getBody());
	}


	public function getTags()
	{
		$request = $this->provider->getAuthenticatedRequest(
	        'GET',
	        sprintf('%s%s', self::API_URL, '/tags'),
	        $this->accessToken
	    );

	    $response = $this->provider->getResponse($request);
	    return json_decode((string) $response->getBody());
	}


	public function addContact($email, $firstName = null, $tagId = null)
	{
		$request = $this->provider->getAuthenticatedRequest(
	        'POST',
	        sprintf('%s%s?email=%s&first_name=%s&tags=%s', self::API_URL, '/contacts', urlencode($email), urlencode($firstName), urlencode($tagId)),
	        $this->accessToken
	    );

	    $response = $this->provider->getResponse($request);
	    return json_decode((string) $response->getBody());
	}
}