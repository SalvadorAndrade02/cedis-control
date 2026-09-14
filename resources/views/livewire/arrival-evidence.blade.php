<div class="space-y-5">

    {{-- ========================================================= --}}
    {{-- ERROR --}}
    {{-- ========================================================= --}}

    @if ($errorMessage)

        <div class="
                        flex
                        items-start
                        gap-3
                        rounded-2xl
                        border
                        border-red-200
                        bg-red-50
                        px-5
                        py-4
                        text-sm
                        text-red-700
                    ">

            <div class="
                            flex
                            h-8
                            w-8
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-red-100
                            font-bold
                            text-red-700
                        ">
                !
            </div>

            <div>

                <p class="font-semibold">
                    No fue posible registrar la llegada
                </p>

                <p class="mt-1">
                    {{ $errorMessage }}
                </p>

            </div>

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- CARD PRINCIPAL --}}
    {{-- ========================================================= --}}

    <section class="
            overflow-hidden
            rounded-3xl
            border
            border-blue-100
            bg-white
            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
        ">

        {{-- HEADER --}}

        <div class="
                border-b
                border-blue-100
                bg-gradient-to-r
                from-blue-50
                via-white
                to-white
                px-6
                py-5
                lg:px-7
            ">

            <div class="
                    flex
                    items-start
                    gap-4
                ">

                <div class="
                        flex
                        h-12
                        w-12
                        shrink-0
                        items-center
                        justify-center
                        rounded-2xl
                        bg-blue-100
                        text-blue-700
                    ">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 15.75h16.5M6 15.75v2.25m12-2.25v2.25M6.75 6h10.5l2.25 5.25H4.5L6.75 6Z" />
                    </svg>

                </div>


                <div>

                    <p class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.18em]
                            text-blue-700
                        ">
                        Primera etapa
                    </p>

                    <h2 class="
                            mt-1
                            text-xl
                            font-semibold
                            tracking-tight
                            text-slate-950
                        ">
                        Registrar llegada al CEDIS
                    </h2>

                    <p class="
                            mt-1
                            max-w-2xl
                            text-sm
                            leading-6
                            text-slate-500
                        ">
                        Documenta las condiciones físicas en las
                        que la unidad es recibida antes de iniciar
                        el proceso de armado.
                    </p>

                </div>

            </div>

        </div>



        <div class="space-y-7 p-6 lg:p-7">


            {{-- ================================================= --}}
            {{-- INDICACIÓN --}}
            {{-- ================================================= --}}

            <div class="
                    flex
                    items-start
                    gap-4
                    rounded-2xl
                    border
                    border-blue-100
                    bg-blue-50/50
                    p-5
                ">

                <div class="
                        flex
                        h-9
                        w-9
                        shrink-0
                        items-center
                        justify-center
                        rounded-xl
                        bg-blue-100
                        text-sm
                        font-bold
                        text-blue-700
                    ">
                    i
                </div>


                <div>

                    <p class="
                            text-sm
                            font-semibold
                            text-blue-900
                        ">
                        Evidencia de recepción
                    </p>

                    <p class="
                            mt-1
                            text-xs
                            leading-5
                            text-blue-700
                        ">
                        Registra fotografías suficientes para
                        identificar el estado de la unidad al momento
                        de ingresar al CEDIS, especialmente si presenta
                        daños, faltantes o alguna condición relevante.
                    </p>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- FOTOGRAFÍAS --}}
            {{-- ========================================================= --}}

            <div>

                <div>

                    <p class="
                text-sm
                font-semibold
                text-slate-950
            ">
                        Evidencias fotográficas
                    </p>

                    <p class="
                mt-1
                text-sm
                text-slate-500
            ">
                        Captura fotografías directamente
                        o selecciona imágenes existentes.
                    </p>

                </div>



                {{-- ===================================================== --}}
                {{-- MÓVIL --}}
                {{-- ===================================================== --}}

                <div class="
            mt-4
            grid
            grid-cols-2
            gap-3
            md:hidden
        ">

                    {{-- CÁMARA --}}

                    <label class="
                flex
                min-h-36
                cursor-pointer
                flex-col
                items-center
                justify-center
                rounded-2xl
                border
                border-blue-200
                bg-blue-50/70
                p-4
                text-center
                transition
                active:scale-[.98]
            ">

                        <input type="file" wire:model="cameraPhoto" accept="image/*" capture="environment"
                            class="sr-only">


                        <div class="
                    flex
                    h-12
                    w-12
                    items-center
                    justify-center
                    rounded-2xl
                    bg-white
                    text-blue-600
                    shadow-sm
                ">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.827 6.175A2.31 2.31 0 0 1 9.186 4.5h5.628a2.31 2.31 0 0 1 2.359 1.675l.184.66c.174.623.741 1.055 1.388 1.055H19.5A1.5 1.5 0 0 1 21 9.39v8.11A1.5 1.5 0 0 1 19.5 19h-15A1.5 1.5 0 0 1 3 17.5V9.39a1.5 1.5 0 0 1 1.5-1.5h.755c.647 0 1.214-.432 1.388-1.055l.184-.66Z" />

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 13.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                            </svg>

                        </div>


                        <p class="
                    mt-3
                    text-sm
                    font-semibold
                    text-blue-900
                ">
                            Tomar foto
                        </p>

                        <p class="
                    mt-1
                    text-[10px]
                    font-medium
                    text-blue-600
                ">
                            Abrir cámara
                        </p>

                    </label>



                    {{-- GALERÍA --}}

                    <label class="
                flex
                min-h-36
                cursor-pointer
                flex-col
                items-center
                justify-center
                rounded-2xl
                border
                border-slate-200
                bg-slate-50
                p-4
                text-center
                transition
                active:scale-[.98]
            ">

                        <input type="file" wire:model="galleryPhotos" accept="image/*" multiple class="sr-only">


                        <div class="
                    flex
                    h-12
                    w-12
                    items-center
                    justify-center
                    rounded-2xl
                    bg-white
                    text-slate-600
                    shadow-sm
                ">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 19.5h16.5A1.5 1.5 0 0 0 21.75 18V6A1.5 1.5 0 0 0 20.25 4.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z" />

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 8.25h.008v.008H8.25V8.25Z" />
                            </svg>

                        </div>


                        <p class="
                    mt-3
                    text-sm
                    font-semibold
                    text-slate-900
                ">
                            Galería
                        </p>

                        <p class="
                    mt-1
                    text-[10px]
                    font-medium
                    text-slate-500
                ">
                            Elegir imágenes
                        </p>

                    </label>

                </div>



                {{-- ===================================================== --}}
                {{-- TABLET / DESKTOP --}}
                {{-- ===================================================== --}}

                <label class="
            group
            mt-4
            hidden
            min-h-48
            cursor-pointer
            flex-col
            items-center
            justify-center
            rounded-3xl
            border-2
            border-dashed
            border-blue-200
            bg-blue-50/30
            p-7
            text-center
            transition
            hover:border-blue-400
            hover:bg-blue-50/60
            md:flex
        ">

                    <input type="file" wire:model="galleryPhotos" accept="image/*" multiple class="sr-only">


                    <div class="
                flex
                h-14
                w-14
                items-center
                justify-center
                rounded-2xl
                bg-white
                text-blue-600
                shadow-sm
                transition
                group-hover:scale-105
            ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-7 w-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 19.5h16.5A1.5 1.5 0 0 0 21.75 18V6A1.5 1.5 0 0 0 20.25 4.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z" />
                        </svg>

                    </div>


                    <p class="
                mt-4
                text-sm
                font-semibold
                text-slate-900
            ">
                        Seleccionar fotografías
                    </p>

                    <p class="
                mt-2
                max-w-md
                text-xs
                leading-5
                text-slate-500
            ">
                        Selecciona las imágenes de recepción
                        desde tu equipo.
                    </p>


                    <span class="
                mt-4
                rounded-full
                bg-white
                px-3
                py-1.5
                text-[10px]
                font-semibold
                uppercase
                tracking-wider
                text-blue-700
                shadow-sm
            ">
                        Máximo 15 fotografías
                    </span>

                </label>



                {{-- ===================================================== --}}
                {{-- LOADING --}}
                {{-- ===================================================== --}}

                <div wire:loading wire:target="cameraPhoto" class="
            mt-3
            rounded-xl
            bg-blue-50
            px-4
            py-3
            text-xs
            font-medium
            text-blue-700
        ">
                    Procesando fotografía tomada...
                </div>


                <div wire:loading wire:target="galleryPhotos" class="
            mt-3
            rounded-xl
            bg-slate-50
            px-4
            py-3
            text-xs
            font-medium
            text-slate-600
        ">
                    Procesando fotografías seleccionadas...
                </div>



                {{-- ===================================================== --}}
                {{-- ERRORES --}}
                {{-- ===================================================== --}}

                @error('cameraPhoto')

                        <p class="
                        mt-2
                        text-sm
                        font-medium
                        text-red-600
                    ">
                            {{ $message }}
                        </p>

                @enderror


                @error('galleryPhotos')

                        <p class="
                        mt-2
                        text-sm
                        font-medium
                        text-red-600
                    ">
                            {{ $message }}
                        </p>

                @enderror


                @error('galleryPhotos.*')

                        <p class="
                        mt-2
                        text-sm
                        font-medium
                        text-red-600
                    ">
                            {{ $message }}
                        </p>

                @enderror


                @error('photos')

                        <p class="
                        mt-2
                        text-sm
                        font-medium
                        text-red-600
                    ">
                            {{ $message }}
                        </p>

                @enderror


                @error('photos.*')

                        <p class="
                        mt-2
                        text-sm
                        font-medium
                        text-red-600
                    ">
                            {{ $message }}
                        </p>

                @enderror

            </div>



            {{-- ================================================= --}}
            {{-- PREVIEW --}}
            {{-- ================================================= --}}

            @if ($photos)

                <div>

                    <div class="
                                    mb-4
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                ">

                        <div>

                            <p class="
                                            text-sm
                                            font-semibold
                                            text-slate-900
                                        ">
                                Evidencias seleccionadas
                            </p>

                            <p class="
                                            mt-0.5
                                            text-xs
                                            text-slate-500
                                        ">
                                Revisa las imágenes antes
                                de confirmar la recepción.
                            </p>

                        </div>


                        <span class="
                                        rounded-full
                                        bg-blue-50
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-blue-700
                                    ">
                            {{ count($photos) }}
                            seleccionada(s)
                        </span>

                    </div>


                    <div class="
                                    grid
                                    grid-cols-2
                                    gap-3
                                    sm:grid-cols-3
                                    lg:grid-cols-4
                                    xl:grid-cols-5
                                ">

                        @foreach (
                                $photos
                                as $index => $photo
                            )

                            <div class="
                                                    group
                                                    relative
                                                    overflow-hidden
                                                    rounded-2xl
                                                    border
                                                    border-slate-200
                                                    bg-slate-100
                                                    shadow-sm
                                                ">

                                <img src="{{ $photo->temporaryUrl() }}" alt="Evidencia de llegada" class="
                                                        aspect-square
                                                        w-full
                                                        object-cover
                                                    ">


                                <div class="
                                                        pointer-events-none
                                                        absolute
                                                        inset-x-0
                                                        bottom-0
                                                        h-16
                                                        bg-gradient-to-t
                                                        from-black/70
                                                        to-transparent
                                                    "></div>


                                <button type="button" wire:click="
                                                        removePhoto({{ $index }})
                                                    " title="Eliminar fotografía" class="
                                                        absolute
                                                        right-2
                                                        top-2
                                                        flex
                                                        h-8
                                                        w-8
                                                        items-center
                                                        justify-center
                                                        rounded-full
                                                        bg-black/70
                                                        text-sm
                                                        font-bold
                                                        text-white
                                                        shadow-sm
                                                        transition
                                                        hover:bg-red-600
                                                    ">
                                    ×
                                </button>


                                <p class="
                                                        absolute
                                                        bottom-2
                                                        left-3
                                                        text-[10px]
                                                        font-medium
                                                        text-white
                                                    ">
                                    Evidencia {{ $index + 1 }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif



            {{-- ================================================= --}}
            {{-- OBSERVACIONES --}}
            {{-- ================================================= --}}

            <div class="
                    border-t
                    border-slate-100
                    pt-6
                ">

                <label class="
                        text-sm
                        font-semibold
                        text-slate-900
                    ">
                    Observaciones de recepción
                </label>

                <p class="
                        mt-1
                        text-xs
                        leading-5
                        text-slate-500
                    ">
                    Registra daños, faltantes o cualquier otra
                    condición relevante observada al recibir la unidad.
                </p>


                <textarea wire:model="observations" rows="4" placeholder="Ej. Unidad recibida sin daños visibles..."
                    class="
                        mt-3
                        w-full
                        rounded-2xl
                        border
                        border-slate-200
                        bg-slate-50/60
                        px-4
                        py-3
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
                    "></textarea>

            </div>



            {{-- ================================================= --}}
            {{-- CONFIRMAR --}}
            {{-- ================================================= --}}

            <div class="
                    rounded-2xl
                    border
                    border-emerald-100
                    bg-emerald-50/40
                    p-5
                ">

                <div class="
                        flex
                        flex-col
                        gap-5
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                    ">

                    <div>

                        <p class="
                                text-sm
                                font-semibold
                                text-emerald-900
                            ">
                            Confirmar recepción
                        </p>

                        <p class="
                                mt-1
                                max-w-xl
                                text-xs
                                leading-5
                                text-emerald-700
                            ">
                            Al confirmar, las evidencias y observaciones
                            quedarán asociadas al expediente y la unidad
                            avanzará automáticamente a la etapa de armado.
                        </p>

                    </div>


                    <button type="button" wire:click="complete" wire:loading.attr="disabled"
                        wire:target="complete,photos" class="
                            inline-flex
                            shrink-0
                            items-center
                            justify-center
                            gap-2
                            rounded-xl
                            bg-emerald-600
                            px-6
                            py-3.5
                            text-sm
                            font-semibold
                            text-white
                            shadow-sm
                            transition
                            hover:bg-emerald-700
                            hover:shadow-md
                            active:scale-[.98]
                            disabled:cursor-not-allowed
                            disabled:opacity-50
                        ">

                        <span wire:loading.remove wire:target="complete">
                            Confirmar llegada
                        </span>

                        <span wire:loading wire:target="complete">
                            Guardando evidencias...
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </section>

</div>