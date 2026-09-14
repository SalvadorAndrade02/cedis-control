<?php

namespace App\Livewire;

use App\Enums\MilestoneStage;
use App\Models\Unit;
use App\Services\Milestones\CompleteArrivalService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class ArrivalEvidence extends Component
{
    use WithFileUploads;

    public int $unitId;

    /*
    |--------------------------------------------------------------------------
    | EVIDENCIAS DEFINITIVAS
    |--------------------------------------------------------------------------
    |
    | Aquí terminan tanto las fotografías tomadas con cámara
    | como las seleccionadas desde la galería.
    |
    */

    public array $photos = [];


    /*
    |--------------------------------------------------------------------------
    | CARGAS TEMPORALES
    |--------------------------------------------------------------------------
    */

    public $cameraPhoto = null;

    public array $galleryPhotos = [];


    public string $observations = '';

    public ?string $errorMessage = null;


    public function mount(Unit $unit): void
    {
        $this->unitId = $unit->id;
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDACIÓN FINAL
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [

            'photos' => [
                'required',
                'array',
                'min:1',
                'max:15',
            ],

            'photos.*' => [
                'image',
                'max:10240',
            ],

            'observations' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | FOTO TOMADA CON CÁMARA
    |--------------------------------------------------------------------------
    */

    public function updatedCameraPhoto(): void
    {
        if (!$this->cameraPhoto) {
            return;
        }


        /*
         * Validamos solamente la fotografía
         * que acaba de llegar desde la cámara.
         */
        $this->validate([
            'cameraPhoto' => [
                'image',
                'max:10240',
            ],
        ]);


        /*
         * Evitamos superar el máximo global.
         */
        if (count($this->photos) >= 15) {

            $this->addError(
                'cameraPhoto',
                'Puedes registrar como máximo 15 fotografías.'
            );

            $this->reset('cameraPhoto');

            return;
        }


        /*
         * Agregamos la nueva fotografía
         * sin eliminar las anteriores.
         */
        $this->photos[] =
            $this->cameraPhoto;


        /*
         * Limpiamos únicamente el input
         * temporal de cámara.
         */
        $this->reset('cameraPhoto');

        $this->resetValidation(
            'cameraPhoto'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FOTOS SELECCIONADAS DESDE GALERÍA
    |--------------------------------------------------------------------------
    */

    public function updatedGalleryPhotos(): void
    {
        if (empty($this->galleryPhotos)) {
            return;
        }


        /*
         * Validamos la nueva selección.
         */
        $this->validate([

            'galleryPhotos' => [
                'array',
                'max:15',
            ],

            'galleryPhotos.*' => [
                'image',
                'max:10240',
            ],
        ]);


        $newTotal =
            count($this->photos)
            + count($this->galleryPhotos);


        /*
         * El máximo continúa siendo 15
         * independientemente del origen.
         */
        if ($newTotal > 15) {

            $available =
                max(
                    0,
                    15 - count($this->photos)
                );


            $this->addError(
                'galleryPhotos',
                "Sólo puedes agregar {$available} fotografía(s) más."
            );


            $this->galleryPhotos = [];

            return;
        }


        /*
         * Acumulamos las fotografías nuevas.
         */
        $this->photos = array_values([
            ...$this->photos,
            ...$this->galleryPhotos,
        ]);


        /*
         * Limpiamos únicamente la selección
         * temporal de galería.
         */
        $this->galleryPhotos = [];

        $this->resetValidation(
            'galleryPhotos'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETAR LLEGADA
    |--------------------------------------------------------------------------
    */

    public function complete(
        CompleteArrivalService $service
    ) {
        $this->validate();

        $this->errorMessage = null;


        try {

            $unit = Unit::findOrFail(
                $this->unitId
            );


            $service->execute(
                unit: $unit,

                photos: $this->photos,

                observations:
                trim($this->observations)
                ?: null,

                userId:
                (int) Auth::id(),
            );


            session()->flash(
                'success',
                'La llegada fue documentada correctamente.'
            );


            return redirect()
                ->route(
                    'units.show',
                    $unit
                );

        } catch (Throwable $exception) {

            report($exception);

            $this->errorMessage =
                $exception->getMessage();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR FOTO DEL PREVIEW
    |--------------------------------------------------------------------------
    */

    public function removePhoto(
        int $index
    ): void {

        unset(
            $this->photos[$index]
        );

        $this->photos = array_values(
            $this->photos
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $unit = Unit::query()
            ->with([
                'milestones' => fn($query) =>
                    $query->where(
                        'stage',
                        MilestoneStage::ARRIVAL->value
                    ),
            ])
            ->findOrFail(
                $this->unitId
            );


        $milestone =
            $unit->milestones->first();


        return view(
            'livewire.arrival-evidence',
            compact(
                'unit',
                'milestone'
            )
        );
    }
}