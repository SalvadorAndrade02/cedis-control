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

                    <div
                        class="
            mt-5
            border-t
            border-slate-100
            pt-4
        ">

                        <p
                            class="
                text-xs
                text-slate-500
            ">
                            Completado
                        </p>

                        <p
                            class="
                mt-1
                text-sm
                font-medium
                text-slate-900
            ">
                            {{ \App\Support\DateHelper::format(
                $milestone->completed_at
            ) }}
                        </p>


                        @if (
                        $milestone->completed_by_name
                        || $milestone->completedBy
                        )

                        <p
                            class="
            mt-3
            text-xs
            text-slate-500
        ">
                            Realizado por
                        </p>

                        <p
                            class="
            mt-1
            text-sm
            font-medium
            text-slate-900
        ">
                            {{ $milestone->completed_by_name
            ?? $milestone->completedBy?->name
            ?? '—'
        }}
                        </p>

                        @endif

                    </div>

                    @endif
                    @endif
                </article>

                @endforeach

            </div>

        </div>

    </section>

    {{-- EVIDENCIAS DEL EXPEDIENTE --}}

    @if (
    $unit->milestones
    ->sum(
    fn ($milestone) =>
    $milestone->evidences->count()
    ) > 0
    )

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
                Evidencias del expediente
            </h2>

            <p
                class="
                    mt-1
                    text-sm
                    text-slate-500
                ">
                Fotografías registradas durante
                el proceso de la unidad.
            </p>

        </div>


        <div class="space-y-8 p-6">

            @foreach (
            $unit->milestones
            ->sortBy('id')
            as $milestone
            )

            @if (
            $milestone->evidences->isNotEmpty()
            )

            <div>

                {{-- HEADER ETAPA --}}

                <div
                    class="
                                flex
                                flex-col
                                gap-2
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                            ">

                    <div>

                        <h3
                            class="
                                        font-semibold
                                        text-slate-950
                                    ">
                            {{ $milestone
                                        ->stage
                                        ->label()
                                    }}
                        </h3>


                        <div
                            class="
                                        mt-1
                                        flex
                                        flex-wrap
                                        gap-x-3
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
                                ·
                                {{ $milestone->completed_by_name
            ?? $milestone->completedBy?->name
            ?? '—'
        }}
                            </span>

                            @endif

                        </div>

                    </div>


                    <span
                        class="
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

                <div
                    class="
                                mt-4
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

                    <a
                        href="{{ route(
                                        'evidences.show',
                                        $evidence
                                    ) }}"
                        target="_blank"
                        class="
                                        group
                                        relative
                                        overflow-hidden
                                        rounded-xl
                                        border
                                        border-slate-200
                                        bg-slate-100
                                    ">

                        @if (
                        $evidence->type
                        === \App\Enums\EvidenceType::IMAGE
                        )

                        <img
                            src="{{ route(
                                                'evidences.show',
                                                $evidence
                                            ) }}"
                            alt="Evidencia"
                            loading="lazy"
                            class="
                                                aspect-square
                                                w-full
                                                object-cover
                                                transition
                                                duration-200
                                                group-hover:scale-105
                                            ">

                        @else

                        <div
                            class="
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


                        <div
                            class="
                                            absolute
                                            inset-x-0
                                            bottom-0
                                            bg-gradient-to-t
                                            from-black/70
                                            to-transparent
                                            p-3
                                            pt-10
                                        ">

                            <p
                                class="
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

                <div
                    class="
                                    mt-4
                                    rounded-xl
                                    bg-slate-50
                                    p-4
                                ">

                    <p
                        class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    ">
                        Observaciones
                    </p>

                    <p
                        class="
                                        mt-2
                                        text-sm
                                        leading-6
                                        text-slate-700
                                    ">
                        {{ $milestone->observations }}
                    </p>

                </div>

                @endif

                @if (
                $milestone->stage
                === \App\Enums\MilestoneStage::CARRIER_DELIVERY
                && $milestone->carrierDelivery
                )

                <div
                    class="
            mt-4
            rounded-xl
            border
            border-slate-200
            p-4
        ">

                    <p
                        class="
                text-xs
                font-semibold
                uppercase
                tracking-wider
                text-slate-400
            ">
                        Datos de entrega
                    </p>


                    <dl
                        class="
                mt-4
                grid
                gap-4
                sm:grid-cols-2
                lg:grid-cols-3
            ">

                        <div>

                            <dt class="text-xs text-slate-500">
                                Transportadora
                            </dt>

                            <dd class="mt-1 text-sm font-medium">
                                {{ $milestone
                        ->carrierDelivery
                        ->carrier
                        ?->name
                        ?? '—'
                    }}
                            </dd>

                        </div>


                        <div>

                            <dt class="text-xs text-slate-500">
                                Operador
                            </dt>

                            <dd class="mt-1 text-sm font-medium">
                                {{ $milestone
                        ->carrierDelivery
                        ->operator_name
                        ?? '—'
                    }}
                            </dd>

                        </div>


                        <div>

                            <dt class="text-xs text-slate-500">
                                Placas
                            </dt>

                            <dd class="mt-1 text-sm font-medium">
                                {{ $milestone
                        ->carrierDelivery
                        ->vehicle_plate
                        ?? '—'
                    }}
                            </dd>

                        </div>


                        @if (
                        $milestone
                        ->carrierDelivery
                        ->vehicle_number
                        )

                        <div>

                            <dt class="text-xs text-slate-500">
                                Número económico
                            </dt>

                            <dd class="mt-1 text-sm font-medium">
                                {{ $milestone
                            ->carrierDelivery
                            ->vehicle_number
                        }}
                            </dd>

                        </div>

                        @endif


                        @if (
                        $milestone
                        ->carrierDelivery
                        ->transport_type
                        )

                        <div>

                            <dt class="text-xs text-slate-500">
                                Tipo de transporte
                            </dt>

                            <dd class="mt-1 text-sm font-medium">
                                {{ $milestone
                            ->carrierDelivery
                            ->transport_type
                        }}
                            </dd>

                        </div>

                        @endif

                    </dl>

                </div>

                @endif

            </div>

            @unless ($loop->last)

            <div
                class="
                                border-t
                                border-slate-100
                            "></div>

            @endunless

            @endif

            @endforeach

        </div>

    </section>

    @endif

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


    {{-- ACCIÓN OPERATIVA: ENTREGA A TRANSPORTADORA --}}

    @if (
    $unit->status
    === \App\Enums\UnitStatus::DELIVERY_PENDING
    && auth()->user()?->can('delivery.complete')
    )

    <livewire:delivery-evidence
        :unit="$unit"
        :key="'delivery-' . $unit->id" />

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

                        @if (
                        $event->performed_by_name
                        || $event->performedBy
                        )

                        ·
                        {{ $event->performed_by_name
        ?? $event->performedBy?->name
        ?? '—'
    }}

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