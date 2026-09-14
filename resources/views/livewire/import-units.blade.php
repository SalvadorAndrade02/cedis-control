<div class="space-y-7">

    {{-- ========================================================= --}}
    {{-- MENSAJES --}}
    {{-- ========================================================= --}}

    @if ($successMessage)

        <div class="
                    flex
                    items-start
                    gap-3
                    rounded-2xl
                    border
                    border-emerald-200
                    bg-emerald-50
                    px-5
                    py-4
                ">

            <div class="
                        flex
                        h-9
                        w-9
                        shrink-0
                        items-center
                        justify-center
                        rounded-full
                        bg-emerald-100
                        font-bold
                        text-emerald-700
                    ">
                ✓
            </div>

            <div>

                <p class="
                            text-sm
                            font-semibold
                            text-emerald-900
                        ">
                    Importación completada
                </p>

                <p class="
                            mt-1
                            text-sm
                            text-emerald-700
                        ">
                    {{ $successMessage }}
                </p>

            </div>

        </div>

    @endif


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
                ">

            <div class="
                        flex
                        h-9
                        w-9
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

                <p class="
                            text-sm
                            font-semibold
                            text-red-900
                        ">
                    No fue posible completar la operación
                </p>

                <p class="
                            mt-1
                            text-sm
                            text-red-700
                        ">
                    {{ $errorMessage }}
                </p>

            </div>

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- INDICADOR DE PASOS --}}
    {{-- ========================================================= --}}

    <section class="
            rounded-3xl
            border
            border-slate-200/80
            bg-white
            p-5
            shadow-[0_8px_30px_rgba(15,23,42,0.04)]
        ">

        <div class="
                grid
                gap-3
                md:grid-cols-[1fr_auto_1fr]
                md:items-center
            ">

            {{-- PASO 1 --}}

            <div class="
                    flex
                    items-center
                    gap-4
                    rounded-2xl
                    bg-slate-50
                    p-4
                ">

                <div class="
                        flex
                        h-10
                        w-10
                        shrink-0
                        items-center
                        justify-center
                        rounded-xl
                        bg-blue-600
                        text-xs
                        font-bold
                        text-white
                    ">
                    01
                </div>

                <div>

                    <p class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-[0.14em]
                            text-blue-600
                        ">
                        Documentos
                    </p>

                    <p class="
                            mt-0.5
                            text-sm
                            font-semibold
                            text-slate-900
                        ">
                        Seleccionar XML y PDF
                    </p>

                </div>

            </div>


            <div class="
                    hidden
                    h-px
                    w-10
                    bg-slate-200
                    md:block
                "></div>


            {{-- PASO 2 --}}

            <div class="
                    flex
                    items-center
                    gap-4
                    rounded-2xl
                    p-4
                    {{ $analyzed
    ? 'bg-emerald-50'
    : 'bg-slate-50'
                    }}
                ">

                <div class="
                        flex
                        h-10
                        w-10
                        shrink-0
                        items-center
                        justify-center
                        rounded-xl
                        text-xs
                        font-bold

                        {{ $analyzed
    ? 'bg-emerald-600 text-white'
    : 'bg-slate-200 text-slate-500'
                        }}
                    ">
                    02
                </div>

                <div>

                    <p class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-[0.14em]

                            {{ $analyzed
    ? 'text-emerald-700'
    : 'text-slate-400'
                            }}
                        ">
                        Revisión
                    </p>

                    <p class="
                            mt-0.5
                            text-sm
                            font-semibold
                            text-slate-900
                        ">
                        Validar y confirmar información
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- PASO 1: DOCUMENTOS --}}
    {{-- ========================================================= --}}

    <section class="
            overflow-hidden
            rounded-3xl
            border
            border-slate-200/80
            bg-white
            shadow-[0_8px_30px_rgba(15,23,42,0.04)]
        ">

        {{-- HEADER --}}

        <div class="
                border-b
                border-slate-100
                px-6
                py-5
                lg:px-7
            ">

            <div class="
                    flex
                    items-center
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
                        bg-blue-50
                        text-blue-600
                    ">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M10.5 2.25H5.625A1.875 1.875 0 0 0 3.75 4.125v15.75a1.875 1.875 0 0 0 1.875 1.875h12.75a1.875 1.875 0 0 0 1.875-1.875V11.625a9 9 0 0 0-9-9Z" />
                    </svg>

                </div>

                <div>

                    <p class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.18em]
                            text-blue-600
                        ">
                        Paso 1
                    </p>

                    <h2 class="
                            mt-1
                            text-xl
                            font-semibold
                            tracking-tight
                            text-slate-950
                        ">
                        Documentos de origen
                    </h2>

                    <p class="
                            mt-1
                            text-sm
                            text-slate-500
                        ">
                        Selecciona el CFDI XML y su representación
                        PDF para analizar la unidad.
                    </p>

                </div>

            </div>

        </div>



        {{-- ARCHIVOS --}}

        <div class="
                grid
                gap-5
                p-6
                lg:grid-cols-2
                lg:p-7
            ">

            {{-- XML --}}

            <label class="
                    group
                    relative
                    flex
                    min-h-56
                    cursor-pointer
                    flex-col
                    items-center
                    justify-center
                    overflow-hidden
                    rounded-3xl
                    border-2
                    border-dashed
                    p-6
                    text-center
                    transition

                    {{ $xmlFile
    ? 'border-blue-300 bg-blue-50/50'
    : 'border-slate-200 bg-slate-50/60 hover:border-blue-300 hover:bg-blue-50/30'
                    }}
                ">

                <input type="file" wire:model="xmlFile" accept=".xml,text/xml,application/xml" class="sr-only">


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

                    <span class="
                            text-sm
                            font-bold
                        ">
                        XML
                    </span>

                </div>


                <p class="
                        mt-4
                        text-sm
                        font-semibold
                        text-slate-900
                    ">
                    CFDI original
                </p>

                <p class="
                        mt-1
                        max-w-sm
                        text-xs
                        leading-5
                        text-slate-500
                    ">
                    Selecciona el archivo XML emitido
                    por el proveedor.
                </p>


                @if ($xmlFile)

                    <div class="
                                mt-5
                                flex
                                max-w-full
                                items-center
                                gap-2
                                rounded-xl
                                border
                                border-blue-100
                                bg-white
                                px-3
                                py-2.5
                                text-left
                                shadow-sm
                            ">

                        <span class="
                                    flex
                                    h-6
                                    w-6
                                    shrink-0
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
                                    truncate
                                    text-xs
                                    font-semibold
                                    text-slate-700
                                ">
                            {{ $xmlFile->getClientOriginalName() }}
                        </p>

                    </div>

                @else

                    <span class="
                                mt-5
                                rounded-full
                                bg-white
                                px-3
                                py-1.5
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-500
                                shadow-sm
                            ">
                        Seleccionar XML
                    </span>

                @endif

            </label>



            {{-- PDF --}}

            <label class="
                    group
                    relative
                    flex
                    min-h-56
                    cursor-pointer
                    flex-col
                    items-center
                    justify-center
                    overflow-hidden
                    rounded-3xl
                    border-2
                    border-dashed
                    p-6
                    text-center
                    transition

                    {{ $pdfFile
    ? 'border-red-300 bg-red-50/40'
    : 'border-slate-200 bg-slate-50/60 hover:border-red-300 hover:bg-red-50/30'
                    }}
                ">

                <input type="file" wire:model="pdfFile" accept=".pdf,application/pdf" class="sr-only">


                <div class="
                        flex
                        h-14
                        w-14
                        items-center
                        justify-center
                        rounded-2xl
                        bg-white
                        text-red-600
                        shadow-sm
                        transition
                        group-hover:scale-105
                    ">
                    <span class="
                            text-sm
                            font-bold
                        ">
                        PDF
                    </span>
                </div>


                <p class="
                        mt-4
                        text-sm
                        font-semibold
                        text-slate-900
                    ">
                    Representación PDF
                </p>

                <p class="
                        mt-1
                        max-w-sm
                        text-xs
                        leading-5
                        text-slate-500
                    ">
                    Selecciona el documento PDF asociado
                    a la misma factura.
                </p>


                @if ($pdfFile)

                    <div class="
                                mt-5
                                flex
                                max-w-full
                                items-center
                                gap-2
                                rounded-xl
                                border
                                border-red-100
                                bg-white
                                px-3
                                py-2.5
                                text-left
                                shadow-sm
                            ">

                        <span class="
                                    flex
                                    h-6
                                    w-6
                                    shrink-0
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
                                    truncate
                                    text-xs
                                    font-semibold
                                    text-slate-700
                                ">
                            {{ $pdfFile->getClientOriginalName() }}
                        </p>

                    </div>

                @else

                    <span class="
                                mt-5
                                rounded-full
                                bg-white
                                px-3
                                py-1.5
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-500
                                shadow-sm
                            ">
                        Seleccionar PDF
                    </span>

                @endif

            </label>

        </div>



        {{-- ERRORES + ANALIZAR --}}

        <div class="
                flex
                flex-col
                gap-4
                border-t
                border-slate-100
                bg-slate-50/40
                px-6
                py-5
                sm:flex-row
                sm:items-end
                sm:justify-between
                lg:px-7
            ">

            <div>

                @error('xmlFile')
                    <p class="
                                text-sm
                                font-medium
                                text-red-600
                            ">
                        {{ $message }}
                    </p>
                @enderror

                @error('pdfFile')
                    <p class="
                                mt-1
                                text-sm
                                font-medium
                                text-red-600
                            ">
                        {{ $message }}
                    </p>
                @enderror


                <p class="
                        text-xs
                        text-slate-500
                        {{ $errors->has('xmlFile') || $errors->has('pdfFile')
    ? 'mt-2'
    : ''
                        }}
                    ">
                    Ambos archivos son necesarios
                    para iniciar el análisis.
                </p>

            </div>


            <button type="button" wire:click="analyze" wire:loading.attr="disabled"
                wire:target="analyze,xmlFile,pdfFile" class="
                    inline-flex
                    items-center
                    justify-center
                    gap-2
                    rounded-xl
                    bg-blue-600
                    px-5
                    py-3
                    text-sm
                    font-semibold
                    text-white
                    shadow-sm
                    transition
                    hover:bg-blue-700
                    hover:shadow-md
                    active:scale-[.98]
                    disabled:cursor-not-allowed
                    disabled:opacity-50
                ">

                <span wire:loading.remove wire:target="analyze,xmlFile,pdfFile">
                    Analizar documentos
                </span>

                <span wire:loading wire:target="analyze,xmlFile,pdfFile">
                    Analizando documentos...
                </span>

            </button>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- PASO 2: PREVIEW --}}
    {{-- ========================================================= --}}

    @if ($analyzed && !empty($preview))

        <section class="
                    overflow-hidden
                    rounded-3xl
                    border
                    border-slate-200/80
                    bg-white
                    shadow-[0_8px_30px_rgba(15,23,42,0.04)]
                ">

            {{-- HEADER --}}

            <div class="
                        flex
                        flex-col
                        gap-4
                        border-b
                        border-slate-100
                        px-6
                        py-5
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
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
                                bg-emerald-50
                                text-emerald-700
                            ">
                        ✓
                    </div>


                    <div>

                        <p class="
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-[0.18em]
                                    text-emerald-700
                                ">
                            Paso 2
                        </p>

                        <h2 class="
                                    mt-1
                                    text-xl
                                    font-semibold
                                    tracking-tight
                                    text-slate-950
                                ">
                            Revisión de la importación
                        </h2>

                        <p class="
                                    mt-1
                                    text-sm
                                    text-slate-500
                                ">
                            Revisa, completa o corrige los datos
                            detectados antes de crear el expediente.
                        </p>

                    </div>

                </div>


                <span class="
                            inline-flex
                            w-fit
                            items-center
                            gap-2
                            rounded-full
                            bg-blue-50
                            px-3.5
                            py-1.5
                            text-xs
                            font-semibold
                            text-blue-700
                        ">

                    <span class="
                                h-1.5
                                w-1.5
                                rounded-full
                                bg-blue-500
                            "></span>

                    {{ $preview['supplier']['name'] }}

                </span>

            </div>



            <div class="
                        grid
                        gap-6
                        p-6
                        xl:grid-cols-[380px_minmax(0,1fr)]
                        lg:p-7
                    ">

                {{-- ================================================= --}}
                {{-- DATOS FISCALES --}}
                {{-- ================================================= --}}

                <aside class="xl:self-start">

                    <div class="
                                rounded-3xl
                                border
                                border-slate-200
                                bg-slate-50/70
                                p-5
                                xl:sticky
                                xl:top-24
                            ">

                        <div class="
                                    flex
                                    items-center
                                    justify-between
                                    gap-3
                                ">

                            <div>

                                <p class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-[0.16em]
                                            text-slate-400
                                        ">
                                    CFDI
                                </p>

                                <h3 class="
                                            mt-1
                                            font-semibold
                                            text-slate-950
                                        ">
                                    Información fiscal
                                </h3>

                            </div>


                            <span class="
                                        rounded-full
                                        bg-slate-200
                                        px-2.5
                                        py-1
                                        text-[9px]
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-600
                                    ">
                                Sólo lectura
                            </span>

                        </div>


                        <div class="
                                    mt-4
                                    rounded-2xl
                                    border
                                    border-blue-100
                                    bg-blue-50
                                    px-4
                                    py-3
                                    text-xs
                                    leading-5
                                    text-blue-700
                                ">
                            Esta información proviene directamente
                            del CFDI y no puede modificarse.
                        </div>


                        <dl class="mt-5 space-y-5">

                            <div>

                                <dt class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        ">
                                    Serie / Folio
                                </dt>

                                <dd class="
                                            mt-1
                                            text-sm
                                            font-semibold
                                            text-slate-900
                                        ">
                                    {{ $preview['invoice']['series'] ?? '—' }}
                                    {{ $preview['invoice']['folio'] ?? '' }}
                                </dd>

                            </div>


                            <div>

                                <dt class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        ">
                                    Receptor
                                </dt>

                                <dd class="
                                            mt-1
                                            text-sm
                                            font-semibold
                                            text-slate-900
                                        ">
                                    {{ $preview['invoice']['receiver_name'] ?? '—' }}
                                </dd>

                            </div>


                            <div>

                                <dt class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        ">
                                    UUID
                                </dt>

                                <dd class="
                                            mt-1
                                            break-all
                                            font-mono
                                            text-xs
                                            leading-5
                                            text-slate-600
                                        ">
                                    {{ $preview['invoice']['uuid'] ?? '—' }}
                                </dd>

                            </div>


                            <div>

                                <dt class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        ">
                                    Total
                                </dt>

                                <dd class="
                                            mt-1
                                            text-2xl
                                            font-bold
                                            tracking-tight
                                            text-slate-950
                                        ">
                                    {{ $preview['invoice']['currency'] ?? '' }}

                                    {{ $preview['invoice']['total'] ?? '—' }}
                                </dd>

                            </div>

                        </dl>


                        {{-- ARCHIVOS --}}

                        <div class="
                                    mt-6
                                    border-t
                                    border-slate-200
                                    pt-5
                                ">

                            <p class="
                                        text-[10px]
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    ">
                                Documentos
                            </p>


                            <div class="mt-3 space-y-2">

                                <div class="
                                            rounded-xl
                                            bg-white
                                            px-3
                                            py-2.5
                                        ">

                                    <span class="
                                                text-[10px]
                                                font-bold
                                                text-blue-600
                                            ">
                                        XML
                                    </span>

                                    <p class="
                                                mt-1
                                                truncate
                                                text-xs
                                                text-slate-600
                                            ">
                                        {{ $preview['files']['xml'] ?? '—' }}
                                    </p>

                                </div>


                                <div class="
                                            rounded-xl
                                            bg-white
                                            px-3
                                            py-2.5
                                        ">

                                    <span class="
                                                text-[10px]
                                                font-bold
                                                text-red-600
                                            ">
                                        PDF
                                    </span>

                                    <p class="
                                                mt-1
                                                truncate
                                                text-xs
                                                text-slate-600
                                            ">
                                        {{ $preview['files']['pdf'] ?? '—' }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        @if (!$preview['files']['names_match'])

                            <div class="
                                            mt-5
                                            rounded-2xl
                                            border
                                            border-amber-200
                                            bg-amber-50
                                            p-4
                                        ">

                                <p class="
                                                text-xs
                                                font-semibold
                                                text-amber-900
                                            ">
                                    Verifica los documentos
                                </p>

                                <p class="
                                                mt-1
                                                text-xs
                                                leading-5
                                                text-amber-700
                                            ">
                                    Los nombres del XML y PDF no coinciden.
                                    Confirma que ambos correspondan a la misma factura.
                                </p>

                            </div>

                        @endif

                    </div>

                </aside>



                {{-- ================================================= --}}
                {{-- UNIDADES --}}
                {{-- ================================================= --}}

                <div class="min-w-0 space-y-5">

                    <div class="
                                rounded-2xl
                                border
                                border-blue-100
                                bg-blue-50/60
                                p-4
                            ">

                        <p class="
                                    text-sm
                                    font-semibold
                                    text-blue-900
                                ">
                            Información editable
                        </p>

                        <p class="
                                    mt-1
                                    text-xs
                                    leading-5
                                    text-blue-700
                                ">
                            Puedes corregir o completar la información
                            de la unidad. Los valores detectados originalmente
                            se conservarán para trazabilidad.
                        </p>

                    </div>



                    @foreach ($editableUnits as $index => $unit)

                            @php

                                $previewUnit =
                                    $preview['units'][$index]
                                    ?? [];

                                $originalUnit =
                                    $originalUnits[$index]
                                    ?? [];

                                $isModified =
                                    $unit != $originalUnit;

                            @endphp


                            <article class="
                                            overflow-hidden
                                            rounded-3xl
                                            border
                                            bg-white

                                            {{ $isModified
                        ? 'border-blue-200'
                        : (
                            ($previewUnit['duplicate'] ?? false)
                            ? 'border-red-200'
                            : (
                                ($previewUnit['requires_review'] ?? false)
                                ? 'border-amber-200'
                                : 'border-slate-200'
                            )
                        )
                                            }}
                                        ">

                                {{-- CABECERA --}}

                                <div class="
                                                flex
                                                flex-col
                                                gap-4
                                                border-b
                                                border-slate-100
                                                px-5
                                                py-5
                                                sm:flex-row
                                                sm:items-start
                                                sm:justify-between
                                            ">

                                    <div class="
                                                    flex
                                                    items-center
                                                    gap-3
                                                ">

                                        <div class="
                                                        flex
                                                        h-10
                                                        w-10
                                                        shrink-0
                                                        items-center
                                                        justify-center
                                                        rounded-xl
                                                        bg-slate-100
                                                        text-xs
                                                        font-bold
                                                        text-slate-600
                                                    ">
                                            {{ $index + 1 }}
                                        </div>


                                        <div>

                                            <p class="
                                                            text-[10px]
                                                            font-semibold
                                                            uppercase
                                                            tracking-[0.14em]
                                                            text-slate-400
                                                        ">
                                                Unidad detectada
                                            </p>

                                            <h3 class="
                                                            mt-1
                                                            text-base
                                                            font-semibold
                                                            text-slate-950
                                                        ">
                                                {{ $unit['brand']
                        ?: 'Marca por revisar'
                                                        }}

                                                @if ($unit['model'])
                                                    · {{ $unit['model'] }}
                                                @endif
                                            </h3>

                                        </div>

                                    </div>


                                    <div class="
                                                    flex
                                                    flex-wrap
                                                    gap-2
                                                ">

                                        @if ($isModified)

                                            <span class="
                                                                rounded-full
                                                                bg-blue-100
                                                                px-3
                                                                py-1
                                                                text-[10px]
                                                                font-semibold
                                                                uppercase
                                                                tracking-wider
                                                                text-blue-700
                                                            ">
                                                Modificado
                                            </span>

                                        @endif


                                        @if ($previewUnit['duplicate'] ?? false)

                                            <span class="
                                                                rounded-full
                                                                bg-red-100
                                                                px-3
                                                                py-1
                                                                text-[10px]
                                                                font-semibold
                                                                uppercase
                                                                tracking-wider
                                                                text-red-700
                                                            ">
                                                VIN ya registrado
                                            </span>

                                        @elseif ($previewUnit['requires_review'] ?? false)

                                            <span class="
                                                                rounded-full
                                                                bg-amber-100
                                                                px-3
                                                                py-1
                                                                text-[10px]
                                                                font-semibold
                                                                uppercase
                                                                tracking-wider
                                                                text-amber-700
                                                            ">
                                                Requiere revisión
                                            </span>

                                        @else

                                            <span class="
                                                                rounded-full
                                                                bg-emerald-100
                                                                px-3
                                                                py-1
                                                                text-[10px]
                                                                font-semibold
                                                                uppercase
                                                                tracking-wider
                                                                text-emerald-700
                                                            ">
                                                Datos detectados
                                            </span>

                                        @endif

                                    </div>

                                </div>



                                {{-- FORMULARIO --}}

                                <div class="
                                                grid
                                                gap-5
                                                p-5
                                                md:grid-cols-2
                                            ">

                                    {{-- VIN --}}

                                    <div class="md:col-span-2">

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            VIN *
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.vin" maxlength="50" class="
                                                        mt-2
                                                        w-full
                                                        rounded-2xl
                                                        border
                                                        border-slate-200
                                                        bg-slate-50/60
                                                        px-4
                                                        py-3
                                                        font-mono
                                                        text-sm
                                                        font-medium
                                                        uppercase
                                                        tracking-wide
                                                        text-slate-900
                                                        outline-none
                                                        transition
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                        @error("editableUnits.$index.vin")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror


                                        @if (
                                                ($originalUnit['vin'] ?? null)
                                                !== ($unit['vin'] ?? null)
                                            )

                                            <div class="
                                                                mt-2
                                                                rounded-lg
                                                                bg-blue-50
                                                                px-3
                                                                py-2
                                                                text-xs
                                                                text-blue-700
                                                            ">
                                                Detectado originalmente:
                                                <span class="font-mono font-semibold">
                                                    {{ $originalUnit['vin'] ?? '—' }}
                                                </span>
                                            </div>

                                        @endif

                                    </div>



                                    {{-- MARCA --}}

                                    <div>

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            Marca *
                                        </label>

                                        <select wire:model="editableUnits.{{ $index }}.brand" class="
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
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                            <option value="">
                                                Selecciona una marca
                                            </option>

                                            @foreach ($brands as $brand)

                                                <option value="{{ $brand }}">
                                                    {{ $brand }}
                                                </option>

                                            @endforeach

                                        </select>

                                        @error("editableUnits.$index.brand")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror


                                        @if (
                                                ($originalUnit['brand'] ?? null)
                                                !== ($unit['brand'] ?? null)
                                            )

                                            <p class="
                                                                mt-2
                                                                text-xs
                                                                text-blue-600
                                                            ">
                                                Detectado:
                                                {{ $originalUnit['brand'] ?? '—' }}
                                            </p>

                                        @endif

                                    </div>



                                    {{-- AÑO --}}

                                    <div>

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            Año
                                        </label>

                                        <input type="number" wire:model="editableUnits.{{ $index }}.year" min="1900"
                                            max="{{ now()->year + 2 }}" class="
                                                        mt-2
                                                        w-full
                                                        rounded-2xl
                                                        border
                                                        border-slate-200
                                                        bg-slate-50/60
                                                        px-4
                                                        py-3
                                                        text-sm
                                                        outline-none
                                                        transition
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                        @error("editableUnits.$index.year")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>



                                    {{-- MODELO --}}

                                    <div class="md:col-span-2">

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            Modelo
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.model" maxlength="255"
                                            placeholder="Modelo de la unidad" class="
                                                        mt-2
                                                        w-full
                                                        rounded-2xl
                                                        border
                                                        border-slate-200
                                                        bg-slate-50/60
                                                        px-4
                                                        py-3
                                                        text-sm
                                                        outline-none
                                                        transition
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                        @error("editableUnits.$index.model")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror


                                        @if (
                                                ($originalUnit['model'] ?? null)
                                                !== ($unit['model'] ?? null)
                                            )

                                            <div class="
                                                                mt-2
                                                                rounded-lg
                                                                bg-blue-50
                                                                px-3
                                                                py-2
                                                                text-xs
                                                                text-blue-700
                                                            ">
                                                Detectado originalmente:
                                                <strong>
                                                    {{ $originalUnit['model'] ?? '—' }}
                                                </strong>
                                            </div>

                                        @endif

                                    </div>



                                    {{-- VERSIÓN --}}

                                    <div class="md:col-span-2">

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            Versión
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.version" maxlength="255"
                                            placeholder="Versión, edición o configuración" class="
                                                        mt-2
                                                        w-full
                                                        rounded-2xl
                                                        border
                                                        border-slate-200
                                                        bg-slate-50/60
                                                        px-4
                                                        py-3
                                                        text-sm
                                                        outline-none
                                                        transition
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                        @error("editableUnits.$index.version")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>



                                    {{-- COLOR EXTERIOR --}}

                                    <div>

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            Color exterior
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.exterior_color" maxlength="150"
                                            class="
                                                        mt-2
                                                        w-full
                                                        rounded-2xl
                                                        border
                                                        border-slate-200
                                                        bg-slate-50/60
                                                        px-4
                                                        py-3
                                                        text-sm
                                                        outline-none
                                                        transition
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                        @error("editableUnits.$index.exterior_color")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>



                                    {{-- COLOR INTERIOR --}}

                                    <div>

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            Color interior
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.interior_color" maxlength="150"
                                            class="
                                                        mt-2
                                                        w-full
                                                        rounded-2xl
                                                        border
                                                        border-slate-200
                                                        bg-slate-50/60
                                                        px-4
                                                        py-3
                                                        text-sm
                                                        outline-none
                                                        transition
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                        @error("editableUnits.$index.interior_color")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>



                                    {{-- MOTOR --}}

                                    <div>

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            Número de motor
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.engine_number" maxlength="100"
                                            placeholder="Número de motor" class="
                                                        mt-2
                                                        w-full
                                                        rounded-2xl
                                                        border
                                                        border-slate-200
                                                        bg-slate-50/60
                                                        px-4
                                                        py-3
                                                        text-sm
                                                        outline-none
                                                        transition
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                        @error("editableUnits.$index.engine_number")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>



                                    {{-- PEDIMENTO --}}

                                    <div>

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            Pedimento
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.pedimento" maxlength="100" class="
                                                        mt-2
                                                        w-full
                                                        rounded-2xl
                                                        border
                                                        border-slate-200
                                                        bg-slate-50/60
                                                        px-4
                                                        py-3
                                                        text-sm
                                                        outline-none
                                                        transition
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                        @error("editableUnits.$index.pedimento")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>



                                    {{-- ORDEN DE COMPRA --}}

                                    <div class="md:col-span-2">

                                        <label class="
                                                        text-xs
                                                        font-semibold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-500
                                                    ">
                                            Orden de compra
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.purchase_order" maxlength="100"
                                            class="
                                                        mt-2
                                                        w-full
                                                        rounded-2xl
                                                        border
                                                        border-slate-200
                                                        bg-slate-50/60
                                                        px-4
                                                        py-3
                                                        text-sm
                                                        outline-none
                                                        transition
                                                        focus:border-blue-500
                                                        focus:bg-white
                                                        focus:ring-4
                                                        focus:ring-blue-500/10
                                                    ">

                                        @error("editableUnits.$index.purchase_order")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>

                                </div>



                                {{-- MODIFICACIONES --}}

                                @if ($isModified)

                                    <div class="
                                                        border-t
                                                        border-blue-100
                                                        bg-blue-50/60
                                                        px-5
                                                        py-4
                                                    ">

                                        <div class="
                                                            flex
                                                            items-start
                                                            gap-3
                                                        ">

                                            <div class="
                                                                flex
                                                                h-7
                                                                w-7
                                                                shrink-0
                                                                items-center
                                                                justify-center
                                                                rounded-full
                                                                bg-blue-100
                                                                text-xs
                                                                font-bold
                                                                text-blue-700
                                                            ">
                                                i
                                            </div>

                                            <div>

                                                <p class="
                                                                    text-xs
                                                                    font-semibold
                                                                    text-blue-900
                                                                ">
                                                    Información modificada manualmente
                                                </p>

                                                <p class="
                                                                    mt-1
                                                                    text-xs
                                                                    leading-5
                                                                    text-blue-700
                                                                ">
                                                    Los valores detectados originalmente
                                                    se conservarán dentro de la trazabilidad
                                                    de importación.
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                @endif

                            </article>

                    @endforeach

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- CONFIRMAR --}}
            {{-- ================================================= --}}

            <div class="
                        border-t
                        border-slate-100
                        bg-slate-50/50
                        px-6
                        py-5
                        lg:px-7
                    ">

                @if ($preview['requires_review'])

                    <label class="
                                    mb-5
                                    flex
                                    cursor-pointer
                                    items-start
                                    gap-3
                                    rounded-2xl
                                    border
                                    border-amber-200
                                    bg-amber-50
                                    p-4
                                ">

                        <input type="checkbox" wire:model="reviewAccepted" class="
                                        mt-0.5
                                        h-4
                                        w-4
                                        rounded
                                        border-amber-300
                                        text-amber-500
                                        focus:ring-amber-500
                                    ">

                        <div>

                            <p class="
                                            text-sm
                                            font-semibold
                                            text-amber-900
                                        ">
                                Confirmación de revisión
                            </p>

                            <p class="
                                            mt-1
                                            text-xs
                                            leading-5
                                            text-amber-700
                                        ">
                                Revisé la información detectada,
                                realicé las correcciones necesarias
                                y confirmo que corresponde a la unidad.
                            </p>

                        </div>

                    </label>

                @endif


                <div class="
                            flex
                            flex-col
                            gap-4
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                        ">

                    <div>

                        <p class="
                                    text-sm
                                    font-semibold
                                    text-slate-900
                                ">
                            Crear expediente
                        </p>

                        <p class="
                                    mt-1
                                    text-xs
                                    text-slate-500
                                ">
                            Los datos mostrados serán utilizados
                            para registrar la unidad en CEDIS.
                        </p>

                    </div>


                    <button type="button" wire:click="confirmImport" wire:loading.attr="disabled"
                        wire:target="confirmImport" class="
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

                        <span wire:loading.remove wire:target="confirmImport">
                            Confirmar e importar
                        </span>

                        <span wire:loading wire:target="confirmImport">
                            Creando expediente...
                        </span>

                    </button>

                </div>

            </div>

        </section>

    @endif

</div>