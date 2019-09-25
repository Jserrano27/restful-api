<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;


    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    protected $dates = ['deleted_at'];

    //Seller y Buyer heredan esta definicion, de modo que laravel sabe que no hay tabla propia para ellos dos
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * Atributos que deben estar ocultos en el array Json de la respuesta http
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token',        
    ];

    //Mutators and Accessors

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }
    
    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email']= strtolower($email);
    }
    /*********** END MUTATORS AND ACCESSORS ***********/

    //True if its verified, false if its not
    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    //True if is admin, False if is not
    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }    

    //Generate verification token
    public static function generateVerificationCode()
    {
        return str_random(70);
    }



}
