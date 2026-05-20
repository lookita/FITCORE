# database/migrations/

Carpeta de **migraciones de base de datos** de PostgreSQL.

## Que es una migracion

Una migracion es un archivo que describe un cambio en el esquema de la base de datos
(crear tabla, agregar columna, modificar tipo de dato, etc.). Se ejecutan en orden
cronologico y son versionables con git.

## Herramienta

Las migraciones se gestionaran con **TypeORM** desde el backend NestJS.

## Estructura esperada

```
migrations/
|-- 1700000001-CreateUsersTable.ts
|-- 1700000002-CreateSubscriptionsTable.ts
|-- 1700000003-CreateRoutinesTable.ts
|-- 1700000004-CreateExercisesTable.ts
|-- 1700000005-AddXpToUsers.ts
`-- 1700000006-CreateKanbanBoardTable.ts
```

## Como ejecutar migraciones

```bash
# Desde la raiz del monorepo:
npm run migration:run --workspace=@obsidiantfit/backend

# Revertir la ultima migracion:
npm run migration:revert --workspace=@obsidiantfit/backend

# Generar una nueva migracion:
npm run migration:generate --workspace=@obsidiantfit/backend -- --name=NombreDeLaMigracion
```

## Tablas planificadas

| Tabla | Descripcion |
|---|---|
| `users` | Usuarios del sistema (admin, trainer, user) |
| `subscriptions` | Planes y estado de suscripcion por usuario |
| `routines` | Rutinas de entrenamiento |
| `exercises` | Ejercicios dentro de cada rutina |
| `kanban_boards` | Tableros Kanban por usuario |
| `kanban_cards` | Tarjetas del Kanban (rutinas en flujo) |
| `xp_logs` | Registro de puntos de experiencia ganados |
| `payments` | Historial de pagos |
