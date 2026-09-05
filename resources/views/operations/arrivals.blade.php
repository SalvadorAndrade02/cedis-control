@extends('layouts.app')

@section('title', 'Llegadas | CEDIS')

@section('content')

<div class="space-y-8">

    <div>
        <h1
            class="
                text-2xl
                font-semibold
                tracking-tight
            ">
            Llegadas
        </h1>

        <p
            class="
                mt-1
                text-sm
                text-slate-500
            ">
            Unidades pendientes de documentar
            su llegada al CEDIS.
        </p>
    </div>

    <livewire:operational-queue
        type="arrival" />

</div>

@endsection