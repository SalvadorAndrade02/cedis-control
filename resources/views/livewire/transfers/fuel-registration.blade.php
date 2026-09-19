<div class="space-y-6">

    {{-- ============================================================
    UNIDAD
    ============================================================ --}}

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 bg-gradient-to-r from-teal-50 to-white px-5 py-5 sm:px-6">

            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-teal-700">
                Registro de combustible
            </p>

            <h2 class="mt-2 text-xl font-bold tracking-tight text-slate-950">
                {{ $unit->brand?->name ?? 'Unidad' }}

                @if ($unit->model)
                    · {{ $unit->model }}
                @endif
            </h2>

            <p class="mt-2 break-all font-mono text-sm font-medium text-slate-500">
                {{ $unit->vin }}
            </p>

        </div>


        <div class="grid gap-4 px-5 py-5 sm:grid-cols-2 sm:px-6">

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
                    {{ $assignment->destination_name ?: '—' }}
                </p>
            </div>

        </div>

    </section>



    {{-- ============================================================
    IMPORTE
    ============================================================ --}}

    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div>

            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">
                Paso 1
            </p>

            <h2 class="mt-1 text-lg font-semibold text-slate-950">
                ¿Cuánto se cargó de gasolina?
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Selecciona el importe correspondiente.
            </p>

        </div>


        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-5">

            @foreach (['500', '300', '200', '150'] as $amount)

                    <button type="button" wire:click="selectFixedAmount('{{ $amount }}')" class="
                                rounded-2xl
                                border
                                px-4
                                py-4
                                text-center
                                transition

                                {{
                $amountOption === 'FIXED'
                && $fixedAmount === $amount
                ? 'border-teal-500 bg-teal-50 ring-2 ring-teal-100'
                : 'border-slate-200 bg-white hover:border-teal-300 hover:bg-teal-50/50'
                                }}
                            ">

                        <span class="block text-xl font-bold text-slate-950">
                            ${{ $amount }}
                        </span>

                    </button>

            @endforeach


            <button type="button" wire:click="selectOther" class="
                    col-span-2
                    rounded-2xl
                    border
                    px-4
                    py-4
                    text-center
                    transition
                    sm:col-span-1

                    {{
    $amountOption === 'OTHER'
    ? 'border-teal-500 bg-teal-50 ring-2 ring-teal-100'
    : 'border-slate-200 bg-white hover:border-teal-300 hover:bg-teal-50/50'
                    }}
                ">

                <span class="block text-base font-bold text-slate-950">
                    Otro
                </span>

            </button>

        </div>


        @error('amountOption')
            <p class="mt-3 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror



        {{-- IMPORTE PERSONALIZADO --}}

        @if ($amountOption === 'OTHER')

            <div class="mt-5">

                <label for="customAmount" class="mb-2 block text-sm font-semibold text-slate-700">
                    Importe
                </label>

                <div class="relative">

                    <span
                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-lg font-semibold text-slate-500">
                        $
                    </span>

                    <input id="customAmount" type="number" min="0" step="0.01" inputmode="decimal"
                        wire:model.live.debounce.350ms="customAmount" placeholder="0.00"
                        class="block w-full rounded-xl border-slate-300 py-3 pl-9 pr-4 text-base font-semibold shadow-sm focus:border-teal-500 focus:ring-teal-500">

                </div>

                @error('customAmount')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        @endif

    </section>



    {{-- ============================================================
    SIN GASOLINA
    ============================================================ --}}

    @if ($isZeroAmount)

        <section class="rounded-3xl border border-amber-200 bg-amber-50/50 p-5 shadow-sm sm:p-6">

            <div>

                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-amber-700">
                    Sin carga
                </p>

                <h2 class="mt-1 text-lg font-semibold text-slate-950">
                    ¿Por qué no se cargó gasolina?
                </h2>

                <p class="mt-1 text-sm text-slate-600">
                    Selecciona el motivo correspondiente.
                </p>

            </div>


            <div class="mt-5 grid gap-3">

                @foreach ($noFuelReasons as $reason)

                    <label class="
                                    flex
                                    cursor-pointer
                                    items-start
                                    gap-3
                                    rounded-xl
                                    border
                                    p-4
                                    transition

                                    {{
                    $noFuelReason === $reason->value
                    ? 'border-amber-400 bg-white ring-2 ring-amber-100'
                    : 'border-amber-200 bg-white/70 hover:bg-white'
                                    }}
                                ">

                        <input type="radio" wire:model.live="noFuelReason" value="{{ $reason->value }}"
                            class="mt-1 border-slate-300 text-amber-600 focus:ring-amber-500">

                        <span class="text-sm font-medium text-slate-800">
                            {{ $reason->label() }}
                        </span>

                    </label>

                @endforeach

            </div>


            @error('noFuelReason')
                <p class="mt-3 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror


            @if (
                    $noFuelReason
                    === \App\Enums\NoFuelReason::OTHER->value
                )

                <div class="mt-5">

                    <label for="reasonNotes" class="mb-2 block text-sm font-semibold text-slate-700">
                        Especifica el motivo
                        <span class="text-red-500">*</span>
                    </label>

                    <textarea id="reasonNotes" wire:model="reasonNotes" rows="3" maxlength="1000"
                        placeholder="Describe brevemente por qué no fue posible cargar gasolina..."
                        class="block w-full resize-none rounded-xl border-slate-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500"></textarea>

                    @error('reasonNotes')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            @endif

        </section>

    @endif



    {{-- ============================================================
    TICKET
    ============================================================ --}}

    @if (
            $amountOption
            && !$isZeroAmount
        )

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <div>

                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">
                    Paso 2
                </p>

                <h2 class="mt-1 text-lg font-semibold text-slate-950">
                    Fotografía del ticket
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    La evidencia del ticket es obligatoria cuando se realizó una carga.
                </p>

            </div>


            @if (!$selectedTicket)

                <div class="mt-5 grid gap-3 sm:grid-cols-2">

                    {{-- CÁMARA --}}

                    <label
                        class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-teal-200 bg-teal-50/50 px-5 py-7 text-center transition hover:border-teal-400 hover:bg-teal-50">

                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-teal-100 text-teal-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.25 6.087c0-.355-.186-.676-.401-.959-.365-.48-.767-.878-1.183-1.23a2.25 2.25 0 0 0-2.832 0c-.416.352-.818.75-1.183 1.23-.215.283-.401.604-.401.959H6A2.25 2.25 0 0 0 3.75 8.337v8.413A2.25 2.25 0 0 0 6 19h12a2.25 2.25 0 0 0 2.25-2.25V8.337A2.25 2.25 0 0 0 18 6.087h-3.75Z" />

                                <circle cx="12" cy="12.5" r="3" />
                            </svg>
                        </div>

                        <span class="mt-3 text-sm font-semibold text-slate-900">
                            Tomar fotografía
                        </span>

                        <span class="mt-1 text-xs text-slate-500">
                            Abrir cámara del dispositivo
                        </span>

                        <input type="file" accept="image/*" capture="environment" wire:model="cameraTicket" class="sr-only">

                    </label>


                    {{-- GALERÍA --}}

                    <label
                        class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 px-5 py-7 text-center transition hover:border-slate-400">

                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-200 text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909" />

                                <circle cx="15" cy="7.5" r="1.5" />
                            </svg>
                        </div>

                        <span class="mt-3 text-sm font-semibold text-slate-900">
                            Elegir de galería
                        </span>

                        <span class="mt-1 text-xs text-slate-500">
                            Seleccionar imagen existente
                        </span>

                        <input type="file" accept="image/*" wire:model="galleryTicket" class="sr-only">

                    </label>

                </div>

            @else

                <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4">

                    <div class="flex items-center justify-between gap-4">

                        <div class="min-w-0">

                            <p class="text-sm font-semibold text-emerald-900">
                                Ticket seleccionado
                            </p>

                            <p class="mt-1 truncate text-xs text-emerald-700">
                                {{ $selectedTicket->getClientOriginalName() }}
                            </p>

                        </div>


                        <button type="button" wire:click="removeTicket"
                            class="shrink-0 rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                            Quitar
                        </button>

                    </div>

                </div>

            @endif


            <div wire:loading wire:target="cameraTicket,galleryTicket" class="mt-3 text-sm font-medium text-teal-700">
                Procesando fotografía...
            </div>


            @error('ticket')
                <p class="mt-3 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('cameraTicket')
                <p class="mt-3 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('galleryTicket')
                <p class="mt-3 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </section>

    @endif



    {{-- ============================================================
    OBSERVACIONES
    ============================================================ --}}

    @if ($amountOption)

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <label for="fuel-observations" class="block text-sm font-semibold text-slate-700">
                Observaciones
            </label>

            <p class="mt-1 text-xs text-slate-500">
                Opcional
            </p>

            <textarea id="fuel-observations" wire:model="observations" rows="4" maxlength="1000"
                placeholder="Agrega información adicional si es necesario..."
                class="mt-3 block w-full resize-none rounded-xl border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500"></textarea>

            @error('observations')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </section>

    @endif



    {{-- ============================================================
    GUARDAR
    ============================================================ --}}

    @if ($amountOption)

        <div
            class="sticky bottom-3 z-20 rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-xl backdrop-blur sm:static sm:border-0 sm:bg-transparent sm:p-0 sm:shadow-none">

            <button type="button" wire:click="register" wire:loading.attr="disabled" wire:target="register"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-teal-600 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:opacity-60">

                <span wire:loading.remove wire:target="register">
                    Registrar combustible
                </span>

                <span wire:loading wire:target="register">
                    Guardando...
                </span>

            </button>

        </div>

    @endif

</div>