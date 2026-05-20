# Timers (setTimeout e setInterval) — El Reloj del Gym

### ¿Qué herramientas tenemos? (El Kit de Control) 🧰

Para manejar el tiempo en **ObsidiantFit**, usamos estas 5 herramientas principales:

1.  **`setTimeout`**: El temporizador. Ejecuta una función **una sola vez** después de esperar.
2.  **`setInterval`**: El cronómetro. Ejecuta una función **repetidamente** cada cierto tiempo.
3.  **`clearTimeout`**: El botón de pánico. Cancela un `setTimeout` antes de que ocurra.
4.  **`clearInterval`**: El botón de stop. Detiene un `setInterval` para que no siga por siempre.
5.  **`setImmediate`**: La vía rápida. Ejecuta algo **lo más pronto posible**, justo después de que termine lo que se está haciendo ahora.

---

## 10 Ejemplos de `setTimeout` (De Bebé a Harvard) 🏹

### 1. Nivel 5 años: El Saludo de Siesta

Le decimos "¡Hola!" al atleta 2 segundos después de que se despierte.

```typescript
setTimeout(() => console.log("¡Hola campeón! A entrenar."), 2000);
```

### 2. La Tostadora

Simulamos que el pan sale de la tostadora a los 5 segundos.

```typescript
console.log("Pan adentro...");
setTimeout(() => console.log("¡PAN LISTO! 🍞"), 5000);
```

### 3. El Cartel que desaparece

Un mensaje de "Guardado" que se borra a los 3 segundos.

```typescript
const mostrarCartel = () => {
  console.log("Guardando...");
  setTimeout(() => console.clear(), 3000);
};
```

### 4. Recordatorio de Agua

Avisar que tome agua después de 15 minutos de entrenamiento.

```typescript
setTimeout(() => console.log("¡Toma agua! 💧"), 15 * 60 * 1000);
```

### 5. Auto-Guardado de Rutina

Esperar un momento después de que el usuario deja de escribir para guardar.

```typescript
function autoGuardado() {
  console.log("Esperando pausa del usuario...");
  setTimeout(() => console.log("Rutina guardada en la nube ☁️"), 2000);
}
```

### 6. Simulador de Internet Lento

Retrasar la aparición de los ejercicios para que parezca que vienen de lejos.

```typescript
function cargarEjercicios() {
  console.log("Buscando en el servidor...");
  setTimeout(() => console.log(["Flexiones", "Dominadas", "Plancha"]), 1500);
}
```

### 7. El "Debounce" (Evitar doble clic)

Si el usuario hace mil clics seguidos, solo procesamos el último después de una pausa.

```typescript
let timer: any;
function clicEnBoton() {
  clearTimeout(timer);
  timer = setTimeout(() => console.log("Botón procesado una sola vez"), 500);
}
```

### 8. Reintento con Retraso (Retry Logic)

Si falla la conexión, esperamos 5 segundos para intentar de nuevo.

```typescript
function conectarServidor() {
  const exito = false;
  if (!exito) {
    console.log("Fallo. Reintentando en 5s...");
    setTimeout(conectarServidor, 5000);
  }
}
```

### 9. Limpieza de Memoria

Eliminar datos temporales que ya no sirven después de un tiempo para no llenar la RAM.

```typescript
let cacheSesion = { datos: "pesados" };
setTimeout(() => {
  cacheSesion = null;
  console.log("Memoria liberada");
}, 60000);
```

### 10. Nivel Harvard: Recursividad con Compensación de Tiempo

En lugar de un intervalo, usamos `setTimeout` recursivo para que cada ejecución empiece **exactamente** cuando terminó la anterior, evitando que se encimen si el código es lento.

```typescript
function tareaPesada() {
  const inicio = Date.now();
  console.log("Ejecutando proceso complejo...");

  // Simulamos proceso que tarda
  const delay = Math.random() * 1000;

  setTimeout(() => {
    const fin = Date.now();
    console.log(`Finalizado en ${fin - inicio}ms. Programando siguiente...`);
    tareaPesada(); // Se llama a sí misma
  }, 2000);
}
```

---

## 10 Ejemplos de `setInterval` (De Bebé a Harvard) 💓

### 1. Nivel 5 años: El Corazón del Gym

Suena cada segundo: ¡PUM! ¡PUM!

```typescript
setInterval(() => console.log("¡PUM! ❤️"), 1000);
```

### 2. El Reloj de Pared

Muestra la hora actual cada segundo.

```typescript
setInterval(() => console.log(new Date().toLocaleTimeString()), 1000);
```

### 3. Contador de Plancha

Dice los segundos que llevas aguantando.

```typescript
let seg = 0;
setInterval(() => console.log(`Aguanta... ${++seg}s`), 1000);
```

### 4. Semáforo de Entrenamiento

Cambia de color (Verde/Rojo) cada 5 segundos.

```typescript
let verde = true;
setInterval(() => {
  verde = !verde;
  console.log(verde ? "¡DALE! 🟢" : "¡PARA! 🔴");
}, 5000);
```

### 5. Barra de Carga Dinámica

Va llenando una barrita de progreso.

```typescript
let progreso = 0;
const barra = setInterval(() => {
  progreso += 10;
  console.log(`Cargando rutina: [${"=".repeat(progreso / 10)}] ${progreso}%`);
  if (progreso >= 100) clearInterval(barra);
}, 500);
```

### 6. Monitor de Pulso Cardiaco

Simula un sensor que lee tus pulsaciones cada 2 segundos.

```typescript
setInterval(() => {
  const pulso = 60 + Math.floor(Math.random() * 40);
  console.log(`Tu pulso: ${pulso} BPM`);
}, 2000);
```

### 7. Sistema EMOM Pro

Avisa el inicio de ronda y el descanso cada minuto exacto.

```typescript
let min = 0;
setInterval(() => {
  min++;
  console.log(`--- Minuto ${min} --- ¡NUEVA SERIE!`);
}, 60000);
```

### 8. Sincronización de Base de Datos

Cada 30 segundos, envía los cambios locales a la nube.

```typescript
setInterval(() => {
  console.log("Sincronizando datos con la nube... 🔄");
}, 30000);
```

### 9. Animación de Texto (Marquee)

Mueve un texto en la pantalla poco a poco.

```typescript
let texto = "ENTRENA DURO... ";
setInterval(() => {
  texto = texto.substring(1) + texto[0];
  console.clear();
  console.log(texto);
}, 200);
```

### 10. Nivel Harvard: Scheduler con Corrección de Jitter

Los intervalos en JS no son exactos (pueden retrasarse si la CPU está ocupada). Este código calcula el retraso y se "ajusta" para mantenerse siempre en el tiempo real.

```typescript
let tiempoEsperado = Date.now() + 1000;
function tickPreciso() {
  const drift = Date.now() - tiempoEsperado; // Qué tanto nos retrasamos
  console.log(`Tick ejecutado. Retraso (Jitter): ${drift}ms`);

  tiempoEsperado += 1000;
  setTimeout(tickPreciso, Math.max(0, 1000 - drift)); // Ajustamos el próximo salto
}
setTimeout(tickPreciso, 1000);
```

---

## 🚀 10 Desafíos de `setTimeout` (Ejercicios)

1. **Nivel 5 años**: Escribe una función que espere 2 segundos y luego imprima "¡Sorpresa! 🎁" en la consola.
2. **La Tostadora**: Crea una función `tostarPan()` que diga "Tostando..." y a los 4 segundos diga "¡Pan listo! 🍞".
3. **El Invitado Tarde**: Haz que un nombre aparezca en la consola, pero con un retraso de 3 segundos.
4. **Desvanecer Mensaje**: Simula un mensaje que se borra solo: imprime un texto y a los 5 segundos limpia la consola con `console.clear()`.
5. **Alarma de Agua**: Crea un recordatorio que te avise "Toma agua 💧" exactamente 10 segundos después de iniciar el programa.
6. **Carga de Perfil**: Haz una función que imprima "Cargando usuario..." y a los 1.5 segundos muestre un objeto con nombre y peso.
7. **Botón de Reintento**: Crea una lógica que si una variable `conectado` es false, espere 3 segundos y diga "Intentando conectar de nuevo...".
8. **Doble Check**: Escribe un código que imprima "Enviando..." y a los 2 segundos diga "Enviado", y 1 segundo después de eso diga "Leído". (Pista: un setTimeout dentro de otro).
9. **Simulador de Delay**: Crea una función que reciba un texto y un tiempo, y lo imprima después de ese tiempo.
10. **Nivel Harvard (Debounce)**: Crea una función que reciba otra función y un tiempo. Debe asegurar que la función recibida solo se ejecute si han pasado X milisegundos desde la última vez que se llamó.

---

## 🚀 10 Desafíos de `setInterval` (Ejercicios)

1. **Nivel 5 años**: Haz que la consola diga "¡HOLA! 👋" cada 1 segundo infinitamente.
2. **Contador Simple**: Crea un contador que empiece en 1 y vaya sumando de uno en uno cada medio segundo (500ms).
3. **Reloj Digital**: Muestra la hora actual (solo horas, minutos y segundos) y que se actualice cada segundo.
4. **Entrenamiento de Plancha**: Haz un contador que cuente de 1 a 30 segundos y, cuando llegue a 30, se detenga solo y diga "¡Plancha completada! 🔥".
5. **Semáforo Loco**: Haz que la consola alterne entre "VERDE 🟢" y "ROJO 🔴" cada 2 segundos.
6. **Barra de Carga**: Crea una variable `carga` que empiece en 0. Cada 100ms debe subir 10 puntos. Cuando llegue a 100, debe detenerse y decir "Carga completa".
7. **Monitor Cardíaco**: Simula un sensor que cada 3 segundos imprima un número aleatorio entre 60 y 100 (pulsaciones).
8. **Aviso de Serie (EMOM)**: Haz un programa que cada 10 segundos diga "¡Empieza nueva serie!" y cuente cuántas series llevas. Que se detenga al llegar a 5.
9. **Texto Animado**: Haz que un string como "Cargando..." vaya sumando un punto cada segundo (Cargando., Cargando.., Cargando...) y al cuarto punto vuelva a empezar.
10. **Nivel Harvard (Precisión)**: Crea un intervalo que cada segundo compare la hora real con la hora en la que "debería" haber sonado y calcule cuántos milisegundos de retraso tuvo el sistema.

---

### El Truco del Experto 💡

Nunca dejes un `setInterval` abierto si vas a cambiar de pantalla en tu app. Siempre guarda el ID y usa `clearInterval` o tu aplicación se volverá lenta y pesada.
