# FITCORE — Documentación técnica

**Estado:** descripción de la implementación revisada el 6 de noviembre de 2026  
**Propósito:** explicar la arquitectura, los flujos principales, los datos y las limitaciones conocidas sin confundir funciones implementadas con funcionalidades previstas.

## 1. Resumen

FITCORE es una aplicación web para configurar rutinas, registrar sesiones de entrenamiento y representar el progreso del usuario mediante XP y niveles.

La implementación está dividida entre:

- **Frontend:** Vue 3, TypeScript, Pinia y Vue Router, ubicado en `Frontend/`.
- **API:** endpoints PHP ejecutados por Apache. En el entorno revisado están en `C:\Apache24\htdocs\Backend`, fuera de este repositorio.
- **Base de datos:** PostgreSQL, que conserva usuarios, rutinas, planificaciones, sesiones y XP.

El sistema permite registrar usuarios, iniciar y verificar sesiones, crear rutinas y guardar entrenamientos. El avance automático de semanas, la comunidad persistida y algunas métricas del dashboard todavía no están implementados de extremo a extremo.

## 2. Arquitectura y recorrido de una solicitud

```text
Navegador
  Vue: vistas y componentes
  Pinia: estado compartido
  Servicios TypeScript: solicitudes HTTP y lectura de respuestas
        ↓ JSON / HTTP
Apache + PHP: validación, reglas y acceso a datos con PDO
        ↓ SQL
PostgreSQL: persistencia
```

Vue presenta la interfaz; Pinia mantiene estado que necesitan varias vistas; los servicios aíslan la comunicación HTTP. PHP valida la sesión y los datos, aplica las reglas del flujo y usa PDO para consultar la base. PostgreSQL conserva los datos entre solicitudes.

En desarrollo, Vite reenvía las solicitudes a `/Backend` a Apache. La configuración está en `Frontend/vite.config.ts`; el destino puede ajustarse con `VITE_BACKEND_ORIGIN`. El frontend también permite definir `VITE_API_BASE_URL` para cambiar la base de las rutas de API.

## 3. Organización del código

### Frontend

- `Frontend/src/main.ts`: crea la aplicación Vue y registra Pinia y el router.
- `Frontend/src/App.vue`: raíz de la aplicación; muestra la vista activa.
- `Frontend/src/router/index.ts`: declara las rutas y comprueba la autenticación para las páginas protegidas.
- `Frontend/src/views/`: páginas, entre ellas registro, login, perfil, rutinas y entrenamiento.
- `Frontend/src/components/`: formularios y elementos reutilizables de interfaz.
- `Frontend/src/stores/`: estado compartido de autenticación, rutina y otros datos de interfaz.
- `Frontend/src/services/`: solicitudes HTTP y almacenamiento local.
- `Frontend/src/DTO/`: tipos TypeScript para los datos intercambiados.
- `Frontend/src/assets/` y `Frontend/public/`: estilos e imágenes. Los recursos de `public/` se sirven directamente.
- `Frontend/vite.config.ts`: alias `@` y proxy de desarrollo.

### Backend

Los PHP descritos abajo se encuentran en `C:\Apache24\htdocs\Backend`, no en la raíz del repositorio. Antes de cambiar un endpoint, confirmar que se está editando la copia que Apache ejecuta.

- `registro.php`, `login.php`, `verificar_sesion.php` y `logout.php`: ciclo de autenticación.
- `GeneradorEntrenamiento.php` y `Generador_rutina.php`: consulta y creación de rutinas.
- `registrar_sesion.php`: persistencia de sesiones, series y XP.
- `perfil.php`: consulta de perfil por identificador.
- `db.php`: conexión PDO a PostgreSQL.
- `roles.php`: roles permitidos y rol predeterminado.

Los scripts `diagnostico.php` e `inspeccionar_constraints.php` son herramientas auxiliares; no son pasos del recorrido normal del usuario.

### Carpetas auxiliares

`database/migrations/` y `database/seeds/` están destinadas a migraciones y datos iniciales, pero actualmente contienen documentación y marcadores, no un flujo activo conectado a la API PHP. `packages/shared/` define roles y estados de suscripción para el monorepo, pero no se observó su uso en los flujos principales actuales. `scripts/ruby/` y `.github/workflows/` documentan automatizaciones previstas; no se encontraron scripts de mantenimiento ni pipelines activos. `teoria typescript/` y `archivos md/` contienen material de aprendizaje y planificación, no código que se ejecute en el navegador.

## 4. Flujos funcionales

### 4.1 Registro

1. `Formulario.vue` recoge nombre, email y contraseña y emite el evento `guardar`.
2. `RegistroView.vue` recibe los datos y llama a `ejecutarRegistro()` en `autenticar.service.ts`.
3. El servicio envía un `POST` JSON a `registro.php` y valida la respuesta.
4. `registro.php` comprueba los campos, busca un email duplicado y genera el hash de contraseña con `password_hash()`.
5. El usuario se inserta en `usuarios` mediante PDO. El backend devuelve una respuesta JSON.
6. La vista informa el resultado y, si el registro tuvo éxito, redirige a `/login`. El registro no inicia sesión automáticamente.

**Archivos principales:** `Frontend/src/components/Formulario.vue`, `Frontend/src/views/RegistroView.vue`, `Frontend/src/services/autenticar.service.ts`, `Frontend/src/DTO/Auth.DTO.ts` y backend `registro.php`, `db.php`, `roles.php`.

**Revisión necesaria:** la tabla `usuarios` utiliza identificadores UUID, pero `registro.php` convierte el ID devuelto a entero antes de responder. Alinear el tipo de la respuesta con el tipo real de la clave primaria.

### 4.2 Inicio, restauración y cierre de sesión

En el inicio de sesión, `FormLogin.vue` emite las credenciales; `LoginView.vue` llama al store de autenticación; el store ejecuta `ejecutarLogin()` y el servicio envía un `POST` a `login.php`. El backend verifica la contraseña con `password_verify()`, genera un token aleatorio, guarda su hash en `sesiones_auth` y devuelve el token y los datos del usuario. El store guarda el token y el usuario con `StorageService` y actualiza Pinia.

Antes de navegar, el guard de `router/index.ts` llama a `restaurarSesion()` si todavía no se ha comprobado el estado. El store lee el token de `localStorage` y llama a `verificar_sesion.php`. El backend calcula el hash del token y comprueba que la sesión exista, no haya expirado y no esté revocada. Si la verificación falla, el frontend limpia los datos locales.

Al cerrar sesión, el frontend solicita a `logout.php` que marque el token como revocado y luego elimina el token y los datos del usuario de `localStorage` y Pinia.

**Archivos principales:** `FormLogin.vue`, `LoginView.vue`, `auth.store.ts`, `autenticar.service.ts`, `StorageService.ts`, `EstadoSesion.ts`, `router/index.ts`; backend `login.php`, `verificar_sesion.php`, `logout.php`, `db.php`.

**Nota:** `StorageService` guarda el token en `localStorage`. El servicio de logout permite cerrar la sesión local aunque falle la revocación en el servidor; por lo tanto, el cierre visual no siempre confirma que el token remoto haya sido revocado.

### 4.3 Configuración y creación de rutina

`RutinaConfig.vue` permite elegir entre uno y seis días. La categoría se propone según la cantidad de días: Full Body para uno a tres, Upper / Lower para cuatro, Push / Pull / Legs para cinco y PPL × 2 para seis.

El store `rutina.store.ts` carga el catálogo mediante `Rutina_service.ts` y `GeneradorEntrenamiento.php`. Al confirmar, el servicio envía los días y la categoría a `Generador_rutina.php`. El backend verifica el token y el nivel, consulta ejercicios activos, aplica reglas de series/repeticiones y crea la rutina y sus relaciones en una transacción.

Los datos se guardan principalmente en `rutinas`, `rutina_ejercicios`, `dia_semanal`, `planificaciones_semanales`, `ejercicios_planificados` y `usuario_configuracion`. La planificación se crea inicialmente con `semana = 1`.

**Archivos principales:** `RutinaConfig.vue`, `rutina.store.ts`, `Rutina_service.ts`, `Auth.DTO.ts`, `EntrenamientoView.vue`; backend `GeneradorEntrenamiento.php`, `Generador_rutina.php`, `db.php`.

El generador es un conjunto de reglas fijas en PHP; no utiliza inteligencia artificial. La lista de ejercicios que devuelve la rutina no está diferenciada por día en la interfaz actual.

### 4.4 Realización y registro de una sesión

`Entrenamiento.vue` muestra una tarjeta por día y navega a `EntrenamientoTiempoReal.vue` con datos del día en la URL. La vista de sesión presenta los ejercicios, permite modificar repeticiones, marcar series y usar un cronómetro local.

Al finalizar, el frontend calcula la XP de la sesión y llama a `registrarSesion()` en `Rutina_service.ts`. El servicio envía los ejercicios y las series a `registrar_sesion.php`. El backend verifica el token, abre una transacción, obtiene o crea la planificación activa, guarda la sesión y las series, registra XP y actualiza el total y nivel del usuario. Si el guardado falla, revierte la transacción y devuelve un error.

Las tablas principales son `planificaciones_semanales`, `sesiones_entrenamiento`, `ejercicios_planificados`, `series_registro`, `xp_historial` y `usuarios`.

**Archivos principales:** `Entrenamiento.vue`, `EntrenamientoView.vue`, `EntrenamientoTiempoReal.vue`, `Rutina_service.ts`, `rutina.store.ts`, `auth.store.ts`; backend `registrar_sesion.php`.

**Límites conocidos:** la XP se calcula en parte en el frontend y el backend no reconstruye de forma independiente todo el cálculo. Además, la interfaz permite elegir domingo, pero el registro limita `numero_dia` a 1–6. El día de calendario seleccionado y el número ordinal de sesión también pueden diferir.

### 4.5 Perfil, dashboard e historial

`PerfilView.vue` usa datos del store y muestra información de XP/nivel. Hay un endpoint `perfil.php`, pero no se encontró una llamada a él desde el frontend; además, el endpoint no verifica token.

`DashboardView.vue` muestra publicaciones y permite interactuar con ellas localmente. No se encontró una API que persista publicaciones, comentarios o reacciones en PostgreSQL. Algunos valores de `BarraUsuario.vue` y `entrenamiento.store.ts`, como amigos, minutos o racha, son de presentación y no métricas consultadas al servidor.

El backend registra eventos en `xp_historial`, pero no se encontró un servicio/vista que consulte y muestre ese historial. El store `xp_historial.store.ts` existe, pero no está conectado a una API de historial en el flujo revisado.

## 5. Datos principales

- `usuarios`: cuenta, hash de contraseña, rol, XP total y nivel.
- `sesiones_auth`: hash del token, vencimiento y revocación de sesiones de acceso.
- `niveles`: catálogo de niveles y XP requerido.
- `ejercicios`: catálogo, categoría, actividad y dificultad.
- `rutinas` y `rutina_ejercicios`: definición de rutinas y ejercicios asociados con orden y objetivos.
- `dia_semanal`: días configurados por usuario y rutina.
- `usuario_configuracion`: frecuencia y tipo de entrenamiento.
- `planificaciones_semanales` y `ejercicios_planificados`: planificación semanal y detalle de ejercicios objetivo.
- `sesiones_entrenamiento` y `series_registro`: ejecución real de entrenamientos y detalle de series.
- `xp_historial`: movimientos de experiencia otorgada.

El archivo `archivos md/fitcore_schema.sql` es material de referencia, pero no coincide completamente con las tablas/columnas que consumen los PHP actuales. Usar como fuente de verdad la base PostgreSQL efectiva y comprobar las consultas del backend antes de ejecutar DDL o migraciones.

## 6. API actual

Las rutas siguientes se refieren a los PHP ubicados en `Backend/` bajo el `DocumentRoot` de Apache.

| Método | Endpoint | Función | Autenticación observada |
|---|---|---|---|
| `POST` | `registro.php` | Crear cuenta | No |
| `POST` | `login.php` | Validar credenciales y crear sesión | No |
| `POST` | `verificar_sesion.php` | Validar token | Sí |
| `POST` | `logout.php` | Revocar token | Token enviado por el cliente |
| `GET` | `GeneradorEntrenamiento.php` | Cargar catálogo y rutina | Sí |
| `POST` | `Generador_rutina.php` | Crear rutina y planificación inicial | Sí |
| `POST` | `registrar_sesion.php` | Guardar sesión, series y XP | Sí |
| `GET` | `perfil.php` | Consultar perfil por ID | No valida token en el código revisado |

Las respuestas de la API usan JSON con campos como `exito`, `mensaje` y, cuando corresponde, `datos`. Los códigos de éxito varían por endpoint; por ejemplo, el registro actual responde `200` y la creación de rutina responde `201`. No asumir que todos los endpoints siguen un mismo contrato HTTP sin revisar su implementación.

## 7. XP, niveles y semanas

`registrar_sesion.php` suma la XP recibida al total del usuario y calcula el nivel usando tramos de 1.000 XP, con límite de nivel 10. Los eventos de XP se insertan en `xp_historial`.

El diseño menciona un objetivo de 250 XP por semana y cuatro semanas para completar 1.000 XP. Sin embargo:

- La planificación inicial se crea en semana 1.
- No existe una transición completa que cierre la semana y active la siguiente.
- La interfaz muestra S1–S4, pero S1 está fijada visualmente.
- El progreso semanal se aproxima con el XP total módulo 250, no con la semana real guardada.
- La consulta de días completados no filtra por planificación/semana.
- El store y el perfil tienen umbrales visuales de XP que no coinciden plenamente con el cálculo del backend.

Por lo tanto, el avance semanal debe considerarse **pendiente**. La columna `semana` por sí sola no implementa el cambio de semana.

## 8. Configuración y validación

### Requisitos y ejecución local

Consultar `Frontend/package.json` para las versiones de Node/npm requeridas y los comandos disponibles. En el entorno actual:

1. Iniciar Apache y PostgreSQL.
2. Confirmar que el backend PHP esté bajo el `DocumentRoot` configurado como `Backend/`.
3. Configurar el archivo privado `Backend/.env` con los datos de conexión. No versionar ni copiar sus valores a esta documentación.
4. Desde `Frontend/`, instalar dependencias con `npm install` si aún no están instaladas.
5. Ejecutar `npm run dev` y abrir la URL informada por Vite.

`Frontend/vite.config.ts` reenvía `/Backend` a `http://127.0.0.1:80` por defecto. Si Apache escucha en otro origen, definir `VITE_BACKEND_ORIGIN`. La base de URL de servicios puede ajustarse con `VITE_API_BASE_URL`.

### Comprobaciones disponibles

- `npm run build` en `Frontend/` ejecuta la comprobación de tipos y la compilación.
- No se encontró un conjunto de pruebas automatizadas funcionales que justifique declarar cobertura completa.
- Validar manualmente los flujos de registro, login, restauración, cierre, creación de rutina y registro de sesión con una cuenta/base de desarrollo.

## 9. Correcciones prioritarias antes de producción

1. Validar autenticación y propiedad del recurso en todos los endpoints privados; revisar especialmente `perfil.php`.
2. Alinear el UUID de usuario con el tipo de respuesta de `registro.php`.
3. Definir e implementar el cierre y avance de semanas antes de mostrar S1–S4 como progreso real.
4. Unificar el significado de `numero_dia` y soportar coherentemente los días permitidos.
5. Validar la XP en el servidor y hacer coincidir los umbrales del perfil con los del backend.
6. Evitar exponer excepciones y detalles de base de datos en respuestas de producción.
7. Proteger el despliegue con HTTPS, configuración privada y CORS restringido a los orígenes necesarios.
8. Crear migraciones versionadas que reflejen la base real antes de modificar tablas.

## 10. Cambios realizados a esta documentación

Se eliminaron las secciones repetidas de arquitectura, implementación, proceso de desarrollo e instalación detallada. También se retiraron afirmaciones no verificadas sobre cobertura de pruebas, estado de MVP, nivel de producción, agenda persistente, cronómetro persistente y desbloqueos dinámicos.

Se corrigieron los nombres de tablas y endpoints para reflejar el código actual, se aclaró que el backend PHP está fuera del repositorio y se documentaron las diferencias conocidas entre la base real, el esquema de referencia y la interfaz.
