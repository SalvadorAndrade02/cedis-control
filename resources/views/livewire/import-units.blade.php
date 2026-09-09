<div class="space-y-6">

    {{-- MENSAJES --}}

    @if ($successMessage)

        <div class="
                                    rounded-xl
                                    border
                                    border-emerald-200
                                    bg-emerald-50
                                    px-5
                                    py-4
                                    text-sm
                                    text-emerald-800
                                ">
            {{ $successMessage }}
        </div>

    @endif


    @if ($errorMessage)

        <div class="
                                    rounded-xl
                                    border
                                    border-red-200
                                    bg-red-50
                                    px-5
                                    py-4
                                    text-sm
                                    text-red-700
                                ">
            {{ $errorMessage }}
        </div>

    @endif


    {{-- ARCHIVOS --}}

    <section class="
            rounded-2xl
            border
            border-slate-200
            bg-white
            shadow-sm
        ">

        <div class="
                border-b
                border-slate-200
                px-6
                py-5
            ">

            <h2 class="
                    font-semibold
                    text-slate-950
                ">
                Documentos de origen
            </h2>

            <p class="
                    mt-1
                    text-sm
                    text-slate-500
                ">
                Selecciona el XML y PDF correspondientes
                a la factura de la unidad.
            </p>

        </div>


        <div class="
                grid
                gap-5
                p-6
                lg:grid-cols-2
            ">

            {{-- XML --}}

            <label class="
                    relative
                    flex
                    min-h-48
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
                    hover:bg-blue-50/30
                ">

                <input type="file" wire:model="xmlFile" accept=".xml,text/xml,application/xml" class="sr-only">

                <p class="
                        text-sm
                        font-semibold
                        text-slate-800
                    ">
                    Archivo XML
                </p>

                <p class="
                        mt-2
                        text-sm
                        text-slate-500
                    ">
                    Selecciona el CFDI original.
                </p>

                @if ($xmlFile)

                    <div class="
                                                mt-4
                                                max-w-full
                                                rounded-lg
                                                bg-white
                                                px-3
                                                py-2
                                                text-xs
                                                font-medium
                                                text-slate-700
                                                shadow-sm
                                            ">
                        {{ $xmlFile->getClientOriginalName() }}
                    </div>

                @endif

            </label>


            {{-- PDF --}}

            <label class="
                    relative
                    flex
                    min-h-48
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
                    hover:bg-blue-50/30
                ">

                <input type="file" wire:model="pdfFile" accept=".pdf,application/pdf" class="sr-only">

                <p class="
                        text-sm
                        font-semibold
                        text-slate-800
                    ">
                    Archivo PDF
                </p>

                <p class="
                        mt-2
                        text-sm
                        text-slate-500
                    ">
                    Selecciona la representación PDF.
                </p>

                @if ($pdfFile)

                    <div class="
                                                mt-4
                                                max-w-full
                                                rounded-lg
                                                bg-white
                                                px-3
                                                py-2
                                                text-xs
                                                font-medium
                                                text-slate-700
                                                shadow-sm
                                            ">
                        {{ $pdfFile->getClientOriginalName() }}
                    </div>

                @endif

            </label>

        </div>


        <div class="px-6 pb-6">

            @error('xmlFile')
                <p class="mb-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('pdfFile')
                <p class="mb-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror


            <button type="button" wire:click="analyze" wire:loading.attr="disabled"
                wire:target="analyze,xmlFile,pdfFile" class="
                    inline-flex
                    items-center
                    justify-center
                    rounded-xl
                    bg-slate-950
                    px-5
                    py-3
                    text-sm
                    font-semibold
                    text-white
                    transition
                    hover:bg-slate-800
                    disabled:cursor-not-allowed
                    disabled:opacity-50
                ">
                <span wire:loading.remove wire:target="analyze">
                    Analizar documentos
                </span>

                <span wire:loading wire:target="analyze">
                    Analizando...
                </span>
            </button>

        </div>

    </section>


    {{-- PREVIEW --}}

    @if ($analyzed && !empty($preview))

        <section class="
                                    overflow-hidden
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    bg-white
                                    shadow-sm
                                ">

            <div class="
                                        flex
                                        flex-col
                                        gap-3
                                        border-b
                                        border-slate-200
                                        px-6
                                        py-5
                                        sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                    ">

                <div>

                    <h2 class="
                                                font-semibold
                                                text-slate-950
                                            ">
                        Vista previa
                    </h2>

                    <p class="
                        mt-1
                        text-sm
                        text-slate-500
                    ">
                        Revisa, corrige o completa los datos
                        de la unidad antes de importar.
                    </p>

                </div>

                <span class="
                                            inline-flex
                                            w-fit
                                            rounded-full
                                            bg-blue-50
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-blue-700
                                        ">
                    {{ $preview['supplier']['name'] }}
                </span>

            </div>


            <div class="
                                        grid
                                        gap-6
                                        p-6
                                        xl:grid-cols-[0.8fr_1.2fr]
                                    ">

                {{-- FACTURA --}}

                <div class="
                                            rounded-xl
                                            border
                                            border-slate-200
                                            bg-slate-50
                                            p-5
                                        ">

                    <p class="
                                                text-xs
                                                font-semibold
                                                uppercase
                                                tracking-wider
                                                text-slate-400
                                            ">
                        Factura
                    </p>

                    <div class="
                    mt-4
                    rounded-lg
                    bg-slate-200/60
                    px-3
                    py-2
                    text-xs
                    text-slate-600
                ">
                        Información fiscal obtenida directamente
                        del CFDI. Estos datos no son editables.
                    </div>

                    <dl class="
                                                mt-5
                                                space-y-4
                                                text-sm
                                            ">

                        <div>
                            <dt class="text-slate-500">
                                Serie / Folio
                            </dt>

                            <dd class="
                                                        mt-1
                                                        font-medium
                                                        text-slate-950
                                                    ">
                                {{ $preview['invoice']['series'] ?? '—' }}
                                {{ $preview['invoice']['folio'] ?? '' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-slate-500">
                                Receptor
                            </dt>

                            <dd class="
                                                        mt-1
                                                        font-medium
                                                        text-slate-950
                                                    ">
                                {{ $preview['invoice']['receiver_name'] ?? '—' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-slate-500">
                                UUID
                            </dt>

                            <dd class="
                                                        mt-1
                                                        break-all
                                                        font-mono
                                                        text-xs
                                                        text-slate-700
                                                    ">
                                {{ $preview['invoice']['uuid'] ?? '—' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-slate-500">
                                Total
                            </dt>

                            <dd class="
                                                        mt-1
                                                        text-lg
                                                        font-semibold
                                                        text-slate-950
                                                    ">
                                {{ $preview['invoice']['currency'] ?? '' }}
                                {{ $preview['invoice']['total'] ?? '—' }}
                            </dd>
                        </div>

                    </dl>


                    @if (!$preview['files']['names_match'])

                        <div class="
                                                                        mt-5
                                                                        rounded-lg
                                                                        border
                                                                        border-amber-200
                                                                        bg-amber-50
                                                                        px-4
                                                                        py-3
                                                                        text-xs
                                                                        text-amber-800
                                                                    ">
                            Los nombres del XML y PDF no coinciden.
                            Verifica que ambos correspondan a la
                            misma factura.
                        </div>

                    @endif

                </div>


                {{-- UNIDADES EDITABLES --}}

                <div class="space-y-5">

                    <div class="
                                rounded-xl
                                border
                                border-blue-200
                                bg-blue-50
                                px-4
                                py-3
                                text-sm
                                text-blue-800
                            ">
                        Puedes corregir o completar los datos de la unidad
                        antes de confirmar la importación.

                        Los datos fiscales de la factura no serán modificados.
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
                                                                            rounded-2xl
                                                                            border
                                                                            {{ $isModified
                        ? 'border-blue-300 bg-blue-50/20'
                        : (
                            ($previewUnit['duplicate'] ?? false)
                            ? 'border-red-300 bg-red-50/30'
                            : 'border-slate-200'
                        )
                                                                            }}
                                                                            p-5
                                                                        ">

                                {{-- HEADER UNIDAD --}}

                                <div class="
                                                                                flex
                                                                                flex-col
                                                                                gap-3
                                                                                sm:flex-row
                                                                                sm:items-start
                                                                                sm:justify-between
                                                                            ">

                                    <div>

                                        <p class="
                                                                                        text-xs
                                                                                        font-semibold
                                                                                        uppercase
                                                                                        tracking-wider
                                                                                        text-slate-400
                                                                                    ">
                                            Unidad {{ $index + 1 }}
                                        </p>

                                        <h3 class="
                                                                                        mt-1
                                                                                        text-lg
                                                                                        font-semibold
                                                                                        text-slate-950
                                                                                    ">
                                            {{ $unit['brand'] ?: 'Marca por revisar' }}

                                            @if ($unit['model'])
                                                · {{ $unit['model'] }}
                                            @endif
                                        </h3>

                                    </div>


                                    <div class="
                                                                                    flex
                                                                                    flex-wrap
                                                                                    gap-2
                                                                                ">

                                        @if ($isModified)

                                            <span class="
                                                                                                                w-fit
                                                                                                                rounded-full
                                                                                                                bg-blue-100
                                                                                                                px-3
                                                                                                                py-1
                                                                                                                text-xs
                                                                                                                font-semibold
                                                                                                                text-blue-700
                                                                                                            ">
                                                Modificado manualmente
                                            </span>

                                        @endif


                                        @if ($previewUnit['duplicate'] ?? false)

                                            <span class="
                                                                                                                w-fit
                                                                                                                rounded-full
                                                                                                                bg-red-100
                                                                                                                px-3
                                                                                                                py-1
                                                                                                                text-xs
                                                                                                                font-semibold
                                                                                                                text-red-700
                                                                                                            ">
                                                VIN original ya registrado
                                            </span>

                                        @elseif ($previewUnit['requires_review'] ?? false)

                                            <span class="
                                                                                                                w-fit
                                                                                                                rounded-full
                                                                                                                bg-amber-100
                                                                                                                px-3
                                                                                                                py-1
                                                                                                                text-xs
                                                                                                                font-semibold
                                                                                                                text-amber-700
                                                                                                            ">
                                                Requiere revisión
                                            </span>

                                        @else

                                            <span class="
                                                                                                                w-fit
                                                                                                                rounded-full
                                                                                                                bg-emerald-100
                                                                                                                px-3
                                                                                                                py-1
                                                                                                                text-xs
                                                                                                                font-semibold
                                                                                                                text-emerald-700
                                                                                                            ">
                                                Datos detectados
                                            </span>

                                        @endif

                                    </div>

                                </div>


                                {{-- FORMULARIO --}}

                                <div class="
                                                                                mt-6
                                                                                grid
                                                                                gap-5
                                                                                md:grid-cols-2
                                                                            ">

                                    {{-- VIN --}}

                                    <div class="md:col-span-2">

                                        <label class="
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            VIN *
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.vin" maxlength="50" class="
                                                                                        mt-2
                                                                                        w-full
                                                                                        rounded-xl
                                                                                        border
                                                                                        border-slate-300
                                                                                        px-4
                                                                                        py-3
                                                                                        font-mono
                                                                                        text-sm
                                                                                        uppercase
                                                                                        outline-none
                                                                                        transition
                                                                                        focus:border-blue-500
                                                                                        focus:ring-4
                                                                                        focus:ring-blue-500/10
                                                                                    ">

                                        @error("editableUnits.$index.vin")

                                            <p class="
                                                                                                                mt-1
                                                                                                                text-xs
                                                                                                                text-red-600
                                                                                                            ">
                                                {{ $message }}
                                            </p>

                                        @enderror


                                        @if (
                                                ($originalUnit['vin'] ?? null)
                                                !== ($unit['vin'] ?? null)
                                            )

                                            <p class="
                                                                                                                mt-2
                                                                                                                text-xs
                                                                                                                text-blue-600
                                                                                                            ">
                                                Detectado originalmente:
                                                {{ $originalUnit['vin'] ?? '—' }}
                                            </p>

                                        @endif

                                    </div>


                                    {{-- MARCA --}}

                                    <div>

                                        <label class="
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            Marca *
                                        </label>

                                        <select wire:model="editableUnits.{{ $index }}.brand" class="
                                                                                        mt-2
                                                                                        w-full
                                                                                        rounded-xl
                                                                                        border
                                                                                        border-slate-300
                                                                                        bg-white
                                                                                        px-4
                                                                                        py-3
                                                                                        text-sm
                                                                                        outline-none
                                                                                        focus:border-blue-500
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

                                            <p class="mt-2 text-xs text-blue-600">
                                                Detectado:
                                                {{ $originalUnit['brand'] ?? '—' }}
                                            </p>

                                        @endif

                                    </div>


                                    {{-- AÑO --}}

                                    <div>

                                        <label class="
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            Año
                                        </label>

                                        <input type="number" wire:model="editableUnits.{{ $index }}.year" min="1900"
                                            max="{{ now()->year + 2 }}" class="
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
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            Modelo
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.model" maxlength="255"
                                            placeholder="Modelo de la unidad" class="
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

                                            <p class="mt-2 text-xs text-blue-600">
                                                Detectado originalmente:
                                                {{ $originalUnit['model'] ?? '—' }}
                                            </p>

                                        @endif

                                    </div>


                                    {{-- VERSIÓN --}}

                                    <div class="md:col-span-2">

                                        <label class="
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            Versión
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.version" maxlength="255"
                                            placeholder="Versión, edición o configuración" class="
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
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            Color exterior
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.exterior_color" maxlength="150"
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
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            Color interior
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.interior_color" maxlength="150"
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
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            Número de motor
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.engine_number" maxlength="100"
                                            placeholder="Número de motor" class="
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
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            Pedimento
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.pedimento" maxlength="100" class="
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
                                                                                        text-sm
                                                                                        font-medium
                                                                                        text-slate-700
                                                                                    ">
                                            Orden de compra
                                        </label>

                                        <input type="text" wire:model="editableUnits.{{ $index }}.purchase_order" maxlength="100"
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
                                                                                    ">

                                        @error("editableUnits.$index.purchase_order")

                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>

                                </div>


                                {{-- RESUMEN MODIFICACIONES --}}

                                @if ($isModified)

                                    <div class="
                                                                                                        mt-5
                                                                                                        rounded-xl
                                                                                                        border
                                                                                                        border-blue-200
                                                                                                        bg-blue-50
                                                                                                        px-4
                                                                                                        py-3
                                                                                                        text-xs
                                                                                                        text-blue-700
                                                                                                    ">
                                        Esta unidad contiene información
                                        modificada manualmente.

                                        Los valores originales detectados
                                        se conservarán como parte de la
                                        trazabilidad de importación.
                                    </div>

                                @endif

                            </article>

                    @endforeach

                </div>

            </div>


            <div class="
                                        border-t
                                        border-slate-200
                                        bg-slate-50
                                        px-6
                                        py-5
                                    ">

                @if ($preview['requires_review'])

                    <label class="
                                                                    mb-5
                                                                    flex
                                                                    items-start
                                                                    gap-3
                                                                    text-sm
                                                                    text-slate-700
                                                                ">

                        <input type="checkbox" wire:model="reviewAccepted" class="
                                                                        mt-0.5
                                                                        h-4
                                                                        w-4
                                                                        rounded
                                                                        border-slate-300
                                                                    ">

                        <span>
                            Revisé los datos de la unidad, realicé
                            las correcciones necesarias y confirmo
                            que la información es correcta.
                        </span>

                    </label>

                @endif


                <button type="button" wire:click="confirmImport" wire:loading.attr="disabled" wire:target="confirmImport"
                    class="
                                            inline-flex
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-blue-600
                                            px-5
                                            py-3
                                            text-sm
                                            font-semibold
                                            text-white
                                            transition
                                            hover:bg-blue-700
                                            disabled:cursor-not-allowed
                                            disabled:opacity-50
                                        ">

                    <span wire:loading.remove wire:target="confirmImport">
                        Confirmar e importar
                    </span>

                    <span wire:loading wire:target="confirmImport">
                        Importando...
                    </span>

                </button>

            </div>

        </section>

    @endif

</div>