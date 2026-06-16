<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Menú - McDonald</title>

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
    padding:20px;
}

.kiosco-menu{
    width:1180px;
    height:760px;
    display:grid;
    grid-template-columns:220px 1fr 320px;
    background:rgba(15,5,0,0.92);
    border-radius:36px;
    overflow:hidden;
    box-shadow:0 40px 90px rgba(0,0,0,0.75);
    border:1px solid rgba(255,255,255,0.08);
}

.sidebar{
    background:rgba(0,0,0,0.45);
    padding:28px 18px;
}

.logo-menu{
    font-size:62px;
    font-weight:900;
    color:#FFC300;
    font-family:Arial Black, Arial, sans-serif;
    line-height:1;
}

.texto-logo{
    color:rgba(255,195,0,0.55);
    font-size:11px;
    letter-spacing:4px;
    text-transform:uppercase;
    margin-bottom:45px;
}

.opcion{
    width:100%;
    padding:18px;
    margin-bottom:16px;
    border:none;
    border-radius:18px;
    background:rgba(255,255,255,0.08);
    color:white;
    font-size:15px;
    font-weight:700;
    text-align:left;
    cursor:pointer;
    transition:0.2s;
}

.opcion:hover,
.opcion.activa{
    background:#FFC300;
    color:#1a0a00;
}

.contenido-menu{
    padding:34px;
    overflow-y:auto;
    color:white;
}

.Nombre-user{
    color:#FFC300;
    font-size:18px;
    font-weight:800;
    margin-bottom:8px;
}

.contenido-menu h1{
    font-size:48px;
    margin-bottom:6px;
}

.contenido-menu h2{
    font-size:28px;
    color:rgba(255,255,255,0.65);
    margin-bottom:28px;
}

.buscar{
    width:100%;
    padding:18px 22px;
    border-radius:22px;
    border:1px solid rgba(255,255,255,0.12);
    background:rgba(255,255,255,0.08);
    color:white;
    font-size:17px;
    outline:none;
    margin-bottom:34px;
}

.buscar::placeholder{
    color:rgba(255,255,255,0.35);
}

.productos{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:24px;
}

.producto{
    background:rgba(255,255,255,0.08);
    border-radius:28px;
    padding:18px;
    color:white;
    transition:0.2s;
}

.producto:hover{
    transform:translateY(-5px);
    background:rgba(255,255,255,0.12);
}

.producto img{
    width:100%;
    height:170px;
    object-fit:cover;
    border-radius:20px;
    margin-bottom:16px;
}

.producto h3{
    font-size:18px;
    margin-bottom:10px;
    line-height:1.3;
}

.producto p{
    color:#FFC300;
    font-size:18px;
    font-weight:900;
    margin-bottom:14px;
}

.producto button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:18px;
    background:#FFC300;
    color:#1a0a00;
    font-size:18px;
    font-weight:900;
    cursor:pointer;
}

.carrito{
    background:rgba(0,0,0,0.52);
    padding:28px 22px;
    display:flex;
    flex-direction:column;
    overflow:hidden;
}

.carrito h2{
    color:white;
    font-size:28px;
    margin-bottom:24px;
}

#listaCarrito{
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
    gap:10px;
}

.carrito-info{
    display:flex;
    align-items:center;
    gap:12px;
    flex:1;
}

.img-carrito{
    width:58px;
    height:58px;
    object-fit:cover;
    border-radius:14px;
    flex-shrink:0;
}

.item-carrito span{
    color:white;
    font-size:14px;
    font-weight:700;
    line-height:1.2;
}

.item-carrito b{
    color:#FFC300;
    font-size:14px;
    white-space:nowrap;
}

.btn-quitar{
    width:28px;
    height:28px;
    border:none;
    border-radius:50%;
    background:#FFC300;
    color:#1a0a00;
    font-size:18px;
    font-weight:900;
    cursor:pointer;
    flex-shrink:0;
}

.carrito-vacio{
    color:rgba(255,255,255,0.55);
    font-size:15px;
    margin-top:20px;
}

.total{
    padding-top:18px;
    border-top:1px solid rgba(255,255,255,0.12);
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:white;
    font-size:18px;
    margin-top:10px;
}

.total b{
    color:#FFC300;
    font-size:22px;
}

.btn-final{
    width:100%;
    margin-top:20px;
    padding:18px;
    border:none;
    border-radius:20px;
    background:#FFC300;
    color:#1a0a00;
    font-size:18px;
    font-weight:900;
    cursor:pointer;
}

.sin-resultados{
    display:none;
    text-align:center;
    color:#FFC300;
    font-size:22px;
    margin-top:60px;
}

@media(max-width:1100px){
    body{
        padding:0;
    }

    .kiosco-menu{
        width:100%;
        height:100vh;
        border-radius:0;
        grid-template-columns:1fr;
    }

    .sidebar{
        display:none;
    }

    .carrito{
        border-top:1px solid rgba(255,255,255,0.08);
    }

    .productos{
        grid-template-columns:repeat(2,1fr);
    }
}
</style>
</head>

<body>

<div class="kiosco-menu">

    <aside class="sidebar">

        <div class="logo-menu">M</div>
        <p class="texto-logo">ME ENCANTA</p>

        <button class="opcion activa" onclick="filtrarCategoria('Todos', this)">🏠 Inicio</button>
        <button class="opcion" onclick="filtrarCategoria('Hamburguesas', this)">🍔 Hamburguesas</button>
        <button class="opcion" onclick="filtrarCategoria('McCombos', this)">🍟 McCombos</button>
        <button class="opcion" onclick="filtrarCategoria('Bebidas', this)">🥤 Bebidas</button>
        <button class="opcion" onclick="filtrarCategoria('Postres', this)">🍦 Postres</button>

    </aside>

    <main class="contenido-menu">

        <p class="Nombre-user">
            Hola, {{ session('Nombre') }}
        </p>

        <h1>¡Hola!</h1>
        <h2>¿Qué deseas pedir hoy?</h2>

        <input
            class="buscar"
            type="text"
            id="buscador"
            placeholder="Buscar productos..."
            onkeyup="buscarProducto()"
        >

        <div class="productos" id="contenedorProductos"></div>

        <div id="sinResultados" class="sin-resultados">
            Producto sin stock o no encontrado
        </div>

    </main>

    <aside class="carrito">

        <h2>Tu pedido</h2>

        <div id="listaCarrito"></div>

        <div class="total">
            <span>Total</span>
            <b id="totalCarrito">S/ 0.00</b>
        </div>

        <button class="btn-final" onclick="irAPago()">
            Ver pedido →
        </button>

    </aside>

</div>

<script>
const rutaPago = '/pago';
</script>

<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>