<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\APIs\UserAPI;
use App\Repositories\ElasticSearch\ElasticSearchRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Services\Validation\ModelValidationService;
use Illuminate\Http\Request;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * @var OrderRepositoryInterface
     */
    private $repository;

    /**
     * UserController constructor.
     *
     * @param OrderRepositoryInterface $repository
     */
    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function index(Request $request)
    {
        $data      = $request->all();
        $registers = $this->repository->allBy($data);

        return $this->respond($registers);
    }

    /**
     * @return array
     */
    public function indexTest()
    {
        $registers = $this->repository->all([]);

        return $this->respond($registers);
    }

    /**
     * @param ModelValidationService $validationService
     * @param                        $id
     *
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function indexByUser(ModelValidationService $validationService, $id)
    {
        try {
            $validationService->validateID($id);
            $registers = $this->repository->allByUser($id);

            return $this->respond($registers);
        } catch (\Exception $exception) {
            $this->setStatusCode(422);

            return $this->respondWithErrors($exception->getMessage());
        }

    }

    /**
     * @param ModelValidationService $validationService
     * @param UserAPI                $API
     *
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(ModelValidationService $validationService, UserAPI $API)
    {
        try {
            $validationService->validateModel(new Order());

            $id = $validationService->validateIDCustom('user_id');

            $API->isValidUser($id);

            $contentRequest = $validationService->getRequestData();
            $record         = $this->repository->create($contentRequest);

            $this->setStatusCode(201);

            return $this->respond($record);
        } catch (\Exception $e) {

            $this->setStatusCode(422);

            return $this->respondWithErrors($e->getMessage());
        }
    }

    /**
     * @param ModelValidationService $validationService
     * @param UserAPI                $API
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function storeTest(ModelValidationService $validationService, UserAPI $API)
    {
        try {
            $validationService->validateModel(new Order());

            $contentRequest = $validationService->getRequestData();
            $record         = $this->repository->create($contentRequest);

            $this->setStatusCode(201);

            return $this->respond($record);
        } catch (\Exception $e) {
            $this->setStatusCode(422);

            return $this->respondWithErrors($e->getMessage());
        }
    }

    /**
     * @param ElasticSearchRepositoryInterface $elastic
     * @param ModelValidationService           $validationService
     * @param                                  $id
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function show(ElasticSearchRepositoryInterface $elastic,
                         ModelValidationService $validationService,
                         $id)
    {
        try {
            $validationService->validateID($id);

            $result = $elastic->get('orders-table', $id);

            return $this->respond($result);
        } catch (\Exception $exception) {
            $this->setStatusCode(422);

            return $this->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @param ModelValidationService $validationService
     * @param                        $id
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function showTest(ModelValidationService $validationService, $id)
    {
        try {
            $validationService->validateID($id);

            $result = $this->repository->getById($id);

            return $this->respond($result);
        } catch (\Exception $exception) {
            $this->setStatusCode(422);

            return $this->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @param ModelValidationService $validationService
     * @param string                 $id
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function update(ModelValidationService $validationService, string $id)
    {
        try {
            $validationService->validateID($id);
            $validationService->validateModel(new Order(), $id);

            $contentRequest = $validationService->getRequestData();
            $this->repository->update($id, $contentRequest);

            $this->setStatusCode(204);

            return $this->respond([]);
        } catch (\Exception $exception) {
            $this->setStatusCode(422);
            $message = $exception->getMessage();

            return $this->respondWithErrors($message);
        }
    }

    /**
     * Remove user by id.
     *
     * @param ModelValidationService $validationService Service to validate inputs
     * @param string                 $id ID from user
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function destroy(ModelValidationService $validationService, string $id)
    {
        try {
            $validationService->validateID($id);

            $this->repository->delete($id);

            $this->setStatusCode(204);

            return $this->respond([]);
        } catch (\Exception $exception) {
            $this->setStatusCode(422);
            $message = $exception->getMessage();

            return $this->respondWithErrors($message, []);
        }
    }
}
