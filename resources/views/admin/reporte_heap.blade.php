<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría de Presupuestos - Módulo Analítico</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📊</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: radial-gradient(circle at 50% 10%, #291212 0%, #120808 50%, #050202 100%);
        }
        .glass-panel {
            background: rgba(26, 15, 15, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 193, 7, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }
        /* Lienzo interactivo del Árbol de Montículo Vectorial */
        #heap-canvas-wrapper {
            position: relative;
            width: 100%;
            min-height: 400px;
            overflow-x: auto;
        }
        #heap-svg-links {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }
        .heap-link-line {
            stroke: rgba(0, 188, 212, 0.25);
            stroke-width: 3;
            stroke-linecap: round;
            transition: all 0.4s ease;
        }
        #heap-html-nodes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
        }
        /* Burbujas adaptativas con posicionamiento absoluto guiado */
        .heap-absolute-node {
            position: absolute;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1c2d3d 0%, #0b131a 100%);
            border: 2px solid #00bcd4;
            box-shadow: 0 0 15px rgba(0, 188, 212, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: #ffffff;
            transform: translate(-50%, -50%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
        }
        .heap-absolute-node.is-root {
            border-color: #ffc107;
            box-shadow: 0 0 22px rgba(255, 193, 7, 0.4);
        }
    </style>
</head>
<body class="text-[#f5f5f5] min-h-screen font-sans p-4 md:p-8 selection:bg-[#ffc107] selection:text-black relative">

    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-[#ffc107]/5 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-[#00bcd4]/5 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10 space-y-6">
        
        <header class="glass-panel rounded-2xl p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative overflow-hidden">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-[#ffc107] animate-pulse"></span>
                    <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">Módulo Analítico: Heap Sort</h1>
                </div>
                <p class="text-[#8c8c8c] text-xs mt-1 font-medium">Complejidad Algorítmica <span class="text-[#00bcd4] font-mono">O(n log n)</span> • Procesamiento in-memory sobre MongoDB NoSQL</p>
            </div>
            <div>
                <a href="/admin/dashboard" class="glass-panel hover:bg-white/5 border border-white/10 text-[#ffc107] font-semibold px-6 py-3 rounded-xl text-sm transition-all block text-center">
                    Volver al Panel
                </a>
            </div>
        </header>

        <div class="glass-panel rounded-2xl p-6">
            <h2 class="text-xs font-bold text-[#00bcd4] uppercase tracking-widest mb-4 flex items-center gap-2">
                <span>📋</span> Registro Secuencial de Transacciones (MongoDB)
            </h2>
            <div class="overflow-x-auto max-h-[260px] overflow-y-auto pr-1">
                <table class="w-full text-left text-sm">
                    <thead class="sticky top-0 bg-[#170e0e] z-10">
                        <tr class="border-b border-white/10 text-[#8c8c8c] font-medium text-xs uppercase tracking-wider">
                            <th class="pb-3 pl-4">Código Pedido</th>
                            <th class="pb-3">Cliente</th>
                            <th class="pb-3">Método de Pago</th>
                            <th class="pb-3 text-right pr-4">Total Pagado</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-contenido" class="divide-y divide-white/5 font-medium">
                        <tr>
                            <td colspan="4" class="text-center text-[#8c8c8c] py-16 italic text-xs">
                                Sincronizando de forma activa con MongoDB...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-white/5 pb-4 mb-6 gap-2">
                <div>
                    <h2 class="text-xs font-bold text-[#ffc107] uppercase tracking-widest flex items-center gap-2">
                        <span>🌳</span> Topología del Montículo Binario Interconectado
                    </h2>
                    <p class="text-gray-400 text-[11px] mt-1">Representación gráfica completa mediante mapeo de relaciones vectoriales de índices lineales.</p>
                </div>
                <div class="text-right text-[11px] font-mono text-[#00bcd4] bg-white/5 px-3 py-1 rounded-md border border-white/5">
                    Completo & Interconectado
                </div>
            </div>
            
            <div id="heap-canvas-wrapper" class="w-full">
                <svg id="heap-svg-links"></svg>
                <div id="heap-html-nodes">
                    <div class="text-center text-gray-600 text-xs italic pt-24">Compilando enlaces físicos...</div>
                </div>
            </div>
        </div>

    </div>

<script>
async function ejecutarHeapSortEnVivo() {
    const tbody = document.getElementById('tabla-contenido');
    const nodesContainer = document.getElementById('heap-html-nodes');
    const svgLinks = document.getElementById('heap-svg-links');
    const wrapper = document.getElementById('heap-canvas-wrapper');

    try {
        const respuesta = await fetch('/api/v1/admin/pedidos-heapsort');
        if (!respuesta.ok) throw new Error("Error API");
        const pedidos = await respuesta.json();

        if (!pedidos || pedidos.length === 0) {
            if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="text-center text-[#ffc107] py-16 text-xs">No hay órdenes pendientes.</td></tr>';
            if (nodesContainer) nodesContainer.innerHTML = '<div class="text-gray-600 text-xs italic text-center pt-24">Sin datos</div>';
            if (svgLinks) svgLinks.innerHTML = '';
            return;
        }

        // 1. Renderizar la tabla analítica
        let htmlContenido = '';
        pedidos.forEach(pedido => {
            htmlContenido += `
                <tr class="hover:bg-white/[0.02] transition-colors group">
                    <td class="py-3 pl-4"><span class="text-[#ffc107] font-bold group-hover:text-yellow-400">${pedido.codigo_pedido}</span></td>
                    <td class="py-3 text-gray-300">${pedido.cliente}</td>
                    <td class="py-3 text-gray-400 text-xs">${pedido.metodo_pago}</td>
                    <td class="py-3 text-right pr-4 text-[#00bcd4] font-bold font-mono">S/. ${parseFloat(pedido.total_pagado).toFixed(2)}</td>
                </tr>
            `;
        });
        if (tbody) tbody.innerHTML = htmlContenido;

        // 2. CONSTRUCCIÓN TOPOLÓGICA DE TU CAPTURA (CÁLCULO GEOMÉTRICO ASÍNCRONO)
        const totalNodos = pedidos.length;
        const canvasWidth = wrapper.offsetWidth;
        
        // Estructura de posiciones calculadas por niveles jerárquicos
        let coordenadas = [];
        let htmlNodes = '';
        let svgLinesHtml = '';

        // Definición de configuraciones espaciales adaptativas por nivel
        const alturaNivel = 90;
        const topMargen = 50;

        for (let i = 0; i < totalNodos; i++) {
            let nivel = Math.floor(Math.log2(i + 1));
            let posicionEnNivel = i - (Math.pow(2, nivel) - 1);
            let totalNodosNivel = Math.pow(2, nivel);

            // Fórmula geométrica centralizada para abrir las ramas simétricamente
            let sectorAncho = canvasWidth / totalNodosNivel;
            let x = (sectorAncho * posicionEnNivel) + (sectorAncho / 2);
            let y = topMargen + (nivel * alturaNivel);

            coordenadas.push({ x: x, y: y });

            // Dibujar las líneas guías de conexión al padre biunívoco (Fórmula: padre = floor((i-1)/2))
            if (i > 0) {
                let padreIdx = Math.floor((i - 1) / 2);
                let padreCoor = coordenadas[padreIdx];
                if (padreCoor) {
                    svgLinesHtml += `<line class="heap-link-line" x1="${padreCoor.x}" y1="${padreCoor.y}" x2="${x}" y2="${y}"/>`;
                }
            }

            // Renderizar la burbuja HTML
            let p = pedidos[i];
            let precio = parseFloat(p.total_pagado).toFixed(0);
            let rootClass = i === 0 ? 'is-root' : '';

            htmlNodes += `
                <div class="heap-absolute-node ${rootClass}" style="left: ${x}px; top: ${y}px;">
                    <span class="text-[9px] text-[#8c8c8c] font-mono leading-none mb-0.5">${i}</span>
                    <span class="leading-none text-white">S/.${precio}</span>
                    <span class="text-[7.5px] text-gray-400 block font-mono leading-none mt-0.5">${p.codigo_pedido}</span>
                </div>
            `;
        }

        // Ajustar la altura del contenedor dinámicamente según los niveles creados
        let maxNivel = Math.floor(Math.log2(totalNodos));
        wrapper.style.height = `${topMargen + (maxNivel * alturaNivel) + 60}px`;

        // Inyectar de golpe los vectores y nodos para evitar parpadeos rudos
        if (svgLinks) svgLinks.innerHTML = svgLinesHtml;
        if (nodesContainer) nodesContainer.innerHTML = htmlNodes;

    } catch (error) {
        console.error("Fallo estructural asíncrono:", error);
    }
}

// Inicializadores
window.addEventListener('load', ejecutarHeapSortEnVivo);
window.addEventListener('resize', ejecutarHeapSortEnVivo); // Recalcula las coordenadas si cambias el tamaño de la pantalla
setInterval(ejecutarHeapSortEnVivo, 3000);
</script>

</body>
</html>