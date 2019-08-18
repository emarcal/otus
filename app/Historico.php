<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'historicos';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['descricao', 'tipo', 'utilizador', 'acao', 'ip', 'data'];

    
}
