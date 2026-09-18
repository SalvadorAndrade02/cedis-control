<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- PESTAÑAS --}}
    {{-- ========================================================= --}}

    <div class="
            inline-flex
            w-full
            rounded-2xl
            border
            border-slate-200
            bg-white
            p-1.5
            shadow-sm
            sm:w-auto
        ">

        <button type="button" wire:click="selectTab('completed')" class="
                flex-1
                rounded-xl
                px-5
                py-2.5
                text-sm
                font-semibold
                transition
                sm:flex-none

                {{ $tab === 'completed'
    ? 'bg-amber-500 text-white shadow-sm'
    : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'
                }}
            ">
            Finalizados
        </button>


        <button type="button" wire:click="selectTab('in_progress')" class="
                flex-1
                rounded-xl
                px-5
                py-2.5
                text-sm
                font-semibold
                transition
                sm:flex-none

                {{ $tab === 'in_progress'
    ? 'bg-amber-500 text-white shadow-sm'
    : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'
                }}
            ">
            En proceso

            @if ($progressSummary['total'] > 0)

                <span class="
                                                                    ml-1
                                                                    rounded-full
                                                                    bg-white/20
                                                                    px-2
                                                                    py-0.5
                                                                    text-[10px]
                                                                ">
                    {{ $progressSummary['total'] }}
                </span>

            @endif

        </button>

    </div>



    {{-- ========================================================= --}}
    {{-- FINALIZADOS --}}
    {{-- ========================================================= --}}

    @if ($tab === 'completed')

        <div class="space-y-6">

            {{-- FILTROS --}}

            <section class="
                                                                        rounded-3xl
                                                                        border
                                                                        border-slate-200
                                                                        bg-white
                                                                        p-5
                                                                        shadow-sm
                                                                        lg:p-6
                                                                    ">

                <div class="
                                                                            grid
                                                                            gap-4
                                                                            md:grid-cols-2
                                                                            xl:grid-cols-4
                                                                        ">

                    {{-- DESDE --}}

                    <div>

                        <label class="
                                                                                    text-xs
                                                                                    font-semibold
                                                                                    uppercase
                                                                                    tracking-wider
                                                                                    text-slate-500
                                                                                ">
                            Desde
                        </label>

                        <input type="date" wire:model.live="startDate" class="
                                                                                    mt-2
                                                                                    w-full
                                                                                    rounded-xl
                                                                                    border
                                                                                    border-slate-200
                                                                                    bg-slate-50
                                                                                    px-4
                                                                                    py-3
                                                                                    text-sm
                                                                                    outline-none
                                                                                    focus:border-amber-500
                                                                                    focus:bg-white
                                                                                    focus:ring-4
                                                                                    focus:ring-amber-500/10
                                                                                ">

                    </div>


                    {{-- HASTA --}}

                    <div>

                        <label class="
                                                                                    text-xs
                                                                                    font-semibold
                                                                                    uppercase
                                                                                    tracking-wider
                                                                                    text-slate-500
                                                                                ">
                            Hasta
                        </label>

                        <input type="date" wire:model.live="endDate" class="
                                                                                    mt-2
                                                                                    w-full
                                                                                    rounded-xl
                                                                                    border
                                                                                    border-slate-200
                                                                                    bg-slate-50
                                                                                    px-4
                                                                                    py-3
                                                                                    text-sm
                                                                                    outline-none
                                                                                    focus:border-amber-500
                                                                                    focus:bg-white
                                                                                    focus:ring-4
                                                                                    focus:ring-amber-500/10
                                                                                ">

                    </div>


                    {{-- ARMADOR --}}

                    <div>

                        <label class="
                                                                                    text-xs
                                                                                    font-semibold
                                                                                    uppercase
                                                                                    tracking-wider
                                                                                    text-slate-500
                                                                                ">
                            Armador
                        </label>

                        <select wire:model.live="completedWorker" class="
                                                                                    mt-2
                                                                                    w-full
                                                                                    rounded-xl
                                                                                    border
                                                                                    border-slate-200
                                                                                    bg-slate-50
                                                                                    px-4
                                                                                    py-3
                                                                                    text-sm
                                                                                    outline-none
                                                                                    focus:border-amber-500
                                                                                    focus:bg-white
                                                                                    focus:ring-4
                                                                                    focus:ring-amber-500/10
                                                                                ">

                            <option value="">
                                Todos los armadores
                            </option>

                            @foreach ($completedWorkers as $worker)

                                <option value="{{ $worker }}">
                                    {{ $worker }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BÚSQUEDA --}}

                    <div>

                        <label class="
                                                                                    text-xs
                                                                                    font-semibold
                                                                                    uppercase
                                                                                    tracking-wider
                                                                                    text-slate-500
                                                                                ">
                            Buscar
                        </label>

                        <input type="search" wire:model.live.debounce.400ms="completedSearch"
                            placeholder="VIN, marca o modelo..." class="
                                                                                    mt-2
                                                                                    w-full
                                                                                    rounded-xl
                                                                                    border
                                                                                    border-slate-200
                                                                                    bg-slate-50
                                                                                    px-4
                                                                                    py-3
                                                                                    text-sm
                                                                                    outline-none
                                                                                    placeholder:text-slate-400
                                                                                    focus:border-amber-500
                                                                                    focus:bg-white
                                                                                    focus:ring-4
                                                                                    focus:ring-amber-500/10
                                                                                ">

                    </div>

                </div>


                <div class="
                                                                            mt-4
                                                                            flex
                                                                            justify-end
                                                                        ">

                    <button type="button" wire:click="resetCompletedFilters" class="
                                                                                text-xs
                                                                                font-semibold
                                                                                text-slate-500
                                                                                transition
                                                                                hover:text-slate-900
                                                                            ">
                        Limpiar filtros
                    </button>

                </div>

            </section>



            {{-- KPIs --}}

            <div class="
                border-b
                border-slate-100
                px-5
                pb-5
                pt-6
                sm:px-6
                sm:pb-6
                sm:pt-6
                lg:px-7
                lg:pb-6
                lg:pt-7
            ">

                <div class="
                    flex
                    flex-col
                    gap-5
                    xl:flex-row
                    xl:items-center
                    xl:justify-between
                ">

                    {{-- INFORMACIÓN --}}

                    <div>

                        <p class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-[0.14em]
                            text-amber-600
                        ">
                            R001
                        </p>

                        <h2 class="
                            mt-1
                            text-lg
                            font-semibold
                            text-slate-950
                        ">
                            Armados finalizados
                        </h2>

                        <p class="
                            mt-1
                            text-xs
                            text-slate-500
                        ">
                            {{ $completedSummary['total'] }}
                            registro(s) encontrados
                        </p>

                    </div>


                    {{-- EXPORTACIONES --}}

                    <div class="
                        flex
                        w-full
                        flex-col
                        gap-3
                        sm:w-auto
                        sm:flex-row
                        sm:flex-wrap
                        xl:shrink-0
                        xl:pl-8
                    ">

                        {{-- EXCEL --}}

                        <button type="button" wire:click="exportCompletedExcel" wire:loading.attr="disabled"
                            wire:target="exportCompletedExcel" class="
                            inline-flex
                            min-h-11
                            w-full
                            items-center
                            justify-center
                            gap-2
                            rounded-xl
                            border
                            border-emerald-200
                            bg-emerald-50
                            px-5
                            py-2.5
                            text-sm
                            font-semibold
                            text-emerald-700
                            transition
                            hover:border-emerald-300
                            hover:bg-emerald-100
                            disabled:cursor-not-allowed
                            disabled:opacity-50
                            sm:w-auto
                        ">

                            <span wire:loading.remove wire:target="exportCompletedExcel">
                                Exportar Excel
                            </span>

                            <span wire:loading wire:target="exportCompletedExcel">
                                Generando...
                            </span>

                        </button>


                        {{-- PDF --}}

                        <button type="button" wire:click="exportCompletedPdf" wire:loading.attr="disabled"
                            wire:target="exportCompletedPdf" class="
                            inline-flex
                            min-h-11
                            w-full
                            items-center
                            justify-center
                            gap-2
                            rounded-xl
                            border
                            border-red-200
                            bg-red-50
                            px-5
                            py-2.5
                            text-sm
                            font-semibold
                            text-red-700
                            transition
                            hover:border-red-300
                            hover:bg-red-100
                            disabled:cursor-not-allowed
                            disabled:opacity-50
                            sm:w-auto
                        ">

                            <span wire:loading.remove wire:target="exportCompletedPdf">
                                Exportar PDF
                            </span>

                            <span wire:loading wire:target="exportCompletedPdf">
                                Generando...
                            </span>

                        </button>

                    </div>

                </div>

            </div>


            {{-- DESKTOP --}}

            <div class="hidden overflow-x-auto lg:block">

                <table class="w-full">

                    <thead class="
                                                                                    bg-slate-50
                                                                                    text-left
                                                                                    text-[10px]
                                                                                    font-semibold
                                                                                    uppercase
                                                                                    tracking-wider
                                                                                    text-slate-400
                                                                                ">

                        <tr>

                            <th class="px-6 py-4">
                                Armador
                            </th>

                            <th class="px-6 py-4">
                                Finalización
                            </th>

                            <th class="px-6 py-4">
                                Marca / Modelo
                            </th>

                            <th class="px-6 py-4">
                                VIN
                            </th>

                            <th class="px-6 py-4 text-right">
                                Tiempo efectivo
                            </th>

                        </tr>

                    </thead>


                    <tbody class="
                                                                                    divide-y
                                                                                    divide-slate-100
                                                                                ">

                        @forelse ($completedSessions as $session)

                                        <tr
                                            class="
                                                                                                                                                                                                                                                                                                                transition
                                                                                                                                                                                                                                                                                                                hover:bg-slate-50
                                                                                                                                                                                                                                                                                                            ">

                                            <td class="px-6 py-5">

                                                <p
                                                    class="
                                                                                                                                                                                                                                                                                                                        text-sm
                                                                                                                                                                                                                                                                                                                        font-semibold
                                                                                                                                                                                                                                                                                                                        text-slate-900
                                                                                                                                                                                                                                                                                                                    ">
                                                    {{ $session->report_worker_name }}
                                                </p>

                                            </td>


                                            <td class="px-6 py-5">

                                                <p class="text-sm text-slate-700">

                                                    {{ \App\Support\DateHelper::format(
                                $session->completed_at
                            ) }}

                                                </p>

                                            </td>


                                            <td class="px-6 py-5">

                                                <p
                                                    class="
                                                                                                                                                                                                                                                                                                                        text-xs
                                                                                                                                                                                                                                                                                                                        font-semibold
                                                                                                                                                                                                                                                                                                                        text-amber-700
                                                                                                                                                                                                                                                                                                                    ">
                                                    {{ $session->unit?->brand?->name ?? '—' }}
                                                </p>

                                                <p
                                                    class="
                                                                                                                                                                                                                                                                                                                        mt-1
                                                                                                                                                                                                                                                                                                                        text-sm
                                                                                                                                                                                                                                                                                                                        font-medium
                                                                                                                                                                                                                                                                                                                        text-slate-900
                                                                                                                                                                                                                                                                                                                    ">
                                                    {{ $session->unit?->model ?? '—' }}
                                                </p>

                                            </td>


                                            <td class="px-6 py-5">

                                                <span
                                                    class="
                                                                                                                                                                                                                                                                                                                        font-mono
                                                                                                                                                                                                                                                                                                                        text-xs
                                                                                                                                                                                                                                                                                                                        text-slate-600
                                                                                                                                                                                                                                                                                                                    ">
                                                    {{ $session->unit?->vin ?? '—' }}
                                                </span>

                                            </td>


                                            <td
                                                class="
                                                                                                                                                                                                                                                                                                                    px-6
                                                                                                                                                                                                                                                                                                                    py-5
                                                                                                                                                                                                                                                                                                                    text-right
                                                                                                                                                                                                                                                                                                                ">

                                                <span
                                                    class="
                                                                                                                                                                                                                                                                                                                        rounded-full
                                                                                                                                                                                                                                                                                                                        bg-amber-50
                                                                                                                                                                                                                                                                                                                        px-3
                                                                                                                                                                                                                                                                                                                        py-1.5
                                                                                                                                                                                                                                                                                                                        text-xs
                                                                                                                                                                                                                                                                                                                        font-semibold
                                                                                                                                                                                                                                                                                                                        text-amber-700
                                                                                                                                                                                                                                                                                                                    ">
                                                    {{ \App\Support\DurationHelper::format(
                                $session->report_effective_seconds
                            ) }}
                                                </span>

                                            </td>

                                        </tr>


                        @empty

                            <tr>

                                <td colspan="5"
                                    class="
                                                                                                                                            px-6
                                                                                                                                            py-14
                                                                                                                                            text-center
                                                                                                                                            text-sm
                                                                                                                                            text-slate-500
                                                                                                                                        ">
                                    No se encontraron armados
                                    para los filtros seleccionados.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- MOBILE --}}

            <div class="
                                                                            divide-y
                                                                            divide-slate-100
                                                                            lg:hidden
                                                                        ">

                @forelse ($completedSessions as $session)

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
                                                                                                                                                                                                                            text-amber-700
                                                                                                                                                                                                                        ">
                                        {{ $session->unit?->brand?->name ?? '—' }}
                                    </p>

                                    <p
                                        class="
                                                                                                                                                                                                                            mt-1
                                                                                                                                                                                                                            font-semibold
                                                                                                                                                                                                                            text-slate-950
                                                                                                                                                                                                                        ">
                                        {{ $session->unit?->model ?? '—' }}
                                    </p>

                                </div>


                                <span
                                    class="
                                                                                                                                                                                                                        shrink-0
                                                                                                                                                                                                                        rounded-full
                                                                                                                                                                                                                        bg-amber-50
                                                                                                                                                                                                                        px-3
                                                                                                                                                                                                                        py-1
                                                                                                                                                                                                                        text-xs
                                                                                                                                                                                                                        font-semibold
                                                                                                                                                                                                                        text-amber-700
                                                                                                                                                                                                                    ">
                                    {{ \App\Support\DurationHelper::format(
                        $session->report_effective_seconds
                    ) }}
                                </span>

                            </div>


                            <p
                                class="
                                                                                                                                                                                                                    mt-3
                                                                                                                                                                                                                    break-all
                                                                                                                                                                                                                    font-mono
                                                                                                                                                                                                                    text-xs
                                                                                                                                                                                                                    text-slate-500
                                                                                                                                                                                                                ">
                                {{ $session->unit?->vin ?? '—' }}
                            </p>


                            <div
                                class="
                                                                                                                                                                                                                    mt-4
                                                                                                                                                                                                                    grid
                                                                                                                                                                                                                    grid-cols-2
                                                                                                                                                                                                                    gap-3
                                                                                                                                                                                                                    text-xs
                                                                                                                                                                                                                ">

                                <div>

                                    <p class="text-slate-400">
                                        Armador
                                    </p>

                                    <p
                                        class="
                                                                                                                                                                                                                            mt-1
                                                                                                                                                                                                                            font-semibold
                                                                                                                                                                                                                            text-slate-700
                                                                                                                                                                                                                        ">
                                        {{ $session->report_worker_name }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-slate-400">
                                        Finalización
                                    </p>

                                    <p
                                        class="
                                                                                                                                                                                                                            mt-1
                                                                                                                                                                                                                            font-semibold
                                                                                                                                                                                                                            text-slate-700
                                                                                                                                                                                                                        ">
                                        {{ \App\Support\DateHelper::format(
                        $session->completed_at
                    ) }}
                                    </p>

                                </div>

                            </div>

                        </article>


                @empty

                    <div class="
                                                                                                                                px-6
                                                                                                                                py-14
                                                                                                                                text-center
                                                                                                                                text-sm
                                                                                                                                text-slate-500
                                                                                                                            ">
                        No se encontraron armados.
                    </div>

                @endforelse

            </div>


            @if ($completedSessions->hasPages())

                <div class="
                                                                                                                            border-t
                                                                                                                            border-slate-100
                                                                                                                            px-6
                                                                                                                            py-4
                                                                                                                        ">
                    {{ $completedSessions->links() }}
                </div>

            @endif

            </section>

        </div>



        {{-- ========================================================= --}}
        {{-- EN PROCESO --}}
        {{-- ========================================================= --}}

    @else

        <div class="space-y-6" wire:poll.15s>

            {{-- FILTROS --}}

            <section class="
                                                                rounded-3xl
                                                                border
                                                                border-slate-200
                                                                bg-white
                                                                p-5
                                                                shadow-sm
                                                            ">

                <div class="
                                                                    grid
                                                                    gap-4
                                                                    md:grid-cols-3
                                                                ">

                    <select wire:model.live="progressStatus" class="
                                                                        rounded-xl
                                                                        border
                                                                        border-slate-200
                                                                        bg-slate-50
                                                                        px-4
                                                                        py-3
                                                                        text-sm
                                                                    ">

                        <option value="">
                            Todos los estados
                        </option>

                        <option value="RUNNING">
                            En armado
                        </option>

                        <option value="PAUSED">
                            Pausados
                        </option>

                    </select>


                    <select wire:model.live="progressWorker" class="
                                                                        rounded-xl
                                                                        border
                                                                        border-slate-200
                                                                        bg-slate-50
                                                                        px-4
                                                                        py-3
                                                                        text-sm
                                                                    ">

                        <option value="">
                            Todos los armadores
                        </option>

                        @foreach ($progressWorkers as $worker)

                            <option value="{{ $worker }}">
                                {{ $worker }}
                            </option>

                        @endforeach

                    </select>


                    <input type="search" wire:model.live.debounce.400ms="progressSearch"
                        placeholder="VIN, marca o modelo..." class="
                                                                        rounded-xl
                                                                        border
                                                                        border-slate-200
                                                                        bg-slate-50
                                                                        px-4
                                                                        py-3
                                                                        text-sm
                                                                    ">

                </div>

            </section>



            {{-- KPIs --}}

            <div class="
                                                                grid
                                                                gap-4
                                                                sm:grid-cols-3
                                                            ">

                <article class="rounded-3xl border border-blue-100 bg-white p-5">

                    <p class="text-sm text-slate-500">
                        En armado
                    </p>

                    <p class="mt-3 text-3xl font-bold text-blue-700">
                        {{ $progressSummary['running'] }}
                    </p>

                </article>


                <article class="rounded-3xl border border-amber-100 bg-white p-5">

                    <p class="text-sm text-slate-500">
                        Pausados
                    </p>

                    <p class="mt-3 text-3xl font-bold text-amber-700">
                        {{ $progressSummary['paused'] }}
                    </p>

                </article>


                <article class="rounded-3xl border border-slate-200 bg-white p-5">

                    <p class="text-sm text-slate-500">
                        Total en proceso
                    </p>

                    <p class="mt-3 text-3xl font-bold text-slate-950">
                        {{ $progressSummary['total'] }}
                    </p>

                </article>

            </div>



            {{-- LISTADO --}}

            <section class="
                                                                overflow-hidden
                                                                rounded-3xl
                                                                border
                                                                border-slate-200
                                                                bg-white
                                                                shadow-sm
                                                            ">

                <div class="
                                            flex
                                            flex-col
                                            gap-5
                                            border-b
                                            border-slate-100
                                            px-5
                                            py-5
                                            sm:px-6
                                            lg:px-7
                                            lg:py-6
                                            xl:flex-row
                                            xl:items-center
                                            xl:justify-between
                                        ">

                    <div>

                        <p class="
                                                    text-xs
                                                    font-semibold
                                                    uppercase
                                                    tracking-wider
                                                    text-blue-600
                                                ">
                            R002
                        </p>

                        <h2 class="
                                                    mt-1
                                                    text-lg
                                                    font-semibold
                                                    text-slate-950
                                                ">
                            Armados en proceso
                        </h2>

                        <p class="
                                                    mt-1
                                                    text-xs
                                                    text-slate-500
                                                ">
                            {{ $progressSessions->total() }}
                            registro(s) encontrados
                        </p>

                    </div>


                    <div class="
            flex
            w-full
            flex-col
            gap-3
            sm:w-auto
            sm:flex-row
            sm:flex-wrap
            xl:shrink-0
            xl:pl-8
        ">

                        {{-- EXCEL --}}

                        <button type="button" wire:click="exportProgressExcel" wire:loading.attr="disabled"
                            wire:target="exportProgressExcel" class="
                inline-flex
                min-h-11
                w-full
                items-center
                justify-center
                gap-2
                rounded-xl
                border
                border-emerald-200
                bg-emerald-50
                px-5
                py-2.5
                text-sm
                font-semibold
                text-emerald-700
                transition
                hover:border-emerald-300
                hover:bg-emerald-100
                disabled:cursor-not-allowed
                disabled:opacity-50
                sm:w-auto
            ">

                            <span wire:loading.remove wire:target="exportProgressExcel">
                                Exportar Excel
                            </span>

                            <span wire:loading wire:target="exportProgressExcel">
                                Generando...
                            </span>

                        </button>



                        {{-- PDF --}}

                        <button type="button" wire:click="exportProgressPdf" wire:loading.attr="disabled"
                            wire:target="exportProgressPdf" class="
                inline-flex
                min-h-11
                w-full
                items-center
                justify-center
                gap-2
                rounded-xl
                border
                border-red-200
                bg-red-50
                px-5
                py-2.5
                text-sm
                font-semibold
                text-red-700
                transition
                hover:border-red-300
                hover:bg-red-100
                disabled:cursor-not-allowed
                disabled:opacity-50
                sm:w-auto
            ">

                            <span wire:loading.remove wire:target="exportProgressPdf">
                                Exportar PDF
                            </span>

                            <span wire:loading wire:target="exportProgressPdf">
                                Generando...
                            </span>

                        </button>

                    </div>
                </div>


                <div class="
                                                                    divide-y
                                                                    divide-slate-100
                                                                ">

                    @forelse ($progressSessions as $session)

                                <article
                                    class="
                                                                                                                                                                                                                p-5
                                                                                                                                                                                                                lg:p-6
                                                                                                                                                                                                            ">

                                    <div
                                        class="
                                                                                                                                                                                                                    flex
                                                                                                                                                                                                                    flex-col
                                                                                                                                                                                                                    gap-5
                                                                                                                                                                                                                    lg:flex-row
                                                                                                                                                                                                                    lg:items-center
                                                                                                                                                                                                                    lg:justify-between
                                                                                                                                                                                                                ">

                                        <div>

                                            <div
                                                class="
                                                                                                                                                                                                                            flex
                                                                                                                                                                                                                            flex-wrap
                                                                                                                                                                                                                            items-center
                                                                                                                                                                                                                            gap-2
                                                                                                                                                                                                                        ">

                                                <span
                                                    class="
                                                                                                                                                                                                                                rounded-full
                                                                                                                                                                                                                                px-3
                                                                                                                                                                                                                                py-1
                                                                                                                                                                                                                                text-xs
                                                                                                                                                                                                                                font-semibold
                                                                                                                                                                                                                                {{ $session->status->badgeClasses() }}
                                                                                                                                                                                                                            ">
                                                    {{ $session->status->label() }}
                                                </span>

                                                <span
                                                    class="
                                                                                                                                                                                                                                text-xs
                                                                                                                                                                                                                                font-semibold
                                                                                                                                                                                                                                text-slate-600
                                                                                                                                                                                                                            ">
                                                    {{ $session->report_worker_name }}
                                                </span>

                                            </div>


                                            <p
                                                class="
                                                                                                                                                                                                                            mt-3
                                                                                                                                                                                                                            font-semibold
                                                                                                                                                                                                                            text-slate-950
                                                                                                                                                                                                                        ">
                                                {{ $session->unit?->brand?->name }}
                                                ·
                                                {{ $session->unit?->model }}
                                            </p>


                                            <p
                                                class="
                                                                                                                                                                                                                            mt-1
                                                                                                                                                                                                                            font-mono
                                                                                                                                                                                                                            text-xs
                                                                                                                                                                                                                            text-slate-500
                                                                                                                                                                                                                        ">
                                                {{ $session->unit?->vin }}
                                            </p>


                                            @if ($session->report_current_pause)

                                                            <p
                                                                class="
                                                                                                                                                                                                                                                                                                                                                                                                                mt-3
                                                                                                                                                                                                                                                                                                                                                                                                                text-xs
                                                                                                                                                                                                                                                                                                                                                                                                                text-amber-700
                                                                                                                                                                                                                                                                                                                                                                                                            ">
                                                                Pausa:
                                                                {{ $session
                                                ->report_current_pause
                                                ->reason
                                                ->label()
                                                                                                                                                                                                                                                                                                                                                                                                            }}
                                                            </p>

                                            @endif

                                        </div>


                                        <div
                                            class="
                                                                                                                                                                                                                        shrink-0
                                                                                                                                                                                                                        text-left
                                                                                                                                                                                                                        lg:text-right
                                                                                                                                                                                                                    ">

                                            <p
                                                class="
                                                                                                                                                                                                                            text-xs
                                                                                                                                                                                                                            uppercase
                                                                                                                                                                                                                            tracking-wider
                                                                                                                                                                                                                            text-slate-400
                                                                                                                                                                                                                        ">
                                                Tiempo efectivo
                                            </p>

                                            <p
                                                class="
                                                                                                                                                                                                                            mt-1
                                                                                                                                                                                                                            font-mono
                                                                                                                                                                                                                            text-2xl
                                                                                                                                                                                                                            font-bold
                                                                                                                                                                                                                            text-slate-950
                                                                                                                                                                                                                        ">
                                                {{ \App\Support\DurationHelper::format(
                            $session->report_effective_seconds
                        ) }}
                                            </p>


                                            <p
                                                class="
                                                                                                                                                                                                                            mt-2
                                                                                                                                                                                                                            text-xs
                                                                                                                                                                                                                            text-slate-500
                                                                                                                                                                                                                        ">
                                                Inicio:
                                                {{ \App\Support\DateHelper::format(
                            $session->started_at
                        ) }}
                                            </p>

                                        </div>

                                    </div>

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
                                                                                                                            h-12
                                                                                                                            w-12
                                                                                                                            items-center
                                                                                                                            justify-center
                                                                                                                            rounded-2xl
                                                                                                                            bg-emerald-50
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
                                No hay unidades actualmente en armado
                            </p>

                            <p class="
                                                                                                                            mt-1
                                                                                                                            text-sm
                                                                                                                            text-slate-500
                                                                                                                        ">
                                Las nuevas sesiones aparecerán aquí
                                automáticamente.
                            </p>

                        </div>

                    @endforelse

                </div>


                @if ($progressSessions->hasPages())

                    <div class="border-t border-slate-100 px-6 py-4">
                        {{ $progressSessions->links() }}
                    </div>

                @endif

            </section>

        </div>

    @endif

</div>