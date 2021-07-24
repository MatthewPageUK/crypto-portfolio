<?php

namespace App\Support\Presenters;

trait QuantityPresenter
{
    /**
     * Return a human readable representation of this amount
     * 
     * @return string
     */
    public function humanReadable(): string
    {
        $quantity = $this->value;
        $pre = "";

        if($quantity === 0.0)
        {
            $text = "-";
        }
        elseif($quantity <= 0.0001 && $quantity !== 0.0) 
        {
            $pre = "< ";
            $text = "0.0001";
        }
        elseif($quantity < 0.1 && $quantity > 0.0001) $text = number_format($quantity, 6, '.', '');
        elseif($quantity >= 0.1 && $quantity < 1) $text = number_format($quantity, 5, '.', '');
        elseif($quantity >= 1 && $quantity < 10) $text = number_format($quantity, 4, '.', '');
        elseif($quantity >= 10 && $quantity < 100000) $text = number_format($quantity, 2, '.', '');
        elseif($quantity >= 100000 && $quantity < 1000000) $text = number_format($quantity/1000, 2, '.', '').'k';
        elseif($quantity >= 1000000) $text = number_format($quantity/1000000, 2, '.', '').'m';
        else $text = $quantity;

        return $pre.$text;
    }
}