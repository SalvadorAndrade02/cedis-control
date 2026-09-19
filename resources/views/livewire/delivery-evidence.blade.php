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
                    No fue posible registrar la entrega
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
            border-violet-100
            bg-white
            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
        ">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}

        <div class="
                border-b
                border-violet-100
                bg-gradient-to-r
                from-violet-50
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
                        bg-violet-100
                        text-violet-700
                    ">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h10.5v8.25H3.75V6.75Zm10.5 3h3.75l2.25 2.25v3h-6V9.75ZM6.75 18a1.5 1.5 0 1 1-3 0m15 0a1.5 1.5 0 1 1-3 0" />
                    </svg>

                </div>


                <div>

                    <p class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.18em]
                            text-violet-700
                        ">
                        Tercera etapa
                    </p>

                    <h2 class="
                            mt-1
                            text-xl
                            font-semibold
                            tracking-tight
                            text-slate-950
                        ">
                        Entrega a transportadora
                    </h2>

                    <p class="
                            mt-1
                            max-w-2xl
                            text-sm
                            leading-6
                            text-slate-500
                        ">
                        Registra los datos del transporte,
                        la persona que recibe la unidad
                        y las evidencias correspondientes
                        a su salida del CEDIS.
                    </p>

                </div>

            </div>

        </div>



        <div class="space-y-8 p-6 lg:p-7">


            {{-- ================================================= --}}
            {{-- UNIDAD --}}
            {{-- ================================================= --}}

            <div class="
                    flex
                    flex-col
                    gap-4
                    rounded-2xl
                    border
                    border-violet-100
                    bg-violet-50/40
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
                            text-violet-600
                        ">
                        Unidad lista para entrega
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


                    @if ($unit->year)

                        <p class="
                                        mt-1
                                        text-sm
                                        text-slate-500
                                    ">
                            Modelo {{ $unit->year }}

                            @if ($unit->exterior_color)
                                · {{ $unit->exterior_color }}
                            @endif
                        </p>

                    @endif

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
            {{-- DATOS TRANSPORTADORA --}}
            {{-- ================================================= --}}

            <div>

                <div class="
                        flex
                        items-start
                        gap-3
                    ">

                    <div class="
                            flex
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-violet-100
                            text-sm
                            font-bold
                            text-violet-700
                        ">
                        1
                    </div>

                    <div>

                        <h3 class="
                                font-semibold
                                text-slate-950
                            ">
                            Datos de transportadora
                        </h3>

                        <p class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                            Identifica quién recibe y transporta
                            físicamente la unidad.
                        </p>

                    </div>

                </div>


                <div class="
                        mt-5
                        grid
                        gap-5
                        md:grid-cols-2
                    ">

                    {{-- TRANSPORTADORA --}}

                    <div class="md:col-span-2">

                        <label class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-500
                            ">
                            Transportadora *
                        </label>

                        <input type="text" wire:model="carrierName" placeholder="Ej. Transportes del Norte" class="
                                mt-2
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
                                focus:border-violet-500
                                focus:bg-white
                                focus:ring-4
                                focus:ring-violet-500/10
                            ">

                        @error('carrierName')

                            <p class="
                                            mt-1
                                            text-xs
                                            font-medium
                                            text-red-600
                                        ">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- OPERADOR --}}

                    <div>

                        <label class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-500
                            ">
                            Operador *
                        </label>

                        <input type="text" wire:model="operatorName" wire:input="markOperatorAsManual"
                            placeholder="Nombre del operador"
                            class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">

                        @error('operatorName')

                            <p class="
                                            mt-1
                                            text-xs
                                            font-medium
                                            text-red-600
                                        ">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- IDENTIFICACIÓN --}}

                    <div>

                        <label class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-500
                            ">
                            Identificación
                        </label>

                        <input type="text" wire:model="operatorIdentification"
                            placeholder="Número o referencia de identificación" class="
                                mt-2
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
                                focus:border-violet-500
                                focus:bg-white
                                focus:ring-4
                                focus:ring-violet-500/10
                            ">

                        @error('operatorIdentification')

                            <p class="
                                            mt-1
                                            text-xs
                                            font-medium
                                            text-red-600
                                        ">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- TELÉFONO --}}

                    <div>

                        <label class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-500
                            ">
                            Teléfono
                        </label>

                        <input type="tel" wire:model="operatorPhone" placeholder="Ej. 8112345678" class="
                                mt-2
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
                                focus:border-violet-500
                                focus:bg-white
                                focus:ring-4
                                focus:ring-violet-500/10
                            ">

                        @error('operatorPhone')

                            <p class="
                                            mt-1
                                            text-xs
                                            font-medium
                                            text-red-600
                                        ">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- PLACAS --}}

                    <div>

                        <label class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-500
                            ">
                            Placas *
                        </label>

                        <input type="text" wire:model="vehiclePlate" placeholder="Ej. ABC-123" class="
                                mt-2
                                w-full
                                rounded-2xl
                                border
                                border-slate-200
                                bg-slate-50/60
                                px-4
                                py-3
                                text-sm
                                font-semibold
                                uppercase
                                tracking-wide
                                text-slate-900
                                outline-none
                                transition
                                placeholder:font-normal
                                placeholder:normal-case
                                placeholder:tracking-normal
                                placeholder:text-slate-400
                                hover:border-slate-300
                                focus:border-violet-500
                                focus:bg-white
                                focus:ring-4
                                focus:ring-violet-500/10
                            ">

                        @error('vehiclePlate')

                            <p class="
                                            mt-1
                                            text-xs
                                            font-medium
                                            text-red-600
                                        ">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- NÚMERO ECONÓMICO --}}

                    <div>

                        <label class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-500
                            ">
                            Número económico
                        </label>

                        <input type="text" wire:model="vehicleNumber" placeholder="Ej. TR-014" class="
                                mt-2
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
                                focus:border-violet-500
                                focus:bg-white
                                focus:ring-4
                                focus:ring-violet-500/10
                            ">

                        @error('vehicleNumber')

                            <p class="
                                            mt-1
                                            text-xs
                                            font-medium
                                            text-red-600
                                        ">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    {{-- TIPO DE TRANSPORTE --}}

                    <div class="md:col-span-2">

                        <label class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-500
                            ">
                            Tipo de transporte
                        </label>

                        <input type="text" wire:model="transportType"
                            placeholder="Ej. Plataforma, caja cerrada, madrina..." class="
                                mt-2
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
                                focus:border-violet-500
                                focus:bg-white
                                focus:ring-4
                                focus:ring-violet-500/10
                            ">

                        @error('transportType')

                            <p class="
                                            mt-1
                                            text-xs
                                            font-medium
                                            text-red-600
                                        ">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- EVIDENCIAS --}}
            {{-- ================================================= --}}

            <div class="
                    border-t
                    border-slate-100
                    pt-7
                ">

                <div class="
                        flex
                        items-start
                        gap-3
                    ">

                    <div class="
                            flex
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-violet-100
                            text-sm
                            font-bold
                            text-violet-700
                        ">
                        2
                    </div>

                    <div>

                        <h3 class="
                                font-semibold
                                text-slate-950
                            ">
                            Evidencias de entrega
                        </h3>

                        <p class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                            Documenta la condición de la unidad
                            y su carga en el transporte.
                        </p>

                    </div>

                </div>


                <label class="
                        group
                        mt-5
                        flex
                        min-h-48
                        cursor-pointer
                        flex-col
                        items-center
                        justify-center
                        rounded-3xl
                        border-2
                        border-dashed
                        border-violet-200
                        bg-violet-50/30
                        p-7
                        text-center
                        transition
                        hover:border-violet-400
                        hover:bg-violet-50/60
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
                            text-violet-600
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
                            max-w-lg
                            text-xs
                            leading-5
                            text-slate-500
                        ">
                        Se recomienda registrar la unidad antes
                        de cargar, la unidad cargada y las placas
                        del transporte.
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
                            text-violet-700
                            shadow-sm
                        ">
                        Máximo 15 fotografías
                    </span>

                </label>


                <div wire:loading wire:target="photos" class="
                        mt-3
                        text-xs
                        font-medium
                        text-violet-600
                    ">
                    Procesando fotografías...
                </div>


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
                                Revisa las imágenes antes
                                de confirmar la entrega.
                            </p>

                        </div>


                        <span class="
                                        rounded-full
                                        bg-violet-50
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-violet-700
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

                                <img src="{{ $photo->temporaryUrl() }}" alt="Evidencia de entrega" class="
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
                    pt-7
                ">

                <div class="
                        flex
                        items-start
                        gap-3
                    ">

                    <div class="
                            flex
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-violet-100
                            text-sm
                            font-bold
                            text-violet-700
                        ">
                        3
                    </div>

                    <div>

                        <h3 class="
                                font-semibold
                                text-slate-950
                            ">
                            Observaciones
                        </h3>

                        <p class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                            Agrega cualquier condición relevante
                            registrada durante la entrega.
                        </p>

                    </div>

                </div>


                <textarea wire:model="observations" rows="4"
                    placeholder="Ej. Unidad entregada en buenas condiciones y cargada correctamente..." class="
                        mt-5
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
                        focus:border-violet-500
                        focus:bg-white
                        focus:ring-4
                        focus:ring-violet-500/10
                    "></textarea>


                @error('observations')

                    <p class="
                                    mt-1
                                    text-xs
                                    font-medium
                                    text-red-600
                                ">
                        {{ $message }}
                    </p>

                @enderror

            </div>



            {{-- ================================================= --}}
            {{-- CONFIRMACIÓN --}}
            {{-- ================================================= --}}

            <div class="
                    rounded-2xl
                    border
                    border-emerald-100
                    bg-gradient-to-r
                    from-emerald-50
                    to-white
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

                        <div class="
                                flex
                                items-center
                                gap-2
                            ">

                            <span class="
                                    flex
                                    h-6
                                    w-6
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-emerald-100
                                    text-xs
                                    font-bold
                                    text-emerald-700
                                ">
                                ✓
                            </span>

                            <p class="
                                    text-sm
                                    font-semibold
                                    text-emerald-900
                                ">
                                Confirmar entrega de la unidad
                            </p>

                        </div>


                        <p class="
                                mt-2
                                max-w-2xl
                                text-xs
                                leading-5
                                text-emerald-700
                            ">
                            Al confirmar, la información del transporte
                            y las evidencias quedarán asociadas al expediente.
                            La unidad cambiará a expediente completo y el flujo
                            operativo finalizará.
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
                            Confirmar entrega
                        </span>

                        <span wire:loading wire:target="complete">
                            Registrando entrega...
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </section>

</div>