<div>
    {{-- ============================================================
    TARJETA DE TRASLADO
    ============================================================ --}}

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        {{-- HEADER --}}
        <div
            class="flex flex-col gap-4 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <div class="flex items-center gap-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-teal-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13h2l2-5h9l3 5h2v5h-2a2 2 0 0 1-4 0H9a2 2 0 0 1-4 0H3v-5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8V5h6v3" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-base font-semibold text-slate-900">
                            Traslado
                        </h2>

                        <p class="mt-0.5 text-sm text-slate-500">
                            Asignación del responsable de traslado de la unidad.
                        </p>
                    </div>
                </div>
            </div>


            {{-- BOTÓN --}}
            @can('transfers.assign')
                @if (
                        $unit->status
                        === \App\Enums\UnitStatus::DELIVERY_PENDING
                    )
                    <button type="button" wire:click="openModal" wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:opacity-60">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                        </svg>

                        @if ($activeAssignment)
                            Cambiar trasladista
                        @else
                            Asignar trasladista
                        @endif
                    </button>
                @endif
            @endcan
        </div>


        {{-- MENSAJE DE ÉXITO --}}
        @if ($successMessage)
            <div
                class="mx-5 mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 sm:mx-6">
                {{ $successMessage }}
            </div>
        @endif


        {{-- ERROR GENERAL --}}
        @error('unit')
            <div class="mx-5 mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 sm:mx-6">
                {{ $message }}
            </div>
        @enderror


        {{-- ========================================================
        ASIGNACIÓN ACTIVA
        ======================================================== --}}

        <div class="px-5 py-5 sm:px-6">
            @if ($activeAssignment)

                        <div class="rounded-2xl border border-teal-200 bg-teal-50/50 p-4 sm:p-5">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <div
                                        class="mb-3 inline-flex items-center gap-2 rounded-full bg-teal-100 px-3 py-1 text-xs font-semibold text-teal-700">
                                        <span class="h-2 w-2 rounded-full bg-teal-500"></span>

                                        Asignado
                                    </div>

                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Trasladista
                                    </p>

                                    <p class="mt-1 text-lg font-semibold text-slate-900">
                                        {{ $activeAssignment->transporter_name }}
                                    </p>

                                    @if (
                                            $activeAssignment->transporter?->email
                                        )
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $activeAssignment->transporter->email }}
                                        </p>
                                    @endif
                                </div>


                                <div class="grid gap-3 text-sm sm:min-w-[250px]">
                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                            Asignado
                                        </p>

                                        <p class="mt-1 font-medium text-slate-800">
                                            {{ \App\Support\DateHelper::format(
                    $activeAssignment->assigned_at
                ) }}

                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                            Asignado por
                                        </p>

                                        <p class="mt-1 font-medium text-slate-800">
                                            {{ $activeAssignment->assigned_by_name }}
                                        </p>

                                        <div class="mt-4 grid gap-4 border-t border-teal-200 pt-4 sm:grid-cols-2">

                                            <div>
                                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                                    Origen
                                                </p>

                                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                                    {{ $activeAssignment->origin_name ?: 'CEDIS' }}
                                                </p>
                                            </div>


                                            <div>
                                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                                    Destino
                                                </p>

                                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                                    {{ $activeAssignment->destination_name ?: '—' }}
                                                </p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            @if ($activeAssignment->notes)
                                <div class="mt-4 border-t border-teal-200 pt-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Observaciones
                                    </p>

                                    <p class="mt-1 whitespace-pre-line text-sm text-slate-700">
                                        {{ $activeAssignment->notes }}
                                    </p>
                                </div>
                            @endif
                        </div>

            @else

                <div
                    class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />

                            <circle cx="9" cy="7" r="4" />

                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 8v6M16 11h6" />
                        </svg>
                    </div>

                    <p class="mt-3 font-semibold text-slate-800">
                        Sin trasladista asignado
                    </p>

                    <p class="mt-1 max-w-md text-sm text-slate-500">
                        Esta unidad todavía no tiene un responsable de traslado asignado.
                    </p>

                    @if (
                            $unit->status
                            !== \App\Enums\UnitStatus::DELIVERY_PENDING
                        )
                        <p class="mt-3 text-xs font-medium text-amber-700">
                            La asignación estará disponible cuando la unidad se encuentre pendiente de entrega.
                        </p>
                    @endif
                </div>

            @endif
        </div>
    </section>



    {{-- ============================================================
    MODAL
    ============================================================ --}}

    @if ($showModal)

        <div class="fixed inset-0 z-[100] flex items-end justify-center sm:items-center sm:p-4" x-data
            x-on:keydown.escape.window="$wire.closeModal()">

            {{-- BACKDROP --}}
            <button type="button" aria-label="Cerrar modal" wire:click="closeModal"
                class="absolute inset-0 h-full w-full bg-slate-950/60 backdrop-blur-[2px]"></button>


            {{-- CONTENIDO --}}
            <div role="dialog" aria-modal="true" aria-labelledby="transfer-modal-title"
                class="relative z-10 max-h-[92dvh] w-full overflow-y-auto rounded-t-3xl bg-white shadow-2xl sm:max-w-xl sm:rounded-2xl"
                wire:click.stop>

                {{-- HEADER --}}
                <div class="flex items-start justify-between border-b border-slate-200 px-5 py-5 sm:px-6">
                    <div class="pr-4">
                        <h3 id="transfer-modal-title" class="text-lg font-semibold text-slate-900">
                            @if ($isReassignment)
                                Reasignar trasladista
                            @else
                                Asignar trasladista
                            @endif
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            @if ($isReassignment)
                                Selecciona al nuevo responsable. Los datos del traslado se conservarán.
                            @else
                                Selecciona quién será responsable del traslado de esta unidad.
                            @endif
                        </p>
                    </div>

                    <button type="button" wire:click="closeModal"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                        <span class="sr-only">
                            Cerrar
                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>


                {{-- BODY --}}
                <div class="space-y-5 px-5 py-5 sm:px-6">

                    {{-- UNIDAD --}}
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            Unidad
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $unit->brand?->name ?? 'Sin marca' }}

                            @if ($unit->model)
                                · {{ $unit->model }}
                            @endif
                        </p>

                        <p class="mt-1 font-mono text-sm text-slate-600">
                            {{ $unit->vin }}
                        </p>
                    </div>


                    {{-- TRASLADISTA --}}
                    <div>
                        <label for="transporterId" class="mb-2 block text-sm font-semibold text-slate-700">
                            Trasladista
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="transporterId" wire:model="transporterId"
                            class="block w-full rounded-xl border-slate-300 bg-white text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="">
                                Selecciona un trasladista
                            </option>

                            @foreach ($transporters as $transporter)
                                <option value="{{ $transporter->id }}">
                                    {{ $transporter->name }}
                                    @if ($transporter->email)
                                        — {{ $transporter->email }}
                                    @endif

                                    @if (
                                            $activeAssignment
                                            && $activeAssignment->transporter_user_id
                                            === $transporter->id
                                        )
                                        — Actual
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        @error('transporterId')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        @if ($transporters->isEmpty())
                            <div class="mt-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                                No existen usuarios activos con rol TRASLADISTA.
                                Primero debes crear o activar uno desde Administración de usuarios.
                            </div>
                        @endif
                    </div>

                    @if (!$isReassignment)

                        {{-- ========================================================
                        PRIMERA ASIGNACIÓN
                        ======================================================== --}}

                        <div class="grid gap-4 sm:grid-cols-2">

                            {{-- ORIGEN --}}
                            <div>
                                <label for="originName" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Origen
                                    <span class="text-red-500">*</span>
                                </label>

                                <input id="originName" type="text" wire:model="originName" maxlength="150" placeholder="CEDIS"
                                    class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500">

                                @error('originName')
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>


                            {{-- DESTINO --}}
                            <div>
                                <label for="destinationName" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Destino
                                    <span class="text-red-500">*</span>
                                </label>

                                <input id="destinationName" type="text" wire:model="destinationName" maxlength="255"
                                    placeholder="Ej. Agencia Polaris Xalapa"
                                    class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500">

                                @error('destinationName')
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>


                        {{-- OBSERVACIONES --}}
                        <div>

                            <div class="mb-2 flex items-center justify-between gap-3">

                                <label for="transfer-notes" class="block text-sm font-semibold text-slate-700">
                                    Observaciones
                                </label>

                                <span class="text-xs text-slate-400">
                                    Opcional
                                </span>

                            </div>

                            <textarea id="transfer-notes" wire:model="notes" rows="4" maxlength="1000"
                                placeholder="Ej. Entregar primero en agencia..."
                                class="block w-full resize-none rounded-xl border-slate-300 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-teal-500 focus:ring-teal-500"></textarea>

                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    @else

                        {{-- ========================================================
                        REASIGNACIÓN
                        ======================================================== --}}

                        <div class="rounded-2xl border border-teal-100 bg-teal-50/40 p-4">

                            <div class="flex items-start gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 7h11m0 0-3-3m3 3-3 3M17 17H6m0 0 3 3m-3-3 3-3" />
                                    </svg>
                                </div>


                                <div>
                                    <p class="text-sm font-semibold text-slate-900">
                                        Se conservarán los datos del traslado
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        Sólo cambiará el trasladista responsable.
                                        El origen, destino y las observaciones permanecerán iguales.
                                    </p>
                                </div>

                            </div>


                            <dl class="mt-4 grid gap-4 border-t border-teal-100 pt-4 sm:grid-cols-2">

                                {{-- ORIGEN --}}
                                <div>

                                    <dt class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                        Origen
                                    </dt>

                                    <dd class="mt-1 text-sm font-semibold text-slate-900">
                                        {{ $originName ?: 'CEDIS' }}
                                    </dd>

                                </div>


                                {{-- DESTINO --}}
                                <div>

                                    <dt class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                        Destino
                                    </dt>

                                    <dd class="mt-1 text-sm font-semibold text-slate-900">
                                        {{ $destinationName ?: '—' }}
                                    </dd>

                                </div>


                                {{-- OBSERVACIONES --}}
                                <div class="sm:col-span-2">

                                    <dt class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                        Observaciones
                                    </dt>

                                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">
                                        {{ $notes ?: 'Sin observaciones' }}
                                    </dd>

                                </div>

                            </dl>

                        </div>

                    @endif


                    @error('unit')
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                {{-- FOOTER --}}
                <div
                    class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                    <button type="button" wire:click="closeModal" wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 disabled:opacity-60">
                        Cancelar
                    </button>

                    <button type="button" wire:click="assign" wire:loading.attr="disabled" wire:target="assign"
                        @disabled($transporters->isEmpty())
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <svg wire:loading.remove wire:target="assign" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                        </svg>

                        <svg wire:loading wire:target="assign" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>

                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                        </svg>

                        <span wire:loading.remove wire:target="assign">
                            @if ($isReassignment)
                                Reasignar unidad
                            @else
                                Asignar unidad
                            @endif
                        </span>

                        <span wire:loading wire:target="assign">
                            Guardando...
                        </span>
                    </button>
                </div>
            </div>
        </div>

    @endif
</div>