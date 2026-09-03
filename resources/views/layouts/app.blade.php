<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'CEDIS')
    </title>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js'
    ])

    @livewireStyles

</head>

<body
    class="
        min-h-screen
        bg-slate-50
        text-slate-950
    ">

    <div class="min-h-screen lg:flex">

        {{-- SIDEBAR --}}
        <aside
            class="
            hidden
            lg:flex
            lg:w-72
            lg:flex-col
            border-r
            border-slate-200
            bg-white
        ">

            <div
                class="
                flex
                h-20
                items-center
                border-b
                border-slate-200
                px-7
            ">

                <div>

                    <p
                        class="
                        text-xs
                        font-semibold
                        tracking-[0.25em]
                        text-blue-600
                    ">
                        GRUPO RISE
                    </p>

                    <p
                        class="
                        mt-1
                        text-lg
                        font-semibold
                    ">
                        Control CEDIS
                    </p>

                </div>

            </div>

            <nav
                class="
                flex-1
                space-y-1
                p-4
            ">

                <a
                    href="{{ route('dashboard') }}"
                    class="
                    flex
                    items-center
                    rounded-xl
                    bg-slate-950
                    px-4
                    py-3
                    text-sm
                    font-medium
                    text-white
                ">
                    Dashboard
                </a>

                <a
                    href="#"
                    class="
                    flex
                    items-center
                    rounded-xl
                    px-4
                    py-3
                    text-sm
                    font-medium
                    text-slate-600
                    hover:bg-slate-100
                ">
                    Unidades
                </a>

                <a
                    href="{{ route('imports.index') }}"
                    class="
                    flex
                    items-center
                    rounded-xl
                    px-4
                    py-3
                    text-sm
                    font-medium
                    text-slate-600
                    hover:bg-slate-100
                ">
                    Importar documentos
                </a>

                <div
                    class="
                    my-4
                    border-t
                    border-slate-200
                "></div>

                <p
                    class="
                    px-4
                    py-2
                    text-xs
                    font-semibold
                    uppercase
                    tracking-wider
                    text-slate-400
                ">
                    Evidencias
                </p>

                <a
                    href="#"
                    class="
                    block
                    rounded-xl
                    px-4
                    py-3
                    text-sm
                    text-slate-600
                    hover:bg-slate-100
                ">
                    Llegadas
                </a>

                <a
                    href="#"
                    class="
                    block
                    rounded-xl
                    px-4
                    py-3
                    text-sm
                    text-slate-600
                    hover:bg-slate-100
                ">
                    Armados finalizados
                </a>

                <a
                    href="#"
                    class="
                    block
                    rounded-xl
                    px-4
                    py-3
                    text-sm
                    text-slate-600
                    hover:bg-slate-100
                ">
                    Entregas
                </a>

            </nav>

        </aside>


        {{-- MAIN --}}
        <div class="min-w-0 flex-1">

            <header
                class="
                sticky
                top-0
                z-30
                flex
                h-20
                items-center
                justify-between
                border-b
                border-slate-200
                bg-white/90
                px-5
                backdrop-blur
                lg:px-8
            ">

                <div>

                    <p
                        class="
                        text-sm
                        text-slate-500
                    ">
                        Control de unidades CEDIS
                    </p>

                </div>

                <div
                    class="
                    flex
                    items-center
                    gap-4
                ">

                    <div
                        class="
                        hidden
                        text-right
                        sm:block
                    ">

                        <p
                            class="
                            text-sm
                            font-medium
                        ">
                            {{ auth()->user()->name }}
                        </p>

                        <p
                            class="
                            text-xs
                            text-slate-500
                        ">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                    <form
                        method="POST"
                        action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="
                            rounded-lg
                            border
                            border-slate-200
                            px-4
                            py-2
                            text-sm
                            font-medium
                            text-slate-600
                            hover:bg-slate-100
                        ">
                            Salir
                        </button>
                    </form>
                </div>
            </header>
            <main
                class="
                p-5
                lg:p-8
            ">
                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts
</body>

</html>