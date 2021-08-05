<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Backup;

class BackupController extends Controller
{

    /**
     * Download the backup file.
     *
     * @return Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Backup $backup)
    {
        return $backup->download();
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
    public function restore(Backup $backup, Request $request)
    {
        return $backup->restore($request);
    }    
}
