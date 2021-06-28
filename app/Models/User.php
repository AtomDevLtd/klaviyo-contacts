<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'klaviyo_public_api_key',
        'klaviyo_private_api_key'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * All of the models Mutators&Accessors
     * should begin from here.
     */

    /**
     * All of the models scopes methods
     * should begin from here.
     */

    /**
     * All of the models custom methods
     * should begin from here.
     */

    public function hasKlaviyoApiKeys(): bool
    {
        return $this->klaviyo_public_api_key && $this->klaviyo_private_api_key;
    }

    /**
     * All of the models method overwrites
     * should begin from here.
     */


    /**
     * All of the models relationships
     * should begin from here.
     */


    /**
     * @return HasMany
     */
    public function contacts() :HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
