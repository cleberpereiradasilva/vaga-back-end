<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dependente extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'email', 'celular', 'user_id', 'cliente_id'
    ];

    protected $dates = ['deleted_at'];


    /**
     * Get the cliente that owns
     */
    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }
}
