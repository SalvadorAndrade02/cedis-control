@extends('layouts.app')

@section('title', 'Usuarios | CEDIS')

@section('content')

<div class="space-y-8">

    <div>

        <p
            class="
                text-sm
                font-medium
                text-blue-600
            ">
            Administración
        </p>

        <h1
            class="
                mt-1
                text-3xl
                font-semibold
                tracking-tight
                text-slate-950
            ">
            Usuarios
        </h1>

        <p
            class="
                mt-2
                text-sm
                text-slate-500
            ">
            Administra el personal,
            sus accesos y su rol operativo.
        </p>

    </div>


    <livewire:user-management />

</div>

@endsection