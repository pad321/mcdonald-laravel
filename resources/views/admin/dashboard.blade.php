<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard McDonald's</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    <div class="p-6 max-w-7xl mx-auto">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-200 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-800">Dashboard McDonald's 🍔</h1>
                <p class="text-gray-500 text-sm mt-1">Métricas en tiempo real procesadas con MongoDB Aggregation Pipeline</p>
            </div>
            <a href="/admin/salir" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-xl transition shadow-md text-sm flex items-center gap-2">
                Cerrar Sesión 🚪
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 border-l-8 border-yellow-500 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ingresos Totales</h3>
                    <p class="text-4xl font-black text-gray-900 mt-2">S/. {{ number_format($gananciaTotal, 2) }}</p>
                </div>
                <div class="text-4xl bg-yellow-100 p-4 rounded-full shadow-inner">💰</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 border-l-8 border-red-600 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pedidos Procesados</h3>
                    <p class="text-4xl font-black text-gray-900 mt-2">{{ $totalPedidos }} órdenes</p>
                </div>
                <div class="text-4xl bg-red-100 p-4 rounded-full shadow-inner">📦</div>
            </div>

        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span>🔍</span> Motor de Búsqueda del Sistema (Consumo Interno de API RESTful)
            </h3>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <select id="tipoBusqueda" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 font-medium">
                    <option value="codigo">Código de Pedido (ej: A-379)</option>
                    <option value="cliente">Nombre del Cliente</option>
                </select>
                <input type="text" id="inputBuscar" placeholder="Escribe tu criterio para buscar en la base de datos..." class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 font-medium">
                <button onclick="consultarAPI()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-md text-sm">
                    Buscar en Mongo 🚀
                </button>
            </div>

            <div id="resultadosAPI" class="mt-6 space-y-3 hidden">
                </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">Accesos de Administrador</h3>
            <div class="flex flex-wrap gap-4">
                <a href="/reportes" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl transition shadow-md flex items-center gap-2 text-sm">
                    📋 Ver Lista de Reportes
                </a>
                <a href="/cola-pedidos" class="bg-gray-800 hover:bg-gray-900 text-white font-bold px-6 py-3 rounded-xl transition shadow-md flex items-center gap-2 text-sm">
                    ⏳ Ver Cola FIFO
                </a>
                <a href="/admin/arbol" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-md flex items-center gap-2 text-sm">
                    🌳 Ver Árbol Binario BST
                </a>
                <a href="/" target="_blank" class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-md flex items-center gap-2 text-sm">
                    🛒 Abrir Tienda (Nueva Pestaña)
                </a>
            </div>
        </div>

    </div>

    <script>
    async function consultarAPI() {
        const tipo = document.getElementById('tipoBusqueda').value;
        const valor = document.getElementById('inputBuscar').value.trim();
        const contenedor = document.getElementById('resultadosAPI');
        
        if(!valor) {
            alert('Por favor, ingresa un valor para iniciar la consulta.');
            return;
        }

        // Estructuramos la URL del Endpoint con Query Strings estándar
        const url = `/api/v1/pedidos/buscar?${tipo}=${encodeURIComponent(valor)}`;

        try {
            // Ejecutamos la petición asíncrona mediante Fetch API
            const response = await fetch(url);
            const datos = await response.json();

            contenedor.innerHTML = '';
            contenedor.classList.remove('hidden');

            if(datos.length === 0) {
                contenedor.innerHTML = `<p class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded-xl border">❌ Ningún documento coincide con el criterio en MongoDB.</p>`;
                return;
            }

            // Mapeamos el arreglo JSON de respuesta para inyectar los elementos al DOM
            datos.forEach(pedido => {
                let productosHtml = '';
                if(pedido.pedido) {
                    pedido.pedido.forEach(prod => {
                        productosHtml += `<span class="inline-block bg-gray-100 border text-gray-700 px-2 py-0.5 rounded text-xs mr-1 font-mono">• ${prod.nombre ?? 'Producto'}</span>`;
                    });
                }

                contenedor.innerHTML += `
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-md uppercase tracking-wider font-mono">Response Payload JSON</span>
                            <h4 class="text-base font-black text-gray-800 mt-1">Pedido ID: ${pedido.codigo_pedido}</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Cliente: <span class="text-gray-700 font-bold">${pedido.cliente}</span> | Método: ${pedido.metodo_pago}</p>
                            <div class="mt-2">${productosHtml}</div>
                        </div>
                        <div class="text-right sm:self-center">
                            <span class="text-[10px] text-gray-400 block uppercase font-bold font-mono">Total</span>
                            <p class="text-lg font-black text-blue-600">S/. ${(pedido.total_pagado).toFixed(2)}</p>
                        </div>
                    </div>
                `;
            });

        } catch (error) {
            console.error(error);
            alert('Error crítico de comunicación con el servicio de API.');
        }
    }
    </script>

</body>
</html>