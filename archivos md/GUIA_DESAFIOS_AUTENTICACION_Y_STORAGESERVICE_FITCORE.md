# 🥋 Guía Maestra de Desafíos: Persistencia, Autenticación y Sesión Real en FITCORE

Esta guía está diseñada a la medida de tu código actual en **FITCORE** para que construyas y conectes **tu propio sistema de persistencia local, autenticación con PHP y control de sesiones** pensando cada paso por tu cuenta, entendiendo los flujos de datos y la responsabilidad de cada archivo.

Aquí **no hay soluciones servidas**: hay un diagnóstico real de tus archivos, pistas lógicas, preguntas disparadoras, rutas exactas y criterios de éxito para que seas vos quien escriba, importe, exporte y razone cada pieza.

---

## 🔍 1. Diagnóstico de tu Sistema Actual (Tus Archivos Reales)

En tu proyecto ya tenés construido cerca del 70% del camino visual y de conexión:
- ✅ **Formulario:** `Frontend/src/components/FormLogin.vue` emite `@guardar` con email y contraseña.
- ✅ **Vista:** `Frontend/src/views/LoginView.vue` captura los datos y llama al servicio.
- ✅ **Servicio HTTP:** `Frontend/src/services/autenticar.service.ts` hace el `fetch()` a `http://localhost/Backend/login.php`.
- ✅ **Almacén:** `Frontend/src/services/StorageService.ts` tiene los métodos estáticos para `localStorage`.

### ⚠️ Las 3 Fugas Críticas que te impiden tener sesión persistente hoy:

1. **Fuga 1: La sesión "muere" dentro de `LoginView.vue`**
   * En `LoginView.vue` tenés declarado `const sesion = reactive<EstadoSesion>({ ... })`.
   * Esa variable es **local** de esa vista. En cuanto el login tiene éxito y hacés `router.push('/rutinas')`, Vue **destruye** `LoginView.vue`. Al destruirse la vista, la sesión desaparece de la memoria RAM.
2. **Fuga 2: El baúl (`StorageService`) existe, ¡pero nadie lo usa al loguear!**
   * Cuando `ejecutarLogin(datos)` devuelve éxito con `respuesta.datos`, nunca se invoca a `StorageService.guardar()`.
   * Al no guardarse en el almacenamiento local del navegador, apenas el atleta presiona **F5** o cambia de URL, la sesión se pierde en el aire.
3. **Fuga 3: `PerfilView.vue` está a ciegas (hardcodeado)**
   * En `PerfilView.vue` tenés escrito `const datosUsuario = ref({ nombre: 'Lucas', ... })` fijo a mano porque la vista no tiene de dónde consultar quién es el usuario que acaba de iniciar sesión.
4. **Fuga 4: `router/index.ts` no tiene guardián**
   * Cualquiera puede escribir en el navegador `http://localhost:5173/perfil` o `/rutinas` y entra directamente sin haberse autenticado, porque no hay un `router.beforeEach` que lo frene.

---

## 🗺️ 2. El Triángulo Fullstack Real de FITCORE

Tu sistema no es teórico, conviven tres actores concretos:

```text
┌───────────────────────────────────────┐
│         FRONTEND (Vite / Vue 3)       │
│         http://localhost:5173         │
└───────────────────────────────────────┘
          │                           ▲
  1. Envía credenciales        4. Devuelve JSON
    (JSON por POST)             (ApiResponse<Usuario>)
          ▼                           │
┌───────────────────────────────────────┐
│         BACKEND (Apache / PHP)        │
│        http://localhost/Backend       │
└───────────────────────────────────────┘
          │                           ▲
  2. Consulta SELECT           3. Retorna fila
     por email                   con password_hash
          ▼                           │
┌───────────────────────────────────────┐
│     BASE DE DATOS (PostgreSQL)        │
│     localhost:5432 (BD: FITNESS)      │
└───────────────────────────────────────┘
```

### 🤝 El Contrato entre PHP y TypeScript:
Para que TypeScript entienda a PHP, el JSON que devuelve `login.php` debe calzar 1:1 con tu DTO en [Frontend/src/DTO/Auth.DTO.ts](file:///c:/Users/lucas/FITCORE/Frontend/src/DTO/Auth.DTO.ts):

* **En TypeScript (`ApiResponse<Usuario>`):**
  * `exito: boolean`
  * `mensaje: string`
  * `datos: Usuario` (`id`, `nombre`, `email`)
* **En PHP (`echo json_encode(...)`):**
  * Claves idénticas: `'exito'`, `'mensaje'` y `'datos'`.
  * **¡Regla de oro de seguridad!** En `'datos'` jamás se devuelve `password_hash`, solo el `id`, `nombre` y `email`.

---

## 🧗‍♂️ 3. La Escalera de 10 Desafíos: De Cero a Sistema Completo

---

### 🟢 Nivel 1: Diseñar los moldes de plastilina
* **Objetivo:** Definir la forma exacta de los datos para que TypeScript no permita errores de tipeo en todo el proyecto.
* **¿Dónde trabajar?:** `Frontend/src/DTO/`
* **Nombre del archivo:** `Auth.DTO.ts` *(¡ya lo tenés empezado!)*
* **Qué tenés que pensar:**
  * **Exportaciones:**
    1. `interface Login`: con `email: string` y `password: string`.
    2. `interface Usuario`: con `id: string | number`, `nombre: string`, `email: string`.
    3. `interface ApiResponse<T>`: con `exito: boolean`, `mensaje: string`, `datos: T`.
* **Pregunta disparadora:** ¿El `id` que genera tu tabla en PostgreSQL es un `UUID` (`string`) o un autoincremental (`number`)? Ajustá tu interfaz según tu esquema real.
* **Criterio de éxito:** Si abrís cualquier archivo `.ts` y escribís una variable con tipo `Usuario`, el autocompletado te muestra exactamente sus campos.

---

### 🟢 Nivel 2: El baúl de herramientas (`StorageService`)
* **Objetivo:** Tener una clase con métodos estáticos capaz de guardar, leer y eliminar datos del atleta en el navegador, controlando que los datos no se rompan.
* **¿Dónde trabajar?:** [Frontend/src/services/StorageService.ts](file:///c:/Users/lucas/FITCORE/Frontend/src/services/StorageService.ts)
* **Qué tenés que pensar:**
  * Métodos `static`: `guardar<T>(clave: string, valor: T): void`, `obtener<T>(clave: string): T | null`, `eliminar(clave: string): void`.
  * En `obtener()`: ¿Qué pasa si el texto guardado en `localStorage` no es un JSON válido porque alguien lo alteró manualmente? ¿Cómo lo envuelves en un `try / catch` para devolver `null` sin romper la aplicación?
* **Pregunta disparadora:** ¿Por qué usamos genéricos `<T>` en lugar de fijar un solo tipo? *(Pista: para poder guardar tanto un string simple como un objeto de usuario complejo)*.
* **Criterio de éxito:** Podés hacer `StorageService.guardar('prueba', { test: true })` y luego `StorageService.obtener('prueba')` devuelve el objeto intacto.

---

### 🟡 Nivel 3: El cerebro reactivo global (Sacar la sesión de `LoginView`)
* **Objetivo:** Resolver la **Fuga 1**: la sesión no puede vivir atrapada adentro de `LoginView.vue`. Necesita estar en un archivo compartido que cualquier vista pueda consultar en tiempo real.
* **¿Dónde trabajar?:** `Frontend/src/composables/`
* **Nombre del archivo:** `useAuth.ts`
* **Qué tenés que pensar:**
  * **Importaciones:** `ref` o `reactive` desde `'vue'`, y tu interfaz `Usuario` desde `@/DTO/Auth.DTO`.
  * **Estado Global:** Declará una variable reactiva `usuarioActual = ref<Usuario | null>(null)` **afuera** de la función para que sea compartida como singleton.
  * **Exportaciones:** `export function useAuth() { return { usuarioActual, ... }; }`.
* **Pregunta disparadora:** Si dos componentes diferentes llaman a `const { usuarioActual } = useAuth()`, ¿qué ocurre cuando uno de ellos modifica `usuarioActual.value`?
* **Criterio de éxito:** Al cambiar `usuarioActual` desde una vista, otra vista diferente refleja el cambio al instante sin recargar la página.

---

### 🟡 Nivel 4: La interfaz del formulario
* **Objetivo:** Capturar lo que escribe el usuario de forma limpia sin mezclar lógica de red.
* **¿Dónde trabajar?:** [Frontend/src/components/FormLogin.vue](file:///c:/Users/lucas/FITCORE/Frontend/src/components/FormLogin.vue) y [Frontend/src/views/LoginView.vue](file:///c:/Users/lucas/FITCORE/Frontend/src/views/LoginView.vue).
* **Qué tenés que pensar:**
  * En `FormLogin.vue`: Inputs vinculados con `v-model` (`email`, `password`), y el `@submit.prevent` que valida campos y dispara `emit('guardar', { email, password })`. *(¡Ya lo tenés funcionando!)*
  * En `LoginView.vue`: La función `loguear(datos: Login)` recibe ese evento y arranca el proceso.
* **Pregunta disparadora:** ¿Por qué es una buena práctica de arquitectura separar el formulario visual (`FormLogin.vue`) de la vista que maneja la navegación (`LoginView.vue`)?
* **Criterio de éxito:** Al tocar "Iniciar Sesión", la página no se recarga y la función `loguear()` recibe el objeto `{ email, password }` listo para enviar.

---

### 🟠 Nivel 5: El cartero HTTP (`autenticar.service.ts`)
* **Objetivo:** El único archivo responsable de saber la URL del backend y hacer la petición por la red.
* **¿Dónde trabajar?:** [Frontend/src/services/autenticar.service.ts](file:///c:/Users/lucas/FITCORE/Frontend/src/services/autenticar.service.ts)
* **Qué tenés que pensar:**
  * URL base: `http://localhost/Backend/login.php`.
  * Configuración del `fetch()`: método `POST`, cabecera `'Content-Type': 'application/json'`, y `body: JSON.stringify(payload)`.
  * Parseo seguro: leer con `respuesta.text()`, hacer `JSON.parse()`, y si `!respuesta.ok` lanzar un error con el mensaje devuelto por PHP.
* **Pregunta disparadora:** Si el servidor Apache está apagado, ¿qué error arroja `fetch()` y cómo se lo informás al usuario?
* **Criterio de éxito:** En la pestaña *Red (Network)* de las herramientas de desarrollador (`F12`), ves la petición con código 200 (o 401 si la contraseña es errónea) y la respuesta JSON.

---

### 🟠 Nivel 6: La llave de la cerradura (Conexión PDO en PHP)
* **Objetivo:** Un archivo en el backend que establezca el puente seguro con PostgreSQL.
* **¿Dónde trabajar?:** `Backend/config/db.php` (o `Backend/db.php`).
* **Qué tenés que pensar:**
  * DSN de conexión: `pgsql:host=localhost;port=5432;dbname=FITNESS`.
  * Usuario y contraseña de PostgreSQL.
  * Opciones PDO:
    * `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION` (para que cualquier fallo lance excepción y no continúe a ciegas).
    * `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC` (para recibir arrays asociativos limpios).
* **Pregunta disparadora:** ¿Por qué la conexión a la base de datos se debe hacer en un archivo separado y reutilizarse con `require_once` en lugar de copiarla en cada endpoint?
* **Criterio de éxito:** Si ejecutas el script y la base de datos está activa, no arroja ninguna excepción y la variable `$pdo` queda lista para ejecutar consultas.

---

### 🔴 Nivel 7: El portero del edificio (`login.php` en PHP)
* **Objetivo:** Recibir el JSON enviado por Vue, consultar la tabla `usuarios` en PostgreSQL, verificar la contraseña y responder con el formato acordado.
* **¿Dónde trabajar?:** `Backend/login.php`
* **Qué tenés que pensar:**
  * **CORS:** Incluir las cabeceras `Access-Control-Allow-Origin: http://localhost:5173`, `Access-Control-Allow-Methods: POST, OPTIONS` y responder `200` si la petición es `OPTIONS`.
  * **Lectura del JSON:** Leer con `file_get_contents('php://input')` y decodificar con `json_decode()`.
  * **Consulta preparada:**
    ```sql
    SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = :email LIMIT 1
    ```
  * **Verificación:** Comparar con `password_verify($passwordRecibida, $fila['password_hash'])`.
  * **Salida limpia:** Si coincide, armar el JSON con `exito: true` y los datos del usuario (excluyendo el hash).
* **Pregunta disparadora:** ¿Qué código HTTP debe devolver PHP si el email no existe o la contraseña no coincide? *(Pista: 401 Unauthorized)*.
* **Criterio de éxito:** Probando el endpoint con credenciales válidas, devuelve `{ exito: true, datos: { id: ..., nombre: ..., email: ... } }`.

---

### 🔴 Nivel 8: El eslabón perdido (Guardar en `StorageService` al loguear)
* **Objetivo:** Resolver la **Fuga 2**: conectar la respuesta exitosa de PHP directamente con `StorageService`.
* **¿Dónde trabajar?:** [Frontend/src/views/LoginView.vue](file:///c:/Users/lucas/FITCORE/Frontend/src/views/LoginView.vue) y `Frontend/src/composables/useAuth.ts`.
* **Qué tenés que pensar:**
  * En `LoginView.vue`, dentro de la función `loguear()`:
    ```typescript
    if (respuesta.exito && respuesta.datos) {
      // 1. Guardar en el baúl persistente:
      StorageService.guardar('usuario_sesion', respuesta.datos);

      // 2. Actualizar el estado global en memoria RAM:
      // (a través de useAuth o de tu store)

      // 3. Navegar a la pantalla protegida:
      router.push('/rutinas');
    }
    ```
* **Pregunta disparadora:** ¿Por qué guardamos el objeto completo (`id`, `nombre`, `email`) y no solo el nombre? *(Pista: para que PerfilView pueda usar el ID en futuras consultas)*.
* **Criterio de éxito:** Te logueás en la app, abrís la consola del navegador (`F12`), vas a *Application* -> *Local Storage* y ves la clave `'usuario_sesion'` con los datos del usuario autenticado.

---

### 🟣 Nivel 9: El despertar tras el F5 (Auto-Login y `PerfilView` dinámico)
* **Objetivo:** Resolver la **Fuga 3**: cuando la aplicación arranca o el atleta recarga la página, recuperar la sesión guardada y mostrar los datos reales en pantalla.
* **¿Dónde trabajar?:** `Frontend/src/composables/useAuth.ts` y [Frontend/src/views/PerfilView.vue](file:///c:/Users/lucas/FITCORE/Frontend/src/views/PerfilView.vue).
* **Qué tenés que pensar:**
  * **En `useAuth.ts`:** Al inicializar la variable `usuarioActual`, consultá primero al almacenamiento:
    ```typescript
    const usuarioGuardado = StorageService.obtener<Usuario>('usuario_sesion');
    const usuarioActual = ref<Usuario | null>(usuarioGuardado);
    ```
  * **En `PerfilView.vue`:** En vez de tener `nombre: 'Lucas'` harcodeado:
    * Usar los datos de `usuarioActual` (o leer de `StorageService`).
    * Mostrar en la tarjeta el nombre real, su email y su ID de socio.
  * **Función de salida:** Crear `cerrarSesion()`:
    * `StorageService.eliminar('usuario_sesion')`.
    * `usuarioActual.value = null`.
    * `router.push('/login')`.
* **Pregunta disparadora:** Si el atleta toca el botón "Cerrar Sesión", ¿qué dos cosas deben limpiarse obligatoriamente? *(Pista: la memoria RAM reactiva y el almacenamiento del disco en el navegador)*.
* **Criterio de éxito:** Iniciás sesión, vas a `/perfil`, recargás con F5 diez veces y la pantalla sigue mostrando el nombre e ID del usuario logueado.

---

### 🟣 Nivel 10: El guardia de seguridad del gimnasio (Proteger rutas con el Router)
* **Objetivo:** Resolver la **Fuga 4**: impedir que usuarios anónimos entren a `/rutinas` o `/perfil`, y redirigir usuarios logueados lejos de `/login`.
* **¿Dónde trabajar?:** [Frontend/src/router/index.ts](file:///c:/Users/lucas/FITCORE/Frontend/src/router/index.ts)
* **Qué tenés que pensar:**
  * **Marcar las rutas protegidas:** En tu array de rutas, agregar `meta: { requiereAuth: true }` a `/rutinas`, `/perfil`, `/entrenamiento`.
  * **El guardia global:** Agregar `router.beforeEach((to, from, next) => { ... })`:
    * Consultar a `StorageService.obtener('usuario_sesion')`.
    * **Regla 1:** Si la ruta requiere autenticación (`to.meta.requiereAuth`) y **no** hay usuario guardado -> `next('/login')`.
    * **Regla 2:** Si la ruta es `/login` y el usuario **ya** está logueado -> `next('/rutinas')` (o `/dashboard`).
    * **Regla 3:** En cualquier otro caso -> `next()` (dar paso).
* **Pregunta disparadora:** ¿Qué pasaría si en `beforeEach` te olvidás de llamar a `next()` en alguna rama del `if`? *(Pista: la página se queda congelada en blanco esperando una decisión de navegación)*.
* **Criterio de éxito:** Abrís una pestaña en modo incógnito, intentás tipear directamente `http://localhost:5173/perfil` en la barra de direcciones y el router te expulsa automáticamente a `/login`.

---

## 📋 4. Checklist de Progreso de tu Implementación

Marcá cada paso a medida que lo vayas resolviendo:

- [ ] **Paso 1:** Verificar que `Frontend/src/DTO/Auth.DTO.ts` tenga `Usuario`, `Login` y `ApiResponse<T>`.
- [ ] **Paso 2:** Verificar que `StorageService.ts` tenga `guardar`, `obtener` con `try/catch`, y `eliminar`.
- [ ] **Paso 3:** Crear `Frontend/src/composables/useAuth.ts` con estado compartido para sacar la sesión de `LoginView.vue`.
- [ ] **Paso 4:** En `Frontend/src/views/LoginView.vue`, invocar `StorageService.guardar('usuario_sesion', respuesta.datos)` tras un login exitoso.
- [ ] **Paso 5:** Asegurar que `Backend/login.php` tenga cabeceras CORS y responda con `{ exito: true, datos: { id, nombre, email } }`.
- [ ] **Paso 6:** En `Frontend/src/views/PerfilView.vue`, reemplazar los datos harcodeados por los datos leídos de la sesión.
- [ ] **Paso 7:** En `Frontend/src/router/index.ts`, configurar `meta: { requiereAuth: true }` en las rutas privadas.
- [ ] **Paso 8:** Implementar `router.beforeEach` para expulsar a `/login` si no hay sesión en `StorageService`.
- [ ] **Paso 9:** Probar el flujo completo: Login -> Redirección a `/rutinas` -> Presionar F5 en `/perfil` -> Cerrar sesión -> Intentar volver atrás.
