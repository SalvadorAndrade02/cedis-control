<div class="space-y-6">

    {{-- ============================================================
    RESUMEN
    ============================================================ --}}

    <div class="grid gap-4 sm:grid-cols-2">

        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Traslados activos
            </p>

            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                {{ $totalAssigned }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Unidades actualmente asignadas.
            </p>
        </article>


        <article class="rounded-2xl border border-amber-100 bg-amber-50/50 p-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">
                Pendientes de combustible
            </p>

            <p class="mt-2 text-3xl font-bold tracking-tight text-amber-950">
                {{ $pendingFuel }}
            </p>

            <p class="mt-1 text-sm text-amber-700">
                Sin registro de gasolina.
            </p>
        </article>

    </div>



    {{-- ============================================================
    BUSCADOR
    ============================================================ --}}

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

        <label for="transfer-search" class="mb-2 block text-sm font-semibold text-slate-700">
            Buscar traslado
        </label>

        <input id="transfer-search" type="search" wire:model.live.debounce.400ms="search"
            placeholder="VIN, marca o modelo..."
            class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500">

    </section>



    {{-- ============================================================
    TRASLADOS
    ============================================================ --}}

    <div class="grid gap-5 xl:grid-cols-2">

        @forelse ($assignments as $assignment)

                @php

                    $unit =
                        $assignment->unit;

                    $hasFuel =
                        $assignment
                            ->fuelLoads
                            ->isNotEmpty();

                @endphp


                <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                    {{-- HEADER --}}

                    <div class="border-b border-slate-100 px-5 py-5 sm:px-6">

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0">

                                <div class="flex flex-wrap items-center gap-2">

                                    <span class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-semibold text-teal-700">
                                        Traslado activo
                                    </span>


                                    @if ($hasFuel)

                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            Combustible registrado
                                        </span>

                                    @else

                                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                            Combustible pendiente
                                        </span>

                                    @endif

                                </div>


                                <h2 class="mt-3 truncate text-lg font-semibold text-slate-950">
                                    {{ $unit->brand?->name ?? 'Unidad' }}

                                    @if ($unit->model)
                                        · {{ $unit->model }}
                                    @endif
                                </h2>


                                <p class="mt-1 break-all font-mono text-sm font-medium text-slate-500">
                                    {{ $unit->vin }}
                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- DATOS --}}

                    <div class="grid gap-5 px-5 py-5 sm:grid-cols-2 sm:px-6">

                        <div>

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Origen
                            </p>

                            <p class="mt-1 text-sm font-semibold text-slate-900">
                                {{ $assignment->origin_name ?: 'CEDIS' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Destino
                            </p>

                            <p class="mt-1 text-sm font-semibold text-slate-900">
                                {{ $assignment->destination_name ?: 'No especificado' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Fecha de asignación
                            </p>

                            <p class="mt-1 text-sm font-semibold text-slate-900">
                                {{ \App\Support\DateHelper::format(
                $assignment->assigned_at
            ) }}
                            </p>

                        </div>


                        <div>

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Estado
                            </p>

                            <p class="mt-1 text-sm font-semibold text-teal-700">
                                {{ $assignment->status->label() }}
                            </p>

                        </div>

                    </div>



                    {{-- ACCIONES --}}

                    <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:px-6">

                        <button type="button" wire:click="showDetails({{ $assignment->id }})"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Ver detalle
                        </button>


                        <button type="button" x-data x-on:click="
                                    navigator.clipboard.writeText(
                                        @js($unit->vin)
                                    )
                                "
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Copiar VIN
                        </button>


                        {{-- Aparecerá automáticamente cuando creemos
                        el formulario de combustible. --}}

                        @if (
    ! $hasFuel
    && Route::has('fuel.create')
    && auth()->user()?->can('fuel.create')
)

    <a
        href="{{ route(
            'fuel.create',
            $assignment
        ) }}"
        class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700"
    >
        Registrar gasolina
    </a>

@elseif ($hasFuel)

    <div
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700"
    >
        <span>
            ✓
        </span>

        Combustible registrado
    </div>

@endif

                    </div>

                </article>

        @empty

            <div class="xl:col-span-2 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">

                <p class="font-semibold text-slate-800">
                    No tienes traslados asignados
                </p>

                <p class="mt-2 text-sm text-slate-500">
                    Las unidades asignadas aparecerán aquí automáticamente.
                </p>

            </div>

        @endforelse

    </div>



    {{-- PAGINACIÓN --}}

    @if ($assignments->hasPages())

        <div>
            {{ $assignments->links() }}
        </div>

    @endif



    {{-- ============================================================
    MODAL DETALLE
    ============================================================ --}}

    @if ($selectedAssignment)

        <div class="fixed inset-0 z-[100] flex items-end justify-center sm:items-center sm:p-4" x-data
            x-on:keydown.escape.window="$wire.closeDetails()">

            <button type="button" wire:click="closeDetails" class="absolute inset-0 h-full w-full bg-slate-950/60"></button>


            <div
                class="relative z-10 max-h-[90dvh] w-full overflow-y-auto rounded-t-3xl bg-white shadow-2xl sm:max-w-xl sm:rounded-2xl">

                <div class="flex items-start justify-between border-b border-slate-200 px-5 py-5 sm:px-6">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-teal-600">
                            Traslado activo
                        </p>

                        <h3 class="mt-1 text-lg font-semibold text-slate-950">
                            {{ $selectedAssignment->unit->brand?->name }}

                            @if ($selectedAssignment->unit->model)
                                · {{ $selectedAssignment->unit->model }}
                            @endif
                        </h3>

                        <p class="mt-1 font-mono text-sm text-slate-500">
                            {{ $selectedAssignment->unit->vin }}
                        </p>

                    </div>


                    <button type="button" wire:click="closeDetails"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100">
                        ✕
                    </button>

                </div>


                <div class="space-y-5 px-5 py-5 sm:px-6">

                    <div class="grid gap-4 sm:grid-cols-2">

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase text-slate-400">
                                Origen
                            </p>

                            <p class="mt-1 font-semibold text-slate-900">
                                {{ $selectedAssignment->origin_name ?: 'CEDIS' }}
                            </p>
                        </div>


                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase text-slate-400">
                                Destino
                            </p>

                            <p class="mt-1 font-semibold text-slate-900">
                                {{ $selectedAssignment->destination_name ?: 'No especificado' }}
                            </p>
                        </div>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Asignado
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ \App\Support\DateHelper::format(
            $selectedAssignment->assigned_at
        ) }}
                        </p>

                    </div>


                    @if ($selectedAssignment->notes)

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Indicaciones
                            </p>

                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">
                                {{ $selectedAssignment->notes }}
                            </p>

                        </div>

                    @endif

                </div>


                <div class="border-t border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">

                    <button type="button" wire:click="closeDetails"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700">
                        Cerrar
                    </button>

                </div>

            </div>

        </div>

    @endif

</div>