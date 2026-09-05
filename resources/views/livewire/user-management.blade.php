<div class="space-y-6">

    {{-- MENSAJES --}}

    @if (session('success'))

    <div
        class="
                rounded-xl
                border
                border-emerald-200
                bg-emerald-50
                px-5
                py-4
                text-sm
                font-medium
                text-emerald-800
            ">
        {{ session('success') }}
    </div>

    @endif


    @if (session('error'))

    <div
        class="
                rounded-xl
                border
                border-red-200
                bg-red-50
                px-5
                py-4
                text-sm
                font-medium
                text-red-800
            ">
        {{ session('error') }}
    </div>

    @endif



    {{-- HERRAMIENTAS --}}

    <section
        class="
            rounded-2xl
            border
            border-slate-200
            bg-white
            p-5
            shadow-sm
        ">

        <div
            class="
                flex
                flex-col
                gap-4
                lg:flex-row
                lg:items-end
                lg:justify-between
            ">

            <div class="flex-1">

                <label
                    class="
                        text-sm
                        font-medium
                        text-slate-700
                    ">
                    Buscar usuario
                </label>

                <input
                    type="search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Nombre, correo o rol..."
                    class="
                        mt-2
                        w-full
                        rounded-xl
                        border
                        border-slate-300
                        px-4
                        py-3
                        text-sm
                        outline-none
                        transition
                        focus:border-blue-500
                        focus:ring-4
                        focus:ring-blue-500/10
                    ">

            </div>


            <button
                type="button"
                wire:click="create"
                class="
                    inline-flex
                    items-center
                    justify-center
                    rounded-xl
                    bg-slate-950
                    px-5
                    py-3
                    text-sm
                    font-semibold
                    text-white
                    transition
                    hover:bg-slate-800
                ">
                + Nuevo usuario
            </button>

        </div>

    </section>



    {{-- FORMULARIO --}}

    @if ($showForm)

    <section
        class="
                rounded-2xl
                border
                border-blue-200
                bg-white
                shadow-sm
            ">

        <div
            class="
                    border-b
                    border-slate-200
                    px-6
                    py-5
                ">

            <h2
                class="
                        font-semibold
                        text-slate-950
                    ">
                {{ $editingUserId
                        ? 'Editar usuario'
                        : 'Nuevo usuario'
                    }}
            </h2>

            <p
                class="
                        mt-1
                        text-sm
                        text-slate-500
                    ">
                Define los datos de acceso
                y el área de trabajo.
            </p>

        </div>


        <form
            wire:submit="save"
            class="
                    grid
                    gap-5
                    p-6
                    md:grid-cols-2
                ">

            {{-- NOMBRE --}}

            <div>

                <label class="text-sm font-medium">
                    Nombre *
                </label>

                <input
                    type="text"
                    wire:model="name"
                    class="
                            mt-2
                            w-full
                            rounded-xl
                            border
                            border-slate-300
                            px-4
                            py-3
                            text-sm
                        ">

                @error('name')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- CORREO --}}

            <div>

                <label class="text-sm font-medium">
                    Correo *
                </label>

                <input
                    type="email"
                    wire:model="email"
                    class="
                            mt-2
                            w-full
                            rounded-xl
                            border
                            border-slate-300
                            px-4
                            py-3
                            text-sm
                        ">

                @error('email')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- ROL --}}

            <div>

                <label class="text-sm font-medium">
                    Rol *
                </label>

                <select
                    wire:model="role"
                    class="
                            mt-2
                            w-full
                            rounded-xl
                            border
                            border-slate-300
                            bg-white
                            px-4
                            py-3
                            text-sm
                        ">

                    <option value="">
                        Selecciona un rol
                    </option>

                    @foreach ($roles as $roleOption)

                    <option
                        value="{{ $roleOption }}">
                        {{ $roleOption }}
                    </option>

                    @endforeach

                </select>

                @error('role')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            <div></div>


            {{-- PASSWORD --}}

            <div>

                <label class="text-sm font-medium">
                    Contraseña
                    {{ $editingUserId ? '' : '*' }}
                </label>

                <input
                    type="password"
                    wire:model="password"
                    autocomplete="new-password"
                    class="
                            mt-2
                            w-full
                            rounded-xl
                            border
                            border-slate-300
                            px-4
                            py-3
                            text-sm
                        ">

                @if ($editingUserId)

                <p
                    class="
                                mt-1
                                text-xs
                                text-slate-400
                            ">
                    Déjala vacía para conservar
                    la contraseña actual.
                </p>

                @endif

                @error('password')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- CONFIRM PASSWORD --}}

            <div>

                <label class="text-sm font-medium">
                    Confirmar contraseña
                </label>

                <input
                    type="password"
                    wire:model="password_confirmation"
                    autocomplete="new-password"
                    class="
                            mt-2
                            w-full
                            rounded-xl
                            border
                            border-slate-300
                            px-4
                            py-3
                            text-sm
                        ">

            </div>


            {{-- ACCIONES --}}

            <div
                class="
                        flex
                        flex-col-reverse
                        gap-3
                        border-t
                        border-slate-100
                        pt-5
                        md:col-span-2
                        sm:flex-row
                        sm:justify-end
                    ">

                <button
                    type="button"
                    wire:click="cancel"
                    class="
                            rounded-xl
                            border
                            border-slate-300
                            px-5
                            py-3
                            text-sm
                            font-semibold
                            text-slate-700
                        ">
                    Cancelar
                </button>


                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="
                            rounded-xl
                            bg-blue-600
                            px-5
                            py-3
                            text-sm
                            font-semibold
                            text-white
                            transition
                            hover:bg-blue-700
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



    {{-- LISTADO --}}

    <section
        class="
            overflow-hidden
            rounded-2xl
            border
            border-slate-200
            bg-white
            shadow-sm
        ">

        <div
            class="
                border-b
                border-slate-200
                px-6
                py-5
            ">

            <h2 class="font-semibold">
                Usuarios
            </h2>

            <p
                class="
                    mt-1
                    text-sm
                    text-slate-500
                ">
                Personal con acceso al sistema CEDIS.
            </p>

        </div>


        {{-- DESKTOP --}}

        <div class="hidden overflow-x-auto md:block">

            <table class="w-full">

                <thead
                    class="
                        bg-slate-50
                        text-left
                        text-xs
                        uppercase
                        tracking-wider
                        text-slate-500
                    ">

                    <tr>

                        <th class="px-6 py-4">
                            Usuario
                        </th>

                        <th class="px-6 py-4">
                            Rol
                        </th>

                        <th class="px-6 py-4">
                            Estado
                        </th>

                        <th class="px-6 py-4 text-right">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody
                    class="
                        divide-y
                        divide-slate-100
                    ">

                    @forelse ($users as $user)

                    <tr class="hover:bg-slate-50">

                        <td class="px-6 py-5">

                            <p
                                class="
                                        text-sm
                                        font-semibold
                                        text-slate-950
                                    ">
                                {{ $user->name }}

                                @if ($user->id === auth()->id())

                                <span
                                    class="
                                                ml-1
                                                text-xs
                                                font-normal
                                                text-slate-400
                                            ">
                                    (Tú)
                                </span>

                                @endif
                            </p>

                            <p
                                class="
                                        mt-1
                                        text-xs
                                        text-slate-500
                                    ">
                                {{ $user->email }}
                            </p>

                        </td>


                        <td class="px-6 py-5">

                            @foreach ($user->roles as $userRole)

                            <span
                                class="
                                            rounded-full
                                            bg-slate-100
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-slate-700
                                        ">
                                {{ $userRole->name }}
                            </span>

                            @endforeach

                        </td>


                        <td class="px-6 py-5">

                            @if ($user->active)

                            <span
                                class="
                                            rounded-full
                                            bg-emerald-100
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-emerald-700
                                        ">
                                Activo
                            </span>

                            @else

                            <span
                                class="
                                            rounded-full
                                            bg-slate-100
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-slate-500
                                        ">
                                Inactivo
                            </span>

                            @endif

                        </td>


                        <td class="px-6 py-5">

                            <div
                                class="
                                        flex
                                        justify-end
                                        gap-2
                                    ">

                                <button
                                    type="button"
                                    wire:click="edit({{ $user->id }})"
                                    class="
                                            rounded-lg
                                            border
                                            border-slate-300
                                            px-3
                                            py-2
                                            text-xs
                                            font-semibold
                                            text-slate-700
                                        ">
                                    Editar
                                </button>


                                @if ($user->id !== auth()->id())

                                <button
                                    type="button"
                                    wire:click="
                                                toggleActive(
                                                    {{ $user->id }}
                                                )
                                            "
                                    wire:confirm="
                                                ¿Confirmas este cambio?
                                            "
                                    class="
                                                rounded-lg
                                                px-3
                                                py-2
                                                text-xs
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

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="4"
                            class="
                                    px-6
                                    py-12
                                    text-center
                                    text-sm
                                    text-slate-500
                                ">
                            No se encontraron usuarios.
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MOBILE --}}

        <div
            class="
                divide-y
                divide-slate-100
                md:hidden
            ">

            @foreach ($users as $user)

            <article class="p-5">

                <div
                    class="
                            flex
                            items-start
                            justify-between
                            gap-3
                        ">

                    <div>

                        <p class="font-semibold">
                            {{ $user->name }}
                        </p>

                        <p
                            class="
                                    mt-1
                                    text-xs
                                    text-slate-500
                                ">
                            {{ $user->email }}
                        </p>

                    </div>


                    @if ($user->active)

                    <span
                        class="
                                    rounded-full
                                    bg-emerald-100
                                    px-2.5
                                    py-1
                                    text-xs
                                    font-semibold
                                    text-emerald-700
                                ">
                        Activo
                    </span>

                    @else

                    <span
                        class="
                                    rounded-full
                                    bg-slate-100
                                    px-2.5
                                    py-1
                                    text-xs
                                    font-semibold
                                    text-slate-500
                                ">
                        Inactivo
                    </span>

                    @endif

                </div>


                <p
                    class="
                            mt-3
                            text-xs
                            font-semibold
                            text-slate-600
                        ">
                    {{ $user->roles
                            ->first()
                            ?->name
                            ?? 'Sin rol'
                        }}
                </p>


                <div
                    class="
                            mt-4
                            flex
                            gap-2
                        ">

                    <button
                        type="button"
                        wire:click="edit({{ $user->id }})"
                        class="
                                flex-1
                                rounded-xl
                                border
                                border-slate-300
                                px-4
                                py-2.5
                                text-sm
                                font-semibold
                            ">
                        Editar
                    </button>


                    @if ($user->id !== auth()->id())

                    <button
                        type="button"
                        wire:click="
                                    toggleActive(
                                        {{ $user->id }}
                                    )
                                "
                        wire:confirm="
                                    ¿Confirmas este cambio?
                                "
                        class="
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

            @endforeach

        </div>


        @if ($users->hasPages())

        <div
            class="
                    border-t
                    border-slate-200
                    px-6
                    py-4
                ">
            {{ $users->links() }}
        </div>

        @endif

    </section>

</div>