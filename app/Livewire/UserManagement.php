<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public ?int $editingUserId = null;

    public string $name = '';

    public string $email = '';

    public string $role = '';

    public string $password = '';

    public string $password_confirmation = '';


    public function mount(): void
    {
        abort_unless(
            Auth::user()?->can('users.manage'),
            403
        );
    }


    public function updatedSearch(): void
    {
        $this->resetPage();
    }


    public function create(): void
    {
        $this->resetForm();

        $this->showForm = true;
    }


    public function edit(int $userId): void
    {
        $user = User::query()
            ->with('roles')
            ->findOrFail($userId);

        $this->editingUserId =
            $user->id;

        $this->name =
            $user->name;

        $this->email =
            $user->email;

        $this->role =
            $user->roles
            ->first()
            ?->name
            ?? '';

        $this->password = '';

        $this->password_confirmation = '';

        $this->showForm = true;
    }


    public function cancel(): void
    {
        $this->resetForm();
    }


    public function save(): void
    {
        abort_unless(
            Auth::user()?->can('users.manage'),
            403
        );

        $rules = [
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $this->editingUserId
                ),
            ],

            'role' => [
                'required',
                'string',
                Rule::exists(
                    'roles',
                    'name'
                ),
            ],
        ];


        /*
         * Crear usuario:
         * contraseña obligatoria.
         *
         * Editar usuario:
         * contraseña opcional.
         */
        if ($this->editingUserId) {

            $rules['password'] = [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ];
        } else {

            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'confirmed',
            ];
        }


        $validated =
            $this->validate($rules);


        /*
         * Protección:
         * el administrador no puede modificar
         * su propio rol accidentalmente.
         */
        if (
            $this->editingUserId
            === Auth::id()
        ) {

            $currentRole =
                Auth::user()
                ?->roles
                ->first()
                ?->name;

            if (
                $currentRole
                !== $validated['role']
            ) {
                $this->addError(
                    'role',
                    'No puedes cambiar tu propio rol.'
                );

                return;
            }
        }


        if ($this->editingUserId) {

            $user = User::findOrFail(
                $this->editingUserId
            );

            $user->name =
                trim(
                    $validated['name']
                );

            $user->email =
                strtolower(
                    trim(
                        $validated['email']
                    )
                );


            if (
                ! empty($validated['password'])
            ) {
                $user->password =
                    $validated['password'];
            }


            $user->save();


            $user->syncRoles([
                $validated['role'],
            ]);


            session()->flash(
                'success',
                'Usuario actualizado correctamente.'
            );
        } else {

            $user = User::create([
                'name' =>
                trim(
                    $validated['name']
                ),

                'email' =>
                strtolower(
                    trim(
                        $validated['email']
                    )
                ),

                'password' =>
                $validated['password'],

                'active' =>
                true,
            ]);


            $user->syncRoles([
                $validated['role'],
            ]);


            session()->flash(
                'success',
                'Usuario creado correctamente.'
            );
        }


        $this->resetForm();
    }


    public function toggleActive(
        int $userId
    ): void {

        abort_unless(
            Auth::user()?->can('users.manage'),
            403
        );


        /*
         * Nunca permitir que un usuario
         * se desactive a sí mismo.
         */
        if ($userId === Auth::id()) {

            session()->flash(
                'error',
                'No puedes desactivar tu propio usuario.'
            );

            return;
        }


        $user = User::findOrFail(
            $userId
        );


        $user->update([
            'active' =>
            ! $user->active,
        ]);


        session()->flash(
            'success',
            $user->active
                ? 'Usuario activado correctamente.'
                : 'Usuario desactivado correctamente.'
        );
    }


    private function resetForm(): void
    {
        $this->reset([
            'editingUserId',
            'name',
            'email',
            'role',
            'password',
            'password_confirmation',
            'showForm',
        ]);

        $this->resetValidation();
    }


    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when(
                trim($this->search) !== '',
                function ($query) {

                    $search =
                        trim(
                            $this->search
                        );

                    $query->where(
                        function ($query) use ($search) {

                            $query
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'roles',
                                    function ($roleQuery) use ($search) {

                                        $roleQuery->where(
                                            'name',
                                            'like',
                                            "%{$search}%"
                                        );
                                    }
                                );
                        }
                    );
                }
            )
            ->orderBy('name')
            ->paginate(15);


        $roles = Role::query()
            ->where(
                'guard_name',
                'web'
            )
            ->orderBy('name')
            ->pluck('name');


        return view(
            'livewire.user-management',
            compact(
                'users',
                'roles'
            )
        );
    }
}
