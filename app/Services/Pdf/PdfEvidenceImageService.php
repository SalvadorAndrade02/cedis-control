<?php

namespace App\Services\Pdf;

use App\Models\Evidence;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PdfEvidenceImageService
{
    public function toDataUri(
        Evidence $evidence
    ): ?string {

        $disk = Storage::disk(
            $evidence->storage_disk
        );

        if (
            !$disk->exists(
                $evidence->storage_path
            )
        ) {
            return null;
        }

        $path = $disk->path(
            $evidence->storage_path
        );

        $mime = mime_content_type($path)
            ?: 'image/jpeg';

        if (
            !str_starts_with(
                $mime,
                'image/'
            )
        ) {
            return null;
        }

        /*
         * Intentamos generar una versión reducida
         * para evitar PDFs gigantes.
         */
        if (
            extension_loaded('gd')
        ) {
            try {

                $optimized =
                    $this->optimizeImage(
                        $path,
                        $mime
                    );

                if ($optimized !== null) {
                    return
                        'data:image/jpeg;base64,'
                        . base64_encode(
                            $optimized
                        );
                }

            } catch (Throwable $exception) {
                report($exception);
            }
        }

        /*
         * Fallback:
         * usamos la imagen original.
         */
        $contents = file_get_contents(
            $path
        );

        if ($contents === false) {
            return null;
        }

        return
            "data:{$mime};base64,"
            . base64_encode(
                $contents
            );
    }


    private function optimizeImage(
        string $path,
        string $mime
    ): ?string {

        $contents = file_get_contents(
            $path
        );

        if ($contents === false) {
            return null;
        }

        $source = @imagecreatefromstring(
            $contents
        );

        if (!$source) {
            return null;
        }

        /*
         * Corregir orientación EXIF
         * cuando sea JPEG.
         */
        if (
            $mime === 'image/jpeg'
            && function_exists(
                'exif_read_data'
            )
        ) {

            $exif = @exif_read_data(
                $path
            );

            $orientation =
                $exif['Orientation']
                ?? null;

            if ($orientation === 3) {
                $source =
                    imagerotate(
                        $source,
                        180,
                        0
                    );
            }

            if ($orientation === 6) {
                $source =
                    imagerotate(
                        $source,
                        -90,
                        0
                    );
            }

            if ($orientation === 8) {
                $source =
                    imagerotate(
                        $source,
                        90,
                        0
                    );
            }
        }


        $width = imagesx(
            $source
        );

        $height = imagesy(
            $source
        );


        /*
         * Tamaño máximo para documento.
         */
        $maxWidth = 1400;

        $maxHeight = 1050;


        $scale = min(
            1,
            $maxWidth / $width,
            $maxHeight / $height
        );


        $newWidth = max(
            1,
            (int) round(
                $width * $scale
            )
        );

        $newHeight = max(
            1,
            (int) round(
                $height * $scale
            )
        );


        $canvas = imagecreatetruecolor(
            $newWidth,
            $newHeight
        );


        /*
         * Fondo blanco.
         */
        $white = imagecolorallocate(
            $canvas,
            255,
            255,
            255
        );

        imagefill(
            $canvas,
            0,
            0,
            $white
        );


        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );


        ob_start();

        imagejpeg(
            $canvas,
            null,
            78
        );

        $result =
            ob_get_clean();


        imagedestroy(
            $source
        );

        imagedestroy(
            $canvas
        );


        return is_string($result)
            ? $result
            : null;
    }
}