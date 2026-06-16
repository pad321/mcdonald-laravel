<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Avanzados - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#120600] text-white min-h-screen font-sans p-6 md:p-10">

    @php
        // Mantener intacta tu lógica de cálculo de Laravel Collections
        $totalVentas = $pedidos->sum('total_pagado');
        $totalPedidos = $pedidos->count();
        $productos = [];

        foreach($pedidos as $pedido){
            if(isset($pedido->pedido)){
                foreach($pedido->pedido as $producto){
                    $nombre = $producto['nombre'] ?? $producto->nombre ?? 'Producto';
                    $productos[$nombre] = ($productos[$nombre] ?? 0) + 1;
                }
            }
        }

        arsort($productos);
        $productoTop = count($productos) > 0 ? array_key_first($productos) : 'Sin datos';

        $metodos = $pedidos->groupBy('metodo_pago')->map->count()->sortDesc();
        $metodoTop = $metodos->keys()->first() ?? 'Sin datos';

        $clientes = $pedidos->groupBy('cliente')->map(function($items){
            return $items->sum('total_pagado');
        })->sortDesc();
    @endphp

    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-[#1f0c00] p-6 rounded-2xl border border-yellow-500/10 shadow-lg">
            <div>
                <h1 class="text-3xl font-black text-[#FFC300]">Análisis y Reportes de Ventas 📊</h1>
                <p class="text-gray-400 text-sm mt-1">Estadísticas descriptivas e historial detallado del sistema</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <a href="/reportes" class="bg-[#2a1200] hover:bg-[#3d1a00] border border-yellow-500/30 text-[#FFC300] font-bold px-4 py-2.5 rounded-xl transition text-sm flex items-center gap-2 shadow-md">
                    🔄 Actualizar
                </a>
                <a href="/cola-pedidos" class="bg-[#2a1200] hover:bg-[#3d1a00] border border-yellow-500/30 text-[#FFC300] font-bold px-4 py-2.5 rounded-xl transition text-sm flex items-center gap-2 shadow-md">
                    ⏳ Cola FIFO
                </a>
                <a href="/admin/dashboard" class="bg-[#FFC300] hover:bg-[#e6b000] text-[#1a0a00] font-black px-5 py-2.5 rounded-xl transition text-sm flex items-center gap-2 shadow-md">
                    🏠 Panel Principal
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            
            <div class="bg-[#2a1200] p-6 rounded-2xl border-l-4 border-yellow-500 shadow-md">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Ingresos</h2>
                <p class="text-2xl font-black text-[#FFC300] mt-2">S/. {{ number_format($totalVentas, 2) }}</p>
            </div>

            <div class="bg-[#2a1200] p-6 rounded-2xl border-l-4 border-red-600 shadow-md">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Órdenes</h2>
                <p class="text-2xl font-black text-[#FFC300] mt-2">{{ $totalPedidos }} pedidos</p>
            </div>

            <div class="bg-[#2a1200] p-6 rounded-2xl border-l-4 border-amber-500 shadow-md overflow-hidden">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Producto Estrella</h2>
                <p class="text-lg font-black text-white mt-2 truncate" title="{{ $productoTop }}">{{ $productoTop }}</p>
            </div>

            <div class="bg-[#2a1200] p-6 rounded-2xl border-l-4 border-green-600 shadow-md">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Método Preferido</h2>
                <p class="text-2xl font-black text-[#FFC300] mt-2">{{ $metodoTop }}</p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-[#2a1200] p-6 rounded-2xl shadow-md border border-gray-800/40">
                <h2 class="text-lg font-black text-[#FFC300] mb-4 flex items-center gap-2">🍩 Métodos de Pago</h2>
                <div class="bg-white p-4 rounded-xl shadow-inner h-80 flex items-center justify-center">
                    <canvas id="graficoMetodos"></canvas>
                </div>
            </div>

            <div class="bg-[#2a1200] p-6 rounded-2xl shadow-md border border-gray-800/40">
                <h2 class="text-lg font-black text-[#FFC300] mb-4 flex items-center gap-2">📊 Volumen de Productos</h2>
                <div class="bg-white p-4 rounded-xl shadow-inner h-80 flex items-center justify-center">
                    <canvas id="graficoProductos"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <div class="bg-[#2a1200] p-6 rounded-2xl shadow-md border border-gray-800/40">
                <h3 class="text-lg font-black text-[#FFC300] mb-4">Pedidos por Método</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead>
                            <tr class="border-b border-gray-700 text-[#FFC300] font-bold">
                                <th class="py-3 px-2">Método</th>
                                <th class="py-3 px-2 text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @foreach($metodos as $metodo => $cantidad)
                            <tr class="hover:bg-[#331601] transition-colors">
                                <td class="py-3 px-2 font-medium">{{ $metodo }}</td>
                                <td class="py-3 px-2 text-right text-white font-bold">{{ $cantidad }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-[#2a1200] p-6 rounded-2xl shadow-md border border-gray-800/40">
                <h3 class="text-lg font-black text-[#FFC300] mb-4">Ranking de Ventas</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead>
                            <tr class="border-b border-gray-700 text-[#FFC300] font-bold">
                                <th class="py-3 px-2">Producto</th>
                                <th class="py-3 px-2 text-right">Unidades Vendidas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @foreach($productos as $producto => $cantidad)
                            <tr class="hover:bg-[#331601] transition-colors">
                                <td class="py-3 px-2 font-medium">{{ $producto }}</td>
                                <td class="py-3 px-2 text-right text-white font-bold">{{ $cantidad }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="bg-[#2a1200] p-6 rounded-2xl shadow-md border border-gray-800/40 mb-8">
            <h3 class="text-lg font-black text-[#FFC300] mb-4">Fidelidad de Clientes (Total Gastado)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead>
                        <tr class="border-b border-gray-700 text-[#FFC300] font-bold">
                            <th class="py-3 px-4">Nombre del Cliente</th>
                            <th class="py-3 px-4 text-right">Inversión Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @foreach($clientes as $cliente => $total)
                        <tr class="hover:bg-[#331601] transition-colors">
                            <td class="py-3 px-4 font-medium">{{ $cliente }}</td>
                            <td class="py-3 px-4 text-right text-[#FFC300] font-bold">S/. {{ number_format($total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-[#2a1200] p-6 rounded-2xl shadow-md border border-gray-800/40">
            <h3 class="text-lg font-black text-[#FFC300] mb-4">Historial de Auditoría de Pedidos</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead>
                        <tr class="border-b border-gray-700 text-[#FFC300] font-bold">
                            <th class="py-3 px-4">Cliente</th>
                            <th class="py-3 px-4">Ítems Comprados</th>
                            <th class="py-3 px-4">Método</th>
                            <th class="py-3 px-4 text-right">Monto Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @foreach($pedidos as $pedido)
                        <tr class="hover:bg-[#331601] transition-colors">
                            <td class="py-3 px-4 font-medium text-white">{{ $pedido->cliente }}</td>
                            <td class="py-3 px-4 text-xs text-gray-400 leading-relaxed">
                                @if(isset($pedido->pedido))
                                    @foreach($pedido->pedido as $producto)
                                        <span class="inline-block bg-[#120600] text-gray-300 px-2 py-0.5 rounded-md my-0.5 border border-gray-800">
                                            • {{ $producto['nombre'] ?? $producto->nombre ?? 'Producto' }}
                                        </span><br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-300">{{ $pedido->metodo_pago }}</td>
                            <td class="py-3 px-4 text-right text-[#FFC300] font-bold">S/. {{ number_format($pedido->total_pagado, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
    // 1. Traemos los datos desde Laravel
    const metodosLabels = @json($metodos->keys()->values());
    const metodosData = @json($metodos->values());

    const productosLabels = @json(array_keys($productos));
    const productosData = @json(array_values($productos));

    // 2. Definimos la paleta de colores oficial de las aplicaciones en Perú
    const paletaColores = {
        'yape': '#742284',      // Morado Yape
        'plin': '#00b4c5',      // Celeste / Azul Plin
        'efectivo': '#16a34a',  // Verde Efectivo
        'tarjeta': '#3b82f6',   // Azul estándar
        'default': '#9ca3af'    // Gris por si acaso
    };

    // 3. Asignamos el color dinámicamente comparando el texto del método de pago
    const metodosColors = metodosLabels.map(label => {
        const metodoLimpio = label.toLowerCase().trim();
        return paletaColores[metodoLimpio] || paletaColores['default'];
    });

    // 4. Renderizado del Gráfico de Torta (Métodos de Pago)
    new Chart(document.getElementById('graficoMetodos'),{
        type: 'pie',
        data: {
            labels: metodosLabels,
            datasets: [{
                data: metodosData,
                backgroundColor: metodosColors
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // 5. Renderizado del Gráfico de Barras (Productos)
    new Chart(document.getElementById('graficoProductos'),{
        type: 'bar',
        data: {
            labels: productosLabels,
            datasets: [{
                label: 'Unidades vendidas',
                data: productosData,
                backgroundColor: '#FFC300',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    </script>

</body>
</html>