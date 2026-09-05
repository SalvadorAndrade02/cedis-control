@extends('layouts.app')

@section('title', 'Armados | CEDIS')

@section('content')

<div class="space-y-8">

    <div>
        <h1
            class="
                text-2xl
                font-semibold
                tracking-tight
            ">
            Armados finalizados
        </h1>

        <p
            class="
                mt-1
                text-sm
                text-slate-500
            ">
            Unidades pendientes de registrar
            la evidencia del armado finalizado.
        </p>
    </div>

    <livewire:operational-queue
        type="assembly" />

</div>

@endsection