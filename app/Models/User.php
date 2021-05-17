<?php

namespace App\Models;

use App\Models\Note;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    const GENDERS = ['male', 'female', 'rather not say'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
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

    public function notes()
    {
        return $this->hasMany(Note::class)->orderBy('created_at', 'DESC');
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function scopePatients($query)
    {
        return $query->where('is_admin', 0);
    }

    public function getProfilePhotoUrlAttribute()
    {
        return Storage::disk('profiles')->url($this->profile_photo);
    }
}
