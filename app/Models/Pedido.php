<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pedido extends Model
{

    protected $connection = 'mongodb';

    protected $collection = 'pedidos';

    protected $fillable = [

        'cliente',
        'codigo_pedido',
        'pedido',
        'metodo_pago',
        'total_pagado',
        'fecha'

    ];

}