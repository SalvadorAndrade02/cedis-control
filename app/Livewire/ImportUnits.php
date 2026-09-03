<?php

namespace App\Livewire;

use App\Services\Imports\ImportPreviewService;
use App\Services\Imports\ImportUnitsService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Throwable;

class ImportUnits extends Component
{
    use WithFileUploads;

    public $xmlFile = null;

    public $pdfFile = null;

    public array $preview = [];

    public bool $analyzed = false;

    public bool $reviewAccepted = false;

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

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

    public function updatedXmlFile(): void
    {
        $this->resetAnalysis();
    }

    public function updatedPdfFile(): void
    {
        $this->resetAnalysis();
    }

    public function analyze(
        ImportPreviewService $previewService
    ): void {
        $this->validate();

        $this->errorMessage = null;
        $this->successMessage = null;

        try {
            $this->preview = $previewService
                ->preview(
                    xmlPath: $this->xmlFile
                        ->getRealPath(),

                    xmlOriginalFilename: $this->xmlFile
                        ->getClientOriginalName(),

                    pdfOriginalFilename: $this->pdfFile
                        ->getClientOriginalName(),
                );

            $this->analyzed = true;
        } catch (Throwable $exception) {
            report($exception);

            $this->errorMessage =
                $exception->getMessage();

            $this->preview = [];
            $this->analyzed = false;
        }
    }

    public function confirmImport(
        ImportUnitsService $service
    ): void {
        if (! $this->analyzed) {
            $this->errorMessage =
                'Primero debes analizar los archivos.';

            return;
        }

        if (
            $this->preview['has_duplicates']
            ?? false
        ) {
            $this->errorMessage =
                'La importación contiene uno o más VIN ya registrados.';

            return;
        }

        if (
            ($this->preview['requires_review']
                ?? false)
            && ! $this->reviewAccepted
        ) {
            $this->errorMessage =
                'Debes confirmar que revisaste los datos detectados.';

            return;
        }

        try {
            $result = $service->import(
                xmlPath: $this->xmlFile
                    ->getRealPath(),

                pdfPath: $this->pdfFile
                    ->getRealPath(),

                userId: Auth::id(),

                xmlOriginalFilename: $this->xmlFile
                    ->getClientOriginalName(),

                pdfOriginalFilename: $this->pdfFile
                    ->getClientOriginalName(),
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
            $this->analyzed = false;
            $this->reviewAccepted = false;
        } catch (Throwable $exception) {
            report($exception);

            $this->errorMessage =
                $exception->getMessage();
        }
    }

    private function resetAnalysis(): void
    {
        $this->preview = [];
        $this->analyzed = false;
        $this->reviewAccepted = false;
        $this->errorMessage = null;
        $this->successMessage = null;
    }

    public function render()
    {
        return view('livewire.import-units');
    }
}
