@extends('layouts.app')

@section('title', 'Registrar combustible | CEDIS')

@section('content')

    <div class="space-y-6">

        <div>

            <a href="{{ route('transfers.mine') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-950">
                ←
                Volver a mis traslados
            </a>


            <div class="mt-5">

                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-teal-600">
                    Traslado
                </p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                    Registrar gasolina
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Registra el importe cargado y conserva la evidencia del ticket.
                </p>

            </div>

        </div>


        <livewire:transfers.fuel-registration :assignment-id="$assignment->id" :key="'fuel-registration-' . $assignment->id" />

    </div>

@endsection