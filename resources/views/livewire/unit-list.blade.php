<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- FILTROS --}}
    {{-- ========================================================= --}}

    <section class="
            rounded-3xl
            border
            border-slate-200/80
            bg-white
            p-5
            shadow-[0_8px_30px_rgba(15,23,42,0.04)]
            lg:p-6
        ">

        <div class="
                flex
                flex-col
                gap-5
                xl:flex-row
                xl:items-end
            ">

            {{-- BUSCADOR --}}

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


            {{-- ESTADO --}}

            <div class="xl:w-72">

                <label class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.12em]
                        text-slate-500
                    ">
                    Estado
                </label>

                <select wire:model.live="status" class="
                        mt-2
                        w-full
                        rounded-2xl
                        border
                        border-slate-200
                        bg-slate-50/70
                        px-4
                        py-3.5
                        text-sm
                        text-slate-700
                        outline-none
                        transition
                        hover:border-slate-300
                        focus:border-blue-500
                        focus:bg-white
                        focus:ring-4
                        focus:ring-blue-500/10
                    ">

                    <option value="">
                        Todos los estados
                    </option>

                    @foreach ($statuses as $item)

                        <option value="{{ $item->value }}">
                            {{ $item->label() }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- LIMPIAR --}}

            <button type="button" wire:click="clearFilters" class="
                    inline-flex
                    items-center
                    justify-center
                    gap-2
                    rounded-2xl
                    border
                    border-slate-200
                    bg-white
                    px-5
                    py-3.5
                    text-sm
                    font-semibold
                    text-slate-600
                    shadow-sm
                    transition
                    hover:border-slate-300
                    hover:bg-slate-50
                    hover:text-slate-950
                    active:scale-[.98]
                ">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                    stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992V4.356m-.919 9.144a8.25 8.25 0 1 1-2.29-8.573L21.015 8.14" />
                </svg>

                Limpiar

            </button>

        </div>


        {{-- INDICADOR DE CARGA --}}

        <div wire:loading wire:target="search,status,clearFilters" class="
                mt-4
                text-xs
                font-medium
                text-blue-600
            ">
            Actualizando resultados...
        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- LISTADO --}}
    {{-- ========================================================= --}}

    <section class="
            overflow-hidden
            rounded-3xl
            border
            border-slate-200/80
            bg-white
            shadow-[0_8px_30px_rgba(15,23,42,0.04)]
        ">

        {{-- HEADER DEL LISTADO --}}

        <div class="
                flex
                flex-col
                gap-4
                border-b
                border-slate-100
                px-5
                py-5
                sm:flex-row
                sm:items-center
                sm:justify-between
                lg:px-7
            ">

            <div class="
                    flex
                    items-center
                    gap-4
                ">

                <div class="
                        flex
                        h-11
                        w-11
                        shrink-0
                        items-center
                        justify-center
                        rounded-2xl
                        bg-slate-100
                        text-slate-700
                    ">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 1 1-3 0m13.5 0a1.5 1.5 0 1 1-3 0M3.75 15.75V9.621a2.25 2.25 0 0 1 .659-1.591l2.371-2.371A2.25 2.25 0 0 1 8.371 5h6.879a2.25 2.25 0 0 1 2.25 2.25v1.5h.879a2.25 2.25 0 0 1 1.591.659l.621.621a2.25 2.25 0 0 1 .659 1.591v4.129H3.75Z" />
                    </svg>

                </div>


                <div>

                    <h2 class="
                            text-lg
                            font-semibold
                            text-slate-950
                        ">
                        Unidades registradas
                    </h2>

                    <p class="
                            mt-0.5
                            text-sm
                            text-slate-500
                        ">
                        {{ $units->total() }}
                        {{ $units->total() === 1
    ? 'unidad encontrada'
    : 'unidades encontradas'
                        }}
                    </p>

                </div>

            </div>


            @can('imports.manage')

                <a href="{{ route('imports.index') }}" class="
                            inline-flex
                            items-center
                            justify-center
                            gap-2
                            rounded-xl
                            bg-blue-600
                            px-4
                            py-3
                            text-sm
                            font-semibold
                            text-white
                            shadow-sm
                            transition
                            hover:bg-blue-700
                            hover:shadow-md
                            active:scale-[.98]
                        ">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    Importar unidad

                </a>

            @endcan

        </div>



        {{-- ===================================================== --}}
        {{-- DESKTOP --}}
        {{-- ===================================================== --}}

        <div class="hidden overflow-x-auto md:block">

            <table class="min-w-full">

                <thead class="
                        border-b
                        border-slate-100
                        bg-slate-50/70
                    ">

                    <tr class="
                            text-left
                            text-[11px]
                            font-semibold
                            uppercase
                            tracking-[0.12em]
                            text-slate-400
                        ">

                        <th class="px-6 py-4 lg:px-7">
                            Unidad
                        </th>

                        <th class="px-6 py-4">
                            VIN
                        </th>

                        <th class="px-6 py-4">
                            Año
                        </th>

                        <th class="px-6 py-4">
                            Estado
                        </th>

                        <th class="px-6 py-4">
                            Registro
                        </th>

                        <th class="
                                px-6
                                py-4
                                text-right
                                lg:px-7
                            ">
                            Acción
                        </th>

                    </tr>

                </thead>


                <tbody class="
                        divide-y
                        divide-slate-100
                    ">

                    @forelse ($units as $unit)

                                        @php

                                            $statusAccent = match (
                                            $unit->status->value
                                            ) {
                                                'ARRIVAL_PENDING' => [
                                                    'bg' => 'bg-blue-50',
                                                    'text' => 'text-blue-700',
                                                    'ring' => 'ring-blue-100',
                                                ],

                                                'ASSEMBLY_PENDING' => [
                                                    'bg' => 'bg-amber-50',
                                                    'text' => 'text-amber-700',
                                                    'ring' => 'ring-amber-100',
                                                ],

                                                'DELIVERY_PENDING' => [
                                                    'bg' => 'bg-violet-50',
                                                    'text' => 'text-violet-700',
                                                    'ring' => 'ring-violet-100',
                                                ],

                                                'COMPLETED' => [
                                                    'bg' => 'bg-emerald-50',
                                                    'text' => 'text-emerald-700',
                                                    'ring' => 'ring-emerald-100',
                                                ],

                                                default => [
                                                    'bg' => 'bg-slate-100',
                                                    'text' => 'text-slate-700',
                                                    'ring' => 'ring-slate-200',
                                                ],
                                            };

                                        @endphp


                                        <tr class="
                                                    group
                                                    transition
                                                    hover:bg-slate-50/70
                                                ">

                                            {{-- UNIDAD --}}

                                            <td class="
                                                        px-6
                                                        py-5
                                                        lg:px-7
                                                    ">

                                                <div class="
                                                            flex
                                                            items-center
                                                            gap-4
                                                        ">

                                                    <div class="
                                                                flex
                                                                h-11
                                                                w-11
                                                                shrink-0
                                                                items-center
                                                                justify-center
                                                                rounded-2xl
                                                                bg-slate-100
                                                                text-sm
                                                                font-bold
                                                                text-slate-600
                                                                transition
                                                                group-hover:bg-white
                                                                group-hover:shadow-sm
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

                                                        <p class="
                                                                    truncate
                                                                    text-sm
                                                                    font-semibold
                                                                    text-slate-950
                                                                ">
                                                            {{ $unit->model
                            ?: 'Sin modelo'
                                                                }}
                                                        </p>


                                                        <div class="
                                                                    mt-1
                                                                    flex
                                                                    flex-wrap
                                                                    items-center
                                                                    gap-1.5
                                                                    text-xs
                                                                    text-slate-500
                                                                ">

                                                            <span>
                                                                {{ $unit->brand?->name
                            ?? 'Sin marca'
                                                                    }}
                                                            </span>


                                                            @if ($unit->exterior_color)

                                                                <span class="
                                                                                text-slate-300
                                                                            ">
                                                                    •
                                                                </span>

                                                                <span>
                                                                    {{ $unit->exterior_color }}
                                                                </span>

                                                            @endif

                                                        </div>

                                                    </div>

                                                </div>

                                            </td>


                                            {{-- VIN --}}

                                            <td class="px-6 py-5">

                                                <span class="
                                                            whitespace-nowrap
                                                            font-mono
                                                            text-xs
                                                            font-medium
                                                            tracking-wide
                                                            text-slate-700
                                                        ">
                                                    {{ $unit->vin }}
                                                </span>

                                            </td>


                                            {{-- AÑO --}}

                                            <td class="
                                                        px-6
                                                        py-5
                                                        text-sm
                                                        font-medium
                                                        text-slate-700
                                                    ">
                                                {{ $unit->year ?? '—' }}
                                            </td>


                                            {{-- ESTADO --}}

                                            <td class="px-6 py-5">

                                                <span class="
                                                            inline-flex
                                                            items-center
                                                            gap-2
                                                            rounded-full
                                                            px-3
                                                            py-1.5
                                                            text-xs
                                                            font-semibold
                                                            ring-1
                                                            ring-inset
                                                            {{ $statusAccent['bg'] }}
                                                            {{ $statusAccent['text'] }}
                                                            {{ $statusAccent['ring'] }}
                                                        ">

                                                    <span class="
                                                                h-1.5
                                                                w-1.5
                                                                rounded-full
                                                                bg-current
                                                            "></span>

                                                    {{ $unit->status->label() }}

                                                </span>

                                            </td>


                                            {{-- REGISTRO --}}

                                            <td class="
                                                        px-6
                                                        py-5
                                                        text-sm
                                                        text-slate-500
                                                    ">
                                                {{ \App\Support\DateHelper::format(
                            $unit->created_at
                        ) }}
                                            </td>


                                            {{-- ACCIÓN --}}

                                            <td class="
                                                        px-6
                                                        py-5
                                                        text-right
                                                        lg:px-7
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
                                                            px-3.5
                                                            py-2.5
                                                            text-xs
                                                            font-semibold
                                                            text-slate-700
                                                            shadow-sm
                                                            transition
                                                            hover:border-blue-200
                                                            hover:bg-blue-50
                                                            hover:text-blue-700
                                                        ">
                                                    Ver expediente

                                                    <span>→</span>
                                                </a>

                                            </td>

                                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="
                                        px-6
                                        py-20
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
                                            bg-slate-100
                                            text-slate-400
                                        ">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.7" stroke="currentColor" class="h-6 w-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" />
                                    </svg>

                                </div>

                                <p class="
                                            mt-4
                                            text-sm
                                            font-semibold
                                            text-slate-700
                                        ">
                                    No se encontraron unidades
                                </p>

                                <p class="
                                            mt-1
                                            text-sm
                                            text-slate-500
                                        ">
                                    Intenta modificar los filtros
                                    de búsqueda.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



        {{-- ===================================================== --}}
        {{-- MOBILE --}}
        {{-- ===================================================== --}}

        <div class="
                divide-y
                divide-slate-100
                md:hidden
            ">

            @forelse ($units as $unit)

                        @php

                            $statusAccent = match (
                            $unit->status->value
                            ) {
                                'ARRIVAL_PENDING' =>
                                    'bg-blue-50 text-blue-700 ring-blue-100',

                                'ASSEMBLY_PENDING' =>
                                    'bg-amber-50 text-amber-700 ring-amber-100',

                                'DELIVERY_PENDING' =>
                                    'bg-violet-50 text-violet-700 ring-violet-100',

                                'COMPLETED' =>
                                    'bg-emerald-50 text-emerald-700 ring-emerald-100',

                                default =>
                                    'bg-slate-100 text-slate-700 ring-slate-200',
                            };

                        @endphp


                        <article class="
                                    p-5
                                    transition
                                    hover:bg-slate-50/70
                                ">

                            {{-- ENCABEZADO --}}

                            <div class="
                                        flex
                                        items-start
                                        gap-3
                                    ">

                                <div class="
                                            flex
                                            h-11
                                            w-11
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-2xl
                                            bg-slate-100
                                            text-sm
                                            font-bold
                                            text-slate-600
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


                                <div class="min-w-0 flex-1">

                                    <p class="
                                                text-[11px]
                                                font-semibold
                                                uppercase
                                                tracking-[0.12em]
                                                text-blue-600
                                            ">
                                        {{ $unit->brand?->name
                    ?? 'Sin marca'
                                            }}
                                    </p>

                                    <h3 class="
                                                mt-1
                                                truncate
                                                text-base
                                                font-semibold
                                                text-slate-950
                                            ">
                                        {{ $unit->model
                    ?: 'Sin modelo'
                                            }}
                                    </h3>

                                </div>

                            </div>


                            {{-- ESTADO --}}

                            <div class="mt-4">

                                <span class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-full
                                            px-3
                                            py-1.5
                                            text-xs
                                            font-semibold
                                            ring-1
                                            ring-inset
                                            {{ $statusAccent }}
                                        ">

                                    <span class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-current
                                            "></span>

                                    {{ $unit->status->label() }}

                                </span>

                            </div>


                            {{-- DATOS --}}

                            <div class="
                                        mt-5
                                        rounded-2xl
                                        bg-slate-50
                                        p-4
                                    ">

                                <dl class="
                                            grid
                                            grid-cols-2
                                            gap-x-4
                                            gap-y-4
                                        ">

                                    <div class="col-span-2">

                                        <dt class="
                                                    text-[10px]
                                                    font-semibold
                                                    uppercase
                                                    tracking-wider
                                                    text-slate-400
                                                ">
                                            VIN
                                        </dt>

                                        <dd class="
                                                    mt-1
                                                    break-all
                                                    font-mono
                                                    text-xs
                                                    font-medium
                                                    tracking-wide
                                                    text-slate-800
                                                ">
                                            {{ $unit->vin }}
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
                                            Año
                                        </dt>

                                        <dd class="
                                                    mt-1
                                                    text-sm
                                                    font-semibold
                                                    text-slate-800
                                                ">
                                            {{ $unit->year ?? '—' }}
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
                                            Color
                                        </dt>

                                        <dd class="
                                                    mt-1
                                                    text-sm
                                                    font-semibold
                                                    text-slate-800
                                                ">
                                            {{ $unit->exterior_color ?? '—' }}
                                        </dd>

                                    </div>


                                    <div class="col-span-2">

                                        <dt class="
                                                    text-[10px]
                                                    font-semibold
                                                    uppercase
                                                    tracking-wider
                                                    text-slate-400
                                                ">
                                            Registro
                                        </dt>

                                        <dd class="
                                                    mt-1
                                                    text-sm
                                                    font-medium
                                                    text-slate-700
                                                ">
                                            {{ \App\Support\DateHelper::format(
                    $unit->created_at
                ) }}
                                        </dd>

                                    </div>

                                </dl>

                            </div>


                            {{-- ACCIÓN --}}

                            <a href="{{ route(
                    'units.show',
                    $unit
                ) }}" class="
                                        mt-4
                                        flex
                                        w-full
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-xl
                                        bg-slate-950
                                        px-4
                                        py-3
                                        text-sm
                                        font-semibold
                                        text-white
                                        shadow-sm
                                        transition
                                        hover:bg-slate-800
                                        active:scale-[.98]
                                    ">
                                Ver expediente

                                <span>→</span>
                            </a>

                        </article>

            @empty

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
                                bg-slate-100
                                text-slate-400
                            ">
                        —
                    </div>

                    <p class="
                                mt-4
                                text-sm
                                font-semibold
                                text-slate-700
                            ">
                        No se encontraron unidades.
                    </p>

                    <p class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                        Intenta cambiar los filtros.
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