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

            <h2 class="font-semibold">
                Evidencia de llegada
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Registra cómo llegó físicamente la unidad
                al CEDIS.
            </p>

        </div>


        <div class="space-y-6 p-6">

            {{-- ARCHIVOS --}}

            <label
                class="
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
                    hover:border-blue-400
                    hover:bg-blue-50/40
                ">

                <input
                    type="file"
                    wire:model="photos"
                    accept="image/*"
                    capture="environment"
                    multiple
                    class="sr-only">

                <p
                    class="
                        text-sm
                        font-semibold
                        text-slate-900
                    ">
                    Tomar o seleccionar fotografías
                </p>

                <p
                    class="
                        mt-2
                        text-xs
                        text-slate-500
                    ">
                    Puedes registrar hasta 15 imágenes.
                </p>

            </label>


            @error('photos')
            <p class="text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror

            @error('photos.*')
            <p class="text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror


            {{-- PREVIEW --}}

            @if ($photos)

            <div>

                <div
                    class="
                            mb-3
                            flex
                            items-center
                            justify-between
                        ">

                    <p
                        class="
                                text-sm
                                font-medium
                                text-slate-700
                            ">
                        Evidencias seleccionadas
                    </p>

                    <span
                        class="
                                rounded-full
                                bg-slate-100
                                px-2.5
                                py-1
                                text-xs
                                font-semibold
                                text-slate-600
                            ">
                        {{ count($photos) }}
                    </span>

                </div>


                <div
                    class="
                            grid
                            grid-cols-2
                            gap-3
                            sm:grid-cols-3
                            lg:grid-cols-4
                        ">

                    @foreach (
                    $photos as $index => $photo
                    )

                    <div
                        class="
                                    relative
                                    overflow-hidden
                                    rounded-xl
                                    border
                                    border-slate-200
                                    bg-slate-100
                                ">

                        <img
                            src="{{ $photo->temporaryUrl() }}"
                            alt="Evidencia"
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
                                        font-semibold
                                        text-white
                                    ">
                            ×
                        </button>

                    </div>

                    @endforeach

                </div>

            </div>

            @endif


            {{-- OBSERVACIONES --}}

            <div>

                <label
                    class="
                        text-sm
                        font-medium
                        text-slate-700
                    ">
                    Observaciones
                </label>

                <textarea
                    wire:model="observations"
                    rows="4"
                    placeholder="
                        Describe daños, faltantes o cualquier
                        condición relevante de llegada...
                    "
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
                        focus:border-blue-500
                        focus:ring-4
                        focus:ring-blue-500/10
                    "></textarea>

            </div>


            {{-- CONFIRMAR --}}

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
                    bg-blue-600
                    px-5
                    py-3.5
                    text-sm
                    font-semibold
                    text-white
                    transition
                    hover:bg-blue-700
                    disabled:cursor-not-allowed
                    disabled:opacity-50
                ">

                <span
                    wire:loading.remove
                    wire:target="complete">
                    Confirmar llegada
                </span>

                <span
                    wire:loading
                    wire:target="complete">
                    Guardando evidencias...
                </span>

            </button>

        </div>

    </section>

</div>