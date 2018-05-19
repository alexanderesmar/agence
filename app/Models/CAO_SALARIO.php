<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CAO_SALARIO extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "cao_salario";
    protected $fillable = [
        'co_usuario',
        'dt_alteracao',
        'brut_salario',
        'liq_salario',
    ];

    /**
     * Bind the Task to a User.
     */
    /*public function user()
    {
        return $this->belongsTo('App\Models\User');
    }*/
}
