<?php

namespace App\Services\Imports;

use App\Models\Supplier;
use App\Services\Imports\Contracts\VehicleXmlParser;
use App\Services\Imports\Parsers\PolarisParser;
use App\Services\Imports\Parsers\GeelyParser;
use App\Services\Imports\Parsers\BrpParser;
use App\Services\Imports\Parsers\KawasakiMexicoParser;
use RuntimeException;

class VehicleParserResolver
{
    public function resolve(
        Supplier $supplier
    ): VehicleXmlParser {
        return match ($supplier->parser_key) {
            'polaris' =>
            app(PolarisParser::class),

            'geely' =>
            app(GeelyParser::class),

            'brp' =>
            app(BrpParser::class),

            'kawasaki_mexico' =>
            app(KawasakiMexicoParser::class),

            default =>
            throw new RuntimeException(
                "No existe parser para '{$supplier->parser_key}'."
            ),
        };
    }
}
