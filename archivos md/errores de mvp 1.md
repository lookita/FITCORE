# Errores corregidos del sistema — ObsidiantFit

> Registro completo de los 12 errores encontrados durante la revisión del sistema (Backend PHP + Frontend Vue/TypeScript).

---

## Error 1 — `error_log()` recibía un array en lugar de un string (PHP)

**Archivo:** `db.php`

**Código original:**
```php
error_log(
    [db_connection.error]
);
```

**Por qué era un error:**  
`[db_connection.error]` en PHP no es un string ni una constante: es sintaxis de acceso a un array con una clave que usa el operador `.` (concatenación), lo que genera un error de parse. Incluso si se escribía `["db_connection_error"]`, seguía siendo un array, y `error_log()` espera un `string` como primer argumento. Pasar un array provoca un `TypeError` en tiempo de ejecución con `strict_types=1`.

**Corrección:**  
```php
error_log("[db_connection_error] " . $e->getMessage());
```

---

## Error 2 — `declare(static_type= 1)` — directiva inválida (PHP)

**Archivo:** `db.php`

**Código original:**
```php
declare(static_type= 1);
```

**Por qué era un error:**  
La directiva correcta en PHP es `strict_types`, no `static_type`. PHP no reconoce `static_type` como directiva válida de `declare()` y simplemente la ignora — el archivo correría **sin** tipado estricto sin dar ningún aviso visible. Esto significa que todos los tipos declarados en las funciones (`PDO`, `string`, etc.) dejaban de validarse, anulando completamente la protección que `strict_types` ofrece.

**Corrección:**
```php
declare(strict_types=1);
```

---

## Error 3 — DSN de PDO con errores tipográficos (PHP)

**Archivo:** `db.php`

**Código original:**
```php
"ppgsql:host={$host}; port={$port}; dbname={$db}"
```

**Por qué era un error:**  
Dos problemas simultáneos:  
1. `ppgsql` en lugar de `pgsql` — el driver de PostgreSQL no existe con ese nombre, lo que provoca una `PDOException` inmediata al intentar conectar.  
2. Los espacios después de `;` en el DSN (`; port=`, `; dbname=`) son inválidos — el parser del DSN de PDO no los tolera y no reconoce los parámetros correctamente, lo que puede causar que la conexión use valores por defecto incorrectos.

**Corrección:**
```php
"pgsql:host={$host};port={$port};dbname={$db}"
```

---

## Error 4 — `PDO::ARRT_ERRMODE` — constante inexistente (PHP)

**Archivo:** `db.php`

**Código original:**
```php
PDO::ARRT_ERRMODE => PDO::ERRMODE_EXCEPTION,
```

**Por qué era un error:**  
`ARRT_ERRMODE` no existe como constante en la clase `PDO`. El nombre correcto es `ATTR_ERRMODE`. Al usar una constante indefinida, PHP lanzaría un error fatal con `strict_types=1`. Sin strict types, usaría `null` como clave del array de opciones, y el modo de error **nunca se configuraría** — los errores de la base de datos pasarían silenciosos en lugar de lanzar excepciones.

**Corrección:**
```php
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
```

---

## Error 5 — `else` innecesario tras un `return` (PHP)

**Archivo:** `db.php`

**Código original:**
```php
if ($pdo instanceof PDO) {
    return $pdo;
} else {
    // todo el código de conexión
}
```

**Por qué era un error:**  
No es un error que rompa la ejecución, pero sí un error de lógica y legibilidad. Cuando un bloque `if` termina con `return`, el flujo de ejecución ya salió de la función — el `else` es completamente redundante. Usar `else` en ese contexto agrega una indentación innecesaria a todo el cuerpo de la función y hace el código más difícil de leer. El patrón correcto se llama "early return" o "guard clause".

**Corrección:**
```php
if ($pdo instanceof PDO) {
    return $pdo;
}
// código de conexión, sin else
```

---

## Error 6 — Credenciales hardcodeadas en el código fuente (PHP)

**Archivo:** `db.php`

**Código original:**
```php
$host = 'localhost';
$db   = 'FITNESS';
$user = 'postgres';
$pass = 'unpactoparavivir';
$port = '5432';
```

**Por qué era un error:**  
Tener credenciales escritas directamente en el código fuente es un riesgo de seguridad grave. Si el repositorio se sube a GitHub, las credenciales quedan expuestas en el historial de commits de forma permanente — incluso si se borran después, Git guarda el historial. Además, si se necesita cambiar la contraseña o mover a otro servidor, hay que tocar el código fuente en lugar de solo una configuración externa.

**Corrección:**  
Se creó un archivo `.env` en el backend con las variables de entorno, y se leen desde PHP con `parse_ini_file()`. El `.env` se agrega al `.gitignore`.

```php
$env  = parse_ini_file(__DIR__ . '/.env');
$host = $env['DB_HOST'];
$pass = $env['DB_PASS'];
// ...
```

---

## Error 7 — Typo en el mensaje de excepción (PHP)

**Archivo:** `db.php`

**Código original:**
```php
throw new RuntimeException("No se pude establecer la conexion con la base de datos");
```

**Por qué era un error:**  
`"pude"` es un error ortográfico — el verbo correcto en ese contexto es `"pudo"`. Aunque no afecta la ejecución, sí aparece en logs y potencialmente en respuestas de error al frontend. Dificulta encontrar el mensaje buscando la frase correcta en logs, y da mala impresión si llega a ser visible.

**Corrección:**
```php
throw new RuntimeException("No se pudo establecer la conexion con la base de datos");
```

---

## Error 8 — Interface `Login` con campo incorrecto (TypeScript)

**Archivo:** `Auth.DTO.ts`

**Código original:**
```ts
export interface Login {
    nombre: string,
    password: string
}
```

**Por qué era un error:**  
El formulario de login captura `email` y `password`. El backend PHP también valida `$input['email']` y `$input['password']`. Sin embargo, el contrato TypeScript definía `nombre` en lugar de `email`. Esto significa que el objeto enviado al backend tenía la clave `nombre` en lugar de `email`. El PHP recibía `null` en `$input['email']` y respondía siempre con "Email y contraseña son requeridos", sin importar lo que el usuario escribiera. El login nunca podría funcionar.

**Corrección:**
```ts
export interface Login {
    email: string,
    password: string
}
```

---

## Error 9 — Descalce de nombres de propiedades en `EstadoSesion` (Vue/TypeScript)

**Archivos:** `FormLogin.vue`, `LoginView.vue`, `EstadoSesion.ts`

**Código original en el template:**
```html
<section v-if="!sesion.autenticado">
  <p>Bienvenido: {{ sesion.usuarioConectado }}</p>
```

**Por qué era un error:**  
La interface `EstadoSesion` y el objeto reactivo en `LoginView` usaban `Autenticado` y `UsuarioConectado` (con mayúscula inicial). Pero el template de `FormLogin.vue` accedía a `sesion.autenticado` y `sesion.usuarioConectado` (todo minúscula). En JavaScript y Vue, los nombres de propiedades son **case-sensitive**. El resultado: `sesion.autenticado` era siempre `undefined` (falsy), por lo que el formulario nunca desaparecía después del login, y el nombre del usuario nunca se mostraba en el panel de bienvenida.

**Corrección:**
```html
<section v-if="!sesion.Autenticado">
  <p>Bienvenido: {{ sesion.UsuarioConectado }}</p>
```

---

## Error 10 — URLs del servicio apuntando al puerto de Vite (TypeScript)

**Archivo:** `autenticar.service.ts`

**Código original:**
```ts
const url = 'http://localhost:5173/registro/Backend/login.php';
```

**Por qué era un error:**  
El puerto `5173` es el servidor de desarrollo de **Vite** (el frontend). Los archivos `.php` son procesados por **Apache**, que corre en el puerto `80`. Hacer un `fetch()` a `localhost:5173/Backend/login.php` significa pedirle a Vite que sirva un archivo PHP — lo que es imposible ya que Vite no ejecuta PHP. Todas las requests al backend fallaban con error de red o devolvían HTML de error de Vite en lugar de JSON, rompiendo el parseo de la respuesta.

**Corrección:**
```ts
const url = 'http://localhost:80/registro/Backend/login.php';
// O equivalente, sin puerto explícito:
// 'http://localhost/registro/Backend/login.php'
```

---

## Error 11 — Import sin uso en `Formulario.vue` (TypeScript/Vue)

**Archivo:** `Formulario.vue`

**Código original:**
```ts
import { obtenerTituloVista } from '@/DTO/usuario.dto';
import { PasoFormulario } from '@/DTO/RegistroUsuarioDTO';
```

**Por qué era un error:**  
Ambos imports (y la variable `titulo` que generaban) fueron quedando sin uso cuando el título dinámico se reemplazó por el string literal `"REGISTRARSE"` hardcodeado en el template. Un import sin uso no rompe la ejecución, pero genera advertencias de TypeScript/ESLint, puede aumentar el bundle size en algunos bundlers, y es código muerto que confunde — hace creer que `PasoFormulario` tiene algún efecto en el componente cuando en realidad no hace nada.

**Corrección:**  
Se eliminaron los imports sin uso.

---

## Error 12 — CORS bloqueado: `Failed to fetch` al conectar Frontend con Backend (PHP/Apache)

**Archivos afectados:** `autenticar.service.ts`, cualquier archivo PHP del backend que reciba requests del frontend.

**Síntoma:**
```
No se pudo conectar con el servidor. Error: Failed to fetch
```

**Por qué era un error:**  
El navegador bloqueaba las requests del frontend (corriendo en `http://localhost:5173` con Vite) hacia el backend (corriendo en `http://localhost:80` con Apache) por la política **CORS** (Cross-Origin Resource Sharing). Al tener diferente puerto, el navegador los considera orígenes distintos. Sin los headers `Access-Control-Allow-Origin` en las respuestas del backend, el navegador descarta la respuesta antes de que llegue a JavaScript, lanzando un `TypeError: Failed to fetch`. El error aparecía en consola del navegador como:
```
Access to fetch at 'http://localhost/...' from origin 'http://localhost:5173'
has been blocked by CORS policy: No 'Access-Control-Allow-Origin' header
is present on the requested resource.
```
Esto hacía que **todas** las llamadas al backend fallaran en el frontend con el mensaje genérico `"No se pudo conectar con el servidor"`, sin importar si el PHP funcionaba correctamente.

**Corrección:**  
Agregar los headers CORS al inicio de cada archivo PHP del backend (o en un archivo de configuración compartido):

```php
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responder el preflight de OPTIONS sin procesar lógica
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
```

> [!TIP]
> En producción, reemplazar `http://localhost:5173` por el dominio real del frontend. Nunca usar `*` si el backend maneja autenticación con cookies o tokens.

---

## Tabla resumen

| # | Archivo | Descripción del error | Gravedad |
|---|---------|----------------------|----------|
| 1 | `db.php` | `error_log()` recibía un array | 🔴 Fatal |
| 2 | `db.php` | Directiva `declare` inválida (`static_type`) | 🔴 Fatal |
| 3 | `db.php` | DSN de PDO con typos (`ppgsql`, espacios) | 🔴 Fatal |
| 4 | `db.php` | Constante PDO inexistente (`ARRT_ERRMODE`) | 🔴 Fatal |
| 5 | `db.php` | `else` innecesario tras `return` | 🟡 Calidad |
| 6 | `db.php` | Credenciales hardcodeadas en código | 🔴 Seguridad |
| 7 | `db.php` | Typo `"pude"` en mensaje de error | 🟢 Menor |
| 8 | `Auth.DTO.ts` | Interface `Login` usaba `nombre` en vez de `email` | 🔴 Fatal |
| 9 | `FormLogin.vue` | Case mismatch en propiedades de `EstadoSesion` | 🔴 Runtime |
| 10 | `autenticar.service.ts` | URLs apuntaban al puerto de Vite (5173) en vez de Apache (80) | 🔴 Fatal |
| 11 | `Formulario.vue` | Imports sin uso (`PasoFormulario`, `obtenerTituloVista`) | 🟢 Menor |
| 12 | Backend PHP (todos) | CORS bloqueado → `Failed to fetch` al conectar con el frontend | 🔴 Fatal |
