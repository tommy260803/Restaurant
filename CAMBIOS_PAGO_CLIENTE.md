# Implementación: Datos del Cliente al Pagar

## Cambios Realizados

### 1. **Base de Datos**
- ✅ Migración creada: `2026_01_13_add_cliente_orden_to_pagos.php`
- ✅ Campos agregados a tabla `pagos`:
  - `cliente_id` (INT, NULLABLE) - Relación con tabla `cliente`
  - `orden_id` (BIGINT UNSIGNED, NULLABLE) - Relación con tabla `ordenes`

### 2. **Modelo Pago** (`app/Models/Pago.php`)
- ✅ Agregados campos al `$fillable`:
  - `cliente_id`
  - `orden_id`
- ✅ Relaciones agregadas:
  - `cliente()` - Relación con modelo Cliente
  - `orden()` - Relación con modelo Orden
  - `reserva()` - Ya existía

### 3. **Controlador** (`app/Http/Controllers/OrdenController.php`)
- ✅ Imports agregados: `Cliente` y `Pago`
- ✅ Nuevo método `procesarPago()`:
  - Recibe datos del cliente via AJAX
  - Valida el cliente (opcional)
  - Crea registro de pago con cliente_id
  - Marca orden como pagada
  - Libera la mesa
  - Retorna JSON de respuesta
- ✅ Nuevo método `buscarClientes()`:
  - Búsqueda AJAX de clientes por nombre/teléfono/email
  - Retorna lista de 10 clientes máximo

### 4. **Rutas** (`routes/web.php`)
- ✅ Ruta POST: `/ordenes/mesa/{mesa}/procesar-pago` → `OrdenController@procesarPago`
- ✅ Ruta GET: `/ordenes/buscar-clientes` → `OrdenController@buscarClientes`

### 5. **Vista Modal de Pago** (`resources/views/ordenes/partials/modal-pago.blade.php`)
- ✅ Modal `#modalPago` con:
  - **Búsqueda de Cliente**:
    - Input de búsqueda con autocomplete
    - Botón para crear nuevo cliente (preparado)
    - Opción "Sin cliente registrado"
  - **Información del Cliente Seleccionado**:
    - Nombre completo
    - Teléfono
    - Email
    - Puntos acumulados
  - **Método de Pago**:
    - Botones de selección: Efectivo, Tarjeta, Yape, Plin, Otros
    - Campo de número de operación (aparece para pagos digitales)
  - **Total a Pagar** mostrado en el modal

### 6. **Vista Detalle de Orden** (`resources/views/ordenes/detalle.blade.php`)
- ✅ Botón "Cobrar" cambiado:
  - De formulario a botón simple
  - Ahora abre modal `#modalPago` con `abrirModalPago()`
  - Pasa mesa_id y total al modal
  - Símbolo de moneda actualizado (S/. en lugar de $)
- ✅ Incluido modal de pago: `@include('ordenes.partials.modal-pago')`

## Funcionalidad

### Flujo de Pago:
1. Usuario hace clic en botón "Cobrar"
2. Se abre modal con formulario de pago
3. Busca cliente por nombre/teléfono/email (opcional)
4. Selecciona método de pago
5. Ingresa número de operación (si aplica)
6. Hace clic en "Procesar Pago"
7. AJAX envía datos al servidor
8. Servidor:
   - Valida orden activa y platos
   - Crea registro de pago con cliente_id
   - Marca orden como pagada
   - Marca platos como entregados
   - Completa reserva si existe
   - Libera mesa
8. Modal se cierra
9. Redirecciona a panel de mesas

## Validaciones
- ✅ Cliente opcional (permite venta sin cliente registrado)
- ✅ Método de pago obligatorio
- ✅ Número de operación obligatorio para pagos digitales
- ✅ Monto validado
- ✅ Orden debe existir y tener platos
- ✅ Cliente debe existir en BD si se proporciona

## Campos de Pago Almacenados
```php
[
    'cliente_id'      => Opcional, relación con Cliente
    'orden_id'        => Obligatorio, relación con Orden
    'metodo'          => efectivo|tarjeta|yape|plin|otros
    'numero_operacion' => Opcional (requerido para digitales)
    'monto'           => Decimal(10,2)
    'fecha'           => Timestamp automático
    'estado'          => 'confirmado'
]
```

## Próximos Pasos (Opcionales)
- [ ] Crear modal para registrar cliente rápidamente en el pago
- [ ] Agregar descuentos/puntos al pagar
- [ ] Generar recibos/comprobantes
- [ ] Integración con Culqi para pagos con tarjeta
- [ ] Reporte de pagos con cliente
