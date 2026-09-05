<?php

namespace App\Http\Controllers;

use App\Models\Evidence;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EvidenceController extends Controller
{
    public function show(
        Evidence $evidence
    ): StreamedResponse {

        abort_unless(
            auth()->user()?->can('evidences.view'),
            403
        );

        $disk = Storage::disk(
            $evidence->storage_disk
        );

        abort_unless(
            $disk->exists(
                $evidence->storage_path
            ),
            404
        );

        return $disk->response(
            $evidence->storage_path,
            $evidence->original_filename ?? 'evidence',
            [
                'Content-Disposition' => 'inline',
            ]
        );
    }

    public function download(
        Evidence $evidence
    ): StreamedResponse {

        abort_unless(
            auth()->user()?->can('evidences.view'),
            403
        );

        $disk = Storage::disk(
            $evidence->storage_disk
        );

        abort_unless(
            $disk->exists(
                $evidence->storage_path
            ),
            404
        );

        return $disk->download(
            $evidence->storage_path,
            $evidence->original_filename
                ?? 'evidence'
        );
    }
}
