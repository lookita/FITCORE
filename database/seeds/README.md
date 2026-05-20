# database/seeds/

Carpeta de **seeds** (datos iniciales y de prueba) para la base de datos.

## Que es un seed

Un seed es un script que inserta datos de ejemplo o datos iniciales necesarios
para que la aplicacion funcione. Se ejecutan DESPUES de las migraciones.

## Tipos de seeds

### Seeds de datos iniciales (siempre se ejecutan)
Datos que la app NECESITA para funcionar desde el primer deploy:
- Roles del sistema (admin, trainer, user)
- Planes de suscripcion (trial, basico, premium)
- Configuracion global

### Seeds de datos de prueba (solo en desarrollo)
Datos falsos para probar la app sin tener que cargar todo a mano:
- 50 usuarios de ejemplo
- Rutinas de muestra
- Historial de XP y pagos

## Estructura esperada

```
seeds/
|-- 01-roles.ts              <- Roles del sistema (inicial)
|-- 02-planes.ts             <- Planes de suscripcion (inicial)
|-- 03-usuarios-demo.ts      <- Usuarios de prueba (solo dev)
|-- 04-rutinas-demo.ts       <- Rutinas de ejemplo (solo dev)
`-- index.ts                 <- Ejecuta todos los seeds en orden
```

## Como ejecutar seeds

```bash
# Desde la raiz del monorepo:
npm run seed --workspace=@obsidiantfit/backend

# O con el script Ruby alternativo:
ruby scripts/ruby/seeds/usuarios.rb
```

## Notas importantes

- Los seeds de prueba NUNCA deben ejecutarse en produccion
- Usar variables de entorno para detectar el entorno: `NODE_ENV=development`
- Los seeds son idempotentes: pueden ejecutarse multiples veces sin duplicar datos
