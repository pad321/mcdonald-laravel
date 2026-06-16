let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
let categoriaActual = 'Todos';
let metodoPago = localStorage.getItem('metodoPago') || '';

const productos = [
    { nombre: 'Big Mac', precio: 18.90, imagen: 'img/pedidos/bigmac.png', categoria: 'Hamburguesas' },
    { nombre: 'Cuarto de Libra', precio: 21.90, imagen: 'img/pedidos/cuarto de libra.png', categoria: 'Hamburguesas' },
    { nombre: 'McPollo', precio: 17.50, imagen: 'img/pedidos/mcpollo.png', categoria: 'Hamburguesas' },
    { nombre: 'McCombo Big Mac', precio: 28.90, imagen: 'img/pedidos/mc.combobigmac.png', categoria: 'McCombos' },
    { nombre: 'McCombo Cuarto de Libra', precio: 31.90, imagen: 'img/pedidos/mc.combocuartodelibra.png', categoria: 'McCombos' },
    { nombre: 'Coca-Cola Mediana', precio: 6.90, imagen: 'img/pedidos/cocamediana.png', categoria: 'Bebidas' },
    { nombre: 'Inca Kola Mediana', precio: 6.90, imagen: 'img/pedidos/inkamediana.png', categoria: 'Bebidas' },
    { nombre: 'McFlurry Oreo', precio: 9.90, imagen: 'img/pedidos/mcflurry.png', categoria: 'Postres' },
    { nombre: 'Pie de Manzana', precio: 7.50, imagen: 'img/pedidos/piedemanzana.png', categoria: 'Postres' }
];

function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

function mostrarProductos(lista = productos) {
    const contenedor = document.getElementById('contenedorProductos');
    const sinResultados = document.getElementById('sinResultados');

    if (!contenedor) return;

    contenedor.innerHTML = '';

    if (lista.length === 0) {
        if (sinResultados) sinResultados.style.display = 'block';
        return;
    }

    if (sinResultados) sinResultados.style.display = 'none';

    lista.forEach(producto => {
        contenedor.innerHTML += `
            <div class="producto">
                <img src="${producto.imagen}" class="img-producto" alt="${producto.nombre}">
                <h3>${producto.nombre}</h3>
                <p>S/ ${producto.precio.toFixed(2)}</p>
                <button onclick="agregarProducto('${producto.nombre}', ${producto.precio}, '${producto.imagen}')">Agregar +</button>
            </div>
        `;
    });
}

function filtrarCategoria(categoria, boton) {
    categoriaActual = categoria;

    document.querySelectorAll('.opcion').forEach(btn => {
        btn.classList.remove('activa');
    });

    if (boton) boton.classList.add('activa');

    const buscador = document.getElementById('buscador');
    if (buscador) buscador.value = '';

    if (categoria === 'Todos') {
        mostrarProductos(productos);
    } else {
        mostrarProductos(productos.filter(p => p.categoria === categoria));
    }
}

function buscarProducto() {
    const buscador = document.getElementById('buscador');
    if (!buscador) return;

    const texto = buscador.value.toLowerCase();
    let lista = productos;

    if (categoriaActual !== 'Todos') {
        lista = productos.filter(p => p.categoria === categoriaActual);
    }

    mostrarProductos(lista.filter(p => p.nombre.toLowerCase().includes(texto)));
}

function agregarProducto(nombre, precio, imagen) {
    carrito.push({ nombre, precio, imagen });
    guardarCarrito();
    mostrarCarrito();
}

function quitarProducto(index) {
    carrito.splice(index, 1);
    guardarCarrito();
    mostrarCarrito();
    mostrarResumenPago();
    mostrarConfirmacion();
}

function mostrarCarrito() {
    const lista = document.getElementById('listaCarrito');
    const total = document.getElementById('totalCarrito');

    if (!lista || !total) return;

    lista.innerHTML = '';
    let suma = 0;

    if (carrito.length === 0) {
        lista.innerHTML = `<p class="carrito-vacio">Aún no agregaste productos.</p>`;
    }

    carrito.forEach((item, index) => {
        suma += item.precio;

        lista.innerHTML += `
            <div class="item-carrito">
                <div class="carrito-info">
                    <img src="${item.imagen}" class="img-carrito" alt="${item.nombre}">
                    <span>${item.nombre}</span>
                </div>
                <b>S/ ${item.precio.toFixed(2)}</b>
                <button class="btn-quitar" onclick="quitarProducto(${index})">×</button>
            </div>
        `;
    });

    total.innerText = 'S/ ' + suma.toFixed(2);
}

function irAPago() {
    if (carrito.length === 0) {
        alert('Primero agrega productos a tu pedido.');
        return;
    }

    window.location.href = '/pago';
}

function mostrarResumenPago() {
    const resumen = document.getElementById('resumenPago');
    const totalPago = document.getElementById('totalPago');

    if (!resumen || !totalPago) return;

    resumen.innerHTML = '';
    let suma = 0;

    if (carrito.length === 0) {
        alert('No tienes productos en el pedido.');
        window.location.href = '/menu';
        return;
    }

    carrito.forEach((item, index) => {
        suma += item.precio;

        resumen.innerHTML += `
            <div class="item-carrito">
                <div class="carrito-info">
                    <img src="${item.imagen}" class="img-carrito" alt="${item.nombre}">
                    <span>1 ${item.nombre}</span>
                </div>
                <b>S/ ${item.precio.toFixed(2)}</b>
                <button class="btn-quitar" onclick="quitarProducto(${index})">×</button>
            </div>
        `;
    });

    totalPago.innerText = 'S/ ' + suma.toFixed(2);
}

function seleccionarMetodo(boton, metodo) {
    metodoPago = metodo;
    localStorage.setItem('metodoPago', metodo);

    document.querySelectorAll('.metodo').forEach(btn => {
        btn.classList.remove('activo');
    });

    if (boton) boton.classList.add('activo');

    const texto = document.getElementById('metodoSeleccionado');
    const formulario = document.getElementById('formularioPago');

    if (texto) {
        texto.innerText = 'Método seleccionado: ' + metodo;
    }

    if (!formulario) return;

    if (metodo === 'Tarjeta') {
        formulario.innerHTML = `
            <h3>Datos de tarjeta</h3>
            <input class="campo-pago" type="text" placeholder="Número de tarjeta">
            <input class="campo-pago" type="text" placeholder="Nombre del titular">
            <input class="campo-pago" type="text" placeholder="Fecha de vencimiento MM/AA">
            <input class="campo-pago" type="text" placeholder="CVV">
        `;
    }

    if (metodo === 'Yape') {
        formulario.innerHTML = `
            <h3>Datos de Yape</h3>
            <input class="campo-pago" type="text" placeholder="Número de celular">
            <input class="campo-pago" type="text" placeholder="Código de aprobación">
        `;
    }

    if (metodo === 'Plin') {
        formulario.innerHTML = `
            <h3>Datos de Plin</h3>
            <input class="campo-pago" type="text" placeholder="Número de celular">
            <input class="campo-pago" type="text" placeholder="Código de aprobación">
        `;
    }

    if (metodo === 'Efectivo') {
        formulario.innerHTML = `
            <h3>Pago en efectivo</h3>
            <input class="campo-pago" type="text" placeholder="Monto entregado por el cliente">
        `;
    }
}

function pagarPedido() {
    if (carrito.length === 0) {
        alert('No tienes productos para pagar.');
        window.location.href = '/menu';
        return;
    }

    if (metodoPago === '') {
        alert('Selecciona un método de pago.');
        return;
    }

    const campos = document.querySelectorAll('#formularioPago input');

    for (let campo of campos) {
        if (campo.value.trim() === '') {
            alert('Completa todos los datos del método de pago.');
            campo.focus();
            return;
        }
    }

    window.location.href = '/confirmacion';
}

function mostrarConfirmacion() {
    const detalle = document.getElementById('detalleConfirmacion');
    const total = document.getElementById('totalConfirmacion');

    if (!detalle || !total) return;

    detalle.innerHTML = '';
    let suma = 0;

    carrito.forEach(item => {
        suma += item.precio;

        detalle.innerHTML += `
            <div class="item-carrito">
                <div class="carrito-info">
                    <img src="${item.imagen}" class="img-carrito" alt="${item.nombre}">
                    <span>${item.nombre}</span>
                </div>
                <b>S/ ${item.precio.toFixed(2)}</b>
            </div>
        `;
    });

    total.innerText = 'S/ ' + suma.toFixed(2);
}

function iniciarCuentaRegresiva() {
    const tiempoTexto = document.getElementById('tiempoRestante');
    const barra = document.getElementById('barraProgreso');

    if (!tiempoTexto || !barra) return;

    let tiempoTotal = 60;
    let tiempoRestante = tiempoTotal;

    tiempoTexto.innerText = '1:00';
    barra.style.width = '0%';

    const intervalo = setInterval(() => {
        tiempoRestante--;

        let minutos = Math.floor(tiempoRestante / 60);
        let segundos = tiempoRestante % 60;

        tiempoTexto.innerText = `${minutos}:${segundos < 10 ? '0' : ''}${segundos}`;

        let progreso = ((tiempoTotal - tiempoRestante) / tiempoTotal) * 100;
        barra.style.width = progreso + '%';

        if (tiempoRestante <= 0) {
            clearInterval(intervalo);
            tiempoTexto.innerText = '0:00';
            barra.style.width = '100%';

            setTimeout(() => {
                window.location.href = '/pedido-listo';
            }, 700);
        }
    }, 1000);
}

mostrarProductos();
mostrarCarrito();
mostrarResumenPago();
mostrarConfirmacion();
iniciarCuentaRegresiva();
