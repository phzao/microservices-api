<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * Class Order
 * @package App\Models
 */
class Order extends ModelBase implements OrderInterface
{
    use Searchable, SoftDeletes;

    const INDEX_ELASTIC_SEARCH = 'orders-table';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'item_description',
        'item_quantity',
        'item_price',
        'total_value'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function (Order $order) {
            $order->fillTotalValue();

        });

        static::updating(function (Order $order) {
            $order->fillTotalValue();
        });
    }

    public function fillTotalValue(): void
    {
        $total = $this->item_quantity * $this->item_price;

        if ((float) $total !== (float) $this->total_value) {
            $this->total_value = $total;
        }
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
            'user_id'          => $sometimes."required|integer",
            'item_description' => $sometimes."required|string|max:250",
            'item_quantity'    => $sometimes."nullable|numeric|min:0|max:999999999999999",
            'item_price'       => $sometimes."nullable|numeric|min:0|max:999999999999999",
            'total_value'      => $sometimes."nullable|numeric|min:0|max:99999999999999999",
        ];

        return $attributes;
    }

    /**
     * @return array
     */
    public function getFullDetails(): array
    {
        return [
            'user_id'          => $this->user_id,
            'item_description' => $this->item_description,
            'item_quantity'    => $this->item_quantity,
            'item_price'       => $this->item_price,
            'total_value'      => $this->total_value,
            'updated_at'       => $this->updated_at,
            'created_at'       => $this->created_at,
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

        if ($data['description']) {
            $description = $data['description'];

            $param['simple_query_string'] = [
                "query"  => "$description*",
                "fields" => ["item_description"]
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
}
