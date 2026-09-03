<div class="space-y-6">

    {{-- MENSAJES --}}

    @if ($successMessage)

    <div
        class="
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

    <div
        class="
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

            <h2
                class="
                    font-semibold
                    text-slate-950
                ">
                Documentos de origen
            </h2>

            <p
                class="
                    mt-1
                    text-sm
                    text-slate-500
                ">
                Selecciona el XML y PDF correspondientes
                a la factura de la unidad.
            </p>

        </div>


        <div
            class="
                grid
                gap-5
                p-6
                lg:grid-cols-2
            ">

            {{-- XML --}}

            <label
                class="
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

                <input
                    type="file"
                    wire:model="xmlFile"
                    accept=".xml,text/xml,application/xml"
                    class="sr-only">

                <p
                    class="
                        text-sm
                        font-semibold
                        text-slate-800
                    ">
                    Archivo XML
                </p>

                <p
                    class="
                        mt-2
                        text-sm
                        text-slate-500
                    ">
                    Selecciona el CFDI original.
                </p>

                @if ($xmlFile)

                <div
                    class="
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

            <label
                class="
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

                <input
                    type="file"
                    wire:model="pdfFile"
                    accept=".pdf,application/pdf"
                    class="sr-only">

                <p
                    class="
                        text-sm
                        font-semibold
                        text-slate-800
                    ">
                    Archivo PDF
                </p>

                <p
                    class="
                        mt-2
                        text-sm
                        text-slate-500
                    ">
                    Selecciona la representación PDF.
                </p>

                @if ($pdfFile)

                <div
                    class="
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


            <button
                type="button"
                wire:click="analyze"
                wire:loading.attr="disabled"
                wire:target="analyze,xmlFile,pdfFile"
                class="
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
                <span
                    wire:loading.remove
                    wire:target="analyze">
                    Analizar documentos
                </span>

                <span
                    wire:loading
                    wire:target="analyze">
                    Analizando...
                </span>
            </button>

        </div>

    </section>


    {{-- PREVIEW --}}

    @if ($analyzed && ! empty($preview))

    <section
        class="
                overflow-hidden
                rounded-2xl
                border
                border-slate-200
                bg-white
                shadow-sm
            ">

        <div
            class="
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

                <h2
                    class="
                            font-semibold
                            text-slate-950
                        ">
                    Vista previa
                </h2>

                <p
                    class="
                            mt-1
                            text-sm
                            text-slate-500
                        ">
                    Revisa los datos antes de importar.
                </p>

            </div>

            <span
                class="
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


        <div
            class="
                    grid
                    gap-6
                    p-6
                    xl:grid-cols-[0.8fr_1.2fr]
                ">

            {{-- FACTURA --}}

            <div
                class="
                        rounded-xl
                        border
                        border-slate-200
                        bg-slate-50
                        p-5
                    ">

                <p
                    class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-wider
                            text-slate-400
                        ">
                    Factura
                </p>

                <dl
                    class="
                            mt-5
                            space-y-4
                            text-sm
                        ">

                    <div>
                        <dt class="text-slate-500">
                            Serie / Folio
                        </dt>

                        <dd
                            class="
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

                        <dd
                            class="
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

                        <dd
                            class="
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

                        <dd
                            class="
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


                @if (! $preview['files']['names_match'])

                <div
                    class="
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


            {{-- UNIDADES --}}

            <div class="space-y-4">

                @foreach ($preview['units'] as $unit)

                <article
                    class="
                                rounded-xl
                                border
                                {{ $unit['duplicate']
                                    ? 'border-red-300 bg-red-50/40'
                                    : 'border-slate-200'
                                }}
                                p-5
                            ">

                    <div
                        class="
                                    flex
                                    flex-col
                                    gap-3
                                    sm:flex-row
                                    sm:items-start
                                    sm:justify-between
                                ">

                        <div>

                            <p
                                class="
                                            text-xs
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        ">
                                {{ $unit['brand'] }}
                            </p>

                            <h3
                                class="
                                            mt-1
                                            text-xl
                                            font-semibold
                                            text-slate-950
                                        ">
                                {{ $unit['model'] ?? 'Modelo por revisar' }}
                            </h3>

                        </div>


                        @if ($unit['duplicate'])

                        <span
                            class="
                                            w-fit
                                            rounded-full
                                            bg-red-100
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-red-700
                                        ">
                            VIN ya registrado
                        </span>

                        @elseif ($unit['requires_review'])

                        <span
                            class="
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

                        <span
                            class="
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


                    <div
                        class="
                                    mt-5
                                    grid
                                    gap-x-6
                                    gap-y-4
                                    sm:grid-cols-2
                                    lg:grid-cols-3
                                ">

                        @foreach ([
                        'VIN' => $unit['vin'],
                        'Año' => $unit['year'],
                        'Color exterior' => $unit['exterior_color'],
                        'Color interior' => $unit['interior_color'],
                        'Motor' => $unit['engine_number'],
                        'Pedimento' => $unit['pedimento'],
                        ] as $label => $value)

                        <div>

                            <p
                                class="
                                                text-xs
                                                text-slate-500
                                            ">
                                {{ $label }}
                            </p>

                            <p
                                class="
                                                mt-1
                                                break-words
                                                text-sm
                                                font-medium
                                                text-slate-900
                                            ">
                                {{ $value ?: '—' }}
                            </p>

                        </div>

                        @endforeach

                    </div>

                </article>

                @endforeach

            </div>

        </div>


        <div
            class="
                    border-t
                    border-slate-200
                    bg-slate-50
                    px-6
                    py-5
                ">

            @if ($preview['requires_review'])

            <label
                class="
                            mb-5
                            flex
                            items-start
                            gap-3
                            text-sm
                            text-slate-700
                        ">

                <input
                    type="checkbox"
                    wire:model="reviewAccepted"
                    class="
                                mt-0.5
                                h-4
                                w-4
                                rounded
                                border-slate-300
                            ">

                <span>
                    Revisé los datos detectados y confirmo
                    que corresponden a la unidad mostrada.
                </span>

            </label>

            @endif


            <button
                type="button"
                wire:click="confirmImport"
                wire:loading.attr="disabled"
                wire:target="confirmImport"
                @disabled($preview['has_duplicates'])
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

                <span
                    wire:loading.remove
                    wire:target="confirmImport">
                    Confirmar importación
                </span>

                <span
                    wire:loading
                    wire:target="confirmImport">
                    Importando...
                </span>

            </button>

        </div>

    </section>

    @endif

</div>