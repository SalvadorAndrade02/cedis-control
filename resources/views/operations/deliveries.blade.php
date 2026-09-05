@extends('layouts.app')

@section('title', 'Entregas | CEDIS')

@section('content')

<div class="space-y-8">

    <div>
        <h1
            class="
                text-2xl
                font-semibold
                tracking-tight
            ">
            Entregas
        </h1>

        <p
            class="
                mt-1
                text-sm
                text-slate-500
            ">
            Unidades listas para entrega
            a transportadora.
        </p>
    </div>

    <livewire:operational-queue
        type="delivery" />

</div>

@endsection