@extends('layouts.app')

@section('title', 'Reportes de Armado | CEDIS')

@section('content')

    <div class="space-y-6">

        {{-- CABECERA --}}

        <div>

            <p class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.16em]
                        text-amber-600
                    ">
                Analítica operativa
            </p>

            <h1 class="
                        mt-2
                        text-2xl
                        font-semibold
                        tracking-tight
                        text-slate-950
                        sm:text-3xl
                    ">
                Reportes de Armado
            </h1>

            <p class="
                        mt-2
                        max-w-2xl
                        text-sm
                        leading-6
                        text-slate-500
                    ">
                Consulta los armados finalizados y las unidades
                que actualmente se encuentran en proceso.
            </p>

        </div>


        <livewire:reports.assembly-report />

    </div>

@endsection