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

    # 🚀 NUEVO MÉTODO: Construcción y recorrido del Árbol Binario en memoria
    public function verArbol()
    {
        if (!session('admin')) {
            return redirect('/admin/login');
        }

        // 1. Traemos los pedidos en bruto de MongoDB
        $pedidos = Pedido::all();

        // 2. Instanciamos nuestra estructura de datos pura
        $arbol = new ArbolBinarioPedidos();

        // 3. Alimentamos el árbol insertando los pedidos uno por uno
        foreach ($pedidos as $pedido) {
            $arbol->insertar($pedido);
        }

        // 4. Ejecutamos el algoritmo de recorrido In-Order (Izquierda -> Raíz -> Derecha)
        // Esto ordenará automáticamente los pedidos de menor a mayor precio a nivel algorítmico
        $pedidosOrdenados = $arbol->obtenerInOrder();

        return view('admin.arbol', compact('pedidosOrdenados'));
    }
}

// =========================================================================
// 🧠 ESTRUCTURAS DE DATOS PURAS (Para sustentar tu nota de Algoritmos)
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

    // Algoritmo de inserción en un Árbol Binario de Búsqueda (BST)
    public function insertar($pedido) {
        $nuevoNodo = new NodoPedido($pedido);
        if ($this->raiz === null) {
            $this->raiz = $nuevoNodo;
        } else {
            $this->insertarNodo($this->raiz, $nuevoNodo);
        }
    }

    private function insertarNodo($nodoActual, $nuevoNodo) {
        // Criterio de ordenación: Monto Total Pagado
        if ($nuevoNodo->pedido->total_pagado < $nodoActual->pedido->total_pagado) {
            // Si es menor, se va a la sub-rama izquierda
            if ($nodoActual->izquierdo === null) {
                $nodoActual->izquierdo = $nuevoNodo;
            } else {
                $this->insertarNodo($nodoActual->izquierdo, $nuevoNodo);
            }
        } else {
            // Si es mayor o igual, se va a la sub-rama derecha
            if ($nodoActual->derecho === null) {
                $nodoActual->derecho = $nuevoNodo;
            } else {
                $this->insertarNodo($nodoActual->derecho, $nuevoNodo);
            }
        }
    }

    // Algoritmo de recorrido In-Order para recuperar los elementos ordenados ascendentemente
    public function obtenerInOrder() {
        $resultado = [];
        $this->recorridoInOrder($this->raiz, $resultado);
        return $resultado;
    }

    private function recorridoInOrder($nodo, &$resultado) {
        if ($nodo !== null) {
            $this->recorridoInOrder($nodo->izquierdo, $resultado); // Izquierda
            $resultado[] = $nodo;                                  // Raíz (Guardamos el nodo completo)
            $this->recorridoInOrder($nodo->derecho, $resultado);   // Derecha
        }
    }
}