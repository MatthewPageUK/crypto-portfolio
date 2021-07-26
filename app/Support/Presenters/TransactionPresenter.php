<?php

namespace App\Support\Presenters;

trait TransactionPresenter
{

    /**
     * Return a colour associated with this transaction
     * 
     * @return string
     */
    public function colour()
    {
        return $this->isBuy() ? 'green' : 'red';
    }

    /**
     * Return past tense of the transaction type
     * 
     * @param int $case     0 - lowercase , 1 - Upper Words , 2 - UPPER
     * @return string 
     */
    public function pastTenseType( $case = 0 ): string
    {
        $buy = ['bought', 'purchased', 'picked up', 'aquired', 'obtained', 'snapped up', 'grabbed', 'took', 'procured', 'came by', 'invested in', 'secured', 'gained'];
        $sell = ['sold', 'got rid of', 'dumped', 'ejected', 'traded', 'disposed of', 'threw out', 'binned', 'cashed in', 'exited'];
        
        if( $this->isBuy() )
        {
            $r = array_rand($buy);
            $type = $buy[$r];
        }
        else
        {
            $r = array_rand($sell);
            $type = $sell[$r];
        }
        switch( $case )
        {
            case 1 : 
                $type = ucfirst($type);
                break;

            case 2 : 
                $type = strtoupper($type);
        }

        return $type;
    }
    
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