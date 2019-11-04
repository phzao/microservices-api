<?php declare(strict_types=1);

namespace App\Models;

/**
 * Interface ModelInterface
 * @package App\Models
 */
interface ModelInterface 
{
    /**
     * @param null $id
     *
     * @return array
     */
    public function rules($id = null): array;

    /**
     * @return array
     */
    public function getFullDetails(): array;

    /**
     * @return array
     */
    public function getFullDataToIndex(): array;

    /**
     * @param string $column
     * @param string $format
     *
     * @return mixed
     */
    public function getDateTimeStringFrom(string $column, $format = "Y-m-d H:i:s"): string;

    /**
     * @param array $data
     *
     * @return array
     */
    public function getSearchParams(array $data): array;
}
