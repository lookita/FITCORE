# scripts/ruby/

Scripts de automatizacion escritos en **Ruby** para tareas de soporte del SaaS.

## Proposito

Ruby se usa aqui para scripting y automatizacion, no para el servidor web. Es ideal para:

- Seeds de base de datos (datos de prueba)
- Backups automatizados
- Scripts de migracion de datos
- Reportes y exportaciones
- Tareas de mantenimiento programadas

## Estructura esperada

```
scripts/ruby/
|-- seeds/
|   |-- usuarios.rb          <- Generar usuarios de prueba
|   |-- rutinas.rb           <- Generar rutinas de ejemplo
|   `-- suscripciones.rb     <- Generar planes y suscripciones
|-- backups/
|   |-- backup_db.rb         <- Backup de PostgreSQL
|   `-- backup_files.rb      <- Backup de archivos
|-- reports/
|   `-- reporte_usuarios.rb  <- Exportar datos de usuarios
`-- utils/
    `-- helpers.rb           <- Funciones reutilizables
```

## Requisitos

```bash
ruby --version   # >= 3.0.0
gem install pg   # conector PostgreSQL
gem install dotenv
```

## Como ejecutar un script

```bash
cd scripts/ruby
ruby seeds/usuarios.rb
```

## Variables de entorno necesarias

Los scripts leen las mismas variables del `.env` del proyecto:
- `DATABASE_URL` — cadena de conexion a PostgreSQL
- `REDIS_URL` — conexion a Redis
