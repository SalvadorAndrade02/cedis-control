<div class="space-y-8">

    {{-- ========================================================= --}}
    {{-- RESUMEN ADMIN / SUPERVISOR --}}
    {{-- ========================================================= --}}

    @if ($managementMode)

        @php
            $totalUnits = (int) ($managementSummary['total'] ?? 0);
            $completedUnits = (int) ($managementSummary['completed'] ?? 0);

            $completionPercentage = $totalUnits > 0
                ? round(($completedUnits / $totalUnits) * 100)
                : 0;
        @endphp


        <section class="space-y-5">

            {{-- HEADER DE SECCIÓN --}}

            <div class="
                        flex
                        flex-col
                        gap-3
                        sm:flex-row
                        sm:items-end
                        sm:justify-between
                    ">

                <div>

                    <div class="
                                flex
                                items-center
                                gap-2
                            ">

                        <span class="
                                    h-2
                                    w-2
                                    rounded-full
                                    bg-blue-500
                                "></span>

                        <p class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-[0.16em]
                                    text-blue-600
                                ">
                            Operación CEDIS
                        </p>

                    </div>


                    <h2 class="
                                mt-2
                                text-2xl
                                font-semibold
                                tracking-tight
                                text-slate-950
                            ">
                        Resumen general
                    </h2>

                    <p class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                        Vista consolidada del estado actual
                        de las unidades.
                    </p>

                </div>


                <div class="
                            rounded-xl
                            border
                            border-slate-200
                            bg-white
                            px-4
                            py-2.5
                            text-xs
                            font-medium
                            text-slate-500
                            shadow-sm
                        ">
                    Actualización en tiempo real
                </div>

            </div>


            {{-- ================================================= --}}
            {{-- CARDS PRINCIPALES --}}
            {{-- ================================================= --}}

            <div class="
                        grid
                        gap-4
                        md:grid-cols-2
                        xl:grid-cols-3
                    ">

                {{-- TOTAL DE UNIDADES --}}

                <article class="
                            group
                            relative
                            overflow-hidden
                            rounded-3xl
                            border
                            border-slate-200/80
                            bg-white
                            p-6
                            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
                            transition
                            duration-200
                            hover:-translate-y-0.5
                            hover:shadow-[0_14px_35px_rgba(15,23,42,0.08)]
                        ">

                    <div class="
                                absolute
                                right-0
                                top-0
                                h-28
                                w-28
                                translate-x-8
                                -translate-y-8
                                rounded-full
                                bg-slate-100
                            "></div>


                    <div class="
                                relative
                                flex
                                items-start
                                justify-between
                                gap-4
                            ">

                        <div>

                            <p class="
                                        text-sm
                                        font-medium
                                        text-slate-500
                                    ">
                                Total de unidades
                            </p>

                            <p class="
                                        mt-4
                                        text-4xl
                                        font-bold
                                        tracking-tight
                                        text-slate-950
                                    ">
                                {{ $managementSummary['total'] }}
                            </p>

                        </div>


                        <div class="
                                    flex
                                    h-12
                                    w-12
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-slate-100
                                    text-slate-700
                                ">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 18.75a1.5 1.5 0 1 1-3 0m13.5 0a1.5 1.5 0 1 1-3 0M3.75 15.75V9.621a2.25 2.25 0 0 1 .659-1.591l2.371-2.371A2.25 2.25 0 0 1 8.371 5h6.879a2.25 2.25 0 0 1 2.25 2.25v1.5h.879a2.25 2.25 0 0 1 1.591.659l.621.621a2.25 2.25 0 0 1 .659 1.591v4.129H3.75Z" />
                            </svg>

                        </div>

                    </div>


                    <div class="
                                relative
                                mt-5
                                flex
                                items-center
                                gap-2
                                text-xs
                                text-slate-500
                            ">

                        <span class="
                                    rounded-full
                                    bg-blue-50
                                    px-2.5
                                    py-1
                                    font-semibold
                                    text-blue-700
                                ">
                            +{{ $managementSummary['importedToday'] }}
                        </span>

                        importada(s) hoy

                    </div>

                </article>


                {{-- LLEGADAS --}}

                <article class="
                            group
                            relative
                            overflow-hidden
                            rounded-3xl
                            border
                            border-blue-100
                            bg-white
                            p-6
                            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
                            transition
                            duration-200
                            hover:-translate-y-0.5
                            hover:shadow-[0_14px_35px_rgba(15,23,42,0.08)]
                        ">

                    <div class="
                                absolute
                                right-0
                                top-0
                                h-28
                                w-28
                                translate-x-8
                                -translate-y-8
                                rounded-full
                                bg-blue-50
                            "></div>


                    <div class="
                                relative
                                flex
                                items-start
                                justify-between
                                gap-4
                            ">

                        <div>

                            <p class="
                                        text-sm
                                        font-medium
                                        text-slate-500
                                    ">
                                Pendientes de llegada
                            </p>

                            <p class="
                                        mt-4
                                        text-4xl
                                        font-bold
                                        tracking-tight
                                        text-blue-600
                                    ">
                                {{ $managementSummary['arrivalPending'] }}
                            </p>

                        </div>


                        <div class="
                                    flex
                                    h-12
                                    w-12
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-blue-50
                                    text-blue-600
                                ">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 15.75h16.5M6 15.75v2.25m12-2.25v2.25M6.75 6h10.5l2.25 5.25H4.5L6.75 6Z" />
                            </svg>

                        </div>

                    </div>


                    @can('arrival.view')

                        <a href="{{ route('operations.arrivals') }}" class="
                                        relative
                                        mt-5
                                        inline-flex
                                        items-center
                                        gap-2
                                        text-sm
                                        font-semibold
                                        text-blue-600
                                        transition
                                        hover:gap-3
                                        hover:text-blue-700
                                    ">
                            Ver llegadas

                            <span>→</span>
                        </a>

                    @endcan

                </article>


                {{-- ARMADOS --}}

                <article class="
                            group
                            relative
                            overflow-hidden
                            rounded-3xl
                            border
                            border-amber-100
                            bg-white
                            p-6
                            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
                            transition
                            duration-200
                            hover:-translate-y-0.5
                            hover:shadow-[0_14px_35px_rgba(15,23,42,0.08)]
                        ">

                    <div class="
                                absolute
                                right-0
                                top-0
                                h-28
                                w-28
                                translate-x-8
                                -translate-y-8
                                rounded-full
                                bg-amber-50
                            "></div>


                    <div class="
                                relative
                                flex
                                items-start
                                justify-between
                                gap-4
                            ">

                        <div>

                            <p class="
                                        text-sm
                                        font-medium
                                        text-slate-500
                                    ">
                                Pendientes de armado
                            </p>

                            <p class="
                                        mt-4
                                        text-4xl
                                        font-bold
                                        tracking-tight
                                        text-amber-600
                                    ">
                                {{ $managementSummary['assemblyPending'] }}
                            </p>

                        </div>


                        <div class="
                                    flex
                                    h-12
                                    w-12
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-amber-50
                                    text-amber-600
                                ">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.42 15.17 17.25 21l2.75-2.75-5.83-5.83m-2.75 2.75L4 7.75 6.75 5l7.42 7.42m-2.75 2.75L9 17.59m5.17-5.17L16.59 10" />
                            </svg>

                        </div>

                    </div>


                    @can('assembly.view')

                        <a href="{{ route('operations.assemblies') }}" class="
                                        relative
                                        mt-5
                                        inline-flex
                                        items-center
                                        gap-2
                                        text-sm
                                        font-semibold
                                        text-amber-700
                                        transition
                                        hover:gap-3
                                        hover:text-amber-800
                                    ">
                            Ver armados

                            <span>→</span>
                        </a>

                    @endcan

                </article>


                {{-- ENTREGAS --}}

                <article class="
                            group
                            relative
                            overflow-hidden
                            rounded-3xl
                            border
                            border-violet-100
                            bg-white
                            p-6
                            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
                            transition
                            duration-200
                            hover:-translate-y-0.5
                            hover:shadow-[0_14px_35px_rgba(15,23,42,0.08)]
                        ">

                    <div class="
                                absolute
                                right-0
                                top-0
                                h-28
                                w-28
                                translate-x-8
                                -translate-y-8
                                rounded-full
                                bg-violet-50
                            "></div>


                    <div class="
                                relative
                                flex
                                items-start
                                justify-between
                                gap-4
                            ">

                        <div>

                            <p class="
                                        text-sm
                                        font-medium
                                        text-slate-500
                                    ">
                                Pendientes de entrega
                            </p>

                            <p class="
                                        mt-4
                                        text-4xl
                                        font-bold
                                        tracking-tight
                                        text-violet-600
                                    ">
                                {{ $managementSummary['deliveryPending'] }}
                            </p>

                        </div>


                        <div class="
                                    flex
                                    h-12
                                    w-12
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-violet-50
                                    text-violet-600
                                ">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h10.5v8.25H3.75V6.75Zm10.5 3h3.75l2.25 2.25v3h-6V9.75ZM6.75 18a1.5 1.5 0 1 1-3 0m15 0a1.5 1.5 0 1 1-3 0" />
                            </svg>

                        </div>

                    </div>


                    @can('delivery.view')

                        <a href="{{ route('operations.deliveries') }}" class="
                                        relative
                                        mt-5
                                        inline-flex
                                        items-center
                                        gap-2
                                        text-sm
                                        font-semibold
                                        text-violet-700
                                        transition
                                        hover:gap-3
                                        hover:text-violet-800
                                    ">
                            Ver entregas

                            <span>→</span>
                        </a>

                    @endcan

                </article>


                {{-- COMPLETADOS --}}

                <article class="
                            group
                            relative
                            overflow-hidden
                            rounded-3xl
                            border
                            border-emerald-200
                            bg-gradient-to-br
                            from-emerald-50
                            to-white
                            p-6
                            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
                            transition
                            duration-200
                            hover:-translate-y-0.5
                            hover:shadow-[0_14px_35px_rgba(15,23,42,0.08)]
                        ">

                    <div class="
                                absolute
                                right-0
                                top-0
                                h-28
                                w-28
                                translate-x-8
                                -translate-y-8
                                rounded-full
                                bg-emerald-100/70
                            "></div>


                    <div class="
                                relative
                                flex
                                items-start
                                justify-between
                                gap-4
                            ">

                        <div>

                            <p class="
                                        text-sm
                                        font-medium
                                        text-emerald-700
                                    ">
                                Expedientes completos
                            </p>

                            <p class="
                                        mt-4
                                        text-4xl
                                        font-bold
                                        tracking-tight
                                        text-emerald-700
                                    ">
                                {{ $managementSummary['completed'] }}
                            </p>

                        </div>


                        <div class="
                                    flex
                                    h-12
                                    w-12
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-emerald-100
                                    text-emerald-700
                                ">
                            ✓
                        </div>

                    </div>


                    <p class="
                                relative
                                mt-5
                                text-xs
                                font-medium
                                text-emerald-700
                            ">
                        +{{ $managementSummary['completedToday'] }}
                        completado(s) hoy
                    </p>

                </article>


                {{-- IMPORTADAS HOY --}}

                <article class="
                            group
                            relative
                            overflow-hidden
                            rounded-3xl
                            border
                            border-sky-100
                            bg-white
                            p-6
                            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
                            transition
                            duration-200
                            hover:-translate-y-0.5
                            hover:shadow-[0_14px_35px_rgba(15,23,42,0.08)]
                        ">

                    <div class="
                                absolute
                                right-0
                                top-0
                                h-28
                                w-28
                                translate-x-8
                                -translate-y-8
                                rounded-full
                                bg-sky-50
                            "></div>


                    <div class="
                                relative
                                flex
                                items-start
                                justify-between
                                gap-4
                            ">

                        <div>

                            <p class="
                                        text-sm
                                        font-medium
                                        text-slate-500
                                    ">
                                Importadas hoy
                            </p>

                            <p class="
                                        mt-4
                                        text-4xl
                                        font-bold
                                        tracking-tight
                                        text-sky-600
                                    ">
                                {{ $managementSummary['importedToday'] }}
                            </p>

                        </div>


                        <div class="
                                    flex
                                    h-12
                                    w-12
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-sky-50
                                    text-sky-600
                                ">
                            ↓
                        </div>

                    </div>


                    <p class="
                                relative
                                mt-5
                                text-xs
                                text-slate-400
                            ">
                        Registros creados durante el día
                    </p>

                </article>

            </div>


            {{-- ================================================= --}}
            {{-- PROGRESO GENERAL --}}
            {{-- ================================================= --}}

            <article class="
                        rounded-3xl
                        border
                        border-slate-200/80
                        bg-white
                        p-6
                        shadow-[0_8px_30px_rgba(15,23,42,0.04)]
                    ">

                <div class="
                            flex
                            flex-col
                            gap-5
                            md:flex-row
                            md:items-center
                            md:justify-between
                        ">

                    <div>

                        <p class="
                                    text-sm
                                    font-semibold
                                    text-slate-950
                                ">
                            Avance general de expedientes
                        </p>

                        <p class="
                                    mt-1
                                    text-sm
                                    text-slate-500
                                ">
                            {{ $completedUnits }}
                            de
                            {{ $totalUnits }}
                            unidades cuentan con expediente completo.
                        </p>

                    </div>


                    <div class="
                                text-left
                                md:text-right
                            ">

                        <p class="
                                    text-3xl
                                    font-bold
                                    tracking-tight
                                    text-slate-950
                                ">
                            {{ $completionPercentage }}%
                        </p>

                        <p class="
                                    mt-1
                                    text-xs
                                    text-slate-400
                                ">
                            Progreso total
                        </p>

                    </div>

                </div>


                <div class="
                            mt-5
                            h-2.5
                            overflow-hidden
                            rounded-full
                            bg-slate-100
                        ">

                    <div class="
                                h-full
                                rounded-full
                                bg-gradient-to-r
                                from-blue-500
                                to-emerald-500
                                transition-all
                                duration-500
                            " style="
                                width:
                                {{ min(
            100,
            max(
                0,
                $completionPercentage
            )
        ) }}%;
                            "></div>

                </div>

            </article>

        </section>

    @endif



    {{-- ========================================================= --}}
    {{-- ÁREAS OPERATIVAS --}}
    {{-- ========================================================= --}}

    @foreach ($queues as $queue)

        @php

            $queueRoute =
                $queue['route'];

            $queueAccent =
                match ($queueRoute) {

                    'operations.arrivals' => [
                        'dot' => 'bg-blue-500',
                        'badge' => 'bg-blue-50 text-blue-700',
                        'button' => 'bg-blue-600 hover:bg-blue-700',
                    ],

                    'operations.assemblies' => [
                        'dot' => 'bg-amber-500',
                        'badge' => 'bg-amber-50 text-amber-700',
                        'button' => 'bg-amber-500 hover:bg-amber-600',
                    ],

                    'operations.deliveries' => [
                        'dot' => 'bg-violet-500',
                        'badge' => 'bg-violet-50 text-violet-700',
                        'button' => 'bg-violet-600 hover:bg-violet-700',
                    ],

                    default => [
                        'dot' => 'bg-slate-500',
                        'badge' => 'bg-slate-100 text-slate-700',
                        'button' => 'bg-slate-950 hover:bg-slate-800',
                    ],
                };

        @endphp


        <section class="
                    overflow-hidden
                    rounded-3xl
                    border
                    border-slate-200/80
                    bg-white
                    shadow-[0_8px_30px_rgba(15,23,42,0.04)]
                ">

            {{-- HEADER --}}

            <div class="
                        border-b
                        border-slate-100
                        px-6
                        py-5
                        lg:px-7
                    ">

                <div class="
                            flex
                            flex-col
                            gap-4
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
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
                                        {{ $queueAccent['dot'] }}
                                    "></span>

                            <h2 class="
                                        text-lg
                                        font-semibold
                                        text-slate-950
                                    ">
                                {{ $queue['title'] }}
                            </h2>

                        </div>


                        <p class="
                                    mt-1.5
                                    text-sm
                                    text-slate-500
                                ">
                            Actividad operativa correspondiente
                            a esta etapa.
                        </p>

                    </div>


                    <a href="{{ route($queue['route']) }}" class="
                                inline-flex
                                items-center
                                justify-center
                                gap-2
                                rounded-xl
                                px-4
                                py-2.5
                                text-sm
                                font-semibold
                                text-white
                                shadow-sm
                                transition
                                {{ $queueAccent['button'] }}
                            ">
                        Ver bandeja

                        <span>→</span>
                    </a>

                </div>

            </div>


            {{-- CONTADORES --}}

            <div class="
                        grid
                        gap-4
                        border-b
                        border-slate-100
                        bg-slate-50/60
                        p-5
                        sm:grid-cols-2
                        lg:p-6
                    ">

                <div class="
                            rounded-2xl
                            border
                            border-slate-200
                            bg-white
                            p-5
                        ">

                    <p class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-400
                            ">
                        {{ $queue['pendingLabel'] }}
                    </p>

                    <p class="
                                mt-3
                                text-3xl
                                font-bold
                                tracking-tight
                                text-slate-950
                            ">
                        {{ $queue['pendingCount'] }}
                    </p>

                </div>


                <div class="
                            rounded-2xl
                            border
                            border-slate-200
                            bg-white
                            p-5
                        ">

                    <p class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-400
                            ">
                        {{ $queue['completedLabel'] }}
                    </p>

                    <p class="
                                mt-3
                                text-3xl
                                font-bold
                                tracking-tight
                                text-emerald-600
                            ">
                        {{ $queue['completedToday'] }}
                    </p>

                </div>

            </div>


            {{-- PRÓXIMAS UNIDADES --}}

            <div>

                @forelse (
                            $queue['units']
                            as $unit
                        )

                        <article class="
                                        border-b
                                        border-slate-100
                                        px-6
                                        py-5
                                        transition
                                        last:border-b-0
                                        hover:bg-slate-50/70
                                        lg:px-7
                                    ">

                            <div class="
                                            flex
                                            flex-col
                                            gap-4
                                            lg:flex-row
                                            lg:items-center
                                            lg:justify-between
                                        ">

                                <div class="
                                                flex
                                                min-w-0
                                                items-start
                                                gap-4
                                            ">

                                    <div class="
                                                    mt-1
                                                    flex
                                                    h-10
                                                    w-10
                                                    shrink-0
                                                    items-center
                                                    justify-center
                                                    rounded-xl
                                                    {{ $queueAccent['badge'] }}
                                                ">
                                        {{ strtoupper(
                        substr(
                            $unit->brand?->name
                            ?? 'U',
                            0,
                            1
                        )
                    ) }}
                                    </div>


                                    <div class="min-w-0">

                                        <div class="
                                                        flex
                                                        flex-wrap
                                                        items-center
                                                        gap-2
                                                    ">

                                            <p class="
                                                            text-xs
                                                            font-semibold
                                                            uppercase
                                                            tracking-wider
                                                            text-blue-600
                                                        ">
                                                {{ $unit->brand?->name
                        ?? 'Sin marca'
                                                        }}
                                            </p>

                                            @if ($unit->year)

                                                <span class="
                                                                    text-xs
                                                                    text-slate-300
                                                                ">
                                                    •
                                                </span>

                                                <span class="
                                                                    text-xs
                                                                    text-slate-500
                                                                ">
                                                    {{ $unit->year }}
                                                </span>

                                            @endif

                                        </div>


                                        <h3 class="
                                                        mt-1
                                                        truncate
                                                        font-semibold
                                                        text-slate-950
                                                    ">
                                            {{ $unit->model
                        ?: 'Modelo sin identificar'
                                                    }}
                                        </h3>


                                        <p class="
                                                        mt-2
                                                        break-all
                                                        font-mono
                                                        text-xs
                                                        tracking-wide
                                                        text-slate-500
                                                    ">
                                            {{ $unit->vin }}
                                        </p>

                                    </div>

                                </div>


                                <a href="{{ route(
                        'units.show',
                        $unit
                    ) }}" class="
                                                inline-flex
                                                shrink-0
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-xl
                                                border
                                                border-slate-300
                                                bg-white
                                                px-4
                                                py-2.5
                                                text-sm
                                                font-semibold
                                                text-slate-700
                                                shadow-sm
                                                transition
                                                hover:border-slate-400
                                                hover:bg-slate-50
                                                active:scale-[.98]
                                            ">
                                    {{ $queue['actionLabel'] }}

                                    <span>→</span>
                                </a>

                            </div>

                        </article>

                @empty

                    <div class="
                                    px-6
                                    py-14
                                    text-center
                                ">

                        <div class="
                                        mx-auto
                                        flex
                                        h-12
                                        w-12
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-emerald-50
                                        text-xl
                                        text-emerald-600
                                    ">
                            ✓
                        </div>


                        <p class="
                                        mt-4
                                        text-sm
                                        font-semibold
                                        text-slate-700
                                    ">
                            No hay unidades pendientes
                        </p>

                        <p class="
                                        mt-1
                                        text-sm
                                        text-slate-500
                                    ">
                            La bandeja está al día.
                        </p>

                    </div>

                @endforelse

            </div>

        </section>

    @endforeach



    {{-- ========================================================= --}}
    {{-- SIN ÁREA OPERATIVA --}}
    {{-- ========================================================= --}}

    @if (
            !$managementMode
            && $queues->isEmpty()
        )

        <section class="
                    rounded-3xl
                    border
                    border-slate-200
                    bg-white
                    px-6
                    py-14
                    text-center
                    shadow-[0_8px_30px_rgba(15,23,42,0.04)]
                ">

            <div class="
                        mx-auto
                        flex
                        h-14
                        w-14
                        items-center
                        justify-center
                        rounded-2xl
                        bg-slate-100
                        text-slate-500
                    ">
                —
            </div>

            <p class="
                        mt-4
                        font-semibold
                        text-slate-800
                    ">
                No tienes una bandeja operativa asignada.
            </p>

            <p class="
                        mt-1
                        text-sm
                        text-slate-500
                    ">
                Consulta con un administrador si necesitas
                acceso a una etapa del proceso.
            </p>

        </section>

    @endif

</div>