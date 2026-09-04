@extends('layouts.app')

@section(
'title',
($unit->model ?: $unit->vin) . ' | CEDIS'
)

@section('content')

<div class="space-y-8">

    @if (session('success'))

    <div
        class="
            rounded-xl
            border
            border-emerald-200
            bg-emerald-50
            px-5
            py-4
            text-sm
            font-medium
            text-emerald-800
        ">
        {{ session('success') }}
    </div>

    @endif

    {{-- NAVEGACIÓN --}}

    <div>
        <a
            href="{{ route('units.index') }}"
            class="
                inline-flex
                items-center
                gap-2
                text-sm
                font-medium
                text-slate-500
                hover:text-slate-950
            ">
            ← Volver a unidades
        </a>
    </div>


    {{-- HEADER --}}

    <section
        class="
            rounded-2xl
            border
            border-slate-200
            bg-white
            p-6
            shadow-sm
            lg:p-8
        ">

        <div
            class="
                flex
                flex-col
                gap-6
                lg:flex-row
                lg:items-start
                lg:justify-between
            ">

            <div>

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.18em]
                        text-blue-600
                    ">
                    {{ $unit->brand?->name ?? 'Unidad' }}
                </p>

                <h1
                    class="
                        mt-2
                        text-3xl
                        font-semibold
                        tracking-tight
                        text-slate-950
                    ">
                    {{ $unit->model ?: 'Modelo sin identificar' }}
                </h1>


                <div
                    class="
                        mt-4
                        flex
                        flex-wrap
                        items-center
                        gap-x-5
                        gap-y-2
                        text-sm
                        text-slate-500
                    ">

                    @if ($unit->year)
                    <span>
                        Modelo {{ $unit->year }}
                    </span>
                    @endif

                    @if ($unit->exterior_color)
                    <span>
                        {{ $unit->exterior_color }}
                    </span>
                    @endif

                    @if ($unit->engine_number)
                    <span>
                        Motor {{ $unit->engine_number }}
                    </span>
                    @endif

                </div>

            </div>


            <span
                class="
                    inline-flex
                    w-fit
                    rounded-full
                    px-4
                    py-2
                    text-xs
                    font-semibold
                    {{ $unit->status->badgeClasses() }}
                ">
                {{ $unit->status->label() }}
            </span>

        </div>


        <div
            class="
                mt-8
                rounded-xl
                bg-slate-950
                px-5
                py-4
                text-white
            ">

            <p
                class="
                    text-xs
                    uppercase
                    tracking-wider
                    text-slate-400
                ">
                VIN
            </p>

            <p
                class="
                    mt-1
                    break-all
                    font-mono
                    text-lg
                    font-semibold
                    tracking-wide
                ">
                {{ $unit->vin }}
            </p>

        </div>

    </section>


    {{-- INFORMACIÓN + FACTURA --}}

    <div
        class="
            grid
            gap-6
            xl:grid-cols-2
        ">

        {{-- VEHÍCULO --}}

        <section
            class="
                rounded-2xl
                border
                border-slate-200
                bg-white
                shadow-sm
            ">

            <div
                class="
                    border-b
                    border-slate-200
                    px-6
                    py-5
                ">
                <h2 class="font-semibold">
                    Información de la unidad
                </h2>
            </div>


            <dl
                class="
                    grid
                    gap-x-6
                    gap-y-5
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

                    <dt
                        class="
                                text-xs
                                text-slate-500
                            ">
                        {{ $label }}
                    </dt>

                    <dd
                        class="
                                mt-1
                                text-sm
                                font-medium
                                text-slate-950
                            ">
                        {{ $value ?: '—' }}
                    </dd>

                </div>

                @endforeach

                <div>

                    <dt class="text-xs text-slate-500">
                        Fecha de registro
                    </dt>

                    <dd class="mt-1 text-sm font-medium">
                        {{ \App\Support\DateHelper::format(
                            $unit->created_at
                        ) }}
                    </dd>

                </div>

            </dl>

        </section>


        {{-- DOCUMENTOS --}}

        <section
            class="
                rounded-2xl
                border
                border-slate-200
                bg-white
                shadow-sm
            ">

            <div
                class="
                    border-b
                    border-slate-200
                    px-6
                    py-5
                ">
                <h2 class="font-semibold">
                    Documentos de origen
                </h2>
            </div>


            <div class="space-y-5 p-6">

                <div
                    class="
                        grid
                        gap-3
                        sm:grid-cols-2
                    ">

                    @if ($xmlDocument)

                    <a
                        href="{{ route(
                                'documents.download',
                                $xmlDocument
                            ) }}"
                        class="
                                rounded-xl
                                border
                                border-slate-200
                                p-4
                                transition
                                hover:border-blue-300
                                hover:bg-blue-50/40
                            ">

                        <p
                            class="
                                    text-xs
                                    font-semibold
                                    text-blue-600
                                ">
                            XML
                        </p>

                        <p
                            class="
                                    mt-2
                                    truncate
                                    text-sm
                                    font-medium
                                ">
                            {{ $xmlDocument->original_filename }}
                        </p>

                        <p
                            class="
                                    mt-2
                                    text-xs
                                    text-slate-500
                                ">
                            Descargar archivo
                        </p>

                    </a>

                    @endif


                    @if ($pdfDocument)

                    <a
                        href="{{ route(
                                'documents.download',
                                $pdfDocument
                            ) }}"
                        class="
                                rounded-xl
                                border
                                border-slate-200
                                p-4
                                transition
                                hover:border-red-300
                                hover:bg-red-50/40
                            ">

                        <p
                            class="
                                    text-xs
                                    font-semibold
                                    text-red-600
                                ">
                            PDF
                        </p>

                        <p
                            class="
                                    mt-2
                                    truncate
                                    text-sm
                                    font-medium
                                ">
                            {{ $pdfDocument->original_filename }}
                        </p>

                        <p
                            class="
                                    mt-2
                                    text-xs
                                    text-slate-500
                                ">
                            Descargar archivo
                        </p>

                    </a>

                    @endif

                </div>


                @if ($invoice)

                <div
                    class="
                            border-t
                            border-slate-100
                            pt-5
                        ">

                    <dl
                        class="
                                grid
                                gap-4
                                sm:grid-cols-2
                            ">

                        <div>
                            <dt class="text-xs text-slate-500">
                                Factura
                            </dt>

                            <dd class="mt-1 text-sm font-medium">
                                {{ $invoice->series }}
                                {{ $invoice->folio }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs text-slate-500">
                                Total
                            </dt>

                            <dd class="mt-1 text-sm font-medium">
                                {{ $invoice->currency }}
                                {{ number_format(
                                        (float) $invoice->total,
                                        2
                                    ) }}
                            </dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-xs text-slate-500">
                                UUID
                            </dt>

                            <dd
                                class="
                                        mt-1
                                        break-all
                                        font-mono
                                        text-xs
                                        text-slate-700
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


    {{-- TRAZABILIDAD --}}

    <section
        class="
            rounded-2xl
            border
            border-slate-200
            bg-white
            shadow-sm
        ">

        <div
            class="
                border-b
                border-slate-200
                px-6
                py-5
            ">
            <h2 class="font-semibold">
                Trazabilidad de evidencia
            </h2>

            <p
                class="
                    mt-1
                    text-sm
                    text-slate-500
                ">
                Seguimiento de las tres etapas
                principales de la unidad.
            </p>
        </div>


        <div class="p-6">

            <div
                class="
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
                @endphp

                <article
                    class="
                            rounded-2xl
                            border
                            border-slate-200
                            p-5
                        ">

                    <div
                        class="
                                flex
                                items-start
                                justify-between
                                gap-3
                            ">

                        <div>

                            <p
                                class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    ">
                                Etapa
                            </p>

                            <h3
                                class="
                                        mt-2
                                        font-semibold
                                        text-slate-950
                                    ">
                                {{ $stage->label() }}
                            </h3>

                        </div>


                        @if ($milestone)

                        <span
                            class="
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

                    <div class="mt-6">

                        <p
                            class="
                                        text-xs
                                        text-slate-500
                                    ">
                            Evidencias registradas
                        </p>

                        <p
                            class="
                                        mt-1
                                        text-2xl
                                        font-semibold
                                    ">
                            {{ $milestone
                                        ->evidences
                                        ->count()
                                    }}
                        </p>

                    </div>


                    @if ($milestone->completed_at)

                    <p
                        class="
                                        mt-4
                                        text-xs
                                        text-slate-500
                                    ">
                        Completado:
                        {{ \App\Support\DateHelper::format(
                                        $milestone->completed_at
                                    ) }}
                    </p>

                    @endif

                    @endif

                </article>

                @endforeach

            </div>

        </div>

    </section>

    {{-- ACCIÓN OPERATIVA: LLEGADA --}}

    @if (
    $unit->status
    === \App\Enums\UnitStatus::ARRIVAL_PENDING
    && auth()->user()?->can('arrival.complete')
    )

    <livewire:arrival-evidence
        :unit="$unit"
        :key="'arrival-' . $unit->id" />

    @endif


    {{-- ACCIÓN OPERATIVA: ARMADO FINALIZADO --}}

    @if (
    $unit->status
    === \App\Enums\UnitStatus::ASSEMBLY_PENDING
    && auth()->user()?->can('assembly.complete')
    )

    <livewire:assembly-evidence
        :unit="$unit"
        :key="'assembly-' . $unit->id" />

    @endif


    {{-- HISTORIAL --}}

    <section
        class="
        rounded-2xl
        border
        border-slate-200
        bg-white
        shadow-sm
    ">


        {{-- HISTORIAL --}}

        <section
            class="
            rounded-2xl
            border
            border-slate-200
            bg-white
            shadow-sm
        ">

            <div
                class="
                border-b
                border-slate-200
                px-6
                py-5
            ">
                <h2 class="font-semibold">
                    Historial
                </h2>
            </div>


            <div class="p-6">

                <div class="space-y-6">

                    @forelse (
                    $unit->events
                    ->sortByDesc('created_at')
                    as $event
                    )

                    <div
                        class="
                            relative
                            pl-8
                        ">

                        <div
                            class="
                                absolute
                                left-0
                                top-1.5
                                h-3
                                w-3
                                rounded-full
                                bg-blue-600
                            "></div>

                        <p
                            class="
                                text-sm
                                font-semibold
                                text-slate-950
                            ">
                            {{ $event->title }}
                        </p>

                        @if ($event->description)

                        <p
                            class="
                                    mt-1
                                    text-sm
                                    text-slate-500
                                ">
                            {{ $event->description }}
                        </p>

                        @endif

                        <p
                            class="
                                mt-2
                                text-xs
                                text-slate-400
                            ">
                            {{ \App\Support\DateHelper::format(
                                $event->created_at
                            ) }}

                            @if ($event->performedBy)
                            ·
                            {{ $event->performedBy->name }}
                            @endif
                        </p>

                    </div>

                    @empty

                    <p
                        class="
                            text-sm
                            text-slate-500
                        ">
                        No existe actividad registrada.
                    </p>

                    @endforelse

                </div>

            </div>

        </section>

</div>

@endsection