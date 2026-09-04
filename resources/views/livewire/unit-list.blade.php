<div class="space-y-6">

    {{-- FILTROS --}}
    <section
        class="
            rounded-2xl
            border
            border-slate-200
            bg-white
            p-5
            shadow-sm
        ">

        <div
            class="
                grid
                gap-4
                md:grid-cols-[1fr_260px_auto]
            ">

            <div>
                <label
                    class="
                        text-sm
                        font-medium
                        text-slate-700
                    ">
                    Buscar unidad
                </label>

                <input
                    type="search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="VIN, marca o modelo..."
                    class="
                        mt-2
                        w-full
                        rounded-xl
                        border
                        border-slate-300
                        px-4
                        py-3
                        text-sm
                        outline-none
                        transition
                        focus:border-blue-500
                        focus:ring-4
                        focus:ring-blue-500/10
                    ">
            </div>


            <div>
                <label
                    class="
                        text-sm
                        font-medium
                        text-slate-700
                    ">
                    Estado
                </label>

                <select
                    wire:model.live="status"
                    class="
                        mt-2
                        w-full
                        rounded-xl
                        border
                        border-slate-300
                        bg-white
                        px-4
                        py-3
                        text-sm
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


            <div
                class="
                    flex
                    items-end
                ">
                <button
                    type="button"
                    wire:click="clearFilters"
                    class="
                        w-full
                        rounded-xl
                        border
                        border-slate-300
                        px-4
                        py-3
                        text-sm
                        font-medium
                        text-slate-600
                        hover:bg-slate-50
                        md:w-auto
                    ">
                    Limpiar
                </button>
            </div>

        </div>

    </section>


    {{-- TABLA --}}
    <section
        class="
            overflow-hidden
            rounded-2xl
            border
            border-slate-200
            bg-white
            shadow-sm
        ">

        <div
            class="
                flex
                items-center
                justify-between
                border-b
                border-slate-200
                px-6
                py-5
            ">
            <div>
                <h2 class="font-semibold text-slate-950">
                    Unidades registradas
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $units->total() }}
                    unidad(es) encontrada(s)
                </p>
            </div>

            <a
                href="{{ route('imports.index') }}"
                class="
                    rounded-xl
                    bg-slate-950
                    px-4
                    py-2.5
                    text-sm
                    font-semibold
                    text-white
                    hover:bg-slate-800
                ">
                + Importar
            </a>
        </div>


        {{-- DESKTOP --}}
        <div class="hidden overflow-x-auto md:block">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr
                        class="
                            text-left
                            text-xs
                            font-semibold
                            uppercase
                            tracking-wider
                            text-slate-500
                        ">
                        <th class="px-6 py-4">
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

                        <th class="px-6 py-4 text-right">
                            Acción
                        </th>
                    </tr>

                </thead>

                <tbody
                    class="
                        divide-y
                        divide-slate-100
                    ">

                    @forelse ($units as $unit)

                    <tr class="hover:bg-slate-50">

                        <td class="px-6 py-4">

                            <p
                                class="
                                        font-medium
                                        text-slate-950
                                    ">
                                {{ $unit->model ?: 'Sin modelo' }}
                            </p>

                            <p
                                class="
                                        mt-1
                                        text-xs
                                        text-slate-500
                                    ">
                                {{ $unit->brand?->name ?? 'Sin marca' }}

                                @if ($unit->exterior_color)
                                · {{ $unit->exterior_color }}
                                @endif
                            </p>

                        </td>

                        <td
                            class="
                                    px-6
                                    py-4
                                    font-mono
                                    text-sm
                                    text-slate-700
                                ">
                            {{ $unit->vin }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            {{ $unit->year ?? '—' }}
                        </td>

                        <td class="px-6 py-4">

                            <span
                                class="
                                inline-flex
                                rounded-full
                                px-3
                                py-1
                                text-xs
                                font-semibold
                                {{ $unit->status->badgeClasses() }}
                            ">
                                {{ $unit->status->label() }}
                            </span>

                        </td>

                        <td
                            class="
                                    px-6
                                    py-4
                                    text-sm
                                    text-slate-500
                                ">
                            {{ $unit->created_at
                                ->timezone(config('cedis.timezone'))
                                ->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-6 py-4 text-right">

                            <a
                                href="{{ route(
                                        'units.show',
                                        $unit
                                    ) }}"
                                class="
                                        inline-flex
                                        rounded-lg
                                        border
                                        border-slate-200
                                        px-3
                                        py-2
                                        text-xs
                                        font-semibold
                                        text-slate-700
                                        transition
                                        hover:bg-slate-100
                                    ">
                                Ver expediente
                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td
                            colspan="6"
                            class="
                                    px-6
                                    py-16
                                    text-center
                                    text-sm
                                    text-slate-500
                                ">
                            No se encontraron unidades.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MOBILE --}}
        {{-- MOBILE --}}
        <div
            class="
        divide-y
        divide-slate-100
        md:hidden
    ">

            @forelse ($units as $unit)

            <article class="p-5">

                <div
                    class="
                    flex
                    items-start
                    justify-between
                    gap-4
                ">

                    <div class="min-w-0">

                        <p
                            class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-wider
                            text-blue-600
                        ">
                            {{ $unit->brand?->name ?? 'Sin marca' }}
                        </p>

                        <h3
                            class="
                            mt-1
                            truncate
                            text-lg
                            font-semibold
                        ">
                            {{ $unit->model ?: 'Sin modelo' }}
                        </h3>

                    </div>

                    <span
                        class="
                        shrink-0
                        rounded-full
                        px-2.5
                        py-1
                        text-[11px]
                        font-semibold
                        {{ $unit->status->badgeClasses() }}
                    ">
                        {{ $unit->status->label() }}
                    </span>

                </div>


                <dl
                    class="
                    mt-5
                    grid
                    grid-cols-2
                    gap-4
                    text-sm
                ">

                    <div class="col-span-2">

                        <dt class="text-xs text-slate-500">
                            VIN
                        </dt>

                        <dd
                            class="
                            mt-1
                            break-all
                            font-mono
                            text-slate-900
                        ">
                            {{ $unit->vin }}
                        </dd>

                    </div>


                    <div>

                        <dt class="text-xs text-slate-500">
                            Año
                        </dt>

                        <dd class="mt-1 font-medium">
                            {{ $unit->year ?? '—' }}
                        </dd>

                    </div>


                    <div>

                        <dt class="text-xs text-slate-500">
                            Color
                        </dt>

                        <dd class="mt-1 font-medium">
                            {{ $unit->exterior_color ?? '—' }}
                        </dd>

                    </div>


                    <div class="col-span-2">

                        <dt class="text-xs text-slate-500">
                            Registro
                        </dt>

                        <dd class="mt-1 font-medium">
                            {{ \App\Support\DateHelper::format(
                            $unit->created_at
                        ) }}
                        </dd>

                    </div>

                </dl>


                <a
                    href="{{ route('units.show', $unit) }}"
                    class="
                    mt-5
                    flex
                    w-full
                    items-center
                    justify-center
                    rounded-xl
                    bg-slate-950
                    px-4
                    py-3
                    text-sm
                    font-semibold
                    text-white
                    transition
                    hover:bg-slate-800
                ">
                    Ver expediente
                </a>

            </article>

            @empty

            <div
                class="
                p-12
                text-center
                text-sm
                text-slate-500
            ">
                No se encontraron unidades.
            </div>

            @endforelse

        </div>


        @if ($units->hasPages())

        <div
            class="
                    border-t
                    border-slate-200
                    px-6
                    py-4
                ">
            {{ $units->links() }}
        </div>

        @endif

    </section>

</div>