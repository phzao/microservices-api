<?php declare(strict_types=1);

namespace App\Repositories\APIs;

use GuzzleHttp\Client;

/**
 * Class BaseAPI
 * @package App\Repositories\APIs
 */
class BaseAPI implements BaseAPIInterface
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @param       $method
     * @param       $requestUrl
     * @param array $formParams
     * @param array $headers
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAPIData($method, $requestUrl, $formParams = [], $headers = [])
    {
        $base['base_uri'] = $this->baseUri;

        $client = new Client($base);

        if (isset($this->secret)) {
            $headers['x-api-key'] = $this->secret;
        }

        $parameters = [
            'form_params' => $formParams,
            'headers'     => $headers
        ];

        $response = $client->request($method, $requestUrl, $parameters);

        return $response
                    ->getBody()
                    ->getContents();
    }

    /**
     * @return bool
     */
    public function isBaseURIValid():bool
    {
        if (empty($this->baseUri)) {
            return false;
        }

        return true;
    }
}
