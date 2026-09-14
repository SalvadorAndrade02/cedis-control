<div class="space-y-6">

    @php

        /*
        |--------------------------------------------------------------------------
        | IDENTIDAD VISUAL DE LA BANDEJA
        |--------------------------------------------------------------------------
        */

        $queueVisual = match ($type) {

            'arrival' => [
                'eyebrow' => 'Recepción',
                'color' => 'blue',
                'dot' => 'bg-blue-500',
                'soft' => 'bg-blue-50',
                'softText' => 'text-blue-700',
                'border' => 'border-blue-100',
                'button' => 'bg-blue-600 hover:bg-blue-700',
                'iconBg' => 'bg-blue-100',
                'iconText' => 'text-blue-700',
            ],

            'assembly' => [
                'eyebrow' => 'Armado',
                'color' => 'amber',
                'dot' => 'bg-amber-500',
                'soft' => 'bg-amber-50',
                'softText' => 'text-amber-700',
                'border' => 'border-amber-100',
                'button' => 'bg-amber-500 hover:bg-amber-600',
                'iconBg' => 'bg-amber-100',
                'iconText' => 'text-amber-700',
            ],

            'delivery' => [
                'eyebrow' => 'Entrega',
                'color' => 'violet',
                'dot' => 'bg-violet-500',
                'soft' => 'bg-violet-50',
                'softText' => 'text-violet-700',
                'border' => 'border-violet-100',
                'button' => 'bg-violet-600 hover:bg-violet-700',
                'iconBg' => 'bg-violet-100',
                'iconText' => 'text-violet-700',
            ],

            default => [
                'eyebrow' => 'Operación',
                'color' => 'slate',
                'dot' => 'bg-slate-500',
                'soft' => 'bg-slate-50',
                'softText' => 'text-slate-700',
                'border' => 'border-slate-200',
                'button' => 'bg-slate-950 hover:bg-slate-800',
                'iconBg' => 'bg-slate-100',
                'iconText' => 'text-slate-700',
            ],
        };

    @endphp


    {{-- ========================================================= --}}
    {{-- RESUMEN --}}
    {{-- ========================================================= --}}

    <section class="
            grid
            gap-4
            md:grid-cols-2
        ">

        {{-- PENDIENTES --}}

        <article class="
                relative
                overflow-hidden
                rounded-3xl
                border
                {{ $queueVisual['border'] }}
                bg-white
                p-6
                shadow-[0_8px_30px_rgba(15,23,42,0.04)]
            ">

            <div class="
                    absolute
                    right-0
                    top-0
                    h-32
                    w-32
                    translate-x-10
                    -translate-y-10
                    rounded-full
                    {{ $queueVisual['soft'] }}
                "></div>


            <div class="
                    relative
                    flex
                    items-start
                    justify-between
                    gap-5
                ">

                <div>

                    <p class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-[0.14em]
                            text-slate-400
                        ">
                        {{ $queueVisual['eyebrow'] }}
                    </p>

                    <p class="
                            mt-2
                            text-sm
                            font-medium
                            text-slate-500
                        ">
                        Pendientes
                    </p>

                    <p class="
                            mt-3
                            text-4xl
                            font-bold
                            tracking-tight
                            text-slate-950
                        ">
                        {{ $units->total() }}
                    </p>

                    <p class="
                            mt-2
                            text-xs
                            text-slate-400
                        ">
                        Unidad(es) esperando atención
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
                        {{ $queueVisual['iconBg'] }}
                        {{ $queueVisual['iconText'] }}
                    ">

                    @if ($type === 'arrival')

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 15.75h16.5M6 15.75v2.25m12-2.25v2.25M6.75 6h10.5l2.25 5.25H4.5L6.75 6Z" />
                        </svg>

                    @elseif ($type === 'assembly')

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.42 15.17 17.25 21l2.75-2.75-5.83-5.83m-2.75 2.75L4 7.75 6.75 5l7.42 7.42" />
                        </svg>

                    @else

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h10.5v8.25H3.75V6.75Zm10.5 3h3.75l2.25 2.25v3h-6V9.75ZM6.75 18a1.5 1.5 0 1 1-3 0m15 0a1.5 1.5 0 1 1-3 0" />
                        </svg>

                    @endif

                </div>

            </div>

        </article>


        {{-- COMPLETADAS HOY --}}

        <article class="
                relative
                overflow-hidden
                rounded-3xl
                border
                border-emerald-100
                bg-gradient-to-br
                from-emerald-50/70
                to-white
                p-6
                shadow-[0_8px_30px_rgba(15,23,42,0.04)]
            ">

            <div class="
                    flex
                    items-start
                    justify-between
                    gap-5
                ">

                <div>

                    <p class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-[0.14em]
                            text-emerald-700
                        ">
                        Productividad
                    </p>

                    <p class="
                            mt-2
                            text-sm
                            font-medium
                            text-slate-500
                        ">
                        Completadas hoy
                    </p>

                    <p class="
                            mt-3
                            text-4xl
                            font-bold
                            tracking-tight
                            text-emerald-700
                        ">
                        {{ $completedToday }}
                    </p>

                    <p class="
                            mt-2
                            text-xs
                            text-emerald-600
                        ">
                        Operaciones cerradas durante el día
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
                        text-lg
                        font-bold
                        text-emerald-700
                    ">
                    ✓
                </div>

            </div>

        </article>

    </section>



    {{-- ========================================================= --}}
    {{-- BUSCADOR --}}
    {{-- ========================================================= --}}

    <section class="
            rounded-3xl
            border
            border-slate-200/80
            bg-white
            p-5
            shadow-[0_8px_30px_rgba(15,23,42,0.04)]
        ">

        <div class="
                flex
                flex-col
                gap-4
                lg:flex-row
                lg:items-end
                lg:justify-between
            ">

            <div class="min-w-0 flex-1">

                <label class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.12em]
                        text-slate-500
                    ">
                    Buscar unidad
                </label>


                <div class="relative mt-2">

                    <div class="
                            pointer-events-none
                            absolute
                            inset-y-0
                            left-0
                            flex
                            items-center
                            pl-4
                            text-slate-400
                        ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" />
                        </svg>

                    </div>


                    <input type="search" wire:model.live.debounce.400ms="search"
                        placeholder="Buscar por VIN, marca o modelo..." class="
                            w-full
                            rounded-2xl
                            border
                            border-slate-200
                            bg-slate-50/70
                            py-3.5
                            pl-12
                            pr-4
                            text-sm
                            text-slate-900
                            outline-none
                            transition
                            placeholder:text-slate-400
                            hover:border-slate-300
                            focus:border-blue-500
                            focus:bg-white
                            focus:ring-4
                            focus:ring-blue-500/10
                        ">

                </div>

            </div>


            <div wire:loading wire:target="search" class="
                    text-xs
                    font-medium
                    text-blue-600
                ">
                Buscando unidades...
            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- COLA OPERATIVA --}}
    {{-- ========================================================= --}}

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
                    gap-3
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
                                {{ $queueVisual['dot'] }}
                            "></span>

                        <p class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.16em]
                                {{ $queueVisual['softText'] }}
                            ">
                            Cola operativa
                        </p>

                    </div>


                    <h2 class="
                            mt-2
                            text-xl
                            font-semibold
                            tracking-tight
                            text-slate-950
                        ">
                        {{ $title }}
                    </h2>

                    <p class="
                            mt-1
                            text-sm
                            text-slate-500
                        ">
                        {{ $description }}
                    </p>

                </div>


                <span class="
                        w-fit
                        rounded-full
                        {{ $queueVisual['soft'] }}
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        {{ $queueVisual['softText'] }}
                    ">
                    {{ $units->total() }}
                    pendiente(s)
                </span>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- UNIDADES --}}
        {{-- ===================================================== --}}

        <div class="
                divide-y
                divide-slate-100
            ">

            @forelse ($units as $unit)

                        @php

                            $previousMilestone =
                                $previousStage
                                ? $unit
                                    ->milestones
                                    ->first(
                                        fn($milestone) =>
                                            $milestone->stage
                                            === $previousStage
                                    )
                                : null;

                        @endphp


                        <article class="
                                    group
                                    px-5
                                    py-5
                                    transition
                                    hover:bg-slate-50/70
                                    lg:px-7
                                ">

                            <div class="
                                        flex
                                        flex-col
                                        gap-5
                                        xl:flex-row
                                        xl:items-center
                                        xl:justify-between
                                    ">

                                {{-- DATOS PRINCIPALES --}}

                                <div class="
                                            flex
                                            min-w-0
                                            items-start
                                            gap-4
                                        ">

                                    {{-- INICIAL MARCA --}}

                                    <div class="
                                                flex
                                                h-12
                                                w-12
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-2xl
                                                {{ $queueVisual['soft'] }}
                                                {{ $queueVisual['softText'] }}
                                                text-sm
                                                font-bold
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

                                            <span class="
                                                        text-[10px]
                                                        font-semibold
                                                        uppercase
                                                        tracking-[0.14em]
                                                        {{ $queueVisual['softText'] }}
                                                    ">
                                                {{ $unit->brand?->name ?? 'Sin marca' }}
                                            </span>


                                            @if ($unit->year)

                                                <span class="text-slate-300">
                                                    •
                                                </span>

                                                <span class="
                                                                text-xs
                                                                text-slate-500
                                                            ">
                                                    {{ $unit->year }}
                                                </span>

                                            @endif


                                            @if ($unit->exterior_color)

                                                <span class="text-slate-300">
                                                    •
                                                </span>

                                                <span class="
                                                                text-xs
                                                                text-slate-500
                                                            ">
                                                    {{ $unit->exterior_color }}
                                                </span>

                                            @endif

                                        </div>


                                        <h3 class="
                                                    mt-1
                                                    truncate
                                                    text-lg
                                                    font-semibold
                                                    text-slate-950
                                                ">
                                            {{ $unit->model ?: 'Sin modelo' }}
                                        </h3>


                                        <div class="
                                                    mt-2
                                                    inline-flex
                                                    max-w-full
                                                    rounded-lg
                                                    bg-slate-100
                                                    px-3
                                                    py-1.5
                                                ">

                                            <p class="
                                                        truncate
                                                        font-mono
                                                        text-xs
                                                        font-medium
                                                        tracking-wide
                                                        text-slate-600
                                                    ">
                                                {{ $unit->vin }}
                                            </p>

                                        </div>



                                        {{-- INFORMACIÓN PREVIA --}}

                                        @if (
                                                                    $previousMilestone
                                                                    && $previousMilestone->completed_at
                                                                )

                                                                <div class="
                                                                                mt-4
                                                                                flex
                                                                                flex-wrap
                                                                                gap-x-5
                                                                                gap-y-2
                                                                                text-xs
                                                                                text-slate-500
                                                                            ">

                                                                    <span class="
                                                                                    inline-flex
                                                                                    items-center
                                                                                    gap-1.5
                                                                                ">

                                                                        <span class="
                                                                                        h-1.5
                                                                                        w-1.5
                                                                                        rounded-full
                                                                                        bg-emerald-500
                                                                                    "></span>

                                                                        Etapa anterior completada

                                                                    </span>


                                                                    <span>
                                                                        {{ \App\Support\DateHelper::format(
                                                $previousMilestone->completed_at
                                            ) }}
                                                                    </span>


                                                                    @if (
                                                                                            $previousMilestone->completed_by_name
                                                                                            || $previousMilestone->completedBy
                                                                                        )

                                                                                        <span>
                                                                                            Por:
                                                                                            <strong class="
                                                                                                                font-semibold
                                                                                                                text-slate-700
                                                                                                            ">
                                                                                                {{ $previousMilestone->completed_by_name
                                                                        ?? $previousMilestone->completedBy?->name
                                                                        ?? '—'
                                                                                                            }}
                                                                                            </strong>
                                                                                        </span>

                                                                    @endif

                                                                </div>


                                        @elseif ($type === 'arrival')

                                                                <div class="
                                                                                mt-4
                                                                                flex
                                                                                items-center
                                                                                gap-2
                                                                                text-xs
                                                                                text-slate-500
                                                                            ">

                                                                    <span class="
                                                                                    h-1.5
                                                                                    w-1.5
                                                                                    rounded-full
                                                                                    bg-blue-500
                                                                                "></span>

                                                                    Importada:

                                                                    <strong class="
                                                                                    font-semibold
                                                                                    text-slate-700
                                                                                ">
                                                                        {{ \App\Support\DateHelper::format(
                                                $unit->created_at
                                            ) }}
                                                                    </strong>

                                                                </div>

                                        @endif

                                    </div>

                                </div>



                                {{-- ACCIONES --}}

                                <div class="
                                            flex
                                            shrink-0
                                            flex-col
                                            gap-2
                                            sm:flex-row
                                        ">

                                    <a href="{{ route(
                    'units.show',
                    $unit
                ) }}" class="
                                                inline-flex
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-xl
                                                border
                                                border-slate-200
                                                bg-white
                                                px-4
                                                py-3
                                                text-sm
                                                font-semibold
                                                text-slate-700
                                                shadow-sm
                                                transition
                                                hover:border-slate-300
                                                hover:bg-slate-50
                                                active:scale-[.98]
                                            ">
                                        Ver expediente
                                    </a>


                                    <a href="{{ route(
                    'units.show',
                    $unit
                ) }}" class="
                                                inline-flex
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-xl
                                                px-4
                                                py-3
                                                text-sm
                                                font-semibold
                                                text-white
                                                shadow-sm
                                                transition
                                                active:scale-[.98]
                                                {{ $queueVisual['button'] }}
                                            ">
                                        {{ $actionLabel }}

                                        <span>→</span>
                                    </a>

                                </div>

                            </div>

                        </article>


            @empty

                {{-- ================================================= --}}
                {{-- VACÍO --}}
                {{-- ================================================= --}}

                <div class="
                            px-6
                            py-16
                            text-center
                        ">

                    <div class="
                                mx-auto
                                flex
                                h-14
                                w-14
                                items-center
                                justify-center
                                rounded-2xl
                                bg-emerald-50
                                text-xl
                                font-bold
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
                                mx-auto
                                mt-1
                                max-w-md
                                text-sm
                                leading-6
                                text-slate-500
                            ">
                        La bandeja se encuentra al día.
                        Las nuevas unidades aparecerán aquí
                        automáticamente cuando lleguen a esta etapa.
                    </p>

                </div>

            @endforelse

        </div>



        {{-- ===================================================== --}}
        {{-- PAGINACIÓN --}}
        {{-- ===================================================== --}}

        @if ($units->hasPages())

            <div class="
                        border-t
                        border-slate-100
                        bg-slate-50/40
                        px-5
                        py-4
                        lg:px-7
                    ">
                {{ $units->links() }}
            </div>

        @endif

    </section>

</div>