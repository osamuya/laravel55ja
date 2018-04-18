<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
	
    /**
     * モデルと関連しているテーブル
     *
     * @var string
	 *
        id 主 int(10)
        name varchar(128)
        password varchar(256)
        email varchar(255)
        uniqueid varchar(64)
        uniquehash varchar(64)
        remember_token varchar(100)
        description longtext
        role int(11)
        status int(11)
        delflag tinyint(3)
        created_at timestamp
        updated_at timestamp
	 *
     */
    protected $table = 'base_users';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'password',
        'email',
		'uniqueid',
        'uniquehash',
        'description',
        'role',
        'status',
        'delflag',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'uniqehash',
    ];
}
