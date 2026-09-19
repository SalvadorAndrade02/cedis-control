@extends('layouts.app')

@section('title', 'Dashboard | CEDIS')

@section('content')

        @if ($isTransporterDashboard ?? false)

            @include('dashboard.trasladista')

        @else

<div class="space-y-8">

    {{-- HEADER --}}

    <div>

        <p
            class="
                text-sm
                font-medium
                text-blue-600
            ">
            Control de unidades CEDIS
        </p>

        <h1
            class="
                mt-1
                text-3xl
                font-semibold
                tracking-tight
                text-slate-950
            ">
            Dashboard
        </h1>

        <p
            class="
                mt-2
                text-sm
                text-slate-500
            ">
            Bienvenido,
            {{ auth()->user()->name }}.
        </p>

    </div>


    {{-- DASHBOARD DINÁMICO --}}

    <livewire:dashboard-overview />

</div>
@endif
@endsection