<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface BackupInterface
{

   /**
     * Strean the backup file to the client
     *
     * @return Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(): StreamedResponse;

    /**
     * Restore the backup from the client file
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request): RedirectResponse; 

}
