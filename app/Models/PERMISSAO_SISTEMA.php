<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PERMISSAO_SISTEMA extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "permissao_sistema";
    protected $fillable = [
        'co_usuario',
        'co_tipo_usuario',
        'co_sistema',
        'in_ativo',
        'co_usuario_atualizacao',
        'dt_atualizacao',
    ];

    /**
     * Bind the Task to a User.
     */
    /*public function user()
    {
        return $this->belongsTo('App\Models\User');
    }*/
}
