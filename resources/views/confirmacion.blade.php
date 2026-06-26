@php
$Nombre = session('Nombre');
$codigo = 'A-' . rand(100,999);
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmación</title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍔</text></svg>">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    min-height:100vh;
    background-image:
        linear-gradient(rgba(15,5,0,0.82), rgba(15,5,0,0.92)),
        url('{{ asset("img/fondo2.png") }}');
    background-size:cover;
    background-position:center;
    font-family:Arial, sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

/* El contenedor principal cambia a Flexbox flexible */
.confirmacion{
    width:100%;
    max-width:1120px;
    background:rgba(15,5,0,0.92);
    border-radius:36px;
    overflow:hidden;
    display:flex;
    flex-wrap:wrap; /* Permite que las columnas bajen solas en celulares y tablets */
    box-shadow:0 40px 90px rgba(0,0,0,0.75);
    border:1px solid rgba(255,255,255,0.08);
}

.mensaje-confirmacion{
    flex: 1 1 550px; /* Base elástica adaptable */
    padding:48px;
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.check{
    width:90px;
    height:90px;
    border-radius:50%;
    background:#FFC300;
    color:#1a0a00;
    font-size:58px;
    font-weight:900;
    display:flex;
    justify-content:center;
    align-items:center;
    margin-bottom:28px;
    box-shadow:0 0 35px rgba(255,195,0,0.35);
}

.mensaje-confirmacion h1{
    font-size: clamp(30px, 4vw, 44px); /* Escalado tipográfico responsivo */
    line-height:1.1;
    margin-bottom:22px;
}

.mensaje-confirmacion p{
    color:rgba(255,255,255,0.65);
    font-size:17px;
    margin-bottom:8px;
}

.codigo-pedido{
    margin:18px 0 30px;
    width:max-content;
    padding:18px 34px;
    border-radius:24px;
    background:rgba(255,195,0,0.12);
    color:#FFC300;
    font-size:48px;
    font-weight:900;
    letter-spacing:4px;
    border:1px solid rgba(255,195,0,0.35);
}

.estado-pedido{
    width: 100%;
    background:rgba(255,255,255,0.08);
    border-radius:28px;
    padding:26px;
}

.estado-pedido h2{
    font-size:26px;
    margin-bottom:10px;
}

.estado-pedido p{
    margin-bottom:18px;
}

.estado-pedido b{
    color:#FFC300;
}

.barra{
    width:100%;
    height:16px;
    border-radius:20px;
    background:rgba(255,255,255,0.12);
    overflow:hidden;
    margin-bottom:14px;
}

.progreso{
    width:35%; 
    height:100%;
    background:#FFC300;
    border-radius:20px;
    box-shadow: 0 0 10px #FFC300;
    transition:width 1s linear;
}

.pasos-estado{
    display:flex;
    justify-content:space-between;
    color:rgba(255,255,255,0.55);
    font-size:14px;
}

.btn-final{
    width:100%;
    margin-top:26px;
    padding:18px;
    border:none;
    border-radius:20px;
    background:#FFC300;
    color:#1a0a00;
    font-size:18px;
    font-weight:900;
    cursor:pointer;
    transition: background 0.2s, transform 0.1s;
}

.btn-final:hover{
    background:#ffb000;
}

.btn-final:active{
    transform: scale(0.99);
}

.detalle-confirmacion{
    flex: 1 1 370px; /* Base para el panel lateral derecho */
    background:rgba(0,0,0,0.48);
    padding:34px 26px;
    color:white;
    display:flex;
    flex-direction:column;
    overflow:hidden;
    border-left: 1px solid rgba(255,255,255,0.05);
}

.detalle-confirmacion h2{
    font-size:26px;
    margin-bottom:24px;
    font-weight: 800;
}

#detalleConfirmacion{
    flex:1;
    overflow-y:auto;
    overflow-x:hidden;
    padding-right:4px;
    max-height: 440px; 
}

.item-carrito{
    width:100%;
    background:rgba(255,255,255,0.06);
    border-radius:18px;
    padding:14px;
    margin-bottom:14px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
}

.carrito-info{
    display:flex;
    align-items:center;
    gap:14px;
    flex:1;
    min-width: 0;
}

.img-carrito{
    width:55px;
    height:55px;
    object-fit:cover; 
    border-radius:12px;
    flex-shrink:0; 
    background: #140e0e;
}

.item-carrito span{
    color:white;
    font-size:14px;
    font-weight:700;
    line-height:1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; 
}

.item-carrito b{
    color:#FFC300;
    font-size:14px;
    white-space:nowrap;
    flex-shrink:0; 
}

.total{
    margin-top:20px;
    padding-top:20px;
    border-top:1px solid rgba(255,255,255,0.12);
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:white;
    font-size:18px;
}

.total b{
    color:#FFC300;
    font-size:24px;
    font-weight: 900;
}

/* 📱 REGLAS DE CORRECCIÓN RESPONSIVA ADAPTATIVA */
@media(max-width:920px){
    body{
        padding:15px;
    }

    .confirmacion{
        border-radius:24px;
    }

    .mensaje-confirmacion{
        padding:40px 24px;
        align-items: center;
        text-align: center;
    }

    .mensaje-confirmacion h1{
        font-size:32px;
    }

    .codigo-pedido{
        font-size:36px;
        padding: 14px 28px;
        margin: 18px auto 30px; 
    }

    .estado-pedido{
        text-align: left; 
    }

    .detalle-confirmacion{
        border-left: none;
        border-top:1px solid rgba(255,255,255,0.08);
        padding: 34px 24px;
    }
}
</style>
</head>

<body>

<div class="confirmacion">

    <div class="mensaje-confirmacion">
        <div class="check">✓</div>
        <h1>¡Pedido realizado con éxito!</h1>
        <p>Hola, {{ $Nombre }}</p>
        <p>Tu número de pedido es:</p>

        <div class="codigo-pedido" id="codigoPedido">
            {{ $codigo }}
        </div>

        <div class="estado-pedido">
            <h2>Preparando tu pedido...</h2>
            <p>Tiempo restante: <b id="tiempoRestante">1:00</b></p>

            <div class="barra">
                <div class="progreso" id="barraProgreso"></div>
            </div>

            <div class="pasos-estado">
                <span>Recibido</span>
                <span>Preparando</span>
                <span>Listo</span>
            </div>
        </div>

        <button class="btn-final" id="btnFinalizarPedido">
            Finalizar pedido
        </button>
    </div>

    <div class="detalle-confirmacion">
        <h2>Detalle del pedido</h2>
        <div id="detalleConfirmacion"></div>
        <div class="total">
            <span>Total pagado</span>
            <b id="totalConfirmacion">S/ 0.00</b>
        </div>
    </div>

</div>

<script src="{{ asset('js/script.js') }}"></script>

<script>
const nombreCliente = @json($Nombre);
const codigoPedido = @json($codigo);

document.addEventListener('DOMContentLoaded', () => {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const contenedor = document.getElementById('detalleConfirmacion');
    const totalTxt = document.getElementById('totalConfirmacion');
    
    if (carrito.length === 0) {
        contenedor.innerHTML = '<p style="color: rgba(255,255,255,0.4); font-size: 14px;">No hay productos en el carrito.</p>';
        return;
    }

    let html = '';
    let totalAcumulado = 0;

    carrito.forEach(item => {
        totalAcumulado += Number(item.precio);
        
        // 🚀 CORRECCIÓN CLAVE: Extraer solo el nombre limpio del archivo para armar la ruta local exacta
        let nombreArchivo = item.imagen ? item.imagen.split('/').pop() : '';
        const imgPath = nombreArchivo ? `/img/pedidos/${nombreArchivo}` : 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=120&auto=format&fit=crop';

        html += `
            <div class="item-carrito">
                <div class="carrito-info">
                    <img src="${imgPath}" class="img-carrito" alt="${item.nombre}">
                    <span>${item.nombre}</span>
                </div>
                <b>S/ ${Number(item.precio).toFixed(2)}</b>
            </div>
        `;
    });

    contenedor.innerHTML = html;
    totalTxt.innerHTML = `<b>S/ ${totalAcumulado.toFixed(2)}</b>`;
});

document.getElementById('btnFinalizarPedido').addEventListener('click', function(){
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const metodoPago = localStorage.getItem('metodoPago') || '';

    let total = 0;
    carrito.forEach(item => {
        total += Number(item.precio);
    });

    fetch('/guardar-pedido', {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body:JSON.stringify({
            cliente:nombreCliente,
            codigo_pedido:codigoPedido,
            pedido:carrito,
            metodo_pago:metodoPago,
            total_pagado:total
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.ok){
            localStorage.removeItem('carrito');
            localStorage.removeItem('metodoPago');
            window.location.href='/pedido-listo';
        }else{
            alert('No se pudo guardar el pedido');
        }
    })
    .catch(error => {
        alert('Error al guardar en MongoDB');
        console.log(error);
    });
});
</script>

</body>
</html>