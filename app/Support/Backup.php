<?php

namespace App\Support;

use App\Interfaces\BackupInterface;
use App\Models\Token;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Backup implements BackupInterface
{
    private array $headerFields = [
        'tk_id', 
        'tk_symbol', 
        'tk_name', 
        'tk_created_at', 
        'tk_updated_at',
        'tr_id', 
        'tr_token_id', 
        'tr_quantity', 
        'tr_price', 
        'tr_type', 
        'tr_time', 
        'tr_created_at', 
        'tr_updated_at'
    ];

   /**
     * Strean the backup file to the client
     *
     * @return Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(): StreamedResponse
    {
        $tokens = Token::all();
        $transactions = Transaction::all();
        $filename = 'mcpt-backup-'.now()->format('m-d-Y-H-i').'-v'.config('app.version').'.csv';     

        return response()->streamDownload(function() use ($tokens, $transactions) {

            $file = fopen('php://output', 'w+');

            /**
             * Version - useful to maintain backwards com.
             */
            fputcsv($file, [
                config('app.version'), '', '', '', '', '', '', '', '', '', '', '', ''
            ]);            

            /**
             * Header row for both tokens and transactions
             */
            fputcsv($file, $this->headerFields);

            /**
             * Output the tokens as csv
             */
            foreach($tokens as $token)
            {
                fputcsv($file, [
                    $token->id, 
                    $token->symbol, 
                    $token->name, 
                    $token->created_at, 
                    $token->updated_at, 
                    '', '', '', '', '', '', '', '',
                ]);
            }

            /**
             * Output the transactions as csv
             */
            foreach($transactions as $transaction)
            {
                fputcsv($file, [
                    '', '', '', '', '', 
                    $transaction->id, 
                    $transaction->token_id, 
                    $transaction->quantity->getValue(), 
                    $transaction->price->getValue(), 
                    $transaction->type, 
                    $transaction->time, 
                    $transaction->created_at, 
                    $transaction->updated_at, 
                ]);
            }

        }, $filename);        

    }

    /**
     * Restore the backup from the client file
     * 
     * @param Illuminate\Http\Request
     * @return Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request): RedirectResponse
    {
        $data = array_map('str_getcsv', file($request->file('backupfile')->getPathname()));
        $version = array_shift($data);

        if( $version[0] !== config('app.version'))
        {
            return redirect()
                ->route('dashboard')
                ->with('failure', __('Backup file ['.$version[0].'] is the wrong version expected ['.config('app.version').']'));        
        }

        $header = array_shift($data);

        if($this->headerFields !== $header)
        {
            return redirect()
                ->route('dashboard')
                ->with('failure', __('Backup file has invalid headers'));
        }

        array_walk($data, function (&$row, $key, $header) {
            $row = array_combine($header, $row);
        }, $header);

        dd($version[0], $data);

        /**
         * Do the restore ........
         */

        return redirect()
            ->route('dashboard')
            ->with('success', __('Backup restored....'));
    }    
}
