<?php declare(strict_types=1);

namespace App\Models;

use Laravel\Scout\Searchable;

/**
 * Class User
 * @package App\Models
 */
class User extends ModelBase implements UserInterface
{
    use Searchable;

    const INDEX_ELASTIC_SEARCH = 'users-table';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cpf',
        'email',
        'phone_number',
        'cpf'
    ];

    /**
     * @return array
     */
    public function getFullDataToIndex(): array
    {
        return [
            'index' => self::INDEX_ELASTIC_SEARCH,
            'id'    => $this->id,
            'body'  => $this->getFullDetails()
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getSearchParams(array $data): array
    {
        $param = [];

        if ($data['name']) {

            $name = $data['name'];

            $param['simple_query_string'] = [
                "query"  => "$name*",
                "fields" => ["name"]
            ];
        }

        return [
            "index" => self::INDEX_ELASTIC_SEARCH,
            "body"  => [
                "query" => $param
            ]
        ];
    }

    /**
     * @param string $column
     * @param string $format
     *
     * @return string
     */
    public function getDateTimeStringFrom(string $column, $format = "Y-m-d H:i:s"): string
    {
        return parent::getDateTimeStringFrom($column, $format);
    }

    /**
     * @param null $id
     *
     * @return array
     */
    public function rules($id = null): array
    {
        $id        = empty($id) ? "" : ",".$id;
        $sometimes = empty($id) ? "" : "sometimes|";

        $attributes = [
            'name'         => $sometimes."required|string|max:100|min:5",
            'phone_number' => $sometimes."nullable|string|min:10|max:12",
            'cpf'          => $sometimes."required|string|max:11|min:11|unique:users,cpf".$id,
            'email'        => $sometimes."required|email|max:200|unique:users,email".$id,
        ];

        return $attributes;
    }

    /**
     * @return array
     */
    public function getFullDetails(): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'email'        => $this->email,
            'cpf'          => $this->cpf,
            'phone_number' => $this->phone_number,
            'updated_at'   => $this->getDateTimeStringFrom('updated_at'),
            'created_at'   => $this->getDateTimeStringFrom('created_at'),
        ];
    }
}
