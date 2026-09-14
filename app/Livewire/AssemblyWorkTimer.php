<?php

namespace App\Livewire;

use App\Enums\AssemblyPauseReason;
use App\Enums\AssemblyWorkStatus;
use App\Enums\MilestoneStage;
use App\Models\AssemblyWorkSession;
use App\Models\Unit;
use App\Services\Assembly\AssemblyWorkTimeService;
use App\Support\DurationHelper;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Throwable;

class AssemblyWorkTimer extends Component
{
    public int $unitId;

    public bool $showPauseForm = false;

    public string $pauseReason = '';

    public string $pauseNotes = '';

    public ?string $errorMessage = null;


    public function mount(
        Unit $unit
    ): void {

        abort_unless(
            Auth::user()?->can(
                'assembly.complete'
            ),
            403
        );

        $this->unitId =
            $unit->id;
    }


    public function start(
        AssemblyWorkTimeService $service
    ): void {

        $this->errorMessage = null;

        try {

            $service->start(
                Unit::findOrFail(
                    $this->unitId
                ),
                (int) Auth::id()
            );

        } catch (Throwable $exception) {

            report($exception);

            $this->errorMessage =
                $exception->getMessage();
        }
    }


    public function openPause(): void
    {
        $this->showPauseForm = true;

        $this->pauseReason = '';

        $this->pauseNotes = '';

        $this->resetValidation();
    }


    public function cancelPause(): void
    {
        $this->showPauseForm = false;

        $this->pauseReason = '';

        $this->pauseNotes = '';

        $this->resetValidation();
    }


    public function pause(
        AssemblyWorkTimeService $service
    ): void {

        $validated =
            $this->validate([
                'pauseReason' => [
                    'required',
                    'string',
                ],

                'pauseNotes' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]);


        $reason =
            AssemblyPauseReason::tryFrom(
                $validated[
                    'pauseReason'
                ]
            );


        if (!$reason) {

            $this->addError(
                'pauseReason',
                'Selecciona un motivo válido.'
            );

            return;
        }


        $this->errorMessage = null;


        try {

            $service->pause(
                unit:
                Unit::findOrFail(
                    $this->unitId
                ),

                reason:
                $reason,

                notes:
                trim(
                    $validated[
                        'pauseNotes'
                    ] ?? ''
                ) ?: null,

                userId:
                (int) Auth::id()
            );


            $this->cancelPause();

        } catch (Throwable $exception) {

            report($exception);

            $this->errorMessage =
                $exception->getMessage();
        }
    }


    public function resume(
        AssemblyWorkTimeService $service
    ): void {

        $this->errorMessage = null;


        try {

            $service->resume(
                Unit::findOrFail(
                    $this->unitId
                ),
                (int) Auth::id()
            );

        } catch (Throwable $exception) {

            report($exception);

            $this->errorMessage =
                $exception->getMessage();
        }
    }


    public function render()
    {
        $unit = Unit::query()
            ->with([
                'milestones' =>
                    function ($query) {

                        $query
                            ->where(
                                'stage',
                                MilestoneStage::ASSEMBLY_COMPLETED
                                    ->value
                            )
                            ->with([
                                'assemblyWorkSession.pauses',
                            ]);
                    },
            ])
            ->findOrFail(
                $this->unitId
            );


        $milestone =
            $unit->milestones
                ->first();


        $session =
            $milestone
                    ?->assemblyWorkSession;


        $currentPause = null;

        $effectiveSeconds = 0;

        $pausedSeconds = 0;


        if ($session) {

            $pausedSeconds =
                $session
                    ->total_paused_seconds;


            /*
             * RUNNING:
             *
             * now - inicio - pausas cerradas
             */
            if (
                $session->status
                === AssemblyWorkStatus::RUNNING
            ) {

                $elapsed =
                    $session
                        ->started_at
                        ->diffInSeconds(
                            now()
                        );


                $effectiveSeconds =
                    max(
                        0,
                        $elapsed
                        - $pausedSeconds
                    );
            }


            /*
             * PAUSED:
             *
             * El tiempo efectivo se congela
             * exactamente en paused_at.
             */
            if (
                $session->status
                === AssemblyWorkStatus::PAUSED
            ) {

                $currentPause =
                    $session
                        ->pauses
                        ->first(
                            fn($pause) =>
                                $pause->resumed_at
                                === null
                        );


                if ($currentPause) {

                    $elapsed =
                        $session
                            ->started_at
                            ->diffInSeconds(
                                $currentPause
                                    ->paused_at
                            );


                    $effectiveSeconds =
                        max(
                            0,
                            $elapsed
                            - $pausedSeconds
                        );


                    $pausedSeconds +=
                        $currentPause
                            ->paused_at
                            ->diffInSeconds(
                                now()
                            );
                }
            }


            if (
                $session->status
                === AssemblyWorkStatus::COMPLETED
            ) {

                $effectiveSeconds =
                    $session
                        ->total_active_seconds;

                $pausedSeconds =
                    $session
                        ->total_paused_seconds;
            }
        }


        return view(
            'livewire.assembly-work-timer',
            [
                'unit' =>
                    $unit,

                'session' =>
                    $session,

                'currentPause' =>
                    $currentPause,

                'effectiveSeconds' =>
                    $effectiveSeconds,

                'effectiveFormatted' =>
                    DurationHelper::format(
                        $effectiveSeconds
                    ),

                'pausedFormatted' =>
                    DurationHelper::format(
                        $pausedSeconds
                    ),

                'pauseReasons' =>
                    AssemblyPauseReason::cases(),
            ]
        );
    }
}