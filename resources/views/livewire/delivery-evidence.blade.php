<div class="space-y-5">

    @if ($errorMessage)

    <div
        class="
                rounded-xl
                border
                border-red-200
                bg-red-50
                px-4
                py-3
                text-sm
                text-red-700
            ">
        {{ $errorMessage }}
    </div>

    @endif


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

            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-wider
                    text-violet-600
                ">
                Tercera etapa
            </p>

            <h2 class="mt-1 font-semibold">
                Entrega a transportadora
            </h2>

            <p
                class="
                    mt-1
                    text-sm
                    text-slate-500
                ">
                Registra quién recibe la unidad
                y la evidencia de su salida.
            </p>

        </div>


        <div class="space-y-7 p-6">

            {{-- UNIDAD --}}

            <div
                class="
                    rounded-xl
                    bg-slate-50
                    p-4
                ">

                <p class="text-xs text-slate-500">
                    Unidad
                </p>

                <p
                    class="
                        mt-1
                        font-semibold
                        text-slate-950
                    ">
                    {{ $unit->brand?->name }}
                    {{ $unit->model }}
                </p>

                <p
                    class="
                        mt-1
                        font-mono
                        text-xs
                        text-slate-500
                    ">
                    {{ $unit->vin }}
                </p>

            </div>


            {{-- TRANSPORTADORA --}}

            <div>

                <h3 class="font-semibold">
                    Datos de transportadora
                </h3>

                <div
                    class="
                        mt-4
                        grid
                        gap-4
                        sm:grid-cols-2
                    ">

                    <div class="sm:col-span-2">

                        <label class="text-sm font-medium">
                            Transportadora *
                        </label>

                        <input
                            type="text"
                            wire:model="carrierName"
                            placeholder="Nombre de transportadora"
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
                                focus:border-violet-500
                                focus:ring-4
                                focus:ring-violet-500/10
                            ">

                        @error('carrierName')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>


                    <div>

                        <label class="text-sm font-medium">
                            Operador *
                        </label>

                        <input
                            type="text"
                            wire:model="operatorName"
                            placeholder="Nombre del operador"
                            class="
                                mt-2
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                px-4
                                py-3
                                text-sm
                            ">

                    </div>


                    <div>

                        <label class="text-sm font-medium">
                            Identificación
                        </label>

                        <input
                            type="text"
                            wire:model="operatorIdentification"
                            class="
                                mt-2
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                px-4
                                py-3
                                text-sm
                            ">

                    </div>


                    <div>

                        <label class="text-sm font-medium">
                            Teléfono
                        </label>

                        <input
                            type="text"
                            wire:model="operatorPhone"
                            class="
                                mt-2
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                px-4
                                py-3
                                text-sm
                            ">

                    </div>


                    <div>

                        <label class="text-sm font-medium">
                            Placas *
                        </label>

                        <input
                            type="text"
                            wire:model="vehiclePlate"
                            class="
                                mt-2
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                px-4
                                py-3
                                text-sm
                                uppercase
                            ">

                    </div>


                    <div>

                        <label class="text-sm font-medium">
                            Número económico
                        </label>

                        <input
                            type="text"
                            wire:model="vehicleNumber"
                            class="
                                mt-2
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                px-4
                                py-3
                                text-sm
                            ">

                    </div>


                    <div>

                        <label class="text-sm font-medium">
                            Tipo de transporte
                        </label>

                        <input
                            type="text"
                            wire:model="transportType"
                            placeholder="Plataforma, caja, etc."
                            class="
                                mt-2
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                px-4
                                py-3
                                text-sm
                            ">

                    </div>

                </div>

            </div>


            {{-- EVIDENCIAS --}}

            <div>

                <h3 class="font-semibold">
                    Evidencia de salida
                </h3>

                <label
                    class="
                        mt-4
                        flex
                        min-h-44
                        cursor-pointer
                        flex-col
                        items-center
                        justify-center
                        rounded-2xl
                        border-2
                        border-dashed
                        border-slate-300
                        bg-slate-50
                        p-6
                        text-center
                        transition
                        hover:border-violet-400
                        hover:bg-violet-50/30
                    ">

                    <input
                        type="file"
                        wire:model="photos"
                        accept="image/*"
                        capture="environment"
                        multiple
                        class="sr-only">

                    <p class="text-sm font-semibold">
                        Tomar fotografías de la entrega
                    </p>

                    <p
                        class="
                            mt-2
                            text-xs
                            text-slate-500
                        ">
                        Unidad antes de cargar, unidad cargada,
                        placas u otra evidencia relevante.
                    </p>

                </label>


                @if ($photos)

                <div
                    class="
                            mt-4
                            grid
                            grid-cols-2
                            gap-3
                            sm:grid-cols-3
                            lg:grid-cols-4
                        ">

                    @foreach (
                    $photos
                    as $index => $photo
                    )

                    <div
                        class="
                                    relative
                                    overflow-hidden
                                    rounded-xl
                                    border
                                    border-slate-200
                                ">

                        <img
                            src="{{ $photo->temporaryUrl() }}"
                            alt="Evidencia de entrega"
                            class="
                                        aspect-square
                                        w-full
                                        object-cover
                                    ">

                        <button
                            type="button"
                            wire:click="
                                        removePhoto({{ $index }})
                                    "
                            class="
                                        absolute
                                        right-2
                                        top-2
                                        rounded-full
                                        bg-black/70
                                        px-2
                                        py-1
                                        text-xs
                                        text-white
                                    ">
                            ×
                        </button>

                    </div>

                    @endforeach

                </div>

                @endif

            </div>


            {{-- OBSERVACIONES --}}

            <div>

                <label class="text-sm font-medium">
                    Observaciones
                </label>

                <textarea
                    wire:model="observations"
                    rows="4"
                    placeholder="Condición de la unidad al momento de entrega..."
                    class="
                        mt-2
                        w-full
                        rounded-xl
                        border
                        border-slate-300
                        px-4
                        py-3
                        text-sm
                    "></textarea>

            </div>


            <button
                type="button"
                wire:click="complete"
                wire:loading.attr="disabled"
                wire:target="complete,photos"
                class="
                    flex
                    w-full
                    items-center
                    justify-center
                    rounded-xl
                    bg-violet-600
                    px-5
                    py-3.5
                    text-sm
                    font-semibold
                    text-white
                    transition
                    hover:bg-violet-700
                    disabled:opacity-50
                ">

                <span
                    wire:loading.remove
                    wire:target="complete">
                    Confirmar entrega
                </span>

                <span
                    wire:loading
                    wire:target="complete">
                    Registrando entrega...
                </span>

            </button>

        </div>

    </section>

</div>