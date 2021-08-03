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
            fputcsv($file, ['istoken', 'id', 'symbol', 'name', 'created_at', 'updated_at', 'balancecheck', 'transactionscheck']);

            $tokens = Token::all();

            foreach($tokens as $token)
            {
                fputcsv($file, [
                    'Yes', 
                    $token->id, 
                    $token->symbol, 
                    $token->name, 
                    $token->created_at, 
                    $token->updated_at, 
                    $token->balance()->getValue(), 
                    $token->transactions()->count(),
                ]);
            }

            $transactions = Transaction::all();

            foreach($transactions as $transaction)
            {
                fputcsv($file, [
                    'No', 
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

        $data = File::get($request->file('backupfile')->getPathname());

        dd($data);

        return redirect()
            ->route('dashboard')
            ->with('success', __('Backup restored....'));
    }    
}
