<?php

namespace App\Support\Presenters;

trait TransactionPresenter
{
    /**
     * Return a human readable representation of this amount
     * 
     * "Buy 1.35 ADA at £0.034 on 18 July '21 at 02:11:20pm (Ref 203)
     * 
     * @return string
     */
    public function humanReadable(): string
    {
        return 
            ucwords($this->type) . 
            ' ' . 
            $this->quantity->humanReadable() . 
            ' ' . 
            $this->cryptoToken->symbol . 
            ' at ' . 
            $this->price->humanReadable() . 
            ' on ' . 
            $this->time->format('j F \'y \a\t h:i:s A') . 
            ' (Ref ' . 
            $this->id . 
            ')';         

    }
}