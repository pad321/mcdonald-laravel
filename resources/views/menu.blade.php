<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menú - McDonald</title>
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

/* 📦 CONTENEDOR FLEXIBLE ADAPTATIVO */
.kiosco-menu{
    width: 100%;
    max-width: 1200px;
    min-height: 780px;
    display: flex;
    flex-wrap: wrap; /* Clave: permite que los bloques se reorganicen solos en móviles */
    background:rgba(15,5,0,0.92);
    border-radius:36px;
    overflow:hidden;
    box-shadow:0 40px 90px rgba(0,0,0,0.75);
    border:1px solid rgba(255,255,255,0.08);
}

/* SIDEBAR IZQUIERDA */
.sidebar{
    flex: 1 1 220px;
    background:rgba(0,0,0,0.45);
    padding:28px 18px;
    display: flex;
    flex-direction: column;
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
    padding:16px 18px;
    margin-bottom:12px;
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

/* PANEL CENTRAL DE PRODUCTOS */
.contenido-menu{
    flex: 2 1 600px; /* Base elástica */
    padding:34px;
    max-height: 800px;
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
    font-size: clamp(32px, 5vw, 48px); /* Letra fluida según tamaño de pantalla */
    margin-bottom:6px;
}

.contenido-menu h2{
    font-size: clamp(20px, 3vw, 28px);
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

/* GRILLA INTERNA DE PRODUCTOS AUTOMÁTICA */
.productos{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); /* Reorganiza las columnas solas sin romper la tarjeta */
    gap:24px;
}

.producto{
    background:rgba(255,255,255,0.08);
    border-radius:28px;
    padding:18px;
    color:white;
    transition:0.2s;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.producto:hover{
    transform:translateY(-5px);
    background:rgba(255,255,255,0.12);
}

.producto img{
    width:100%;
    height:160px;
    object-fit:cover;
    border-radius:20px;
    margin-bottom:16px;
    content-visibility: auto;
}

.producto h3{
    font-size:17px;
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
    padding:14px;
    border:none;
    border-radius:18px;
    background:#FFC300;
    color:#1a0a00;
    font-size:16px;
    font-weight:900;
    cursor:pointer;
}

/* ASIDE DERECHO (CARRITO DE COMPRAS) */
.carrito{
    flex: 1 1 320px;
    background:rgba(0,0,0,0.52);
    padding:28px 22px;
    display:flex;
    flex-direction:column;
    overflow:hidden;
    border-left: 1px solid rgba(255,255,255,0.05);
}

.carrito h2{
    color:white;
    font-size:26px;
    margin-bottom:24px;
}

#listaCarrito{
    flex:1;
    overflow-y:auto;
    overflow-x:hidden;
    padding-right:4px;
    max-height: 400px;
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
    min-width: 0;
}

.img-carrito{
    width:55px;
    height:55px;
    object-fit:cover;
    border-radius:14px;
    flex-shrink:0;
}

.item-carrito span{
    color:white;
    font-size:14px;
    font-weight:700;
    line-height:1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; /* Evita que nombres largos descuadren la foto */
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

/* 📱 REGLAS DE RESPONSIVIDAD PARA CELULARES Y TABLETS */
@media(max-width:860px){
    body{
        padding:10px;
    }

    .kiosco-menu{
        border-radius:24px;
        min-height: auto;
    }

    /* Transformamos el sidebar lateral en una barra de navegación horizontal superior fluida */
    .sidebar{
        flex: 1 1 100%;
        flex-direction: row;
        align-items: center;
        gap: 10px;
        overflow-x: auto; /* Permite scroll de categorías con el pulgar */
        padding: 14px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .logo-menu, .texto-logo{
        display: none; /* Escondemos el logo estático en móviles para ganar espacio */
    }

    .opcion{
        width: auto;
        white-space: nowrap;
        margin-bottom: 0;
        padding: 12px 20px;
    }

    .contenido-menu{
        flex: 1 1 100%;
        max-height: none;
        padding: 24px 18px;
    }

    .carrito{
        flex: 1 1 100%;
        border-left: none;
        border-top:1px solid rgba(255,255,255,0.08);
        padding: 24px 18px;
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

// Array de productos base con nombres mapeados
const productosBase = [
    { id: 1, nombre: 'Big Mac', precio: 18.90, categoria: 'Hamburguesas', img: '{{ asset("img/pedidos/bigmac.png") }}' },
    { id: 2, nombre: 'Cuarto de Libra', precio: 21.90, categoria: 'Hamburguesas', img: '{{ asset("img/pedidos/cuarto_de_libra.png") }}' },
    { id: 3, nombre: 'McPollo', precio: 16.90, categoria: 'Hamburguesas', img: '{{ asset("img/pedidos/mcpollo.png") }}' },
    { id: 4, nombre: 'Combo Big Mac', precio: 25.90, categoria: 'McCombos', img: '{{ asset("img/pedidos/mc.combobigmac.png") }}' },
    { id: 5, nombre: 'Combo Cuarto de Libra', precio: 28.90, categoria: 'McCombos', img: '{{ asset("img/pedidos/mc.combocuartodelibra.png") }}' },
    { id: 6, nombre: 'Inka Cola Mediana', precio: 5.50, categoria: 'Bebidas', img: '{{ asset("img/pedidos/inkamediana.png") }}' },
    { id: 7, nombre: 'Coca Cola Mediana', precio: 5.50, categoria: 'Bebidas', img: '{{ asset("img/pedidos/cocamediana.png") }}' },
    { id: 8, nombre: 'McFlurry', precio: 8.90, categoria: 'Postres', img: '{{ asset("img/pedidos/mcflurry.png") }}' },
    { id: 9, nombre: 'Pie de Manzana', precio: 6.90, categoria: 'Postres', img: '{{ asset("img/pedidos/piedemanzana.png") }}' }
];

function renderizarProductos(lista) {
    const contenedor = document.getElementById('contenedorProductos');
    const sinResultados = document.getElementById('sinResultados');
    if (!contenedor) return;
    contenedor.innerHTML = '';
    
    if(lista.length === 0) {
        if (sinResultados) sinResultados.style.display = 'block';
        return;
    }
    
    if (sinResultados) sinResultados.style.display = 'none';
    lista.forEach(p => {
        contenedor.innerHTML += `
            <div class="producto" data-categoria="${p.categoria}">
                <img src="${p.img}" alt="${p.nombre}" loading="lazy">
                <h3>${p.nombre}</h3>
                <p>S/ ${p.precio.toFixed(2)}</p>
                <button onclick="agregarAlCarrito(${p.id}, '${p.nombre}', ${p.precio}, '${p.img}')">Agregar +</button>
            </div>
        `;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (typeof productos !== 'undefined') {
        // Respeta el array si ya fue instanciado externamente
    } else {
        window.productos = productosBase;
        renderizarProductos(window.productos);
    }
});
</script>

<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>