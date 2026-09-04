@extends('layouts.app')

@section('title', 'Unidades | CEDIS')

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
            Unidades
        </h1>

        <p
            class="
                mt-1
                text-sm
                text-slate-500
            ">
            Consulta y seguimiento de las unidades
            registradas en CEDIS.
        </p>

    </div>

    <livewire:unit-list />

</div>

@endsection