<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function download(
        Document $document
    ): StreamedResponse {
        abort_unless(
            Storage::disk(
                $document->storage_disk
            )->exists(
                $document->storage_path
            ),
            404
        );

        return Storage::disk(
            $document->storage_disk
        )->download(
            $document->storage_path,
            $document->original_filename
        );
    }
}
