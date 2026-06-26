<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monitoreo de Pedidos - Cola FIFO API</title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍔</text></svg>">

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        min-height: 100vh;
        background: #0d0909;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #f5f5f5;
        padding: 20px;
    }
    
    .container {
        width: 100%;
        max-width: 1120px;
        margin: 0 auto;
    }

    header {
        background: #140e0e;
        border-radius: 16px;
        padding: 24px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        flex-wrap: wrap;
        gap: 16px;
    }
    .header-info h1 { font-size: clamp(22px, 4vw, 26px); color: #ffc107; font-weight: 700; }
    .header-info p { color: #8c8c8c; font-size: 13px; margin-top: 4px; }
    .btn-volver {
        background: #ffc107; color: #140e0e; padding: 12px 20px; text-decoration: none;
        border-radius: 8px; font-weight: 600; font-size: 14px; transition: background 0.2s;
    }
    .btn-volver:hover { background: #ffb300; }

    .teoria-box {
        background: #140e0e; border-radius: 16px; padding: 22px 26px;
        border: 1px solid rgba(255, 255, 255, 0.05); margin-bottom: 24px;
        line-height: 1.6; font-size: 14px; color: #dfdfdf;
    }
    .teoria-box strong { color: #ffc107; }
    .teoria-box .destaque-fifo { color: #00bcd4; font-weight: 600; }

    /* 📦 CONTENEDOR ELÁSTICO RESPONSIVO */
    #cola-pedidos-container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: flex-start;
    }

    .pedido-card {
        flex: 1 1 calc(33.333% - 14px);
        min-width: 300px;
        background: #140e0e; border-radius: 16px; padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: flex; gap: 16px; position: relative;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @media (max-width: 900px) {
        .pedido-card {
            flex: 1 1 calc(50% - 10px);
        }
    }
    @media (max-width: 650px) {
        .pedido-card {
            flex: 1 1 100%;
        }
        header {
            justify-content: center;
            text-align: center;
        }
        .btn-volver {
            width: 100%;
            text-align: center;
        }
    }

    .pedido-card::before {
        content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; 
        background: #ffc107; border-radius: 16px 0 0 16px;
    }

    .turno-badge {
        width: 60px; height: 60px; background: #231818; border: 2px solid #ffc107;
        border-radius: 50%; display: flex; flex-direction: column; 
        justify-content: center; align-items: center; flex-shrink: 0;
    }
    .turno-badge .label { font-size: 9px; color: #8c8c8c; text-transform: uppercase; font-weight: 600; }
    .turno-badge .numero { font-size: 20px; color: #ffc107; font-weight: 700; line-height: 1.1; }

    .pedido-detalles { flex: 1; min-width: 0; }
    .pedido-detalles h3 { font-size: 18px; color: #ffc107; font-weight: 700; margin-bottom: 10px; }
    
    .meta-grid { 
        display: flex; 
        flex-direction: column;
        gap: 4px; 
        font-size: 13px; 
        color: #8c8c8c; 
        margin-bottom: 16px; 
    }
    .meta-grid span strong { color: #dfdfdf; font-weight: 600; }

    .productos-box {
        background: #090606; border-radius: 10px; border: 1px solid #2d2020; padding: 14px; margin-bottom: 16px;
    }
    .productos-box p { font-size: 11px; color: #8c8c8c; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; font-weight: 600; }
    .productos-list { list-style: none; display: flex; flex-direction: column; gap: 6px; }
    .productos-list li { 
        font-size: 13px; 
        color: #dfdfdf; 
        display: flex; 
        align-items: center; 
        gap: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .productos-list li::before { content: '•'; color: #ffc107; font-size: 14px; }

    .pedido-footer {
        display: flex; justify-content: flex-end; align-items: center; border-top: 1px solid #2d2020; padding-top: 12px;
    }
    .total-box { text-align: right; }
    .total-box p { font-size: 10px; color: #8c8c8c; text-transform: uppercase; font-weight: 600; }
    .total-box h4 { font-size: 20px; color: #00bcd4; font-weight: 700; margin-top: 2px; }

    .loading-status {
        width: 100%; text-align: center; color: #8c8c8c; font-size: 14px; padding: 40px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>

<div class="container">
    <header>
        <div class="header-info">
            <h1>Cola de Pedidos FIFO</h1>
        </div>
        <a href="/admin/dashboard" class="btn-volver">Volver al panel</a>
    </header>

    <div id="cola-pedidos-container">
        <div class="loading-status">Conectando con MongoDB API...</div>
    </div>
</div>

<script>
// 🚀 Consulta asíncrona apuntando a la API real configurada en web.php
async function consultarColaAPI() {
    const contenedor = document.getElementById('cola-pedidos-container');
    if (!contenedor) return;

    try {
        const respuesta = await fetch('/api/v1/admin/cola-pedidos');
        if (!respuesta.ok) throw new Error("Error en la respuesta del servidor");
        
        const pedidos = await respuesta.json();
        
        if (!pedidos || pedidos.length === 0) {
            contenedor.innerHTML = `<div class="loading-status" style="color: #ffc107;">No hay órdenes pendientes en la cola operativa.</div>`;
            return;
        }

        let htmlContenido = '';

        pedidos.forEach((pedido, index) => {
            const metodoPago = pedido.metodo_pago ? pedido.metodo_pago : 'Yape';
            const fechaRegistro = pedido.fecha ? pedido.fecha : 'No especificada';
            
            let itemsHTML = '';
            
            // Control intermedio NoSQL: Verifica si viene serializado como String u objeto directo
            let productosArray = pedido.pedido;
            if (typeof pedido.pedido === 'string') {
                try { productosArray = JSON.parse(pedido.pedido); } catch(e) { productosArray = []; }
            }

            if (Array.isArray(productosArray)) {
                productosArray.forEach(item => {
                    const nombreProd = item.nombre ? item.nombre : 'Producto';
                    itemsHTML += `<li>${nombreProd}</li>`;
                });
            } else if (Array.isArray(pedido.productos)) {
                pedido.productos.forEach(prod => {
                    itemsHTML += `<li>${prod}</li>`;
                });
            } else {
                itemsHTML += `<li>Combo Seleccionado</li>`;
            }

            htmlContenido += `
                <div class="pedido-card">
                    <div class="turno-badge">
                        <span class="label">Turno</span>
                        <span class="numero">${index + 1}</span>
                    </div>

                    <div class="pedido-detalles">
                        <h3>Pedido ${pedido.codigo_pedido || 'N/A'}</h3>
                        
                        <div class="meta-grid">
                            <span>Cliente: <strong>${pedido.cliente || 'Anónimo'}</strong></span>
                            <span>Pago: <strong>${metodoPago}</strong></span>
                            <span>Fecha: <strong>${fechaRegistro}</strong></span>
                        </div>

                        <div class="productos-box">
                            <p>Productos</p>
                            <ul class="productos-list">
                                ${itemsHTML}
                            </ul>
                        </div>

                        <div class="pedido-footer">
                            <div class="total-box">
                                <p>Total Pagado</p>
                                <h4>S/. ${parseFloat(pedido.total_pagado).toFixed(2)}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        contenedor.innerHTML = htmlContenido;

    } catch (error) {
        console.error("Fallo el rastreo asíncrono de la cola:", error);
        contenedor.innerHTML = `<div class="loading-status" style="color: #ff5252;">Error al sincronizar con el backend de Laravel. Reintentando...</div>`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    consultarColaAPI();
    setInterval(consultarColaAPI, 5000);
});
</script>

</body>
</html>