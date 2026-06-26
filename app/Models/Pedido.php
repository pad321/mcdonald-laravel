<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pedido extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pedidos';

    // Aseguramos que Laravel permita guardar los punteros del árbol en el JSON
    protected $fillable = [
        'cliente',
        'codigo_pedido',
        'pedido',
        'total_pagado',
        'estado',
        'izquierdo_id', 
        'derecho_id'
    ];
}