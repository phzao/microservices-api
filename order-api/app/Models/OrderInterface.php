<?php

namespace App\Models;

/**
 * Interface OrderInterface
 * @package App\Models
 */
interface OrderInterface extends ModelInterface
{
    public function fillTotalValue():void;
}
