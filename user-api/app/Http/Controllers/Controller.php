<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param       $data
     * @param array $headers
     *
     * @return array
     */
    public function respond($data, $headers = [])
    {
        $response = [
            'status' => 'success',
            'data'   => empty($data) ? [] : $data
        ];

        return response()->json($response,
                                $this->getStatusCode(),
                                $headers);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param       $errors
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data   = ['status' => 'fail'];
        $errors = json_decode($errors, true);
        $errors = !is_array($errors) ? [] : $errors;

        $data = array_merge($data, $errors);

        return response()->json($data, $this->getStatusCode(), $headers);
    }

//    /**
//     * Returns a 401 Unauthorized http response
//     *
//     * @param string $message
//     *
//     * @return Symfony\Component\HttpFoundation\JsonResponse
//     */
//    public function respondUnauthorized($message = 'Not authorized!')
//    {
//        return $this->setStatusCode(401)->respondWithErrors($message);
//    }

    /**
     * Returns a 422 Unprocessable Entity
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondValidationError($message = 'Validation errors')
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
     * Returns a 404 Not Found
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
     * Returns a 201 Created
     *
     * @param array $data
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondCreated($data = [])
    {
        return $this->setStatusCode(201)->respond($data);
    }
}
