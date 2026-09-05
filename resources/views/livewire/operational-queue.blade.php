<div class="space-y-6">

    {{-- RESUMEN --}}

    <div
        class="
            grid
            gap-4
            sm:grid-cols-2
        ">

        <article
            class="
                rounded-2xl
                border
                border-slate-200
                bg-white
                p-6
                shadow-sm
            ">

            <p
                class="
                    text-sm
                    font-medium
                    text-slate-500
                ">
                Pendientes
            </p>

            <p
                class="
                    mt-3
                    text-4xl
                    font-semibold
                    tracking-tight
                    text-slate-950
                ">
                {{ $units->total() }}
            </p>

        </article>


        <article
            class="
                rounded-2xl
                border
                border-slate-200
                bg-white
                p-6
                shadow-sm
            ">

            <p
                class="
                    text-sm
                    font-medium
                    text-slate-500
                ">
                Completadas hoy
            </p>

            <p
                class="
                    mt-3
                    text-4xl
                    font-semibold
                    tracking-tight
                    text-emerald-600
                ">
                {{ $completedToday }}
            </p>

        </article>

    </div>


    {{-- BUSCADOR --}}

    <section
        class="
            rounded-2xl
            border
            border-slate-200
            bg-white
            p-5
            shadow-sm
        ">

        <label
            class="
                text-sm
                font-medium
                text-slate-700
            ">
            Buscar unidad
        </label>

        <input
            type="search"
            wire:model.live.debounce.400ms="search"
            placeholder="VIN, marca o modelo..."
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
                transition
                focus:border-blue-500
                focus:ring-4
                focus:ring-blue-500/10
            ">

    </section>


    {{-- COLA --}}

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
                {{ $title }}
            </h2>

            <p
                class="
                    mt-1
                    text-sm
                    text-slate-500
                ">
                {{ $description }}
            </p>

        </div>


        <div
            class="
                divide-y
                divide-slate-100
            ">

            @forelse ($units as $unit)

            @php

            $previousMilestone =
            $previousStage
            ? $unit
            ->milestones
            ->first(
            fn ($milestone) =>
            $milestone->stage
            === $previousStage
            )
            : null;

            @endphp


            <article
                class="
                        p-5
                        transition
                        hover:bg-slate-50
                        lg:p-6
                    ">

                <div
                    class="
                            flex
                            flex-col
                            gap-5
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
                        ">

                    {{-- DATOS --}}

                    <div class="min-w-0">

                        <div
                            class="
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-2
                                ">

                            <span
                                class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-blue-600
                                    ">
                                {{ $unit->brand?->name ?? 'Sin marca' }}
                            </span>

                            <span
                                class="
                                        text-xs
                                        text-slate-300
                                    ">
                                •
                            </span>

                            <span
                                class="
                                        text-xs
                                        text-slate-500
                                    ">
                                {{ $unit->year ?? 'Año sin identificar' }}
                            </span>

                        </div>


                        <h3
                            class="
                                    mt-2
                                    text-lg
                                    font-semibold
                                    text-slate-950
                                ">
                            {{ $unit->model ?: 'Sin modelo' }}
                        </h3>


                        <p
                            class="
                                    mt-2
                                    font-mono
                                    text-sm
                                    text-slate-500
                                ">
                            {{ $unit->vin }}
                        </p>


                        @if ($unit->exterior_color)

                        <p
                            class="
                                        mt-2
                                        text-sm
                                        text-slate-500
                                    ">
                            Color:
                            {{ $unit->exterior_color }}
                        </p>

                        @endif


                        {{-- INFORMACIÓN DE ETAPA PREVIA --}}

                        @if (
                        $previousMilestone
                        && $previousMilestone->completed_at
                        )

                        <div
                            class="
                                        mt-4
                                        flex
                                        flex-wrap
                                        gap-x-5
                                        gap-y-2
                                        text-xs
                                        text-slate-500
                                    ">

                            <span>
                                Etapa anterior:
                                {{ \App\Support\DateHelper::format(
                                            $previousMilestone->completed_at
                                        ) }}
                            </span>

                            @if ($previousMilestone->completedBy)

                            <span>
                                Por:
                                {{ $previousMilestone
                                                ->completedBy
                                                ->name
                                            }}
                            </span>

                            @endif

                        </div>

                        @elseif ($type === 'arrival')

                        <p
                            class="
                                        mt-4
                                        text-xs
                                        text-slate-500
                                    ">
                            Importada:
                            {{ \App\Support\DateHelper::format(
                                        $unit->created_at
                                    ) }}
                        </p>

                        @endif

                    </div>


                    {{-- ACCIONES --}}

                    <div
                        class="
                                flex
                                shrink-0
                                flex-col
                                gap-2
                                sm:flex-row
                            ">

                        <a
                            href="{{ route(
                                    'units.show',
                                    $unit
                                ) }}"
                            class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-xl
                                    border
                                    border-slate-300
                                    px-4
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-slate-700
                                    transition
                                    hover:bg-slate-100
                                ">
                            Ver expediente
                        </a>


                        <a
                            href="{{ route(
                                    'units.show',
                                    $unit
                                ) }}"
                            class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-slate-950
                                    px-4
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-white
                                    transition
                                    hover:bg-slate-800
                                ">
                            {{ $actionLabel }}
                        </a>

                    </div>

                </div>

            </article>

            @empty

            <div
                class="
                        px-6
                        py-16
                        text-center
                    ">

                <p
                    class="
                            text-sm
                            font-medium
                            text-slate-700
                        ">
                    No hay unidades pendientes.
                </p>

                <p
                    class="
                            mt-2
                            text-sm
                            text-slate-500
                        ">
                    Cuando existan unidades para esta etapa
                    aparecerán aquí automáticamente.
                </p>

            </div>

            @endforelse

        </div>


        @if ($units->hasPages())

        <div
            class="
                    border-t
                    border-slate-200
                    px-6
                    py-4
                ">
            {{ $units->links() }}
        </div>

        @endif

    </section>

</div>