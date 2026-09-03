@extends('layouts.app')

@section('title', 'Importar unidades | CEDIS')

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
            Importar unidades
        </h1>

        <p
            class="
                mt-1
                text-sm
                text-slate-500
            ">
            Registra unidades a partir de sus documentos
            XML y PDF.
        </p>

    </div>

    <livewire:import-units />

</div>

@endsection