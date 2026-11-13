# Guion extenso para explicar FITCORE

## Cómo usar este guion

Este documento está escrito para ser dicho en voz alta. No es una lista de diapositivas ni un manual de instalación: es una explicación narrativa del producto, del recorrido del usuario, de las decisiones técnicas y de la organización del proyecto. La intención es que puedas entender la historia completa y contarla sin memorizar términos sueltos.

Las indicaciones entre corchetes, como `[mostrar la pantalla]` o `[hacer una pausa]`, son recordatorios para el ensayo; no se leen en la exposición.

El guion separa deliberadamente tres ideas:

- Lo que el programa permite hacer actualmente.
- Lo que existe como interfaz, prototipo, documento de diseño o experimento local.
- Lo que está diseñado o previsto, pero todavía no está conectado de extremo a extremo.

Esa diferencia es importante. Por ejemplo, que la base de datos tenga un campo llamado `semana` no significa que la aplicación ya permita avanzar de semana; que el dashboard tenga publicaciones no significa que esas publicaciones se guarden en el servidor; y que un componente muestre XP no significa que todas sus cifras estén sincronizadas con una única regla de negocio.

El texto se puede adaptar al tiempo disponible. Para una exposición breve se pueden leer la apertura, los flujos principales, las limitaciones y el cierre. Para una defensa técnica se pueden desarrollar todos los capítulos y las explicaciones de archivos.

---

# Guion oral

## Apertura: presentar el proyecto

> “Buenos días. Hoy voy a presentar FITCORE, un proyecto de aplicación web orientado al entrenamiento de calistenia y a la organización del progreso de una persona que entrena.
>
> La idea de FITCORE es acompañar al usuario en un recorrido: primero crea una cuenta, después configura los días en los que quiere entrenar, consulta una rutina, realiza una sesión y registra lo que hizo. A partir de esas sesiones, el sistema puede guardar actividad y sumar experiencia, o XP, al usuario.
>
> No quiero presentar FITCORE como si fuera solamente un conjunto de pantallas. Lo voy a explicar como un sistema completo: qué problema intenta resolver, qué ve la persona, qué ocurre cuando interactúa con la aplicación, cómo se comunican el frontend y el backend, qué información guarda la base de datos y qué partes siguen en desarrollo.
>
> También voy a distinguir claramente entre las funciones que ya puedo demostrar y las que forman parte de la evolución del proyecto. Por ejemplo, la experiencia y los niveles aparecen en el código, pero la transición automática entre semanas aún no está terminada.”

[Pausa breve. Mirar al público y luego iniciar la presentación.]

## Qué es FITCORE y por qué existe

> “FITCORE nace de una necesidad sencilla: cuando una persona empieza a entrenar, especialmente si recién se acerca a la calistenia, puede costarle decidir qué hacer, qué días entrenar, qué ejercicios elegir y cómo recordar su avance.
>
> Una persona puede tener motivación, pero si no tiene una estructura, muchas veces termina improvisando, repitiendo ejercicios sin un plan o dejando de registrar lo que realizó. FITCORE intenta reunir en una misma aplicación algunas herramientas para organizar ese proceso.
>
> El público principal son personas que quieren empezar o mantener una práctica de entrenamiento y necesitan una forma guiada de organizar días, ejercicios y sesiones. El proyecto no reemplaza a un entrenador, no evalúa médicamente al usuario y no pretende diagnosticar ni prescribir tratamientos. Es una herramienta de organización, seguimiento y motivación.
>
> La parte de gamificación busca que el progreso sea visible. En vez de registrar solamente una lista de ejercicios, FITCORE asocia una sesión con experiencia y con un nivel. La intención es que el usuario pueda reconocer que la constancia se acumula y que avanzar requiere completar trabajo.
>
> Entonces, si tuviera que resumir FITCORE en una oración, diría: es una aplicación web que organiza rutinas de entrenamiento, registra sesiones y usa una progresión de experiencia y niveles para representar el avance del usuario.”

## Qué significa “gamificado” en este proyecto

> “Cuando digo que FITCORE es gamificado, no quiero decir que sea un videojuego completo. Me refiero a que el entrenamiento incorpora elementos habituales de los juegos, como puntos de experiencia, niveles, metas y señales visuales de progreso.
>
> La idea es convertir parte de la constancia en una señal visible. El usuario realiza una sesión, el sistema calcula o recibe una cantidad de XP, esa XP se acumula y el nivel representa una etapa de progreso.
>
> Hay una regla de diseño que aparece en el proyecto: se busca manejar un objetivo de 250 XP por semana y cuatro semanas de 250 XP para llegar a 1.000 XP, que corresponden al umbral usado por el backend para un nivel. Pero debo separar la regla matemática de la función ya terminada. El registro de sesiones y la actualización de XP existen; la pantalla de semanas aún no representa una semana activa real ni tiene implementado el paso completo de una semana a la siguiente.
>
> Esa distinción evita que una animación o un indicador visual se confunda con una función de negocio ya conectada.”

---

## Recorrido del usuario de principio a fin

> “Ahora voy a contar FITCORE siguiendo el camino natural de una persona que usa la aplicación.
>
> Al entrar, la persona llega a la página de inicio. Desde allí puede ir al registro o al inicio de sesión. Si crea una cuenta, completa su nombre, su correo y una contraseña. El frontend envía esos datos al backend, el backend valida y guarda el usuario en PostgreSQL, y la aplicación confirma que la cuenta fue creada.
>
> Después, la persona inicia sesión. El backend verifica sus credenciales y genera un token de sesión. El frontend conserva ese token y los datos básicos del usuario para poder mostrar su identidad y para incluir el token en las solicitudes que necesitan autenticación.
>
> Una vez dentro, el usuario puede acceder a las áreas protegidas, como perfil, rutinas y entrenamiento. Si todavía no tiene una rutina, la aplicación le presenta una configuración donde elige sus días. El sistema decide una categoría de entrenamiento de acuerdo con la cantidad de días elegidos, consulta ejercicios disponibles y persiste la rutina y su planificación.
>
> Cuando el usuario entra a entrenamiento, ve tarjetas para los días configurados. Al iniciar una sesión, puede recorrer los ejercicios, registrar repeticiones y marcar series. Al finalizar, los datos pasan al backend y, si el guardado termina correctamente, se registran la sesión, las series y la experiencia. También se actualiza el total de XP y, si corresponde, el nivel.
>
> Ese es el hilo conductor del producto: identidad, configuración, entrenamiento, registro y progreso.”

---

## Demostración del programa

[Abrir FITCORE con una cuenta de prueba. Evitar exponer credenciales o datos privados.]

> “Voy a mostrar ahora ese recorrido desde la perspectiva del usuario.
>
> Esta es la pantalla inicial. La navegación presenta las opciones disponibles y permite acceder al registro o al inicio de sesión.
>
> En el registro vemos los campos necesarios para crear la cuenta. El formulario no se comunica directamente con PostgreSQL. Su responsabilidad es recopilar los datos y avisar a la vista que el usuario quiere enviarlos.
>
> Una vez iniciada la sesión, el sistema muestra páginas protegidas. Si alguien intenta entrar directamente a una ruta protegida sin una sesión autenticada, el router lo redirige al login. La protección de la interfaz mejora el recorrido, aunque la autorización definitiva siempre debe hacerse también en el backend para cada endpoint que lo necesite.
>
> En la sección de rutinas se pueden elegir días de entrenamiento. La categoría se adapta automáticamente a la frecuencia semanal seleccionada. Después de guardar, la aplicación puede mostrar los ejercicios, las series y las repeticiones que quedaron asociados a la rutina.
>
> En la sección de entrenamiento aparece una tarjeta por día configurado. Al iniciar una sesión, se abre la vista de entrenamiento en tiempo real. Allí se ve el ejercicio actual, el avance dentro de la sesión, las series y las repeticiones. También hay un temporizador de descanso.
>
> Al finalizar el último ejercicio, la aplicación envía el registro al servidor. Si el servidor confirma el guardado, se muestra el resultado con la experiencia otorgada y se actualizan los datos de usuario que están en memoria.
>
> Esta demostración muestra el flujo principal que está conectado. El indicador semanal que aparece en la pantalla no debe interpretarse como una navegación funcional entre semanas: el avance automático entre S1, S2, S3 y S4 sigue pendiente.”

[Si el servidor falla durante la demostración, no depurar frente al jurado. Mostrar capturas y explicar el recorrido.]

---

## Cómo se organiza el proyecto antes de hablar del código

> “Para explicar cómo está construido FITCORE, primero voy a separar el proyecto en sus partes.
>
> La carpeta `Frontend` contiene la aplicación que se ejecuta en el navegador. Ahí están las páginas, los componentes visuales, los estilos, las rutas, los servicios que hacen las solicitudes HTTP y los stores que comparten estado.
>
> El backend PHP que utiliza esta instalación está en `C:\Apache24\htdocs\Backend`. Es importante señalar que esa carpeta está fuera de este repositorio. El código de frontend puede estar versionado en un lugar y el código PHP ejecutado por Apache en otro. Por eso, antes de modificar o desplegar un endpoint, debo confirmar que estoy editando la copia que realmente utiliza el servidor.
>
> La base de datos es PostgreSQL. En ella se guardan las cuentas, sesiones de autenticación, ejercicios, rutinas, planificaciones, sesiones de entrenamiento, series y movimientos de XP.
>
> También hay carpetas auxiliares. `docs` contiene documentación del repositorio. `archivos md` conserva material técnico, planes de trabajo y documentos de diseño. `database/migrations` y `database/seeds` están reservadas para migraciones y datos iniciales, pero actualmente contienen principalmente instrucciones y marcadores, no el mecanismo de migración que ejecuta la aplicación. `packages/shared` es un paquete compartido de TypeScript dentro del monorepo, aunque el frontend actual no lo utiliza como contrato para sus solicitudes. `scripts/ruby` y `.github/workflows` son espacios documentados o preparados para automatizaciones futuras, no partes activas del flujo principal de FITCORE.
>
> Por último, `teoria typescript` contiene material de estudio. Es útil para aprender los conceptos usados al programar, pero sus archivos no se cargan en la aplicación cuando una persona usa FITCORE.”

## Entrada técnica: cómo se inicia el frontend

> “El punto de entrada visible de la aplicación es `Frontend/index.html`. Allí existe el elemento raíz donde Vue monta la aplicación, se carga el archivo de entrada de TypeScript y se define el título y el favicon.
>
> Desde ahí se ejecuta `src/main.ts`. Ese archivo crea la aplicación Vue, crea Pinia para administrar estado compartido, registra Vue Router, carga los iconos y monta el componente raíz.
>
> `App.vue` tiene una responsabilidad deliberadamente sencilla: contiene el espacio `<router-view>`, que es el lugar donde el router inserta la página que corresponde a la URL actual.
>
> Esta separación tiene un motivo. El HTML no debería contener toda la lógica de la aplicación; `main.ts` configura las herramientas globales; `App.vue` funciona como raíz; y el router selecciona qué vista se muestra. Así se puede entender el inicio del sistema sin mezclarlo con la lógica del registro o del entrenamiento.”

## Qué es una vista y qué es un componente

> “En el frontend separo las páginas completas de las piezas reutilizables.
>
> Las vistas, que están en `Frontend/src/views`, representan pantallas asociadas a rutas. Por ejemplo, `InicioView.vue` es la página de inicio, `LoginView.vue` es la página de acceso, `RegistroView.vue` es la de creación de cuenta, `RutinasView.vue` muestra la sección de rutinas y `EntrenamientoView.vue` organiza la página de entrenamiento.
>
> Los componentes, que están en `Frontend/src/components`, resuelven partes más pequeñas de esas pantallas. `Formulario.vue` dibuja el formulario de registro; `FormLogin.vue` dibuja el formulario de login; `RutinaConfig.vue` permite configurar los días; y `Entrenamiento.vue` presenta las tarjetas del plan semanal.
>
> Los encabezados y las barras de usuario, como `Header.vue`, `Header2.vue` y `BarraUsuario.vue`, ayudan a compartir una estructura visual entre varias páginas.
>
> Esta división tiene un porqué práctico. Una vista decide cómo se compone una página completa. Un componente encapsula una parte que puede evolucionar por separado. De esa manera, un cambio en el formulario no obliga a reescribir todo el router ni la estructura de la página.”

---

## Flujo 1: registro y creación de una cuenta

> “Voy a explicar el registro con más detalle, siguiendo el dato desde que el usuario lo escribe hasta que queda almacenado.
>
> Primero, el usuario ve `Formulario.vue`. Allí escribe el nombre, el correo electrónico y la contraseña. El componente mantiene esos valores en campos reactivos, lo que significa que Vue conoce su valor actual y puede actualizar la interfaz si cambia.
>
> Cuando se envía el formulario, el componente verifica que los campos no estén vacíos. Luego emite un evento llamado `guardar` con los datos. El componente no decide por sí mismo cómo se conecta a la base; solamente informa que tiene datos listos para procesar. Esa es una separación importante: la interfaz captura información, mientras que la vista coordina la acción.
>
> `RegistroView.vue` escucha ese evento. Cuando lo recibe, llama a `ejecutarRegistro`, que está en `autenticar.service.ts`. El servicio construye la URL del endpoint, valida el payload con los helpers correspondientes y envía una solicitud HTTP POST con un cuerpo JSON.
>
> El endpoint llamado es `registro.php`. Este archivo trabaja del lado del servidor. Comprueba que se haya recibido nombre, correo y contraseña. Después busca si ya hay una cuenta con ese email. Si encuentra una, devuelve un conflicto y no crea otra cuenta duplicada.
>
> Si el email está disponible, PHP no guarda la contraseña escrita por el usuario. En su lugar usa `password_hash` con BCrypt y guarda el resultado en `password_hash`. Esto importa porque si alguien pudiera leer la tabla, no encontraría la contraseña original como texto.
>
> Luego `registro.php` inserta el usuario en `usuarios`, asignando el rol predeterminado y los valores iniciales de nivel y XP. La conexión con PostgreSQL está centralizada en `db.php`, que crea una conexión PDO. PDO permite ejecutar consultas preparadas y manejar errores de base de datos de una forma común.
>
> El backend devuelve un JSON con el resultado. El servicio del frontend intenta leerlo, valida que tenga la forma esperada y convierte los errores HTTP o de conexión en errores que la vista puede mostrar. Si todo salió bien, `RegistroView.vue` informa que la cuenta fue creada y redirige a la pantalla de login.
>
> Por eso el registro y el login son dos pasos separados en el comportamiento actual. Crear una cuenta no crea automáticamente una sesión; el usuario debe ingresar sus credenciales en el formulario de acceso.”

### Por qué hay varios archivos en el registro

> “Puede parecer que hay muchos archivos para un solo formulario, pero cada uno responde una pregunta distinta.
>
> `Formulario.vue` responde: ¿qué campos ve el usuario y cómo los completa?
>
> `RegistroView.vue` responde: ¿qué debe hacer la página cuando el formulario se envía?
>
> `autenticar.service.ts` responde: ¿cómo se hace la solicitud HTTP y cómo se interpretan los errores?
>
> `Auth.DTO.ts` responde: ¿qué forma tienen los datos de entrada y salida?
>
> `registro.php` responde: ¿qué reglas valida el servidor y cómo inserta la cuenta?
>
> `db.php` responde: ¿cómo se abre la conexión a PostgreSQL?
>
> Esa separación hace que el cambio de un mensaje visual no requiera reescribir una consulta SQL, y que un cambio en la base no tenga que ser resuelto dentro del HTML.”

---

## Flujo 2: inicio de sesión

> “Después del registro, el usuario abre el formulario de inicio de sesión.
>
> `FormLogin.vue` captura el correo y la contraseña. Al enviar el formulario, emite el evento `guardar`, igual que el formulario de registro, pero con un payload diferente.
>
> `LoginView.vue` escucha ese evento y llama al store de autenticación. El store se llama `useUsuarioVerificacion` y está definido en `auth.store.ts`. Lo usamos porque la identidad del usuario no le interesa solamente al formulario: la necesitan también el encabezado, el perfil, las rutas protegidas y las pantallas de entrenamiento.
>
> El store llama a `ejecutarLogin` en `autenticar.service.ts`. Ese servicio envía los datos como JSON a `login.php`. El backend busca el usuario por email y compara la contraseña recibida con el hash guardado mediante `password_verify`.
>
> Si las credenciales son incorrectas, el backend devuelve una respuesta de error y no crea una sesión. Si son correctas, `login.php` crea un token aleatorio, calcula su hash y guarda ese hash en `sesiones_auth` junto con la fecha de expiración. El token original se devuelve al frontend una sola vez en la respuesta de login.
>
> El token funciona como una credencial temporal para solicitudes posteriores. El frontend no tiene que volver a enviar la contraseña cada vez que consulta una rutina o registra una sesión; envía el token como una cabecera de autorización.
>
> Cuando el store recibe la respuesta, guarda token y usuario mediante `StorageService`, actualiza el estado de Pinia y la vista navega al dashboard. De esta forma, el nombre, el nivel y la XP quedan accesibles desde distintas pantallas.”

### Por qué se usa un store

> “Un componente o una vista tiene un ciclo de vida: aparece cuando se navega hacia ella y puede destruirse cuando se navega a otra página. Si la sesión viviera solamente en una variable local de `LoginView.vue`, al salir de esa página esa variable dejaría de servir.
>
> Una store de Pinia proporciona un estado compartido durante la ejecución de la aplicación. No es una base de datos y no reemplaza al servidor; es la memoria reactiva que permite que varios componentes usen la identidad y los datos del usuario sin pasarse props manualmente por toda la jerarquía.
>
> Pinia por sí sola tampoco sobrevive a una recarga completa de la pestaña. Por eso hay otra capa: `StorageService`, que guarda determinados valores en `localStorage`. Cuando la aplicación vuelve a iniciar, el store puede leer esos valores y verificar con el backend si el token sigue vigente.”

---

## Flujo 3: restauración, comprobación y cierre de sesión

> “El inicio de sesión no es solamente una acción que ocurre una vez. FITCORE también necesita decidir qué pasa cuando el usuario recarga el navegador o vuelve a una ruta protegida.
>
> En `router/index.ts` hay un guard de navegación. Antes de entrar a una ruta, comprueba si el store ya restauró la sesión. Si no lo hizo, llama a `restaurarSesion`.
>
> `restaurarSesion` busca el token local. Si no encuentra uno, considera que no hay una sesión que restaurar. Si encuentra un token, puede cargar de inmediato los datos locales del usuario para que la interfaz no quede vacía mientras verifica el servidor. Sin embargo, esos datos locales no prueban que el usuario siga autenticado; son solamente una hidratación temporal de la interfaz.
>
> Para verificarlo de verdad, `autenticar.service.ts` envía el token a `verificar_sesion.php`. El backend vuelve a calcular su hash y consulta `sesiones_auth`. Comprueba que la sesión no haya expirado y que no esté revocada. Si todo coincide, devuelve los datos del usuario; si no, el store elimina la sesión local.
>
> Cuando se cierra sesión, el frontend llama a `logout.php`. El backend busca el hash del token y llena `revocado_at`. Así el servidor deja de aceptar ese token. Después el frontend elimina token y usuario de `localStorage` y reinicia el estado Pinia.
>
> Hay un detalle que debo conocer al explicar la implementación: el servicio de cierre tiene un comportamiento de respaldo que permite cerrar la sesión local aunque falle la petición de revocación. En ese caso el usuario sale de la interfaz, pero el servidor puede no haber confirmado la revocación. Es un área que conviene endurecer para una versión de producción.”

### Qué datos se guardan y qué no

> “La contraseña no se guarda en `localStorage`. El token sí se guarda en el navegador para mantener la sesión, y los datos básicos del usuario se guardan para restaurar la interfaz.
>
> En la base de datos se almacena el hash del token, no el token original. Por eso, cuando llega una solicitud, el backend calcula el hash de la credencial enviada y lo compara con el valor persistido.
>
> En una instalación de producción también sería necesario asegurar el sitio con HTTPS, revisar cuidadosamente el almacenamiento del token y configurar los errores para que los detalles técnicos queden en los logs y no se filtren al navegador.”

---

## Flujo 4: rutas y autorización de páginas

> “El router de Vue define las páginas y sus URL. Algunas rutas están marcadas con `requiereAuth`: por ejemplo, dashboard, perfil, rutinas y entrenamiento.
>
> El guard de `router/index.ts` funciona como una puerta de navegación. Si el usuario intenta abrir una de esas páginas sin una sesión autenticada, lo manda a `/login`. Si una persona autenticada intenta abrir el login o el registro, la aplicación la redirige al dashboard.
>
> Esto mejora la experiencia y evita mostrar páginas privadas dentro de la interfaz a una persona que no inició sesión. Pero una ruta protegida en el frontend no es una barrera de seguridad suficiente para los datos. Un usuario puede intentar llamar a un PHP directamente. Por eso cada endpoint que devuelve o modifica datos privados debe volver a validar el token y comprobar que el recurso pertenece a ese usuario.
>
> En los endpoints de catálogo, creación de rutina y registro de sesión se comprueba la sesión. En cambio, el `perfil.php` que existe actualmente recibe un ID por query y no verifica un token. Esa diferencia es importante: no debería considerarse seguro solo porque la vista `/perfil` está detrás de un guard del router.”

---

## Flujo 5: configurar y crear una rutina

> “Una vez dentro del sistema, el usuario necesita un plan. La configuración de rutina empieza en `RutinaConfig.vue`.
>
> Cuando se monta el componente, solicita el catálogo al store de rutina. El store, definido en `rutina.store.ts`, llama a `obtenerCatalogo` desde `Rutina_service.ts`. Esa función envía una solicitud autenticada a `GeneradorEntrenamiento.php`.
>
> El endpoint verifica la sesión, obtiene el nivel del usuario y consulta si existe una rutina guardada. También trae los días asociados y los ejercicios de la rutina. La respuesta se usa para mostrar el estado actual y permitir que el frontend sepa si debe presentar el generador o la rutina existente.
>
> Si el usuario necesita configurar una rutina, elige días de la semana. El componente limita la selección a entre uno y seis días. A medida que cambia la cantidad seleccionada, el tipo de entrenamiento se adapta:
>
> “Con uno, dos o tres días, se propone Full Body. Con cuatro días, Upper / Lower. Con cinco, Push, Pull y Legs. Con seis, se propone repetir esa división dos veces.”
>
> La selección de categoría busca que el tipo de rutina corresponda a la frecuencia semanal. El backend vuelve a determinar la categoría según la cantidad de días; no confía solamente en el nombre que envía el frontend. Esa repetición de la regla es importante porque el servidor debe validar la información aunque el navegador mande datos incorrectos.
>
> Cuando se confirma la rutina, `rutina.store.ts` llama a `guardarRutina`. El servicio envía los días y la categoría a `Generador_rutina.php`.
>
> En el backend se valida el payload, se comprueba el token y se obtiene el nivel del usuario. A partir de ese nivel se buscan ejercicios activos cuya dificultad corresponda. Si no hay ejercicios para ese nivel, existe una consulta de respaldo que intenta obtener ejercicios activos.
>
> Después el generador inicia una transacción. Una transacción agrupa varias modificaciones que deben quedar consistentes: o se guardan todas, o ante un error se revierten. Dentro de ese proceso se crea la rutina principal, se insertan sus relaciones con ejercicios, se vinculan días de la semana, se crea una planificación inicial y se crean los ejercicios planificados con sus objetivos y componentes de XP.
>
> También se intenta guardar la configuración de frecuencia en `usuario_configuracion`. Al finalizar, el backend confirma la transacción y responde con los ejercicios ordenados, los días y los datos de la rutina.
>
> El frontend recibe esa respuesta y la coloca en `rutina.store.ts`. Como el estado de Pinia es reactivo, la pantalla de confirmación puede mostrar inmediatamente que la rutina está lista sin tener que recargar la página.”

### Por qué existe más de una tabla para rutinas

> “La rutina principal no contiene todos los detalles en una sola fila. Se separa la identidad de la rutina de los ejercicios y los días.
>
> `rutinas` representa el plan general asociado al usuario. `rutina_ejercicios` representa los ejercicios que forman parte de esa rutina, con su orden, series, repeticiones y descanso. `dia_semanal` representa qué días fueron elegidos y a qué rutina están vinculados.
>
> Después está `planificaciones_semanales`, que representa una planificación asociada a usuario, nivel, semana, tipo y estado. `ejercicios_planificados` representa el detalle de ejercicios dentro de esa planificación y día.
>
> La razón de separar estos conceptos es que una rutina, una configuración semanal y una sesión realizada no son lo mismo. La rutina describe qué propone el plan; la planificación identifica un ciclo o semana; la sesión representa lo que el usuario efectivamente realizó en una fecha concreta.”

### Qué significa el motor de rutinas hoy

> “Cuando hablo del motor de rutinas actual, me refiero a reglas de selección y persistencia que están codificadas en `Generador_rutina.php`. El motor tiene una tabla de progresión por nivel que determina cantidades de series y repeticiones. También limita la cantidad de ejercicios, consulta el nivel del usuario y adapta la categoría a los días elegidos.
>
> No debo describirlo como un entrenador inteligente que aprende del usuario. Es un generador basado en reglas fijas y en los ejercicios disponibles en la base de datos. El nombre visual ‘Generador inteligente’ es una etiqueta de interfaz, pero técnicamente la lógica es un conjunto de reglas predeterminadas.”

---

## Flujo 6: mostrar la rutina y elegir un día

> “Después de crear o cargar una rutina, la página de entrenamiento presenta una tarjeta por cada día seleccionado.
>
> `EntrenamientoView.vue` compone la página. Incluye la barra lateral, el encabezado y el componente `Entrenamiento.vue`. El panel visual se encarga de mostrar nombre de la rutina, categoría, nivel, sesiones y resumen de XP.
>
> Para cada día, el componente calcula si aparece como completado comparando los datos de `dias_completados` recibidos en la rutina. Cuando el usuario presiona el botón de iniciar, el frontend navega a `/entrenamientoEjercicio` y pasa información del día en los parámetros de consulta de la URL.
>
> Esta separación facilita que la pantalla de entrenamiento en tiempo real sepa qué día se quiere realizar sin tener que volver a preguntarle al usuario.”

### Advertencia sobre los días

> “Hay una diferencia técnica que todavía requiere atención. En el generador de rutina se trabaja con el número del día de calendario seleccionado, como lunes o miércoles. En la tarjeta también se pasa un número ordinal de sesión, por ejemplo sesión uno o sesión dos. La vista de entrenamiento prioriza ese número ordinal como `numero_dia`.
>
> Si los días seleccionados no son consecutivos, el día de calendario y el número ordinal pueden ser distintos. Además, la interfaz permite seleccionar domingo, que es el día siete, pero el backend de registro limita el número de día a uno hasta seis. Por lo tanto, el mapeo completo de los días todavía debe unificarse antes de considerar que la agenda es totalmente consistente.”

---

## Flujo 7: realizar y registrar una sesión

> “Al entrar a `EntrenamientoTiempoReal.vue`, el usuario ve el plan del día. Si la rutina todavía no está cargada en Pinia, la vista solicita el catálogo para recuperarla.
>
> El componente mantiene un índice del ejercicio actual. Para cada ejercicio crea una lista reactiva de series basada en los objetivos de la rutina. El usuario puede ajustar las repeticiones, agregar o quitar series y marcar cada serie como completada.
>
> La pantalla incluye un temporizador de descanso. Ese temporizador se ejecuta en el navegador mediante un intervalo de JavaScript. Es una ayuda visual para la sesión; no es una entidad que se guarde en PostgreSQL.
>
> Al marcar una serie, el usuario puede iniciar el descanso. Al terminar el ejercicio, la aplicación calcula una estimación de XP. La estimación considera la proporción de series marcadas y las repeticiones realizadas en relación con el objetivo. Después guarda el detalle de ese ejercicio en un registro local de la sesión mientras el usuario avanza por los ejercicios.
>
> Cuando llega al último ejercicio, el botón cambia de ‘Guardar y siguiente ejercicio’ a ‘Finalizar sesión’. La vista suma la XP acumulada y llama a `registrarSesion`, que vive en `Rutina_service.ts`.
>
> Esa función envía un payload con el número de día, la XP total y los ejercicios registrados. Cada ejercicio contiene sus series, sus repeticiones y si se completaron.
>
> El servidor recibe la solicitud en `registrar_sesion.php`. Primero valida que sea una solicitud POST y que haya un token válido. Busca el usuario a partir del hash del token y recupera su XP y nivel actuales. Luego abre una transacción para que el registro de la sesión y las series no queden a medias.
>
> El backend busca una planificación activa. Si no la encuentra, intenta crear una planificación de semana uno. Después crea o actualiza la sesión de entrenamiento para el día de esa planificación. Para cada ejercicio, busca o crea un registro en `ejercicios_planificados`; por cada serie, inserta o actualiza una fila en `series_registro`.
>
> Una vez procesados los detalles, el backend agrega la XP al historial, actualiza el total de XP en `usuarios` y calcula el nivel. Si todo termina correctamente, confirma la transacción y devuelve el resultado. Si ocurre un error, revierte la transacción para evitar que una parte del entrenamiento quede guardada y otra no.
>
> El frontend solo muestra el modal de sesión completada después de recibir confirmación exitosa. Entonces actualiza el usuario en el store de autenticación con la nueva XP y nivel, y marca el día completado en el estado de rutina que tiene en memoria.”

### Por qué la transacción es importante

> “Una sesión tiene varios datos relacionados: la fila principal de la sesión, el detalle de ejercicios, las series y la XP. Si se guardara cada uno de manera independiente, podría ocurrir que la sesión figure como completada pero falten las series, o que se sumara XP aunque el resto del registro hubiera fallado.
>
> La transacción ayuda a que el servidor trate ese conjunto como una operación indivisible. Si una consulta da error antes del commit, el backend hace rollback. Así evita confirmar un estado parcial.”

### Una limitación en el cálculo de XP

> “La XP se calcula en parte en el frontend y luego se envía al backend. El backend actualiza el total, pero no vuelve a reconstruir completamente el cálculo comparando cada repetición registrada con las reglas de rutina.
>
> Esto significa que la validación del valor de XP debería reforzarse en el servidor antes de usarlo en un entorno real. La interfaz puede mostrar una estimación, pero la regla definitiva de recompensa debería estar en una capa confiable del backend.”

---

## Flujo 8: XP y niveles

> “En el sistema de gamificación, el total de XP se guarda en `usuarios.xp_total`, y el nivel actual se guarda en `usuarios.nivel_actual`. Además, cada recompensa de XP puede registrarse como un movimiento en `xp_historial`, con la cantidad, el motivo y la fecha.
>
> Cuando se finaliza una sesión, `registrar_sesion.php` agrega la XP recibida al total del usuario. El cálculo del nivel en el backend usa un umbral de 1.000 XP por nivel y limita el nivel máximo a diez.
>
> Para el usuario, la intención es que la XP refleje la actividad. Para el sistema, tener una columna de total y una tabla de historial ofrece dos perspectivas: el total rápido para mostrar nivel y el historial de eventos para entender de dónde provienen los puntos.
>
> Sin embargo, las distintas pantallas no están todavía totalmente unificadas en torno a una sola regla. Por ejemplo, hay un getter del store que calcula el XP siguiente usando un múltiplo de 500, mientras que el backend incrementa el nivel usando tramos de 1.000. El perfil también contiene textos de requisitos de niveles que no coinciden todos con el backend.
>
> Entonces, en la exposición puedo explicar el principio de XP y el cálculo de nivel del endpoint, pero debo mencionar que todavía falta alinear todos los umbrales visuales para que perfil, entrenamiento y backend muestren exactamente la misma regla.”

---

## Flujo 9: semana activa y por qué no aparece el avance

> “Una pregunta importante es por qué no se puede pasar a la siguiente semana aunque la base de datos tenga una columna `semana`.
>
> La respuesta es que la columna almacena un dato, pero para avanzar hace falta un proceso de negocio completo. El backend crea la planificación en semana uno. El endpoint de registro busca una planificación con estado activo. Sin embargo, el flujo actual no marca de forma coordinada la semana como completada cuando se cumplen las sesiones requeridas y tampoco crea o activa automáticamente la semana siguiente.
>
> En el frontend se muestran cuatro indicadores, S1 a S4, pero el indicador activo está fijado en S1. La barra de progreso se calcula con el XP total módulo 250, no con la semana real guardada en la planificación.
>
> Por eso no aparece una opción de pasar de semana: no es que esté oculta por una condición del usuario, sino que la navegación y la transición todavía no se implementaron.
>
> Antes de programarlo hay que decidir qué significa completar una semana. Por ejemplo, si el usuario eligió cuatro días, ¿debe completar los cuatro? ¿Puede avanzar con tres? ¿Qué ocurre si una sesión se repite? ¿Se conserva el ciclo si sube de nivel a mitad de semana? Estas reglas deben definirse antes de añadir el botón.
>
> Después se debe conectar la regla en el backend, devolver la semana real por la API, tiparla en TypeScript, almacenarla en Pinia y mostrarla en el frontend. También hay que consultar los días completados solo para la planificación actual; de lo contrario, un día realizado en una semana anterior podría mostrarse como completado en la semana actual.
>
> Por eso, en este momento S1–S4 es un indicador visual parcial, no un selector funcional ni un sistema de avance semanal terminado.”

---

## Perfil, dashboard, comunidad e historial

### Perfil

> “La página de perfil muestra datos del estado de autenticación y elementos visuales de nivel y XP. La identidad puede venir de los datos que entregan login y verificación de sesión.
>
> También existe un endpoint PHP llamado `perfil.php`, que consulta una cuenta por su ID. Pero en el frontend actual no encontré un servicio que llame a ese endpoint. Además, ese PHP no valida un token de autenticación. Por eso el perfil visual puede usar datos del store, pero el endpoint no debería presentarse como una consulta privada correctamente protegida hasta que se agregue la validación del servidor.”

### Dashboard

> “El dashboard usa componentes como `Header2.vue` y `BarraUsuario.vue`. La barra lateral presenta nombre, amigos, intereses, minutos y entrenamientos semanales.
>
> Algunas partes son dinámicas respecto del store, pero otras vienen de datos de ejemplo. En particular, `DashboardView.vue` mantiene publicaciones iniciales en una lista dentro del propio componente. Dar me gusta, comentar o crear una publicación cambia el estado local de esa pantalla, pero no se encontró una API que persista esas publicaciones en PostgreSQL.
>
> Por lo tanto, el dashboard existe como experiencia visual y prototipo de interacción, pero no debo decir que ya hay una comunidad social conectada entre usuarios.”

### Historial de XP

> “El backend inserta movimientos en `xp_historial` al otorgar XP. También hay un store llamado `xp_historial.store.ts`, pero no se encontró un endpoint que consulte esos movimientos y los muestre en una vista.
>
> Eso significa que la persistencia del movimiento está iniciada, pero una pantalla de historial completo todavía no forma parte del flujo conectado que puede demostrarse.”

---

## Arquitectura: cómo se pasan los datos

> “La arquitectura se entiende siguiendo una solicitud de extremo a extremo.
>
> Primero una vista o un componente recoge la acción del usuario. Si el dato debe ser compartido entre varias pantallas, se actualiza un store de Pinia. Cuando hay que hablar con el servidor, se llama a un servicio de TypeScript.
>
> El servicio usa `fetch` para enviar una solicitud HTTP con JSON. En desarrollo, Vite tiene un proxy para `/Backend`, que reenvía esas solicitudes a Apache. Apache ejecuta el endpoint PHP. El endpoint valida el método, el token y los datos; después usa PDO para hablar con PostgreSQL.
>
> PostgreSQL devuelve resultados al backend. PHP los convierte en JSON. El servicio frontend interpreta ese JSON y se lo devuelve a la vista o al store. Finalmente, Vue vuelve a renderizar las partes afectadas.
>
> La razón de esta cadena es separar responsabilidades y límites. El navegador no conoce credenciales de la base ni ejecuta SQL directamente. El PHP aplica reglas del lado del servidor. PostgreSQL conserva los datos. Vue administra la experiencia de usuario.”

### Qué hace cada tecnología

> “Vue permite construir la interfaz a partir de componentes reactivos. TypeScript define las formas esperadas de los datos y detecta muchos errores durante el desarrollo. Vite ejecuta el servidor de desarrollo y prepara los archivos de producción.
>
> Vue Router administra las rutas. Pinia administra estado compartido. Los servicios aíslan la comunicación HTTP. PHP sirve como backend en esta instalación y PostgreSQL es el motor de persistencia.
>
> La aplicación también usa Bootstrap Icons para iconografía y hojas CSS globales y locales para los estilos.
>
> No todo el código que existe en el repositorio forma parte de este recorrido. Por ejemplo, `packages/shared` declara tipos y lógica de roles/suscripción para compartir entre workspaces, pero el flujo actual de autenticación y rutina usa principalmente `Auth.DTO.ts` y sus propios servicios. Por eso no debería decir que todos los contratos están centralizados allí.”

### Qué significa DTO

> “DTO significa objeto de transferencia de datos. En este proyecto los archivos de `Frontend/src/DTO` describen qué forma tienen los datos que se intercambian.
>
> `Auth.DTO.ts` define tipos como `Login`, `Registro`, `Usuario`, `ApiResponse`, `CatalogoRutina` y los datos de rutina. `EstadoSesion.ts` describe la información que mantiene el store de autenticación.
>
> El DTO ayuda a que frontend y backend hablen con nombres y campos compatibles. Pero TypeScript no valida en tiempo de ejecución que un JSON externo sea correcto por sí solo. Por eso el servicio también intenta interpretar y validar la respuesta antes de pasarla al resto de la aplicación.”

---

## La base de datos explicada como historia de datos

> “La base de datos está organizada alrededor de varias preguntas.
>
> Para saber quién usa el sistema, existe `usuarios`. Allí se guardan el identificador, nombre, correo, hash de contraseña, rol, XP y nivel.
>
> Para saber si una sesión de acceso sigue vigente, existe `sesiones_auth`. Cada fila relaciona un usuario con un hash de token, una expiración y un posible momento de revocación.
>
> Para describir el catálogo de ejercicios, existe `ejercicios`. Esa tabla contiene nombre, descripción, categoría, actividad y dificultad.
>
> Para definir un plan general de una persona, existe `rutinas`. La relación con los ejercicios está en `rutina_ejercicios`, donde se registra orden, series, repeticiones y descanso.
>
> Los días elegidos se guardan en `dia_semanal`. La configuración de frecuencia y tipo se puede guardar en `usuario_configuracion`.
>
> Para distinguir el ciclo o la semana de entrenamiento, está `planificaciones_semanales`. Esa planificación se relaciona con `ejercicios_planificados`, donde se detalla qué ejercicios están propuestos para cada día y sus objetivos.
>
> Cuando la persona entrena de verdad, se crea una fila en `sesiones_entrenamiento`. Cada serie realizada se guarda en `series_registro`, relacionada con la sesión y con el ejercicio planificado.
>
> Finalmente, `xp_historial` registra eventos de experiencia. Así se separa la definición de un ejercicio, la planificación de un ejercicio y la ejecución real de ese ejercicio. Esa separación facilita mantener el plan sin confundirlo con lo que efectivamente ocurrió.”

### Qué hace una clave foránea

> “Una clave foránea es una regla que obliga a que una referencia apunte a un registro que existe. Por ejemplo, una rutina está asociada con un usuario; una serie registrada está asociada con una sesión y un ejercicio planificado.
>
> Esa integridad evita crear registros huérfanos. Los UUID funcionan como identificadores de las entidades y permiten que los endpoints relacionen las filas sin exponer un contador simple como identidad pública.”

### Cuidado con los esquemas de referencia

> “Los esquemas históricos de referencia fueron retirados del repositorio porque no coinciden completamente con las tablas y columnas que utiliza actualmente el backend.
>
> No se debe ejecutar un esquema antiguo sobre la base de datos actual. La fuente de verdad es la estructura que existe en PostgreSQL y que consultan los endpoints PHP.”

---

## Errores, validaciones y respuestas

> “Una aplicación conectada a un servidor debe contemplar que las solicitudes pueden fallar.
>
> En autenticación, `autenticar.service.ts` comprueba la URL, valida el payload, atrapa errores de red, lee la respuesta como texto e intenta convertirla a JSON. Si el servidor devuelve un cuerpo vacío o un contenido no válido, el servicio genera un error explícito.
>
> En el servicio de rutina también se comprueba que exista un token, que el backend devuelva JSON y que el código HTTP y la propiedad `exito` indiquen una respuesta correcta.
>
> Los endpoints PHP controlan métodos permitidos, campos requeridos, sesión válida y errores de base de datos. En el registro de entrenamiento hay una transacción y una reversión en caso de error.
>
> Estas validaciones tienen un motivo: los datos del navegador no son confiables, la conexión puede fallar y la base de datos puede rechazar una operación por una restricción. La interfaz debe informar que algo salió mal y no mostrar una pantalla de éxito si el servidor no confirmó el guardado.”

## Seguridad y aspectos que requieren trabajo antes de producción

> “En FITCORE ya hay decisiones orientadas a la seguridad: las contraseñas se guardan mediante `password_hash`, el login verifica con `password_verify`, se usa un token aleatorio y el backend almacena su hash, y las consultas principales se realizan con PDO y parámetros.
>
> También hay aspectos que no debo presentar como resueltos solo porque exista un token.
>
> Primero, las solicitudes privadas deben comprobar autenticación y pertenencia del dato en cada endpoint. La protección de rutas Vue no reemplaza esa comprobación.
>
> Segundo, el endpoint de perfil consultado no valida token.
>
> Tercero, algunos errores PHP devuelven detalles de excepción al cliente. En producción conviene devolver mensajes genéricos al navegador y registrar detalles solo en logs protegidos.
>
> Cuarto, el frontend persiste el token en `localStorage`. Esto simplifica la implementación, pero requiere evaluar el riesgo de scripts inyectados y considerar estrategias de almacenamiento y protección adecuadas al despliegue.
>
> Quinto, los servicios tienen respuestas de respaldo en el cierre de sesión que pueden hacer que la interfaz informe una salida local sin confirmar la revocación remota.
>
> Finalmente, las credenciales de PostgreSQL deben mantenerse en configuración privada del servidor, nunca dentro del código versionado ni en una respuesta API. Antes de producción también se necesita HTTPS, control de origen CORS adecuado, políticas de roles y pruebas de autorización.”

---

## Organización de las carpetas del repositorio

> “Ahora voy a explicar para qué sirve cada carpeta importante del proyecto y cuáles son parte del programa que se ejecuta.
>
> En la raíz se encuentra el `package.json`, que declara el monorepo y los comandos generales. El monorepo permite agrupar el frontend y paquetes compartidos bajo un mismo repositorio. El `package-lock.json` registra las versiones concretas de dependencias para que las instalaciones sean reproducibles.
>
> `Frontend` es el workspace activo de la interfaz. `Frontend/src` contiene el código Vue y TypeScript. Dentro de `src`, `views` agrupa páginas, `components` agrupa piezas visuales reutilizables, `router` define rutas, `stores` administra estado compartido, `services` realiza solicitudes y utilidades, `DTO` tipa datos y `assets` contiene estilos e imágenes.
>
> `Frontend/public` contiene recursos que Vite sirve directamente, como el favicon. A diferencia de imágenes importadas desde `src`, los recursos públicos conservan una ruta estática directa. `Frontend/index.html` es el HTML inicial. `vite.config.ts` configura el alias `@` y el proxy de desarrollo hacia Apache. Los archivos `tsconfig` definen opciones de TypeScript. `Frontend/package.json` declara Vue, Pinia, Vue Router, Vite, TypeScript y comandos de build y verificación.
>
> La carpeta `Frontend/node_modules` contiene dependencias instaladas, y `Frontend/dist` contiene una compilación generada. No son código fuente que deba modificarse manualmente; se vuelven a generar con instalación o build.
>
> `database/migrations` está destinada a cambios versionados del esquema. `database/seeds` está destinada a los datos iniciales o de prueba. En el estado actual son principalmente plantillas e instrucciones, no una secuencia aplicada por el flujo PHP.
>
> `packages/shared` tiene un paquete llamado `@fitcore/shared`. Su código actual define roles, estados de suscripción y una función que describe reglas de acceso según rol y suscripción. Es una pieza de arquitectura compartida preparada para workspaces, pero no es la fuente de autorización que consume el backend PHP actual, ni se ha observado que las vistas principales la importen.
>
> `docs` contiene la documentación organizada del proyecto. `docs/README.md` describe una convención de documentación semanal. `docs/FLUJOS-FITCORE.md`, que es este archivo, explica la aplicación actual en forma de guion.
>
> `archivos md` reúne documentación histórica y de planificación: documentación técnica, esquemas, planes de MVP, notas de persistencia, desafíos de autenticación y planes de trabajo. Es material de referencia, no código que se ejecuta con la aplicación. Como varios textos se escribieron en etapas distintas, algunos describen una arquitectura que ya cambió. Siempre se debe contrastar con el código actual antes de repetir una afirmación.
>
> `teoria typescript` contiene lecciones sobre tipos, funciones, clases, operadores, métodos de arrays, métodos de objetos, fechas, errores, genéricos, peticiones HTTP, configuración, testing y arquitectura. Es material pedagógico para comprender conceptos que aparecen en el desarrollo. No forma parte del bundle web.
>
> `scripts/ruby` tiene documentación sobre automatización y posibles scripts de soporte, como backups, seeds y reportes. La carpeta no contiene actualmente esos scripts ejecutables; por eso no se debe afirmar que hay backups automáticos o carga de seeds en Ruby.
>
> `.github/workflows` tiene documentación sobre CI/CD futuro, pero no se encontró un workflow activo de GitHub Actions. Por eso no debo decir que cada cambio ya pasa automáticamente por tests o despliegue.
>
> `.vscode` guarda configuración del entorno de edición para facilitar el trabajo en Visual Studio Code. No participa en la ejecución de la aplicación en el navegador.
>
> `query` aparece en la raíz, pero no se observa como parte del flujo frontend/backend descrito. Si se va a presentar, conviene identificar primero qué contiene y si está vigente.
>
> El antiguo `setup-monorepo.ps1` fue retirado: asumía una estructura de carpetas `Back`/`frontend` y podía borrar dependencias locales.”

---

## Carpetas de aprendizaje frente a carpetas de ejecución

> “Una pregunta que podría aparecer es por qué el repositorio tiene tantas carpetas y archivos si la aplicación principal parece más pequeña.
>
> La respuesta es que el repositorio cumple dos funciones: contiene el software de FITCORE y conserva material de aprendizaje y planificación relacionado con el desarrollo.
>
> Para ejecutar la interfaz, el recorrido principal está en `Frontend`. Para ejecutar la API de esta instalación, Apache usa los PHP ubicados en `C:\Apache24\htdocs\Backend`. Para ejecutar la persistencia, PostgreSQL usa la base de datos configurada en el servidor.
>
> Los documentos en `archivos md`, las lecciones de `teoria typescript`, los README de migraciones, seeds, Ruby y workflows ayudan a planificar o estudiar, pero no se activan automáticamente cuando se abre la página.
>
> Separar estas dos categorías evita describir una idea documentada como si ya fuera una función de la aplicación.”

---

## Etapas de trabajo o MVP

> “Para presentar la evolución de FITCORE, puedo agrupar el trabajo en cuatro etapas funcionales. Aclaro que esta es una forma explicativa de ordenar el proyecto; algunos documentos de planificación tienen fechas y estados anteriores, por lo que antes de exponer debo cotejarlos con la situación real.
>
> La primera etapa es identidad y autenticación: crear cuentas, iniciar sesión, restaurar una sesión, cerrar sesión y proteger rutas. El flujo principal de frontend y PHP está implementado.
>
> La segunda etapa es configuración de rutinas: seleccionar días, asignar una categoría según la frecuencia, elegir ejercicios disponibles, guardar objetivos y crear la planificación inicial. Existe un motor por reglas en PHP y una interfaz Vue para configurarlo.
>
> La tercera etapa es realizar entrenamientos y registrar XP. La persona puede seguir ejercicios, registrar series y repeticiones, enviar la sesión y actualizar la XP y el nivel. El avance automático de semanas sigue pendiente, y además hay inconsistencias entre algunos indicadores visuales y los datos reales.
>
> La cuarta etapa es seguimiento ampliado y comunidad. Hay pantallas y elementos visuales de dashboard, perfil, amigos, intereses e historial. Sin embargo, varias cifras son estáticas o de demostración, las publicaciones del dashboard están en memoria local y el historial de XP todavía no se consulta desde una vista. Esa etapa debe describirse como parcial, no como una comunidad social persistida y terminada.
>
> Esta forma de presentar los MVP permite explicar qué valor entrega hoy el sistema y cuál es el camino de evolución sin inflar el alcance.”

---

## Desafíos técnicos y aprendizajes

> “Durante el desarrollo aparecieron varios desafíos que me ayudaron a entender mejor la conexión entre capas.
>
> El primero fue mantener alineados los nombres y tipos de datos entre Vue, PHP y PostgreSQL. Por ejemplo, no alcanza con que el frontend llame a un archivo: el payload que envía debe coincidir con lo que el endpoint espera, la respuesta debe coincidir con el DTO y las columnas usadas por la consulta deben existir en la base real.
>
> El segundo desafío fue la persistencia de autenticación. El estado dentro de una vista no alcanza porque una vista se destruye al navegar. La solución fue separar la sesión en un store compartido, mantener información local para restaurarla y volver a verificarla con el backend.
>
> El tercer desafío fue modelar las actividades de entrenamiento sin mezclar plan con ejecución. Una rutina define ejercicios y objetivos; una planificación agrupa un ciclo; una sesión registra lo que ocurrió en una fecha; y una serie registra el detalle del trabajo. Separar esas entidades ayuda a conservar el historial.
>
> El cuarto desafío fue comprobar que una pantalla realmente refleje la base. Encontré indicadores que se ven completos, pero no están conectados a una transición de semana real. Aprendí que una interfaz puede dar la impresión de una función terminada, aunque falte la regla de backend y el estado persistido que la respalde.
>
> También aprendí que la documentación debe actualizarse cuando cambia el código. Hay notas y esquemas de etapas anteriores que todavía describen un sistema distinto. La manera responsable de usarlos es como historial y contexto, no como prueba de que la implementación actual funciona igual.”

---

## Qué mejoraría después de la presentación

> “Si continúo el desarrollo, no empezaría agregando simplemente un botón de ‘Siguiente semana’. Primero definiría qué condición completa una semana, qué pasa si falta un día, cómo se manejan los reintentos y si el ciclo cambia cuando el usuario sube de nivel.
>
> Después implementaría esa regla en el backend dentro de una transacción, consultando solo la planificación activa del usuario. El endpoint tendría que devolver el número de semana y el estado real. El frontend agregaría esos campos a sus tipos, los almacenaría en Pinia y mostraría la semana real.
>
> A la vez corregiría la consulta de días completados para que filtre por `planificacion_id`. Un día de una semana anterior no debería marcarse como terminado en la semana actual.
>
> También unificaría el concepto de número de día: día de calendario versus número ordinal de sesión. Resolvería el caso del domingo y garantizaría que la misma clave identifique el día en la rutina, la planificación y el registro.
>
> Luego alinearía el cálculo visual de XP del perfil con el umbral que aplica el backend, validaría la XP en el servidor y conectaría el historial a una pantalla real.
>
> Finalmente revisaría autorización de endpoints, manejo de errores para producción, HTTPS y pruebas automatizadas. Haría esos cambios de forma gradual, respaldando la base de datos y verificando cada flujo antes de continuar.”

---

## Cierre

> “Para concluir, FITCORE es una aplicación web que conecta una interfaz de entrenamiento con una API PHP y una base de datos PostgreSQL.
>
> El usuario puede registrarse, iniciar sesión, configurar una rutina, consultar ejercicios, realizar una sesión y guardar registros de entrenamiento. El sistema también suma experiencia y calcula niveles.
>
> Desde el punto de vista técnico, el proyecto separa vistas, componentes, stores, servicios, contratos de datos, endpoints y persistencia. Esa separación permite entender por qué ocurre cada acción y en qué capa debe implementarse una mejora.
>
> También identifiqué con claridad qué partes siguen incompletas: el avance real entre semanas, la coherencia total de algunos indicadores de XP, el historial visible y la persistencia de la comunidad.
>
> Para mí, conocer esos límites es parte de presentar correctamente un software. FITCORE ya tiene flujos funcionales sobre los que se puede seguir construyendo, y las próximas mejoras se pueden abordar paso a paso, con reglas claras y pruebas.
>
> Muchas gracias. Quedo atento a sus preguntas.”

---

# Guion de respaldo para preguntas técnicas

## Si preguntan: “¿Cómo se crea un usuario?”

> “El formulario de registro recopila nombre, correo y contraseña. La vista recibe el evento del formulario y llama a un servicio TypeScript. El servicio hace un POST JSON a `registro.php`. El PHP comprueba los datos, verifica que el correo no exista, genera un hash BCrypt de la contraseña y crea la fila en `usuarios` usando PDO. Después devuelve un JSON y la aplicación lleva al usuario al login.”

## Si preguntan: “¿La contraseña se guarda cifrada?”

> “Se guarda un hash de contraseña, no la contraseña legible. En el login el backend usa `password_verify` para comprobar el valor ingresado contra el hash guardado.”

## Si preguntan: “¿Qué es el token?”

> “Es una credencial temporal que el frontend envía en solicitudes protegidas. El servidor guarda el hash del token, su expiración y si fue revocado. Así puede validar cada solicitud sin reenviar la contraseña.”

## Si preguntan: “¿Cómo se recupera una sesión después de recargar?”

> “El frontend lee el token guardado localmente y lo manda a `verificar_sesion.php`. El backend comprueba que coincida con una sesión vigente y no revocada. Si la sesión no se valida, el frontend elimina el estado local y pide volver a iniciar sesión.”

## Si preguntan: “¿Por qué usan Pinia?”

> “Porque autenticación y rutina son estados que varias páginas necesitan compartir. Si estuvieran en una sola vista, se perderían al navegar. Pinia los mantiene disponibles durante la ejecución; `localStorage` conserva lo necesario entre recargas; y el backend sigue siendo quien valida la sesión real.”

## Si preguntan: “¿Cómo se genera una rutina?”

> “El usuario selecciona días. El frontend propone el tipo según la cantidad, y el backend valida y vuelve a aplicar esa regla. Después consulta ejercicios activos adecuados al nivel, guarda la rutina y sus relaciones, crea la planificación inicial y devuelve los datos para mostrarlos en pantalla.”

## Si preguntan: “¿El motor elige ejercicios con inteligencia artificial?”

> “No. En esta versión el generador sigue reglas codificadas en PHP: usa nivel, categoría, cantidad de días, listas de ejercicios y objetivos predefinidos. La etiqueta de interfaz no significa que haya aprendizaje automático.”

## Si preguntan: “¿Qué se guarda cuando termina una sesión?”

> “Se guarda la sesión, el detalle de ejercicios planificados y las series registradas. El backend también registra el movimiento de XP y actualiza el total y el nivel del usuario. El guardado se realiza en una transacción para evitar confirmar solo una parte.”

## Si preguntan: “¿Por qué no puedo pasar a la siguiente semana?”

> “La tabla tiene el campo semana y la planificación inicial se crea en semana uno, pero todavía no está implementado el proceso que determina que una semana se completó, la cambia de estado y activa la siguiente. La interfaz muestra cuatro etiquetas, pero S1 está fijada visualmente y no lee la planificación real.”

## Si preguntan: “¿El dashboard social ya está conectado?”

> “Hay una interfaz de dashboard con publicaciones, comentarios y reacciones, pero esos elementos se modifican en memoria local del componente. No encontré un endpoint que los guarde en PostgreSQL, así que actualmente es una demostración de interfaz, no una comunidad persistente entre usuarios.”

## Si preguntan: “¿Para qué sirve `packages/shared`?”

> “Es un paquete TypeScript preparado para compartir tipos y lógica entre workspaces. Hoy define roles y estados de suscripción. El flujo PHP actual no lo importa directamente; por eso no lo presento como la capa que valida las sesiones del sistema en ejecución.”

## Si preguntan: “¿Las carpetas de migraciones y seeds están funcionando?”

> “Las carpetas tienen documentación y están preparadas para ese propósito, pero en el estado actual no se encontró un conjunto de migraciones y seeds que la aplicación PHP ejecute. La estructura real de PostgreSQL se debe administrar con un proceso confirmado antes de desplegar cambios.”

## Si preguntan: “¿Cómo se asegura que la API no entregue datos a cualquier persona?”

> “Los endpoints privados que participan en rutina y registro de sesiones verifican el token. Además, las consultas usan parámetros PDO y las contraseñas se guardan como hash. Aún hay endpoints que requieren revisión; por ejemplo, `perfil.php` consulta por ID sin validar token. La protección del router es solamente del frontend, no reemplaza la autorización del servidor.”

---

# Mapa explicado de archivos y carpetas

Esta parte se puede usar como referencia durante el ensayo. No hace falta leer el inventario completo en la exposición, pero sí entender el propósito de cada grupo.

## Frontend: aplicación que se ejecuta en el navegador

`Frontend/index.html` es el documento que inicia la SPA y carga el punto de entrada. `Frontend/src/main.ts` crea Vue, Pinia y el router. `Frontend/src/App.vue` contiene el punto donde se inserta la vista de la ruta actual.

`Frontend/src/router/index.ts` declara las rutas y comprueba la autenticación antes de mostrar páginas privadas. Las vistas de `Frontend/src/views` son las páginas principales: inicio, registro, login, dashboard, perfil, rutinas y entrenamiento. `EntrenamientoView.vue` y `EntrenamientoTiempoReal.vue` corresponden al resumen de sesiones y a la ejecución detallada.

`Frontend/src/components` contiene piezas visuales utilizadas por las vistas. `Formulario.vue` y `FormLogin.vue` gestionan campos y eventos; `RutinaConfig.vue` permite elegir días y crear rutina; `Entrenamiento.vue` presenta el plan; los encabezados y barras organizan navegación y contexto.

`Frontend/src/stores/auth.store.ts` mantiene identidad, token lógico, estado de login y acciones de inicio/restauración/cierre. `Frontend/src/stores/rutina.store.ts` mantiene catálogo y rutina. `Frontend/src/stores/entrenamiento.store.ts` contiene datos y métricas de presentación que incluyen valores de ejemplo. `xp_historial.store.ts` existe, pero no se encontró conectado a una API de historial.

`Frontend/src/services/autenticar.service.ts` centraliza login, registro, verificación y logout. `Rutina_service.ts` centraliza catálogo, creación y registro de sesiones. `StorageService.ts` envuelve la persistencia en `localStorage`. `PasoService.ts` proporciona validaciones auxiliares usadas por autenticación.

`Frontend/src/DTO` define contratos TypeScript. `Auth.DTO.ts` concentra tipos de identidad, rutina, ejercicios y sesión; `EstadoSesion.ts` define el estado de autenticación. Los otros DTO y utilidades representan contratos o ejercicios auxiliares y deben evaluarse caso por caso antes de afirmar que participan en el flujo principal.

`Frontend/src/assets` contiene CSS e imágenes importables. `Frontend/public` contiene recursos servidos por ruta pública, como el favicon. `Frontend/vite.config.ts` configura alias y proxy hacia Apache. Los `tsconfig` configuran la comprobación de TypeScript; `package.json` define dependencias y scripts.

## Backend PHP fuera del repositorio

La carpeta que se inspeccionó es `C:\Apache24\htdocs\Backend`. No forma parte de la raíz `C:\Users\lucas\FITCORE`, por lo que los cambios en el repositorio y los cambios en el backend del servidor deben tratarse como superficies separadas.

`registro.php` crea usuarios. `login.php` comprueba contraseña y crea sesión. `verificar_sesion.php` valida token. `logout.php` revoca token. `roles.php` define roles del backend. `db.php` crea la conexión PDO.

`GeneradorEntrenamiento.php` carga catálogo y rutina existente. `Generador_rutina.php` procesa la configuración y persiste la rutina. `registrar_sesion.php` guarda sesiones, series y XP.

`perfil.php` consulta un perfil por ID, pero no valida el token en el código revisado. `diagnostico.php` y `inspeccionar_constraints.php` sirven para inspeccionar datos o restricciones y son herramientas administrativas; no son pantallas del recorrido normal del usuario. `consultar_araceli.php` parece un script de consulta de datos específico, no una API general del producto. `inspeccionar_constraints.php` puede generar un volcado JSON al ejecutarse; ese resultado no es una tabla ni parte del flujo de la aplicación.

## Base de datos

`database/migrations` y `database/seeds` están destinadas a administrar cambios de esquema y datos iniciales, respectivamente. Sus README describen el propósito, pero no se encontraron migraciones o seeds de PostgreSQL activas en esas carpetas.

No se mantiene un archivo SQL de esquema de referencia en el repositorio. Antes de modificar la base de datos, se debe verificar su estructura efectiva y las consultas que utilizan los PHP actuales.

## Paquete compartido y monorepo

`packages/shared/src/roles.ts` define tipos de rol, estados de suscripción y una función de ejemplo para evaluar acceso. `packages/shared/src/index.ts` exporta ese módulo. `packages/shared/package.json` configura el paquete `@fitcore/shared`. Existe una salida compilada en `dist`, pero este paquete no sustituye al login PHP y no se encontró integrado con la API de entrenamiento actual.

Los `package.json` de raíz y frontend habilitan workspaces y comandos npm. El proyecto tiene una estructura de monorepo, pero el backend PHP ubicado en `htdocs` está fuera de esos workspaces.

## Material de documentación y aprendizaje

`docs/README.md` define convenciones generales de documentación. `docs/FLUJOS-FITCORE.md` contiene este guion.

`archivos md` incluye documentación técnica y material de planes/MVP. Sus documentos pueden servir para entender decisiones y evolución, pero algunos quedaron desactualizados. Antes de utilizarlos para declarar que una función está terminada, hay que comprobar el código.

`teoria typescript` es un repositorio de aprendizaje organizado en temas: tipos, funciones, clases, genéricos, operadores, métodos de arrays, objetos, strings, números y fechas, errores, HTTP, arquitectura, configuración y testing. También hay ejercicios sobre persistencia, rutinas y XP. Es material formativo, no módulos que importe Vite al bundle de FITCORE.

## Carpetas auxiliares o previstas

`scripts/ruby` documenta scripts posibles para seeds, backups, reportes y mantenimiento; actualmente los scripts no están presentes. `.github/workflows` documenta una futura automatización CI/CD; no se encontraron workflows de pipeline activos. `.vscode` guarda configuraciones locales de editor. `query` existe en la raíz, pero su uso actual no está identificado en el flujo de aplicación.

---

# Recomendación final para el ensayo

> “Cuando practique, no voy a memorizar nombres de archivos sin explicar para qué están. Voy a seguir una sola historia: el usuario hace algo, el frontend recoge la acción, el store conserva lo que varias vistas necesitan, el servicio manda la solicitud, PHP valida la regla, PostgreSQL guarda o devuelve la información, y el resultado vuelve a la pantalla.
>
> Si no recuerdo un nombre técnico, puedo describir primero la responsabilidad y después mencionar el archivo. Lo importante es que se entienda por qué existe cada capa.
>
> También voy a decir con claridad cuándo una parte está implementada, cuándo es un prototipo y cuándo es una mejora pendiente. Esa precisión demuestra que conozco el proyecto y que puedo continuar desarrollándolo de forma responsable.”
