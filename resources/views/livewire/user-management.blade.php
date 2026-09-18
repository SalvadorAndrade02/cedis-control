<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- HERRAMIENTAS --}}
    {{-- ========================================================= --}}

    <section class="
            rounded-3xl
            border
            border-slate-200/80
            bg-white
            p-5
            shadow-[0_8px_30px_rgba(15,23,42,0.04)]
            lg:p-6
        ">

        <div class="
                flex
                flex-col
                gap-5
                lg:flex-row
                lg:items-end
                lg:justify-between
            ">

            {{-- BUSCADOR --}}

            <div class="min-w-0 flex-1">

                <label class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.12em]
                        text-slate-500
                    ">
                    Buscar usuario
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

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" />
                        </svg>

                    </div>


                    <input type="search" wire:model.live.debounce.400ms="search"
                        placeholder="Buscar por nombre, correo o rol..." class="
                            w-full
                            rounded-2xl
                            border
                            border-slate-200
                            bg-slate-50/70
                            py-3.5
                            pl-12
                            pr-4
                            text-sm
                            text-slate-900
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


                <div wire:loading wire:target="search" class="
                        mt-2
                        text-xs
                        font-medium
                        text-blue-600
                    ">
                    Buscando usuarios...
                </div>

            </div>


            {{-- NUEVO USUARIO --}}

            <button type="button" wire:click="create" class="
                    inline-flex
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
                    active:scale-[.98]
                ">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                    stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>

                Nuevo usuario

            </button>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- FORMULARIO --}}
    {{-- ========================================================= --}}

    @if ($showForm)

        <section class="
                        overflow-hidden
                        rounded-3xl
                        border
                        border-blue-100
                        bg-white
                        shadow-[0_8px_30px_rgba(15,23,42,0.05)]
                    ">

            {{-- HEADER --}}

            <div class="
                            border-b
                            border-blue-100
                            bg-gradient-to-r
                            from-blue-50
                            via-white
                            to-white
                            px-6
                            py-5
                            lg:px-7
                        ">

                <div class="
                                flex
                                items-start
                                gap-4
                            ">

                    <div class="
                                    flex
                                    h-12
                                    w-12
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-blue-100
                                    text-blue-700
                                ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>

                    </div>


                    <div>

                        <p class="
                                        text-[10px]
                                        font-semibold
                                        uppercase
                                        tracking-[0.18em]
                                        text-blue-700
                                    ">
                            Administración de acceso
                        </p>

                        <h2 class="
                                        mt-1
                                        text-xl
                                        font-semibold
                                        tracking-tight
                                        text-slate-950
                                    ">
                            {{ $editingUserId
            ? 'Editar usuario'
            : 'Nuevo usuario'
                                    }}
                        </h2>

                        <p class="
                                        mt-1
                                        text-sm
                                        text-slate-500
                                    ">
                            Define la información de acceso
                            y el rol operativo del usuario.
                        </p>

                    </div>

                </div>

            </div>



            {{-- FORMULARIO --}}

            <form wire:submit="save" class="
                            grid
                            gap-5
                            p-6
                            md:grid-cols-2
                            lg:p-7
                        ">

                {{-- NOMBRE --}}

                <div>

                    <label class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-500
                                ">
                        Nombre *
                    </label>

                    <input type="text" wire:model="name" placeholder="Nombre completo" class="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    bg-slate-50/60
                                    px-4
                                    py-3
                                    text-sm
                                    text-slate-900
                                    outline-none
                                    transition
                                    placeholder:text-slate-400
                                    focus:border-blue-500
                                    focus:bg-white
                                    focus:ring-4
                                    focus:ring-blue-500/10
                                ">

                    @error('name')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>



                {{-- CORREO --}}

                <div>

                    <label class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-500
                                ">
                        Correo *
                    </label>

                    <input type="email" wire:model="email" placeholder="usuario@cedis.local" class="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    bg-slate-50/60
                                    px-4
                                    py-3
                                    text-sm
                                    text-slate-900
                                    outline-none
                                    transition
                                    placeholder:text-slate-400
                                    focus:border-blue-500
                                    focus:bg-white
                                    focus:ring-4
                                    focus:ring-blue-500/10
                                ">

                    @error('email')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>



                {{-- ROL --}}

                <div class="md:col-span-2">

                    <label class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-500
                                ">
                        Rol *
                    </label>

                    <select wire:model="role" class="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    bg-slate-50/60
                                    px-4
                                    py-3
                                    text-sm
                                    text-slate-900
                                    outline-none
                                    transition
                                    focus:border-blue-500
                                    focus:bg-white
                                    focus:ring-4
                                    focus:ring-blue-500/10
                                ">

                        <option value="">
                            Selecciona un rol
                        </option>

                        @foreach ($roles as $roleOption)

                            <option value="{{ $roleOption }}">
                                {{ $roleOption }}
                            </option>

                        @endforeach

                    </select>

                    @error('role')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror


                    <div class="
                                    mt-3
                                    grid
                                    gap-2
                                    sm:grid-cols-2
                                    lg:grid-cols-5
                                ">

                        @foreach ([
                                'ADMIN' => 'Acceso completo',
                                'SUPERVISOR' => 'Consulta y supervisión',
                                'RECEPCION' => 'Registro de llegadas',
                                'ARMADO' => 'Proceso de armado',
                                'ENTREGA' => 'Entrega a transportista',
                            ] as $roleName => $roleDescription)

                            @php

                                $roleColor = match ($roleName) {
                                    'ADMIN' =>
                                        'bg-slate-100 text-slate-700',

                                    'SUPERVISOR' =>
                                        'bg-indigo-50 text-indigo-700',

                                    'RECEPCION' =>
                                        'bg-blue-50 text-blue-700',

                                    'ARMADO' =>
                                        'bg-amber-50 text-amber-700',

                                    'ENTREGA' =>
                                        'bg-violet-50 text-violet-700',

                                    default =>
                                        'bg-slate-100 text-slate-700',
                                };

                            @endphp

                            <div class="
                                                    rounded-xl
                                                    {{ $roleColor }}
                                                    px-3
                                                    py-2.5
                                                ">

                                <p class="
                                                        text-[10px]
                                                        font-bold
                                                        uppercase
                                                        tracking-wider
                                                    ">
                                    {{ $roleName }}
                                </p>

                                <p class="
                                                        mt-1
                                                        text-[10px]
                                                        opacity-80
                                                    ">
                                    {{ $roleDescription }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                </div>



                {{-- PASSWORD --}}

                <div>

                    <label for="password" class="
                text-xs
                font-semibold
                uppercase
                tracking-wider
                text-slate-500
            ">
                        Contraseña

                        @if (!$editingUserId)
                            *
                        @endif
                    </label>


                    <input id="password" type="password" wire:model="password" autocomplete="new-password" minlength="8"
                        maxlength="128" @if (!$editingUserId) required @endif placeholder="{{ $editingUserId
            ? 'Déjala vacía para conservar la actual'
            : 'Crea una contraseña segura'
            }}" class="
                mt-2
                w-full
                rounded-2xl
                border
                border-slate-200
                bg-slate-50/60
                px-4
                py-3
                text-sm
                text-slate-900
                outline-none
                transition
                placeholder:text-slate-400
                hover:border-slate-300
                focus:border-blue-500
                focus:bg-white
                focus:ring-4
                focus:ring-blue-500/10
            ">


                    @if ($editingUserId)

                            <p class="
                            mt-2
                            text-xs
                            leading-5
                            text-slate-400
                        ">
                                Déjala vacía para conservar la contraseña
                                actual del usuario.
                            </p>

                    @endif


                    @error('password')

                            <div class="
                            mt-2
                            flex
                            items-start
                            gap-2
                            text-xs
                            font-medium
                            text-red-600
                        ">

                                <span class="
                                flex
                                h-5
                                w-5
                                shrink-0
                                items-center
                                justify-center
                                rounded-full
                                bg-red-100
                                text-[10px]
                                font-bold
                            ">
                                    !
                                </span>

                                <span>
                                    {{ $message }}
                                </span>

                            </div>

                    @enderror


                    {{-- REQUISITOS --}}

                    <div class="
                mt-4
                rounded-2xl
                border
                border-blue-100
                bg-blue-50/50
                p-4
            ">

                        <div class="
                    flex
                    items-start
                    gap-3
                ">

                            <div class="
                        flex
                        h-7
                        w-7
                        shrink-0
                        items-center
                        justify-center
                        rounded-lg
                        bg-blue-100
                        text-xs
                        font-bold
                        text-blue-700
                    ">
                                i
                            </div>


                            <div class="min-w-0">

                                <p class="
                            text-xs
                            font-semibold
                            text-blue-900
                        ">
                                    Requisitos de seguridad
                                </p>


                                <div class="
                            mt-2
                            grid
                            gap-x-6
                            gap-y-1.5
                            text-xs
                            leading-5
                            text-blue-700
                            sm:grid-cols-2
                        ">

                                    <span>
                                        • Mínimo 8 caracteres
                                    </span>

                                    <span>
                                        • Una letra mayúscula
                                    </span>

                                    <span>
                                        • Una letra minúscula
                                    </span>

                                    <span>
                                        • Al menos un número
                                    </span>

                                    <span>
                                        • Al menos un símbolo
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- CONFIRMAR PASSWORD --}}

                <div>

                    <label
                        for="password_confirmation"
                        class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-wider
                            text-slate-500
                        "
                    >
                        Confirmar contraseña

                        @if (! $editingUserId)
                            *
                        @endif
                    </label>


                    <input
                        id="password_confirmation"
                        type="password"
                        wire:model="password_confirmation"
                        autocomplete="new-password"
                        minlength="8"
                        maxlength="128"
                        @if (! $editingUserId)
                            required
                        @endif
                        placeholder="Repite la contraseña"
                        class="
                            mt-2
                            w-full
                            rounded-2xl
                            border
                            border-slate-200
                            bg-slate-50/60
                            px-4
                            py-3
                            text-sm
                            text-slate-900
                            outline-none
                            transition
                            placeholder:text-slate-400
                            hover:border-slate-300
                            focus:border-blue-500
                            focus:bg-white
                            focus:ring-4
                            focus:ring-blue-500/10
                        "
                    >


                    @error('password_confirmation')

                        <p
                            class="
                                mt-2
                                text-xs
                                font-medium
                                text-red-600
                            "
                        >
                            {{ $message }}
                        </p>

                    @enderror

                </div>



                {{-- ACCIONES --}}

                <div class="
                                flex
                                flex-col-reverse
                                gap-3
                                border-t
                                border-slate-100
                                pt-6
                                md:col-span-2
                                sm:flex-row
                                sm:items-center
                                sm:justify-end
                            ">

                    <button type="button" wire:click="cancel" class="
                                    rounded-xl
                                    border
                                    border-slate-300
                                    bg-white
                                    px-5
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-slate-700
                                    transition
                                    hover:bg-slate-50
                                ">
                        Cancelar
                    </button>


                    <button type="submit" wire:loading.attr="disabled" wire:target="save" class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-blue-600
                                    px-5
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-white
                                    shadow-sm
                                    transition
                                    hover:bg-blue-700
                                    hover:shadow-md
                                    disabled:cursor-not-allowed
                                    disabled:opacity-50
                                ">

                        <span wire:loading.remove wire:target="save">
                            {{ $editingUserId
            ? 'Guardar cambios'
            : 'Crear usuario'
                                    }}
                        </span>

                        <span wire:loading wire:target="save">
                            Guardando...
                        </span>

                    </button>

                </div>

            </form>

        </section>

    @endif



    {{-- ========================================================= --}}
    {{-- LISTADO --}}
    {{-- ========================================================= --}}

    <section class="
            overflow-hidden
            rounded-3xl
            border
            border-slate-200/80
            bg-white
            shadow-[0_8px_30px_rgba(15,23,42,0.04)]
        ">

        {{-- HEADER --}}

        <div class="
                flex
                items-center
                gap-4
                border-b
                border-slate-100
                px-6
                py-5
                lg:px-7
            ">

            <div class="
                    flex
                    h-11
                    w-11
                    shrink-0
                    items-center
                    justify-center
                    rounded-2xl
                    bg-blue-50
                    text-blue-600
                ">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                    stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72M18 18.72v-.719c0-.502-.1-.98-.282-1.415M18 18.72a12.98 12.98 0 0 1-6 1.42c-2.162 0-4.2-.524-6-1.452m12 0a6 6 0 0 0-12 0M15 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

            </div>

            <div>

                <h2 class="
                        text-lg
                        font-semibold
                        text-slate-950
                    ">
                    Usuarios
                </h2>

                <p class="
                        mt-0.5
                        text-sm
                        text-slate-500
                    ">
                    Personal autorizado para acceder
                    a Control de Unidades CEDIS.
                </p>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- DESKTOP --}}
        {{-- ===================================================== --}}

        <div class="hidden overflow-x-auto md:block">

            <table class="w-full">

                <thead class="
                        border-b
                        border-slate-100
                        bg-slate-50/70
                    ">

                    <tr class="
                            text-left
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.12em]
                            text-slate-400
                        ">

                        <th class="px-6 py-4 lg:px-7">
                            Usuario
                        </th>

                        <th class="px-6 py-4">
                            Rol
                        </th>

                        <th class="px-6 py-4">
                            Estado
                        </th>

                        <th class="
                                px-6
                                py-4
                                text-right
                                lg:px-7
                            ">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody class="
                        divide-y
                        divide-slate-100
                    ">

                    @forelse ($users as $user)

                                        @php

                                            $roleName =
                                                $user->roles
                                                    ->first()
                                                        ?->name
                                                ?? 'Sin rol';


                                            $roleStyle =
                                                match ($roleName) {

                                                    'ADMIN' =>
                                                        'bg-slate-100 text-slate-700 ring-slate-200',

                                                    'SUPERVISOR' =>
                                                        'bg-indigo-50 text-indigo-700 ring-indigo-100',

                                                    'RECEPCION' =>
                                                        'bg-blue-50 text-blue-700 ring-blue-100',

                                                    'ARMADO' =>
                                                        'bg-amber-50 text-amber-700 ring-amber-100',

                                                    'ENTREGA' =>
                                                        'bg-violet-50 text-violet-700 ring-violet-100',

                                                    default =>
                                                        'bg-slate-100 text-slate-700 ring-slate-200',
                                                };

                                        @endphp


                                        <tr class="
                                                                        group
                                                                        transition
                                                                        hover:bg-slate-50/70
                                                                    ">

                                            {{-- USUARIO --}}

                                            <td class="
                                                                            px-6
                                                                            py-5
                                                                            lg:px-7
                                                                        ">

                                                <div class="
                                                                                flex
                                                                                items-center
                                                                                gap-4
                                                                            ">

                                                    <div class="
                                                                                    flex
                                                                                    h-11
                                                                                    w-11
                                                                                    shrink-0
                                                                                    items-center
                                                                                    justify-center
                                                                                    rounded-2xl
                                                                                    bg-blue-50
                                                                                    text-sm
                                                                                    font-bold
                                                                                    text-blue-700
                                                                                ">
                                                        {{ strtoupper(
                            substr(
                                $user->name,
                                0,
                                1
                            )
                        ) }}
                                                    </div>


                                                    <div class="min-w-0">

                                                        <div class="
                                                                                        flex
                                                                                        items-center
                                                                                        gap-2
                                                                                    ">

                                                            <p class="
                                                                                            truncate
                                                                                            text-sm
                                                                                            font-semibold
                                                                                            text-slate-950
                                                                                        ">
                                                                {{ $user->name }}
                                                            </p>


                                                            @if (
                                                                    $user->id
                                                                    === auth()->id()
                                                                )

                                                                <span class="
                                                                                                        rounded-full
                                                                                                        bg-blue-50
                                                                                                        px-2
                                                                                                        py-0.5
                                                                                                        text-[9px]
                                                                                                        font-semibold
                                                                                                        uppercase
                                                                                                        tracking-wider
                                                                                                        text-blue-600
                                                                                                    ">
                                                                    Tú
                                                                </span>

                                                            @endif

                                                        </div>


                                                        <p class="
                                                                                        mt-1
                                                                                        truncate
                                                                                        text-xs
                                                                                        text-slate-500
                                                                                    ">
                                                            {{ $user->email }}
                                                        </p>

                                                    </div>

                                                </div>

                                            </td>



                                            {{-- ROL --}}

                                            <td class="px-6 py-5">

                                                <span class="
                                                                                inline-flex
                                                                                rounded-full
                                                                                px-3
                                                                                py-1.5
                                                                                text-xs
                                                                                font-semibold
                                                                                ring-1
                                                                                ring-inset
                                                                                {{ $roleStyle }}
                                                                            ">
                                                    {{ $roleName }}
                                                </span>

                                            </td>



                                            {{-- ESTADO --}}

                                            <td class="px-6 py-5">

                                                @if ($user->active)

                                                    <span class="
                                                                                            inline-flex
                                                                                            items-center
                                                                                            gap-2
                                                                                            rounded-full
                                                                                            bg-emerald-50
                                                                                            px-3
                                                                                            py-1.5
                                                                                            text-xs
                                                                                            font-semibold
                                                                                            text-emerald-700
                                                                                            ring-1
                                                                                            ring-inset
                                                                                            ring-emerald-100
                                                                                        ">

                                                        <span class="
                                                                                                h-1.5
                                                                                                w-1.5
                                                                                                rounded-full
                                                                                                bg-emerald-500
                                                                                            "></span>

                                                        Activo

                                                    </span>

                                                @else

                                                    <span class="
                                                                                            inline-flex
                                                                                            items-center
                                                                                            gap-2
                                                                                            rounded-full
                                                                                            bg-slate-100
                                                                                            px-3
                                                                                            py-1.5
                                                                                            text-xs
                                                                                            font-semibold
                                                                                            text-slate-500
                                                                                            ring-1
                                                                                            ring-inset
                                                                                            ring-slate-200
                                                                                        ">

                                                        <span class="
                                                                                                h-1.5
                                                                                                w-1.5
                                                                                                rounded-full
                                                                                                bg-slate-400
                                                                                            "></span>

                                                        Inactivo

                                                    </span>

                                                @endif

                                            </td>



                                            {{-- ACCIONES --}}

                                            <td class="
                                                                            px-6
                                                                            py-5
                                                                            lg:px-7
                                                                        ">

                                                <div class="
                                                                                flex
                                                                                justify-end
                                                                                gap-2
                                                                            ">

                                                    <button type="button" wire:click="
                                                                                    edit({{ $user->id }})
                                                                                " class="
                                                                                    rounded-xl
                                                                                    border
                                                                                    border-slate-200
                                                                                    bg-white
                                                                                    px-3.5
                                                                                    py-2.5
                                                                                    text-xs
                                                                                    font-semibold
                                                                                    text-slate-700
                                                                                    shadow-sm
                                                                                    transition
                                                                                    hover:border-blue-200
                                                                                    hover:bg-blue-50
                                                                                    hover:text-blue-700
                                                                                ">
                                                        Editar
                                                    </button>


                                                    @if (
                                                                                $user->id
                                                                                !== auth()->id()
                                                                            )

                                                                            <button type="button" wire:click="
                                                                                                                                        toggleActive(
                                                                                                                                            {{ $user->id }}
                                                                                                                                        )
                                                                                                                                    " wire:confirm="
                                                                                                                                        ¿Confirmas este cambio?
                                                                                                                                    " class="
                                                                                                                                        rounded-xl
                                                                                                                                        px-3.5
                                                                                                                                        py-2.5
                                                                                                                                        text-xs
                                                                                                                                        font-semibold
                                                                                                                                        transition

                                                                                                                                        {{ $user->active
                                                        ? 'bg-red-50 text-red-700 hover:bg-red-100'
                                                        : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'
                                                                                                                                        }}
                                                                                                                                    ">
                                                                                {{ $user->active
                                                        ? 'Desactivar'
                                                        : 'Activar'
                                                                                                                                    }}
                                                                            </button>

                                                    @endif

                                                </div>

                                            </td>

                                        </tr>


                    @empty

                        <tr>

                            <td colspan="4" class="
                                            px-6
                                            py-16
                                            text-center
                                        ">

                                <p class="
                                                text-sm
                                                font-semibold
                                                text-slate-700
                                            ">
                                    No se encontraron usuarios
                                </p>

                                <p class="
                                                mt-1
                                                text-sm
                                                text-slate-500
                                            ">
                                    Intenta modificar la búsqueda.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



        {{-- ===================================================== --}}
        {{-- MOBILE --}}
        {{-- ===================================================== --}}

        <div class="
                divide-y
                divide-slate-100
                md:hidden
            ">

            @forelse ($users as $user)

                        @php

                            $roleName =
                                $user->roles
                                    ->first()
                                        ?->name
                                ?? 'Sin rol';

                            $roleStyle =
                                match ($roleName) {

                                    'ADMIN' =>
                                        'bg-slate-100 text-slate-700',

                                    'SUPERVISOR' =>
                                        'bg-indigo-50 text-indigo-700',

                                    'RECEPCION' =>
                                        'bg-blue-50 text-blue-700',

                                    'ARMADO' =>
                                        'bg-amber-50 text-amber-700',

                                    'ENTREGA' =>
                                        'bg-violet-50 text-violet-700',

                                    default =>
                                        'bg-slate-100 text-slate-700',
                                };

                        @endphp


                        <article class="p-5">

                            <div class="
                                                    flex
                                                    items-start
                                                    gap-3
                                                ">

                                <div class="
                                                        flex
                                                        h-11
                                                        w-11
                                                        shrink-0
                                                        items-center
                                                        justify-center
                                                        rounded-2xl
                                                        bg-blue-50
                                                        text-sm
                                                        font-bold
                                                        text-blue-700
                                                    ">
                                    {{ strtoupper(
                    substr(
                        $user->name,
                        0,
                        1
                    )
                ) }}
                                </div>


                                <div class="min-w-0 flex-1">

                                    <div class="
                                                            flex
                                                            flex-wrap
                                                            items-center
                                                            gap-2
                                                        ">

                                        <p class="
                                                                font-semibold
                                                                text-slate-950
                                                            ">
                                            {{ $user->name }}
                                        </p>


                                        @if (
                                                $user->id
                                                === auth()->id()
                                            )

                                            <span class="
                                                                            rounded-full
                                                                            bg-blue-50
                                                                            px-2
                                                                            py-0.5
                                                                            text-[9px]
                                                                            font-semibold
                                                                            text-blue-600
                                                                        ">
                                                Tú
                                            </span>

                                        @endif

                                    </div>


                                    <p class="
                                                            mt-1
                                                            break-all
                                                            text-xs
                                                            text-slate-500
                                                        ">
                                        {{ $user->email }}
                                    </p>

                                </div>

                            </div>



                            <div class="
                                                    mt-4
                                                    flex
                                                    flex-wrap
                                                    gap-2
                                                ">

                                <span class="
                                                        rounded-full
                                                        px-3
                                                        py-1.5
                                                        text-xs
                                                        font-semibold
                                                        {{ $roleStyle }}
                                                    ">
                                    {{ $roleName }}
                                </span>


                                @if ($user->active)

                                    <span class="
                                                                    rounded-full
                                                                    bg-emerald-50
                                                                    px-3
                                                                    py-1.5
                                                                    text-xs
                                                                    font-semibold
                                                                    text-emerald-700
                                                                ">
                                        ● Activo
                                    </span>

                                @else

                                    <span class="
                                                                    rounded-full
                                                                    bg-slate-100
                                                                    px-3
                                                                    py-1.5
                                                                    text-xs
                                                                    font-semibold
                                                                    text-slate-500
                                                                ">
                                        ● Inactivo
                                    </span>

                                @endif

                            </div>



                            <div class="
                                                    mt-5
                                                    flex
                                                    gap-2
                                                ">

                                <button type="button" wire:click="
                                                        edit({{ $user->id }})
                                                    " class="
                                                        flex-1
                                                        rounded-xl
                                                        border
                                                        border-slate-200
                                                        bg-white
                                                        px-4
                                                        py-2.5
                                                        text-sm
                                                        font-semibold
                                                        text-slate-700
                                                    ">
                                    Editar
                                </button>


                                @if (
                                                $user->id
                                                !== auth()->id()
                                            )

                                            <button type="button" wire:click="
                                                                                    toggleActive(
                                                                                        {{ $user->id }}
                                                                                    )
                                                                                " wire:confirm="
                                                                                    ¿Confirmas este cambio?
                                                                                " class="
                                                                                    flex-1
                                                                                    rounded-xl
                                                                                    px-4
                                                                                    py-2.5
                                                                                    text-sm
                                                                                    font-semibold

                                                                                    {{ $user->active
                                    ? 'bg-red-50 text-red-700'
                                    : 'bg-emerald-50 text-emerald-700'
                                                                                    }}
                                                                                ">
                                                {{ $user->active
                                    ? 'Desactivar'
                                    : 'Activar'
                                                                                }}
                                            </button>

                                @endif

                            </div>

                        </article>


            @empty

                <div class="
                                px-6
                                py-14
                                text-center
                            ">

                    <p class="
                                    text-sm
                                    font-semibold
                                    text-slate-700
                                ">
                        No se encontraron usuarios.
                    </p>

                </div>

            @endforelse

        </div>



        {{-- ===================================================== --}}
        {{-- PAGINACIÓN --}}
        {{-- ===================================================== --}}

        @if ($users->hasPages())

            <div class="
                            border-t
                            border-slate-100
                            bg-slate-50/40
                            px-6
                            py-4
                        ">
                {{ $users->links() }}
            </div>

        @endif

    </section>

</div>