<?php

namespace App\Utilities;

class Shopping
{
    /**
     * @param $price
     * @param $discount
     * @return float|int|string
     */
    public static function discount($price, $discount)
    {
        if(!is_numeric($price) || !is_numeric($discount)){
            throw new \InvalidArgumentException('Invalid arguments passed');
        }

        if($price == 0 || $discount == 0){
            throw new \DivisionByZeroError('You passed in 0 as part of the arguements');
        }

        return $price - ($price * ($discount / 100));
    }
}
