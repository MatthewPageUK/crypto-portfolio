<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{

    /**
     * Download the backup file.
     *
     * @return \Illuminate\Http\Response
     */
    public function download()
    {
        $filename = 'test.csv';     

        return response()->streamDownload(function() {

            $file = fopen('php://output', 'w+');

            fputcsv($file, [
                'tk_id', 'tk_symbol', 'tk_name', 'tk_created_at', 'tk_updated_at',
                'tr_id', 'tr_token_id', 'tr_quantity', 'tr_price', 'tr_type', 'tr_time', 'tr_created_at', 'tr_updated_at'
            ]);

            $tokens = Token::all();

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

            $transactions = Transaction::all();

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
     * Upload a backup file.
     *
     * @param  \App\Models\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        return view('backup-upload');
    }

    /**
     * Restore the database with the uploaded file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $data = array_map('str_getcsv', file($request->file('backupfile')->getPathname()));
        $header = array_shift($data);

        array_walk($data, function (&$row, $key, $header) {
            $row = array_combine($header, $row);
        }, $header);

        dd($data);

        return redirect()
            ->route('dashboard')
            ->with('success', __('Backup restored....'));
    }    
}
