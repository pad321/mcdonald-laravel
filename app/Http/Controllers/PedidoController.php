<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::all();

        return view('reportes', compact('pedidos'));
    }

    public function guardar(Request $request)
    {
        Pedido::create([
            'cliente' => $request->cliente,
            'codigo_pedido' => $request->codigo_pedido,
            'pedido' => $request->pedido,
            'metodo_pago' => $request->metodo_pago,
            'total_pagado' => floatval($request->total_pagado),
            'fecha' => now()
        ]);

        return response()->json([
            'ok' => true
        ]);
    }
}