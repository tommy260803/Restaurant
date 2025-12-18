<?php

use App\Http\Controllers\Actas\ProveedorController;

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PlatoController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    PresentationController,
    LoginController,
    HomeController,
    RecoveryController,
    BuscarActaController,
    RecienNacidoController,
    RoleController,
    PermissionController,
};

use App\Http\Controllers\UsuarioRolController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ReservaController;

use App\Http\Controllers\Administracion\{
    RegistradorController,
    PersonaController,
    AlcaldeController,
    UsuarioController
};

use App\Http\Controllers\Actas\{
    ActaNacimientoController,
    ActaMatrimonioController,
    ActaDefuncionController
};

use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TarifaController;

// RUTAS PÚBLICAS
Route::get('/', [PresentationController::class, 'presentation'])->name('presentacion');
Route::get('/login', [UsuarioRolController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UsuarioRolController::class, 'login']);

// Reservas públicas
Route::get('/reservar', [ReservaController::class, 'create'])->name('reservas.create');
Route::post('/reservar', [ReservaController::class, 'store'])->name('reservas.store');
// Página pública de confirmación (mostrada al usuario después de crear la reserva)
Route::get('/reservas/{id}/confirmacion', [ReservaController::class, 'confirmacion'])->name('reservas.confirmacion');

// Acciones públicas relacionadas con una reserva: descargar comprobante, reenviar email, agregar a Calendar
Route::get('/reserva/{id}/pdf', [ReservaController::class, 'pdf'])->name('reserva.pdf');
Route::post('/reserva/{id}/reenviar-email', [ReservaController::class, 'reenviarEmail'])->name('reserva.reenviar-email');
// Fallback GET para evitar errores si se accede por enlace directo
Route::get('/reserva/{id}/reenviar-email', [ReservaController::class, 'reenviarEmail']);
Route::get('/reserva/{id}/google-calendar', [ReservaController::class, 'googleCalendar'])->name('reserva.google-calendar');

// Consultar mi reserva (público)
Route::get('/reservas/consultar', [ReservaController::class, 'consultarForm'])->name('reservas.consultar');
Route::post('/reservas/consultar', [ReservaController::class, 'consultarBuscar'])->name('reservas.consultar.buscar');

// Ruta temporal de debug
Route::get('/test-roles', function() {
    $user = Auth::user();
    if (!$user) return 'No hay usuario logueado';
    
    return [
        'usuario' => $user->nombre_usuario,
        'email' => $user->email_mi_acta,
        'roles' => $user->getRoleNames(),
        'tiene_rol_admin' => $user->hasRole('administrador') ? 'SI' : 'NO',
        'permisos' => $user->getAllPermissions()->pluck('name')
    ];
})->middleware('auth');


Route::get('/recovery1', [RecoveryController::class, 'step1'])->name('recovery.step1');
Route::get('/verificarMiActa', [RecoveryController::class, 'verifyMiActa'])->name('verificarMiActa');
Route::get('/recovery2', [RecoveryController::class, 'step2'])->name('recovery.step2');
Route::post('/recovery/send-code', [RecoveryController::class, 'sendCode'])->name('recovery.sendCode');
Route::get('/recovery/verify', [RecoveryController::class, 'step3'])->name('recovery.step3');
Route::post('/recovery/verify-code', [RecoveryController::class, 'verifyCode'])->name('recovery.verifyCode');
Route::get('/recovery/code', [RecoveryController::class, 'step4'])->name('recovery.step4');
Route::post('/recovery/change-code', [RecoveryController::class, 'changeCode'])->name('recovery.changeCode');

// RUTAS PROTEGIDAS POR LOGIN
Route::middleware(['auth'])->group(function () {

    // Logout (disponible para todos los autenticados)
    Route::post('/logout', [UsuarioRolController::class, 'logout'])->name('logout');

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ========================================
    // DASHBOARDS POR ROL
    // ========================================
    
    // Dashboard Administrador
    Route::middleware(['role:administrador'])->group(function () {
        Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    });

    // Dashboard Cocinero
    Route::middleware(['role:cocinero'])->group(function () {
        Route::get('/cocina/dashboard', [HomeController::class, 'cocinaDashboard'])->name('cocina.dashboard');
    });

    // Dashboard Almacenero
    Route::middleware(['role:almacenero'])->group(function () {
        Route::get('/almacen/dashboard', [HomeController::class, 'almacenDashboard'])->name('almacen.dashboard');
    });

    // Dashboard Cajero
    Route::middleware(['role:cajero'])->group(function () {
        Route::get('/caja/dashboard', [HomeController::class, 'cajaDashboard'])->name('caja.dashboard');
    });

    // ========================================
    // RUTAS PARA CAJERO Y ADMINISTRADOR - RESERVAS
    // ========================================
    Route::middleware(['role:cajero|administrador'])->prefix('reservas')->name('reservas.')->group(function () {
        Route::get('/', [ReservaController::class, 'index'])->name('index');
        Route::post('/{id}/confirmar', [ReservaController::class, 'confirmar'])->name('confirmar');
        Route::post('/{id}/asignar-mesa', [ReservaController::class, 'asignarMesa'])->name('asignar-mesa');
        Route::post('/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('cancelar');
        Route::post('/{id}/completar', [ReservaController::class, 'completar'])->name('completar');
    });

    // Pagos de Reservas (Cajero/Admin)
    Route::middleware(['role:cajero|administrador'])->group(function () {
        Route::get('/caja/pagos/reservas', [PagoController::class, 'reservasIndex'])->name('caja.pagos.reservas');
        Route::post('/caja/pagos/{id}/estado', [PagoController::class, 'actualizarEstado'])->name('caja.pagos.actualizar-estado');
    });

    // ==============================
    // COCINERO (Cocinero/Admin)
    Route::prefix('cocinero')->name('cocinero.')->middleware(['role:cocinero|administrador'])->group(function () {
        Route::get('/', [\App\Http\Controllers\CocineroController::class, 'index'])->name('index');
        Route::get('/pedidos', [\App\Http\Controllers\CocineroController::class, 'pedidosPendientes'])->name('pedidos');
        Route::get('/pedidos/{id}', [\App\Http\Controllers\CocineroController::class, 'detalle'])->name('pedidos.detalle');
        Route::post('/pedidos/{id}/preparar', [\App\Http\Controllers\CocineroController::class, 'marcarPreparacion'])->name('pedidos.preparar');
        Route::post('/pedidos/{id}/finalizar', [\App\Http\Controllers\CocineroController::class, 'marcarPreparado'])->name('pedidos.finalizar');
        Route::post('/pedidos/{id}/incidencia', [\App\Http\Controllers\CocineroController::class, 'registrarIncidencia'])->name('pedidos.incidencia');
        Route::get('/historial', [\App\Http\Controllers\CocineroController::class, 'historial'])->name('historial');
        // JSON (auto-actualización)
        Route::get('/api/stats', [\App\Http\Controllers\CocineroController::class, 'stats'])->name('api.stats');
        Route::get('/api/pedidos/{id}', [\App\Http\Controllers\CocineroController::class, 'detalleJson'])->name('api.pedido');
        Route::get('/api/pendientes', [\App\Http\Controllers\CocineroController::class, 'pendientesRecientes'])->name('api.pendientes');
    });

    // ========================================
    // PERFIL (accesible para todos los autenticados)

    // ========================================
    Route::resource('usuarios', UsuarioRolController::class);
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('{usuario}/perfil', [UsuarioController::class, 'perfil'])->name('perfil');        Route::put('{usuario}/perfil', [UsuarioController::class, 'actualizarPerfil'])->name('perfil.update');
        Route::get('{usuario}/cuenta', [UsuarioController::class, 'cuenta'])->name('cuenta');
        Route::put('{usuario}/cuenta', [UsuarioController::class, 'actualizarCuenta'])->name('cuenta.update');
        Route::get('{usuario}/notificaciones', [UsuarioController::class, 'notificaciones'])->name('notificaciones');
        Route::put('{usuario}/notificaciones', [UsuarioController::class, 'actualizarNotificaciones'])->name('notificaciones.update');
    });

    // ========================================
    // RUTAS SOLO PARA ADMINISTRADOR
    // ========================================
    Route::middleware(['role:administrador'])->group(function () {

        Route::resource('roles', RoleController::class);
        Route::resource('permisos', PermissionController::class)
            ->parameters(['permisos' => 'permiso']);
        Route::get('/usuarios/{usuario}/rol', [UsuarioRolController::class, 'edit'])->name('usuarios.rol.edit');
        Route::put('/usuarios/{usuario}/rol', [UsuarioRolController::class, 'update'])->name('usuarios.rol.update');
        Route::get('{usuario}/notificaciones', [UsuarioController::class, 'notificaciones'])->name('usuarios.notificaciones');

        // Usuarios
        Route::get('usuarios/{id}/confirmar', [UsuarioController::class, 'confirmar'])->name('confirmaru');
        Route::get('/cancelarusuario', fn() => redirect()->route('usuarios.index')->with('datos','Acción cancelada!!!'))->name('cancelaru');

        // Alcalde
        Route::resource('alcalde', AlcaldeController::class);

        // Mesas (administración)
        Route::resource('mesas', App\Http\Controllers\MesasController::class)->only(['index','create','store','edit','update','destroy']);
        Route::post('mesas/{mesa}/estado', [App\Http\Controllers\MesasController::class, 'cambiarEstado'])->name('mesas.cambiar-estado');
    });
    
    // ========================================
    // RUTAS PARA ADMINISTRADOR Y ALMACENERO
    // ========================================
    Route::middleware(['role:administrador|almacenero'])->group(function () {
    // ========================================
    // RUTAS PARA ADMINISTRADOR Y ALMACENERO
    // ========================================
    Route::middleware(['role:administrador|almacenero'])->group(function () {
        // Proveedores
        Route::resource('proveedor', ProveedorController::class);
        Route::post('proveedor/{id}/calificar', [ProveedorController::class, 'calificar'])->name('proveedor.calificar');
        Route::get('proveedor/{id}/historial', [ProveedorController::class, 'historialFinanciero'])->name('proveedor.historial');
        Route::get('proveedor/{id}/dashboard', [ProveedorController::class, 'dashboard'])->name('proveedor.dashboard');
        Route::get('proveedor/exportar/pdf', [ProveedorController::class, 'exportarPDF'])->name('proveedor.exportarPDF');
        Route::get('proveedor/exportar/excel', [ProveedorController::class, 'exportarExcel'])->name('proveedor.exportarExcel');

        // Persona
        Route::resource('persona', PersonaController::class);
        Route::get('persona/{id}/confirmar', [PersonaController::class, 'confirmar'])->name('confirmarp');
        Route::get('/cancelarpersona', fn() => redirect()->route('persona.index')->with('datos','Acción cancelada!!!'))->name('cancelarp');
        Route::get('/persona/consultar-dni/{dni}', [PersonaController::class, 'consultarDni'])->name('persona.consultarDni');

        // Tarifas
        Route::resource('tarifas', TarifaController::class);
        Route::get('/cancelartarifas', fn() => redirect()->route('tarifas.index')->with('datos', 'Acción cancelada!!!'))->name('cancelart');
        Route::get('tarifas/{id}/confirmar', [TarifaController::class, 'confirmar'])->name('tarifas.confirmar');

        // Compras
        Route::resource('compras', App\Http\Controllers\Compras\CompraController::class);
        Route::get('compras/{compra}/comprobante', [App\Http\Controllers\Compras\CompraController::class, 'comprobantePDF'])->name('compras.comprobantePDF');
        Route::post('compras/{compra}/recibir', [App\Http\Controllers\Compras\CompraController::class, 'recibir'])->name('compras.recibir');

        // Ingredientes
        Route::resource('ingredientes', App\Http\Controllers\Inventario\IngredienteController::class);
        Route::post('ingredientes/{ingrediente}/ajustar', [App\Http\Controllers\Inventario\IngredienteController::class, 'ajustarStock'])->name('ingredientes.ajustarStock');
        Route::get('ingredientes/bajos', [App\Http\Controllers\Inventario\IngredienteController::class, 'bajos'])->name('ingredientes.bajos');

        // Movimientos de inventario
        Route::resource('movimientos-inventario', App\Http\Controllers\Inventario\MovimientoInventarioController::class)->only(['index', 'show']);
        Route::post('movimientos-inventario', [App\Http\Controllers\Inventario\MovimientoInventarioController::class, 'store'])->name('movimientos-inventario.store');

        // Ingrediente Lotes
        Route::get('ingrediente/{ingrediente_id}/lotes', [App\Http\Controllers\Inventario\IngredienteLoteController::class, 'index'])->name('ingrediente_lotes.index');
        Route::get('ingrediente/{ingrediente_id}/lotes/create', [App\Http\Controllers\Inventario\IngredienteLoteController::class, 'create'])->name('ingrediente_lotes.create');
        Route::post('ingrediente/{ingrediente_id}/lotes', [App\Http\Controllers\Inventario\IngredienteLoteController::class, 'store'])->name('ingrediente_lotes.store');
        Route::get('ingrediente/lotes/{id}/edit', [App\Http\Controllers\Inventario\IngredienteLoteController::class, 'edit'])->name('ingrediente_lotes.edit');
        Route::put('ingrediente/lotes/{id}', [App\Http\Controllers\Inventario\IngredienteLoteController::class, 'update'])->name('ingrediente_lotes.update');
        Route::delete('ingrediente/lotes/{id}', [App\Http\Controllers\Inventario\IngredienteLoteController::class, 'destroy'])->name('ingrediente_lotes.destroy');
        Route::get('ingrediente/{ingrediente_id}/lotes/vencidos', [App\Http\Controllers\Inventario\IngredienteLoteController::class, 'vencidos'])->name('ingrediente_lotes.vencidos');

        // Almacenes
        Route::resource('almacenes', App\Http\Controllers\Inventario\AlmacenController::class);
        Route::post('almacenes/transferir-stock', [App\Http\Controllers\Inventario\AlmacenController::class, 'transferirStock'])->name('almacenes.transferirStock');
    });

    // ========================================
    // RUTAS PARA ADMINISTRADOR Y COCINERO
    // ========================================
    Route::middleware(['role:administrador|cocinero'])->group(function () {
        // Categorías
        Route::prefix('mantenedor/categorias')->group(function () {
            Route::get('/', [CategoriaController::class, 'index'])->name('mantenedor.categorias.index');
            Route::get('create', [CategoriaController::class, 'create'])->name('mantenedor.categorias.create');
            Route::post('store', [CategoriaController::class, 'store'])->name('mantenedor.categorias.store');
            Route::get('{categoria}/edit', [CategoriaController::class, 'edit'])->name('mantenedor.categorias.edit');
            Route::put('{categoria}/update', [CategoriaController::class, 'update'])->name('mantenedor.categorias.update');
            Route::get('{categoria}/confirmar', [CategoriaController::class, 'confirmar'])->name('mantenedor.categorias.confirmar');
            Route::delete('{categoria}/destroy', [CategoriaController::class, 'destroy'])->name('mantenedor.categorias.destroy');
            Route::get('/cancelar', fn() => redirect()->route('mantenedor.categorias.index')->with('datos','Acción cancelada!!!'))->name('mantenedor.categorias.cancelar');
        });

        // Platos
        Route::prefix('mantenedor/platos')->group(function () {
            Route::get('/', [PlatoController::class, 'index'])->name('mantenedor.platos.index');
            Route::get('create', [PlatoController::class, 'create'])->name('mantenedor.platos.create');
            Route::post('store', [PlatoController::class, 'store'])->name('mantenedor.platos.store');
            Route::get('{id}/edit', [PlatoController::class, 'edit'])->name('mantenedor.platos.edit');
            Route::put('{id}/update', [PlatoController::class, 'update'])->name('mantenedor.platos.update');
            Route::get('{id}/confirmar', [PlatoController::class, 'confirmar'])->name('mantenedor.platos.confirmar');
            Route::delete('{id}/destroy', [PlatoController::class, 'destroy'])->name('mantenedor.platos.destroy');
            Route::get('/cancelar', fn() => redirect()->route('mantenedor.platos.index')->with('datos','Acción cancelada!!!'))->name('mantenedor.platos.cancelar');
        });
    });

});

// ========================================
// RUTAS PÚBLICAS DE PAGOS (FUERA DE AUTH)
// ========================================
// Pagos
Route::get('/pagoActa/{id}', [PagoController::class, 'pagoActa'])->name('pagos.pagoActa');
Route::get('/registrarActa', [PagoController::class, 'registrarActa'])->name('pagos.registrarActa');
Route::get('/buscarActa', [PagoController::class, 'buscarActa'])->name('pagos.buscarActa');
Route::get('/tipoActa', function () {
    return view('pagos.tipoActa');
})->name('pagos.tipoActa');
Route::get('/pagos/actas/{tipo}', [PagoController::class, 'obtenerActasPorTipo'])->name('pagos.getActas');
Route::get('/pagos/monto/{tipo}', [PagoController::class, 'obtenerMontoPorTipo'])->name('pagos.getMonto');
Route::get('/pagos/confirmar/{id}', [PagoController::class, 'confirmar'])->name('pagos.confirmar');
Route::post('/buscar/nacimiento', [PagoController::class, 'buscarActaNacimiento'])->name('buscar.nacimiento');
Route::post('/buscar/matrimonio', [PagoController::class, 'buscarActaMatrimonio'])->name('buscar.matrimonio');
Route::post('/buscar/defuncion', [PagoController::class, 'buscarActaDefuncion'])->name('buscar.defuncion');
Route::get('/pagos/reportes', [PagoController::class, 'reportes'])->name('pagos.reportes');
Route::post('/pagos/datos', [PagoController::class, 'guardarDatos'])->name('pagos.datos');
Route::get('/vistaConfirmarPago', [PagoController::class, 'vistaConfirmarPago'])->name('pagos.confirmarVista');
Route::post('/confirmarPago', [PagoController::class, 'confirmarPagoFinal'])->name('pagos.confirmarPago');
Route::resource('pagos', PagoController::class);
Route::post('/pagos/procesar', [PagoController::class, 'procesarPago'])->name('pagos.procesarPago');
Route::post('/pago-confirmado', [PagoController::class, 'confirmarPago'])->name('confirmarPago');
Route::get('/pagos/validar/{id}', [PagoController::class, 'validarPago'])->name('pagos.validarPago');
Route::patch('/pagos/{pago}', [PagoController::class, 'update'])->name('pagos.update');
Route::post('/pago/confirmar', [PagoController::class, 'guardarPago'])->name('pago.guardar');
Route::delete('/pagos/{pago}', [PagoController::class, 'destroy'])->name('pagos.destroy');
});




// ============================================
// RUTAS PARA MÓDULO DE ÓRDENES
// Agregar estas rutas a tu archivo routes/web.php
// ============================================

use App\Http\Controllers\OrdenController;

Route::prefix('ordenes')->name('ordenes.')->group(function () {
    // Vista principal: Panel de mesas
    Route::get('/', [OrdenController::class, 'index'])->name('index');
    
    // Abrir mesa (cambiar estado a ocupada)
    Route::post('/mesa/{mesa}/abrir', [OrdenController::class, 'abrirMesa'])->name('abrir');
    
    // Ver detalle de orden de una mesa
    Route::get('/mesa/{mesa}', [OrdenController::class, 'verOrden'])->name('ver');
    
    // Agregar plato a la orden (via AJAX)
    Route::post('/mesa/{mesa}/agregar-plato', [OrdenController::class, 'agregarPlato'])->name('agregar_plato');
    
    // Actualizar cantidad de un plato (via AJAX)
    Route::post('/mesa/{mesa}/actualizar-cantidad', [OrdenController::class, 'actualizarCantidad'])->name('actualizar_cantidad');
    
    // Eliminar plato de la orden (via AJAX)
    Route::delete('/mesa/{mesa}/eliminar-plato/{plato}', [OrdenController::class, 'eliminarPlato'])->name('eliminar_plato');
    
    // Actualizar nota de un plato (via AJAX)
    Route::post('/mesa/{mesa}/actualizar-nota', [OrdenController::class, 'actualizarNota'])->name('actualizar_nota');
    
    // Procesar cobro y cerrar mesa
    Route::post('/mesa/{mesa}/cobrar', [OrdenController::class, 'cobrar'])->name('cobrar');
    
    // Obtener platos disponibles (para el modal - AJAX)
    Route::get('/platos-disponibles', [OrdenController::class, 'getPlatosDisponibles'])->name('platos_disponibles');
    
    // Volver a la vista anterior (NO cancela la orden, solo regresa)
    Route::get('/mesa/{mesa}/volver', [OrdenController::class, 'volver'])->name('volver');
    
    // Cancelar orden y liberar mesa (SÍ cancela la orden)
    Route::post('/mesa/{mesa}/cancelar', [OrdenController::class, 'cancelar'])->name('cancelar');
});