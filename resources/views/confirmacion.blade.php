@php
$Nombre = session('Nombre');
$codigo = 'A-' . rand(100,999);
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Confirmación</title>

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
        url('{{ asset('img/fondo2.png') }}');
    background-size:cover;
    background-position:center;
    font-family:Arial, sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px;
}

.confirmacion{
    width:1120px;
    height:720px;
    background:rgba(15,5,0,0.92);
    border-radius:36px;
    overflow:hidden;
    display:grid;
    grid-template-columns:1fr 390px;
    box-shadow:0 40px 90px rgba(0,0,0,0.75);
    border:1px solid rgba(255,255,255,0.08);
}

.mensaje-confirmacion{
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
    font-size:44px;
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
    width:0%;
    height:100%;
    background:#FFC300;
    border-radius:20px;
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
}

.btn-final:hover{
    background:#ffb000;
}

.detalle-confirmacion{
    background:rgba(0,0,0,0.48);
    padding:34px 26px;
    color:white;
    display:flex;
    flex-direction:column;
    overflow:hidden;
}

.detalle-confirmacion h2{
    font-size:30px;
    margin-bottom:24px;
}

#detalleConfirmacion{
    flex:1;
    overflow-y:auto;
    overflow-x:hidden;
    padding-right:4px;
}

.item-carrito{
    width:100%;
    background:rgba(255,255,255,0.08);
    border-radius:18px;
    padding:12px;
    margin-bottom:14px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
}

.carrito-info{
    display:flex;
    align-items:center;
    gap:12px;
    flex:1;
}

.img-carrito{
    width:65px;
    height:65px;
    object-fit:cover;
    border-radius:14px;
    flex-shrink:0;
}

.item-carrito span{
    color:white;
    font-size:15px;
    font-weight:700;
    line-height:1.2;
}

.item-carrito b{
    color:#FFC300;
    font-size:15px;
    white-space:nowrap;
}

.total{
    margin-top:20px;
    padding-top:20px;
    border-top:1px solid rgba(255,255,255,0.12);
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:white;
    font-size:20px;
}

.total b{
    color:#FFC300;
    font-size:24px;
}

@media(max-width:900px){

    body{
        padding:0;
    }

    .confirmacion{
        width:100%;
        height:100vh;
        border-radius:0;
        grid-template-columns:1fr;
    }

    .mensaje-confirmacion{
        padding:34px;
    }

    .mensaje-confirmacion h1{
        font-size:34px;
    }

    .codigo-pedido{
        font-size:36px;
    }

    .detalle-confirmacion{
        border-top:1px solid rgba(255,255,255,0.08);
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

            <p>
                Tiempo restante:
                <b id="tiempoRestante">1:00</b>
            </p>

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