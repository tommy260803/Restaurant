<?php

use App\Http\Controllers\Actas\ProveedorController;

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PlatoController;

use Illuminate\Support\Facades\Route;
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
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/identificacion', [LoginController::class, 'verificalogin'])->name('identificacion');

Route::get('/redirigir', [BuscarActaController::class, 'showRedirectLogin'])->name('redirectLogin');

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

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

    // PERFIL (accesible para ambos)
    Route::resource('usuarios', UsuarioController::class);
    // Rutas adicionales para acciones personalizadas
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('{usuario}/perfil', [UsuarioController::class, 'perfil'])->name('perfil');
        Route::put('{usuario}/perfil', [UsuarioController::class, 'actualizarPerfil'])->name('perfil.update');


        Route::get('{usuario}/cuenta', [UsuarioController::class, 'cuenta'])->name('cuenta');
        Route::put('{usuario}/cuenta', [UsuarioController::class, 'actualizarCuenta'])->name('cuenta.update');

        Route::get('{usuario}/notificaciones', [UsuarioController::class, 'notificaciones'])->name('notificaciones');
        Route::put('{usuario}/notificaciones', [UsuarioController::class, 'actualizarNotificaciones'])->name('notificaciones.update');
    });

    // RUTAS SOLO PARA ADMINISTRADOR
    Route::middleware(['role:Administrador'])->group(function () {

        Route::resource('roles', RoleController::class);
        Route::resource('permisos', PermissionController::class)
            ->parameters(['permisos' => 'permiso'])
            ->middleware(['auth', 'role:Administrador']);
        Route::get('/usuarios/{usuario}/rol', [UsuarioRolController::class, 'edit'])->name('usuarios.rol.edit');
        Route::put('/usuarios/{usuario}/rol', [UsuarioRolController::class, 'update'])->name('usuarios.rol.update');
        Route::get('{usuario}/notificaciones', [UsuarioController::class, 'notificaciones'])->name('usuarios.notificaciones');

        // Usuarios
        
        Route::get('usuarios/{id}/confirmar', [UsuarioController::class, 'confirmar'])->name('confirmaru');
        Route::get('/cancelarusuario', fn() => redirect()->route('usuarios.index')->with('datos','Acción cancelada!!!'))->name('cancelaru');

        // Alcalde
        Route::resource('alcalde', AlcaldeController::class);
    });
    
    // RUTAS PARA ADMINISTRADOR Y REGISTRADOR
    Route::middleware(['role:Administrador|Registrador'])->group(function () {
        // Registrador
        Route::resource('proveedor', ProveedorController::class);

    // Métodos personalizados
    Route::post('proveedor/{id}/calificar', [ProveedorController::class, 'calificar'])->name('proveedor.calificar');
    Route::get('proveedor/{id}/historial', [ProveedorController::class, 'historialFinanciero'])->name('proveedor.historial');
    Route::get('proveedor/{id}/dashboard', [ProveedorController::class, 'dashboard'])->name('proveedor.dashboard');
    Route::get('proveedor/exportar/pdf', [ProveedorController::class, 'exportarPDF'])->name('proveedor.exportarPDF');
    Route::get('proveedor/exportar/excel', [ProveedorController::class, 'exportarExcel'])->name('proveedor.exportarExcel');       //         // Acta Nacimiento
        // Route::get('nacimiento/{id}/exportarPDF', [ActaNacimientoController::class, 'exportarPDF'])->name('nacimiento.exportarPDF');
        // Route::get('nacimiento/exportarPDFMasivo', [ActaNacimientoController::class, 'exportarPDFMasivo'])->name('nacimiento.exportarPDFMasivo');
        // Route::get('nacimiento/imprimirActa/{id}', [ActaNacimientoController::class, 'imprimirActa'])->name('nacimiento.imprimirActa');
        // Route::post('nacimiento/folios/crear', [ActaNacimientoController::class, 'crearFolio'])->name('nacimiento.folios.crear');
        // Route::get('nacimiento/folios/siguiente-numero/{libroId}', [ActaNacimientoController::class, 'getSiguienteNumeroFolio'])->name('nacimiento.folios.siguiente-numero');
        // Route::get('nacimiento/{id}/confirmar', [ActaNacimientoController::class, 'confirmar'])->name('nacimiento.confirmar');
        // Route::put('nacimiento/anular/{id}', [ActaNacimientoController::class, 'anular'])->name('nacimiento.anular');
        // Route::resource('nacimiento', ActaNacimientoController::class);
        
        // // Recien nacido
        // Route::resource('recienNacido', RecienNacidoController::class);
        // Route::get('recienNacido/{id}/confirmar', [RecienNacidoController::class, 'confirmar'])->name('recienNacido.confirmar');
        // Route::get('/cancelarnacido', fn() => redirect()->route('recienNacido.index')->with('datos','Acción cancelada!!!'))->name('cancelarn');
        // Route::get('matrimonio/exportarPDFMasivo', [ActaMatrimonioController::class, 'exportarPDFMasivo'])->name('matrimonio.exportarPDFMasivo');
 
        // // Matrimonio
        // Route::resource('matrimonio',ActaMatrimonioController::class);
        // Route::get('matrimonio/exportar', [ActaMatrimonioController::class, 'exportarVista'])->name('matrimonio.exportar');
        // Route::get('matrimonio/{id}/exportarPDF', [ActaMatrimonioController::class, 'exportarPDF'])->name('matrimonio.exportarPDF');

        // // Matrimonio AJAX dinámico
        // Route::get('matrimonio/provincias/{id_region}', [ActaMatrimonioController::class, 'getProvincias'])->name('matrimonio.provincias');
        // Route::get('matrimonio/distritos/{id_provincia}', [ActaMatrimonioController::class, 'getDistritos'])->name('matrimonio.distritos');
        // Route::get('matrimonio/folios/{libroId}', [ActaMatrimonioController::class, 'getFolios'])->name('matrimonio.folios');
        // Route::post('matrimonio/folios/crear', [ActaMatrimonioController::class, 'crearFolio'])->name('matrimonio.folios.crear');
        // Route::get('matrimonio/folios/siguiente-numero/{libroId}', [ActaMatrimonioController::class, 'getSiguienteNumeroFolio'])->name('matrimonio.folios.siguiente-numero');
        // Route::get('matrimonio/buscar-personas', [ActaMatrimonioController::class, 'buscarPersonas'])->name('matrimonio.buscar-personas');

        // // Defunción
        // Route::resource('defuncion', ActaDefuncionController::class);
        // Route::get('actas/{acta}/pdf', [ActaDefuncionController::class, 'generatePDF'])->name('actas.pdf');

        // Persona
        Route::resource('persona', PersonaController::class);
        Route::get('persona/{id}/confirmar', [PersonaController::class, 'confirmar'])->name('confirmarp');
        Route::get('/cancelarpersona', fn() => redirect()->route('persona.index')->with('datos','Acción cancelada!!!'))->name('cancelarp');
        Route::get('/persona/consultar-dni/{dni}', [PersonaController::class, 'consultarDni'])->name('persona.consultarDni');

        // // Ver alcalde (solo vista)
        // Route::get('alcalde', [AlcaldeController::class, 'index'])->name('alcalde.index');

        // // Ubigeo
        // Route::get('/provincias/{id_region}', [UbigeoController::class, 'provincias']);
        // Route::get('/distritos/{id_provincia}', [UbigeoController::class, 'distritos']);

        // // Folios
        // Route::post('/folios/crear', [ActaDefuncionController::class, 'crearFolio'])->name('folios.crear');
        // Route::get('/folios/siguiente-numero/{libroId}', [ActaDefuncionController::class, 'getSiguienteNumeroFolio'])->name('folios.siguiente-numero');

        // Tarifas
        Route::resource('tarifas', TarifaController::class);
        Route::get('/cancelartarifas', fn() => redirect()->route('tarifas.index')->with('datos', 'Acción cancelada!!!'))->name('cancelart');
        Route::get('tarifas/{id}/confirmar', [TarifaController::class, 'confirmar'])->name('tarifas.confirmar');

    });

});
        // Pagos
        Route::get('/pagoActa/{id}', [PagoController::class, 'pagoActa'])->name('pagos.pagoActa');
        // Rutas de formularios
        Route::get('/registrarActa', [PagoController::class, 'registrarActa'])->name('pagos.registrarActa');
        Route::get('/buscarActa', [PagoController::class, 'buscarActa'])->name('pagos.buscarActa');
        // Ruta para tipo de acta
        Route::get('/tipoActa', function () {
            return view('pagos.tipoActa');
        })->name('pagos.tipoActa');
        // Rutas AJAX específicas
        Route::get('/pagos/actas/{tipo}', [PagoController::class, 'obtenerActasPorTipo'])->name('pagos.getActas');
        Route::get('/pagos/monto/{tipo}', [PagoController::class, 'obtenerMontoPorTipo'])->name('pagos.getMonto');
        Route::get('/pagos/confirmar/{id}', [PagoController::class, 'confirmar'])->name('pagos.confirmar');

        Route::post('/buscar/nacimiento', [PagoController::class, 'buscarActaNacimiento'])->name('buscar.nacimiento');
        Route::post('/buscar/matrimonio', [PagoController::class, 'buscarActaMatrimonio'])->name('buscar.matrimonio');
        Route::post('/buscar/defuncion', [PagoController::class, 'buscarActaDefuncion'])->name('buscar.defuncion');
        // Ruta para procesar pago
        Route::get('/pagos/reportes', [PagoController::class, 'reportes'])->name('pagos.reportes');

        // Paso 1: Guardar datos básicos (DNI, correo)
        Route::post('/pagos/datos', [PagoController::class, 'guardarDatos'])->name('pagos.datos');
        Route::get('/vistaConfirmarPago', [PagoController::class, 'vistaConfirmarPago'])->name('pagos.confirmarVista');
        Route::post('/confirmarPago', [PagoController::class, 'confirmarPagoFinal'])->name('pagos.confirmarPago');
        Route::resource('pagos', PagoController::class);
        Route::post('/pagos/procesar', [PagoController::class, 'procesarPago'])->name('pagos.proce  sarPago');
        // web.php
        Route::post('/pago-confirmado', [PagoController::class, 'confirmarPago'])->name('confirmarPago');

        Route::get('/pagos/validar/{id}', [PagoController::class, 'validarPago'])->name('pagos.validarPago');
        Route::patch('/pagos/{pago}', [PagoController::class, 'update'])->name('pagos.update');
        Route::post('/pago/confirmar', [PagoController::class, 'guardarPago'])->name('pago.guardar');
        Route::delete('/pagos/{pago}', [PagoController::class, 'destroy'])->name('pagos.destroy');

        // Tarifas
        Route::resource('tarifas', TarifaController::class);
        Route::get('/cancelartarifas', function () {
            return redirect()->route('tarifas.index')->with('datos', 'Acción cancelada!!!');
        })->name('cancelart');
        Route::get('tarifas/{id}/confirmar', [TarifaController::class, 'confirmar'])->name('tarifas.confirmar');

Route::prefix('mantenedor/proveedor')->group(function () {
    Route::get('/', [App\Http\Controllers\Actas\ProveedorController::class, 'index'])->name('mantenedor.proveedor.index'); // Listar
    Route::get('create', [App\Http\Controllers\Actas\ProveedorController::class, 'create'])->name('mantenedor.proveedor.create'); // Formulario crear
    Route::post('store', [App\Http\Controllers\Actas\ProveedorController::class, 'store'])->name('mantenedor.proveedor.store'); // Guardar nuevo
    Route::get('{id}/edit', [App\Http\Controllers\Actas\ProveedorController::class, 'edit'])->name('mantenedor.proveedor.edit'); // Formulario editar
    Route::put('{id}/update', [App\Http\Controllers\Actas\ProveedorController::class, 'update'])->name('mantenedor.proveedor.update'); // Actualizar
    Route::delete('{id}/destroy', [App\Http\Controllers\Actas\ProveedorController::class, 'destroy'])->name('mantenedor.proveedor.destroy'); // Eliminar (lógica)
    // Personalizadas
    Route::post('{id}/calificar', [App\Http\Controllers\Actas\ProveedorController::class, 'calificar'])->name('mantenedor.proveedor.calificar');
    Route::get('{id}/historial', [App\Http\Controllers\Actas\ProveedorController::class, 'historialFinanciero'])->name('mantenedor.proveedor.historial-financiero');
    Route::get('{id}/dashboard', [App\Http\Controllers\Actas\ProveedorController::class, 'dashboard'])->name('mantenedor.proveedor.dashboard');
    Route::get('exportar/pdf', [App\Http\Controllers\Actas\ProveedorController::class, 'exportarPDF'])->name('mantenedor.proveedor.exportarPDF');
    Route::get('exportar/excel', [App\Http\Controllers\Actas\ProveedorController::class, 'exportarExcel'])->name('mantenedor.proveedor.exportarExcel');
    // Si tienes eliminación de documentos:
    Route::delete('{id}/eliminar-documento', [App\Http\Controllers\Actas\ProveedorController::class, 'eliminarDocumento'])->name('mantenedor.proveedor.eliminar-documento');
});


// 📂 Rutas para Categorías
Route::prefix('mantenedor/categorias')->group(function () {
// Nota: El prefijo de nombre es 'mantenedor.categorias.'
    Route::get('/', [CategoriaController::class, 'index'])->name('mantenedor.categorias.index'); // Listar
    Route::get('create', [CategoriaController::class, 'create'])->name('mantenedor.categorias.create'); // Formulario crear
    Route::post('store', [CategoriaController::class, 'store'])->name('mantenedor.categorias.store'); // Guardar
    Route::get('{categoria}/edit', [CategoriaController::class, 'edit'])->name('mantenedor.categorias.edit'); // Editar
    Route::put('{categoria}/update', [CategoriaController::class, 'update'])->name('mantenedor.categorias.update'); // Actualizar
    Route::get('{categoria}/confirmar', [CategoriaController::class, 'confirmar'])->name('mantenedor.categorias.confirmar'); // Confirmar eliminación
    Route::delete('{categoria}/destroy', [CategoriaController::class, 'destroy'])->name('mantenedor.categorias.destroy'); // Eliminar
    Route::get('/cancelar', fn() => redirect()->route('mantenedor.categorias.index')
    ->with('datos','Acción cancelada!!!'))->name('mantenedor.categorias.cancelar');
});

// 📂 Rutas para Platos
Route::prefix('mantenedor/platos')->group(function () {
    Route::get('/', [PlatoController::class, 'index'])->name('mantenedor.platos.index'); // Listar
    Route::get('create', [PlatoController::class, 'create'])->name('mantenedor.platos.create'); // Formulario crear
    Route::post('store', [PlatoController::class, 'store'])->name('mantenedor.platos.store'); // Guardar
    Route::get('{id}/edit', [PlatoController::class, 'edit'])->name('mantenedor.platos.edit'); // Editar
    Route::put('{id}/update', [PlatoController::class, 'update'])->name('mantenedor.platos.update'); // Actualizar
    Route::get('{id}/confirmar', [PlatoController::class, 'confirmar'])->name('mantenedor.platos.confirmar'); // Confirmar eliminación
    Route::delete('{id}/destroy', [PlatoController::class, 'destroy'])->name('mantenedor.platos.destroy'); // Eliminar
    Route::get('/cancelar', fn() => redirect()->route('mantenedor.platos.index')
        ->with('datos','Acción cancelada!!!'))->name('mantenedor.platos.cancelar');
});

// RUTAS DE GESTIÓN DE COMPRAS E INVENTARIO
Route::middleware(['auth'])->group(function () {
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
