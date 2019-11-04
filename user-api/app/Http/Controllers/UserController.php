<?php declare(strict_types=1);

/**
 * Controller
 * Control all user actions.
 * php version 7.2
 *
 * @description Controller User Actions
 * @category    Controller
 * @package     Controller
 * @author      Paulo Henrique <phbotelho@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link        http://www.apache.org/
 */
namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\ElasticSearch\ElasticSearchRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\Validation\ModelValidationService;
use Elasticsearch\Common\Exceptions\Unauthorized401Exception;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * UserController constructor.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
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
        $registers = $this->repository->allTest([]);

        return $this->respond($registers);
    }

    /**
     * @param ModelValidationService $validationService
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function store(ModelValidationService $validationService)
    {
        try {
            $validationService->validateModel(new User());

            $contentRequest = $validationService->getRequestData();
            $record         = $this->repository->create($contentRequest);

            $this->repository->clearRedisCache('users.all');
            $this->setStatusCode(201);

            return $this->respond($record);
        } catch (\Exception $e) {
            $this->setStatusCode(422);

            return $this->respondWithErrors($e->getMessage());
        }
    }

    /**
     * @param ModelValidationService $validationService
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function storeTest(ModelValidationService $validationService)
    {
        try {
            $validationService->validateModel(new User());

            $contentRequest = $validationService->getRequestData();
            $record         = $this->repository->create($contentRequest);

            $this->repository->clearRedisCache('users.all');
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
    public function showElastic(ElasticSearchRepositoryInterface $elastic,
                                ModelValidationService $validationService,
                                $id)
    {
        try {
            $validationService->validateID($id);

            $result = $elastic->getSource('users-table', $id);

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
    public function show(ModelValidationService $validationService, $id)
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
     * @param                        $id
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function showSecure(ModelValidationService $validationService, $id)
    {
        try {
            $validationService->isAPIKeyValid();
            $validationService->validateID($id);

            $result = $this->repository->getById($id);

            return $this->respond($result);
        } catch (\Exception | Unauthorized401Exception $exception) {
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
            $validationService->validateModel(new User(), $id);

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
            $this->repository->clearRedisCache('users.all');

            return $this->respond([]);
        } catch (\Exception $exception) {
            $this->setStatusCode(422);
            $message = $exception->getMessage();

            return $this->respondWithErrors($message, []);
        }
    }
}
