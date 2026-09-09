<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Unit;
use App\Services\Imports\ImportPreviewService;
use App\Services\Imports\ImportUnitsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class ImportUnits extends Component
{
    use WithFileUploads;

    public $xmlFile = null;

    public $pdfFile = null;

    /*
    |--------------------------------------------------------------------------
    | PREVIEW ORIGINAL
    |--------------------------------------------------------------------------
    |
    | Información completa generada por ImportPreviewService.
    |
    */

    public array $preview = [];

    /*
    |--------------------------------------------------------------------------
    | DATOS DE UNIDADES
    |--------------------------------------------------------------------------
    |
    | originalUnits:
    | conserva exactamente lo detectado por el parser.
    |
    | editableUnits:
    | es lo que el usuario puede corregir antes de importar.
    |
    */

    public array $originalUnits = [];

    public array $editableUnits = [];

    /*
    |--------------------------------------------------------------------------
    | CATÁLOGO DE MARCAS
    |--------------------------------------------------------------------------
    */

    public array $brands = [];

    public bool $analyzed = false;

    public bool $reviewAccepted = false;

    public ?string $errorMessage = null;

    public ?string $successMessage = null;


    public function mount(): void
    {
        /*
         * Sólo permitimos elegir marcas existentes
         * y activas en el sistema.
         */
        $this->brands = Brand::query()
            ->where('active', true)
            ->orderBy('name')
            ->pluck('name')
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDACIÓN DE ARCHIVOS
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [
            'xmlFile' => [
                'required',
                'file',
                'mimes:xml',
                'max:10240',
            ],

            'pdfFile' => [
                'required',
                'file',
                'mimes:pdf',
                'max:20480',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDACIÓN DE DATOS EDITABLES
    |--------------------------------------------------------------------------
    */

    protected function editableRules(): array
    {
        return [
            'editableUnits' => [
                'required',
                'array',
                'min:1',
            ],

            'editableUnits.*.vin' => [
                'required',
                'string',
                'max:50',
            ],

            'editableUnits.*.brand' => [
                'required',
                'string',

                Rule::exists(
                    'brands',
                    'name'
                )->where(
                        fn($query) =>
                        $query->where(
                            'active',
                            true
                        )
                    ),
            ],

            'editableUnits.*.model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'editableUnits.*.version' => [
                'nullable',
                'string',
                'max:255',
            ],

            'editableUnits.*.year' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . (now()->year + 2),
            ],

            'editableUnits.*.exterior_color' => [
                'nullable',
                'string',
                'max:150',
            ],

            'editableUnits.*.interior_color' => [
                'nullable',
                'string',
                'max:150',
            ],

            'editableUnits.*.engine_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'editableUnits.*.pedimento' => [
                'nullable',
                'string',
                'max:100',
            ],

            'editableUnits.*.purchase_order' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ARCHIVOS MODIFICADOS
    |--------------------------------------------------------------------------
    */

    public function updatedXmlFile(): void
    {
        $this->resetAnalysis();
    }


    public function updatedPdfFile(): void
    {
        $this->resetAnalysis();
    }


    /*
    |--------------------------------------------------------------------------
    | ANALIZAR XML / PDF
    |--------------------------------------------------------------------------
    */

    public function analyze(
        ImportPreviewService $previewService
    ): void {

        $this->validate();

        $this->errorMessage = null;
        $this->successMessage = null;
        $this->reviewAccepted = false;

        try {

            $this->preview = $previewService
                ->preview(
                    xmlPath:
                    $this->xmlFile
                        ->getRealPath(),

                    xmlOriginalFilename:
                    $this->xmlFile
                        ->getClientOriginalName(),

                    pdfOriginalFilename:
                    $this->pdfFile
                        ->getClientOriginalName(),
                );


            /*
             * Convertimos las unidades detectadas
             * a una estructura editable compatible
             * con Livewire.
             */
            $this->buildEditableUnitsFromPreview();


            if ($this->editableUnits === []) {
                throw new \RuntimeException(
                    'No se detectaron unidades editables en la vista previa.'
                );
            }


            $this->analyzed = true;

        } catch (Throwable $exception) {

            report($exception);

            $this->errorMessage =
                $exception->getMessage();

            $this->preview = [];

            $this->originalUnits = [];

            $this->editableUnits = [];

            $this->analyzed = false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR IMPORTACIÓN
    |--------------------------------------------------------------------------
    */

    public function confirmImport(
        ImportUnitsService $service
    ): void {

        if (!$this->analyzed) {

            $this->errorMessage =
                'Primero debes analizar los archivos.';

            return;
        }


        $this->errorMessage = null;


        /*
         * Normalizamos espacios y mayúsculas
         * antes de validar.
         */
        $this->normalizeEditableUnits();


        /*
         * Validamos lo que actualmente aparece
         * en pantalla, NO únicamente lo que
         * detectó originalmente el parser.
         */
        $this->validate(
            $this->editableRules(),
            [
                'editableUnits.*.vin.required' =>
                    'El VIN es obligatorio.',

                'editableUnits.*.brand.required' =>
                    'La marca es obligatoria.',

                'editableUnits.*.brand.exists' =>
                    'La marca seleccionada no está registrada o está inactiva.',

                'editableUnits.*.year.integer' =>
                    'El año debe ser un número válido.',

                'editableUnits.*.year.min' =>
                    'El año indicado no es válido.',

                'editableUnits.*.year.max' =>
                    'El año indicado no es válido.',
            ]
        );


        /*
         * Ahora comprobamos VIN duplicados
         * usando los valores que el usuario
         * realmente quiere importar.
         */
        if (!$this->validateEditableVins()) {
            return;
        }


        /*
         * Si el parser marcó la importación
         * como revisión requerida, seguimos
         * obligando al usuario a confirmarla.
         */
        if (
            ($this->preview['requires_review']
                ?? false)
            && !$this->reviewAccepted
        ) {

            $this->errorMessage =
                'Debes confirmar que revisaste los datos detectados.';

            return;
        }


        try {

            $result = $service->import(
                xmlPath:
                $this->xmlFile
                    ->getRealPath(),

                pdfPath:
                $this->pdfFile
                    ->getRealPath(),

                userId:
                Auth::id(),

                xmlOriginalFilename:
                $this->xmlFile
                    ->getClientOriginalName(),

                pdfOriginalFilename:
                $this->pdfFile
                    ->getClientOriginalName(),

                /*
                 * Este parámetro lo agregaremos
                 * ahora al ImportUnitsService.
                 */
                unitOverrides:
                $this->buildUnitOverrides(),
            );


            $count = $result
                ->units
                ->count();


            $this->successMessage =
                $count === 1
                ? 'La unidad fue importada correctamente.'
                : "{$count} unidades fueron importadas correctamente.";


            $this->xmlFile = null;

            $this->pdfFile = null;

            $this->preview = [];

            $this->originalUnits = [];

            $this->editableUnits = [];

            $this->analyzed = false;

            $this->reviewAccepted = false;

        } catch (Throwable $exception) {

            report($exception);

            $this->errorMessage =
                $exception->getMessage();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CREAR DATOS EDITABLES
    |--------------------------------------------------------------------------
    */

    private function buildEditableUnitsFromPreview(): void
    {
        $this->editableUnits = [];

        $previewUnits =
            $this->preview['units']
            ?? [];

        foreach ($previewUnits as $unit) {

            $this->editableUnits[] = [

                'vin' =>
                    strtoupper(
                        trim(
                            (string) data_get(
                                $unit,
                                'vin',
                                ''
                            )
                        )
                    ),

                'brand' =>
                    strtoupper(
                        trim(
                            (string) data_get(
                                $unit,
                                'brand',
                                ''
                            )
                        )
                    ),

                'model' =>
                    $this->nullableString(
                        data_get(
                            $unit,
                            'model'
                        )
                    ),

                'version' =>
                    $this->nullableString(
                        data_get(
                            $unit,
                            'version'
                        )
                    ),

                'year' =>
                    data_get(
                        $unit,
                        'year'
                    ),

                'exterior_color' =>
                    $this->nullableString(
                        data_get(
                            $unit,
                            'exterior_color',
                            data_get(
                                $unit,
                                'exteriorColor'
                            )
                        )
                    ),

                'interior_color' =>
                    $this->nullableString(
                        data_get(
                            $unit,
                            'interior_color',
                            data_get(
                                $unit,
                                'interiorColor'
                            )
                        )
                    ),

                'engine_number' =>
                    $this->nullableString(
                        data_get(
                            $unit,
                            'engine_number',
                            data_get(
                                $unit,
                                'engineNumber'
                            )
                        )
                    ),

                'pedimento' =>
                    $this->nullableString(
                        data_get(
                            $unit,
                            'pedimento'
                        )
                    ),

                'purchase_order' =>
                    $this->nullableString(
                        data_get(
                            $unit,
                            'purchase_order',
                            data_get(
                                $unit,
                                'purchaseOrder'
                            )
                        )
                    ),
            ];
        }

        /*
         * Copia independiente de los datos originales.
         *
         * editableUnits cambiará cuando el usuario edite.
         * originalUnits permanecerá intacto.
         */
        $this->originalUnits =
            $this->editableUnits;
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZACIÓN
    |--------------------------------------------------------------------------
    */

    private function normalizeEditableUnits(): void
    {
        foreach (
            $this->editableUnits
            as $index => $unit
        ) {

            $this->editableUnits[$index]['vin'] =
                strtoupper(
                    trim(
                        (string) (
                            $unit['vin']
                            ?? ''
                        )
                    )
                );


            $this->editableUnits[$index]['brand'] =
                strtoupper(
                    trim(
                        (string) (
                            $unit['brand']
                            ?? ''
                        )
                    )
                );


            foreach (
                [
                    'model',
                    'version',
                    'exterior_color',
                    'interior_color',
                    'engine_number',
                    'pedimento',
                    'purchase_order',
                ]
                as $field
            ) {

                $value =
                    trim(
                        (string) (
                            $unit[$field]
                            ?? ''
                        )
                    );


                $this->editableUnits[
                    $index
                ][
                    $field
                ] =
                    $value !== ''
                    ? $value
                    : null;
            }


            if (
                isset($unit['year'])
                && trim(
                    (string) $unit['year']
                ) === ''
            ) {

                $this->editableUnits[
                    $index
                ]['year'] = null;
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR VIN ACTUAL
    |--------------------------------------------------------------------------
    */

    private function validateEditableVins(): bool
    {
        $vins = collect(
            $this->editableUnits
        )
            ->pluck('vin')
            ->filter()
            ->map(
                fn($vin) =>
                strtoupper(
                    trim(
                        (string) $vin
                    )
                )
            );


        /*
         * VIN repetido dentro del mismo XML
         * después de una edición manual.
         */
        $duplicatedVins =
            $vins
                ->countBy()
                ->filter(
                    fn($count) =>
                    $count > 1
                )
                ->keys();


        if ($duplicatedVins->isNotEmpty()) {

            $this->errorMessage =
                'Existen VIN duplicados dentro de la importación: '
                . $duplicatedVins->implode(', ');

            return false;
        }


        /*
         * VIN que ya existe en la BD.
         */
        $existingVins =
            Unit::query()
                ->whereIn(
                    'vin',
                    $vins->values()->all()
                )
                ->pluck('vin');


        if ($existingVins->isNotEmpty()) {

            $this->errorMessage =
                'Ya existen unidades registradas con VIN: '
                . $existingVins->implode(', ');

            return false;
        }


        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | CONSTRUIR OVERRIDES
    |--------------------------------------------------------------------------
    |
    | Además de los valores nuevos enviamos:
    |
    | - index
    | - original_vin
    |
    | Esto permitirá que ImportUnitsService
    | identifique exactamente qué unidad del
    | parser corresponde con la edición.
    |
    */

    private function buildUnitOverrides(): array
    {
        return collect(
            $this->editableUnits
        )
            ->map(
                function (array $unit, int $index) {

                    return [
                        'index' =>
                            $index,

                        'original_vin' =>
                            $this->originalUnits[
                                $index
                            ]['vin']
                            ?? null,

                        'vin' =>
                            $unit['vin'],

                        'brand' =>
                            $unit['brand'],

                        'model' =>
                            $unit['model'],

                        'version' =>
                            $unit['version'],

                        'year' =>
                            !empty(
                            $unit['year']
                        )
                            ? (int) $unit['year']
                            : null,

                        'exterior_color' =>
                            $unit[
                                'exterior_color'
                            ],

                        'interior_color' =>
                            $unit[
                                'interior_color'
                            ],

                        'engine_number' =>
                            $unit[
                                'engine_number'
                            ],

                        'pedimento' =>
                            $unit['pedimento'],

                        'purchase_order' =>
                            $unit[
                                'purchase_order'
                            ],
                    ];
                }
            )
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    private function nullableString(
        mixed $value
    ): ?string {

        if ($value === null) {
            return null;
        }


        $value =
            trim(
                (string) $value
            );


        return $value !== ''
            ? $value
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    private function resetAnalysis(): void
    {
        $this->preview = [];

        $this->originalUnits = [];

        $this->editableUnits = [];

        $this->analyzed = false;

        $this->reviewAccepted = false;

        $this->errorMessage = null;

        $this->successMessage = null;
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.import-units'
        );
    }
}