<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis y Reportes de Ventas - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#0d0909] text-[#f5f5f5] min-h-screen font-sans p-6 md:p-10">

    @php
        // 🛠️ ADAPTACIÓN IN-MEMORY PARA ARRAYS (PROCESADOS POR HEAP SORT)
        $totalVentas = array_sum(array_column($pedidos, 'total_pagado'));
        $totalPedidos = count($pedidos);
        $productos = [];
        $metodosAgrupados = [];
        $clientesAgrupados = [];

        foreach($pedidos as $pedido){
            // 1. Conteo de Productos Estrella
            if(isset($pedido->pedido)){
                foreach($pedido->pedido as $producto){
                    // Manejo dinámico si es array u objeto interno
                    $nombre = is_array($producto) ? ($producto['nombre'] ?? 'Producto') : ($producto->nombre ?? 'Producto');
                    $productos[$nombre] = ($productos[$nombre] ?? 0) + 1;
                }
            }

            // 2. Agrupación Manual por Método de Pago (Simula groupBy->count)
            $metodo = $pedido->metodo_pago ?? 'Efectivo';
            $metodosAgrupados[$metodo] = ($metodosAgrupados[$metodo] ?? 0) + 1;

            // 3. Agrupación Manual por Cliente (Suma total gastado)
            $clienteNom = $pedido->cliente ?? 'Anónimo';
            $clientesAgrupados[$clienteNom] = ($clientesAgrupados[$clienteNom] ?? 0) + floatval($pedido->total_pagado);
        }

        // Ordenamos los productos de mayor a menor ventas
        arsort($productos);
        $productoTop = count($productos) > 0 ? array_key_first($productos) : 'Sin datos';

        // Ordenamos los métodos de pago por popularidad
        arsort($metodosAgrupados);
        $metodoTop = count($metodosAgrupados) > 0 ? array_key_first($metodosAgrupados) : 'Sin datos';

        // Ordenamos el ranking de clientes que más han gastado
        arsort($clientesAgrupados);
    @endphp

    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-[#140e0e] p-6 rounded-2xl border border-white/5 shadow-lg">
            <div>
                <h1 class="text-2xl font-bold text-[#ffc107]">Análisis y Reportes de Ventas <span class="text-xs text-green-500 animate-pulse ml-2">● En Vivo</span></h1>
                <p class="text-[#8c8c8c] text-xs mt-1">Estadísticas descriptivas e historial detallado del sistema operacional gobernado por Heap Sort</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <a href="/reportes" class="bg-[#1c1414] hover:bg-[#2d2020] border border-white/10 text-[#ffc107] font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    Actualizar
                </a>
                <a href="/cola-pedidos" class="bg-[#1c1414] hover:bg-[#2d2020] border border-white/10 text-[#ffc107] font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    Cola FIFO
                </a>
                <a href="/admin/dashboard" class="bg-[#ffc107] hover:bg-[#ffb300] text-[#140e0e] font-bold px-5 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    Panel Principal
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-[#140e0e] p-6 rounded-2xl border border-white/5 border-l-4 border-l-[#ffc107] shadow-md">
                <h2 class="text-[11px] font-semibold text-[#8c8c8c] uppercase tracking-wider">Total Ingresos</h2>
                <p class="text-3xl font-bold text-[#ffc107] mt-2">S/. {{ number_format($totalVentas, 2) }}</p>
            </div>

            <div class="bg-[#140e0e] p-6 rounded-2xl border border-white/5 border-l-4 border-l-[#00bcd4] shadow-md">
                <h2 class="text-[11px] font-semibold text-[#8c8c8c] uppercase tracking-wider">Total Órdenes</h2>
                <p class="text-3xl font-bold text-[#00bcd4] mt-2">{{ $totalPedidos }} {{ $totalPedidos == 1 ? 'pedido' : 'pedidos' }}</p>
            </div>

            <div class="bg-[#140e0e] p-6 rounded-2xl border border-white/5 border-l-4 border-l-[#e91e63] shadow-md overflow-hidden">
                <h2 class="text-[11px] font-semibold text-[#8c8c8c] uppercase tracking-wider">Producto Estrella</h2>
                <p class="text-xl font-bold text-[#e91e63] mt-2 truncate" title="{{ $productoTop }}">{{ $productoTop }}</p>
            </div>

            <div class="bg-[#140e0e] p-6 rounded-2xl border border-white/5 border-l-4 border-l-[#4caf50] shadow-md">
                <h2 class="text-[11px] font-semibold text-[#8c8c8c] uppercase tracking-wider">Método Preferido</h2>
                <p class="text-3xl font-bold text-[#4caf50] mt-2">{{ $metodoTop }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-[#140e0e] p-6 rounded-2xl border border-white/5 shadow-md">
                <h2 class="text-xs font-semibold text-[#8c8c8c] uppercase tracking-wider mb-4">Métodos de Pago</h2>
                <div class="bg-[#090606] p-4 rounded-xl border border-white/5 h-80 flex items-center justify-center">
                    <canvas id="graficoMetodos"></canvas>
                </div>
            </div>

            <div class="bg-[#140e0e] p-6 rounded-2xl shadow-md border border-white/5">
                <h2 class="text-xs font-semibold text-[#8c8c8c] uppercase tracking-wider mb-4">Volumen de Productos (Top)</h2>
                <div class="bg-[#090606] p-4 rounded-xl border border-white/5 h-80 flex items-center justify-center">
                    <canvas id="graficoProductos"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-[#140e0e] p-6 rounded-2xl border border-white/5 shadow-md">
                <h3 class="text-sm font-semibold text-[#ffc107] uppercase tracking-wider mb-4">Pedidos por Método</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#dfdfdf]">
                        <thead>
                            <tr class="border-b border-white/10 text-[#00bcd4] font-semibold">
                                <th class="py-3 px-2">Método</th>
                                <th class="py-3 px-2 text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($metodosAgrupados as $metodo => $cantidad)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-3 px-2 font-medium">{{ $metodo }}</td>
                                <td class="py-3 px-2 text-right text-white font-bold">{{ $cantidad }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-[#140e0e] p-6 rounded-2xl border border-white/5 shadow-md">
                <h3 class="text-sm font-semibold text-[#ffc107] uppercase tracking-wider mb-4">Ranking de Ventas</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#dfdfdf]">
                        <thead>
                            <tr class="border-b border-white/10 text-[#00bcd4] font-semibold">
                                <th class="py-3 px-2">Producto</th>
                                <th class="py-3 px-2 text-right">Unidades Vendidas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($productos as $producto => $cantidad)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-3 px-2 font-medium">{{ $producto }}</td>
                                <td class="py-3 px-2 text-right text-white font-bold">{{ $cantidad }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-[#140e0e] p-6 rounded-2xl border border-white/5 shadow-md mb-8">
            <h3 class="text-sm font-semibold text-[#ffc107] uppercase tracking-wider mb-4">Fidelidad de Clientes (Total Gastado)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#dfdfdf]">
                    <thead>
                        <tr class="border-b border-white/10 text-[#00bcd4] font-semibold">
                            <th class="py-3 px-4">Nombre del Cliente</th>
                            <th class="py-3 px-4 text-right">Inversión Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($clientesAgrupados as $cliente => $total)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 px-4 font-medium">{{ $cliente }}</td>
                            <td class="py-3 px-4 text-right text-[#ffc107] font-bold">S/. {{ number_format($total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-[#140e0e] p-6 rounded-2xl border border-white/5 shadow-md">
            <h3 class="text-sm font-semibold text-[#ffc107] uppercase tracking-wider mb-4">Historial de Auditoría de Pedidos (Ordenado por Heap Sort)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#dfdfdf]">
                    <thead>
                        <tr class="border-b border-white/10 text-[#00bcd4] font-semibold">
                            <th class="py-3 px-4">Cliente</th>
                            <th class="py-3 px-4">Ítems Comprados</th>
                            <th class="py-3 px-4">Método</th>
                            <th class="py-3 px-4 text-right">Monto Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($pedidos as $pedido)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 px-4 font-medium text-white">{{ $pedido->cliente }}</td>
                            <td class="py-3 px-4 text-xs text-gray-400 leading-relaxed">
                                @if(isset($pedido->pedido))
                                    @foreach($pedido->pedido as $producto)
                                        @php
                                            $prodNombre = is_array($producto) ? ($producto['nombre'] ?? 'Producto') : ($producto->nombre ?? 'Producto');
                                        @endphp
                                        <span class="inline-block bg-[#090606] text-gray-300 px-2 py-0.5 rounded-md my-0.5 border border-white/5">
                                            • {{ $prodNombre }}
                                        </span><br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-400">{{ $pedido->metodo_pago }}</td>
                            <td class="py-3 px-4 text-right text-[#ffc107] font-bold">S/. {{ number_format($pedido->total_pagado, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
    const metodosLabels = @json(array_keys($metodosAgrupados));
    const metodosData = @json(array_values($metodosAgrupados));

    const productosLabels = @json(array_keys($productos));
    const productosData = @json(array_values($productos));

    const paletaColores = {
        'yape': '#742284',      
        'plin': '#00b4c5',      
        'efectivo': '#16a34a',  
        'tarjeta': '#3b82f6',   
        'default': '#8c8c8c'    
    };

    const metodosColors = metodosLabels.map(label => {
        const metodoLimpio = label.toLowerCase().trim();
        return paletaColores[metodoLimpio] || paletaColores['default'];
    });

    const chartPluginsConfig = {
        legend: {
            labels: {
                color: '#ead6d6',
                font: { size: 12, weight: '600', family: 'Segoe UI' }
            }
        }
    };

    new Chart(document.getElementById('graficoMetodos'), {
        type: 'pie',
        data: {
            labels: metodosLabels,
            datasets: [{
                data: metodosData,
                backgroundColor: metodosColors,
                borderColor: '#090606',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: chartPluginsConfig
        }
    });

    new Chart(document.getElementById('graficoProductos'), {
        type: 'bar',
        data: {
            labels: productosLabels,
            datasets: [{
                label: 'Unidades vendidas',
                data: productosData,
                backgroundColor: '#ffc107',
                borderRadius: 8,          
                maxBarThickness: 45,      
                barPercentage: 0.6        
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: chartPluginsConfig,
            scales: {
                x: {
                    ticks: { color: '#8c8c8c', font: { size: 11 } },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#8c8c8c', stepSize: 1 },
                    grid: { color: 'rgba(255, 255, 255, 0.04)' }
                }
            }
        }
    });

    // ⏱️ Sincronización reactiva automatizada
    const pedidosInicialesCount = {{ $totalPedidos }};
    
    setInterval(async () => {
        try {
            const respuesta = await fetch('/api/v1/admin/cola-pedidos');
            if (respuesta.ok) {
                const actuales = await respuesta.json();
                if (actuales.length !== pedidosInicialesCount) {
                    window.location.reload();
                }
            }
        } catch (e) {
            console.error("Monitor analítico en segundo plano pausado.");
        }
    }, 4000);
    </script>

</body>
</html>