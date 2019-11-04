<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelBase
 * @package App\Models
 */
class ModelBase extends Model
{
    /**
     * @param string $column
     * @param string $format
     *
     * @return string
     */
    public function getDateTimeStringFrom(string $column, $format = "Y-m-d H:i:s"): string
    {
        if (empty($this->$column)) {
            return "";
        }

        return $this->$column->format($format);
    }
}
