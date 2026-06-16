<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cola de Pedidos FIFO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#120600] text-white min-h-screen font-sans p-6 md:p-10">

    <div class="max-w-4xl mx-auto">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-[#FFC300] tracking-tight">Cola de Pedidos FIFO ⏳</h1>
                <p class="text-gray-400 text-sm mt-1">Monitoreo de estructura de datos en tiempo real</p>
            </div>
            <a href="/admin/dashboard" class="bg-[#FFC300] hover:bg-[#e6b000] text-[#1a0a00] font-black px-6 py-3 rounded-xl transition-all shadow-lg text-sm flex items-center gap-2 transform hover:scale-105">
                Volver al panel 📊
            </a>
        </div>

        <div class="bg-[#2a1200] border-l-4 border-[#FFC300] rounded-2xl p-5 mb-8 shadow-md">
            <p class="text-gray-300 text-sm leading-relaxed">
                Esta pantalla representa una estructura de datos lineal de tipo <b class="text-[#FFC300]">Cola FIFO (First In, First Out)</b>. 
                El principio fundamental es que el <span class="text-white font-semibold">primer pedido que ingresa</span> a la base de datos es estrictamente el <span class="text-white font-semibold">primero en ser atendido</span>, manteniendo un orden cronológico perfecto.
            </p>
        </div>

        @if($pedidos->count() == 0)
            <div class="bg-[#2a1200] p-10 rounded-2xl text-center border border-dashed border-gray-700">
                <p class="text-gray-400 font-medium">✨ No hay pedidos pendientes en la cola de producción.</p>
            </div>
        @else
            <div class="flex flex-col gap-4">

                @foreach($pedidos as $index => $pedido)
                    
                    <div class="bg-[#2a1200] rounded-2xl p-6 border-l-8 border-[#FFC300] shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-6 transition-all hover:bg-[#361802]">
                        
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-full bg-[#FFC300] text-[#1a0a00] flex flex-col items-center justify-center shadow-md">
                                <span class="text-[10px] font-bold uppercase tracking-wider leading-none">Turno</span>
                                <span class="text-xl font-black mt-0.5">{{ $index + 1 }}</span>
                            </div>
                            
                            <div>
                                <h2 class="text-xl font-black text-[#FFC300]">Pedido {{ $pedido->codigo_pedido }}</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 mt-2 text-sm text-gray-300">
                                    <p><b class="text-gray-400">Cliente:</b> {{ $pedido->cliente }}</p>
                                    <p><b class="text-gray-400">Pago:</b> {{ $pedido->metodo_pago }}</p>
                                    <p class="sm:col-span-2"><b class="text-gray-400">Fecha:</b> {{ $pedido->fecha }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#1c0c01] rounded-xl p-4 w-full md:w-64 border border-gray-800 self-stretch flex flex-col justify-center">
                            <span class="text-[11px] uppercase font-bold text-gray-500 tracking-wider block mb-1">Productos</span>
                            <div class="text-xs text-gray-300 space-y-1 max-h-24 overflow-y-auto">
                                @if(isset($pedido->pedido))
                                    @foreach($pedido->pedido as $producto)
                                        <div class="flex items-start gap-1">
                                            <span class="text-[#FFC300]">•</span>
                                            <span>{{ $producto['nombre'] ?? $producto->nombre ?? 'Producto' }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="text-right w-full md:w-auto self-end md:self-center">
                            <span class="text-[11px] uppercase font-bold text-gray-500 tracking-wider block md:hidden mb-1">Total Pagado</span>
                            <p class="text-2xl font-black text-[#FFC300] tracking-tight">
                                S/. {{ number_format($pedido->total_pagado, 2) }}
                            </p>
                        </div>

                    </div>

                    @if(!$loop->last)
                        <div class="flex justify-center my-1">
                            <div class="bg-[#2a1200] text-[#FFC300] font-black px-4 py-1.5 rounded-full text-sm shadow-inner flex items-center gap-1 animate-bounce">
                                <span>↓</span>
                                <span class="text-[10px] tracking-widest uppercase font-bold text-gray-400">Siguiente Nodo</span>
                            </div>
                        </div>
                    @endif

                @endforeach

            </div>
        @endif

    </div>

</body>
</html>