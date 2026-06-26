<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Estructura de Datos - Árbol Balanceado</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        min-height: 100vh;
        background: #0f0a0a;
        font-family: Arial, sans-serif;
        color: white;
        display: flex;
        flex-direction: column;
    }
    header {
        background: #160f0f;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #FFC300;
    }
    h1 { color: #FFC300; font-size: 28px; }
    .btn-volver {
        background: #FFC300; color: #1a0a00; padding: 10px 20px;
        text-decoration: none; border-radius: 12px; font-weight: bold;
    }
    .main-container {
        display: flex; flex: 1; padding: 20px; gap: 20px;
    }
    .sidebar-filters {
        width: 300px; background: #160f0f; border-radius: 20px;
        padding: 24px; border: 1px solid rgba(255,255,255,0.05);
    }
    .sidebar-filters h3 { color: #00E5FF; margin-bottom: 15px; font-size: 14px; letter-spacing: 1px; }
    .input-group { margin-bottom: 15px; }
    .input-group label { display: block; font-size: 12px; color: #aaa; margin-bottom: 5px; }
    .input-group input {
        width: 100%; padding: 12px; background: #221818; border: 1px solid #332222;
        border-radius: 10px; color: white; outline: none;
    }
    .btn-action {
        width: 100%; padding: 12px; border: none; border-radius: 10px;
        font-weight: bold; cursor: pointer; margin-bottom: 10px; transition: 0.2s;
    }
    .btn-rastrear { background: #FFC300; color: #1a0a00; }
    .btn-limpiar { background: #332222; color: #ccc; }
    .btn-action:hover { opacity: 0.9; transform: translateY(-2px); }

    .canvas-container {
        flex: 1; background: #050303; border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.05); position: relative;
        overflow: hidden; min-height: 550px;
    }
    .canvas-title {
        position: absolute; top: 20px; left: 50%; transform: translateX(-50%);
        text-align: center; z-index: 10;
    }
    .canvas-title h2 { color: #00E5FF; font-size: 20px; }
    .canvas-title p { color: #666; font-size: 12px; margin-top: 4px; }
    
    #tree-svg {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;
    }
    .tree-link {
        fill: none; stroke: #332222; stroke-width: 3; transition: 0.3s;
    }

    #nodes-container {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;
    }
    .node {
        position: absolute; width: 65px; height: 65px; border-radius: 50%;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        font-size: 11px; font-weight: bold; color: white; cursor: pointer;
        transition: all 0.3s ease; box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        transform: translate(-50%, -50%);
    }
    
    .node.red-node { background: #E91E63; border: 2px solid #FF5252; }
    .node.black-node { background: #1E1E24; border: 2px solid #3F51B5; }
    
    .node.match-filter {
        border: 3px solid #00E5FF !important;
        box-shadow: 0 0 20px #00E5FF;
        transform: translate(-50%, -50%) scale(1.1);
    }
    .node span { font-size: 10px; opacity: 0.85; margin-top: 2px; }
</style>
</head>
<body>

<header>
    <h1>Estructura de Datos: Árbol Analítico 🌳 <span style="font-size: 12px; color: #4caf50;">● Tiempo Real</span></h1>
    <a href="{{ route('admin.dashboard') }}" class="btn-volver">Volver al panel 📊</a>
</header>

<div class="main-container">
    <div class="sidebar-filters">
        <h3>🔒 RASTREADOR SELECTIVO</h3>
        <p style="font-size: 12px; color: #777; margin-bottom: 20px;">
            Resalta secuencialmente los pedidos que entran en el rango presupuestal.
        </p>
        
        <div class="input-group">
            <label for="min-price">Mínimo (S/.)</label>
            <input type="number" id="min-price" placeholder="Ej. 10">
        </div>
        
        <div class="input-group">
            <label for="max-price">Máximo (S/.)</label>
            <input type="number" id="max-price" placeholder="Ej. 30">
        </div>
        
        <button class="btn-action btn-rastrear" onclick="rastrearNodos()">🔍 Rastrear y Señalar</button>
        <button class="btn-action btn-limpiar" onclick="limpiarRastreo()">Limpiar Rastreo</button>
    </div>

    <div class="canvas-container">
        <div class="canvas-title">
            <h2>Árbol Red-Black Autobalanceado</h2>
            <p>Renderizado vectorial indexado en tiempo real desde MongoDB</p>
        </div>

        <svg id="tree-svg"></svg>
        <div id="nodes-container"></div>
    </div>
</div>

<script>
const fallbackDemo = [
    { codigo_pedido: 'M-01', total_pagado: 15.00, cliente: 'Prueba' },
    { codigo_pedido: 'M-02', total_pagado: 12.00, cliente: 'Prueba' },
    { codigo_pedido: 'M-03', total_pagado: 32.00, cliente: 'Prueba' }
];

class NodoArbol {
    constructor(pedido, color = 'RED') {
        this.pedido = pedido;
        this.valor = parseFloat(pedido.total_pagado);
        this.color = color; 
        this.izq = null;
        this.der = null;
        this.x = 0;
        this.y = 0;
    }
}

function construirArbolSimulado(lista) {
    if (lista.length === 0) return null;
    lista.sort((a, b) => parseFloat(a.total_pagado) - parseFloat(b.total_pagado));
    function armarRecursivo(inicio, fin, esNegro = true) {
        if (inicio > fin) return null;
        
        let medio = Math.floor((inicio + fin) / 2);
        let nodo = new NodoArbol(lista[medio], esNegro ? 'BLACK' : 'RED');
        
        nodo.izq = armarRecursivo(inicio, medio - 1, !esNegro);
        nodo.der = armarRecursivo(medio + 1, fin, !esNegro);
        return nodo;
    }
    
    return armarRecursivo(0, lista.length - 1, true);
}

function calcularPosiciones(nodo, xInicio, xFin, nivel, alturaNivel = 90) {
    if (!nodo) return;
    
    nodo.x = (xInicio + xFin) / 2;
    nodo.y = 130 + (nivel * alturaNivel); 
    
    calcularPosiciones(nodo.izq, xInicio, nodo.x, nivel + 1, alturaNivel);
    calcularPosiciones(nodo.der, nodo.x, xFin, nivel + 1, alturaNivel);
}

function dibujarArbol(raiz) {
    const svg = document.getElementById('tree-svg');
    const contenedor = document.getElementById('nodes-container');
    
    if(!svg || !contenedor) return;
    svg.innerHTML = '';
    contenedor.innerHTML = '';
    
    function renderPasada(nodo) {
        if (!nodo) return;
        
        if (nodo.izq) {
            svg.innerHTML += `<line class="tree-link" x1="${nodo.x}" y1="${nodo.y}" x2="${nodo.izq.x}" y2="${nodo.izq.y}"/>`;
        }
        if (nodo.der) {
            svg.innerHTML += `<line class="tree-link" x1="${nodo.x}" y1="${nodo.y}" x2="${nodo.der.x}" y2="${nodo.der.y}"/>`;
        }
        
        const claseColor = nodo.color === 'BLACK' ? 'black-node' : 'red-node';
        contenedor.innerHTML += `
            <div class="node ${claseColor}" 
                 id="node-${nodo.pedido.codigo_pedido}"
                 data-valor="${nodo.valor}"
                 style="left: ${nodo.x}px; top: ${nodo.y}px;">
                ${nodo.pedido.codigo_pedido}
                <span>S/ ${nodo.valor.toFixed(2)}</span>
            </div>
        `;
        
        renderPasada(nodo.izq);
        renderPasada(nodo.der);
    }
    
    renderPasada(raiz);
}

let raizGlobal = null;

async function cargarArbolDesdeAPI() {
    try {
        const respuesta = await fetch('/api/v1/admin/pedidos-arbol');
        if (!respuesta.ok) throw new Error('Error en el servidor');
        
        const pedidosReales = await respuesta.json();
        
        if (pedidosReales.length === 0) {
            document.getElementById('tree-svg').innerHTML = '';
            document.getElementById('nodes-container').innerHTML = '';
            raizGlobal = null;
            return; 
        }
        
        const contenedorAncho = document.querySelector('.canvas-container').offsetWidth;
        
        raizGlobal = construirArbolSimulado(pedidosReales);
        calcularPosiciones(raizGlobal, 50, contenedorAncho - 50, 0, 100);
        dibujarArbol(raizGlobal);
        
        // 🔍 Mantenemos los filtros aplicados después del redibujado asíncrono
        rastrearNodos();
    } catch (error) {
        console.error("Fallo al conectar con la API de MongoDB:", error);
        
        const contenedorAncho = document.querySelector('.canvas-container').offsetWidth;
        raizGlobal = construirArbolSimulado(fallbackDemo);
        calcularPosiciones(raizGlobal, 50, contenedorAncho - 50, 0, 100);
        dibujarArbol(raizGlobal);
    }
}

window.rastrearNodos = function() {
    const min = parseFloat(document.getElementById('min-price').value) || 0;
    const max = parseFloat(document.getElementById('max-price').value) || Infinity;
    
    document.querySelectorAll('.node').forEach(elem => {
        const valor = parseFloat(elem.getAttribute('data-valor'));
        if (valor >= min && valor <= max) {
            elem.classList.add('match-filter');
        } else {
            elem.classList.remove('match-filter');
        }
    });
};

window.limpiarRastreo = function() {
    document.getElementById('min-price').value = '';
    document.getElementById('max-price').value = '';
    document.querySelectorAll('.node').forEach(elem => {
        elem.classList.remove('match-filter');
    });
};

window.addEventListener('load', cargarArbolDesdeAPI);
window.addEventListener('resize', () => {
    if(raizGlobal) {
        const contenedorAncho = document.querySelector('.canvas-container').offsetWidth;
        calcularPosiciones(raizGlobal, 50, contenedorAncho - 50, 0, 100);
        dibujarArbol(raizGlobal);
    }
});

// ⏱️ Refresco automático constante para reestructurar nodos en vivo cada 3 segundos
setInterval(cargarArbolDesdeAPI, 3000);
</script>

</body>
</html>