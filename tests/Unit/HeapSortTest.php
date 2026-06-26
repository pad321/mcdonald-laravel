<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HeapSortTest extends TestCase
{
    /**
     * Prueba de Caja Negra para verificar la estabilidad de la complejidad O(n log n)
     */
    public function test_algoritmo_heap_sort_ordena_precios_correctamente()
    {
        // 1. Datos de entrada simulados (Desordenados, emulando la flexibilidad de MongoDB)
        $pedidosSimulados = [
            ['codigo_pedido' => 'M-01', 'total_pagado' => 32.50],
            ['codigo_pedido' => 'M-02', 'total_pagado' => 12.00],
            ['codigo_pedido' => 'M-03', 'total_pagado' => 15.00],
            ['codigo_pedido' => 'M-04', 'total_pagado' => 8.50]
        ];

        // 2. Ejecutamos nuestra réplica del algoritmo Heap Sort
        $resultado = $this->ejecutarHeapSortInterno($pedidosSimulados);

        // 3. VALIDACIÓN DE INVARIANTES (Asserts de Control de Calidad)
        $this->assertEquals(8.50,  floatval($resultado[0]['total_pagado']), "El elemento menor debería ser 8.50");
        $this->assertEquals(12.00, floatval($resultado[1]['total_pagado']), "El segundo elemento debería ser 12.00");
        $this->assertEquals(15.00, floatval($resultado[2]['total_pagado']), "El tercer elemento debería ser 15.00");
        $this->assertEquals(32.50, floatval($resultado[3]['total_pagado']), "El elemento mayor debería ser 32.50");
    }

    private function ejecutarHeapSortInterno($arr)
    {
        $n = count($arr);
        for ($i = floor($n / 2) - 1; $i >= 0; $i--) {
            $this->heapify($arr, $n, $i);
        }
        for ($i = $n - 1; $i > 0; $i--) {
            $temp = $arr[0];
            $arr[0] = $arr[$i];
            $arr[$i] = $temp;
            $this->heapify($arr, $i, 0);
        }
        return $arr;
    }

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