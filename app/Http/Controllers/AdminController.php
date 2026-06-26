<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function acceder(Request $request)
    {
        $usuario = $request->usuario;
        $clave = $request->clave;

        if ($usuario === 'admin' && $clave === 'Admin123') {
            session(['admin' => true]);
            return redirect('/admin/dashboard'); 
        }

        return back()->with('error', 'Usuario o contraseña incorrectos');
    }

    public function salir()
    {
        session()->forget('admin');
        return redirect('/');
    }

    public function dashboard()
    {
        if (!session('admin')) {
            return redirect('/admin/login');
        }

        $datosAgregados = Pedido::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => null,
                        'ganancia_total' => ['$sum' => '$total_pagado'], 
                        'total_pedidos' => ['$sum' => 1]          
                    ]
                ]
            ]);
        })->toArray();

        $gananciaTotal = !empty($datosAgregados) ? $datosAgregados[0]['ganancia_total'] : 0;
        $totalPedidos = !empty($datosAgregados) ? $datosAgregados[0]['total_pedidos'] : 0;

        return view('admin.dashboard', compact('gananciaTotal', 'totalPedidos'));
    }

    public function verArbol()
    {
        if (!session('admin')) {
            return redirect('/admin/login');
        }

        // Simplemente renderiza la vista contenedora del Canvas. El frontend se encargará del fetch.
        return view('admin.arbol');
    }

    # 🌐 ENDPOINT API: Retorna JSON directo desde MongoDB para el render dinámico del árbol
    public function apiPedidosArbol()
    {
        // Extrae los últimos 15 pedidos reales para mantener el balanceo visual limpio
        $pedidos = Pedido::orderBy('fecha', 'desc')
            ->take(15)
            ->get(['codigo_pedido', 'total_pagado', 'cliente']);

        return response()->json($pedidos, 200);
    }
}

// =========================================================================
// 🧠 ESTRUCTURAS DE DATOS PURAS (Sustento algorítmico en memoria)
// =========================================================================

class NodoPedido {
    public $pedido;
    public $izquierdo = null;
    public $derecho = null;

    public function __construct($pedido) {
        $this->pedido = $pedido;
    }
}

class ArbolBinarioPedidos {
    public $raiz = null;

    public function insertar($pedido) {
        $nuevoNodo = new NodoPedido($pedido);
        if ($this->raiz === null) {
            $this->raiz = $nuevoNodo;
        } else {
            $this->insertarNodo($this->raiz, $nuevoNodo);
        }
    }

    private function insertarNodo($nodoActual, $nuevoNodo) {
        if ($nuevoNodo->pedido->total_pagado < $nodoActual->pedido->total_pagado) {
            if ($nodoActual->izquierdo === null) {
                $nodoActual->izquierdo = $nuevoNodo;
            } else {
                $this->insertarNodo($nodoActual->izquierdo, $nuevoNodo);
            }
        } else {
            if ($nodoActual->derecho === null) {
                $nodoActual->derecho = $nuevoNodo;
            } else {
                $this->insertarNodo($nodoActual->derecho, $nuevoNodo);
            }
        }
    }

    public function obtenerInOrder() {
        $resultado = [];
        $this->recorridoInOrder($this->raiz, $resultado);
        return $resultado;
    }

    private function recorridoInOrder($nodo, &$resultado) {
        if ($nodo !== null) {
            $this->recorridoInOrder($nodo->izquierdo, $resultado);
            $resultado[] = $nodo;
            $this->recorridoInOrder($nodo->derecho, $resultado);
        }
    }
}