<div class="space-y-8">

    {{-- ========================================================= --}}
    {{-- DASHBOARD ADMIN / SUPERVISOR --}}
    {{-- ========================================================= --}}

    @if ($managementMode)

    <section>

        <div
            class="
                    mb-5
                    flex
                    flex-col
                    gap-2
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                ">

            <div>

                <h2
                    class="
                            text-lg
                            font-semibold
                            text-slate-950
                        ">
                    Resumen general
                </h2>

                <p
                    class="
                            mt-1
                            text-sm
                            text-slate-500
                        ">
                    Estado actual de la operación
                    del CEDIS.
                </p>

            </div>

        </div>


        <div
            class="
                    grid
                    gap-4
                    sm:grid-cols-2
                    xl:grid-cols-3
                ">

            {{-- TOTAL --}}

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
                    Total de unidades
                </p>

                <p
                    class="
                            mt-3
                            text-4xl
                            font-semibold
                            tracking-tight
                            text-slate-950
                        ">
                    {{ $managementSummary['total'] }}
                </p>

                <p
                    class="
                            mt-3
                            text-xs
                            text-slate-400
                        ">
                    {{ $managementSummary['importedToday'] }}
                    importada(s) hoy
                </p>

            </article>


            {{-- LLEGADAS --}}

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
                    Pendientes de llegada
                </p>

                <p
                    class="
                            mt-3
                            text-4xl
                            font-semibold
                            tracking-tight
                            text-amber-600
                        ">
                    {{ $managementSummary[
                            'arrivalPending'
                        ] }}
                </p>

                @can('arrival.view')

                <a
                    href="{{ route(
                                'operations.arrivals'
                            ) }}"
                    class="
                                mt-4
                                inline-flex
                                text-sm
                                font-semibold
                                text-blue-600
                                hover:text-blue-700
                            ">
                    Ver llegadas →
                </a>

                @endcan

            </article>


            {{-- ARMADOS --}}

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
                    Pendientes de armado
                </p>

                <p
                    class="
                            mt-3
                            text-4xl
                            font-semibold
                            tracking-tight
                            text-orange-600
                        ">
                    {{ $managementSummary[
                            'assemblyPending'
                        ] }}
                </p>

                @can('assembly.view')

                <a
                    href="{{ route(
                                'operations.assemblies'
                            ) }}"
                    class="
                                mt-4
                                inline-flex
                                text-sm
                                font-semibold
                                text-blue-600
                                hover:text-blue-700
                            ">
                    Ver armados →
                </a>

                @endcan

            </article>


            {{-- ENTREGAS --}}

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
                    Pendientes de entrega
                </p>

                <p
                    class="
                            mt-3
                            text-4xl
                            font-semibold
                            tracking-tight
                            text-violet-600
                        ">
                    {{ $managementSummary[
                            'deliveryPending'
                        ] }}
                </p>

                @can('delivery.view')

                <a
                    href="{{ route(
                                'operations.deliveries'
                            ) }}"
                    class="
                                mt-4
                                inline-flex
                                text-sm
                                font-semibold
                                text-blue-600
                                hover:text-blue-700
                            ">
                    Ver entregas →
                </a>

                @endcan

            </article>


            {{-- EXPEDIENTES COMPLETOS --}}

            <article
                class="
                        rounded-2xl
                        border
                        border-emerald-200
                        bg-emerald-50/40
                        p-6
                        shadow-sm
                    ">

                <p
                    class="
                            text-sm
                            font-medium
                            text-emerald-700
                        ">
                    Expedientes completos
                </p>

                <p
                    class="
                            mt-3
                            text-4xl
                            font-semibold
                            tracking-tight
                            text-emerald-700
                        ">
                    {{ $managementSummary[
                            'completed'
                        ] }}
                </p>

                <p
                    class="
                            mt-3
                            text-xs
                            text-emerald-600
                        ">
                    {{ $managementSummary[
                            'completedToday'
                        ] }}
                    completado(s) hoy
                </p>

            </article>


            {{-- IMPORTADAS HOY --}}

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
                    Importadas hoy
                </p>

                <p
                    class="
                            mt-3
                            text-4xl
                            font-semibold
                            tracking-tight
                            text-blue-600
                        ">
                    {{ $managementSummary[
                            'importedToday'
                        ] }}
                </p>

            </article>

        </div>

    </section>

    @endif



    {{-- ========================================================= --}}
    {{-- ÁREAS OPERATIVAS --}}
    {{-- ========================================================= --}}

    @foreach ($queues as $queue)

    <section
        class="
                overflow-hidden
                rounded-2xl
                border
                border-slate-200
                bg-white
                shadow-sm
            ">

        {{-- HEADER --}}

        <div
            class="
                    border-b
                    border-slate-200
                    px-6
                    py-5
                ">

            <div
                class="
                        flex
                        flex-col
                        gap-4
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
                        {{ $queue['title'] }}
                    </h2>

                    <p
                        class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                        Actividad operativa
                        correspondiente a esta área.
                    </p>

                </div>


                <a
                    href="{{ route(
                            $queue['route']
                        ) }}"
                    class="
                            inline-flex
                            items-center
                            justify-center
                            rounded-xl
                            bg-slate-950
                            px-4
                            py-2.5
                            text-sm
                            font-semibold
                            text-white
                            transition
                            hover:bg-slate-800
                        ">
                    Ver bandeja
                </a>

            </div>

        </div>


        {{-- CONTADORES --}}

        <div
            class="
                    grid
                    gap-4
                    border-b
                    border-slate-100
                    bg-slate-50/50
                    p-5
                    sm:grid-cols-2
                ">

            <div
                class="
                        rounded-xl
                        border
                        border-slate-200
                        bg-white
                        p-5
                    ">

                <p
                    class="
                            text-sm
                            text-slate-500
                        ">
                    {{ $queue[
                            'pendingLabel'
                        ] }}
                </p>

                <p
                    class="
                            mt-2
                            text-3xl
                            font-semibold
                            text-slate-950
                        ">
                    {{ $queue[
                            'pendingCount'
                        ] }}
                </p>

            </div>


            <div
                class="
                        rounded-xl
                        border
                        border-slate-200
                        bg-white
                        p-5
                    ">

                <p
                    class="
                            text-sm
                            text-slate-500
                        ">
                    {{ $queue[
                            'completedLabel'
                        ] }}
                </p>

                <p
                    class="
                            mt-2
                            text-3xl
                            font-semibold
                            text-emerald-600
                        ">
                    {{ $queue[
                            'completedToday'
                        ] }}
                </p>

            </div>

        </div>


        {{-- PRÓXIMAS UNIDADES --}}

        <div>

            @forelse (
            $queue['units']
            as $unit
            )

            <article
                class="
                            border-b
                            border-slate-100
                            px-6
                            py-5
                            last:border-b-0
                        ">

                <div
                    class="
                                flex
                                flex-col
                                gap-4
                                lg:flex-row
                                lg:items-center
                                lg:justify-between
                            ">

                    <div class="min-w-0">

                        <p
                            class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-blue-600
                                    ">
                            {{ $unit->brand?->name
                                        ?? 'Sin marca'
                                    }}
                        </p>

                        <h3
                            class="
                                        mt-1
                                        font-semibold
                                        text-slate-950
                                    ">
                            {{ $unit->model
                                        ?: 'Modelo sin identificar'
                                    }}
                        </h3>

                        <p
                            class="
                                        mt-2
                                        break-all
                                        font-mono
                                        text-xs
                                        text-slate-500
                                    ">
                            {{ $unit->vin }}
                        </p>

                    </div>


                    <a
                        href="{{ route(
                                    'units.show',
                                    $unit
                                ) }}"
                        class="
                                    inline-flex
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    border
                                    border-slate-300
                                    px-4
                                    py-2.5
                                    text-sm
                                    font-semibold
                                    text-slate-700
                                    transition
                                    hover:bg-slate-100
                                ">
                        {{ $queue[
                                    'actionLabel'
                                ] }}
                    </a>

                </div>

            </article>

            @empty

            <div
                class="
                            px-6
                            py-12
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
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                    La bandeja está al día.
                </p>

            </div>

            @endforelse

        </div>

    </section>

    @endforeach



    {{-- USUARIO SIN ÁREA OPERATIVA --}}

    @if (
    ! $managementMode
    && $queues->isEmpty()
    )

    <section
        class="
                rounded-2xl
                border
                border-slate-200
                bg-white
                p-8
                text-center
                shadow-sm
            ">

        <p
            class="
                    font-medium
                    text-slate-700
                ">
            No tienes una bandeja operativa asignada.
        </p>

    </section>

    @endif

</div>