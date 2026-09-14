<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CEDIS | Iniciar sesión</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="
        min-h-screen
        bg-[#F5F7FA]
        text-slate-900
        antialiased
    ">

    <main class="
            grid
            min-h-screen
            lg:grid-cols-[minmax(0,1.05fr)_minmax(520px,0.95fr)]
        ">

        {{-- ===================================================== --}}
        {{-- PANEL CORPORATIVO --}}
        {{-- ===================================================== --}}

        <section class="
                relative
                hidden
                overflow-hidden
                bg-[#0B1220]
                p-12
                text-white
                lg:flex
                xl:p-16
            ">

            {{-- DECORACIÓN DE FONDO --}}

            <div class="
                    pointer-events-none
                    absolute
                    -left-24
                    -top-24
                    h-80
                    w-80
                    rounded-full
                    bg-blue-500/10
                    blur-3xl
                "></div>

            <div class="
                    pointer-events-none
                    absolute
                    -bottom-32
                    -right-24
                    h-96
                    w-96
                    rounded-full
                    bg-cyan-400/10
                    blur-3xl
                "></div>


            <div class="
                    relative
                    z-10
                    flex
                    w-full
                    flex-col
                    justify-between
                ">

                {{-- LOGO --}}

                <div>

                    <img src="{{ asset('images/logo-rise.png') }}" alt="Grupo Rise" class="
                            h-auto
                            w-44
                            object-contain
                            object-left
                            xl:w-52
                        ">

                    <div class="
                            mt-7
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            border
                            border-white/10
                            bg-white/[0.05]
                            px-3.5
                            py-2
                        ">

                        <span class="
                                h-2
                                w-2
                                rounded-full
                                bg-blue-400
                            "></span>

                        <span class="
                                text-[11px]
                                font-semibold
                                uppercase
                                tracking-[0.16em]
                                text-slate-300
                            ">
                            Control de Unidades CEDIS
                        </span>

                    </div>

                </div>



                {{-- MENSAJE PRINCIPAL --}}

                <div class="max-w-xl">

                    <p class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-[0.2em]
                            text-blue-400
                        ">
                        Gestión operativa
                    </p>

                    <h1 class="
                            mt-5
                            text-4xl
                            font-semibold
                            leading-[1.12]
                            tracking-tight
                            text-white
                            xl:text-5xl
                        ">
                        Trazabilidad completa
                        de cada unidad.
                    </h1>


                    <p class="
                            mt-6
                            max-w-lg
                            text-base
                            leading-7
                            text-slate-400
                            xl:text-lg
                            xl:leading-8
                        ">
                        Centraliza la recepción, armado,
                        entrega y evidencia documental
                        dentro de un expediente digital.
                    </p>



                    {{-- ETAPAS --}}

                    <div class="
                            mt-10
                            grid
                            max-w-lg
                            grid-cols-3
                            gap-3
                        ">

                        {{-- LLEGADA --}}

                        <div class="
                                rounded-2xl
                                border
                                border-white/10
                                bg-white/[0.04]
                                p-4
                            ">

                            <div class="
                                    flex
                                    h-8
                                    w-8
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-blue-500/15
                                    text-xs
                                    font-bold
                                    text-blue-300
                                ">
                                01
                            </div>

                            <p class="
                                    mt-3
                                    text-xs
                                    font-semibold
                                    text-white
                                ">
                                Llegada
                            </p>

                            <p class="
                                    mt-1
                                    text-[10px]
                                    leading-4
                                    text-slate-500
                                ">
                                Recepción y evidencia
                            </p>

                        </div>



                        {{-- ARMADO --}}

                        <div class="
                                rounded-2xl
                                border
                                border-white/10
                                bg-white/[0.04]
                                p-4
                            ">

                            <div class="
                                    flex
                                    h-8
                                    w-8
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-amber-500/15
                                    text-xs
                                    font-bold
                                    text-amber-300
                                ">
                                02
                            </div>

                            <p class="
                                    mt-3
                                    text-xs
                                    font-semibold
                                    text-white
                                ">
                                Armado
                            </p>

                            <p class="
                                    mt-1
                                    text-[10px]
                                    leading-4
                                    text-slate-500
                                ">
                                Proceso y tiempo
                            </p>

                        </div>



                        {{-- ENTREGA --}}

                        <div class="
                                rounded-2xl
                                border
                                border-white/10
                                bg-white/[0.04]
                                p-4
                            ">

                            <div class="
                                    flex
                                    h-8
                                    w-8
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-violet-500/15
                                    text-xs
                                    font-bold
                                    text-violet-300
                                ">
                                03
                            </div>

                            <p class="
                                    mt-3
                                    text-xs
                                    font-semibold
                                    text-white
                                ">
                                Entrega
                            </p>

                            <p class="
                                    mt-1
                                    text-[10px]
                                    leading-4
                                    text-slate-500
                                ">
                                Transporte y cierre
                            </p>

                        </div>

                    </div>

                </div>



                {{-- FOOTER --}}

                <div class="
                        flex
                        items-center
                        justify-between
                        border-t
                        border-white/10
                        pt-6
                    ">

                    <p class="
                            text-xs
                            text-slate-500
                        ">
                        Grupo Rise
                    </p>

                    <p class="
                            text-xs
                            text-slate-600
                        ">
                        Sistema CEDIS
                    </p>

                </div>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- LOGIN --}}
        {{-- ===================================================== --}}

        <section class="
                flex
                min-h-screen
                items-center
                justify-center
                bg-[#F5F7FA]
                px-5
                py-10
                sm:px-8
                lg:px-12
            ">

            <div class="
                    w-full
                    max-w-md
                ">

                {{-- LOGO MOBILE --}}

                <div class="mb-8 lg:hidden">

                    <div class="
                            inline-flex
                            rounded-2xl
                            bg-[#0B1220]
                            px-5
                            py-4
                        ">

                        <img src="{{ asset('images/logo-rise.png') }}" alt="Grupo Rise" class="
                                h-auto
                                w-36
                            ">

                    </div>

                </div>



                {{-- CABECERA --}}

                <div>

                    <div class="
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            bg-blue-50
                            px-3
                            py-1.5
                        ">

                        <span class="
                                h-1.5
                                w-1.5
                                rounded-full
                                bg-blue-500
                            "></span>

                        <span class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.14em]
                                text-blue-700
                            ">
                            Control de unidades CEDIS
                        </span>

                    </div>


                    <h2 class="
                            mt-5
                            text-3xl
                            font-semibold
                            tracking-tight
                            text-slate-950
                        ">
                        Bienvenido
                    </h2>


                    <p class="
                            mt-2
                            text-sm
                            leading-6
                            text-slate-500
                        ">
                        Ingresa tus credenciales para acceder
                        a la plataforma de operación.
                    </p>

                </div>



                {{-- ================================================= --}}
                {{-- FORMULARIO --}}
                {{-- ================================================= --}}

                <div class="
                        mt-8
                        rounded-3xl
                        border
                        border-slate-200/80
                        bg-white
                        p-6
                        shadow-[0_15px_45px_rgba(15,23,42,0.06)]
                        sm:p-7
                    ">

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">

                        @csrf



                        {{-- CORREO --}}

                        <div>

                            <label for="email" class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-500
                                ">
                                Correo electrónico
                            </label>


                            <div class="relative mt-2">

                                <div class="
                                        pointer-events-none
                                        absolute
                                        inset-y-0
                                        left-0
                                        flex
                                        items-center
                                        pl-4
                                        text-slate-400
                                    ">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.7" stroke="currentColor" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.539 5.693a2.25 2.25 0 0 1-2.422 0L2.25 6.75" />
                                    </svg>

                                </div>


                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                    autofocus autocomplete="email" placeholder="correo@empresa.com" class="
                                        block
                                        w-full
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        bg-slate-50/70
                                        py-3.5
                                        pl-12
                                        pr-4
                                        text-sm
                                        text-slate-950
                                        outline-none
                                        transition
                                        placeholder:text-slate-400
                                        hover:border-slate-300
                                        focus:border-blue-500
                                        focus:bg-white
                                        focus:ring-4
                                        focus:ring-blue-500/10
                                    ">

                            </div>


                            @error('email')

                                <div class="
                                            mt-2
                                            flex
                                            items-center
                                            gap-2
                                            text-xs
                                            font-medium
                                            text-red-600
                                        ">

                                    <span class="
                                                flex
                                                h-5
                                                w-5
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-red-100
                                                text-[10px]
                                                font-bold
                                            ">
                                        !
                                    </span>

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>



                        {{-- CONTRASEÑA --}}

                        <div>

                            <label for="password" class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-500
                                ">
                                Contraseña
                            </label>


                            <div class="relative mt-2">

                                <div class="
                                        pointer-events-none
                                        absolute
                                        inset-y-0
                                        left-0
                                        flex
                                        items-center
                                        pl-4
                                        text-slate-400
                                    ">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.7" stroke="currentColor" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6a2.25 2.25 0 0 1 2.25-2.25Z" />
                                    </svg>

                                </div>


                                <input id="password" type="password" name="password" required
                                    autocomplete="current-password" placeholder="Ingresa tu contraseña" class="
                                        block
                                        w-full
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        bg-slate-50/70
                                        py-3.5
                                        pl-12
                                        pr-4
                                        text-sm
                                        text-slate-950
                                        outline-none
                                        transition
                                        placeholder:text-slate-400
                                        hover:border-slate-300
                                        focus:border-blue-500
                                        focus:bg-white
                                        focus:ring-4
                                        focus:ring-blue-500/10
                                    ">

                            </div>


                            @error('password')

                                <div class="
                                            mt-2
                                            flex
                                            items-center
                                            gap-2
                                            text-xs
                                            font-medium
                                            text-red-600
                                        ">

                                    <span class="
                                                flex
                                                h-5
                                                w-5
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-red-100
                                                text-[10px]
                                                font-bold
                                            ">
                                        !
                                    </span>

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>



                        {{-- RECORDAR --}}

                        <label class="
                                flex
                                cursor-pointer
                                items-center
                                gap-3
                                rounded-xl
                                py-1
                                text-sm
                                text-slate-600
                            ">

                            <input type="checkbox" name="remember" value="1" class="
                                    h-4
                                    w-4
                                    rounded
                                    border-slate-300
                                    text-blue-600
                                    focus:ring-blue-500
                                ">

                            Mantener sesión iniciada

                        </label>



                        {{-- BOTÓN --}}

                        <button type="submit" class="
                                inline-flex
                                w-full
                                items-center
                                justify-center
                                gap-2
                                rounded-xl
                                bg-blue-600
                                px-5
                                py-3.5
                                text-sm
                                font-semibold
                                text-white
                                shadow-sm
                                transition
                                hover:bg-blue-700
                                hover:shadow-md
                                active:scale-[.99]
                                focus:outline-none
                                focus:ring-4
                                focus:ring-blue-500/20
                            ">
                            Ingresar al sistema

                            <span>
                                →
                            </span>
                        </button>

                    </form>

                </div>



                {{-- FOOTER MOBILE / DESKTOP --}}

                <div class="
                        mt-6
                        flex
                        flex-col
                        gap-1
                        text-center
                    ">

                    <p class="
                            text-xs
                            text-slate-400
                        ">
                        Acceso exclusivo para personal autorizado.
                    </p>

                    <p class="
                            text-[10px]
                            uppercase
                            tracking-[0.12em]
                            text-slate-300
                        ">
                        Grupo Rise · Control de Unidades CEDIS
                    </p>

                </div>

            </div>

        </section>

    </main>

</body>

</html>