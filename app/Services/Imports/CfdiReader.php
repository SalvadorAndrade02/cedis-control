<?php

namespace App\Services\Imports;

use App\Services\Imports\DTOs\CfdiContext;
use App\Services\Imports\DTOs\CfdiDocumentData;
use Carbon\CarbonImmutable;
use DOMDocument;
use DOMElement;
use DOMXPath;
use RuntimeException;

class CfdiReader
{
    public function read(string $filePath): CfdiContext
    {
        if (! is_file($filePath)) {
            throw new RuntimeException(
                "El archivo XML no existe: {$filePath}"
            );
        }

        $xml = file_get_contents($filePath);

        if ($xml === false) {
            throw new RuntimeException(
                "No fue posible leer el archivo XML."
            );
        }

        libxml_use_internal_errors(true);

        $dom = new DOMDocument();

        $loaded = $dom->loadXML(
            $xml,
            LIBXML_NONET | LIBXML_NOBLANKS
        );

        if (! $loaded) {
            $errors = libxml_get_errors();

            libxml_clear_errors();

            $messages = array_map(
                fn($error) => trim($error->message),
                $errors
            );

            throw new RuntimeException(
                'XML inválido: ' . implode(' | ', $messages)
            );
        }

        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        /*
         * Namespaces que encontramos en los XML reales.
         */
        $xpath->registerNamespace(
            'cfdi',
            'http://www.sat.gob.mx/cfd/4'
        );

        $xpath->registerNamespace(
            'tfd',
            'http://www.sat.gob.mx/TimbreFiscalDigital'
        );

        $xpath->registerNamespace(
            'ventavehiculos',
            'http://www.sat.gob.mx/ventavehiculos'
        );

        $xpath->registerNamespace(
            'fx',
            'http://www.fact.com.mx/schema/fx'
        );

        $comprobante = $xpath
            ->query('/cfdi:Comprobante')
            ?->item(0);

        if (! $comprobante instanceof DOMElement) {
            throw new RuntimeException(
                'No se encontró cfdi:Comprobante.'
            );
        }

        $emisor = $xpath
            ->query('/cfdi:Comprobante/cfdi:Emisor')
            ?->item(0);

        $receptor = $xpath
            ->query('/cfdi:Comprobante/cfdi:Receptor')
            ?->item(0);

        $timbre = $xpath
            ->query('//tfd:TimbreFiscalDigital')
            ?->item(0);

        $impuestos = $xpath
            ->query('/cfdi:Comprobante/cfdi:Impuestos')
            ?->item(0);

        $data = new CfdiDocumentData(
            version: $this->attribute(
                $comprobante,
                'Version'
            ),

            series: $this->attribute(
                $comprobante,
                'Serie'
            ),

            folio: $this->attribute(
                $comprobante,
                'Folio'
            ),

            issuedAt: $this->date(
                $this->attribute(
                    $comprobante,
                    'Fecha'
                )
            ),

            certifiedAt: $timbre instanceof DOMElement
                ? $this->date(
                    $this->attribute(
                        $timbre,
                        'FechaTimbrado'
                    )
                )
                : null,

            currency: $this->attribute(
                $comprobante,
                'Moneda'
            ),

            paymentMethod: $this->attribute(
                $comprobante,
                'MetodoPago'
            ),

            paymentForm: $this->attribute(
                $comprobante,
                'FormaPago'
            ),

            issuerRfc: $emisor instanceof DOMElement
                ? $this->attribute($emisor, 'Rfc')
                : null,

            issuerName: $emisor instanceof DOMElement
                ? $this->attribute($emisor, 'Nombre')
                : null,

            receiverRfc: $receptor instanceof DOMElement
                ? $this->attribute($receptor, 'Rfc')
                : null,

            receiverName: $receptor instanceof DOMElement
                ? $this->attribute($receptor, 'Nombre')
                : null,

            uuid: $timbre instanceof DOMElement
                ? $this->attribute($timbre, 'UUID')
                : null,

            subtotal: $this->attribute(
                $comprobante,
                'SubTotal'
            ),

            tax: $impuestos instanceof DOMElement
                ? $this->attribute(
                    $impuestos,
                    'TotalImpuestosTrasladados'
                )
                : null,

            total: $this->attribute(
                $comprobante,
                'Total'
            ),
        );

        return new CfdiContext(
            dom: $dom,
            xpath: $xpath,
            data: $data,
        );
    }

    private function attribute(
        DOMElement $element,
        string $name
    ): ?string {
        if (! $element->hasAttribute($name)) {
            return null;
        }

        $value = trim(
            $element->getAttribute($name)
        );

        return $value !== ''
            ? $value
            : null;
    }

    private function date(
        ?string $value
    ): ?CarbonImmutable {
        if ($value === null) {
            return null;
        }

        return CarbonImmutable::parse($value);
    }
}
