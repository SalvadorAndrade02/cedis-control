<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- CABECERA --}}
    {{-- ========================================================= --}}

    <section class="
            relative
            overflow-hidden
            rounded-3xl
            bg-[#0B1220]
            p-6
            text-white
            shadow-[0_18px_50px_rgba(15,23,42,0.16)]
            sm:p-7
        ">

        <div class="
                pointer-events-none
                absolute
                -right-20
                -top-20
                h-60
                w-60
                rounded-full
                bg-teal-500/10
            "></div>

        <div class="relative">

            <p class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.18em]
                    text-teal-400
                ">
                Operación de traslado
            </p>

            <h1 class="
                    mt-2
                    text-2xl
                    font-bold
                    tracking-tight
                    text-white
                    sm:text-3xl
                ">
                Hola, {{ auth()->user()->name }}
            </h1>

            <p class="
                    mt-2
                    max-w-2xl
                    text-sm
                    leading-6
                    text-slate-400
                ">
                Consulta tus unidades asignadas y atiende
                los registros de gasolina pendientes.
            </p>

            <div class="mt-5">

                <a href="{{ route('transfers.mine') }}" class="
                        inline-flex
                        items-center
                        justify-center
                        gap-2
                        rounded-xl
                        bg-teal-500
                        px-4
                        py-2.5
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-teal-400
                    ">
                    Ver mis traslados
                    <span>→</span>
                </a>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- INDICADORES --}}
    {{-- ========================================================= --}}

    <div class="
            grid
            gap-4
            sm:grid-cols-3
        ">

        {{-- ACTIVOS --}}
        <article class="
                rounded-2xl
                border
                border-slate-200
                bg-white
                p-5
                shadow-sm
            ">

            <p class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-wider
                    text-slate-400
                ">
                Traslados activos
            </p>

            <p class="
                    mt-3
                    text-3xl
                    font-bold
                    tracking-tight
                    text-slate-950
                ">
                {{ $activeTransfers }}
            </p>

            <p class="
                    mt-2
                    text-sm
                    text-slate-500
                ">
                Unidades actualmente asignadas.
            </p>

        </article>


        {{-- PENDIENTES DE GASOLINA --}}
        <article class="
                rounded-2xl
                border
                border-amber-200
                bg-amber-50/60
                p-5
                shadow-sm
            ">

            <div class="
                    flex
                    items-start
                    justify-between
                    gap-4
                ">

                <div>

                    <p class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-wider
                            text-amber-700
                        ">
                        Gasolina pendiente
                    </p>

                    <p class="
                            mt-3
                            text-3xl
                            font-bold
                            tracking-tight
                            text-amber-950
                        ">
                        {{ $pendingFuel }}
                    </p>

                </div>


                @if ($pendingFuel > 0)

                    <div class="
                                flex
                                h-10
                                w-10
                                items-center
                                justify-center
                                rounded-full
                                bg-amber-100
                                font-bold
                                text-amber-700
                            ">
                        !
                    </div>

                @endif

            </div>

            <p class="
                    mt-2
                    text-sm
                    text-amber-700
                ">
                Requieren registro de combustible.
            </p>

        </article>


        {{-- REGISTRADOS --}}
        <article class="
                rounded-2xl
                border
                border-emerald-200
                bg-emerald-50/50
                p-5
                shadow-sm
            ">

            <p class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-wider
                    text-emerald-700
                ">
                Combustible registrado
            </p>

            <p class="
                    mt-3
                    text-3xl
                    font-bold
                    tracking-tight
                    text-emerald-950
                ">
                {{ $fuelRegistered }}
            </p>

            <p class="
                    mt-2
                    text-sm
                    text-emerald-700
                ">
                Traslados activos ya documentados.
            </p>

        </article>

    </div>


    {{-- ========================================================= --}}
    {{-- PENDIENTES --}}
    {{-- ========================================================= --}}

    <section class="
            overflow-hidden
            rounded-3xl
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
                border-slate-100
                px-5
                py-5
                sm:flex-row
                sm:items-center
                sm:justify-between
                sm:px-6
            ">

            <div>

                <p class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.14em]
                        text-amber-600
                    ">
                    Atención requerida
                </p>

                <h2 class="
                        mt-1
                        text-lg
                        font-semibold
                        text-slate-950
                    ">
                    Pendientes de gasolina
                </h2>

                <p class="
                        mt-1
                        text-sm
                        text-slate-500
                    ">
                    Unidades asignadas que todavía no cuentan
                    con registro de combustible.
                </p>

            </div>


            @if ($pendingFuel > 0)

                <span class="
                            w-fit
                            rounded-full
                            bg-amber-100
                            px-3
                            py-1
                            text-xs
                            font-semibold
                            text-amber-800
                        ">
                    {{ $pendingFuel }} pendiente(s)
                </span>

            @endif

        </div>


        <div class="divide-y divide-slate-100">

            @forelse ($pendingFuelAssignments as $assignment)

                        @php
                            $unit = $assignment->unit;
                        @endphp


                        <article class="
                                    px-5
                                    py-5
                                    sm:px-6
                                ">

                            <div class="
                                        flex
                                        flex-col
                                        gap-5
                                        lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                    ">

                                <div class="min-w-0">

                                    <span class="
                                                inline-flex
                                                rounded-full
                                                bg-amber-50
                                                px-2.5
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-amber-700
                                            ">
                                        Gasolina pendiente
                                    </span>


                                    <h3 class="
                                                mt-3
                                                font-semibold
                                                text-slate-950
                                            ">
                                        {{ $unit->brand?->name ?? 'Unidad' }}

                                        @if ($unit->model)
                                            · {{ $unit->model }}
                                        @endif
                                    </h3>


                                    <p class="
                                                mt-1
                                                break-all
                                                font-mono
                                                text-sm
                                                text-slate-500
                                            ">
                                        {{ $unit->vin }}
                                    </p>


                                    <div class="
                                                mt-3
                                                flex
                                                flex-wrap
                                                gap-x-5
                                                gap-y-2
                                                text-xs
                                                text-slate-500
                                            ">

                                        <span>
                                            {{ $assignment->origin_name ?: 'CEDIS' }}
                                            →
                                            {{ $assignment->destination_name ?: 'Destino no especificado' }}
                                        </span>

                                        <span>
                                            Asignado:
                                            {{ \App\Support\DateHelper::format(
                    $assignment->assigned_at
                ) }}
                                        </span>

                                    </div>

                                </div>


                                <div class="shrink-0">

                                    <a href="{{ route(
                    'fuel.create',
                    $assignment
                ) }}" class="
                                                inline-flex
                                                w-full
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-xl
                                                bg-teal-600
                                                px-5
                                                py-3
                                                text-sm
                                                font-semibold
                                                text-white
                                                shadow-sm
                                                transition
                                                hover:bg-teal-700
                                                lg:w-auto
                                            ">
                                        Registrar gasolina
                                        <span>→</span>
                                    </a>

                                </div>

                            </div>

                        </article>

            @empty

                <div class="
                            px-6
                            py-12
                            text-center
                        ">

                    <div class="
                                mx-auto
                                flex
                                h-11
                                w-11
                                items-center
                                justify-center
                                rounded-full
                                bg-emerald-100
                                text-emerald-700
                            ">
                        ✓
                    </div>

                    <p class="
                                mt-3
                                font-semibold
                                text-slate-800
                            ">
                        Sin pendientes de gasolina
                    </p>

                    <p class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                        Todas tus unidades activas están documentadas.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- TRASLADOS ACTIVOS --}}
    {{-- ========================================================= --}}

    @if ($recentTransfers->isNotEmpty())

        <section class="
                    overflow-hidden
                    rounded-3xl
                    border
                    border-slate-200
                    bg-white
                    shadow-sm
                ">

            <div class="
                        flex
                        items-center
                        justify-between
                        gap-4
                        border-b
                        border-slate-100
                        px-5
                        py-5
                        sm:px-6
                    ">

                <div>

                    <p class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-[0.14em]
                                text-teal-600
                            ">
                        Actividad
                    </p>

                    <h2 class="
                                mt-1
                                font-semibold
                                text-slate-950
                            ">
                        Mis traslados activos
                    </h2>

                </div>


                <a href="{{ route('transfers.mine') }}" class="
                            text-sm
                            font-semibold
                            text-teal-700
                            hover:text-teal-800
                        ">
                    Ver todos →
                </a>

            </div>


            <div class="divide-y divide-slate-100">

                @foreach ($recentTransfers as $assignment)

                    @php

                        $hasFuel =
                            $assignment
                                ->fuelLoads
                                ->isNotEmpty();

                        $unit =
                            $assignment->unit;

                    @endphp


                    <div class="
                                    flex
                                    flex-col
                                    gap-3
                                    px-5
                                    py-4
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-between
                                    sm:px-6
                                ">

                        <div>

                            <p class="
                                            text-sm
                                            font-semibold
                                            text-slate-900
                                        ">
                                {{ $unit->brand?->name ?? 'Unidad' }}

                                @if ($unit->model)
                                    · {{ $unit->model }}
                                @endif
                            </p>

                            <p class="
                                            mt-1
                                            font-mono
                                            text-xs
                                            text-slate-500
                                        ">
                                {{ $unit->vin }}
                            </p>

                        </div>


                        @if ($hasFuel)

                            <span class="
                                                w-fit
                                                rounded-full
                                                bg-emerald-50
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-emerald-700
                                            ">
                                ✓ Combustible registrado
                            </span>

                        @else

                            <span class="
                                                w-fit
                                                rounded-full
                                                bg-amber-50
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-amber-700
                                            ">
                                Pendiente de gasolina
                            </span>

                        @endif

                    </div>

                @endforeach

            </div>

        </section>

    @endif

</div>