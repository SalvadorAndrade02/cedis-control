<div class="space-y-5">

    {{-- ERROR --}}

    @if ($errorMessage)

        <div class="
                    flex
                    items-start
                    gap-3
                    rounded-2xl
                    border
                    border-red-200
                    bg-red-50
                    px-5
                    py-4
                    text-sm
                    text-red-700
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
                        font-bold
                        text-red-700
                    ">
                !
            </div>

            <div>
                <p class="font-semibold">
                    No fue posible completar la operación
                </p>

                <p class="mt-1">
                    {{ $errorMessage }}
                </p>
            </div>

        </div>

    @endif


    <section class="
            overflow-hidden
            rounded-3xl
            border
            border-amber-100
            bg-white
            shadow-[0_8px_30px_rgba(15,23,42,0.05)]
        ">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}

        <div class="
                border-b
                border-amber-100
                bg-gradient-to-r
                from-amber-50
                via-white
                to-white
                px-6
                py-5
                lg:px-7
            ">

            <div class="
                    flex
                    flex-col
                    gap-4
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                ">

                <div class="
                        flex
                        items-center
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
                            bg-amber-100
                            text-amber-700
                        ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </div>


                    <div>

                        <p class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.18em]
                                text-amber-700
                            ">
                            Control de armado
                        </p>

                        <h2 class="
                                mt-1
                                text-xl
                                font-semibold
                                tracking-tight
                                text-slate-950
                            ">
                            Tiempo efectivo de trabajo
                        </h2>

                        <p class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                            Control de inicio, pausas y reanudación
                            del proceso de armado.
                        </p>

                    </div>

                </div>


                @if ($session)

                            <span class="
                                        inline-flex
                                        w-fit
                                        items-center
                                        gap-2
                                        rounded-full
                                        px-3.5
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        {{ $session
                    ->status
                    ->badgeClasses()
                                        }}
                                    ">

                                <span class="
                                            h-1.5
                                            w-1.5
                                            rounded-full
                                            bg-current
                                        "></span>

                                {{ $session
                    ->status
                    ->label()
                                    }}

                            </span>

                @else

                    <span class="
                                inline-flex
                                w-fit
                                items-center
                                gap-2
                                rounded-full
                                bg-slate-100
                                px-3.5
                                py-1.5
                                text-xs
                                font-semibold
                                text-slate-600
                            ">

                        <span class="
                                    h-1.5
                                    w-1.5
                                    rounded-full
                                    bg-slate-400
                                "></span>

                        Sin iniciar

                    </span>

                @endif

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- CONTENIDO --}}
        {{-- ===================================================== --}}

        <div class="p-6 lg:p-8">


            {{-- ================================================= --}}
            {{-- SIN INICIAR --}}
            {{-- ================================================= --}}

            @if (!$session)

                <div class="
                            mx-auto
                            max-w-2xl
                            py-6
                            text-center
                        ">

                    <div class="
                                mx-auto
                                flex
                                h-16
                                w-16
                                items-center
                                justify-center
                                rounded-2xl
                                bg-slate-100
                                text-slate-500
                            ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6"
                            stroke="currentColor" class="h-8 w-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </div>


                    <p class="
                                mt-5
                                font-mono
                                text-5xl
                                font-bold
                                tracking-tight
                                text-slate-950
                                sm:text-6xl
                            ">
                        00:00:00
                    </p>


                    <p class="
                                mt-2
                                text-xs
                                font-semibold
                                uppercase
                                tracking-[0.16em]
                                text-slate-400
                            ">
                        Tiempo efectivo
                    </p>


                    <p class="
                                mx-auto
                                mt-5
                                max-w-md
                                text-sm
                                leading-6
                                text-slate-500
                            ">
                        El tiempo comenzará a registrarse cuando
                        el armador inicie formalmente el proceso
                        de esta unidad.
                    </p>


                    <button type="button" wire:click="start" wire:loading.attr="disabled" wire:target="start" class="
                                mt-7
                                inline-flex
                                items-center
                                justify-center
                                gap-2
                                rounded-xl
                                bg-blue-600
                                px-6
                                py-3.5
                                text-sm
                                font-semibold
                                text-white
                                shadow-sm
                                transition
                                hover:bg-blue-700
                                hover:shadow-md
                                active:scale-[.98]
                                disabled:cursor-not-allowed
                                disabled:opacity-50
                            ">

                        <span wire:loading.remove wire:target="start">
                            Iniciar armado
                        </span>

                        <span wire:loading wire:target="start">
                            Iniciando...
                        </span>

                    </button>

                </div>



                {{-- ================================================= --}}
                {{-- EN PROCESO --}}
                {{-- ================================================= --}}

            @elseif (
                            $session->status
                            === \App\Enums\AssemblyWorkStatus::RUNNING
                        )

                        <div x-data="{
                                    seconds: {{ $effectiveSeconds }}
                                }" x-init="
                                    setInterval(
                                        () => seconds++,
                                        1000
                                    )
                                ">

                            <div class="
                                        rounded-3xl
                                        bg-[#0B1220]
                                        px-6
                                        py-8
                                        text-center
                                        text-white
                                        shadow-[0_12px_35px_rgba(15,23,42,0.15)]
                                    ">

                                <div class="
                                            flex
                                            items-center
                                            justify-center
                                            gap-2
                                        ">

                                    <span class="
                                                h-2
                                                w-2
                                                animate-pulse
                                                rounded-full
                                                bg-blue-400
                                            "></span>

                                    <p class="
                                                text-[10px]
                                                font-semibold
                                                uppercase
                                                tracking-[0.18em]
                                                text-blue-300
                                            ">
                                        Armado en proceso
                                    </p>

                                </div>


                                <p class="
                                            mt-5
                                            font-mono
                                            text-5xl
                                            font-bold
                                            tracking-tight
                                            text-white
                                            sm:text-6xl
                                        " x-text="
                                            String(
                                                Math.floor(
                                                    seconds / 3600
                                                )
                                            ).padStart(2, '0')
                                            + ':'
                                            + String(
                                                Math.floor(
                                                    (seconds % 3600)
                                                    / 60
                                                )
                                            ).padStart(2, '0')
                                            + ':'
                                            + String(
                                                seconds % 60
                                            ).padStart(2, '0')
                                        ">
                                    {{ $effectiveFormatted }}
                                </p>


                                <p class="
                                            mt-2
                                            text-xs
                                            font-medium
                                            uppercase
                                            tracking-[0.14em]
                                            text-slate-500
                                        ">
                                    Tiempo efectivo
                                </p>


                                <div class="
                                            mx-auto
                                            mt-7
                                            grid
                                            max-w-xl
                                            gap-3
                                            sm:grid-cols-2
                                        ">

                                    <div class="
                                                rounded-2xl
                                                border
                                                border-white/10
                                                bg-white/[0.05]
                                                px-4
                                                py-3
                                            ">

                                        <p class="
                                                    text-[10px]
                                                    font-semibold
                                                    uppercase
                                                    tracking-wider
                                                    text-slate-500
                                                ">
                                            Inicio
                                        </p>

                                        <p class="
                                                    mt-1
                                                    text-sm
                                                    font-semibold
                                                    text-white
                                                ">
                                            {{ \App\Support\DateHelper::format(
                    $session->started_at
                ) }}
                                        </p>

                                    </div>


                                    <div class="
                                                rounded-2xl
                                                border
                                                border-white/10
                                                bg-white/[0.05]
                                                px-4
                                                py-3
                                            ">

                                        <p class="
                                                    text-[10px]
                                                    font-semibold
                                                    uppercase
                                                    tracking-wider
                                                    text-slate-500
                                                ">
                                            Armador actual
                                        </p>

                                        <p class="
                                                    mt-1
                                                    truncate
                                                    text-sm
                                                    font-semibold
                                                    text-white
                                                ">
                                            {{ $session
                    ->current_worker_name
                    ?? '—'
                                                }}
                                        </p>

                                    </div>

                                </div>


                                <button type="button" wire:click="openPause" class="
                                            mt-7
                                            inline-flex
                                            items-center
                                            justify-center
                                            gap-2
                                            rounded-xl
                                            bg-amber-500
                                            px-6
                                            py-3.5
                                            text-sm
                                            font-semibold
                                            text-white
                                            shadow-sm
                                            transition
                                            hover:bg-amber-600
                                            active:scale-[.98]
                                        ">
                                    Pausar armado
                                </button>

                            </div>

                        </div>



                        {{-- ================================================= --}}
                        {{-- PAUSADO --}}
                        {{-- ================================================= --}}

            @elseif (
                    $session->status
                    === \App\Enums\AssemblyWorkStatus::PAUSED
                )

                <div class="
                            rounded-3xl
                            border
                            border-amber-200
                            bg-gradient-to-br
                            from-amber-50
                            to-white
                            px-6
                            py-8
                            text-center
                        ">

                    <div class="
                                flex
                                items-center
                                justify-center
                                gap-2
                            ">

                        <span class="
                                    h-2
                                    w-2
                                    rounded-full
                                    bg-amber-500
                                "></span>

                        <p class="
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-[0.18em]
                                    text-amber-700
                                ">
                            Armado pausado
                        </p>

                    </div>


                    <p class="
                                mt-5
                                font-mono
                                text-5xl
                                font-bold
                                tracking-tight
                                text-amber-900
                                sm:text-6xl
                            ">
                        {{ $effectiveFormatted }}
                    </p>

                    <p class="
                                mt-2
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-amber-600
                            ">
                        Tiempo efectivo acumulado
                    </p>


                    @if ($currentPause)

                                <div class="
                                                mx-auto
                                                mt-7
                                                max-w-lg
                                                rounded-2xl
                                                border
                                                border-amber-200
                                                bg-white
                                                p-5
                                                text-left
                                                shadow-sm
                                            ">

                                    <div class="
                                                    grid
                                                    gap-5
                                                    sm:grid-cols-2
                                                ">

                                        <div>

                                            <p class="
                                                            text-[10px]
                                                            font-semibold
                                                            uppercase
                                                            tracking-wider
                                                            text-amber-600
                                                        ">
                                                Motivo
                                            </p>

                                            <p class="
                                                            mt-1
                                                            text-sm
                                                            font-semibold
                                                            text-slate-950
                                                        ">
                                                {{ $currentPause
                            ->reason
                            ->label()
                                                        }}
                                            </p>

                                        </div>


                                        <div>

                                            <p class="
                                                            text-[10px]
                                                            font-semibold
                                                            uppercase
                                                            tracking-wider
                                                            text-amber-600
                                                        ">
                                                Pausado desde
                                            </p>

                                            <p class="
                                                            mt-1
                                                            text-sm
                                                            font-semibold
                                                            text-slate-950
                                                        ">
                                                {{ \App\Support\DateHelper::format(
                            $currentPause->paused_at
                        ) }}
                                            </p>

                                        </div>

                                    </div>


                                    @if ($currentPause->notes)

                                        <div class="
                                                            mt-5
                                                            border-t
                                                            border-slate-100
                                                            pt-4
                                                        ">

                                            <p class="
                                                                text-[10px]
                                                                font-semibold
                                                                uppercase
                                                                tracking-wider
                                                                text-slate-400
                                                            ">
                                                Observación
                                            </p>

                                            <p class="
                                                                mt-1
                                                                text-sm
                                                                leading-6
                                                                text-slate-600
                                                            ">
                                                {{ $currentPause->notes }}
                                            </p>

                                        </div>

                                    @endif

                                </div>

                    @endif


                    <button type="button" wire:click="resume" wire:loading.attr="disabled" wire:target="resume" class="
                                mt-7
                                inline-flex
                                items-center
                                justify-center
                                rounded-xl
                                bg-blue-600
                                px-6
                                py-3.5
                                text-sm
                                font-semibold
                                text-white
                                shadow-sm
                                transition
                                hover:bg-blue-700
                                active:scale-[.98]
                                disabled:cursor-not-allowed
                                disabled:opacity-50
                            ">

                        <span wire:loading.remove wire:target="resume">
                            Reanudar armado
                        </span>

                        <span wire:loading wire:target="resume">
                            Reanudando...
                        </span>

                    </button>

                </div>

            @endif



            {{-- ================================================= --}}
            {{-- FORMULARIO DE PAUSA --}}
            {{-- ================================================= --}}

            @if ($showPauseForm)

                <div class="
                            mt-6
                            rounded-3xl
                            border
                            border-amber-200
                            bg-amber-50/70
                            p-5
                            lg:p-6
                        ">

                    <div>

                        <p class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-[0.14em]
                                    text-amber-700
                                ">
                            Registrar interrupción
                        </p>

                        <h3 class="
                                    mt-1
                                    text-lg
                                    font-semibold
                                    text-slate-950
                                ">
                            ¿Por qué deseas pausar el armado?
                        </h3>

                        <p class="
                                    mt-1
                                    text-sm
                                    text-slate-500
                                ">
                            El tiempo transcurrido durante la pausa
                            no se contabilizará como trabajo efectivo.
                        </p>

                    </div>


                    <div class="
                                mt-5
                                grid
                                gap-3
                                md:grid-cols-3
                            ">

                        @foreach (
                                $pauseReasons
                                as $reason
                            )

                            <label class="
                                            cursor-pointer
                                            rounded-2xl
                                            border
                                            border-amber-200
                                            bg-white
                                            p-4
                                            transition
                                            hover:border-amber-400
                                            hover:shadow-sm
                                        ">

                                <div class="
                                                flex
                                                items-start
                                                gap-3
                                            ">

                                    <input type="radio" wire:model="pauseReason" value="{{ $reason->value }}" class="
                                                    mt-0.5
                                                    h-4
                                                    w-4
                                                    border-slate-300
                                                    text-amber-500
                                                    focus:ring-amber-500
                                                ">

                                    <span class="
                                                    text-sm
                                                    font-semibold
                                                    text-slate-800
                                                ">
                                        {{ $reason->label() }}
                                    </span>

                                </div>

                            </label>

                        @endforeach

                    </div>


                    @error('pauseReason')

                        <p class="
                                        mt-2
                                        text-xs
                                        font-medium
                                        text-red-600
                                    ">
                            {{ $message }}
                        </p>

                    @enderror


                    <div class="mt-5">

                        <label class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-500
                                ">
                            Observación
                            <span class="font-normal normal-case">
                                (opcional)
                            </span>
                        </label>

                        <textarea wire:model="pauseNotes" rows="3"
                            placeholder="Agrega información adicional sobre la pausa..." class="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    bg-white
                                    px-4
                                    py-3
                                    text-sm
                                    text-slate-900
                                    outline-none
                                    transition
                                    placeholder:text-slate-400
                                    focus:border-amber-500
                                    focus:ring-4
                                    focus:ring-amber-500/10
                                "></textarea>

                    </div>


                    <div class="
                                mt-5
                                flex
                                flex-col-reverse
                                gap-3
                                sm:flex-row
                                sm:justify-end
                            ">

                        <button type="button" wire:click="cancelPause" class="
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


                        <button type="button" wire:click="pause" wire:loading.attr="disabled" wire:target="pause" class="
                                    rounded-xl
                                    bg-amber-500
                                    px-5
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-white
                                    shadow-sm
                                    transition
                                    hover:bg-amber-600
                                    disabled:cursor-not-allowed
                                    disabled:opacity-50
                                ">

                            <span wire:loading.remove wire:target="pause">
                                Confirmar pausa
                            </span>

                            <span wire:loading wire:target="pause">
                                Registrando...
                            </span>

                        </button>

                    </div>

                </div>

            @endif

        </div>

    </section>

</div>