<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Administrator - McDonald</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        min-height: 100vh;
        background: #0d0909;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #f5f5f5;
        padding: 30px;
    }
    
    .dashboard-wrapper {
        max-width: 1140px;
        margin: 0 oauto;
        margin: 0 auto;
    }

    /* Header Corporativo */
    header {
        background: #140e0e;
        border-radius: 16px;
        padding: 24px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .header-info h1 { font-size: 26px; color: #ffc107; font-weight: 700; }
    .header-info p { color: #8c8c8c; font-size: 13px; margin-top: 4px; }
    .btn-cerrar {
        background: #c62828; color: white; padding: 12px 20px; text-decoration: none;
        border-radius: 8px; font-weight: 600; font-size: 14px; transition: background 0.2s;
    }
    .btn-cerrar:hover { background: #b71c1c; }

    /* Bloque de KPIs */
    .kpi-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;
    }
    .kpi-card {
        background: #140e0e; border-radius: 16px; padding: 26px;
        border: 1px solid rgba(255, 255, 255, 0.05); position: relative;
    }
    .kpi-card::before {
        content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; border-radius: 16px 0 0 16px;
    }
    .kpi-ingresos::before { background: #ffc107; }
    .kpi-pedidos::before { background: #00bcd4; }
    
    .kpi-data p { font-size: 11px; color: #8c8c8c; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
    .kpi-data h2 { font-size: 36px; font-weight: 700; margin-top: 6px; }
    .kpi-ingresos h2 { color: #ffc107; }
    .kpi-pedidos h2 { color: #00bcd4; }

    /* Sección de Búsqueda API */
    .search-section {
        background: #140e0e; border-radius: 16px; padding: 26px;
        border: 1px solid rgba(255, 255, 255, 0.05); margin-bottom: 24px;
    }
    .search-section h3 { font-size: 13px; color: #00bcd4; margin-bottom: 16px; letter-spacing: 0.5px; text-transform: uppercase; }
    .search-flex { display: flex; gap: 12px; }
    .search-select {
        padding: 14px; background: #1c1414; border: 1px solid #2d2020; border-radius: 8px; color: white; font-size: 14px; outline: none; cursor: pointer;
    }
    .search-input {
        flex: 1; padding: 14px 20px; background: #1c1414; border: 1px solid #2d2020; border-radius: 8px; color: white; font-size: 14px; outline: none;
    }
    .search-input:focus { border-color: #00bcd4; }
    .btn-search {
        padding: 0 24px; background: #ffc107; color: #140e0e; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: background 0.2s;
    }
    .btn-search:hover { background: #ffb300; }

    /* Tabla de Resultados NoSQL */
    .results-box {
        background: #090606; border-radius: 12px; border: 1px solid #2d2020; margin-top: 20px; display: none; overflow: hidden;
    }
    .results-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
    .results-table th { background: #1c1414; padding: 14px; color: #00bcd4; font-weight: 600; border-bottom: 1px solid #2d2020; }
    .results-table td { padding: 14px; border-bottom: 1px solid #1c1414; color: #dfdfdf; }
    .results-table tr:hover { background: rgba(255,255,255,0.01); }

    /* Panel de Navegación */
    .navigation-box {
        background: #140e0e; border-radius: 16px; padding: 26px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .navigation-box h3 { font-size: 13px; color: #8c8c8c; margin-bottom: 16px; letter-spacing: 0.5px; text-transform: uppercase; }
    .btn-group { display: flex; flex-wrap: wrap; gap: 12px; }
    .btn-nav {
        flex: 1; min-width: 200px; padding: 14px; border-radius: 8px; text-decoration: none;
        font-weight: 600; text-align: center; font-size: 14px; transition: background 0.2s, transform 0.2s;
    }
    .btn-reportes { background: #d35400; color: white; }
    .btn-fifo { background: #2c3e50; color: white; }
    .btn-bst { background: #4a148c; color: white; }
    .btn-heapsort { background: #0097a7; color: white; } /* Color premium exclusivo para el montículo binario */
    .btn-tienda { background: #1b5e20; color: white; }
    .btn-nav:hover { transform: translateY(-1px); filter: brightness(1.1); }
</style>
</head>
<body>

<div class="dashboard-wrapper">
    <header>
        <div class="header-info">
            <h1>Dashboard McDonald</h1>
        </div>
        <a href="/admin/salir" class="btn-cerrar">Cerrar Sesión</a>
    </header>

    <div class="kpi-grid">
        <div class="kpi-card kpi-ingresos">
            <div class="kpi-data">
                <p>Ingresos Totales</p>
                <h2>S/. {{ number_format($gananciaTotal, 2) }}</h2>
            </div>
        </div>
        <div class="kpi-card kpi-pedidos">
            <div class="kpi-data">
                <p>Pedidos Procesados</p>
                <h2>{{ $totalPedidos }} {{ $totalPedidos == 1 ? 'orden' : 'órdenes' }}</h2>
            </div>
        </div>
    </div>

    <div class="search-section">
        <h3>Motor de Búsqueda Integrado</h3>
        <div class="search-flex">
            <select id="tipoBusqueda" class="search-select">
                <option value="codigo">Código de Pedido</option>
                <option value="cliente">Nombre de Cliente</option>
            </select>
            <input 
                type="text" 
                id="criterioBusqueda" 
                class="search-input" 
                placeholder="Ingrese el criterio de búsqueda..."
                onkeyup="if(event.key === 'Enter') ejecutarBusquedaAPI()"
            >
            <button class="btn-search" onclick="ejecutarBusquedaAPI()">Buscar Pedido</button>
        </div>

        <div id="resultsBox" class="results-box">
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Código Pedido</th>
                        <th>Cliente</th>
                        <th>Monto Total</th>
                        <th>Fecha Registro</th>
                    </tr>
                </thead>
                <tbody id="resultadosTablaBody"></tbody>
            </table>
        </div>
    </div>

    <div class="navigation-box">
        <h3>Menú Administrativo</h3>
        <div class="btn-group">
            <a href="/reportes" class="btn-nav btn-reportes">Ver Lista de Reportes</a>
            <a href="/cola-pedidos" class="btn-nav btn-fifo">Ver Cola FIFO</a>       
            <a href="/menu" target="_blank" class="btn-nav btn-tienda">Abrir Tienda</a>
        </div>
    </div>
</div>

<script>
    // frontend pa ver mis datos
async function ejecutarBusquedaAPI() {
    const tipo = document.getElementById('tipoBusqueda').value;
    const valor = document.getElementById('criterioBusqueda').value.trim();
    const resultsBox = document.getElementById('resultsBox');
    const tbody = document.getElementById('resultadosTablaBody');
    
    if (!valor) {
        resultsBox.style.display = 'none';
        return;
    }
    
    try {
        const url = `/api/v1/pedidos/buscar?${tipo}=${encodeURIComponent(valor)}`;
        const respuesta = await fetch(url);
        
        if (!respuesta.ok) throw new Error("Error en la petición");
        const datos = await respuesta.json();
        
        tbody.innerHTML = '';
        
        if (datos.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; color: #ff5252; padding: 14px;">Ningún registro encontrado en MongoDB</td></tr>`;
            resultsBox.style.display = 'block';
            return;
        }
        
        datos.forEach(p => {
            const fecha = p.fecha ? new Date(p.fecha).toLocaleString() : 'No registrada';
            tbody.innerHTML += `
                <tr>
                    <td style="font-weight: 600; color: #ffc107;">${p.codigo_pedido || 'N/A'}</td>
                    <td>${p.cliente || 'Anónimo'}</td>
                    <td style="color: #00bcd4; font-weight: 600;">S/. ${parseFloat(p.total_pagado).toFixed(2)}</td>
                    <td>${fecha}</td>
                </tr>
            `;
        });
        
        resultsBox.style.display = 'block';
        
    } catch (error) {
        console.error("Fallo al buscar:", error);
        tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; color: #ff5252; padding: 14px;">Error de conexión con el servidor</td></tr>`;
        resultsBox.style.display = 'block';
    }
}
</script>

</body>
</html>