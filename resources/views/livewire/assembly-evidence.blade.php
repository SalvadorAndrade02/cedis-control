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
                    No fue posible finalizar el armado
                </p>

                <p class="mt-1">
                    {{ $errorMessage }}
                </p>

            </div>

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- CARD --}}
    {{-- ========================================================= --}}

    <section class="
            overflow-hidden
            rounded-3xl
            border
            border-amber-100
            bg-white
            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
        ">

        {{-- HEADER --}}

        <div class="
                border-b
                border-amber-100
                bg-gradient-to-r
                from-amber-50
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
                        bg-amber-100
                        text-amber-700
                    ">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>

                </div>


                <div>

                    <p class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.18em]
                            text-amber-700
                        ">
                        Segunda etapa
                    </p>

                    <h2 class="
                            mt-1
                            text-xl
                            font-semibold
                            tracking-tight
                            text-slate-950
                        ">
                        Finalizar armado
                    </h2>

                    <p class="
                            mt-1
                            max-w-2xl
                            text-sm
                            leading-6
                            text-slate-500
                        ">
                        Documenta la condición final de la unidad
                        y registra las evidencias necesarias para
                        cerrar la etapa de armado.
                    </p>

                </div>

            </div>

        </div>



        <div class="space-y-7 p-6 lg:p-7">


            {{-- ================================================= --}}
            {{-- UNIDAD --}}
            {{-- ================================================= --}}

            <div class="
                    flex
                    flex-col
                    gap-4
                    rounded-2xl
                    border
                    border-slate-200
                    bg-slate-50/70
                    p-5
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                ">

                <div>

                    <p class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.14em]
                            text-slate-400
                        ">
                        Unidad en armado
                    </p>

                    <p class="
                            mt-1
                            text-lg
                            font-semibold
                            text-slate-950
                        ">
                        {{ $unit->brand?->name ?? 'Sin marca' }}

                        {{ $unit->model }}
                    </p>

                </div>


                <div class="
                        rounded-xl
                        bg-white
                        px-4
                        py-3
                        shadow-sm
                    ">

                    <p class="
                            text-[9px]
                            font-semibold
                            uppercase
                            tracking-wider
                            text-slate-400
                        ">
                        VIN
                    </p>

                    <p class="
                            mt-1
                            break-all
                            font-mono
                            text-xs
                            font-semibold
                            tracking-wide
                            text-slate-700
                        ">
                        {{ $unit->vin }}
                    </p>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- EVIDENCIAS --}}
            {{-- ================================================= --}}

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
                        Captura imágenes claras de la condición
                        final de la unidad.
                    </p>

                </div>


                <label class="
                        group
                        mt-4
                        flex
                        min-h-48
                        cursor-pointer
                        flex-col
                        items-center
                        justify-center
                        rounded-3xl
                        border-2
                        border-dashed
                        border-amber-200
                        bg-amber-50/40
                        p-7
                        text-center
                        transition
                        hover:border-amber-400
                        hover:bg-amber-50
                    ">

                    <input type="file" wire:model="photos" accept="image/*" capture="environment" multiple
                        class="sr-only">


                    <div class="
                            flex
                            h-14
                            w-14
                            items-center
                            justify-center
                            rounded-2xl
                            bg-white
                            text-amber-600
                            shadow-sm
                            transition
                            group-hover:scale-105
                        ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-7 w-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.827 6.175A2.31 2.31 0 0 1 9.186 4.5h5.628a2.31 2.31 0 0 1 2.359 1.675l.184.66c.174.623.741 1.055 1.388 1.055H19.5A1.5 1.5 0 0 1 21 9.39v8.11A1.5 1.5 0 0 1 19.5 19h-15A1.5 1.5 0 0 1 3 17.5V9.39a1.5 1.5 0 0 1 1.5-1.5h.755c.647 0 1.214-.432 1.388-1.055l.184-.66Z" />

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 13.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>

                    </div>


                    <p class="
                            mt-4
                            text-sm
                            font-semibold
                            text-slate-900
                        ">
                        Tomar o seleccionar fotografías
                    </p>

                    <p class="
                            mt-2
                            max-w-md
                            text-xs
                            leading-5
                            text-slate-500
                        ">
                        Registra imágenes que permitan verificar
                        claramente que la unidad terminó su proceso
                        de armado correctamente.
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
                            text-amber-700
                            shadow-sm
                        ">
                        Máximo 15 fotografías
                    </span>

                </label>


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
                                Fotografías seleccionadas
                            </p>

                            <p class="
                                        mt-0.5
                                        text-xs
                                        text-slate-500
                                    ">
                                Revisa las imágenes antes de finalizar.
                            </p>

                        </div>


                        <span class="
                                    rounded-full
                                    bg-amber-50
                                    px-3
                                    py-1
                                    text-xs
                                    font-semibold
                                    text-amber-700
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

                                <img src="{{ $photo->temporaryUrl() }}" alt="Evidencia de armado" class="
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
                                                from-black/60
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
                    Observaciones del armado
                </label>

                <p class="
                        mt-1
                        text-xs
                        text-slate-500
                    ">
                    Registra cualquier detalle relevante
                    antes de cerrar esta etapa.
                </p>


                <textarea wire:model="observations" rows="4" placeholder="Ej. Armado terminado sin incidencias..."
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
                        focus:border-amber-500
                        focus:bg-white
                        focus:ring-4
                        focus:ring-amber-500/10
                    "></textarea>

            </div>



            {{-- ================================================= --}}
            {{-- FINALIZAR --}}
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
                        gap-4
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
                            Finalizar etapa de armado
                        </p>

                        <p class="
                                mt-1
                                max-w-xl
                                text-xs
                                leading-5
                                text-emerald-700
                            ">
                            Al confirmar, el cronómetro será detenido,
                            las evidencias quedarán asociadas al expediente
                            y la unidad avanzará a entrega.
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
                            Confirmar armado finalizado
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