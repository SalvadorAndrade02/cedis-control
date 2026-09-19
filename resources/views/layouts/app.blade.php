<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'CEDIS | Grupo Rise')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>


<body x-data="{
        sidebarOpen: false,
        desktop: window.innerWidth >= 1024
    }" x-init="
        $watch('sidebarOpen', value => {
            document.documentElement.style.overflow =
                value && !desktop
                    ? 'hidden'
                    : '';
        });
    " x-on:resize.window="
        desktop = window.innerWidth >= 1024;

        if (desktop) {
            sidebarOpen = false;
            document.documentElement.style.overflow = '';
        }
    " x-on:keydown.escape.window="
        sidebarOpen = false;
    " class="
        min-h-dvh
        bg-[#F5F7FA]
        text-slate-950
        antialiased
    ">

    <div class="
        min-h-dvh
        min-w-0
        overflow-x-clip
        lg:flex
    ">


        {{-- ===================================================== --}}
        {{-- OVERLAY MÓVIL --}}
        {{-- ===================================================== --}}

        <div x-cloak x-show="sidebarOpen && ! desktop" x-transition.opacity x-on:click="sidebarOpen = false" class="
                fixed
                inset-0
                z-40
                bg-slate-950/60
                backdrop-blur-sm
                lg:hidden
            "></div>


        {{-- ===================================================== --}}
        {{-- SIDEBAR --}}
        {{-- ===================================================== --}}

        <aside x-cloak x-show="desktop || sidebarOpen" x-transition:enter="
                transition
                ease-out
                duration-200
            " x-transition:enter-start="
                -translate-x-full
            " x-transition:enter-end="
                translate-x-0
            " x-transition:leave="
                transition
                ease-in
                duration-150
            " x-transition:leave-start="
                translate-x-0
            " x-transition:leave-end="
                -translate-x-full
            " class="
                fixed
                inset-y-0
                left-0
                z-50
                flex
                w-[calc(100vw-1rem)]
                max-w-[290px]
                flex-col
                bg-[#0B1220]
                text-white
                shadow-2xl
                lg:sticky
                lg:top-0
                lg:z-auto
                lg:flex
                lg:h-dvh
                lg:w-72
                lg:max-w-none
                lg:self-start
                lg:translate-x-0
                lg:shadow-none
            ">

            {{-- BRANDING --}}

            <div class="
                    border-b
                    border-white/10
                    px-6
                    pb-6
                    pt-7
                ">

                <div class="
                        flex
                        items-start
                        justify-between
                        gap-4
                    ">

                    <div>

                        <img src="{{ asset('images/logo-rise.png') }}" alt="Grupo Rise" class="
                                h-8
                                w-auto
                                max-w-[190px]
                                object-contain
                                object-left
                            ">

                        <div class="mt-5">

                            <p class="
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-[0.22em]
                                    text-slate-500
                                ">
                                Plataforma CEDIS
                            </p>

                            <p class="
                                    mt-1
                                    text-sm
                                    font-semibold
                                    text-white
                                ">
                                Control de unidades
                            </p>

                        </div>

                    </div>


                    {{-- CERRAR SIDEBAR MÓVIL --}}

                    <button type="button" x-on:click="sidebarOpen = false" class="
                            rounded-lg
                            p-2
                            text-slate-400
                            transition
                            hover:bg-white/10
                            hover:text-white
                            lg:hidden
                        ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>

                    </button>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- NAVEGACIÓN --}}
            {{-- ================================================= --}}

            <nav class="
                scrollbar-hidden
                min-h-0
                flex-1
                overflow-y-auto
                overscroll-contain
                px-4
                py-5
                sm:py-6
            ">

                {{-- GENERAL --}}

                <p class="
                        mb-2
                        px-3
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-[0.18em]
                        text-slate-500
                    ">
                    General
                </p>


                <div class="space-y-1">


                    {{-- DASHBOARD --}}

                    <a href="{{ route('dashboard') }}" class="
                            group
                            flex
                            items-center
                            gap-3
                            rounded-xl
                            border-l-2
                            px-3
                            py-3
                            text-sm
                            font-medium
                            transition-all

                            {{ request()->routeIs('dashboard')
    ? 'border-blue-400 bg-white/10 text-white'
    : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white'
                            }}
                        ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="
                                h-5
                                w-5
                                shrink-0
                                {{ request()->routeIs('dashboard')
    ? 'text-blue-400'
    : 'text-slate-500 group-hover:text-slate-300'
                                }}
                            ">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 3v18h16.5V3H3.75Zm4.5 4.5h1.5v1.5h-1.5V7.5Zm0 4.5h1.5v1.5h-1.5V12Zm0 4.5h1.5V18h-1.5v-1.5Zm6-9h1.5v1.5h-1.5V7.5Zm0 4.5h1.5v1.5h-1.5V12Zm0 4.5h1.5V18h-1.5v-1.5Z" />
                        </svg>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    {{-- UNIDADES --}}

                    @can('units.view')

                                    <a href="{{ route('units.index') }}"
                                        class="
                                                                                                                                                                                                                                                                group
                                                                                                                                                                                                                                                                flex
                                                                                                                                                                                                                                                                items-center
                                                                                                                                                                                                                                                                gap-3
                                                                                                                                                                                                                                                                rounded-xl
                                                                                                                                                                                                                                                                border-l-2
                                                                                                                                                                                                                                                                px-3
                                                                                                                                                                                                                                                                py-3
                                                                                                                                                                                                                                                                text-sm
                                                                                                                                                                                                                                                                font-medium
                                                                                                                                                                                                                                                                transition-all

                                                                                                                                                                                                                                                                {{ request()->routeIs('units.*')
                        ? 'border-blue-400 bg-white/10 text-white'
                        : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white'
                                                                                                                                                                                                                                                                }}
                                                                                                                                                                                                                                                            ">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                            stroke="currentColor"
                                            class="
                                                                                                                                                                                                                                                                    h-5
                                                                                                                                                                                                                                                                    w-5
                                                                                                                                                                                                                                                                    shrink-0
                                                                                                                                                                                                                                                                    {{ request()->routeIs('units.*')
                        ? 'text-blue-400'
                        : 'text-slate-500 group-hover:text-slate-300'
                                                                                                                                                                                                                                                                    }}
                                                                                                                                                                                                                                                                ">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 18.75a1.5 1.5 0 1 1-3 0m13.5 0a1.5 1.5 0 1 1-3 0M3.75 15.75V9.621a2.25 2.25 0 0 1 .659-1.591l2.371-2.371A2.25 2.25 0 0 1 8.371 5h6.879a2.25 2.25 0 0 1 2.25 2.25v1.5h.879a2.25 2.25 0 0 1 1.591.659l.621.621a2.25 2.25 0 0 1 .659 1.591v4.129H3.75Z" />
                                        </svg>

                                        <span>
                                            Unidades
                                        </span>

                                    </a>

                    @endcan


                    {{-- IMPORTACIÓN --}}

                    @can('imports.manage')

                                    <a href="{{ route('imports.index') }}"
                                        class="
                                                                                                                                                                                                                                                                group
                                                                                                                                                                                                                                                                flex
                                                                                                                                                                                                                                                                items-center
                                                                                                                                                                                                                                                                gap-3
                                                                                                                                                                                                                                                                rounded-xl
                                                                                                                                                                                                                                                                border-l-2
                                                                                                                                                                                                                                                                px-3
                                                                                                                                                                                                                                                                py-3
                                                                                                                                                                                                                                                                text-sm
                                                                                                                                                                                                                                                                font-medium
                                                                                                                                                                                                                                                                transition-all

                                                                                                                                                                                                                                                                {{ request()->routeIs('imports.*')
                        ? 'border-blue-400 bg-white/10 text-white'
                        : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white'
                                                                                                                                                                                                                                                                }}
                                                                                                                                                                                                                                                            ">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                            stroke="currentColor"
                                            class="
                                                                                                                                                                                                                                                                    h-5
                                                                                                                                                                                                                                                                    w-5
                                                                                                                                                                                                                                                                    shrink-0
                                                                                                                                                                                                                                                                    {{ request()->routeIs('imports.*')
                        ? 'text-blue-400'
                        : 'text-slate-500 group-hover:text-slate-300'
                                                                                                                                                                                                                                                                    }}
                                                                                                                                                                                                                                                                ">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 16.5V3m0 13.5-4.5-4.5m4.5 4.5 4.5-4.5M4.5 15v3.75A2.25 2.25 0 0 0 6.75 21h10.5a2.25 2.25 0 0 0 2.25-2.25V15" />
                                        </svg>

                                        <span>
                                            Importar documentos
                                        </span>

                                    </a>

                    @endcan

                </div>


                {{-- ================================================= --}}
                {{-- OPERACIÓN --}}
                {{-- ================================================= --}}

                @canany([
                        'arrival.view',
                        'assembly.view',
                        'delivery.view',
                        'transfers.view'
                    ])

                    <div class="
                                                                                    my-6
                                                                                    border-t
                                                                                    border-white/10
                                                                                "></div>

                    <p class="
                                                                                    mb-2
                                                                                    px-3
                                                                                    text-[10px]
                                                                                    font-semibold
                                                                                    uppercase
                                                                                    tracking-[0.18em]
                                                                                    text-slate-500
                                                                                ">
                        Operación
                    </p>

                @endcanany


                <div class="space-y-1">


                    {{-- LLEGADAS --}}

                    @can('arrival.view')

                                    <a href="{{ route('operations.arrivals') }}"
                                        class="
                                                                                                                                                                                                                                                                group
                                                                                                                                                                                                                                                                flex
                                                                                                                                                                                                                                                                items-center
                                                                                                                                                                                                                                                                gap-3
                                                                                                                                                                                                                                                                rounded-xl
                                                                                                                                                                                                                                                                border-l-2
                                                                                                                                                                                                                                                                px-3
                                                                                                                                                                                                                                                                py-3
                                                                                                                                                                                                                                                                text-sm
                                                                                                                                                                                                                                                                font-medium
                                                                                                                                                                                                                                                                transition-all

                                                                                                                                                                                                                                                                {{ request()->routeIs('operations.arrivals')
                        ? 'border-blue-400 bg-white/10 text-white'
                        : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white'
                                                                                                                                                                                                                                                                }}
                                                                                                                                                                                                                                                            ">

                                        <span
                                            class="
                                                                                                                                                                                                                                                                    h-2
                                                                                                                                                                                                                                                                    w-2
                                                                                                                                                                                                                                                                    rounded-full
                                                                                                                                                                                                                                                                    bg-blue-400
                                                                                                                                                                                                                                                                "></span>

                                        Llegadas

                                    </a>

                    @endcan


                    {{-- ARMADOS --}}

                    @can('assembly.view')

                                    <a href="{{ route('operations.assemblies') }}"
                                        class="
                                                                                                                                                                                                                                                                group
                                                                                                                                                                                                                                                                flex
                                                                                                                                                                                                                                                                items-center
                                                                                                                                                                                                                                                                gap-3
                                                                                                                                                                                                                                                                rounded-xl
                                                                                                                                                                                                                                                                border-l-2
                                                                                                                                                                                                                                                                px-3
                                                                                                                                                                                                                                                                py-3
                                                                                                                                                                                                                                                                text-sm
                                                                                                                                                                                                                                                                font-medium
                                                                                                                                                                                                                                                                transition-all

                                                                                                                                                                                                                                                                {{ request()->routeIs('operations.assemblies')
                        ? 'border-amber-400 bg-white/10 text-white'
                        : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white'
                                                                                                                                                                                                                                                                }}
                                                                                                                                                                                                                                                            ">

                                        <span
                                            class="
                                                                                                                                                                                                                                                                    h-2
                                                                                                                                                                                                                                                                    w-2
                                                                                                                                                                                                                                                                    rounded-full
                                                                                                                                                                                                                                                                    bg-amber-400
                                                                                                                                                                                                                                                                "></span>

                                        Armados

                                    </a>

                    @endcan


                    {{-- ENTREGAS --}}

                    @can('delivery.view')

                                    <a href="{{ route('operations.deliveries') }}"
                                        class="
                                                                                                                                                                                                                                                                group
                                                                                                                                                                                                                                                                flex
                                                                                                                                                                                                                                                                items-center
                                                                                                                                                                                                                                                                gap-3
                                                                                                                                                                                                                                                                rounded-xl
                                                                                                                                                                                                                                                                border-l-2
                                                                                                                                                                                                                                                                px-3
                                                                                                                                                                                                                                                                py-3
                                                                                                                                                                                                                                                                text-sm
                                                                                                                                                                                                                                                                font-medium
                                                                                                                                                                                                                                                                transition-all

                                                                                                                                                                                                                                                                {{ request()->routeIs('operations.deliveries')
                        ? 'border-emerald-400 bg-white/10 text-white'
                        : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white'
                                                                                                                                                                                                                                                                }}
                                                                                                                                                                                                                                                            ">

                                        <span
                                            class="
                                                                                                                                                                                                                                                                    h-2
                                                                                                                                                                                                                                                                    w-2
                                                                                                                                                                                                                                                                    rounded-full
                                                                                                                                                                                                                                                                    bg-emerald-400
                                                                                                                                                                                                                                                                "></span>

                                        Entregas

                                    </a>

                    @endcan

                    {{-- MIS TRASLADOS --}}

                    @role('TRASLADISTA')

                    <a href="{{ route('transfers.mine') }}" class="
            group
            flex
            items-center
            gap-3
            rounded-xl
            border-l-2
            px-3
            py-3
            text-sm
            font-medium
            transition-all

            {{
    request()->routeIs('transfers.mine')
    ? 'border-teal-400 bg-white/10 text-white'
    : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white'
            }}
        ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="
                h-5
                w-5
                shrink-0

                {{
    request()->routeIs('transfers.mine')
    ? 'text-teal-400'
    : 'text-slate-500 group-hover:text-slate-300'
                }}
            ">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 13.5V9.75a1.5 1.5 0 0 1 1.5-1.5h8.25a1.5 1.5 0 0 1 1.5 1.5v3.75m0 0h2.25l2.25 2.25v2.25h-1.5m-3-4.5v4.5m0 0H8.25m6.75 0a1.5 1.5 0 1 0 3 0m-9.75 0a1.5 1.5 0 1 0-3 0m3 0H5.25" />
                        </svg>

                        <span>
                            Mis traslados
                        </span>

                    </a>

                    @endrole

                </div>


                {{-- ================================================= --}}
                {{-- ADMINISTRACIÓN --}}
                {{-- ================================================= --}}

                @canany([
                        'users.manage',
                        'catalogs.manage',
                        'reports.view'
                    ])

                    <div class="
                                                                                    my-6
                                                                                    border-t
                                                                                    border-white/10
                                                                                "></div>

                    <p class="
                                                                                    mb-2
                                                                                    px-3
                                                                                    text-[10px]
                                                                                    font-semibold
                                                                                    uppercase
                                                                                    tracking-[0.18em]
                                                                                    text-slate-500
                                                                                ">
                        Administración
                    </p>

                @endcanany


                <div class="space-y-1">


                    {{-- USUARIOS --}}

                    @can('users.manage')

                                    <a href="{{ route('admin.users') }}"
                                        class="
                                                                                                                                                                                                                                                                group
                                                                                                                                                                                                                                                                flex
                                                                                                                                                                                                                                                                items-center
                                                                                                                                                                                                                                                                gap-3
                                                                                                                                                                                                                                                                rounded-xl
                                                                                                                                                                                                                                                                border-l-2
                                                                                                                                                                                                                                                                px-3
                                                                                                                                                                                                                                                                py-3
                                                                                                                                                                                                                                                                text-sm
                                                                                                                                                                                                                                                                font-medium
                                                                                                                                                                                                                                                                transition-all

                                                                                                                                                                                                                                                                {{ request()->routeIs('admin.users')
                        ? 'border-blue-400 bg-white/10 text-white'
                        : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white'
                                                                                                                                                                                                                                                                }}
                                                                                                                                                                                                                                                            ">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                            stroke="currentColor"
                                            class="
                                                                                                                                                                                                                                                                    h-5
                                                                                                                                                                                                                                                                    w-5
                                                                                                                                                                                                                                                                    text-slate-500
                                                                                                                                                                                                                                                                    group-hover:text-slate-300
                                                                                                                                                                                                                                                                ">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766v-.106a6.375 6.375 0 0 1 11.964-3.07M12 6.75a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>

                                        Usuarios

                                    </a>

                    @endcan


                    {{-- REPORTES FUTUROS --}}

                    @can('reports.view')

                                    <a href="{{ route('reports.assembly') }}" class="
                                                            group
                                                            flex
                                                            items-center
                                                            gap-3
                                                            rounded-xl
                                                            border-l-2
                                                            px-3
                                                            py-3
                                                            text-sm
                                                            font-medium
                                                            transition-all

                                                            {{ request()->routeIs('reports.*')
                        ? 'border-amber-400 bg-white/10 text-white'
                        : 'border-transparent text-slate-300 hover:bg-white/5 hover:text-white'
                                                            }}
                                                        ">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                            stroke="currentColor" class="
                                                                h-5
                                                                w-5
                                                                shrink-0

                                                                {{ request()->routeIs('reports.*')
                        ? 'text-amber-400'
                        : 'text-slate-500 group-hover:text-slate-300'
                                                                }}
                                                            ">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.75 3v18h16.5M7.5 16.5v-5.25m4.5 5.25V7.5m4.5 9V10.5" />
                                        </svg>

                                        <span>
                                            Reportes
                                        </span>

                                    </a>

                    @endcan

                </div>

            </nav>


            {{-- ================================================= --}}
            {{-- USUARIO / FOOTER SIDEBAR --}}
            {{-- ================================================= --}}

            <div class="
                shrink-0
                border-t
                border-white/10
                px-4
                pt-4
                pb-[max(1rem,env(safe-area-inset-bottom))]
            ">

                <div class="
                        rounded-2xl
                        bg-white/[0.04]
                        p-4
                    ">

                    <div class="
                            flex
                            items-center
                            gap-3
                        ">

                        <div class="
                                flex
                                h-10
                                w-10
                                shrink-0
                                items-center
                                justify-center
                                rounded-xl
                                bg-blue-500/15
                                text-sm
                                font-bold
                                text-blue-400
                            ">
                            {{ strtoupper(
    substr(
        auth()->user()->name,
        0,
        1
    )
) }}
                        </div>


                        <div class="min-w-0">

                            <p class="
                                    truncate
                                    text-sm
                                    font-semibold
                                    text-white
                                ">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="
                                    mt-0.5
                                    truncate
                                    text-[10px]
                                    font-medium
                                    uppercase
                                    tracking-wider
                                    text-slate-500
                                ">
                                {{ auth()
    ->user()
    ->getRoleNames()
    ->first()
    ?? 'Usuario'
                                }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </aside>


        {{-- ===================================================== --}}
        {{-- MAIN --}}
        {{-- ===================================================== --}}

        <div class="
                min-w-0
                flex-1
                overflow-x-clip
            ">


            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <header class="
                    sticky
                    top-0
                    z-30
                    border-b
                    border-slate-200/80
                    bg-white/90
                    backdrop-blur-xl
                ">

                <div class="
                    flex
                    min-h-16
                    items-center
                    justify-between
                    gap-3
                    px-3
                    py-3
                    sm:min-h-[72px]
                    sm:gap-5
                    sm:px-6
                    lg:px-8
                ">

                    {{-- IZQUIERDA --}}

                    <div class="
                            flex
                            min-w-0
                            items-center
                            gap-3
                        ">

                        {{-- BOTÓN MÓVIL --}}

                        <button type="button" x-on:click="sidebarOpen = true" class="
                                inline-flex
                                h-11
                                w-11
                                shrink-0
                                items-center
                                justify-center
                                rounded-xl
                                border
                                border-slate-200
                                bg-white
                                text-slate-600
                                shadow-sm
                                transition
                                hover:bg-slate-50
                                lg:hidden
                            ">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>

                        </button>


                        <div class="min-w-0">

                            <p class="
                                truncate
                                text-xs
                                font-semibold
                                text-slate-950
                                min-[360px]:text-sm
                            ">
                                Control de unidades CEDIS
                            </p>

                            <p class="
                                    mt-0.5
                                    hidden
                                    text-xs
                                    text-slate-500
                                    sm:block
                                ">
                                Gestión y trazabilidad operativa
                            </p>

                        </div>

                    </div>


                    {{-- DERECHA --}}

                    <div class="
                            flex
                            shrink-0
                            items-center
                            gap-3
                        ">

                        <div class="
                                hidden
                                text-right
                                md:block
                            ">

                            <p class="
                                    text-sm
                                    font-semibold
                                    text-slate-900
                                ">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                ">
                                {{ auth()->user()->email }}
                            </p>

                        </div>


                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <button type="submit" title="Cerrar sesión" class="
                                    inline-flex
                                    h-11
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-xl
                                    border
                                    border-slate-200
                                    bg-white
                                    px-3
                                    text-sm
                                    font-medium
                                    text-slate-600
                                    shadow-sm
                                    transition
                                    hover:border-red-200
                                    hover:bg-red-50
                                    hover:text-red-700
                                ">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.7" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3-6 3 3m0 0-3 3m3-3H9" />
                                </svg>

                                <span class="hidden sm:inline">
                                    Salir
                                </span>

                            </button>

                        </form>

                    </div>

                </div>

            </header>


            {{-- ================================================= --}}
            {{-- CONTENIDO --}}
            {{-- ================================================= --}}

            <main class="
                min-w-0
                px-3
                py-4
                min-[360px]:px-4
                sm:px-6
                sm:py-6
                lg:px-8
                lg:py-8
            ">

                <div class="
                        mx-auto
                        w-full
                        min-w-0
                        max-w-[1600px]
                    ">

                    {{-- MENSAJE GLOBAL DE ÉXITO --}}

                    @if (session('success'))

                        <div class="
                                                                                        mb-6
                                                                                        flex
                                                                                        items-start
                                                                                        gap-3
                                                                                        rounded-2xl
                                                                                        border
                                                                                        border-emerald-200
                                                                                        bg-emerald-50
                                                                                        px-5
                                                                                        py-4
                                                                                        shadow-sm
                                                                                    ">

                            <div class="
                                                                                            flex
                                                                                            h-8
                                                                                            w-8
                                                                                            shrink-0
                                                                                            items-center
                                                                                            justify-center
                                                                                            rounded-full
                                                                                            bg-emerald-100
                                                                                            text-emerald-700
                                                                                        ">
                                ✓
                            </div>


                            <div>

                                <p class="
                                                                                                text-sm
                                                                                                font-semibold
                                                                                                text-emerald-900
                                                                                            ">
                                    Operación realizada correctamente
                                </p>

                                <p class="
                                                                                                mt-1
                                                                                                text-sm
                                                                                                text-emerald-700
                                                                                            ">
                                    {{ session('success') }}
                                </p>

                            </div>

                        </div>

                    @endif


                    {{-- MENSAJE GLOBAL DE ERROR --}}

                    @if (session('error'))

                        <div class="
                                                                                        mb-6
                                                                                        flex
                                                                                        items-start
                                                                                        gap-3
                                                                                        rounded-2xl
                                                                                        border
                                                                                        border-red-200
                                                                                        bg-red-50
                                                                                        px-5
                                                                                        py-4
                                                                                        shadow-sm
                                                                                    ">

                            <div class="
                                                                                            flex
                                                                                            h-8
                                                                                            w-8
                                                                                            shrink-0
                                                                                            items-center
                                                                                            justify-center
                                                                                            rounded-full
                                                                                            bg-red-100
                                                                                            text-red-700
                                                                                        ">
                                !
                            </div>


                            <div>

                                <p class="
                                                                                                text-sm
                                                                                                font-semibold
                                                                                                text-red-900
                                                                                            ">
                                    No fue posible completar la operación
                                </p>

                                <p class="
                                                                                                mt-1
                                                                                                text-sm
                                                                                                text-red-700
                                                                                            ">
                                    {{ session('error') }}
                                </p>

                            </div>

                        </div>

                    @endif


                    @yield('content')

                </div>

            </main>

        </div>

    </div>


    @livewireScripts

</body>

</html>