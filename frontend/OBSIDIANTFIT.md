# FITCORE

## 1. Introduccion y descripcion general

### 1.1. Nombre del programa

**FITCORE** es el nombre del proyecto de una aplicacion fitness orientada al entrenamiento de calistenia.

### 1.2. Que es FITCORE

FITCORE se plantea como una aplicacion fitness gamificada que acompana a las personas desde sus primeros entrenamientos de calistenia hasta niveles mas avanzados. La propuesta combina rutinas guiadas, progresion por fases, registro de avances y recompensas de experiencia (XP).

La vision del producto incluye juegos y una comunidad social. Las recompensas XP deberian reflejar el avance del usuario y ayudar a mantener la motivacion. Estas funciones describen el objetivo del producto; no todas estan implementadas en la version actual.

### 1.3. Objetivo

El proyecto esta pensado para personas que nunca han practicado calistenia y quieren empezar, pero encuentran barreras para acceder a entrenamiento personalizado o a gimnasios adecuados. La aplicacion busca ofrecer una alternativa digital con orientacion y progresion gradual.

### 1.4. Alcance de la version documentada

La version actual contiene una aplicacion web con rutas de inicio, login, registro y perfil, ademas de formularios conectados a una API PHP. El registro de usuario se conecta a PostgreSQL y ya se comprobo creando una cuenta.

La progresion fitness, las rutinas funcionales, las recompensas XP, los juegos y la comunidad son objetivos del producto que deben completarse e integrarse posteriormente.

## 2. Especificacion de requerimientos (SRS)

### 2.1. Requerimientos funcionales actuales

- **RF-01:** Mostrar las vistas de inicio, login, registro y perfil mediante rutas de la aplicacion.
- **RF-02:** Capturar nombre, email y contrasena en el formulario de registro.
- **RF-03:** Capturar email y contrasena en el formulario de login.
- **RF-04:** Enviar los datos al backend mediante solicitudes HTTP `POST` con cuerpo JSON.
- **RF-05:** Validar que existan los campos requeridos para el registro y el login.
- **RF-06:** Comprobar si el email ya existe antes de insertar una cuenta.
- **RF-07:** Guardar la contrasena como hash con `password_hash()` y BCrypt, no como texto plano.
- **RF-08:** Verificar credenciales en el endpoint de login usando `password_verify()`.
- **RF-09:** Responder desde el backend con datos JSON y codigos HTTP apropiados.
- **RF-10:** Mostrar un mensaje de resultado del registro en la interfaz.

### 2.2. Requerimientos funcionales pendientes o planificados

- **RF-11:** Mantener la identidad del usuario en una store global compartida por las vistas.
- **RF-12:** Redirigir al perfil despues de un login correcto y controlar el acceso a esa ruta.
- **RF-13:** Implementar el cierre de sesion conectado al estado global.
- **RF-14:** Completar rutinas guiadas y registrar el progreso del entrenamiento.
- **RF-15:** Implementar XP, fases de progresion, juegos y funciones sociales.

### 2.3. Requerimientos no funcionales

- **RNF-01, Seguridad:** Las contrasenas se almacenan como hash; el trafico de produccion debe usar HTTPS.
- **RNF-02, Seguridad:** Las credenciales de PostgreSQL no deben quedar en codigo publicado. La configuracion actual debe migrarse a variables de entorno o a un archivo privado excluido del control de versiones.
- **RNF-03, Seguridad:** En produccion, los errores detallados de SQL/PHP deben ir a logs del servidor y no exponerse al navegador.
- **RNF-04, Interoperabilidad:** El frontend y el backend intercambian JSON y habilitan CORS para el origen de desarrollo configurado.
- **RNF-05, Mantenibilidad:** La interfaz, contratos de datos, servicios, rutas y endpoints estan separados en archivos distintos.
- **RNF-06, Calidad:** El proyecto debe pasar las comprobaciones TypeScript, el build frontend y la validacion de sintaxis PHP antes de publicarse.

## 3. Arquitectura del sistema

### 3.1. Componentes

```text
Navegador
  Vue 3 + TypeScript + Vite
  Views -> Componentes de formulario -> Servicio de autenticacion
                              |
                              | HTTP POST + JSON / CORS
                              v
Apache + PHP
  registro.php / login.php -> db.php (PDO)
                              |
                              | PostgreSQL, puerto 5432
                              v
                         Base FITNESS
```

### 3.2. Frontend

- **Vue 3:** componentes de interfaz usando Composition API y `<script setup>`.
- **TypeScript:** tipos para formularios, usuarios, respuestas y datos de sesion.
- **Vite:** servidor de desarrollo y empaquetador.
- **Vue Router 4:** rutas `/`, `/login`, `/registro` y `/perfil`.
- **Bootstrap Icons:** paquete de iconos incluido en las dependencias.
- **Punto de entrada:** `src/main.ts` crea la aplicacion, registra el router y monta `App.vue`.
- **Estilos globales:** `src/assets/main.css`; los componentes tambien tienen estilos locales.

### 3.3. Backend

- **Apache HTTP Server:** publica los archivos PHP desde `htdocs`.
- **PHP:** procesa las solicitudes de autenticacion y crea respuestas JSON.
- **PDO PostgreSQL (`pdo_pgsql`):** acceso parametrizado a la base de datos.
- **Configuracion:** `Backend/db.php` centraliza host, nombre de base, usuario, puerto y conexion PDO.

### 3.4. Flujo del formulario de registro

1. El usuario completa los campos en `src/components/Formulario.vue`.
2. El componente emite el evento `guardar` con nombre, email y contrasena.
3. `src/views/RegistroView.vue` recibe los datos y llama a `ejecutarRegistro()`.
4. `src/services/auth.services.ts` valida URL y payload y envia un `POST` JSON a `http://localhost/Backend/registro.php`.
5. `Backend/registro.php` valida los campos, busca un email duplicado, genera el hash de la contrasena e inserta el registro.
6. `Backend/db.php` abre la conexion PDO con PostgreSQL.
7. El backend devuelve JSON con el resultado y la interfaz informa si la cuenta fue creada o si hubo un error.

### 3.5. Flujo del formulario de login

1. `src/components/FormLogin.vue` captura email y contrasena y emite `guardar`.
2. `src/views/LoginView.vue` recibe el evento y llama a `ejecutarLogin()`.
3. `src/services/auth.services.ts` envia un `POST` JSON a `http://localhost/Backend/login.php`.
4. `Backend/login.php` busca el usuario y comprueba el hash de contrasena.
5. El backend responde con el resultado en JSON.

El estado de login actual se mantiene en la vista; todavia no hay una store global ni redireccion automatica al perfil al completar el login.

### 3.6. Estructura relevante

```text
Frontend/
  src/
    components/       Formularios, cabeceras y componentes de interfaz
    composables/      Reservado para logica reutilizable; useAuth aun pendiente
    DTO/              Contratos de datos, sesion y utilidades de vistas
    router/           Configuracion de rutas Vue Router
    services/         Peticiones HTTP y validadores
    stores/           Reservado para estado global; auth.store aun pendiente
    views/            Inicio, login, registro y perfil
  public/             Recursos publicos
  package.json        Dependencias y comandos npm

Backend/
  db.php              Conexion PDO a PostgreSQL
  login.php           Endpoint de autenticacion
  registro.php        Endpoint de alta de usuarios
```

## 4. Diseno de base de datos

### 4.1. Configuracion observada

- **Motor:** PostgreSQL.
- **Base configurada:** `FITNESS`.
- **Host y puerto:** `localhost:5432`.
- **Tabla utilizada:** `usuarios`.

### 4.2. Campos que espera la aplicacion

| Campo | Uso observado | Requisito recomendado |
|---|---|---|
| `id` | Identificador devuelto durante el registro y el login | Clave primaria generada por la base |
| `nombre` | Nombre mostrado y devuelto al frontend | Texto no nulo |
| `email` | Busqueda y prevencion de cuentas duplicadas | Texto no nulo y restriccion `UNIQUE` |
| `password_hash` | Hash para verificar la contrasena | Texto no nulo; nunca almacenar la contrasena original |
| `rol` | El endpoint de login lo consulta | Definir valor por defecto o ajustar consulta si no se utiliza |

La definicion real de la tabla no forma parte de los archivos PHP revisados. Debe contrastarse con el esquema instalado en PostgreSQL antes de preparar un despliegue.

### 4.3. Relacion de datos prevista

```text
usuarios
  id (PK)
  nombre
  email (UNIQUE)
  password_hash
  rol
```

## 5. Documentacion de APIs y servicios

### 5.1. Registro

- **URL local:** `http://localhost/Backend/registro.php`
- **Metodo:** `POST`
- **Content-Type:** `application/json`
- **Solicitud:**

```json
{
  "nombre": "Nombre de usuario",
  "email": "usuario@example.com",
  "password": "contrasena"
}
```

- **Exito:** HTTP `200`, `exito: true` y datos del usuario creado.
- **Email duplicado:** HTTP `409`.
- **Campos ausentes:** HTTP `400`.
- **Error de base de datos:** HTTP `500`.

### 5.2. Login

- **URL local:** `http://localhost/Backend/login.php`
- **Metodo:** `POST`
- **Content-Type:** `application/json`
- **Solicitud:**

```json
{
  "email": "usuario@example.com",
  "password": "contrasena"
}
```

- **Exito:** HTTP `200`, `exito: true` y datos del usuario.
- **Credenciales incorrectas:** HTTP `401`.
- **Campos ausentes:** HTTP `400`.
- **Error de consulta/base de datos:** HTTP `500`.

### 5.3. Forma de respuesta

```json
{
  "exito": true,
  "mensaje": "Operacion completada",
  "datos": {
    "id": 1,
    "nombre": "Nombre de usuario",
    "email": "usuario@example.com"
  }
}
```

### 5.4. Servicio frontend

`src/services/auth.services.ts` exporta `ejecutarRegistro()` y `ejecutarLogin()`. El helper `leerRespuesta()` intenta decodificar JSON y reporta respuestas vacias o invalidas. `src/services/paso.service.ts` contiene validadores; algunas funciones, como `validarEmail()`, `validarContra()` y `validarCodigo()`, aun no estan conectadas a todos los flujos.

## 6. Guia de instalacion y configuracion

### 6.1. Requisitos

- Windows u otro sistema compatible con Node.js, Apache, PHP y PostgreSQL.
- Node.js compatible con la version indicada en `package.json` (`^22.18.0` o `>=24.12.0`).
- npm.
- PHP con extensiones `PDO` y `pdo_pgsql` habilitadas.
- Apache configurado para ejecutar archivos PHP.
- PostgreSQL iniciado y escuchando en el puerto `5432`.

### 6.2. Frontend

Desde la carpeta `Frontend`:

```bash
npm install
npm run dev
```

La aplicacion Vite suele estar disponible en `http://localhost:5173`.

Vue Router ya aparece en `package.json` como dependencia. Para agregarlo manualmente en una instalacion nueva de Vue 3 se utiliza:

```bash
npm install vue-router@4
```

### 6.3. Backend

1. Instalar Apache y PHP.
2. Configurar Apache para cargar PHP y ejecutar archivos `.php`.
3. Verificar que el directorio `C:\Apache24\htdocs\Backend` este servido por Apache.
4. Habilitar `pdo_pgsql` en la configuracion PHP utilizada por Apache.
5. Iniciar PostgreSQL y crear/configurar la base `FITNESS` y la tabla `usuarios`.
6. Configurar en `Backend/db.php` el host, puerto, base, usuario y contrasena correctos.
7. Mantener el origen CORS de desarrollo en concordancia con el servidor frontend (`http://localhost:5173`).

No guardar contrasenas reales en este documento ni en el repositorio. Para produccion, usar variables de entorno o configuracion privada del servidor.

### 6.4. Estructura y herramientas

El frontend y backend estan en carpetas distintas (`Frontend` y `Backend`), aunque pueden trabajarse dentro del mismo workspace. El frontend esta organizado como proyecto npm TypeScript/Vue; no es necesario describirlo como monorepo de TypeScript si no se comparte una raiz npm con paquetes configurados.

## 7. Pruebas y control de calidad

### 7.1. Comprobaciones realizadas

- Se probo el registro desde el formulario y se obtuvo el mensaje de cuenta creada correctamente.
- Se comprobo que PostgreSQL escucha en `5432` y se corrigieron las credenciales utilizadas por la conexion.
- `php -l` se ejecuto sobre los endpoints de autenticacion y no reporto errores de sintaxis.
- Se ejecuto `npm run type-check`; reporto errores en componentes de la aplicacion que deben corregirse.
- `npm run build` no finalizo correctamente: Vite no puede resolver una ruta de imagen incompleta en `src/components/Main.vue`.

### 7.2. Casos de prueba recomendados

| ID | Caso | Resultado esperado |
|---|---|---|
| T-01 | Registrar usuario con datos validos | HTTP 200 y cuenta creada |
| T-02 | Registrar un email existente | HTTP 409 y mensaje de duplicado |
| T-03 | Enviar registro sin campos requeridos | HTTP 400 y mensaje de validacion |
| T-04 | Iniciar sesion con credenciales correctas | HTTP 200 y datos del usuario |
| T-05 | Iniciar sesion con contrasena incorrecta | HTTP 401 |
| T-06 | Solicitar autenticacion con PostgreSQL detenido | HTTP 500 con respuesta JSON de error |
| T-07 | Navegar directamente a `/login`, `/registro` y `/perfil` | Cada ruta muestra su vista correspondiente |
| T-08 | Compilar frontend | `npm run type-check` y `npm run build` terminan sin errores |

## 8. Mantenimiento y despliegue

### 8.1. Antes de publicar

- Corregir los errores TypeScript y de compilacion actuales.
- Cambiar las URL locales por la URL del backend de produccion.
- Configurar CORS para el dominio real del frontend.
- Usar HTTPS.
- Sacar las credenciales de PostgreSQL del codigo.
- Desactivar la exposicion de errores internos de PHP/SQL al cliente y registrar esos detalles en el servidor.
- Verificar esquema, permisos y copias de seguridad de PostgreSQL.
- Implementar gestion global de sesion y proteccion de rutas si el perfil requiere autenticacion.

### 8.2. Despliegue

- Construir el frontend con `npm run build` y publicar el contenido generado por Vite en el servidor web.
- Publicar los endpoints PHP en un servidor Apache configurado con PHP y el driver `pdo_pgsql`.
- Configurar la conexion a PostgreSQL mediante secretos del entorno de despliegue.
- Configurar las rutas de Vue Router para que el servidor web entregue la aplicacion frontend al acceder a rutas como `/login` o `/perfil`.

### 8.3. Registro de cambios de esta version

- Se conecto el formulario de registro con el servicio frontend y el endpoint PHP.
- Se conecto el backend a PostgreSQL mediante PDO en el puerto `5432`.
- Se incorporo el almacenamiento de contrasenas como hash y la comprobacion de credenciales mediante `password_verify()`.
- Se mejoro el manejo de respuestas JSON para comunicar errores de conexion y respuestas invalidas.
- El estado global de autenticacion, la redireccion automatica al perfil y las funciones completas de gamificacion quedan como trabajo futuro.
