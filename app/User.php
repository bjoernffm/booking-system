<?php

namespace App;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements Auditable
{
    use Uuids,
        SoftDeletes,
        \OwenIt\Auditing\Auditable,
        Notifiable,
        MobileOwner,
        MustVerifyEmail,
        HasApiTokens,
        Searchable;

    public $incrementing = false;

    public function toSearchableArray()
    {
        return [
            'title' => $this->firstname.' '.$this->lastname,
            'item' => $this->toArray(),
            'entity' => get_class($this)
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];
}
