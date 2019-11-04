<?php declare(strict_types=1);

namespace App\Repositories\APIs;

/**
 * Interface BaseAPIInterface
 * @package App\Repositories\APIs
 */
interface BaseAPIInterface
{
    /**
     * @return bool
     */
    public function isBaseURIValid():bool;

    /**
     * @param       $method
     * @param       $requestUrl
     * @param array $formParams
     * @param array $headers
     *
     * @return mixed
     */
    public function getAPIData($method, $requestUrl, $formParams = [], $headers = []);
}
