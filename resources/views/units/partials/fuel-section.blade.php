@php

    $fuelLoads =
        $unit
            ->fuelLoads()
            ->with([
                'transferAssignment',
                'registeredBy',
            ])
            ->orderByDesc('fueled_at')
            ->get();

@endphp


<section class="
        overflow-hidden
        rounded-3xl
        border
        border-slate-200/80
        bg-white
        shadow-[0_8px_30px_rgba(15,23,42,0.04)]
    ">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

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

        <div>

            <div class="
                    flex
                    items-center
                    gap-2
                ">

                <span class="
                        h-2.5
                        w-2.5
                        rounded-full
                        bg-teal-500
                    "></span>

                <p class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.14em]
                        text-teal-700
                    ">
                    Traslado
                </p>

            </div>


            <h2 class="
                    mt-2
                    text-xl
                    font-semibold
                    tracking-tight
                    text-slate-950
                ">
                Combustible
            </h2>


            <p class="
                    mt-1
                    text-sm
                    text-slate-500
                ">
                Registro de carga de gasolina y evidencia del ticket.
            </p>

        </div>


        @if ($fuelLoads->isNotEmpty())

            <span class="
                        inline-flex
                        w-fit
                        items-center
                        gap-2
                        rounded-full
                        bg-emerald-50
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        text-emerald-700
                    ">

                <span class="
                            h-2
                            w-2
                            rounded-full
                            bg-emerald-500
                        "></span>

                Registrado

            </span>

        @else

            <span class="
                        inline-flex
                        w-fit
                        items-center
                        gap-2
                        rounded-full
                        bg-amber-50
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        text-amber-700
                    ">

                <span class="
                            h-2
                            w-2
                            rounded-full
                            bg-amber-500
                        "></span>

                Pendiente

            </span>

        @endif

    </div>



    {{-- ========================================================= --}}
    {{-- CONTENIDO --}}
    {{-- ========================================================= --}}

    <div class="
            p-6
            lg:p-7
        ">

        @forelse ($fuelLoads as $fuelLoad)

                @php

                    $hasFuel =
                        (float) $fuelLoad->amount > 0;

                @endphp


                <article class="
                            rounded-2xl
                            border

                            {{
                $hasFuel
                ? 'border-emerald-100 bg-emerald-50/30'
                : 'border-amber-100 bg-amber-50/40'
                            }}

                            p-5
                        ">

                    {{-- ================================================= --}}
                    {{-- ESTADO + IMPORTE --}}
                    {{-- ================================================= --}}

                    <div class="
                                flex
                                flex-col
                                gap-5
                                sm:flex-row
                                sm:items-start
                                sm:justify-between
                            ">

                        <div>

                            @if ($hasFuel)

                                <span class="
                                                inline-flex
                                                rounded-full
                                                bg-emerald-100
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-emerald-700
                                            ">
                                    Carga realizada
                                </span>

                            @else

                                <span class="
                                                inline-flex
                                                rounded-full
                                                bg-amber-100
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-amber-800
                                            ">
                                    Sin carga de gasolina
                                </span>

                            @endif


                            <p class="
                                        mt-4
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    ">
                                Importe
                            </p>


                            <p class="
                                        mt-1
                                        text-3xl
                                        font-bold
                                        tracking-tight
                                        text-slate-950
                                    ">
                                ${{ number_format(
                (float) $fuelLoad->amount,
                2
            ) }}
                            </p>

                        </div>


                        <div class="
                                    grid
                                    gap-4
                                    text-sm
                                    sm:min-w-[260px]
                                ">

                            {{-- FECHA --}}

                            <div>

                                <p class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        ">
                                    Registrado
                                </p>


                                <p class="
                                            mt-1
                                            font-semibold
                                            text-slate-800
                                        ">
                                    {{ \App\Support\DateHelper::format(
                $fuelLoad->fueled_at
            ) }}
                                </p>

                            </div>


                            {{-- RESPONSABLE --}}

                            <div>

                                <p class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        ">
                                    Registrado por
                                </p>


                                <p class="
                                            mt-1
                                            font-semibold
                                            text-slate-800
                                        ">
                                    {{
                $fuelLoad->registered_by_name
                ?? $fuelLoad->registeredBy?->name
                ?? '—'
                                        }}
                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- MOTIVO CUANDO EL IMPORTE ES $0 --}}
                    {{-- ================================================= --}}

                    @if (
                            !$hasFuel
                            && $fuelLoad->no_fuel_reason
                        )

                        <div class="
                                        mt-5
                                        rounded-xl
                                        border
                                        border-amber-200
                                        bg-white
                                        p-4
                                    ">

                            <p class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-amber-700
                                        ">
                                Motivo de no carga
                            </p>


                            <p class="
                                            mt-1
                                            text-sm
                                            font-semibold
                                            text-slate-900
                                        ">
                                {{ $fuelLoad
                        ->no_fuel_reason
                        ->label()
                                        }}
                            </p>


                            @if ($fuelLoad->reason_notes)

                                <p class="
                                                    mt-2
                                                    whitespace-pre-line
                                                    text-sm
                                                    leading-6
                                                    text-slate-600
                                                ">
                                    {{ $fuelLoad->reason_notes }}
                                </p>

                            @endif

                        </div>

                    @endif



                    {{-- ================================================= --}}
                    {{-- TICKET --}}
                    {{-- ================================================= --}}

                    @if ($fuelLoad->ticket_storage_path)

                            <div class="
                                            mt-5
                                            flex
                                            flex-col
                                            gap-3
                                            border-t
                                            border-slate-200/70
                                            pt-5
                                            sm:flex-row
                                            sm:items-center
                                            sm:justify-between
                                        ">

                                <div class="min-w-0">

                                    <p class="
                                                    text-xs
                                                    font-semibold
                                                    text-slate-900
                                                ">
                                        Ticket de gasolina
                                    </p>


                                    <p class="
                                                    mt-1
                                                    max-w-md
                                                    truncate
                                                    text-xs
                                                    text-slate-500
                                                ">
                                        {{
                            $fuelLoad
                                ->ticket_original_filename
                            ?: 'Evidencia de combustible'
                                                }}
                                    </p>

                                </div>


                                <a href="{{ route(
                            'fuel.ticket',
                            $fuelLoad
                        ) }}" target="_blank" rel="noopener noreferrer" class="
                                                inline-flex
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-xl
                                                border
                                                border-teal-200
                                                bg-white
                                                px-4
                                                py-2.5
                                                text-sm
                                                font-semibold
                                                text-teal-700
                                                shadow-sm
                                                transition
                                                hover:border-teal-300
                                                hover:bg-teal-50
                                            ">

                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6Z" />

                                        <circle cx="12" cy="12" r="3" />
                                    </svg>

                                    Ver ticket

                                </a>

                            </div>

                    @endif



                    {{-- ================================================= --}}
                    {{-- OBSERVACIONES --}}
                    {{-- ================================================= --}}

                    @if ($fuelLoad->observations)

                        <div class="
                                        mt-5
                                        border-t
                                        border-slate-200/70
                                        pt-5
                                    ">

                            <p class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        ">
                                Observaciones
                            </p>


                            <p class="
                                            mt-2
                                            whitespace-pre-line
                                            text-sm
                                            leading-6
                                            text-slate-700
                                        ">
                                {{ $fuelLoad->observations }}
                            </p>

                        </div>

                    @endif

                </article>


                @unless ($loop->last)

                    <div class="
                                    my-5
                                    border-t
                                    border-slate-100
                                "></div>

                @endunless


        @empty

            {{-- ================================================= --}}
            {{-- SIN REGISTRO --}}
            {{-- ================================================= --}}

            <div class="
                        flex
                        flex-col
                        items-center
                        justify-center
                        rounded-2xl
                        border
                        border-dashed
                        border-slate-300
                        bg-slate-50
                        px-6
                        py-10
                        text-center
                    ">

                <div class="
                            flex
                            h-11
                            w-11
                            items-center
                            justify-center
                            rounded-full
                            bg-slate-100
                            text-slate-500
                        ">
                    ⛽
                </div>


                <p class="
                            mt-3
                            font-semibold
                            text-slate-800
                        ">
                    Combustible pendiente
                </p>


                <p class="
                            mt-1
                            max-w-md
                            text-sm
                            leading-6
                            text-slate-500
                        ">
                    El trasladista todavía no ha registrado
                    información de combustible para esta unidad.
                </p>

            </div>

        @endforelse

    </div>

</section>