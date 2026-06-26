<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\AdminController;


// --- RUTAS DEL CLIENTE / KIOSCO ---
Route::get('/', function () {
    return view('inicio');
});

Route::post('/menu', function (Request $request) {
    session(['Nombre' => $request->Nombre]);
    return view('menu');
});

Route::get('/menu', function () {
    if (!session('Nombre')) {
        return redirect('/');
    }
    return view('menu');
});

Route::get('/pago', function () {
    if (!session('Nombre')) {
        return redirect('/');
    }
    return view('pago');
});

Route::get('/confirmacion', function () {
    if (!session('Nombre')) {
        return redirect('/');
    }
    return view('confirmacion');
});

Route::get('/pedido-listo', function () {
    return view('pedido_listo');
});

Route::post('/guardar-pedido', [PedidoController::class, 'guardar']);


// --- MÓDULO DE REPORTES (PROCESAMIENTO CON LARAVEL COLLECTIONS) ---
Route::get('/reportes', [PedidoController::class, 'index']);


// --- RUTAS DE ADMINISTRADOR ---
Route::get('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/login', [AdminController::class, 'acceder']);
Route::get('/admin/salir', [AdminController::class, 'salir']);
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');


// --- ESTRUCTURAS DE DATOS VISUALES / COLA FIFO ASÍNCRONA ---
// Vista limpia para la cola operativa (Renderiza mediante Fetch en JavaScript)
Route::get('/cola-pedidos', function () {
    return view('cola_pedidos');
});

// Vista gráfica para la demostración algorítmica del Árbol Binario BST
Route::get('/admin/arbol', [AdminController::class, 'verArbol'])->name('admin.arbol');

// 🛠️ VISTA GRÁFICA PARA EL MÓDULO ANALÍTICO DE HEAP SORT
Route::get('/admin/heapsort', function () {
    return view('admin.reporte_heap');
});


// --- 🌐 ENDPOINTS DE LAS APIS RESTful (RETORNAN JSON DESDE MONGODB) ---

// API 01: Buscador inteligente en el Dashboard por código o regex de cliente
Route::get('/api/v1/pedidos/buscar', function (Request $request) {
    $codigo = $request->query('codigo');
    $cliente = $request->query('cliente');

    if ($codigo) {
        $resultados = App\Models\Pedido::where('codigo_pedido', $codigo)->get();
    } elseif ($cliente) {
        $resultados = App\Models\Pedido::where('cliente', 'regex', new \MongoDB\BSON\Regex($cliente, 'i'))->get();
    } else {
        return response()->json(['error' => 'Falta el parámetro de búsqueda (codigo o cliente)'], 400);
    }

    return response()->json($resultados, 200);
});

// API 02: Alimenta el render de las conexiones vectoriales del Árbol Red-Black
Route::get('/api/v1/admin/pedidos-arbol', [AdminController::class, 'apiPedidosArbol']);

// API 03: Alimenta el refresco constante (cada 5s) de la cola secuencial FIFO de la cocina
Route::get('/api/v1/admin/cola-pedidos', [PedidoController::class, 'apiColaPedidos']);

// API 04: Ruta operativa para ejecutar el borrado lógico y limpiar paneles conservando el historial
Route::post('/admin/vaciar-tienda', [PedidoController::class, 'vaciarTienda']);

// API 05: Endpoint que procesa y ordena los pedidos en tiempo real usando el algoritmo Heap Sort
Route::get('/api/v1/admin/pedidos-heapsort', [PedidoController::class, 'apiHeapSortPedidos'])->name('admin.api.heapsort');