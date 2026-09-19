<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'active',])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    /*
|--------------------------------------------------------------------------
| UNIDADES ASIGNADAS COMO TRASLADISTA
|--------------------------------------------------------------------------
*/

    public function transferAssignments(): HasMany
    {
        return $this->hasMany(
            UnitTransferAssignment::class,
            'transporter_user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASIGNACIONES REALIZADAS
    |--------------------------------------------------------------------------
    */

    public function transferAssignmentsCreated(): HasMany
    {
        return $this->hasMany(
            UnitTransferAssignment::class,
            'assigned_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CARGAS DE GASOLINA REGISTRADAS
    |--------------------------------------------------------------------------
    */

    public function fuelLoadsRegistered(): HasMany
    {
        return $this->hasMany(
            UnitFuelLoad::class,
            'registered_by'
        );
    }
}
