<?php declare(strict_types=1);

namespace App\Repositories\APIs;

/**
 * Class UserAPI
 * @package App\Repositories\APIs
 */
class UserAPI extends BaseAPI implements UserAPIInterface
{
    /**
     * @var integer
     */
    private $user_id;

    public function __construct()
    {
        $this->baseUri = config('services.users.base_uri');
        $this->secret  = config('services.users.secret');
    }

    /**
     * @param array $data
     */
    public function loadUserID(array $data)
    {
        $this->user_id = $data['user_id'];
    }

    /**
     * @param $id
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function isValidUser($id)
    {
        $user = $this->getUser((int) $id);
        $errormsg["message"] = 'User ID is invalid!';

        if (empty($user)) {
            throw new \Exception(json_encode($errormsg));
        }

        $return = json_decode($user, true);

        if (empty($return['data'])) {
            throw new \Exception(json_encode($errormsg));
        }
    }

    /**
     * @param $id
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUser($id)
    {
        return $this->getAPIData("GET", "/api/v1/users-secure/$id");
    }
}
