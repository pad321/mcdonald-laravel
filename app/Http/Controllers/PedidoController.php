<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * 📊 Vista de reportes del Administrador: Ahora procesa obligatoriamente 
     * todos los pedidos en memoria RAM mediante el algoritmo Heap Sort.
     */
    public function index()
    {
        // 1. Jalamos todos los registros crudos de MongoDB
        $pedidosDatos = Pedido::all();

        // Mapeamos a un array limpio para procesarlo "In-Memory"
        $pedidos = [];
        foreach ($pedidosDatos as $p) {
            $pedidos[] = [
                'id'            => $p->_id, // Id interno de Mongo
                'cliente'       => $p->cliente ?? 'Anónimo',
                'codigo_pedido' => $p->codigo_pedido ?? 'N/A',
                'pedido'        => $p->pedido,
                'metodo_pago'   => $p->metodo_pago ?? 'Efectivo',
                'total_pagado'  => floatval($p->total_pagado ?? 0),
                'estado'        => $p->estado ?? 'pendiente',
                'fecha'         => $p->fecha,
                'izquierdo_id'  => $p->izquierdo_id ?? null,
                'derecho_id'    => $p->derecho_id ?? null
            ];
        }

        $n = count($pedidos);

        // 2. Ejecutamos Heap Sort en caliente si hay datos disponibles O(n log n)
        if ($n > 0) {
            // Construcción del Max-Heap
            for ($i = floor($n / 2) - 1; $i >= 0; $i--) {
                $this->heapify($pedidos, $n, $i);
            }

            // Extracción e intercambio secuencial para ordenar de menor a mayor
            for ($i = $n - 1; $i > 0; $i--) {
                $temp = $pedidos[0];
                $pedidos[0] = $pedidos[$i];
                $pedidos[$i] = $temp;

                $this->heapify($pedidos, $i, 0);
            }
        }

        // Convertimos de vuelta a objeto para no romper tu vista blade 'reportes'
        $pedidos = json_decode(json_encode($pedidos));

        return view('reportes', compact('pedidos'));
    }

    /**
     * 💾 Guardar Pedido: Inserta los datos mapeando dinámicamente la estructura 
     * del Árbol Binario directamente dentro del documento JSON de MongoDB.
     */
public function guardar(Request $request)
    {
        $nuevoCodigo = $request->codigo_pedido;

        // Buscamos de forma lógica un nodo padre en MongoDB que tenga espacio para un nodo hijo
        $nodoPadre = Pedido::where('izquierdo_id', '')
                           ->orWhere('derecho_id', '')
                           ->orWhereNull('izquierdo_id')
                           ->orWhereNull('derecho_id')
                           ->first();

        // 🚀 Forzamos a que el JSON guarde los dos campos vacíos para que VS Code los muestre sí o sí
        $nuevoPedido = Pedido::create([
            'cliente'       => $request->cliente,
            'codigo_pedido' => $nuevoCodigo,
            'pedido'        => $request->pedido,
            'metodo_pago'   => $request->metodo_pago,
            'total_pagado'  => floatval($request->total_pagado),
            'estado'        => 'pendiente', 
            'fecha'         => now(),
            'izquierdo_id'  => "", // En lugar de null, ponemos string vacío
            'derecho_id'    => ""  // En lugar de null, ponemos string vacío
        ]);

        // Si encontramos un nodo libre en MongoDB, actualizamos sus punteros lógicos
        if ($nodoPadre) {
            if (empty($nodoPadre->izquierdo_id)) {
                $nodoPadre->update(['izquierdo_id' => $nuevoCodigo]);
            } else {
                $nodoPadre->update(['derecho_id' => $nuevoCodigo]);
            }
        }

        return response()->json([
            'ok' => true
        ]);
    }

    /**
     * 🌐 API RESTful: Retorna la cola de producción guiada 100% por Mongo
     */
    public function apiColaPedidos()
    {
        $pedidosActivos = Pedido::where('estado', 'pendiente')
                                ->orderBy('fecha', 'asc')
                                ->get();

        if ($pedidosActivos->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($pedidosActivos, 200);
    }

    /**
     * 🔄 "Borrado Lógico": Cambia el estado de los pedidos activos a 'completado'
     */
    public function vaciarTienda()
    {
        Pedido::where('estado', 'pendiente')->update([
            'estado' => 'completado'
        ]);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Pedidos procesados e historial conservado en la base de datos.'
        ]);
    }

    /**
     * 📊 Función auxiliar para estructurar y amontonar el subárbol (Heapify)
     */
    private function heapify(&$arr, $n, $i)
    {
        $largest = $i; 
        $left = 2 * $i + 1; 
        $right = 2 * $i + 2; 

        if ($left < $n && floatval($arr[$left]['total_pagado']) > floatval($arr[$largest]['total_pagado'])) {
            $largest = $left;
        }

        if ($right < $n && floatval($arr[$right]['total_pagado']) > floatval($arr[$largest]['total_pagado'])) {
            $largest = $right;
        }

        if ($largest != $i) {
            $swap = $arr[$i];
            $arr[$i] = $arr[$largest];
            $arr[$largest] = $swap;

            $this->heapify($arr, $n, $largest);
        }
    }
}   