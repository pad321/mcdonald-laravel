<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demostración de Árboles Binarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#120600] text-white min-h-screen font-sans p-6 md:p-10">

    <div class="max-w-5xl mx-auto">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-[#FFC300] tracking-tight">Estructura de Datos: Árbol Binario 🌳</h1>
                <p class="text-gray-400 text-sm mt-1">Demostración algorítmica aplicada sobre objetos de MongoDB</p>
            </div>
            <a href="/admin/dashboard" class="bg-[#FFC300] hover:bg-[#e6b000] text-[#1a0a00] font-black px-6 py-3 rounded-xl transition shadow-lg text-sm flex items-center gap-2">
                Volver al panel 📊
            </a>
        </div>

        <div class="bg-[#2a1200] border-l-4 border-[#FFC300] rounded-2xl p-5 mb-8 shadow-md">
            <h3 class="text-[#FFC300] font-bold text-base mb-1">Sustentación del Algoritmo:</h3>
            <p class="text-gray-300 text-sm leading-relaxed">
                Este módulo procesa los documentos en memoria RAM construyendo un <b class="text-[#FFC300]">Árbol Binario de Búsqueda (BST)</b>. 
                Cada elemento se evalúa bajo la regla de ordenamiento binario: valores de <code>total_pagado</code> menores a la raíz se insertan a la izquierda y los mayores a la derecha. Finalmente, se ejecuta un método recursivo <b class="text-[#FFC300]">In-Order Traversal</b>, cuya complejidad temporal es de $O(N)$, garantizando el ordenamiento de los datos de forma lineal y eficiente.
            </p>
        </div>

        <h2 class="text-lg font-bold text-[#FFC300] mb-4">Árbol Procesado (Recorrido In-Order de Menor a Mayor)</h2>

        @if(count($pedidosOrdenados) == 0)
            <div class="bg-[#2a1200] p-10 rounded-2xl text-center border border-dashed border-gray-700">
                <p class="text-gray-400 font-medium">No hay nodos registrados en el árbol actual.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pedidosOrdenados as $nodo)
                    <div class="bg-[#2a1200] p-5 rounded-xl border border-gray-800 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] bg-yellow-500/10 text-[#FFC300] font-bold px-2 py-0.5 rounded-md uppercase">Nodo Registrado</span>
                            <h4 class="text-lg font-black text-white mt-1">Pedido: {{ $nodo->pedido->codigo_pedido }}</h4>
                            <p class="text-xs text-gray-400 mt-1">Cliente: <span class="text-gray-200">{{ $nodo->pedido->cliente }}</span></p>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] text-gray-500 block uppercase font-bold">Clave (Key)</span>
                            <p class="text-xl font-black text-[#FFC300]">S/. {{ number_format($nodo->pedido->total_pagado, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</body>
</html>