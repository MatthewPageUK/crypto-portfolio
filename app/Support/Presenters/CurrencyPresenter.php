<?php

namespace App\Support\Presenters;

use App\Support\Currency;

trait CurrencyPresenter
{
    /**
     * Return a human readable representation of this amount
     * 
     * £0
     * < £0.0001
     * £0.000235
     * £0.12345
     * £5.1234
     * £123.33
     * £1234
     * 
     * £100,000
     * 
     * £1.230m
     * £1.432b
     * 
     * @return string
     */
    public function humanReadable(): string
    {
        $amount = $this->value;
        $pre = "";
        $neg = false;

        if($amount < 0.0)
        {
            $neg = true;
            $amount = abs($amount);
        }
        if($amount === 0.0)
        {
            $text = "0";
        }
        elseif($amount <= 0.0001 && $amount > 0.0) 
        {
            $pre = "< ";
            $text = "0.0001";
        }
        elseif($amount < 0.1 && $amount > 0.0001) $text = number_format($amount, 6, '.', '');
        elseif($amount >= 0.1 && $amount < 1) $text = number_format($amount, 5, '.', '');
        elseif($amount >= 1 && $amount < 10) $text = number_format($amount, 4, '.', '');
        elseif($amount >= 10 && $amount < 10000) $text = number_format($amount, 2, '.', '');
        elseif($amount >= 10000 && $amount < 100000) $text = number_format($amount, 0, '.', '');
        elseif($amount >= 100000 && $amount < 1000000) $text = number_format($amount/1000, 2, '.', '').'k';
        elseif($amount >= 1000000) $text = number_format($amount/1000000, 2, '.', '').'m';
        else $text = $amount;

        return $pre.($neg ? '-':'').Currency::SYMBOL.$text;
    }
}