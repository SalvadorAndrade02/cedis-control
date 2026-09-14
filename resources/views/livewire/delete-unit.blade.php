<div>

    {{-- ========================================================= --}}
    {{-- BOTÓN ELIMINAR --}}
    {{-- ========================================================= --}}

    @if (!$showConfirmation)

        <button type="button" wire:click="open" class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-xl
                                    border
                                    border-red-200
                                    bg-red-50
                                    px-4
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-red-700
                                    transition
                                    hover:border-red-300
                                    hover:bg-red-100
                                    active:scale-[.98]
                                ">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
                class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-1.327L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.477c0-1.092-.84-2.007-1.932-2.04a52.59 52.59 0 0 0-3.636 0C9.09 2.47 8.25 3.385 8.25 4.477v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>

            Eliminar unidad

        </button>

    @endif



    {{-- ========================================================= --}}
    {{-- MODAL DE CONFIRMACIÓN --}}
    {{-- ========================================================= --}}

    @if ($showConfirmation)

        <div class="
                                    fixed
                                    inset-0
                                    z-[100]
                                    flex
                                    items-center
                                    justify-center
                                    overflow-y-auto
                                    bg-slate-950/60
                                    px-4
                                    py-8
                                    backdrop-blur-[2px]
                                " wire:click="cancel" role="dialog" aria-modal="true" aria-labelledby="delete-unit-title">

            {{-- MODAL --}}

            <div wire:click.stop class="
                                        relative
                                        w-full
                                        max-w-lg
                                        overflow-hidden
                                        rounded-3xl
                                        bg-white
                                        shadow-[0_24px_80px_rgba(15,23,42,0.35)]
                                    ">

                {{-- ================================================= --}}
                {{-- CABECERA DE ADVERTENCIA --}}
                {{-- ================================================= --}}

                <div class="
                                            border-b
                                            border-red-100
                                            bg-gradient-to-br
                                            from-red-50
                                            to-white
                                            px-6
                                            py-6
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
                                                    bg-red-100
                                                    text-red-700
                                                ">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.052 3.38c.865-1.5 3.03-1.5 3.896 0l7.355 12.746ZM12 15.75h.008v.008H12v-.008Z" />
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <p class="
                                                        text-[10px]
                                                        font-semibold
                                                        uppercase
                                                        tracking-[0.16em]
                                                        text-red-600
                                                    ">
                                Acción administrativa
                            </p>

                            <h2 id="delete-unit-title" class="
                                                        mt-1
                                                        text-xl
                                                        font-semibold
                                                        tracking-tight
                                                        text-slate-950
                                                    ">
                                Eliminar unidad
                            </h2>

                            <p class="
                                                        mt-1
                                                        text-sm
                                                        leading-6
                                                        text-slate-500
                                                    ">
                                Confirma que deseas retirar esta unidad
                                de la operación normal del CEDIS.
                            </p>

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- CONTENIDO --}}
                {{-- ================================================= --}}

                <div class="
                                            max-h-[70vh]
                                            overflow-y-auto
                                            px-6
                                            py-6
                                        ">

                    {{-- ADVERTENCIA PRINCIPAL --}}

                    <div class="
                            rounded-2xl
                            border
                            border-red-100
                            bg-red-50/60
                            p-4
                        ">

                        <div class="
                                flex
                                items-start
                                gap-3
                            ">

                            <div class="
                                    flex
                                    h-8
                                    w-8
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-red-100
                                    text-sm
                                    font-bold
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
                                    Estás a punto de retirar esta unidad
                                </p>

                                <p class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-red-700
                                    ">
                                    La unidad dejará de estar disponible
                                    dentro del flujo operativo normal del CEDIS.
                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- MOTIVO --}}

                    <div class="mt-6">

                        <label class="
                                                    text-xs
                                                    font-semibold
                                                    uppercase
                                                    tracking-wider
                                                    text-slate-600
                                                ">
                            Motivo de eliminación
                            <span class="
                ml-1
                font-normal
                normal-case
                tracking-normal
                text-slate-400
            ">
                                (opcional)
                            </span>
                        </label>

                        <p class="
            mt-1
            text-xs
            leading-5
            text-slate-500
        ">
                            Puedes indicar por qué se retira la unidad.
                            Si se proporciona, el motivo quedará registrado
                            dentro de la trazabilidad administrativa.
                        </p>


                        <textarea wire:model="reason" rows="4" placeholder="Ej. La factura fue importada por error..."
                            class="
                                                    mt-3
                                                    w-full
                                                    resize-none
                                                    rounded-2xl
                                                    border
                                                    border-slate-200
                                                    bg-slate-50/70
                                                    px-4
                                                    py-3
                                                    text-sm
                                                    text-slate-900
                                                    outline-none
                                                    transition
                                                    placeholder:text-slate-400
                                                    focus:border-red-400
                                                    focus:bg-white
                                                    focus:ring-4
                                                    focus:ring-red-500/10
                                                "></textarea>


                        @error('reason')

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

                </div>



                {{-- ================================================= --}}
                {{-- ACCIONES --}}
                {{-- ================================================= --}}

                <div class="
                                            flex
                                            flex-col-reverse
                                            gap-3
                                            border-t
                                            border-slate-100
                                            bg-slate-50/60
                                            px-6
                                            py-5
                                            sm:flex-row
                                            sm:justify-end
                                        ">

                    <button type="button" wire:click="cancel" wire:loading.attr="disabled" wire:target="delete" class="
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
                                                disabled:opacity-50
                                            ">
                        Cancelar
                    </button>


                    <button type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete" class="
                                                inline-flex
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-xl
                                                bg-red-600
                                                px-5
                                                py-3
                                                text-sm
                                                font-semibold
                                                text-white
                                                shadow-sm
                                                transition
                                                hover:bg-red-700
                                                active:scale-[.98]
                                                disabled:cursor-not-allowed
                                                disabled:opacity-50
                                            ">

                        <span wire:loading.remove wire:target="delete">
                            Eliminar unidad
                        </span>

                        <span wire:loading wire:target="delete">
                            Eliminando...
                        </span>

                    </button>

                </div>

            </div>

        </div>

    @endif

</div>