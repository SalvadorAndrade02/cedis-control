<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>CEDIS | Iniciar sesión</title>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-slate-100">

    <main
        class="
            min-h-screen
            grid
            lg:grid-cols-2
        ">

        {{-- BLOQUE VISUAL --}}
        <section
            class="
                hidden
                lg:flex
                relative
                overflow-hidden
                bg-slate-950
                text-white
                p-14
                flex-col
                justify-between
            ">

            <div
                class="
                    absolute
                    inset-0
                    opacity-20
                    bg-gradient-to-br
                    from-blue-500
                    via-transparent
                    to-cyan-400
                "></div>

            <div class="relative">

                <div
                    class="
                        inline-flex
                        items-center
                        rounded-full
                        border
                        border-white/15
                        bg-white/5
                        px-4
                        py-2
                        text-sm
                    ">
                    Control de unidades CEDIS
                </div>

            </div>

            <div class="relative max-w-xl">

                <h1
                    class="
                        text-5xl
                        font-semibold
                        tracking-tight
                        leading-tight
                    ">
                    Trazabilidad documental de cada unidad.
                </h1>

                <p
                    class="
                        mt-6
                        text-lg
                        leading-8
                        text-slate-300
                    ">
                    Evidencia de llegada, armado finalizado
                    y entrega a transportadora en un solo
                    expediente digital.
                </p>

            </div>

            <p
                class="
                    relative
                    text-sm
                    text-slate-500
                ">
                Sistema CEDIS
            </p>

        </section>


        {{-- LOGIN --}}
        <section
            class="
                flex
                items-center
                justify-center
                p-6
                sm:p-10
            ">

            <div class="w-full max-w-md">

                <div class="mb-10">

                    <p
                        class="
                            text-sm
                            font-medium
                            uppercase
                            tracking-[0.2em]
                            text-blue-600
                        ">
                        CEDIS
                    </p>

                    <h2
                        class="
                            mt-3
                            text-3xl
                            font-semibold
                            tracking-tight
                            text-slate-950
                        ">
                        Iniciar sesión
                    </h2>

                    <p
                        class="
                            mt-2
                            text-sm
                            text-slate-500
                        ">
                        Ingresa tus credenciales para
                        acceder al control de unidades.
                    </p>

                </div>

                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                    class="space-y-6">

                    @csrf

                    <div>

                        <label
                            for="email"
                            class="
                                block
                                text-sm
                                font-medium
                                text-slate-700
                            ">
                            Correo electrónico
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            class="
                                mt-2
                                block
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                bg-white
                                px-4
                                py-3
                                text-slate-950
                                outline-none
                                transition
                                focus:border-blue-500
                                focus:ring-4
                                focus:ring-blue-500/10
                            ">

                        @error('email')
                        <p
                            class="
                                    mt-2
                                    text-sm
                                    text-red-600
                                ">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>

                    <div>

                        <label
                            for="password"
                            class="
                                block
                                text-sm
                                font-medium
                                text-slate-700
                            ">
                            Contraseña
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="
                                mt-2
                                block
                                w-full
                                rounded-xl
                                border
                                border-slate-300
                                bg-white
                                px-4
                                py-3
                                text-slate-950
                                outline-none
                                transition
                                focus:border-blue-500
                                focus:ring-4
                                focus:ring-blue-500/10
                            ">

                        @error('password')
                        <p
                            class="
                                    mt-2
                                    text-sm
                                    text-red-600
                                ">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>

                    <label
                        class="
                            flex
                            items-center
                            gap-3
                            text-sm
                            text-slate-600
                        ">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="
                                h-4
                                w-4
                                rounded
                                border-slate-300
                            ">

                        Mantener sesión iniciada
                    </label>

                    <button
                        type="submit"
                        class="
                            w-full
                            rounded-xl
                            bg-slate-950
                            px-5
                            py-3.5
                            text-sm
                            font-semibold
                            text-white
                            transition
                            hover:bg-slate-800
                            focus:outline-none
                            focus:ring-4
                            focus:ring-slate-950/15
                        ">
                        Ingresar al portal
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>