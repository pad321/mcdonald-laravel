<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\AdminController;

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

Route::get('/reportes', [PedidoController::class, 'index']);

# Rutas de Administrador
Route::get('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/login', [AdminController::class, 'acceder']);
Route::get('/admin/salir', [AdminController::class, 'salir']);
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

# Demostración Algorítmica de Árboles Binarios
Route::get('/admin/arbol', [AdminController::class, 'verArbol'])->name('admin.arbol');

Route::post('/guardar-pedido', [PedidoController::class, 'guardar']);

# Rutas para la cola FIFO
Route::get('/cola-pedidos', function () {
    $pedidos = App\Models\Pedido::orderBy('fecha', 'asc')->get();

    return view('cola_pedidos', compact('pedidos'));
});

# 🌐 API RESTful: Buscador Inteligente conectado directamente a MongoDB
Route::get('/api/v1/pedidos/buscar', function (Request $request) {
    $codigo = $request->query('codigo');
    $cliente = $request->query('cliente');

    // Búsqueda exacta por código de pedido
    if ($codigo) {
        $resultados = App\Models\Pedido::where('codigo_pedido', $codigo)->get();
    } 
    // Búsqueda flexible por nombre de cliente (Filtro insensible a mayúsculas/minúsculas)
    elseif ($cliente) {
        $resultados = App\Models\Pedido::where('cliente', 'regex', new \MongoDB\BSON\Regex($cliente, 'i'))->get();
    } 
    // Si no envían parámetros, responde con código HTTP 400 (Bad Request)
    else {
        return response()->json(['error' => 'Falta el parámetro de búsqueda (codigo o cliente)'], 400);
    }

    return response()->json($resultados, 200);
});