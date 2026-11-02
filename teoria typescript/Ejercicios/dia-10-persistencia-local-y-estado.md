# Día 10: Persistencia local y estado en el frontend (Edición FITCORE 🏋️‍♂️)

Vamos a hacerlo como una escalera de programación: 10 ejemplos reales, desde guardar una palabra hasta construir un sistema completo de preferencias y sesiones para la plataforma **FITCORE**.

Los ejemplos van a combinar **TypeScript**, **Vue 3** y **PHP**, aumentando la dificultad gradualmente. En cada nivel vas a aprender una herramienta nueva y reutilizar lo aprendido anteriormente.

La regla que vamos a seguir es sencilla:
- **TypeScript**: organiza y tipa los datos del atleta y del gimnasio.
- **Vue 3**: muestra las rutinas y métricas, reaccionando a sus cambios en tiempo real.
- **localStorage**: conserva información del entrenamiento en el navegador.
- **PHP**: administra sesiones seguras y responde a las peticiones del frontend.

Los primeros ejercicios funcionan solo en el navegador. Los últimos conectan Vue con PHP. No necesitás una base de datos para empezar.

---

## Nivel 1. Guardar el nombre del atleta
**Dificultad:** Muy fácil  
**Situación real:** En FITCORE, la aplicación recuerda el nombre del socio o atleta que ingresó, aunque actualice o cierre la pestaña del navegador.

### TypeScript
```typescript
const atleta: string = 'Lucas';

localStorage.setItem('atleta', atleta);

const atletaGuardado = localStorage.getItem('atleta');

console.log(atletaGuardado);
```

### ¿Qué sucede?
1. Creamos una variable de tipo `string`.
2. Guardamos el nombre con la clave `'atleta'`.
3. Recuperamos el valor guardado.
4. Mostramos el resultado por consola.

`setItem()` guarda y `getItem()` recupera. Si la clave no existe, `getItem()` devuelve `null`.

> **Práctica:** Cambiá el nombre por tu ejercicio favorito de FITCORE (por ejemplo `'Sentadilla con barra'`) y guardalo con la clave `'ejercicioFavorito'`.

---

## Nivel 2. Recordar si preferís el modo oscuro para entrenar
**Dificultad:** Fácil  
**Situación real:** Abrís la app de FITCORE en la sala de pesas y querés que recuerde si preferís el tema claro o el tema oscuro (modo nocturno/gimnasio con alto contraste).

### TypeScript
```typescript
type Tema = 'claro' | 'oscuro';

const tema: Tema = 'oscuro';

localStorage.setItem('tema', tema);

const temaGuardado = localStorage.getItem('tema');

console.log(temaGuardado);
```

### ¿Qué aprendés?
- `type Tema` limita los valores válidos para las variables declaradas con ese tipo. En TypeScript, `tema` solo puede ser `'claro'` u `'oscuro'`.
- Pero hay una diferencia importante: el dato recuperado de `localStorage` es texto (`string`) o `null`. TypeScript no verifica automáticamente en tiempo de ejecución que el texto guardado sea un tema válido.
- Por eso, al recuperar datos externos en FITCORE, conviene validarlos antes de utilizarlos.

---

## Nivel 3. Contador que recuerda cuántas series completaste
**Dificultad:** Fácil  
**Situación real:** En FITCORE, una pantalla de entrenamiento cuenta cuántas series o repeticiones marcaste como completadas y conserva el contador al recargar la página para no perder el progreso del día.

### Vue 3 + TypeScript
```vue
<script setup lang="ts">
import { ref, watch } from 'vue';

const seriesCompletadas = ref(
  Number(localStorage.getItem('seriesCompletadas') ?? 0)
);

watch(seriesCompletadas, (nuevoValor) => {
  localStorage.setItem('seriesCompletadas', String(nuevoValor));
});

function sumarSerie(): void {
  seriesCompletadas.value++;
}
</script>

<template>
  <h2>Series completadas hoy: {{ seriesCompletadas }}</h2>
  <button @click="sumarSerie">Completar serie (+1)</button>
</template>
```

### ¿Cómo funciona?
- `ref()` crea un dato reactivo para las series.
- `seriesCompletadas.value++` aumenta el valor al terminar una serie.
- `watch()` detecta el cambio automáticamente.
- `String()` convierte el número en texto para almacenarlo.
- `localStorage.setItem()` guarda el nuevo valor.
- Cuando recargás la página, el contador recupera el total de series guardado.

> **Detalle importante:** Este ejemplo sirve para aprender, pero no valida si el valor guardado es realmente un número. Si alguien modifica el almacenamiento local manualmente, podría introducir un valor inválido como `NaN`.

---

## Nivel 4. Guardar la ficha del atleta usando JSON
**Dificultad:** Fácil a intermedio  
**Situación real:** El perfil de FITCORE recuerda tu nombre de atleta, tu sede asignada y si tenés habilitado el plan premium de entrenamiento.

### TypeScript
```typescript
interface PerfilAtleta {
  nombre: string;
  sede: string;
  planPremium: boolean;
}

const perfilAtleta: PerfilAtleta = {
  nombre: 'Lucas',
  sede: 'FITCORE Central',
  planPremium: true
};

// Guardar el objeto como texto JSON
localStorage.setItem(
  'perfilAtleta',
  JSON.stringify(perfilAtleta)
);

// Recuperar el texto
const texto = localStorage.getItem('perfilAtleta');

if (texto !== null) {
  const datos = JSON.parse(texto) as PerfilAtleta;

  console.log(datos.nombre);
  console.log(datos.sede);
  console.log(datos.planPremium);
}
```

### ¿Qué conceptos aparecen?
- `interface`: describe la estructura esperada del objeto del atleta.
- `JSON.stringify()`: convierte el objeto en texto JSON legible para el storage.
- `JSON.parse()`: transforma el texto recuperado nuevamente en un objeto de JavaScript.
- `if`: evita intentar interpretar un valor inexistente (`null`).

> **Cuidado:** `as PerfilAtleta` le indica al compilador qué tipo esperás, pero no comprueba los datos en ejecución. En una aplicación real de FITCORE, además hay que controlar errores de JSON inválido y validar la estructura recuperada.

---

## Nivel 5. Crear tu propio StorageService para FITCORE
**Dificultad:** Intermedio  
**Situación real:** En FITCORE tenés varias pantallas (rutinas, perfil, ejercicios, métricas) y querés evitar escribir `localStorage.setItem()` y `JSON.parse()` repetidamente en todas partes. Ahora vamos a centralizar esas operaciones en una clase reutilizable.

### Archivo `StorageService.ts`
```typescript
export class StorageService {
  static guardar<T>(clave: string, valor: T): void {
    const texto = JSON.stringify(valor);
    localStorage.setItem(clave, texto);
  }

  static obtener<T>(clave: string): T | null {
    const texto = localStorage.getItem(clave);

    if (texto === null) {
      return null;
    }

    return JSON.parse(texto) as T;
  }

  static eliminar(clave: string): void {
    localStorage.removeItem(clave);
  }
}
```

### Archivo `main.ts` o cualquier módulo de FITCORE que lo utilice
```typescript
import { StorageService } from './StorageService';

// Guardamos el atleta activo
StorageService.guardar('atleta', 'Lucas');

const atleta = StorageService.obtener<string>('atleta');

console.log(atleta);

StorageService.eliminar('atleta');
```

### ¿Qué aprendés?
- `class`: agrupa operaciones relacionadas de almacenamiento.
- `static`: permite llamar métodos directamente (`StorageService.guardar(...)`) sin tener que instanciar la clase con `new`.
- `<T>`: genérico que permite guardar y recuperar cualquier tipo de dato (números, strings o modelos de rutinas).
- `guardar()`, `obtener()` y `eliminar()`: definen una interfaz limpia y unificada para todo el frontend de FITCORE.

> **Límite de este ejemplo:** El servicio no valida a fondo si el contenido recuperado coincide con la firma ni captura excepciones de parsing corrupto. Es una primera versión didáctica y limpia.

---

## Nivel 6. Guardar automáticamente las preferencias de entrenamiento
**Dificultad:** Intermedio  
**Situación real:** La aplicación de FITCORE recuerda automáticamente el tema visual y la unidad de peso preferida (`'kg'` o `'lbs'`) configuradas por el atleta.

### Archivo `preferencias.ts`
```typescript
import { reactive, watch } from 'vue';

interface PreferenciasFitcore {
  tema: 'claro' | 'oscuro';
  unidad: 'kg' | 'lbs';
}

function cargarPreferencias(): PreferenciasFitcore {
  const texto = localStorage.getItem('preferencias_fitcore');

  if (texto !== null) {
    try {
      const datos: unknown = JSON.parse(texto);

      if (
        typeof datos === 'object' &&
        datos !== null &&
        'tema' in datos &&
        'unidad' in datos &&
        (datos.tema === 'claro' || datos.tema === 'oscuro') &&
        (datos.unidad === 'kg' || datos.unidad === 'lbs')
      ) {
        return {
          tema: datos.tema,
          unidad: datos.unidad
        };
      }
    } catch {
      // Si el JSON está dañado o manipulado, usamos valores por defecto.
    }
  }

  return {
    tema: 'oscuro',
    unidad: 'kg'
  };
}

export const preferencias = reactive<PreferenciasFitcore>(
  cargarPreferencias()
);

watch(
  preferencias,
  (nuevasPreferencias) => {
    localStorage.setItem(
      'preferencias_fitcore',
      JSON.stringify(nuevasPreferencias)
    );
  }
);
```

### Componente `PreferenciasPage.vue`
```vue
<script setup lang="ts">
import { preferencias } from './preferencias';
</script>

<template>
  <h2>Configuración de FITCORE</h2>

  <label>
    Tema visual:
    <select v-model="preferencias.tema">
      <option value="claro">Modo Claro</option>
      <option value="oscuro">Modo Oscuro (Gym)</option>
    </select>
  </label>

  <label>
    Unidad de peso:
    <select v-model="preferencias.unidad">
      <option value="kg">Kilogramos (kg)</option>
      <option value="lbs">Libras (lbs)</option>
    </select>
  </label>
</template>
```

### ¿Qué ocurre ahora?
- Al iniciar, la aplicación intenta recuperar las preferencias del atleta.
- Si no existen o son inválidas, utiliza valores iniciales seguros (`'oscuro'`, `'kg'`).
- `reactive()` permite que Vue observe reactivamente las modificaciones internas del objeto.
- `v-model` vincula los selectores directamente con el estado reactivo.
- `watch()` guarda las modificaciones automáticamente en `localStorage`.
- Esta versión es mucho más robusta porque contempla que el contenido guardado esté dañado o no tenga los tipos esperados.

---

## Nivel 7. Carrito de suplementos y equipamiento FITCORE que recuerda los productos
**Dificultad:** Intermedio avanzado  
**Situación real:** En la tienda oficial de FITCORE, agregás suplementos y accesorios al carrito, cerrás la pestaña para ir a entrenar y al regresar los productos siguen en el carrito listos para confirmar la compra.

Vamos a guardar la lista de productos en `localStorage` y utilizar Vue para actualizar el resumen en pantalla.

### Componente `CarritoFitcore.vue`
```vue
<script setup lang="ts">
import { ref, watch } from 'vue';

interface ProductoFitcore {
  id: number;
  nombre: string;
  precio: number;
}

const productosDisponibles: ProductoFitcore[] = [
  { id: 1, nombre: 'Proteína Whey FITCORE 1kg', precio: 28000 },
  { id: 2, nombre: 'Creatina Monohidratada 300g', precio: 19500 },
  { id: 3, nombre: 'Cinturón de Fuerza FITCORE', precio: 34000 }
];

function cargarCarrito(): ProductoFitcore[] {
  try {
    const texto = localStorage.getItem('carrito_fitcore');

    if (texto === null) return [];

    const datos: unknown = JSON.parse(texto);

    if (!Array.isArray(datos)) return [];

    return datos.filter((item): item is ProductoFitcore =>
      typeof item === 'object' &&
      item !== null &&
      typeof item.id === 'number' &&
      typeof item.nombre === 'string' &&
      typeof item.precio === 'number'
    );
  } catch {
    return [];
  }
}

const carrito = ref<ProductoFitcore[]>(cargarCarrito());

watch(
  carrito,
  (nuevoCarrito) => {
    localStorage.setItem('carrito_fitcore', JSON.stringify(nuevoCarrito));
  },
  { deep: true }
);

function agregar(producto: ProductoFitcore): void {
  if (!carrito.value.some((p) => p.id === producto.id)) {
    carrito.value.push(producto);
  }
}

function quitar(id: number): void {
  carrito.value = carrito.value.filter((p) => p.id !== id);
}

const total = () =>
  carrito.value.reduce((suma, producto) => suma + producto.precio, 0);
</script>

<template>
  <h2>Suplementos y Accesorios FITCORE</h2>

  <div v-for="producto in productosDisponibles" :key="producto.id">
    {{ producto.nombre }}: ${{ producto.precio }}
    <button @click="agregar(producto)">Agregar al carrito</button>
  </div>

  <h2>Mi carrito de compras</h2>

  <div v-for="producto in carrito" :key="producto.id">
    {{ producto.nombre }} - ${{ producto.precio }}
    <button @click="quitar(producto.id)">Quitar</button>
  </div>

  <p><strong>Total acumulado:</strong> ${{ total() }}</p>
</template>
```

### Lo importante de este ejemplo
- `ProductoFitcore` define la estructura tipada de cada artículo.
- `ref<ProductoFitcore[]>` representa una lista reactiva.
- `push()` agrega un artículo a la lista.
- `filter()` permite removerlo por su identificador.
- `reduce()` calcula el monto total sumando los precios.
- `watch()` con `{ deep: true }` sincroniza el carrito con el almacenamiento cada vez que cambia su contenido.

> **Advertencia real:** Este carrito en el cliente es una ayuda visual. Una plataforma profesional como FITCORE debe calcular el total definitivo en el backend y jamás confiar ciegamente en precios o identificadores modificables desde las herramientas de desarrollador del navegador.

---

## Nivel 8. PHP recuerda al usuario de FITCORE entre peticiones
**Dificultad:** Intermedio avanzado  
**Situación real:** Iniciás sesión en FITCORE con tu cuenta de socio o coach y luego navegás a tu perfil o plan de entrenamiento sin tener que identificarte nuevamente en cada página.

Ahora empezamos con PHP en el backend. Creá un archivo llamado `sesion.php`.

### PHP: `sesion.php`
```php
<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'login':
        // Simulación educativa para FITCORE.
        // Todavía no valida una contraseña real contra la base de datos.
        session_regenerate_id(true);

        $_SESSION['usuario'] = 'Lucas';
        $_SESSION['rol'] = 'atleta';

        echo json_encode([
            'status' => 'ok',
            'mensaje' => 'Sesión iniciada en FITCORE',
            'rol' => $_SESSION['rol']
        ]);
        break;

    case 'perfil':
        if (!isset($_SESSION['usuario'])) {
            http_response_code(401);

            echo json_encode([
                'error' => 'Debes iniciar sesión en FITCORE'
            ]);
            break;
        }

        echo json_encode([
            'usuario' => $_SESSION['usuario'],
            'rol' => $_SESSION['rol'] ?? 'socio'
        ]);
        break;

    case 'logout':
        $_SESSION = [];
        session_destroy();

        echo json_encode([
            'mensaje' => 'Sesión finalizada en FITCORE'
        ]);
        break;

    default:
        http_response_code(400);

        echo json_encode([
            'error' => 'Acción desconocida'
        ]);
}
```

### Probalo en el navegador
Si el servidor PHP está funcionando y el archivo está en la ruta indicada:
1. Abrí `sesion.php?accion=login`.
2. Después abrí `sesion.php?accion=perfil`.
3. Finalmente, abrí `sesion.php?accion=logout`.
4. Volvé a abrir `sesion.php?accion=perfil`.

El perfil debería responder con un error de autenticación (`HTTP 401`) después de destruir la sesión.

> Este ejemplo es deliberadamente sencillo. Para un cierre de sesión completo en producción, también deberías invalidar la cookie de sesión del navegador. Para un inicio de sesión real, necesitás validar credenciales (hash de contraseña con `password_verify`) antes de crear la sesión.

### ¿Qué aprendiste?
- `session_start()` recupera la sesión activa.
- `$_SESSION` permite guardar y leer datos en ella en el servidor.
- La cookie de sesión (`PHPSESSID`) viaja automáticamente en las cabeceras HTTP para que PHP reconozca al cliente en las siguientes peticiones.
- No necesitaste guardar datos sensibles en `localStorage` para que PHP los recordara: el servidor mantiene el estado de autenticación en su propia sesión.

---

## Nivel 9. Vue consulta el perfil del atleta en PHP con `fetch()`
**Dificultad:** Avanzado  
**Situación real:** La aplicación de FITCORE muestra el perfil del atleta logueado luego de consultar al backend de PHP de manera asíncrona.

Para este ejemplo, suponemos que el endpoint PHP del nivel anterior está disponible en `/sesion.php` y que el frontend puede acceder a él.

### Componente `PerfilAtleta.vue`
```vue
<script setup lang="ts">
import { ref, onMounted } from 'vue';

interface PerfilAtleta {
  usuario: string;
  rol?: string;
}

const perfil = ref<PerfilAtleta | null>(null);
const cargando = ref(true);
const error = ref('');

async function cargarPerfil(): Promise<void> {
  cargando.value = true;
  error.value = '';

  try {
    const response = await fetch('/sesion.php?accion=perfil', {
      credentials: 'include'
    });

    if (!response.ok) {
      throw new Error('No se pudo obtener el perfil de FITCORE');
    }

    perfil.value = (await response.json()) as PerfilAtleta;
  } catch {
    error.value = 'No se pudo cargar el perfil del atleta';
  } finally {
    cargando.value = false;
  }
}

onMounted(cargarPerfil);
</script>

<template>
  <p v-if="cargando">Cargando perfil de FITCORE...</p>

  <p v-else-if="error">{{ error }}</p>

  <div v-else-if="perfil">
    <h2>Bienvenido a FITCORE, {{ perfil.usuario }}</h2>
    <p v-if="perfil.rol">Rol asignado: {{ perfil.rol }}</p>
  </div>
</template>
```

### Cómo se conectan las piezas
- `onMounted()` ejecuta la consulta cuando el componente se monta en la vista.
- `fetch()` envía la petición HTTP al endpoint de PHP.
- `credentials: 'include'` es indispensable para enviar las cookies de sesión del navegador al servidor.
- `response.ok` comprueba si el código de estado HTTP está en el rango exitoso (200-299).
- `response.json()` interpreta el cuerpo de la respuesta en formato JSON.
- `ref()` mantiene el resultado y los estados de carga y error reactivos.
- `finally` asegura apagar el indicador de carga tanto si la petición tuvo éxito como si falló.

> **Importante:** El ejemplo supone que la sesión ya fue iniciada en el servidor. Si no existe, PHP devolverá `401`, y Vue mostrará el mensaje de error. Si Vue y PHP corren en puertos u orígenes distintos (por ejemplo Vite en `:5173` y PHP en `:8000`), necesitás configurar CORS y permitir credenciales, o utilizar el proxy de desarrollo de Vite.

---

## Nivel 10. Sistema integrado: preferencias, sesión y perfil en FITCORE
**Dificultad:** Avanzado profesional  
**Situación real:** Este último nivel une todos los conceptos. La aplicación FITCORE recuerda el tema visual en el navegador del atleta, consulta su sesión en PHP, muestra su información de socio y permite cerrar la sesión.

Para mantener el ejemplo manejable, separamos los archivos en backend y frontend.

### A. PHP: `api.php`
Este endpoint utiliza sesiones y simula la autenticación en FITCORE.

```php
<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

$accion = $_GET['accion'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

switch ($accion) {
    case 'login':
        // Simulación: en producción validar usuario y contraseña en base de datos.
        session_regenerate_id(true);

        $_SESSION['usuario_id'] = 25;
        $_SESSION['nombre'] = 'Lucas';
        $_SESSION['rol'] = 'Atleta Pro';

        echo json_encode(['status' => 'ok']);
        break;

    case 'perfil':
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);

            echo json_encode(['error' => 'No autenticado en FITCORE']);
            exit;
        }

        echo json_encode([
            'id' => $_SESSION['usuario_id'],
            'nombre' => $_SESSION['nombre'],
            'rol' => $_SESSION['rol'] ?? 'Socio'
        ]);
        break;

    case 'logout':
        $_SESSION = [];
        session_destroy();

        echo json_encode(['status' => 'ok']);
        break;

    default:
        http_response_code(400);

        echo json_encode(['error' => 'Acción desconocida']);
}
```

> En este ejercicio, cada acción utiliza `POST`. En una aplicación real, conviene definir endpoints y verbos HTTP según su propósito (por ejemplo `GET /perfil` y `POST /login`).

---

### B. Vue + TypeScript: `App.vue`
Este componente recuerda el tema preferido en el almacenamiento local y consulta el perfil del atleta al servidor.

```vue
<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';

interface PerfilAtleta {
  id: number;
  nombre: string;
  rol: string;
}

type Tema = 'claro' | 'oscuro';

const temaGuardado = localStorage.getItem('tema');

const tema = ref<Tema>(
  temaGuardado === 'oscuro' ? 'oscuro' : 'claro'
);

const perfil = ref<PerfilAtleta | null>(null);
const cargando = ref(false);
const error = ref('');

watch(tema, (nuevoTema) => {
  localStorage.setItem('tema', nuevoTema);
});

async function consultarPerfil(): Promise<void> {
  cargando.value = true;
  error.value = '';

  try {
    const response = await fetch('/api.php?accion=perfil', {
      method: 'POST',
      credentials: 'include'
    });

    const datos = await response.json();

    if (!response.ok) {
      throw new Error(datos.error ?? 'Error al consultar perfil');
    }

    perfil.value = datos as PerfilAtleta;
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Error desconocido';
  } finally {
    cargando.value = false;
  }
}

async function iniciarSesionDemo(): Promise<void> {
  const response = await fetch('/api.php?accion=login', {
    method: 'POST',
    credentials: 'include'
  });

  if (!response.ok) {
    error.value = 'No se pudo iniciar la sesión en FITCORE';
    return;
  }

  await consultarPerfil();
}

async function cerrarSesion(): Promise<void> {
  const response = await fetch('/api.php?accion=logout', {
    method: 'POST',
    credentials: 'include'
  });

  if (!response.ok) {
    error.value = 'No se pudo cerrar la sesión';
    return;
  }

  perfil.value = null;
}

onMounted(consultarPerfil);
</script>

<template>
  <main :class="tema">
    <header>
      <h1>FITCORE - Portal de Atletas</h1>

      <label>
        Tema:
        <select v-model="tema">
          <option value="claro">Claro</option>
          <option value="oscuro">Oscuro (Gym Mode)</option>
        </select>
      </label>
    </header>

    <p v-if="cargando">Cargando datos de FITCORE...</p>
    <p v-if="error" class="alerta">{{ error }}</p>

    <section v-if="perfil">
      <h2>¡Hola, {{ perfil.nombre }}! 🏋️‍♂️</h2>
      <p>ID Socio: #{{ perfil.id }}</p>
      <p>Membresía / Rol: {{ perfil.rol }}</p>
      <button @click="cerrarSesion">Cerrar sesión</button>
    </section>

    <section v-else>
      <p>No has iniciado sesión en el gimnasio.</p>
      <button @click="iniciarSesionDemo">
        Iniciar sesión de prueba (Socio Demo)
      </button>
    </section>
  </main>
</template>

<style scoped>
main {
  min-height: 100vh;
  padding: 2rem;
  font-family: sans-serif;
  transition: background-color 0.3s, color 0.3s;
}

.claro {
  background: #f7f9fa;
  color: #1e293b;
}

.oscuro {
  background: #0f172a;
  color: #f8fafc;
}

.alerta {
  color: #ef4444;
}

button {
  margin-top: 1rem;
  padding: 0.6rem 1.2rem;
  font-weight: bold;
  border-radius: 6px;
  cursor: pointer;
}
</style>
```

---

### C. ¿Qué conceptos reúne este último ejemplo?
- **TypeScript**: `interface PerfilAtleta` y `type Tema` describen con precisión las estructuras de datos y tipos literales esperados.
- **Vue 3**: `ref()` mantiene reactivos el perfil, los estados de carga y los mensajes de error.
- **`watch()`**: observa cambios en `tema` y los persiste automáticamente en el navegador.
- **`localStorage`**: recuerda el tema seleccionado por el usuario al recargar la app.
- **`fetch()`**: canaliza las peticiones asíncronas entre el frontend de Vue y el backend de PHP.
- **Cookies**: permiten que el navegador transmita el identificador de sesión automáticamente cuando corresponde.
- **PHP**: valida la existencia de la sesión en el servidor antes de autorizar y entregar los datos del perfil.
- **`try...catch...finally`**: proporciona un flujo robusto de manejo de errores e indicadores visuales de carga.

> Para ejecutar este ejemplo en desarrollo, los archivos deben servirse mediante un entorno con soporte PHP y la ruta `/api.php` debe ser accesible desde el frontend. Para llevarlo a producción en FITCORE, sumaremos: autenticación real con contraseñas seguras, validación de schemas de entrada, protección contra ataques CSRF, cookies con flags `HttpOnly; Secure; SameSite` y verificación de roles y permisos por endpoint.

---

## Resumen de la escalera

| Nivel | Qué construiste en FITCORE | Aprendizaje principal |
| :---: | :--- | :--- |
| **1** | Guardar el nombre del atleta | `localStorage.setItem()` y `getItem()` |
| **2** | Recordar el modo oscuro para entrenar | Tipos literales en TypeScript |
| **3** | Contador persistente de series completadas | `ref()` y `watch()` en Vue 3 |
| **4** | Ficha del atleta estructurada | `JSON.stringify()` y `JSON.parse()` |
| **5** | Servicio reutilizable `StorageService` | Clases, métodos `static` y genéricos `<T>` |
| **6** | Preferencias reactivas de entrenamiento | Validación con type guards, `reactive()` y `watch()` |
| **7** | Carrito de suplementos y equipamiento | Arrays tipados, `filter()`, `reduce()` y persistencia profunda |
| **8** | Sesión de usuario en PHP | `session_start()` y superglobal `$_SESSION` |
| **9** | Consulta asíncrona del perfil del atleta | `fetch()`, `credentials: 'include'` y respuestas HTTP |
| **10** | Portal integral de FITCORE | Sincronización de estado local, sesión en servidor y comunicación fullstack |
