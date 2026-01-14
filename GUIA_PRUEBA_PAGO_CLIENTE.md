# Guía de Prueba: Sistema de Pago con Cliente

## ✅ Cambios Implementados

### 1. Migración ejecutada correctamente
```
✅ 2026_01_13_add_cliente_orden_to_pagos ................ DONE
```

### 2. Base de Datos
- **Tabla `pagos`** ahora tiene:
  - `cliente_id` (INT, NULLABLE)
  - `orden_id` (BIGINT UNSIGNED, NULLABLE)

### 3. Modelo `Pago`
```php
protected $fillable = [
    'cliente_id',      // Nuevo
    'orden_id',        // Nuevo
    'venta_id',
    'reserva_id',
    'metodo',
    'numero_operacion',
    'monto',
    'fecha',
    'estado',
];

// Nuevas relaciones
public function cliente() { ... }
public function orden() { ... }
public function reserva() { ... }
```

### 4. Controlador `OrdenController`
- ✅ Método `procesarPago()` agregado
- ✅ Método `buscarClientes()` agregado
- ✅ Imports: `Cliente`, `Pago`

### 5. Rutas
```
✅ POST  /ordenes/mesa/{mesa}/procesar-pago
✅ GET   /ordenes/buscar-clientes
```

### 6. Vistas
- ✅ Modal de pago: `resources/views/ordenes/partials/modal-pago.blade.php`
- ✅ Vista detalle actualizada: `resources/views/ordenes/detalle.blade.php`
- ✅ Botón "Cobrar" ahora abre el modal

---

## 🧪 Pasos para Probar

### Paso 1: Verificar Base de Datos
```sql
-- Verificar que los campos se crearon en pagos
DESCRIBE pagos;
-- Debe mostrar: cliente_id y orden_id
```

### Paso 2: Iniciar Orden
1. Ir a `/ordenes`
2. Seleccionar una mesa disponible
3. Hacer clic en "Abrir Mesa"
4. Agregar platos

### Paso 3: Procesar Pago
1. Hacer clic en botón "Cobrar"
2. **Debe abrirse modal** `#modalPago` con:
   - Total a pagar (ej: S/. 45.50)
   - Campo de búsqueda de cliente
   - Botones de método de pago
   - Campo de número de operación

### Paso 3.1: Crear cliente desde el modal (si no existe)
1. Si al buscar no aparecen resultados, presionar el botón "Crear cliente "<texto>"" o presionar "Nuevo".
2. Completar al menos *Nombre* y *Apellido Paterno*, opcionalmente teléfono y email.
3. Presionar "Guardar cliente". Al crear el cliente, se seleccionará automáticamente y se mostrará su información en el modal.
4. Continuar con el flujo de pago (seleccionar método, procesar).
### Paso 4: Buscar Cliente
1. En el modal, escribir en "Buscar cliente por nombre..."
2. **Debe aparecer lista** de clientes (máx 10)
3. Hacer clic en cliente para seleccionar
4. **Debe mostrar** datos del cliente:
   - Nombre completo
   - Teléfono
   - Email
   - Puntos

### Paso 5: Seleccionar Método de Pago
1. Seleccionar uno de:
   - ✅ Efectivo (no requiere operación)
   - ✅ Tarjeta (requiere operación)
   - ✅ Yape (requiere operación)
   - ✅ Plin (requiere operación)
   - ✅ Otros (requiere operación)
2. **Para digitales**: Debe aparecer campo "Número de Operación"

### Paso 6: Confirmar Pago
1. Hacer clic en "Procesar Pago"
2. **Debe procesar** y:
   - ✅ Marcar orden como "pagada"
   - ✅ Crear registro en tabla `pagos` con `cliente_id`
   - ✅ Liberar mesa
   - ✅ Redirigir a `/ordenes`

### Paso 7: Verificar Registro de Pago
```sql
-- Ver último pago creado
SELECT * FROM pagos ORDER BY id DESC LIMIT 1;

-- Debe mostrar:
- cliente_id: (ID del cliente seleccionado o NULL)
- orden_id: (ID de la orden pagada)
- metodo: (efectivo|tarjeta|yape|plin|otros)
- numero_operacion: (número si es digital)
- monto: (total de la orden)
- estado: 'confirmado'
```

---

## 🐛 Validaciones a Probar

### ✅ Cliente Opcional
1. Marcar "Venta sin cliente registrado"
2. Procesar pago sin seleccionar cliente
3. **Debe funcionar**: cliente_id será NULL

### ✅ Método de Pago Obligatorio
1. Dejar campo de búsqueda vacío/sin seleccionar
2. No seleccionar método
3. Hacer clic "Procesar Pago"
4. **Debe mostrar**: error de validación

### ✅ Operación Obligatoria para Digitales
1. Seleccionar "Tarjeta" (o Yape/Plin)
2. No llenar "Número de Operación"
3. Hacer clic "Procesar Pago"
4. **Debe mostrar**: alerta "Por favor ingresa el número de operación"

### ✅ Orden Válida
1. Si no hay platos en la orden
2. Hacer clic "Cobrar"
3. **Debe estar deshabilitado** el botón

---

## 📋 Datos Esperados en Tabla `pagos`

```
Columna              | Tipo              | Valor Ejemplo
---------------------|-------------------|------------------------
id                   | INT PK            | Auto-incremento
cliente_id           | INT               | 1 (o NULL si sin cliente)
orden_id             | BIGINT UNSIGNED   | 1
venta_id             | INT               | NULL (si viene de orden)
reserva_id           | BIGINT UNSIGNED   | NULL
metodo               | ENUM              | 'efectivo'/'tarjeta'/'yape'/'plin'/'otros'
numero_operacion     | VARCHAR(191)      | '12345678' (si digital)
monto                | DECIMAL(10,2)     | 45.50
fecha                | TIMESTAMP         | 2026-01-13 14:30:00
estado               | ENUM              | 'confirmado'
```

---

## 🔍 Troubleshooting

### Si el modal no aparece:
- [ ] Verificar que `modal-pago.blade.php` esté incluido en `detalle.blade.php`
- [ ] Revisar consola del navegador (F12 → Console)
- [ ] Verificar que Bootstrap esté cargado

### Si la búsqueda de clientes no funciona:
- [ ] Verificar ruta GET `/ordenes/buscar-clientes` existe
- [ ] Verificar que hay clientes en tabla `cliente` con `estado = 'activo'`
- [ ] Revisar Network tab (F12 → Network) cuando se busca

### Si el pago no se procesa:
- [ ] Verificar ruta POST `/ordenes/mesa/{mesa}/procesar-pago` existe
- [ ] Ver error en consola (F12 → Console)
- [ ] Ver Response en Network tab
- [ ] Verificar que orden existe y tiene platos

### Si cliente_id no se guarda:
- [ ] Verificar que cliente_id está en `$fillable` del modelo Pago
- [ ] Verificar que cliente existe en tabla `cliente`
- [ ] Verificar migración ejecutó correctamente: `php artisan migrate:status`

---

## ✨ Funcionalidad Completa

Una vez que todo funcione, el sistema permitirá:

1. **Capturar cliente al pagar** ✅
2. **Registrar método de pago** ✅
3. **Guardar número de operación** (para auditoría) ✅
4. **Relacionar pago con orden** ✅
5. **Permitir pagos sin cliente** (anónimos) ✅
6. **Validaciones de seguridad** ✅

---

**Fecha de Implementación**: 13 de Enero de 2026
**Estado**: ✅ Listo para Pruebas
