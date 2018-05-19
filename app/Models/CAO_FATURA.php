<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CAO_FATURA extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "cao_factura";
    protected $fillable = [
        'co_fatura',
        'co_cliente',
        'co_sistema',
        'co_os',
        'num_nf',
        'total',
        'valor',
        'data_emissao',
        'corpo_nf',
        'comissao_cn',
        'total_imp_inc',
    ];

    /**
     * Bind the Task to a User.
     */
    /*public function user()
    {
        return $this->belongsTo('App\Models\User');
    }*/
}
