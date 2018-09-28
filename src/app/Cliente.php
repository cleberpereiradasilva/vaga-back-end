<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'email', 'telefone', 'user_id',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get the dependentes for the cliente.
     */
    public function dependentes()
    {
        return $this->hasMany('App\Dependente');
    }


     /**
     * Get the user that owns
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
