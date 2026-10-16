# Plan del formulario de autenticacion

Este documento conserva las siete etapas originales y agrega los puntos necesarios para completar, probar y mantener el flujo de registro, login y perfil.

## Estado actual

- `[x]` Implementado y conectado al flujo.
- `[~]` Existe, pero falta completar o integrar.
- `[ ]` Pendiente.

## ETAPA 1: Construccion de Pantallas Base (Views)

Objetivo: tener los tres contenedores principales donde se montara la aplicacion.

- `[x]` **1.1.** Crear `src/views/LoginView.vue` con su estructura base (`template`, `script setup`, `style`).
- `[x]` **1.2.** Crear `src/views/RegistroView.vue` con su estructura base.
- `[~]` **1.3.** Crear `src/views/PerfilView.vue` con su estructura base de perfil. El archivo existe, pero aun monta componentes de la pagina de inicio.
- `[~]` **1.4.** Colocar titulos en cada vista usando `obtenerTituloVista()` para verificar la comunicacion con los DTOs. Login y registro obtienen titulo desde sus componentes; falta integrarlo en perfil.

## ETAPA 2: Componentes de Formulario (Components)

Objetivo: aislar la interfaz que captura los datos del usuario.

- `[x]` **2.1.** Crear `src/components/FormLogin.vue`.
- `[x]` **2.2.** Definir en `FormLogin.vue` los campos de email y contrasena vinculados con `v-model`.
- `[x]` **2.3.** Configurar `@submit.prevent` y emitir los datos de login al padre mediante `defineEmits`.
- `[~]` **2.4.** Crear `src/components/FormRegistro.vue`. En el proyecto actual su equivalente se llama `src/components/Formulario.vue`.
- `[x]` **2.5.** Definir en el formulario de registro los campos nombre, email y contrasena con `v-model`.
- `[x]` **2.6.** Configurar el envio del formulario para emitir los datos de registro al padre.
- `[x]` **2.7.** Integrar `<FormLogin />` en `LoginView.vue` y el formulario de registro (`Formulario.vue`) en `RegistroView.vue`.

## ETAPA 3: Enrutamiento de la Aplicacion (Router)

Objetivo: permitir cambiar de pantalla mediante la URL.

- `[x]` **3.1.** Crear `src/router/index.ts`.
- `[x]` **3.2.** Definir las rutas `/login`, `/registro` y `/perfil`, asociadas a sus vistas. Tambien existe la ruta `/` para inicio.
- `[x]` **3.3.** Importar y registrar el router desde `src/main.ts`.
- `[x]` **3.4.** Anadir `<router-view />` a `src/App.vue`.

## ETAPA 4: Contratos de Datos y Servicio de Red (Auth DTO & AuthService)

Objetivo: construir la comunicacion entre frontend y backend.

- `[x]` **4.1.** Crear `src/DTO/auth.dto.ts` con `LoginPayload`, `RegisterPayload`, `Usuario` y `ApiResponse`.
- `[~]` **4.2.** Crear `src/services/auth.service.ts`. El archivo actual es `src/services/auth.services.ts` (plural).
- `[x]` **4.3.** Implementar `ejecutarLogin(payload: LoginPayload)`.
- `[~]` **4.4.** Enviar `fetch()` por `POST` y aplicar validaciones. Se usan `validarURL`, `validarPayload` y la validacion de JSON; falta usar `validarCodigo()` de forma explicita.
- `[x]` **4.5.** Implementar `ejecutarRegistro(payload: RegisterPayload)` con envio `POST` y lectura de respuesta.

## ETAPA 5: Backend Centralizado (PHP API)

Objetivo: procesar las solicitudes y devolver respuestas JSON.

- `[x]` **5.1.** Configurar CORS en los endpoints PHP de autenticacion.
- `[~]` **5.2.** Crear un controlador central `backend/api/auth.php`. Actualmente se usan `Backend/login.php` y `Backend/registro.php` separados; no existe el controlador central.
- `[x]` **5.3.** Leer y decodificar el JSON entrante desde `php://input`.
- `[~]` **5.4.** Evaluar la operacion solicitada. Actualmente cada endpoint atiende una operacion distinta, no un campo `accion` en un controlador central.
- `[x]` **5.5.** Devolver respuestas estructuradas en JSON para exito y error.

## ETAPA 6: Manejo del Estado Global (Store)

Objetivo: conservar el usuario autenticado al navegar entre pantallas.

- `[ ]` **6.1.** Crear `src/stores/auth.store.ts`.
- `[ ]` **6.2.** Definir estado reactivo global: `usuarioActual`, `estaLogueado` y `rolUsuario`.
- `[ ]` **6.3.** Crear `setUsuario(datosUsuario)` para guardar el usuario tras un login o registro exitoso.
- `[ ]` **6.4.** Crear `cerrarSesion()` para limpiar la sesion.

## ETAPA 7: Integracion y Conexion Total

Objetivo: conectar vistas, validaciones, servicio, store, backend y router.

- `[ ]` **7.1.** Crear `src/composables/useAuth.ts` para orquestar autenticacion.
- `[ ]` **7.2.** Ejecutar `validarEmail()` y `validarContra()` antes de llamar al servicio.
- `[~]` **7.3.** Conectar el evento de formulario de login con la funcion de autenticacion. Actualmente `LoginView.vue` llama directamente a `ejecutarLogin()`.
- `[ ]` **7.4.** Al autenticar correctamente, guardar el usuario en la store y navegar con `router.push('/perfil')`.
- `[~]` **7.5.** Probar el flujo completo: formulario -> validaciones -> servicio -> PHP -> store -> router -> perfil. El registro ya fue probado con una cuenta; falta completar y probar el flujo integral de login y perfil.

## Puntos nuevos para completar el trabajo

### ETAPA 8: Validacion y experiencia de uso

- `[ ]` **8.1.** Marcar como obligatorios los campos del formulario de registro y aplicar limites razonables de longitud.
- `[ ]` **8.2.** Mostrar errores de validacion junto al campo correspondiente, sin depender solo de `alert()`.
- `[ ]` **8.3.** Mostrar estado de envio/carga y deshabilitar el boton mientras se procesa una solicitud para evitar envios duplicados.
- `[ ]` **8.4.** Mostrar al usuario los mensajes de error que devuelve el backend, conservando mensajes genericos para fallos inesperados.
- `[ ]` **8.5.** Asegurar que los campos y mensajes funcionen con teclado y lectores de pantalla (`label`, foco y `aria-describedby`).

### ETAPA 9: Base de datos y seguridad

- `[x]` **9.1.** Configurar PDO para PostgreSQL en el puerto `5432` en `Backend/db.php`.
- `[ ]` **9.2.** Leer host, puerto, base, usuario y contrasena desde variables de entorno o configuracion fuera del codigo versionado.
- `[ ]` **9.3.** Documentar el esquema PostgreSQL requerido para `usuarios`, incluyendo `id`, `nombre`, `email`, `password_hash` y `rol` si se usa en login.
- `[ ]` **9.4.** Asegurar unicidad de email en la base de datos y manejar la violacion de esa restriccion en el registro.
- `[ ]` **9.5.** Evitar devolver detalles internos de PDO al navegador en produccion; registrar el detalle en el log del servidor.
- `[ ]` **9.6.** Revisar CORS para permitir solo el origen de frontend esperado.

### ETAPA 10: Perfil, rutas y sesion

- `[ ]` **10.1.** Completar `PerfilView.vue` para mostrar los datos reales de `usuarioActual`, no datos de muestra de la pagina de inicio.
- `[ ]` **10.2.** Definir que ocurre despues de registrarse: iniciar sesion automaticamente o enviar al usuario a `/login`.
- `[ ]` **10.3.** Proteger la ruta `/perfil` cuando no exista una sesion, mediante una guarda del router.
- `[ ]` **10.4.** Conectar el boton de cerrar sesion con `cerrarSesion()` y redirigir a `/login`.
- `[ ]` **10.5.** Decidir si la sesion debe sobrevivir a una recarga. Si es asi, definir almacenamiento y expiracion con criterios de seguridad.

### ETAPA 11: Pruebas y compilacion

- `[ ]` **11.1.** Probar registro correcto, email duplicado, campos invalidos y error de base de datos.
- `[ ]` **11.2.** Probar login correcto, contrasena incorrecta y usuario inexistente.
- `[ ]` **11.3.** Probar navegacion login -> perfil, acceso directo a `/perfil` y cierre de sesion.
- `[ ]` **11.4.** Corregir errores de tipos existentes en `Header.vue`, `Header2.vue` y otros componentes usados por las vistas.
- `[ ]` **11.5.** Corregir la importacion de imagen incompleta y las referencias no declaradas `nombre`/`nivel` en `Main.vue`.
- `[ ]` **11.6.** Ejecutar `npm run type-check`, `npm run build` y `php -l` para los endpoints modificados.

## Archivos de autenticacion del proyecto actual

### Frontend

- `src/views/LoginView.vue`
- `src/views/RegistroView.vue`
- `src/views/PerfilView.vue`
- `src/components/FormLogin.vue`
- `src/components/Formulario.vue` (formulario de registro actual)
- `src/DTO/auth.dto.ts`
- `src/DTO/estadoSession.ts`
- `src/DTO/RegistroUsuarioDTO.ts`
- `src/DTO/usuario.dto.ts`
- `src/services/auth.services.ts` (nombre actual, plural)
- `src/services/paso.service.ts`
- `src/router/index.ts`
- `src/main.ts`
- `src/App.vue`

### Backend

- `Backend/db.php`
- `Backend/login.php`
- `Backend/registro.php`

## Archivos planeados que aun no existen

- `src/components/FormRegistro.vue` (hoy su equivalente es `Formulario.vue`).
- `src/services/auth.service.ts` (hoy el archivo se llama `auth.services.ts`).
- `src/stores/auth.store.ts`.
- `src/composables/useAuth.ts`.
- `Backend/api/auth.php` (hoy login y registro son endpoints independientes).