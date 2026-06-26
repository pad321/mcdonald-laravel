<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pago</title>
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

/* 📦 CONTENEDOR ELÁSTICO MULTIDISPOSITIVO */
.pago-contenedor{
    width:100%;
    max-width:1150px;
    min-height:720px;
    background:rgba(15,5,0,0.92);
    border-radius:36px;
    overflow:hidden;
    display:flex;
    flex-wrap:wrap; /* Permite la reestructuración automática en tablets y celulares */
    box-shadow:0 40px 90px rgba(0,0,0,0.75);
    border:1px solid rgba(255,255,255,0.08);
}

/* COLUMNA IZQUIERDA: RESUMEN DEL PEDIDO */
.resumen-pago{
    flex:1 1 500px; /* Crece dinámicamente, base ideal de 500px */
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
    font-size: clamp(28px, 4vw, 40px); /* Escalado fluido de tipografía */
    margin-bottom:28px;
}

#resumenPago{
    flex:1;
    overflow-y:auto;
    overflow-x:hidden;
    padding-right:6px;
    max-height: 380px; /* Previene la deformación si se compran muchos productos */
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
    min-width:0; /* Clave: permite que el contenedor respete los límites flexibles */
}

.img-carrito{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:14px;
    flex-shrink:0; /* Evita que la foto se aplaste */
}

.item-carrito span{
    color:white;
    font-size:16px;
    font-weight:700;
    line-height:1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; /* Agrega puntos suspensivos si el nombre es muy largo */
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
    font-weight:900;
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
    transition: background 0.2s;
}

.btn-secundario:hover{
    background:rgba(255,255,255,0.14);
}

/* COLUMNA DERECHA: MÉTODOS DE PAGO Y PASARELA */
.metodos-pago{
    flex:1 1 430px; /* Base ideal original de 430px */
    background:rgba(0,0,0,0.45);
    padding:42px 30px;
    color:white;
    display:flex;
    flex-direction:column;
    overflow:hidden;
    border-left: 1px solid rgba(255,255,255,0.05);
}

.metodos-pago h2{
    font-size: clamp(22px, 3vw, 28px);
    margin-bottom:28px;
}

.grid-pagos{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}

.metodo{
    padding:22px 14px;
    border:none;
    border-radius:22px;
    background:rgba(255,255,255,0.08);
    color:white;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    transition:0.2s;
    text-align: center;
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
    margin-top:40px; /* Asegura separación del formulario dinámico */
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
    width: 100%;
}

/* 📱 REGLAS DE ACOMODO EXCLUSIVAS PARA MOVILES */
@media(max-width:920px){
    body{
        padding:12px;
    }

    .pago-contenedor{
        border-radius:24px;
        height: auto; /* Quitamos rigidez para prevenir desbordes */
    }

    .resumen-pago{
        padding: 34px 24px;
        flex: 1 1 100%;
    }

    .metodos-pago{
        border-left:none;
        border-top:1px solid rgba(255,255,255,0.08);
        padding: 34px 24px;
        flex: 1 1 100%;
    }
    
    .grid-pagos{
        grid-template-columns: 1fr; /* Los botones se apilan verticalmente de forma óptima */
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