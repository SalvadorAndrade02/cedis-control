@extends('layouts.app')

@section(
    'title',
    ($unit->model ?: $unit->vin) . ' | CEDIS'
)

@section('content')

    @php

        /*
        |--------------------------------------------------------------------------
        | CONFIGURACIÓN VISUAL DEL EXPEDIENTE
        |--------------------------------------------------------------------------
        */

        $statusVisual = match ($unit->status->value) {

            'ARRIVAL_PENDING' => [
                'accent' => 'blue',
                'label' => 'Etapa de recepción',
            ],

            'ASSEMBLY_PENDING' => [
                'accent' => 'amber',
                'label' => 'Etapa de armado',
            ],

            'DELIVERY_PENDING' => [
                'accent' => 'violet',
                'label' => 'Etapa de entrega',
            ],

            'COMPLETED' => [
                'accent' => 'emerald',
                'label' => 'Expediente completo',
            ],

            default => [
                'accent' => 'slate',
                'label' => 'Expediente de unidad',
            ],
        };


        $stageVisual = [

            \App\Enums\MilestoneStage::ARRIVAL->value => [
                'number' => '01',
                'name' => 'Llegada al CEDIS',
                'dot' => 'bg-blue-500',
                'soft' => 'bg-blue-50',
                'text' => 'text-blue-700',
                'border' => 'border-blue-100',
            ],

            \App\Enums\MilestoneStage::ASSEMBLY_COMPLETED->value => [
                'number' => '02',
                'name' => 'Armado finalizado',
                'dot' => 'bg-amber-500',
                'soft' => 'bg-amber-50',
                'text' => 'text-amber-700',
                'border' => 'border-amber-100',
            ],

            \App\Enums\MilestoneStage::CARRIER_DELIVERY->value => [
                'number' => '03',
                'name' => 'Entrega a transportadora',
                'dot' => 'bg-violet-500',
                'soft' => 'bg-violet-50',
                'text' => 'text-violet-700',
                'border' => 'border-violet-100',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | SESIÓN DE ARMADO
        |--------------------------------------------------------------------------
        */

        $assemblyMilestone =
            $unit->milestones
                ->first(
                    fn($milestone) =>
                        $milestone->stage
                        ===
                        \App\Enums\MilestoneStage::ASSEMBLY_COMPLETED
                );

        $assemblyWorkSession =
            $assemblyMilestone
                    ?->assemblyWorkSession;


        $calendarSeconds = 0;
        $assemblyEffectiveSeconds = 0;
        $assemblyPausedSeconds = 0;


        if ($assemblyWorkSession) {

            if (
                $assemblyWorkSession->started_at
                && $assemblyWorkSession->completed_at
            ) {

                $calendarSeconds =
                    $assemblyWorkSession
                        ->started_at
                        ->diffInSeconds(
                            $assemblyWorkSession->completed_at
                        );

            } elseif ($assemblyWorkSession->started_at) {

                $calendarSeconds =
                    $assemblyWorkSession
                        ->started_at
                        ->diffInSeconds(
                            now()
                        );
            }


            if (
                $assemblyWorkSession->status
                === \App\Enums\AssemblyWorkStatus::COMPLETED
            ) {

                $assemblyEffectiveSeconds =
                    $assemblyWorkSession
                        ->total_active_seconds;

                $assemblyPausedSeconds =
                    $assemblyWorkSession
                        ->total_paused_seconds;

            } else {

                $closedPausedSeconds =
                    $assemblyWorkSession
                        ->total_paused_seconds;


                $openPause =
                    $assemblyWorkSession
                        ->pauses
                        ->first(
                            fn($pause) =>
                                $pause->resumed_at === null
                        );


                $currentOpenPauseSeconds = 0;


                if ($openPause) {

                    $currentOpenPauseSeconds =
                        $openPause
                            ->paused_at
                            ->diffInSeconds(
                                now()
                            );
                }


                $assemblyPausedSeconds =
                    $closedPausedSeconds
                    + $currentOpenPauseSeconds;


                $assemblyEffectiveSeconds =
                    max(
                        0,
                        $calendarSeconds
                        - $assemblyPausedSeconds
                    );
            }
        }

    @endphp


    <div class="space-y-8">


        {{-- ========================================================= --}}
        {{-- NAVEGACIÓN --}}
        {{-- ========================================================= --}}

        <div class="
                flex
                flex-col
                gap-3
                sm:flex-row
                sm:items-center
                sm:justify-between
            ">

            <a href="{{ route('units.index') }}" class="
                    inline-flex
                    w-fit
                    items-center
                    gap-2
                    text-sm
                    font-semibold
                    text-slate-500
                    transition
                    hover:text-slate-950
                ">
                <span>←</span>
                Volver a unidades
            </a>


            <p class="
                    text-xs
                    font-medium
                    uppercase
                    tracking-[0.14em]
                    text-slate-400
                ">
                Expediente digital CEDIS
            </p>

        </div>



        {{-- ========================================================= --}}
        {{-- HERO DEL EXPEDIENTE --}}
        {{-- ========================================================= --}}

        <section class="
                relative
                overflow-hidden
                rounded-3xl
                bg-[#0B1220]
                p-6
                text-white
                shadow-[0_18px_50px_rgba(15,23,42,0.16)]
                lg:p-8
            ">

            {{-- DECORACIÓN --}}

            <div class="
                    pointer-events-none
                    absolute
                    -right-24
                    -top-24
                    h-72
                    w-72
                    rounded-full
                    bg-blue-500/10
                "></div>

            <div class="
                    pointer-events-none
                    absolute
                    bottom-0
                    right-20
                    h-40
                    w-40
                    translate-y-20
                    rounded-full
                    bg-white/[0.03]
                "></div>


            <div class="
                    relative
                    flex
                    flex-col
                    gap-8
                    xl:flex-row
                    xl:items-start
                    xl:justify-between
                ">

                {{-- INFORMACIÓN PRINCIPAL --}}

                <div class="min-w-0">

                    <div class="
                            flex
                            flex-wrap
                            items-center
                            gap-3
                        ">

                        <span class="
                                rounded-full
                                bg-white/10
                                px-3
                                py-1.5
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.18em]
                                text-slate-300
                            ">
                            {{ $unit->brand?->name ?? 'Unidad' }}
                        </span>


                        <span class="
                                inline-flex
                                rounded-full
                                px-3
                                py-1.5
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                {{ $unit->status->badgeClasses() }}
                            ">
                            {{ $unit->status->label() }}
                        </span>

                    </div>


                    <h1 class="
                            mt-5
                            text-3xl
                            font-bold
                            tracking-tight
                            text-white
                            sm:text-4xl
                        ">
                        {{ $unit->model ?: 'Modelo sin identificar' }}
                    </h1>


                    <div class="
                            mt-4
                            flex
                            flex-wrap
                            items-center
                            gap-x-5
                            gap-y-2
                            text-sm
                            text-slate-400
                        ">

                        @if ($unit->year)
                            <span>
                                Modelo {{ $unit->year }}
                            </span>
                        @endif

                        @if ($unit->exterior_color)

                            <span class="text-slate-600">
                                •
                            </span>

                            <span>
                                {{ $unit->exterior_color }}
                            </span>

                        @endif

                        @if ($unit->engine_number)

                            <span class="text-slate-600">
                                •
                            </span>

                            <span>
                                Motor {{ $unit->engine_number }}
                            </span>

                        @endif

                    </div>


                    {{-- VIN --}}

                    <div class="
                            mt-7
                            inline-block
                            max-w-full
                            rounded-2xl
                            border
                            border-white/10
                            bg-white/[0.05]
                            px-5
                            py-4
                        ">

                        <p class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.16em]
                                text-slate-500
                            ">
                            VIN
                        </p>

                        <p class="
                                mt-1
                                break-all
                                font-mono
                                text-lg
                                font-semibold
                                tracking-wider
                                text-white
                            ">
                            {{ $unit->vin }}
                        </p>

                    </div>

                </div>


                {{-- ACCIONES --}}

                <div class="
                        flex
                        shrink-0
                        flex-col
                        gap-3
                        sm:flex-row
                        xl:flex-col
                    ">

                    @if (
                                        $unit->status
                                        === \App\Enums\UnitStatus::COMPLETED
                                        && auth()->user()?->can('evidences.view')
                                    )

                                    <a href="{{ route(
                            'units.expedient.pdf',
                            $unit
                        ) }}" class="
                                                inline-flex
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-xl
                                                bg-blue-600
                                                px-5
                                                py-3
                                                text-sm
                                                font-semibold
                                                text-white
                                                shadow-sm
                                                transition
                                                hover:bg-blue-500
                                                hover:shadow-md
                                                active:scale-[.98]
                                            ">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                            stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 16.5V3m0 13.5-4.5-4.5m4.5 4.5 4.5-4.5M4.5 15v3.75A2.25 2.25 0 0 0 6.75 21h10.5a2.25 2.25 0 0 0 2.25-2.25V15" />
                                        </svg>

                                        Descargar expediente PDF

                                    </a>

                                    <a href="{{ route(
                            'units.shipping-guide.pdf',
                            $unit
                        ) }}" class="
                            inline-flex
                            items-center
                            justify-center
                            gap-2
                            rounded-xl
                            border
                            border-violet-200
                            bg-violet-50
                            px-4
                            py-2.5
                            text-sm
                            font-semibold
                            text-violet-700
                            transition
                            hover:border-violet-300
                            hover:bg-violet-100
                        ">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                            stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5" />
                                        </svg>

                                        Descargar guía de envío
                                    </a>

                    @endif


                    <div class="
                            rounded-xl
                            border
                            border-white/10
                            bg-white/[0.04]
                            px-4
                            py-3
                            text-xs
                            text-slate-400
                        ">
                        {{ $statusVisual['label'] }}
                    </div>

                </div>

            </div>

        </section>



        {{-- ========================================================= --}}
        {{-- PROGRESO / TRAZABILIDAD --}}
        {{-- ========================================================= --}}

        <section class="
                rounded-3xl
                border
                border-slate-200/80
                bg-white
                p-6
                shadow-[0_8px_30px_rgba(15,23,42,0.04)]
                lg:p-7
            ">

            <div>

                <p class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.16em]
                        text-slate-400
                    ">
                    Trazabilidad
                </p>

                <h2 class="
                        mt-1
                        text-xl
                        font-semibold
                        tracking-tight
                        text-slate-950
                    ">
                    Progreso del expediente
                </h2>

                <p class="
                        mt-1
                        text-sm
                        text-slate-500
                    ">
                    Seguimiento de las tres etapas operativas
                    principales de la unidad.
                </p>

            </div>


            <div class="
                    mt-6
                    grid
                    gap-4
                    lg:grid-cols-3
                ">

                @foreach (
                            \App\Enums\MilestoneStage::cases()
                            as $stage
                        )

                        @php

                            $milestone =
                                $milestones->get(
                                    $stage->value
                                );

                            $visual =
                                $stageVisual[
                                    $stage->value
                                ];

                            $completed =
                                $milestone
                                && $milestone->status
                                === \App\Enums\MilestoneStatus::COMPLETED;

                        @endphp


                        <article class="
                                    relative
                                    overflow-hidden
                                    rounded-2xl
                                    border
                                    p-5
                                    {{ $completed
                    ? $visual['border'] . ' ' . $visual['soft']
                    : 'border-slate-200 bg-white'
                                    }}
                                ">

                            <div class="
                                        flex
                                        items-start
                                        justify-between
                                        gap-4
                                    ">

                                <div class="
                                            flex
                                            items-center
                                            gap-3
                                        ">

                                    <div class="
                                                flex
                                                h-10
                                                w-10
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-xl
                                                {{ $completed
                    ? $visual['soft']
                    : 'bg-slate-100'
                                                }}
                                                {{ $completed
                    ? $visual['text']
                    : 'text-slate-500'
                                                }}
                                                text-xs
                                                font-bold
                                            ">
                                        {{ $visual['number'] }}
                                    </div>


                                    <div>

                                        <p class="
                                                    text-xs
                                                    font-semibold
                                                    uppercase
                                                    tracking-wider
                                                    text-slate-400
                                                ">
                                            Etapa
                                        </p>

                                        <h3 class="
                                                    mt-1
                                                    font-semibold
                                                    text-slate-950
                                                ">
                                            {{ $stage->label() }}
                                        </h3>

                                    </div>

                                </div>


                                @if ($milestone)

                                            <span class="
                                                            rounded-full
                                                            px-2.5
                                                            py-1
                                                            text-xs
                                                            font-semibold
                                                            {{ $milestone
                                    ->status
                                    ->badgeClasses()
                                                            }}
                                                        ">
                                                {{ $milestone
                                    ->status
                                    ->label()
                                                        }}
                                            </span>

                                @endif

                            </div>


                            @if ($milestone)

                                    <div class="
                                                    mt-5
                                                    flex
                                                    items-end
                                                    justify-between
                                                    gap-4
                                                ">

                                        <div>

                                            <p class="
                                                            text-xs
                                                            text-slate-500
                                                        ">
                                                Evidencias
                                            </p>

                                            <p class="
                                                            mt-1
                                                            text-2xl
                                                            font-bold
                                                            tracking-tight
                                                            text-slate-950
                                                        ">
                                                {{ $milestone
                                ->evidences
                                ->count()
                                                        }}
                                            </p>

                                        </div>


                                        @if ($milestone->completed_at)

                                                    <div class="text-right">

                                                        <p class="
                                                                            text-xs
                                                                            text-slate-400
                                                                        ">
                                                            Completado
                                                        </p>

                                                        <p class="
                                                                            mt-1
                                                                            text-xs
                                                                            font-medium
                                                                            text-slate-700
                                                                        ">
                                                            {{ \App\Support\DateHelper::format(
                                                $milestone->completed_at
                                            ) }}
                                                        </p>

                                                    </div>

                                        @endif

                                    </div>


                                    @if (
                                            $milestone->completed_by_name
                                            || $milestone->completedBy
                                        )

                                        <div class="
                                                            mt-4
                                                            border-t
                                                            border-slate-200/70
                                                            pt-4
                                                        ">

                                            <p class="
                                                                text-xs
                                                                text-slate-400
                                                            ">
                                                Responsable
                                            </p>

                                            <p class="
                                                                mt-1
                                                                text-sm
                                                                font-semibold
                                                                text-slate-800
                                                            ">
                                                {{ $milestone->completed_by_name
                                        ?? $milestone->completedBy?->name
                                        ?? '—'
                                                            }}
                                            </p>

                                        </div>

                                    @endif

                            @endif

                        </article>

                @endforeach

            </div>

        </section>



        {{-- ========================================================= --}}
        {{-- INFORMACIÓN + DOCUMENTOS --}}
        {{-- ========================================================= --}}

        <div class="
                grid
                gap-6
                xl:grid-cols-2
            ">

            {{-- INFORMACIÓN DE UNIDAD --}}

            <section class="
                    overflow-hidden
                    rounded-3xl
                    border
                    border-slate-200/80
                    bg-white
                    shadow-[0_8px_30px_rgba(15,23,42,0.04)]
                ">

                <div class="
                        flex
                        items-center
                        gap-4
                        border-b
                        border-slate-100
                        px-6
                        py-5
                    ">

                    <div class="
                            flex
                            h-11
                            w-11
                            items-center
                            justify-center
                            rounded-2xl
                            bg-slate-100
                            text-slate-700
                        ">
                        ◇
                    </div>

                    <div>

                        <h2 class="
                                font-semibold
                                text-slate-950
                            ">
                            Información de la unidad
                        </h2>

                        <p class="
                                mt-0.5
                                text-sm
                                text-slate-500
                            ">
                            Datos generales del expediente.
                        </p>

                    </div>

                </div>


                <dl class="
                        grid
                        gap-x-8
                        gap-y-6
                        p-6
                        sm:grid-cols-2
                    ">

                    @foreach ([
                            'Marca' => $unit->brand?->name,
                            'Modelo' => $unit->model,
                            'Versión' => $unit->version,
                            'Año' => $unit->year,
                            'Color exterior' => $unit->exterior_color,
                            'Color interior' => $unit->interior_color,
                            'Número de motor' => $unit->engine_number,
                        ] as $label => $value)

                        <div>

                            <dt class="
                                        text-[10px]
                                        font-semibold
                                        uppercase
                                        tracking-[0.12em]
                                        text-slate-400
                                    ">
                                {{ $label }}
                            </dt>

                            <dd class="
                                        mt-1.5
                                        text-sm
                                        font-semibold
                                        text-slate-900
                                    ">
                                {{ $value ?: '—' }}
                            </dd>

                        </div>

                    @endforeach


                    <div>

                        <dt class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.12em]
                                text-slate-400
                            ">
                            Fecha de registro
                        </dt>

                        <dd class="
                                mt-1.5
                                text-sm
                                font-semibold
                                text-slate-900
                            ">
                            {{ \App\Support\DateHelper::format(
        $unit->created_at
    ) }}
                        </dd>

                    </div>

                </dl>

            </section>


            {{-- DOCUMENTOS --}}

            <section class="
                    overflow-hidden
                    rounded-3xl
                    border
                    border-slate-200/80
                    bg-white
                    shadow-[0_8px_30px_rgba(15,23,42,0.04)]
                ">

                <div class="
                        flex
                        items-center
                        gap-4
                        border-b
                        border-slate-100
                        px-6
                        py-5
                    ">

                    <div class="
                            flex
                            h-11
                            w-11
                            items-center
                            justify-center
                            rounded-2xl
                            bg-blue-50
                            text-blue-600
                        ">
                        ↓
                    </div>

                    <div>

                        <h2 class="
                                font-semibold
                                text-slate-950
                            ">
                            Documentos de origen
                        </h2>

                        <p class="
                                mt-0.5
                                text-sm
                                text-slate-500
                            ">
                            CFDI y representación documental asociada.
                        </p>

                    </div>

                </div>


                <div class="space-y-5 p-6">

                    <div class="
                            grid
                            gap-3
                            sm:grid-cols-2
                        ">

                        @if ($xmlDocument)

                                            <a href="{{ route(
                                'documents.download',
                                $xmlDocument
                            ) }}" class="
                                                        group
                                                        rounded-2xl
                                                        border
                                                        border-blue-100
                                                        bg-blue-50/40
                                                        p-4
                                                        transition
                                                        hover:border-blue-300
                                                        hover:bg-blue-50
                                                    ">

                                                <div class="
                                                            flex
                                                            items-center
                                                            justify-between
                                                            gap-3
                                                        ">

                                                    <span class="
                                                                rounded-lg
                                                                bg-blue-100
                                                                px-2.5
                                                                py-1
                                                                text-xs
                                                                font-bold
                                                                text-blue-700
                                                            ">
                                                        XML
                                                    </span>

                                                    <span class="
                                                                text-xs
                                                                text-blue-500
                                                            ">
                                                        Descargar →
                                                    </span>

                                                </div>

                                                <p class="
                                                            mt-4
                                                            truncate
                                                            text-sm
                                                            font-semibold
                                                            text-slate-900
                                                        ">
                                                    {{ $xmlDocument->original_filename }}
                                                </p>

                                            </a>

                        @endif


                        @if ($pdfDocument)

                                            <a href="{{ route(
                                'documents.download',
                                $pdfDocument
                            ) }}" class="
                                                        group
                                                        rounded-2xl
                                                        border
                                                        border-red-100
                                                        bg-red-50/40
                                                        p-4
                                                        transition
                                                        hover:border-red-300
                                                        hover:bg-red-50
                                                    ">

                                                <div class="
                                                            flex
                                                            items-center
                                                            justify-between
                                                            gap-3
                                                        ">

                                                    <span class="
                                                                rounded-lg
                                                                bg-red-100
                                                                px-2.5
                                                                py-1
                                                                text-xs
                                                                font-bold
                                                                text-red-700
                                                            ">
                                                        PDF
                                                    </span>

                                                    <span class="
                                                                text-xs
                                                                text-red-500
                                                            ">
                                                        Descargar →
                                                    </span>

                                                </div>

                                                <p class="
                                                            mt-4
                                                            truncate
                                                            text-sm
                                                            font-semibold
                                                            text-slate-900
                                                        ">
                                                    {{ $pdfDocument->original_filename }}
                                                </p>

                                            </a>

                        @endif

                    </div>


                    @if ($invoice)

                                    <div class="
                                                rounded-2xl
                                                bg-slate-50
                                                p-5
                                            ">

                                        <dl class="
                                                    grid
                                                    gap-5
                                                    sm:grid-cols-2
                                                ">

                                            <div>

                                                <dt class="
                                                            text-[10px]
                                                            font-semibold
                                                            uppercase
                                                            tracking-wider
                                                            text-slate-400
                                                        ">
                                                    Factura
                                                </dt>

                                                <dd class="
                                                            mt-1
                                                            text-sm
                                                            font-semibold
                                                            text-slate-900
                                                        ">
                                                    {{ $invoice->series }}
                                                    {{ $invoice->folio }}
                                                </dd>

                                            </div>


                                            <div>

                                                <dt class="
                                                            text-[10px]
                                                            font-semibold
                                                            uppercase
                                                            tracking-wider
                                                            text-slate-400
                                                        ">
                                                    Total
                                                </dt>

                                                <dd class="
                                                            mt-1
                                                            text-sm
                                                            font-semibold
                                                            text-slate-900
                                                        ">
                                                    {{ $invoice->currency }}

                                                    {{ number_format(
                            (float) $invoice->total,
                            2
                        ) }}
                                                </dd>

                                            </div>


                                            <div class="sm:col-span-2">

                                                <dt class="
                                                            text-[10px]
                                                            font-semibold
                                                            uppercase
                                                            tracking-wider
                                                            text-slate-400
                                                        ">
                                                    UUID
                                                </dt>

                                                <dd class="
                                                            mt-1
                                                            break-all
                                                            font-mono
                                                            text-xs
                                                            text-slate-600
                                                        ">
                                                    {{ $invoice->uuid }}
                                                </dd>

                                            </div>

                                        </dl>

                                    </div>

                    @endif

                </div>

            </section>

        </div>



        {{-- ========================================================= --}}
        {{-- CONTROL DE TIEMPO DE ARMADO --}}
        {{-- ========================================================= --}}

        @if ($assemblyWorkSession)

            <section class="
                        overflow-hidden
                        rounded-3xl
                        border
                        border-amber-100
                        bg-white
                        shadow-[0_8px_30px_rgba(15,23,42,0.04)]
                    ">

                <div class="
                            flex
                            flex-col
                            gap-4
                            border-b
                            border-amber-100
                            bg-gradient-to-r
                            from-amber-50
                            to-white
                            px-6
                            py-5
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                            lg:px-7
                        ">

                    <div>

                        <div class="
                                    flex
                                    items-center
                                    gap-2
                                ">

                            <span class="
                                        h-2.5
                                        w-2.5
                                        rounded-full
                                        bg-amber-500
                                    "></span>

                            <p class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-[0.14em]
                                        text-amber-700
                                    ">
                                Control de armado
                            </p>

                        </div>


                        <h2 class="
                                    mt-2
                                    text-xl
                                    font-semibold
                                    tracking-tight
                                    text-slate-950
                                ">
                            Registro de tiempo de armado
                        </h2>

                    </div>


                    <span class="
                                w-fit
                                rounded-full
                                px-3
                                py-1.5
                                text-xs
                                font-semibold
                                {{ $assemblyWorkSession
                ->status
                ->badgeClasses()
                                }}
                            ">
                        {{ $assemblyWorkSession
                ->status
                ->label()
                            }}
                    </span>

                </div>


                <div class="space-y-7 p-6 lg:p-7">

                    {{-- MÉTRICAS --}}

                    <div class="
                                grid
                                gap-4
                                md:grid-cols-3
                            ">

                        <article class="
                                    rounded-2xl
                                    border
                                    border-blue-100
                                    bg-blue-50/50
                                    p-5
                                ">

                            <p class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-blue-600
                                    ">
                                Tiempo efectivo
                            </p>

                            <p class="
                                        mt-3
                                        font-mono
                                        text-3xl
                                        font-bold
                                        tracking-tight
                                        text-blue-900
                                    ">
                                {{ \App\Support\DurationHelper::format(
                $assemblyEffectiveSeconds
            ) }}
                            </p>

                            <p class="
                                        mt-2
                                        text-xs
                                        text-blue-600
                                    ">
                                Tiempo real dedicado al armado.
                            </p>

                        </article>


                        <article class="
                                    rounded-2xl
                                    border
                                    border-amber-100
                                    bg-amber-50/50
                                    p-5
                                ">

                            <p class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-amber-700
                                    ">
                                Tiempo pausado
                            </p>

                            <p class="
                                        mt-3
                                        font-mono
                                        text-3xl
                                        font-bold
                                        tracking-tight
                                        text-amber-900
                                    ">
                                {{ \App\Support\DurationHelper::format(
                $assemblyPausedSeconds
            ) }}
                            </p>

                            <p class="
                                        mt-2
                                        text-xs
                                        text-amber-700
                                    ">
                                Tiempo excluido del proceso.
                            </p>

                        </article>


                        <article class="
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    bg-slate-50
                                    p-5
                                ">

                            <p class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-500
                                    ">
                                Tiempo transcurrido
                            </p>

                            <p class="
                                        mt-3
                                        font-mono
                                        text-3xl
                                        font-bold
                                        tracking-tight
                                        text-slate-950
                                    ">
                                {{ \App\Support\DurationHelper::format(
                $calendarSeconds
            ) }}
                            </p>

                            <p class="
                                        mt-2
                                        text-xs
                                        text-slate-500
                                    ">
                                Inicio hasta finalización.
                            </p>

                        </article>

                    </div>


                    {{-- SESIÓN --}}

                    <div class="
                                grid
                                gap-5
                                rounded-2xl
                                border
                                border-slate-200
                                bg-white
                                p-5
                                sm:grid-cols-2
                                xl:grid-cols-4
                            ">

                        @foreach ([
                                'Inicio' =>
                                    \App\Support\DateHelper::format(
                                        $assemblyWorkSession->started_at
                                    ),

                                'Finalización' =>
                                    $assemblyWorkSession->completed_at
                                    ? \App\Support\DateHelper::format(
                                        $assemblyWorkSession->completed_at
                                    )
                                    : '—',

                                'Iniciado por' =>
                                    $assemblyWorkSession->started_by_name
                                    ?? '—',

                                'Finalizado por' =>
                                    $assemblyWorkSession->completed_by_name
                                    ?? '—',
                            ] as $label => $value)

                            <div>

                                <p class="
                                                text-[10px]
                                                font-semibold
                                                uppercase
                                                tracking-wider
                                                text-slate-400
                                            ">
                                    {{ $label }}
                                </p>

                                <p class="
                                                mt-1.5
                                                text-sm
                                                font-semibold
                                                text-slate-900
                                            ">
                                    {{ $value }}
                                </p>

                            </div>

                        @endforeach

                    </div>


                    {{-- PAUSAS --}}

                    <div>

                        <div class="
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                ">

                            <div>

                                <h3 class="
                                            font-semibold
                                            text-slate-950
                                        ">
                                    Historial de pausas
                                </h3>

                                <p class="
                                            mt-1
                                            text-sm
                                            text-slate-500
                                        ">
                                    Interrupciones registradas durante
                                    el proceso de armado.
                                </p>

                            </div>


                            <span class="
                                        rounded-full
                                        bg-slate-100
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-slate-600
                                    ">
                                {{ $assemblyWorkSession
                ->pauses
                ->count()
                                    }}
                                pausa(s)
                            </span>

                        </div>


                        <div class="
                                    mt-4
                                    overflow-hidden
                                    rounded-2xl
                                    border
                                    border-slate-200
                                ">

                            @forelse (
                                                $assemblyWorkSession
                                                    ->pauses
                                                    ->sortBy('paused_at')
                                                as $pause
                                            )

                                            @php

                                                $pauseDuration =
                                                    $pause->resumed_at

                                                    ? $pause
                                                        ->paused_at
                                                        ->diffInSeconds(
                                                            $pause->resumed_at
                                                        )

                                                    : $pause
                                                        ->paused_at
                                                        ->diffInSeconds(
                                                            now()
                                                        );

                                            @endphp


                                            <article class="
                                                            border-b
                                                            border-slate-100
                                                            p-5
                                                            last:border-b-0
                                                        ">

                                                <div class="
                                                                flex
                                                                flex-col
                                                                gap-4
                                                                lg:flex-row
                                                                lg:items-start
                                                                lg:justify-between
                                                            ">

                                                    <div>

                                                        <p class="
                                                                        font-semibold
                                                                        text-slate-950
                                                                    ">
                                                            {{ $pause
                                    ->reason
                                    ->label()
                                                                    }}
                                                        </p>


                                                        @if ($pause->notes)

                                                            <p class="
                                                                                mt-1
                                                                                text-sm
                                                                                text-slate-500
                                                                            ">
                                                                {{ $pause->notes }}
                                                            </p>

                                                        @endif


                                                        <div class="
                                                                        mt-3
                                                                        flex
                                                                        flex-wrap
                                                                        gap-x-5
                                                                        gap-y-2
                                                                        text-xs
                                                                        text-slate-500
                                                                    ">

                                                            <span>
                                                                Pausa:
                                                                {{ \App\Support\DateHelper::format(
                                    $pause->paused_at
                                ) }}
                                                            </span>


                                                            <span>

                                                                Reanudación:

                                                                @if ($pause->resumed_at)

                                                                                                {{ \App\Support\DateHelper::format(
                                                                        $pause->resumed_at
                                                                    ) }}

                                                                @else

                                                                    En curso

                                                                @endif

                                                            </span>


                                                            <span>
                                                                Por:
                                                                {{ $pause->paused_by_name
                                    ?? '—'
                                                                        }}
                                                            </span>


                                                            @if ($pause->resumed_by_name)

                                                                <span>
                                                                    Reanudada por:
                                                                    {{ $pause->resumed_by_name }}
                                                                </span>

                                                            @endif

                                                        </div>

                                                    </div>


                                                    <div class="
                                                                    shrink-0
                                                                    rounded-xl
                                                                    bg-slate-100
                                                                    px-4
                                                                    py-2
                                                                    font-mono
                                                                    text-sm
                                                                    font-semibold
                                                                    text-slate-700
                                                                ">
                                                        {{ \App\Support\DurationHelper::format(
                                    $pauseDuration
                                ) }}
                                                    </div>

                                                </div>

                                            </article>

                            @empty

                                <div class="
                                                px-5
                                                py-10
                                                text-center
                                            ">

                                    <p class="
                                                    text-sm
                                                    font-medium
                                                    text-slate-600
                                                ">
                                        Sin pausas registradas
                                    </p>

                                    <p class="
                                                    mt-1
                                                    text-sm
                                                    text-slate-400
                                                ">
                                        El armado se realizó de forma continua.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>

            </section>

        @endif



        {{-- ========================================================= --}}
        {{-- EVIDENCIAS --}}
        {{-- ========================================================= --}}

        @if (
                $unit->milestones
                    ->sum(
                        fn($milestone) =>
                            $milestone->evidences->count()
                    ) > 0
            )

            <section class="
                        overflow-hidden
                        rounded-3xl
                        border
                        border-slate-200/80
                        bg-white
                        shadow-[0_8px_30px_rgba(15,23,42,0.04)]
                    ">

                <div class="
                            border-b
                            border-slate-100
                            px-6
                            py-5
                            lg:px-7
                        ">

                    <p class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-[0.16em]
                                text-blue-600
                            ">
                        Evidencia visual
                    </p>

                    <h2 class="
                                mt-1
                                text-xl
                                font-semibold
                                text-slate-950
                            ">
                        Evidencias del expediente
                    </h2>

                    <p class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                        Registro fotográfico asociado a cada etapa
                        del proceso de la unidad.
                    </p>

                </div>


                <div class="space-y-10 p-6 lg:p-7">

                    @foreach (
                            $unit->milestones
                                ->sortBy('id')
                            as $milestone
                        )

                        @if ($milestone->evidences->isNotEmpty())

                            @php

                                $visual =
                                    $stageVisual[
                                        $milestone->stage->value
                                    ] ?? [
                                        'number' => '—',
                                        'dot' => 'bg-slate-500',
                                        'soft' => 'bg-slate-50',
                                        'text' => 'text-slate-700',
                                        'border' => 'border-slate-200',
                                    ];

                            @endphp


                            <div>

                                {{-- CABECERA ETAPA --}}

                                <div class="
                                                    flex
                                                    flex-col
                                                    gap-4
                                                    sm:flex-row
                                                    sm:items-center
                                                    sm:justify-between
                                                ">

                                    <div class="
                                                        flex
                                                        items-center
                                                        gap-3
                                                    ">

                                        <div class="
                                                            flex
                                                            h-10
                                                            w-10
                                                            items-center
                                                            justify-center
                                                            rounded-xl
                                                            {{ $visual['soft'] }}
                                                            {{ $visual['text'] }}
                                                            text-xs
                                                            font-bold
                                                        ">
                                            {{ $visual['number'] }}
                                        </div>


                                        <div>

                                            <h3 class="
                                                                font-semibold
                                                                text-slate-950
                                                            ">
                                                {{ $milestone
                                ->stage
                                ->label()
                                                            }}
                                            </h3>

                                            <div class="
                                                                mt-1
                                                                flex
                                                                flex-wrap
                                                                gap-x-2
                                                                gap-y-1
                                                                text-xs
                                                                text-slate-500
                                                            ">

                                                @if ($milestone->completed_at)

                                                                    <span>
                                                                        {{ \App\Support\DateHelper::format(
                                                        $milestone->completed_at
                                                    ) }}
                                                                    </span>

                                                @endif


                                                @if (
                                                                    $milestone->completed_by_name
                                                                    || $milestone->completedBy
                                                                )

                                                                <span>
                                                                    •
                                                                </span>

                                                                <span>
                                                                    {{ $milestone->completed_by_name
                                                    ?? $milestone->completedBy?->name
                                                    ?? '—'
                                                                                    }}
                                                                </span>

                                                @endif

                                            </div>

                                        </div>

                                    </div>


                                    <span class="
                                                        w-fit
                                                        rounded-full
                                                        bg-slate-100
                                                        px-3
                                                        py-1
                                                        text-xs
                                                        font-semibold
                                                        text-slate-600
                                                    ">
                                        {{ $milestone
                                ->evidences
                                ->count()
                                                    }}
                                        evidencia(s)
                                    </span>

                                </div>


                                {{-- GALERÍA --}}

                                <div class="
                                                    mt-5
                                                    grid
                                                    grid-cols-2
                                                    gap-3
                                                    sm:grid-cols-3
                                                    lg:grid-cols-4
                                                    xl:grid-cols-5
                                                ">

                                    @foreach (
                                                $milestone->evidences
                                                as $evidence
                                            )

                                            <a href="{{ route(
                                            'evidences.show',
                                            $evidence
                                        ) }}" target="_blank" class="
                                                                    group
                                                                    relative
                                                                    overflow-hidden
                                                                    rounded-2xl
                                                                    border
                                                                    border-slate-200
                                                                    bg-slate-100
                                                                    shadow-sm
                                                                ">

                                                @if (
                                                            $evidence->type
                                                            === \App\Enums\EvidenceType::IMAGE
                                                        )

                                                        <img src="{{ route(
                                                        'evidences.show',
                                                        $evidence
                                                    ) }}" alt="Evidencia" loading="lazy" class="
                                                                                    aspect-square
                                                                                    w-full
                                                                                    object-cover
                                                                                    transition
                                                                                    duration-300
                                                                                    group-hover:scale-105
                                                                                ">

                                                @else

                                                    <div class="
                                                                                flex
                                                                                aspect-square
                                                                                items-center
                                                                                justify-center
                                                                                p-4
                                                                                text-center
                                                                                text-sm
                                                                                text-slate-500
                                                                            ">
                                                        {{ $evidence
                                                    ->type
                                                    ->value
                                                                            }}
                                                    </div>

                                                @endif


                                                <div class="
                                                                        absolute
                                                                        inset-x-0
                                                                        bottom-0
                                                                        bg-gradient-to-t
                                                                        from-black/80
                                                                        via-black/30
                                                                        to-transparent
                                                                        p-3
                                                                        pt-12
                                                                    ">

                                                    <p class="
                                                                            truncate
                                                                            text-xs
                                                                            font-medium
                                                                            text-white
                                                                        ">
                                                        {{ $evidence
                                            ->original_filename
                                            ?? 'Evidencia'
                                                                        }}
                                                    </p>

                                                </div>

                                            </a>

                                    @endforeach

                                </div>


                                {{-- OBSERVACIONES --}}

                                @if ($milestone->observations)

                                    <div class="
                                                            mt-5
                                                            rounded-2xl
                                                            bg-slate-50
                                                            p-5
                                                        ">

                                        <p class="
                                                                text-[10px]
                                                                font-semibold
                                                                uppercase
                                                                tracking-wider
                                                                text-slate-400
                                                            ">
                                            Observaciones
                                        </p>

                                        <p class="
                                                                mt-2
                                                                text-sm
                                                                leading-6
                                                                text-slate-700
                                                            ">
                                            {{ $milestone->observations }}
                                        </p>

                                    </div>

                                @endif


                                {{-- DATOS DE ENTREGA --}}

                                @if (
                                        $milestone->stage
                                        === \App\Enums\MilestoneStage::CARRIER_DELIVERY
                                        && $milestone->carrierDelivery
                                    )

                                    <div class="
                                                            mt-5
                                                            rounded-2xl
                                                            border
                                                            border-violet-100
                                                            bg-violet-50/40
                                                            p-5
                                                        ">

                                        <p class="
                                                                text-[10px]
                                                                font-semibold
                                                                uppercase
                                                                tracking-wider
                                                                text-violet-600
                                                            ">
                                            Datos de entrega
                                        </p>


                                        <dl class="
                                                                mt-4
                                                                grid
                                                                gap-5
                                                                sm:grid-cols-2
                                                                lg:grid-cols-3
                                                            ">

                                            @foreach ([
                                                    'Transportadora' =>
                                                        $milestone
                                                            ->carrierDelivery
                                                            ->carrier
                                                                ?->name,

                                                    'Operador' =>
                                                        $milestone
                                                            ->carrierDelivery
                                                            ->operator_name,

                                                    'Placas' =>
                                                        $milestone
                                                            ->carrierDelivery
                                                            ->vehicle_plate,

                                                    'Número económico' =>
                                                        $milestone
                                                            ->carrierDelivery
                                                            ->vehicle_number,

                                                    'Tipo de transporte' =>
                                                        $milestone
                                                            ->carrierDelivery
                                                            ->transport_type,
                                                ] as $label => $value)

                                                @if ($value)

                                                    <div>

                                                        <dt class="
                                                                                        text-[10px]
                                                                                        font-semibold
                                                                                        uppercase
                                                                                        tracking-wider
                                                                                        text-slate-400
                                                                                    ">
                                                            {{ $label }}
                                                        </dt>

                                                        <dd class="
                                                                                        mt-1
                                                                                        text-sm
                                                                                        font-semibold
                                                                                        text-slate-900
                                                                                    ">
                                                            {{ $value }}
                                                        </dd>

                                                    </div>

                                                @endif

                                            @endforeach

                                        </dl>

                                    </div>

                                @endif

                            </div>


                            @unless ($loop->last)

                                <div class="
                                                        border-t
                                                        border-slate-100
                                                    "></div>

                            @endunless

                        @endif

                    @endforeach

                </div>

            </section>

        @endif



        {{-- ========================================================= --}}
        {{-- ACCIONES OPERATIVAS --}}
        {{-- ========================================================= --}}

        @if (
                $unit->status
                === \App\Enums\UnitStatus::ARRIVAL_PENDING
                && auth()->user()?->can('arrival.complete')
            )

            <livewire:arrival-evidence :unit="$unit" :key="'arrival-' . $unit->id" />

        @endif


        @if (
                $unit->status
                === \App\Enums\UnitStatus::ASSEMBLY_PENDING
                && auth()->user()?->can('assembly.complete')
            )

            <div class="space-y-6">

                <livewire:assembly-work-timer :unit="$unit" :key="'assembly-timer-' . $unit->id" />

                <livewire:assembly-evidence :unit="$unit" :key="'assembly-evidence-' . $unit->id" />

            </div>

        @endif


        @if (
                $unit->status
                === \App\Enums\UnitStatus::DELIVERY_PENDING
                && auth()->user()?->can('delivery.complete')
            )

            <livewire:delivery-evidence :unit="$unit" :key="'delivery-' . $unit->id" />

        @endif



        {{-- ========================================================= --}}
        {{-- HISTORIAL --}}
        {{-- ========================================================= --}}

        <section class="
                overflow-hidden
                rounded-3xl
                border
                border-slate-200/80
                bg-white
                shadow-[0_8px_30px_rgba(15,23,42,0.04)]
            ">

            <div class="
                    border-b
                    border-slate-100
                    px-6
                    py-5
                    lg:px-7
                ">

                <p class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.16em]
                        text-slate-400
                    ">
                    Auditoría
                </p>

                <h2 class="
                        mt-1
                        text-xl
                        font-semibold
                        text-slate-950
                    ">
                    Historial del expediente
                </h2>

                <p class="
                        mt-1
                        text-sm
                        text-slate-500
                    ">
                    Registro cronológico de las acciones
                    realizadas sobre la unidad.
                </p>

            </div>


            <div class="p-6 lg:p-7">

                <div class="space-y-0">

                    @forelse (
                                        $unit->events
                                            ->sortByDesc('created_at')
                                        as $event
                                    )

                                    <article class="
                                                relative
                                                border-l
                                                border-slate-200
                                                pb-7
                                                pl-8
                                                last:border-transparent
                                                last:pb-0
                                            ">

                                        <div class="
                                                    absolute
                                                    -left-[5px]
                                                    top-1
                                                    h-2.5
                                                    w-2.5
                                                    rounded-full
                                                    border-2
                                                    border-white
                                                    bg-blue-500
                                                    ring-2
                                                    ring-blue-100
                                                "></div>


                                        <div class="
                                                    flex
                                                    flex-col
                                                    gap-2
                                                    sm:flex-row
                                                    sm:items-start
                                                    sm:justify-between
                                                ">

                                            <div>

                                                <p class="
                                                            text-sm
                                                            font-semibold
                                                            text-slate-950
                                                        ">
                                                    {{ $event->title }}
                                                </p>


                                                @if ($event->description)

                                                    <p class="
                                                                    mt-1
                                                                    max-w-3xl
                                                                    text-sm
                                                                    leading-6
                                                                    text-slate-500
                                                                ">
                                                        {{ $event->description }}
                                                    </p>

                                                @endif

                                            </div>


                                            <div class="
                                                        shrink-0
                                                        text-left
                                                        sm:text-right
                                                    ">

                                                <p class="
                                                            text-xs
                                                            font-medium
                                                            text-slate-500
                                                        ">
                                                    {{ \App\Support\DateHelper::format(
                            $event->created_at
                        ) }}
                                                </p>


                                                @if (
                                                                        $event->performed_by_name
                                                                        || $event->performedBy
                                                                    )

                                                                    <p class="
                                                                                    mt-1
                                                                                    text-xs
                                                                                    text-slate-400
                                                                                ">
                                                                        {{ $event->performed_by_name
                                                    ?? $event->performedBy?->name
                                                    ?? '—'
                                                                                }}
                                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    </article>

                    @empty

                        <div class="
                                    py-10
                                    text-center
                                ">

                            <p class="
                                        text-sm
                                        font-medium
                                        text-slate-600
                                    ">
                                No existe actividad registrada.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </section>



        {{-- ========================================================= --}}
        {{-- ZONA ADMINISTRATIVA --}}
        {{-- ========================================================= --}}

        @can('units.delete')

            <section class="
                        rounded-3xl
                        border
                        border-red-100
                        bg-gradient-to-r
                        from-red-50/60
                        to-white
                        p-6
                        shadow-[0_8px_30px_rgba(15,23,42,0.03)]
                    ">

                <div class="
                            flex
                            flex-col
                            gap-5
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
                        ">

                    <div>

                        <p class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-[0.14em]
                                    text-red-600
                                ">
                            Zona administrativa
                        </p>

                        <h2 class="
                                    mt-1
                                    font-semibold
                                    text-slate-950
                                ">
                            Gestión del expediente
                        </h2>

                        <p class="
                                    mt-1
                                    max-w-2xl
                                    text-sm
                                    text-slate-500
                                ">
                            Las acciones realizadas aquí afectan
                            la disponibilidad del expediente dentro
                            del flujo operativo.
                        </p>

                    </div>


                    <div class="shrink-0">

                        <livewire:delete-unit :unit="$unit" :key="'delete-unit-' . $unit->id" />

                    </div>

                </div>

            </section>

        @endcan

    </div>

@endsection