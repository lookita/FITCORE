# FITCORE — Plan Maestro de MVPs: Análisis Profundo y Hoja de Ruta

> **Diagnóstico inicial de tu arquitectura:**
> Ya lograste lo que a la mayoría de los principiantes les cuesta semanas: conectar un formulario en Vue/TypeScript con un backend PHP en Apache y validar la entrada con servicios.
>
> Sin embargo, hoy tienes una **desconexión invisible**: tus piezas funcionan aisladas, pero todavía no se pasan la posta entre sí.
> Este documento es tu mapa de ingeniería: **sin darte código ni soluciones masticadas**, te da la radiografía exacta de dónde estás parado, qué le falta a tu sistema para cobrar vida y cuál es el camino mental para construir cada MVP por tu propia cuenta.

---

## 1. Radiografía profunda de tu proyecto hoy

Analizando los archivos reales de tu proyecto (`LoginView.vue`, `PerfilView.vue`, `autenticar.service.ts`, `BarraUsuario.vue`, `Main.vue` y tu schema de base de datos), este es el estado exacto de tu software:

### Lo que ya tienes conquistado y funcionando
1. **Frontend estructurado**: Tienes vistas separadas (`InicioView`, `LoginView`, `RegistroView`, `PerfilView`), router configurado y componentes divididos.
2. **Capa de transporte**: `autenticar.service.ts` y `PasoService.ts` encapsulan la petición HTTP, validan JSON y atrapan errores de red.
3. **Backend y Base de datos listos**: PHP procesa el login y tu schema PostgreSQL tiene tablas de niveles, sesiones, rutinas, agenda y series.

### Las 4 fisuras invisibles que debes resolver en el MVP 1
1. **La sesión atrapada en una sola vista**:
   - En `LoginView.vue`, cuando el backend responde exitoso, guardas los datos en un `reactive<EstadoSesion>` local.
   - **El problema**: Esa variable solo vive dentro de `LoginView`. Cuando el usuario se va a `/perfil`, `LoginView` se destruye de la memoria y el dato del usuario muere con ella.
2. **Las carpetas fantasma (`stores/` y `composables/`)**:
   - Ambas carpetas existen en tu árbol de archivos pero están totalmente vacías.
   - Toda aplicación que maneja sesión necesita una **memoria central** (Store). Sin ella, ninguna otra vista puede saber quién está logueado.
3. **La ilusión de PerfilView**:
   - Si abres `BarraUsuario.vue` o `Main.vue`, verás que `"Lucas Mercado"`, `"Racha activa - 6 dias"` y `"NIVEL 10"` están escritos a mano como texto estático (hardcoded).
   - Tu perfil todavía no sabe escuchar a la base de datos porque nadie le está inyectando el usuario que acaba de loguearse.
4. **El contrato incompleto en `Auth.DTO.ts`**:
   - Tu interfaz `Usuario` actual solo tiene `id`, `nombre` y `email`.
   - Pero tu tabla de base de datos `usuarios` ya tiene `nivel_actual` y `xp_total`. Si el backend no te los devuelve y tu DTO no los contempla, tu frontend está ciego respecto al nivel del usuario desde el día uno.

---

## 2. La regla de los cuatro bloques para cada MVP

Para no marearte nunca más entre Vue, PHP, BD, DTO y Stores, cada pantalla o funcionalidad que construyas se divide siempre en estos **4 bloques mentales**:

```
[BLOQUE 1: Persistencia]   --> ¿Qué tabla y columnas guardan esto en PostgreSQL?
           ↓
[BLOQUE 2: Backend PHP]    --> ¿Qué consulta SQL hace, qué valida y qué JSON responde?
           ↓
[BLOQUE 3: Memoria Viva]   --> ¿Este dato lo necesita una sola vista (variable local)
                                o lo necesitan varias (Store global)?
           ↓
[BLOQUE 4: Presentación]   --> ¿Cómo reacciona la View cuando el dato llega, 
                                cambia o da error?
```

Si respetas este orden de abajo hacia arriba en cada paso, nunca te vas a bloquear.

---

## 3. MVP 1 — Autenticación, Sesión Global y Perfil Dinámico

> **Estado actual**: Formulario hecho y conectado al PHP.  
> **Meta para darlo por finalizado**: Que el usuario inicie sesión, sea redirigido automáticamente a su perfil, y su perfil muestre su nombre real, su nivel inicial (1) y su ID desde la base de datos.

### El flujo mental que debes armar

```
Formulario Login
       ↓ (envía credenciales)
AuthService (petición POST a login.php)
       ↓ (recibe token, id, nombre, nivel_actual, xp_total)
Store Global (guarda el usuario en la memoria de toda la aplicación)
       ↓ (redirección con router)
PerfilView
       ├── BarraUsuario (lee del Store: muestra nombre y racha real)
       └── Main (lee del Store: muestra saludo con nombre y nivel real)
```

### Conceptos clave que tienes que investigar y razonar:
1. **Memoria compartida (Store)**:
   - ¿Cómo hacer que un dato que capturaste en la pantalla A esté disponible en la pantalla B sin tener que volver a pedirlo al servidor?
   - Si el usuario presiona F5 (recarga la página), la memoria RAM de JavaScript se reinicia. ¿Dónde puedes almacenar temporalmente el token o ID para que no tenga que loguearse en cada recarga? *(Pista conceptual: almacenamiento local del navegador)*.
2. **Redirección programática**:
   - Una vez que la promesa del login se resuelve con `exito: true`, el usuario no debería quedarse mirando el formulario. El router debe empujarlo automáticamente a la vista de perfil.
3. **El guardián de rutas (Navigation Guards)**:
   - ¿Qué pasa si alguien abre el navegador y escribe `http://localhost:5173/perfil` sin haberse logueado?
   - Tu router necesita una regla que pregunte: *"¿Hay usuario en el Store? Si no hay, mándalo a `/login`"*.
4. **Desconexión / Logout**:
   - ¿Qué significa cerrar sesión? Limpiar el Store, limpiar el almacenamiento local y mandar al usuario a la pantalla de entrada.

### Lista de preguntas para saber si tu MVP 1 está 100% terminado:
- [ ] ¿Al registrar un usuario nuevo en PostgreSQL se crea con `nivel_actual = 1` y `xp_total = 0`?
- [ ] ¿El endpoint `login.php` devuelve en su JSON el `id` (UUID), `nombre`, `email`, `nivel_actual` y `xp_total`?
- [ ] ¿Tu interfaz `Usuario` en TypeScript refleja exactamente esos campos?
- [ ] ¿El resultado del login se guarda en un Store accesible desde cualquier componente?
- [ ] ¿Al loguearse con éxito, la aplicación navega sola a `/perfil`?
- [ ] ¿`BarraUsuario.vue` y `Main.vue` muestran el nombre y nivel real del usuario que inició sesión, sin ningún dato hardcodeado?
- [ ] ¿Si el usuario cierra sesión, el perfil ya no es accesible?

---

## 4. MVP 2 — Catálogo de Ejercicios, Rutinas y el Sistema de Candados

> **El salto**: Ahora que tienes `usuario.id` y `usuario.nivel_actual` en tu Store, puedes construir el primer contenido real de entrenamiento.

### Tablas involucradas de tu schema:
- `ejercicios`: Catálogo general (con `grupo_muscular` y `creado_por_id`).
- `rutinas`: Las rutinas existentes (con `nivel_requerido` y `usuario_id`).
- `rutina_ejercicios`: La relación que une qué ejercicios van en qué rutina, su `orden`, `series_objetivo`, `reps_objetivo` y `descanso_segundos`.

### Las tres mecánicas que debes diseñar:

#### 1. Ejercicios del sistema vs. Ejercicios del usuario
- En tu tabla `ejercicios`, la columna `creado_por_id` puede ser `NULL` o tener un UUID.
- **La lógica**: Los ejercicios donde `creado_por_id IS NULL` son los oficiales de FitCore (banco plano, sentadilla, etc.) y los ven todos. Si un usuario crea un ejercicio personalizado, lleva su `usuario.id` y solo lo ve él.

#### 2. La regla del Candado (Bloqueo por nivel)
- Cada rutina tiene un `nivel_requerido` (número entero: 1, 2, 3...).
- En tu Store ya vive `usuario.nivel_actual`.
- **La comparación**: 
  - Si `usuario.nivel_actual >= rutina.nivel_requerido` → La rutina está **desbloqueada** (accesible, clickable).
  - Si `usuario.nivel_actual < rutina.nivel_requerido` → La rutina tiene **candado visual** y no permite ingresar a entrenarla. Muestra un mensaje: *"Requiere nivel X"*.

#### 3. El detalle estructurado de una rutina
- Una rutina no es solo un título; es una lista ordenada de ejercicios con metas claras.
- Cuando el usuario toca una rutina desbloqueada, el backend debe entregarle no solo el nombre de la rutina, sino la lista de sus ejercicios ordenados por la columna `orden`, con sus series objetivo, repeticiones objetivo y segundos de descanso.

### Lista de preguntas para saber si tu MVP 2 está 100% terminado:
- [ ] ¿Tienes una vista para listar todas las rutinas disponibles?
- [ ] ¿Las rutinas que piden un nivel superior al del usuario actual muestran un candado o están deshabilitadas?
- [ ] ¿Al hacer clic en una rutina desbloqueada se abre una vista de detalle (`RutinaDetalleView`) con sus ejercicios en orden?
- [ ] ¿Cada ejercicio muestra cuántas series y repeticiones tiene como objetivo?

---

## 5. MVP 3 — Agenda Semanal y el Motor de Entrenamiento en Vivo

> **El salto**: Pasar de "ver rutinas" a "ejecutar un entrenamiento real". Este es el corazón de la aplicación.

### Tablas involucradas de tu schema:
- `agenda_semanal`: Qué rutina le toca al usuario cada día (`dia_semana`: 1 = Lunes a 7 = Domingo).
- `sesiones_entrenamiento`: La sesión activa (`fecha`, `duracion_segundos`, `estado`, `xp_otorgado`).
- `series_registro`: Cada serie física que el usuario realiza (`peso_kg`, `repeticiones`, `completada`).

### El ciclo de vida de un entrenamiento:

```
Paso A: Consulta de Agenda
   El usuario entra a su agenda.
   El sistema mira qué día de la semana es hoy (1 al 7).
   Busca en agenda_semanal si tiene una rutina asignada para ese día.
        ↓
Paso B: Inicio de Sesión
   El usuario pulsa "Comenzar Entrenamiento".
   El backend crea una fila en sesiones_entrenamiento con:
   - estado = 'en_progreso'
   - fecha = NOW()
   - rutina_id correspondiente
   El frontend recibe el id de esa sesión recién nacida.
        ↓
Paso C: Entrenamiento en Vivo (Series)
   Por cada ejercicio de la rutina, el usuario va realizando series.
   Escribe el peso que levantó (peso_kg) y las repeticiones logradas.
   Marca el checkbox "completada = true".
   Se guarda la fila en series_registro vinculada al sesion_id.
        ↓
Paso D: Cierre de Sesión
   El usuario presiona "Finalizar Entrenamiento".
   El cronómetro se detiene.
   El backend actualiza la sesión:
   - duracion_segundos = tiempo total transcurrido
   - estado = 'completada'
```

### Por qué aquí el Store es crítico:
- Mientras un usuario entrena, puede cambiar de pantalla, mirar un ejercicio en otra pestaña o atender una llamada.
- Si guardas el entrenamiento activo en una variable común de Vue, al cambiar de pantalla se reinicia el reloj y se pierden las series anotadas.
- **Tu desafío**: El estado del entrenamiento en curso (ID de sesión, hora de inicio, series marcadas) debe vivir en un Store (`EntrenamientoStore`) para que persista mientras la aplicación siga abierta.

### Lista de preguntas para saber si tu MVP 3 está 100% terminado:
- [ ] ¿El usuario puede ver qué rutina le toca hoy según el día de la semana?
- [ ] ¿Existe un botón para iniciar entrenamiento que cree la sesión con estado `'en_progreso'` en la base de datos?
- [ ] ¿La pantalla de entrenamiento permite ingresar peso y repeticiones por cada serie?
- [ ] ¿Hay un temporizador o cálculo de duración de la sesión?
- [ ] ¿Al finalizar, la sesión pasa a estado `'completada'` con su duración total en segundos?

---

## 6. MVP 4 — Gamificación: XP, Historial y el Efecto Dominó

> **El salto**: Recompensar el esfuerzo físico. Conectar la finalización del entrenamiento con el crecimiento del personaje del usuario.

### Tablas involucradas de tu schema:
- `niveles`: El catálogo de niveles (`numero_nivel`, `nombre`, `xp_requerido`).
- `xp_historial`: Registro inmutable con auditoría (`usuario_id`, `xp_ganado`, `motivo`).
- `usuarios`: Los totales actualizados (`xp_total`, `nivel_actual`).

### El circuito de recompensa:
1. **El disparador**: Cuando la sesión del MVP 3 pasa a `'completada'`, se activa la entrega de puntos.
2. **El registro inmutable**: Se inserta un evento en `xp_historial`:
   - `xp_ganado` = cantidad de puntos (ej: 100).
   - `motivo` = `'sesion_completada'`.
3. **La acumulación**: Se suma ese XP al campo `xp_total` del usuario en la tabla `usuarios`.
4. **La evaluación de ascenso**:
   - El sistema consulta la tabla `niveles`.
   - Si el nuevo `xp_total` del usuario supera el `xp_requerido` del siguiente nivel:
     - Se actualiza `usuarios.nivel_actual` al nuevo nivel.
5. **El efecto dominó en el Frontend**:
   - El Store del usuario se actualiza con su nuevo nivel y nuevo XP total.
   - En `PerfilView`, la barra de progreso avanza.
   - Si el usuario va a `RutinasView` (MVP 2), **las rutinas que antes tenían candado ahora aparecen mágicamente desbloqueadas**, porque la condición `usuario.nivel_actual >= rutina.nivel_requerido` ahora se cumple.

### La matemática que debes resolver en tu Composable de Progreso:
- Para dibujar una barra de progreso que vaya del 0% al 100% entre tu nivel actual y el próximo, necesitas pensar:
  - ¿Cuánto XP pide mi nivel actual?
  - ¿Cuánto XP pide el siguiente nivel?
  - ¿Cuánto XP tengo acumulado entre esos dos puntos?
  - ¿Qué porcentaje representa eso?

### Lista de preguntas para saber si tu MVP 4 está 100% terminado:
- [ ] ¿Al terminar un entrenamiento se registra la fila correspondiente en `xp_historial`?
- [ ] ¿El `xp_total` del usuario aumenta en la base de datos?
- [ ] ¿Si el XP acumulado alcanza para el siguiente nivel, `nivel_actual` se actualiza automáticamente?
- [ ] ¿El perfil muestra una barra visual de progreso hacia el siguiente nivel?
- [ ] ¿Al subir de nivel se desbloquean las rutinas del MVP 2 que estaban bloqueadas?

---

## 7. Mapa de dependencias: El orden absoluto de construcción

Para no perderte jamás, este es el árbol de dependencias de FitCore:

```
[PASO 1: MVP 1]
Crear el AuthStore y conectar LoginView con PerfilView.
Conseguir que el ID, el nombre y el nivel del usuario viajen vivos por la app.
                    ↓
[PASO 2: MVP 2]
Crear el catálogo de rutinas y ejercicios.
Usar el nivel_actual del usuario (del MVP 1) para prender o apagar los candados.
                    ↓
[PASO 3: MVP 3]
Crear la agenda y la pantalla de entrenamiento en vivo.
Usar las rutinas (del MVP 2) y el usuario.id (del MVP 1) para guardar series reales.
                    ↓
[PASO 4: MVP 4]
Premiar la sesión completada (del MVP 3) con XP.
Aumentar el nivel del usuario, lo que a su vez desbloquea nuevas rutinas (del MVP 2).
```

---

## 8. Tu próximo paso concreto para hoy

No mires las rutinas, ni el XP, ni la agenda todavía. Tu objetivo inmediato para cerrar el MVP 1 es responder esta única pregunta práctica:

> **"Cuando hago login exitoso en `LoginView`, ¿cómo hago para que `PerfilView` conozca el nombre y el ID de ese usuario sin tener nada escrito a mano en el HTML?"**

En cuanto resuelvas ese puente con un Store central, todo el resto de FitCore fluirá de forma natural. 🚀

---

## 9. FITCORE — Micro-Learning & Atomic Tasks (Desglose Atómico sin Código)

Este desglose traduce tu plan maestro en micro-tareas atómicas. Cada bloque representa una unidad mental indivisible: no tienes que escribir código, sino entender el concepto, la lógica y el propósito de cada pieza antes de armarla por tu cuenta.

### BLOQUE 1: Diagnóstico y Consciencia de Estado (MVP 1)
- **Micro-tarea 1.1: Entender el problema de la "Sesión Atrapada"**
  - **Concepto**: Una variable declarada dentro de un componente de Vue (`LoginView`) muere en cuanto el usuario cambia de ruta, porque la vista se destruye de la memoria.
  - **Pregunta clave para entenderlo**: ¿Por qué un dato capturado en un formulario de login desaparece al navegar hacia otra página si no se almacena en un lugar global?

- **Micro-tarea 1.2: El rol conceptual del Store (Pinia)**
  - **Concepto**: El Store funciona como una "memoria RAM centralizada e independiente de las vistas". No pertenece a ninguna pantalla en particular, por lo que cualquier vista puede consultarlo o modificarlo.
  - **Pregunta clave para entenderlo**: ¿Cómo actúa una variable global reactiva para que el `PerfilView` pueda leer el nombre del usuario sin importar desde qué ruta haya llegado?

- **Micro-tarea 1.3: La persistencia ante el F5 (LocalStorage)**
  - **Concepto**: La memoria RAM del navegador se borra si el usuario recarga la página. Para evitar que la sesión muera al presionar F5, se utiliza un disco de almacenamiento temporal del navegador.
  - **Pregunta clave para entenderlo**: ¿En qué momento exacto del login debes guardar una copia de respaldo en el navegador y cuándo debe leerse al encender la app?

- **Micro-tarea 1.4: Contratos de Datos (DTOs Actualizados)**
  - **Concepto**: Las interfaces de TypeScript deben reflejar exactamente la realidad de la base de datos, no una versión incompleta.
  - **Pregunta clave para entenderlo**: ¿Por qué tu interfaz de usuario necesita conocer obligatoriamente el `nivel_actual` y el `xp_total` desde el primer segundo de la sesión?

---

### BLOQUE 2: Autenticación, Guardias y Perfil Dinámico (MVP 1 Fin)
- **Micro-tarea 2.1: El flujo de redirección programática**
  - **Concepto**: El enrutador de la aplicación (Vue Router) puede controlar los cambios de pantalla mediante código tras una respuesta exitosa del servidor.
  - **Pregunta clave para entenderlo**: ¿Qué condición lógica debe cumplirse para que el sistema decida empujar al usuario desde el formulario de login hacia el panel de perfil?

- **Micro-tarea 2.2: Guardianes de Ruta (Navigation Guards)**
  - **Concepto**: Son reglas de seguridad perimetrales que interceptan al navegador antes de permitirle renderizar una vista privada.
  - **Pregunta clave para entenderlo**: ¿Cómo evalúa el router si un visitante anónimo intenta entrar por la URL a `/perfil` y cómo decide bloquearle el paso?

- **Micro-tarea 2.3: Limpieza de Estado en el Logout**
  - **Concepto**: Cerrar sesión no es solo cambiar de vista; implica destruir los rastros de identidad en la aplicación.
  - **Pregunta clave para entenderlo**: ¿Qué elementos de la memoria global y del navegador deben borrarse obligatoriamente para garantizar que el usuario actual ya no existe en el sistema?

---

### BLOQUE 3: Catálogo, Relaciones y Sistema de Candados (MVP 2)
- **Micro-tarea 3.1: Segmentación de Ejercicios (Sistema vs. Usuario)**
  - **Concepto**: Las bases de datos relacionales permiten distinguir entre elementos globales (creados por la plataforma para todos) y elementos privados (creados por un usuario específico).
  - **Pregunta clave para entenderlo**: ¿Cómo utiliza la columna `creado_por_id` una consulta SQL para decidir qué ejercicios mostrarle a un usuario en particular?

- **Micro-tarea 3.2: La Lógica del Candado (Comparación de Niveles)**
  - **Concepto**: La interfaz visual puede cambiar de comportamiento evaluando una simple inecuación matemática entre el nivel del usuario y el requisito de la rutina.
  - **Pregunta clave para entenderlo**: ¿Qué ocurre visualmente en una tarjeta de rutina si el valor numérico del nivel del usuario es menor que el nivel exigido por esa rutina?

- **Micro-tarea 3.3: Estructura Jerárquica de una Rutina**
  - **Concepto**: Una rutina compleja se compone de una relación de 1 a muchos con los ejercicios, ordenados secuencialmente mediante una tabla intermedia.
  - **Pregunta clave para entenderlo**: ¿Por qué el backend debe enviar los ejercicios ordenados por la columna `orden` y acompañados de sus metas de series y repeticiones?

---

### BLOQUE 4: Agenda Semanal y Ciclo de Vida del Entrenamiento (MVP 3)
- **Micro-tarea 4.1: Mapeo de Días en la Agenda**
  - **Concepto**: El sistema debe relacionar el día actual del calendario con la planificación semanal asignada al usuario.
  - **Pregunta clave para entenderlo**: ¿Cómo traduce el sistema el número del día actual de la semana (1 al 7) para consultar la tabla `agenda_semanal`?

- **Micro-tarea 4.2: Nacimiento de una Sesión Activa**
  - **Concepto**: Iniciar un entrenamiento equivale a registrar formalmente el nacimiento de un evento en curso con un estado específico en la base de datos.
  - **Pregunta clave para entenderlo**: ¿Qué datos mínimos necesita la tabla `sesiones_entrenamiento` al insertar una fila con estado `'en_progreso'` al pulsar el botón de inicio?

- **Micro-tarea 4.3: Persistencia del Entrenamiento en Vivo (Store Crítico)**
  - **Concepto**: Durante la ejecución física, el estado de las series y el cronómetro no pueden residir en una sola vista, porque el usuario podría cambiar de pestaña accidentalmente.
  - **Pregunta clave para entenderlo**: ¿Qué información debe retener el `EntrenamientoStore` para que el cronómetro no se reinicie si el usuario navega fuera de la pantalla de entrenamiento por unos segundos?

- **Micro-tarea 4.4: Cierre de Sesión y Métricas Finales**
  - **Concepto**: Un entrenamiento concluye calculando el tiempo transcurrido total y cambiando el estado de la sesión a completada.
  - **Pregunta clave para entenderlo**: ¿Qué parámetros finales actualiza el backend en la base de datos cuando el usuario presiona el botón de finalizar?

---

### BLOQUE 5: Gamificación, XP y Efecto Dominó (MVP 4)
- **Micro-tarea 5.1: El Disparador de Recompensas**
  - **Concepto**: La finalización exitosa de un proceso operativo en un módulo (MVP 3) actúa como evento disparador (trigger) para iniciar la lógica de premios en otro módulo (MVP 4).
  - **Pregunta clave para entenderlo**: ¿En qué momento exacto del ciclo de vida de una sesión completada el sistema decide que el usuario ha ganado puntos de experiencia?

- **Micro-tarea 5.2: Auditoría Inmutable (`xp_historial`)**
  - **Concepto**: Las tablas de historial de puntos funcionan como libros contables que registran cada ganancia de forma individual y auditable, sin sobreescribir datos anteriores.
  - **Pregunta clave para entenderlo**: ¿Por qué es necesario insertar un registro nuevo en `xp_historial` en lugar de simplemente alterar un número suelto en la tabla de usuarios?

- **Micro-tarea 5.3: El Motor de Ascenso de Nivel**
  - **Concepto**: La evaluación de rangos compara el acumulado total de puntos del usuario contra los umbrales definidos en la tabla de niveles.
  - **Pregunta clave para entenderlo**: ¿Qué condición lógica evalúa el sistema para decidir si el `nivel_actual` del usuario debe incrementarse en una unidad?

- **Micro-tarea 5.4: El Efecto Dominó en la Interfaz**
  - **Concepto**: El ascenso de nivel de un usuario altera de manera directa las restricciones de acceso en otras secciones de la aplicación.
  - **Pregunta clave para entenderlo**: ¿Por qué subir de nivel en el perfil desbloquea automáticamente las rutinas con candado en el catálogo del MVP 2 sin necesidad de reiniciar la app?
