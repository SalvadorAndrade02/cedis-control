@extends('layouts.app')

@section('title', 'Mis traslados | CEDIS')

@section('content')

    <div class="space-y-6">

        <div>

            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-teal-600">
                Traslados
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                Mis traslados
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Consulta las unidades que actualmente tienes asignadas.
            </p>

        </div>


        <livewire:transfers.my-transfers />

    </div>

@endsection