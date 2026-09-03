@extends('layouts.app')

@section('title', 'Dashboard | CEDIS')

@section('content')

<div class="space-y-8">

    <div>

        <h1
            class="
                text-2xl
                font-semibold
                tracking-tight
                text-slate-950
            ">
            Dashboard
        </h1>

        <p
            class="
                mt-1
                text-sm
                text-slate-500
            ">
            Resumen general del estado de las unidades.
        </p>

    </div>


    <div
        class="
            grid
            gap-4
            sm:grid-cols-2
            xl:grid-cols-4
        ">

        @foreach ([
        [
        'label' => 'Total unidades',
        'value' => \App\Models\Unit::count(),
        ],
        [
        'label' => 'Pendientes de llegada',
        'value' => \App\Models\Unit::where(
        'status',
        'ARRIVAL_PENDING'
        )->count(),
        ],
        [
        'label' => 'Pendientes de armado',
        'value' => \App\Models\Unit::where(
        'status',
        'ASSEMBLY_PENDING'
        )->count(),
        ],
        [
        'label' => 'Expedientes completos',
        'value' => \App\Models\Unit::where(
        'status',
        'COMPLETED'
        )->count(),
        ],
        ] as $card)

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
                {{ $card['label'] }}
            </p>

            <p
                class="
                        mt-4
                        text-4xl
                        font-semibold
                        tracking-tight
                    ">
                {{ $card['value'] }}
            </p>

        </article>

        @endforeach

    </div>


    <section
        class="
            rounded-2xl
            border
            border-slate-200
            bg-white
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
                ">
                Actividad reciente
            </h2>

        </div>

        <div class="p-6">

            <p
                class="
                    text-sm
                    text-slate-500
                ">
                Aquí mostraremos las últimas unidades
                importadas y movimientos de evidencia.
            </p>

        </div>

    </section>

</div>

@endsection