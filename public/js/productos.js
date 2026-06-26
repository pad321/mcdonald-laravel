// public/js/productos.js

// 1. Base de datos modularizada de productos
const productosBase = [
    { id: 1, nombre: 'Big Mac', precio: 18.90, categoria: 'Hamburguesas', img: '/img/pedidos/bigmac.png' },
    { id: 2, nombre: 'Cuarto de Libra', precio: 21.90, categoria: 'Hamburguesas', img: '/img/pedidos/cuarto_de_libra.png' },
    { id: 3, nombre: 'McPollo', precio: 16.90, categoria: 'Hamburguesas', img: '/img/pedidos/mcpollo.png' },
    { id: 4, nombre: 'Combo Big Mac', precio: 25.90, categoria: 'McCombos', img: '/img/pedidos/mc.combobigmac.png' },
    { id: 5, nombre: 'Combo Cuarto de Libra', precio: 28.90, categoria: 'McCombos', img: '/img/pedidos/mc.combocuartodelibra.png' },
    { id: 6, nombre: 'Inka Cola Mediana', precio: 5.50, categoria: 'Bebidas', img: '/img/pedidos/inkamediana.png' },
    { id: 7, nombre: 'Coca Cola Mediana', precio: 5.50, categoria: 'Bebidas', img: '/img/pedidos/cocamediana.png' },
    { id: 8, nombre: 'McFlurry', precio: 8.90, categoria: 'Postres', img: '/img/pedidos/mcflurry.png' },
    { id: 9, nombre: 'Pie de Manzana', precio: 6.90, categoria: 'Postres', img: '/img/pedidos/piedemanzana.png' }
];

let categoriaActual = 'Todos';

// 2. Función global de Renderización con Lazy Loading
window.renderizarProductos = function(lista) {
    const contenedor = document.getElementById('contenedorProductos');
    const sinResultados = document.getElementById('sinResultados');
    if (!contenedor) return;
    
    contenedor.innerHTML = '';
    
    if (lista.length === 0) {
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

// 3. Sistema de Filtro por Categorías
window.filtrarCategoria = function(categoria, boton) {
    categoriaActual = categoria;
    
    document.querySelectorAll('.opcion').forEach(btn => btn.classList.remove('activa'));
    if (boton) boton.classList.add('activa');
    
    const filtrados = categoria === 'Todos' 
        ? productosBase 
        : productosBase.filter(p => p.categoria === categoria);
        
    renderizarProductos(filtrados);
    
    const buscador = document.getElementById('buscador');
    if (buscador) buscador.value = '';
};

// 4. Buscador Inteligente Integrado
window.buscarProducto = function() {
    const texto = document.getElementById('buscador').value.toLowerCase().trim();
    
    const filtrados = productosBase.filter(p => {
        const coincideCategoria = (categoriaActual === 'Todos' || p.categoria === categoriaActual);
        const coincideTexto = p.nombre.toLowerCase().includes(texto);
        return coincideCategoria && coincideTexto;
    });
    
    renderizarProductos(filtrados);
};

// 5. Carga inicial sincronizada con el DOM
document.addEventListener('DOMContentLoaded', () => {
    window.productos = productosBase;
    renderizarProductos(productosBase);
});