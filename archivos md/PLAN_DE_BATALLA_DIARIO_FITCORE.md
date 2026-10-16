# FITCORE — Plan de Batalla Diario Oficial (14 Octubre – 06 Noviembre 2026)
## Cronograma Maestro Adaptado a la Realidad de tu Código

**Líder de Proyecto & Arquitecto:** Antigravity  
**Ingeniero Responsable:** Lucas Mercado  
**Fecha de Entrega y Defensa Oral:** Viernes 06 de Noviembre de 2026  
**Horario Fijo Diario:** 08:30 a 10:30 (Exactamente 2 horas / 120 minutos continuos)  

---

### 📂 INVENTARIO REAL DE TU PROYECTO HOY (Cero Suposiciones)

#### Archivos que SÍ existen en tu Frontend:
- `Frontend/src/views/LoginView.vue` *(captura login pero atrapa la sesión en una variable reactiva local `sesion`)*
- `Frontend/src/views/PerfilView.vue` *(monta `BarraUsuario.vue`, `Header2.vue` y `Main.vue`)*
- `Frontend/src/views/RegistroView.vue` *(captura registro y llama a `ejecutarRegistro`)*
- `Frontend/src/views/InicioView.vue` *(landing inicial con `Header.vue` y `Main.vue`)*
- `Frontend/src/components/FormLogin.vue` *(formulario de login con `v-model` en email/password y emite `guardar`)*
- `Frontend/src/components/Formulario.vue` *(formulario de registro con `v-model` en nombre/password/email)*
- `Frontend/src/components/BarraUsuario.vue` *(perfil lateral con textos fijos "Lucas Mercado" y "Racha activa - 6 dias")*
- `Frontend/src/components/Main.vue` *(cuerpo central con textos fijos "Lucas{{ nombre }}" y "NIVEL 10 {{ nivel }}")*
- `Frontend/src/components/Header.vue` *(barra superior de navegación con logo FITCORE)*
- `Frontend/src/components/Header2.vue` *(saludo superior "Buenas tardes, {{ nombre }}")*
- `Frontend/src/components/Crear-rutinas.vue` *(maqueta inicial para creación de planes)*
- `Frontend/src/DTO/Auth.DTO.ts` *(interfaces: `Login`, `Registro`, `Usuario`, `ApiResponse<T>`)*
- `Frontend/src/DTO/EstadoSesion.ts` *(interfaces: `EstadoSesion` con `UsuarioConectado` y `Autenticado`)*
- `Frontend/src/DTO/Usuario.dto.ts` *(enum `RolesUsuarios`)*
- `Frontend/src/DTO/http.dto.ts` *(enums `metodoHttp`, `EstadoRespuesta`, funciones auxiliares)*
- `Frontend/src/DTO/registroUsuario.DTO.ts` *(enum `PasoFormulario`)*
- `Frontend/src/services/autenticar.service.ts` *(funciones `ejecutarLogin()` y `ejecutarRegistro()` conectadas a `http://localhost/Backend`)*
- `Frontend/src/services/PasoService.ts` *(validaciones de URL, payload, email, password, JSON)*
- `Frontend/src/router/index.ts` *(rutas configuradas: `/`, `/login`, `/perfil`, `/registro`)*
- `Frontend/src/main.ts` *(monta la aplicación y el router)*
- `Frontend/src/App.vue` *(monta `<router-view />`)*

#### Carpetas reales que existen pero están TOTALMENTE VACÍAS hoy:
- `Frontend/src/stores/` *(vacía: aquí es donde construirás el Store central)*
- `Frontend/src/composables/` *(vacía: aquí es donde construirás la lógica reutilizable)*
- `Frontend/src/components/EJERCICIOS/` *(vacía)*

#### Base de Datos y Backend Real:
- `archivos md/fitcore_schema.sql` *(schema PostgreSQL: tablas `niveles`, `usuarios`, `sesiones_auth`, `xp_historial`, `ejercicios`, `rutinas`, `rutina_ejercicios`, `agenda_semanal`, `sesiones_entrenamiento`, `series_registro`)*
- Endpoints PHP en Apache (`http://localhost/Backend`): `login.php`, `registro.php`, `db.php`.

---

### Estructura de Tiempo y Cronómetro Diario (08:30 – 10:30):
- 💻 **08:30 – 09:10 (40 min):** Práctica y Código Atómico (Filosofía Anti-Masticado en tus archivos reales)
- 📝 **09:10 – 09:40 (30 min):** Redacción en el Índice Oficial ([DOCUMENTACION_TECNICA_FITCORE.md](file:///c:/Users/lucas/ObsidiantFit/archivos%20md/DOCUMENTACION_TECNICA_FITCORE.md))
- 🎨 **09:40 – 10:10 (30 min):** Diseño de Diapositiva PowerPoint
- 🗣️ **10:10 – 10:30 (20 min):** Ensayo Oral de Defensa

---

# FASE I: MVP 1 — REPARAR LA IDENTIDAD REAL, SESIÓN GLOBAL Y PERFIL DINÁMICO

---

## JORNADA 1: El Diagnóstico Real de la "Sesión Atrapada" en `LoginView.vue`

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 1.1.1:** Abrir `Frontend/src/views/LoginView.vue`.
  - **Sub-paso 1.1.1.1:** Observa las líneas 9 a 12: tienes `const sesion = reactive<EstadoSesion>({ UsuarioConectado: null, Autenticado: false })`.
  - **Sub-paso 1.1.1.2:** Observa las líneas 18 a 21: cuando `ejecutarLogin(datos)` responde con éxito, guardas el nombre en `sesion.UsuarioConectado = respuesta.datos.nombre`.
  - **Sub-paso 1.1.1.3:** Abre `Frontend/src/views/PerfilView.vue` y fíjate en su `<script setup>`: no recibe nada de `LoginView.vue`. Importa `BarraUsuario.vue`, `Header2.vue` y `Main.vue`.
  - **Sub-paso 1.1.1.4:** Abre `Frontend/src/components/BarraUsuario.vue` (líneas 73-74): el nombre `"Lucas Mercado"` y `"Racha activa - 6 dias"` son texto estático escrito a mano en el HTML. Lo mismo en `Main.vue` (líneas 48 y 53): `"Lucas{{ nombre }}"` y `"NIVEL 10 {{ nivel }}"` donde esas variables ni siquiera están conectadas.
- **Pregunta de Control:** ¿Por qué cuando el usuario hace login exitoso en `LoginView.vue` y luego cambia la URL a `/perfil`, los datos de `sesion` se destruyen y `PerfilView.vue` no se entera de quién se acaba de loguear?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 5.9:** *Flujo de autenticación* y **Sección 5.16:** *Decisiones de diseño*.
  - Redactar en [DOCUMENTACION_TECNICA_FITCORE.md](file:///c:/Users/lucas/ObsidiantFit/archivos%20md/DOCUMENTACION_TECNICA_FITCORE.md) por qué una SPA destruye el componente de login al navegar y por qué la identidad del usuario no puede vivir dentro de una vista individual.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Falla Arquitectónica Detectada: Sesión Atrapada en LoginView"*.
  - **Visual:** Captura del código de `LoginView.vue` (líneas 9-12) contrastada con `BarraUsuario.vue` con textos hardcodeados, mostrando la desconexión.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"Señores del jurado, en el diagnóstico inicial de FITCORE detectamos una fisura típica: la sesión vivía como variable reactiva dentro de `LoginView`. Al navegar hacia el perfil, el ciclo de vida de Vue destruía el componente y la identidad moría con él. Para gobernar la aplicación alrededor del `usuario.id`, es imperativo elevar el estado a una capa central."*

---

## JORNADA 2: Crear el Store Central en tu carpeta vacía `Frontend/src/stores/`

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 1.2.1:** Crear el archivo físico `Frontend/src/stores/auth.ts` (en tu carpeta `stores/` que hoy está vacía).
  - **Sub-paso 1.2.1.1:** Instalar o verificar Pinia en `Frontend/package.json` o diseñar un Store reactivo nativo con `reactive()` exportado a nivel de módulo.
  - **Sub-paso 1.2.1.2:** Definir el estado global: una variable reactiva que contenga el usuario actual (id, nombre, email, nivel, xp) y un booleano `autenticado`.
  - **Sub-paso 1.2.1.3:** Crear dos métodos en ese archivo: `establecerSesion(usuario)` para guardar el usuario al loguearse, y `limpiarSesion()` para borrarlo al salir.
- **Pregunta de Control:** ¿Por qué un archivo exportado desde `src/stores/` no se destruye cuando el router cambia de vista, a diferencia de lo que ocurre dentro de `LoginView.vue`?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 5.3:** *Arquitectura frontend* y **Sección 5.7.4:** *Servicios y Gestión de Estado*.
  - Describir cómo la carpeta `stores/` pasa de estar vacía a albergar la memoria RAM centralizada de FITCORE.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Memoria Centralizada: Arquitectura de Stores en FITCORE"*.
  - **Visual:** Diagrama mostrando el Store `auth.ts` en el centro, alimentado por `autenticar.service.ts` y consumido por `PerfilView.vue` y `Header.vue`.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"Para resolver la sesión atrapada dimos vida a la carpeta `stores/`. Creamos un store de autenticación que actúa como la memoria viva de FITCORE. Ahora, `LoginView` no almacena nada: delega el usuario al Store, permitiendo que cualquier componente consulte la identidad sin acoplamiento."*

---

## JORNADA 3: Persistencia ante el F5 usando `localStorage`

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 1.3.1:** Conectar `Frontend/src/stores/auth.ts` con el disco del navegador (`localStorage`).
  - **Sub-paso 1.3.1.1:** Al ejecutarse `establecerSesion(usuario)`, guardar una copia en `localStorage.setItem('fitcore_user', JSON.stringify(usuario))`.
  - **Sub-paso 1.3.1.2:** En la inicialización del store, agregar una función que lea `localStorage.getItem('fitcore_user')`. Si existe, parsearlo con `JSON.parse()` e inicializar el estado reactivo.
  - **Sub-paso 1.3.1.3:** Al ejecutarse `limpiarSesion()`, llamar a `localStorage.removeItem('fitcore_user')`.
- **Pregunta de Control:** Si un usuario presiona F5 en el teclado, ¿por qué la memoria RAM de JavaScript se reinicia y por qué el `localStorage` es la única garantía de que no tenga que volver a poner su contraseña?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 3.3.6:** *Requerimientos No Funcionales: Persistencia*.
  - Explicar la hidratación automática del cliente y la tolerancia al refresco de página.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Persistencia del Estado del Cliente ante el F5"*.
  - **Visual:** Diagrama de dos carriles: RAM (volátil) vs. LocalStorage (persistente) durante la recarga de página.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"Una aplicación web profesional no puede perder la sesión por un simple refresco del navegador. En FITCORE combinamos la reactividad en RAM del Store con la persistencia de `localStorage`. Al pulsar F5, el store se rehidrata automáticamente antes de pintar la vista."*

---

## JORNADA 4: Corregir tu Contrato Real en `Frontend/src/DTO/Auth.DTO.ts`

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 1.4.1:** Inspeccionar `Frontend/src/DTO/Auth.DTO.ts`.
  - **Sub-paso 1.4.1.1:** Observa la interfaz actual (líneas 13-17):
    ```ts
    export interface Usuario {
      id: string | number,
      nombre: string,
      email: string 
    }
    ```
  - **Sub-paso 1.4.1.2:** Compárala con tu tabla real `usuarios` en [fitcore_schema.sql](file:///c:/Users/lucas/ObsidiantFit/archivos%20md/fitcore_schema.sql): la tabla tiene `id (UUID)`, `nombre`, `email`, `rol`, `xp_total` y `nivel_actual`.
  - **Sub-paso 1.4.1.3:** Modifica `Usuario` en `Auth.DTO.ts` para que incluya `nivel_actual: number` y `xp_total: number`.
  - **Sub-paso 1.4.1.4:** Revisa `Backend/login.php` para asegurarte de que en la consulta SQL `SELECT` devuelvas `nivel_actual` y `xp_total` dentro del objeto JSON `datos`.
- **Pregunta de Control:** Si tu interfaz `Usuario` en TypeScript solo conoce `id`, `nombre` y `email`, ¿por qué tu frontend estaba completamente ciego para mostrar la barra de nivel del usuario en el perfil?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 5.8:** *DTO y transferencia de datos* y **Sección 8.16:** *DTO utilizados*.
  - Transcribir las interfaces actualizadas de `Auth.DTO.ts` y documentar la correspondencia con `usuarios` de PostgreSQL.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Alineación de Contratos DTO con el Schema de Base de Datos"*.
  - **Visual:** Comparativa entre la tabla `usuarios` de PostgreSQL y la interfaz `Usuario` en `Auth.DTO.ts`.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"En FITCORE garantizamos la integridad de tipos extremo a extremo. Alineamos el DTO de usuario con el schema PostgreSQL para que `nivel_actual` y `xp_total` viajen tipados desde el login, permitiendo que la interfaz conozca el rango del atleta desde el primer segundo."*

---

## JORNADA 5: Redirección con Router y Guardianes de Ruta en `Frontend/src/router/index.ts`

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 1.5.1:** Modificar `Frontend/src/views/LoginView.vue`.
  - **Sub-paso 1.5.1.1:** Importar `useRouter` de `vue-router` e instanciar `const router = useRouter()`.
  - **Sub-paso 1.5.1.2:** En la función `loguear`, tras recibir `respuesta.exito`, llamar al método del Store para guardar el usuario y ejecutar `router.push('/perfil')`.
- **Paso 1.5.2:** Blindar `Frontend/src/router/index.ts`.
  - **Sub-paso 1.5.2.1:** Fíjate en tu archivo `router/index.ts`: actualmente no tiene ningún control de acceso.
  - **Sub-paso 1.5.2.2:** Agregar `meta: { requiereAuth: true }` a la ruta `/perfil`.
  - **Sub-paso 1.5.2.3:** Agregar `router.beforeEach((to, from, next) => { ... })`: si la ruta requiere autenticación y el usuario no está logueado en el Store/localStorage, redirigir a `/login`.
- **Pregunta de Control:** ¿Qué pasaba antes si abrías una pestaña de incógnito y escribías directamente `http://localhost:5173/perfil`, y qué pasa ahora con el guardián de ruta?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 3.3.1:** *Seguridad* y **Sección 7.2:** *Estructura de navegación*.
  - Documentar el guardián perimetral `beforeEach` de Vue Router.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Seguridad Perimetral: Navigation Guards en Vue Router"*.
  - **Visual:** Diagrama de decisión del router interceptando una visita anónima hacia `/perfil`.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"La seguridad en FITCORE no se basa en confiar en que el usuario no tipee una URL. Implementamos un Navigation Guard global en `router/index.ts`. Si una sesión no está activa, el router aborta la navegación hacia `/perfil` y fuerza la redirección al login."*

---

## JORNADA 6: Conectar `BarraUsuario.vue`, `Header2.vue` y `Main.vue` con Datos Reales

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 1.6.1:** Desarmar los textos hardcoded en tus componentes de `PerfilView.vue`.
  - **Sub-paso 1.6.1.1:** En `Frontend/src/components/BarraUsuario.vue`: importar el Store de autenticación. Reemplazar el texto fijo `"Lucas Mercado"` por `{{ authStore.usuario?.nombre }}`.
  - **Sub-paso 1.6.1.2:** En `Frontend/src/components/Main.vue`: reemplazar `"Lucas{{ nombre }}"` y `"NIVEL 10 {{ nivel }}"` por los datos reales del Store: `{{ authStore.usuario?.nombre }}` y `"NIVEL " + authStore.usuario?.nivel_actual`.
  - **Sub-paso 1.6.1.3:** En `Header2.vue`: conectar el saludo `"Buenas tardes, {{ nombre }}"` con el nombre real del usuario en sesión.
  - **Sub-paso 1.6.1.4:** Agregar botón "Cerrar Sesión" en `BarraUsuario.vue` o `Header2.vue` que limpie el store y te redirija a `/login`.
- **Pregunta de Control:** ¿Cómo verificas en el navegador que al loguearte con dos cuentas diferentes en la base de datos (`FITNESS`), el perfil cambia de nombre y nivel automáticamente sin tocar el código?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 4.5.3:** *CU-03 Consultar perfil* y **Sección 7.6:** *Vista de perfil*.
  - Documentar el fin del MVP 1: perfil 100% dinámico conectado a base de datos real.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Hito Cumplido: MVP 1 Finalizado y Perfil Dinámico"*.
  - **Visual:** Capturas reales de dos perfiles con nombres y niveles distintos extraídos de PostgreSQL.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"Con esta jornada concluimos el MVP 1 de FITCORE. Erradicamos los datos fijos del DOM: el perfil ahora escucha directamente al Store y a PostgreSQL. El usuario se registra, inicia sesión, es redirigido con seguridad y visualiza su nombre y nivel real en pantalla."*

---

# FASE II: MVP 2 — CATÁLOGO, RELACIONES Y CANDADOS DE RUTINAS

---

## JORNADA 7: Aprovechar tu Componente `Crear-rutinas.vue` y el Catálogo de Ejercicios

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 2.1.1:** Inspección de `Frontend/src/components/Crear-rutinas.vue`.
  - **Sub-paso 2.1.1.1:** Observa tu archivo `Crear-rutinas.vue`: actualmente tiene un formulario con nombre, contraseña y correo bajo el título "PLAN INTELIGENTE".
  - **Sub-paso 2.1.1.2:** Reorientar este componente a su propósito original del MVP 2: capturar el nombre de la rutina, descripción y el `nivel_requerido` (un número entero del 1 al 10).
  - **Sub-paso 2.1.1.3:** Crear el contrato `Frontend/src/DTO/Rutina.DTO.ts` con la interfaz `Rutina` (`id`, `nombre`, `descripcion`, `nivel_requerido`, `usuario_id`).
- **Pregunta de Control:** En tu archivo `fitcore_schema.sql`, ¿por qué la tabla `rutinas` tiene la columna `nivel_requerido REFERENCES niveles(numero_nivel)`?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 6.5.6:** *Entidad rutinas* y **Sección 4.5.4:** *CU-04 Gestionar rutinas*.
  - Describir cómo se define una rutina y su vínculo con el sistema de niveles.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Modelado de Rutinas con Restricción de Nivel"*.
  - **Visual:** Estructura de la entidad `rutinas` en PostgreSQL y su interfaz DTO en TypeScript.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"En el MVP 2 de FITCORE, las rutinas no son solo listas de ejercicios; poseen una restricción matemática de acceso mediante la columna `nivel_requerido`. Esta clave foránea vincula la rutina directamente con los umbrales de la tabla `niveles`."*

---

## JORNADA 8: La Lógica del Candado en la Vista de Rutinas

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 2.2.1:** Crear la vista `Frontend/src/views/RutinasView.vue` y registrarla en `router/index.ts`.
  - **Sub-paso 2.2.1.1:** En `RutinasView.vue`, listar una colección de rutinas (por ejemplo: Calistenia Básica nivel 1, Dominadas Pro nivel 3, Muscle-Up nivel 5).
  - **Sub-paso 2.2.1.2:** En cada tarjeta de rutina, programar la condición reactiva:
    `const bloqueada = computed(() => authStore.usuario.nivel_actual < rutina.nivel_requerido)`.
  - **Sub-paso 2.2.1.3:** Si `bloqueada` es true: mostrar icono de candado y deshabilitar el botón de inicio; si es false: mostrar botón verde "Comenzar".
- **Pregunta de Control:** Si un usuario es Nivel 1 en su perfil, ¿por qué la rutina de Nivel 3 debe mostrar un candado sin que tengamos que hacer otra petición al servidor?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 7.7:** *Vista de rutinas* y **Sección 4.5.4:** *CU-04 Gestionar rutinas*.
  - Documentar la inecuación matemática de bloqueo visual en el frontend.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"El Candado Reactivo: Condición Matemática por Nivel"*.
  - **Visual:** Tarjeta de rutina abierta (Nivel 1) vs. Tarjeta de rutina con candado (Nivel 3) evaluadas con el `nivel_actual` del store.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"El sistema de candados de FITCORE es una expresión directa de la reactividad de Vue 3. La interfaz evalúa si el `nivel_actual` almacenado en el Store es menor al `nivel_requerido` de la rutina. Si es menor, se renderiza un candado de forma inmediata sin latencia de red."*

---

## JORNADA 9: La Relación Intermedia `rutina_ejercicios` en Base de Datos

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 2.3.1:** Analizar la tabla `rutina_ejercicios` en `archivos md/fitcore_schema.sql`.
  - **Sub-paso 2.3.1.1:** Observa las columnas: `rutina_id`, `ejercicio_id`, `orden`, `series_objetivo`, `reps_objetivo`, `descanso_segundos`.
  - **Sub-paso 2.3.1.2:** Fíjate en la restricción `UNIQUE (rutina_id, orden)`: garantiza que no haya dos ejercicios compitiendo por la misma posición secuencial.
  - **Sub-paso 2.3.1.3:** Fíjate en `ON DELETE RESTRICT` en `ejercicio_id`: impide borrar un ejercicio de la base de datos si alguna rutina lo está utilizando.
- **Pregunta de Control:** ¿Por qué la relación entre `rutinas` y `ejercicios` es de Muchos a Muchos (N:M) y por qué la tabla intermedia necesita almacenar `series_objetivo` y `orden`?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 6.5.7:** *Entidad rutina_ejercicios* y **Sección 6.13:** *Integridad referencial*.
  - Documentar la tabla intermedia y la protección referencial con `RESTRICT`.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Relación Muchos a Muchos: Rutinas y Ejercicios"*.
  - **Visual:** Esquema de la tabla intermedia `rutina_ejercicios` conectando `rutinas` con `ejercicios`.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"Para construir rutinas estructuradas, FITCORE normaliza la base de datos mediante la tabla intermedia `rutina_ejercicios`. Esto permite secuenciar los movimientos con la columna `orden` y proteger la biblioteca de ejercicios mediante restricciones `ON DELETE RESTRICT`."*

---

# FASE III: MVP 3 — AGENDA Y SESIÓN EN VIVO

---

## JORNADA 10: Mapeo de Días en la Agenda (`agenda_semanal`)

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 3.1.1:** Analizar en `fitcore_schema.sql` la tabla `agenda_semanal`.
  - **Sub-paso 3.1.1.1:** Observar las columnas: `usuario_id`, `dia_semana`, `rutina_id`.
  - **Sub-paso 3.1.1.2:** Comprender la restricción: `CHECK (dia_semana BETWEEN 1 AND 7)` y `UNIQUE (usuario_id, dia_semana)`.
  - **Sub-paso 3.1.1.3:** Crear un script helper en `Frontend/src/services/agenda.service.ts` para obtener el número de día actual (1 = Lunes a 7 = Domingo) y consultar la rutina asignada para hoy.
- **Pregunta de Control:** ¿Cómo asegura la restricción `UNIQUE (usuario_id, dia_semana)` que un atleta no pueda asignar dos rutinas conflictivas al mismo día Lunes?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 4.5.5:** *CU-05 Programar rutina* y **Sección 6.5.8:** *Entidad agenda_semanal*.
  - Documentar la lógica del planificador semanal y su restricción UNIQUE.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Planificación Semanal: Estructura de la Tabla agenda_semanal"*.
  - **Visual:** Matriz de 7 días (Lunes a Domingo) asociada al `usuario_id` en PostgreSQL.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"La adherencia deportiva requiere planificación. Con la tabla `agenda_semanal`, FITCORE restringe a nivel de base de datos que cada día de la semana (1 al 7) tenga una única rutina asociada por usuario, facilitando la consulta instantánea del entrenamiento de hoy."*

---

## JORNADA 11: El Nacimiento de una Sesión (`sesiones_entrenamiento`)

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 3.2.1:** Analizar en `fitcore_schema.sql` la tabla `sesiones_entrenamiento`.
  - **Sub-paso 3.2.1.1:** Observar las columnas: `id (UUID)`, `usuario_id`, `rutina_id`, `fecha (NOW())`, `estado ('en_progreso')`.
  - **Sub-paso 3.2.1.2:** Diseñar el flujo conceptual: al pulsar "Comenzar Entrenamiento", se envía una petición con `usuario_id` y `rutina_id` para insertar una fila con estado `'en_progreso'`.
  - **Sub-paso 3.2.1.3:** Guardar el `id` de esa sesión en una variable para relacionar las series que se hagan a continuación.
- **Pregunta de Control:** ¿Por qué la columna `estado` tiene una restricción `CHECK (estado IN ('en_progreso', 'completada', 'cancelada'))`?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 4.5.6:** *CU-06 Iniciar entrenamiento* y **Sección 6.5.9:** *Entidad sesiones_entrenamiento*.
  - Documentar el ciclo de vida de la sesión activa y sus estados finitos.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"El Nacimiento de la Sesión de Entrenamiento"*.
  - **Visual:** Diagrama de transición de estados: En Progreso $\rightarrow$ Completada / Cancelada.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"En FITCORE, un entrenamiento no es una variable suelta en el navegador: es una entidad registrada en PostgreSQL. Al pulsar comenzar, nace un registro en `sesiones_entrenamiento` con estado `'en_progreso'`, asegurando que cada segundo y cada serie queden vinculados a un evento formal."*

---

## JORNADA 12: Persistencia del Live Training (Cronómetro Inmune a F5)

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 3.3.1:** Crear `Frontend/src/stores/entrenamiento.ts` en tu carpeta `stores/`.
  - **Sub-paso 3.3.1.1:** Almacenar `sesionActivaId` y `timestampInicio: number` (`Date.now()`).
  - **Sub-paso 3.3.1.2:** Calcular el tiempo transcurrido mediante: `tiempoSegundos = Math.floor((Date.now() - timestampInicio) / 1000)`.
  - **Sub-paso 3.3.1.3:** Guardar `timestampInicio` en `localStorage.setItem('fitcore_timer', ...)`.
- **Pregunta de Control:** ¿Por qué un cronómetro que suma `segundos++` con `setInterval` falla al cambiar de pestaña en el móvil, y por qué la resta de timestamps de reloj (`Date.now() - inicio`) es inmune a ese problema?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 3.3.2:** *Rendimiento y Tolerancia a Fallos* y **Sección 7.8:** *Vista de entrenamiento*.
  - Documentar la arquitectura del cronómetro deportivo tolerante a suspensiones del navegador.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Cronómetro Deportivo Resistente a Interrupciones"*.
  - **Visual:** Gráfico comparativo de precisión: `setInterval` con retardo acumulado vs. Delta de Epoch Timestamps.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"Los teléfonos móviles suspenden la ejecución de JavaScript en segundo plano para ahorrar batería. Para evitar que el cronómetro se congele al cambiar de aplicación, en FITCORE nos basamos en marcas de tiempo reales (timestamps). El tiempo transcurrido es una diferencia matemática exacta."*

---

## JORNADA 13: Cierre de Sesión y Registro Atómico de Series (`series_registro`)

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 3.4.1:** Analizar en `fitcore_schema.sql` la tabla `series_registro`.
  - **Sub-paso 3.4.1.1:** Observar las columnas: `sesion_id`, `ejercicio_id`, `numero_serie`, `peso_kg`, `repeticiones`.
  - **Sub-paso 3.4.1.2:** Comprender la restricción: `UNIQUE (sesion_id, ejercicio_id, numero_serie)`.
  - **Sub-paso 3.4.1.3:** Diseñar el flujo de finalización: calcular `duracion_segundos` total, actualizar `sesiones_entrenamiento` a `estado = 'completada'` y borrar el timer del `localStorage`.
- **Pregunta de Control:** ¿Por qué la restricción UNIQUE en `series_registro` garantiza que si el usuario presiona dos veces seguidas el botón de guardar serie no se dupliquen los datos?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 4.5.7:** *CU-07 Registrar series* y **Sección 6.5.10:** *Entidad series_registro*.
  - Documentar la atomicidad del guardado de series y la finalización formal de la sesión.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Registro Deportivo Atómico: series_registro"*.
  - **Visual:** Estructura relacional conectando `sesiones_entrenamiento` con múltiples `series_registro`.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"El MVP 3 sella la validez de los datos de entrenamiento. Cada serie ejecutada por el atleta se almacena de forma única en `series_registro`. Al concluir, la sesión cambia de `'en_progreso'` a `'completada'`, dejando el camino libre para el otorgamiento de experiencia."*

---

# FASE IV: MVP 4 — GAMIFICACIÓN, XP Y EFECTO DOMINÓ

---

## JORNADA 14: El Disparador de Recompensas y la Tabla `xp_historial`

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 4.1.1:** Analizar en `fitcore_schema.sql` la tabla `xp_historial`.
  - **Sub-paso 4.1.1.1:** Observar las columnas: `usuario_id`, `xp_ganado`, `motivo`, `created_at`.
  - **Sub-paso 4.1.1.2:** Observar la restricción: `CHECK (motivo IN ('sesion_completada', 'ejercicio_nuevo', 'racha_diaria'))`.
  - **Sub-paso 4.1.1.3:** Comprender que al finalizar el entrenamiento con éxito en el backend, se ejecuta:
    1. `INSERT INTO xp_historial (usuario_id, xp_ganado, motivo) VALUES (?, 100, 'sesion_completada')`
    2. `UPDATE usuarios SET xp_total = xp_total + 100 WHERE id = ?`
- **Pregunta de Control:** ¿Por qué nunca se debe sumar XP directamente en la tabla `usuarios` sin registrar el motivo en `xp_historial`?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 4.5.9:** *CU-09 Obtener XP* y **Sección 6.5.4:** *Entidad xp_historial*.
  - Explicar el principio contable de auditoría inmutable en el sistema de recompensas.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"Auditoría Inmutable de Recompensas: xp_historial"*.
  - **Visual:** Diagrama de libro contable: Evento completado $\rightarrow$ Registro en `xp_historial` $\rightarrow$ Acumulación en `usuarios.xp_total`.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"En FITCORE los puntos de experiencia no son un número suelto editable. Funcionan como una cuenta bancaria con libro contable: cada ganancia de XP queda registrada con su fecha y motivo en `xp_historial`, garantizando auditoría total ante cualquier reclamo de progreso."*

---

## JORNADA 15: El Motor de Ascenso de Nivel (Evaluación contra `niveles`)

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 4.2.1:** Analizar en `fitcore_schema.sql` la tabla `niveles`.
  - **Sub-paso 4.2.1.1:** Observar las columnas: `numero_nivel (UNIQUE)`, `nombre`, `xp_requerido`.
  - **Sub-paso 4.2.1.2:** Comprender la consulta SQL de evaluación:
    `SELECT numero_nivel FROM niveles WHERE xp_requerido <= :xp_total ORDER BY numero_nivel DESC LIMIT 1`.
  - **Sub-paso 4.2.1.3:** Si el nivel resultante es mayor que `usuarios.nivel_actual`, actualizar: `UPDATE usuarios SET nivel_actual = :nuevo_nivel`.
- **Pregunta de Control:** ¿Por qué desacoplar los niveles en una tabla propia en lugar de escribirlos como `if (xp > 500) nivel = 2` en el código PHP permite al administrador cambiar las reglas del juego fácilmente?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 6.5.1:** *Entidad niveles* y **Sección 8.13:** *Lógica de XP*.
  - Documentar el algoritmo de evaluación de rangos contra la tabla paramétrica `niveles`.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"El Motor de Niveles Basado en Datos"*.
  - **Visual:** Escalera de niveles con sus nombres y umbrales de XP en la tabla `niveles`.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"El motor de progresión de FITCORE está desacoplado del código fuente. Los umbrales de experiencia residen en la tabla `niveles`. Tras cada entrenamiento, el sistema evalúa el XP total acumulado contra la tabla y promueve automáticamente al atleta."*

---

## JORNADA 16: El Efecto Dominó en la Interfaz (Desbloqueo de Candados en Vivo)

### 💻 08:30 – 09:10 | Parte Práctica (Código y Archivos - 40 min)
- **Paso 4.3.1:** Conectar la subida de nivel con el Store y las vistas.
  - **Sub-paso 4.3.1.1:** Cuando el backend responde que el usuario subió a Nivel 2, actualizar `authStore.usuario.nivel_actual = 2`.
  - **Sub-paso 4.3.1.2:** Comprobar que en `BarraUsuario.vue` y `Main.vue` el texto ahora refleja Nivel 2.
  - **Sub-paso 4.3.1.3:** Navegar a la lista de rutinas: observar cómo las rutinas que pedían Nivel 2 ahora tienen `bloqueada = false`, desaparece el candado y se activa el botón sin necesidad de recargar la página (F5).
- **Pregunta de Control:** ¿Por qué este comportamiento reactivo demuestra que el Store centralizado conecta armoniosamente el MVP 1, MVP 2, MVP 3 y MVP 4?

### 📝 09:10 – 09:40 | Parte Documental (Avanzando en el Índice - 30 min)
- **Sección 5.2:** *Arquitectura general* y **Sección 7.1:** *Principios generales de interfaz*.
  - Documentar la culminación de la arquitectura SPA de FITCORE: el efecto dominó reactivo.

### 🎤 09:40 – 10:30 | PowerPoint y Ensayo Oral (50 min)
- **🎨 09:40 – 10:10 | Diapositiva del día (30 min):**
  - **Título:** *"La Coronación del Sistema: El Efecto Dominó en Tiempo Real"*.
  - **Visual:** Esquema de integración: Sesión finalizada $\rightarrow$ XP sumado $\rightarrow$ Nivel aumentado en Store $\rightarrow$ Candado abierto en catálogo.
- **🗣️ 10:10 – 10:30 | Ensayo de Defensa (20 min):**
  - *"Este es el gran valor de FITCORE: la sincronía reactiva. Al terminar un entrenamiento, el XP ganado asciende al atleta. Al regresar al catálogo, las rutinas de mayor nivel aparecen mágicamente desbloqueadas sin recargar la página. Cada pieza del sistema conversa con las demás a través de una arquitectura coherente y desacoplada."*

---

# FASE V: PRUEBAS, PULIDO Y ENSAYOS DE DEFENSA FINAL

---

## JORNADA 17: Batería Integral de Pruebas (CP-01 al CP-08)
- 💻 **08:30 – 09:10 (40 min):** Ejecutar los 8 casos de prueba formales documentados en la Sección 11.5 del índice. Probar intencionalmente contraseñas erróneas, emails duplicados y navegación sin sesión.
- 📝 **09:10 – 09:40 (30 min):** Completar la Sección 11.7 (*Resultados*) y 11.8 (*Evidencias*) con las capturas de peticiones exitosas en Chrome DevTools y registros SQL.
- 🎨 **09:40 – 10:10 (30 min):** Diseñar Filmina: *"Matriz de Pruebas y Aseguramiento de Calidad"*.
- 🗣️ **10:10 – 10:30 (20 min):** Ensayo oral enfocado en cómo el sistema resiste entradas maliciosas y fallos de conexión.

## JORNADA 18: Optimización de Base de Datos y Verificación de Índices
- 💻 **08:30 – 09:10 (40 min):** Ejecutar consultas `EXPLAIN ANALYZE` en PostgreSQL para verificar que los índices `idx_sesiones_auth_token` y `idx_usuarios_nivel` optimicen las búsquedas.
- 📝 **09:10 – 09:40 (30 min):** Completar la Sección 6.14 (*Índices*) y 6.15 (*Decisiones de diseño de BD*).
- 🎨 **09:40 – 10:10 (30 min):** Diseñar Filmina: *"Escalabilidad y Rendimiento en PostgreSQL"*.
- 🗣️ **10:10 – 10:30 (20 min):** Ensayo oral sobre cómo la base de datos soporta miles de transacciones concurrentes mediante índices B-Tree.

## JORNADA 19: Pulido Visual y Accesibilidad de la Interfaz
- 💻 **08:30 – 09:10 (40 min):** Revisar micro-interacciones, transiciones de CSS, tipografía Rubik y estados de carga (`disabled` en botones durante peticiones HTTP).
- 📝 **09:10 – 09:40 (30 min):** Completar la Sección 7.9 (*Mensajes y validaciones*) y 7.10 (*Prototipos*).
- 🎨 **09:40 – 10:10 (30 min):** Diseñar Filmina: *"Experiencia de Usuario (UX/UI): Diseño Oscuro y Ergonomía Deportiva"*.
- 🗣️ **10:10 – 10:30 (20 min):** Ensayo oral enfocado en la interfaz pensada para el uso en el gimnasio.

## JORNADA 20: Ensayo General de la Presentación Oral (Frente a la Mesa Examinadora)
- 💻 **08:30 – 09:10 (40 min):** Despliegue en vivo: arrancar Apache, PostgreSQL y Vite. Probar el recorrido completo en 5 minutos: Registro $\rightarrow$ Login $\rightarrow$ Perfil $\rightarrow$ Agenda $\rightarrow$ Entrenamiento $\rightarrow$ Ascenso $\rightarrow$ Desbloqueo.
- 📝 **09:10 – 09:40 (30 min):** Revisión final de ortografía, enlaces de archivo y formato de impresión de [DOCUMENTACION_TECNICA_FITCORE.md](file:///c:/Users/lucas/ObsidiantFit/archivos%20md/DOCUMENTACION_TECNICA_FITCORE.md).
- 🎨 **09:40 – 10:10 (30 min):** Revisión y orden de las 15 filminas principales en PowerPoint.
- 🗣️ **10:10 – 10:30 (20 min):** Simulación formal de 20 minutos de defensa completa sin interrupciones con cronómetro en mano.

---
*Plan de Batalla Diario Oficial adaptado a la arquitectura real de FITCORE — 06 de Noviembre de 2026.*
