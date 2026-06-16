<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pago</title>

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

.pago-contenedor{
    width:1150px;
    height:720px;
    background:rgba(15,5,0,0.92);
    border-radius:36px;
    overflow:hidden;
    display:grid;
    grid-template-columns:1fr 430px;
    box-shadow:0 40px 90px rgba(0,0,0,0.75);
    border:1px solid rgba(255,255,255,0.08);
}

.resumen-pago{
    padding:42px;
    color:white;
    overflow:hidden;
    display:flex;
    flex-direction:column;
}

.Nombre-user{
    color:#FFC300;
    font-size:17px;
    font-weight:700;
    margin-bottom:12px;
}

.resumen-pago h1{
    font-size:40px;
    margin-bottom:28px;
}

#resumenPago{
    flex:1;
    overflow-y:auto;
    overflow-x:hidden;
    padding-right:6px;
    display:flex;
    flex-direction:column;
    gap:14px;
}

.item-carrito{
    width:100%;
    background:rgba(255,255,255,0.08);
    border-radius:18px;
    padding:12px;
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
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:14px;
    flex-shrink:0;
}

.item-carrito span{
    color:white;
    font-size:16px;
    font-weight:700;
    line-height:1.2;
}

.item-carrito b{
    color:#FFC300;
    font-size:16px;
    white-space:nowrap;
}

.btn-quitar{
    width:30px;
    height:30px;
    border:none;
    border-radius:50%;
    background:#FFC300;
    color:#1a0a00;
    font-size:18px;
    font-weight:900;
    cursor:pointer;
    flex-shrink:0;
}

.total{
    margin-top:22px;
    padding-top:22px;
    border-top:1px solid rgba(255,255,255,0.12);
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:22px;
    color:white;
}

.total b{
    color:#FFC300;
}

.btn-secundario{
    margin-top:22px;
    width:100%;
    padding:17px;
    border:none;
    border-radius:18px;
    background:rgba(255,255,255,0.08);
    color:white;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
}

.btn-secundario:hover{
    background:rgba(255,255,255,0.14);
}

.metodos-pago{
    background:rgba(0,0,0,0.45);
    padding:42px 30px;
    color:white;
    display:flex;
    flex-direction:column;
    overflow:hidden;
}

.metodos-pago h2{
    font-size:28px;
    margin-bottom:28px;
}

.grid-pagos{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}

.metodo{
    padding:22px;
    border:none;
    border-radius:22px;
    background:rgba(255,255,255,0.08);
    color:white;
    font-size:18px;
    font-weight:700;
    cursor:pointer;
    transition:0.2s;
}

.metodo:hover,
.metodo.activo{
    transform:translateY(-3px);
    background:#FFC300;
    color:#1a0a00;
}

.metodo-texto{
    margin-top:24px;
    color:rgba(255,255,255,0.6);
    font-size:15px;
    line-height:1.5;
}

.formulario-pago{
    margin-top:22px;
    display:flex;
    flex-direction:column;
    gap:14px;
}

.formulario-pago h3{
    font-size:20px;
    color:#FFC300;
}

.campo-pago{
    width:100%;
    padding:16px 18px;
    border-radius:16px;
    border:1px solid rgba(255,255,255,0.12);
    background:rgba(255,255,255,0.08);
    color:white;
    font-size:15px;
    outline:none;
}

.campo-pago::placeholder{
    color:rgba(255,255,255,0.35);
}

.btn-final{
    margin-top:auto;
    width:100%;
    padding:18px;
    border:none;
    border-radius:20px;
    background:#FFC300;
    color:#1a0a00;
    font-size:18px;
    font-weight:900;
    cursor:pointer;
    transition:0.2s;
}

.btn-final:hover{
    background:#ffb000;
}

a{
    text-decoration:none;
}

@media(max-width:900px){
    body{
        padding:0;
    }

    .pago-contenedor{
        width:100%;
        height:100vh;
        border-radius:0;
        grid-template-columns:1fr;
    }

    .metodos-pago{
        border-top:1px solid rgba(255,255,255,0.08);
    }
}
</style>
</head>

<body>

<div class="pago-contenedor">

    <div class="resumen-pago">
        <p class="Nombre-user">Hola, {{ session('Nombre') }}</p>

        <h1>Resumen de tu pedido</h1>

        <div id="resumenPago"></div>

        <div class="total">
            <span>Total a pagar</span>
            <b id="totalPago">S/ 0.00</b>
        </div>

        <a href="/menu">
            <button class="btn-secundario">← Seguir comprando</button>
        </a>
    </div>

    <div class="metodos-pago">
        <h2>Selecciona tu método de pago</h2>

        <div class="grid-pagos">
            <button class="metodo" onclick="seleccionarMetodo(this, 'Tarjeta')">💳 Tarjeta</button>
            <button class="metodo" onclick="seleccionarMetodo(this, 'Yape')">📱 Yape</button>
            <button class="metodo" onclick="seleccionarMetodo(this, 'Plin')">📱 Plin</button>
            <button class="metodo" onclick="seleccionarMetodo(this, 'Efectivo')">💵 Efectivo</button>
        </div>

        <p id="metodoSeleccionado" class="metodo-texto">
            Selecciona un método de pago para continuar.
        </p>

        <div id="formularioPago" class="formulario-pago"></div>

        <button class="btn-final" onclick="pagarPedido()">Pagar ahora →</button>
    </div>

</div>

<script>
const rutaConfirmacion = '/confirmacion';
</script>

<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>