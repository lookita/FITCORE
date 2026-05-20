# Promises — El Ticket del Gimnasio

### ¿Qué es esto? (La analogía para un niño de 10 años) 🎟️
Imagina que pides una hamburguesa en un restaurante. No te la dan enseguida. Te dan un **vibrador** que suena cuando la comida está lista.
1. **Pendiente**: El vibrador está quieto. La cocina está trabajando.
2. **Cumplida (Resolved)**: ¡El vibrador suena! Tu hamburguesa está lista y vas a buscarla.
3. **Rechazada (Rejected)**: El camarero viene y te dice: "Lo siento, se acabaron las hamburguesas".


### 💡 Diccionario de Apodos (Nombres que verás mucho)
Cuando trabajas con Promesas, los programadores usamos "apodos" para las variables. No son obligatorios, ¡pero todos los usan!

*   **`res` / `resp`**: Abreviatura de *Response* (Respuesta). Es la cajita donde llega lo que pediste.
*   **`rej`**: Abreviatura de *Reject*. Es cuando algo sale mal.
*   **`err`**: Abreviatura de *Error*. Es lo que recibes cuando la promesa falla.
*   **`data`**: Abreviatura de *Datos*. Se usa cuando lo que recibes es una lista de cosas (como tus rutinas).

**Recuerda**: Tú puedes ponerle el nombre que quieras, pero usar estos ayuda a que otros programadores entiendan tu código más rápido.

---

## 10 Ejemplos de Promises (Explicados Paso a Paso) 🚀

### 1. Esperando el Juguete 🧸
**Paso a paso:**
1. Creamos la promesa: "Te daré un oso".
2. Ponemos un cronómetro de 2 segundos.
3. Cuando el reloj suena, usamos `resolve` para entregarte el oso.
```typescript
const promesaJuguete = new Promise((resolve) => {
  setTimeout(() => resolve("🧸 ¡Toma tu oso!"), 2000);
});
```

### 2. La Tostadora 🍞
**Paso a paso:**
1. Metemos el pan (creamos la promesa).
2. Esperamos 3 segundos.
3. El pan salta (`resolve`) y ya puedes desayunar.
```typescript
const hacerTostada = new Promise((resolve) => {
  setTimeout(() => resolve("🍞 Tostada lista"), 3000);
});
```

### 3. El Paquete de Amazon 📦
**Paso a paso:**
1. Haces el pedido.
2. Como el repartidor es súper rápido, usa `resolve` de inmediato.
3. El paquete ya está en tu puerta.
```typescript
const pedidoAmazon = new Promise((resolve) => {
  resolve("📦 Paquete entregado");
});
```

### 4. Cargar un Ejercicio Solo 🏋️
**Paso a paso:**
1. Le pides a ObsidiantFit que busque la "Sentadilla".
2. La computadora busca en su memoria (la base de datos).
3. Cuando la encuentra, te la entrega con `resolve`.
```typescript
function cargarEjercicio() {
  return new Promise((resolve) => {
    resolve({ nombre: "Sentadilla", musculo: "Piernas" });
  });
}
```

### 5. Guardar Entrenamiento ☁️
**Paso a paso:**
1. Terminas tu rutina y pulsas "Guardar".
2. La promesa dice: "Subiendo...".
3. A los 1.5 segundos, el servidor dice: "¡Recibido! (`resolve`)".
```typescript
const guardarEnNube = new Promise((resolve) => {
  console.log("Subiendo...");
  setTimeout(() => resolve("✅ Guardado correctamente"), 1500);
});
```

### 6. El Pago Rechazado ❌
**Paso a paso:**
1. Intentas comprar la suscripción.
2. El sistema mira tu hucha (dinero).
3. Como tienes 0, usa `reject` para decirte que no puedes comprarla.
```typescript
const pagarSuscripcion = new Promise((resolve, reject) => {
  const dinero = 0;
  if (dinero > 0) resolve("Suscripción activa");
  else reject("❌ Saldo insuficiente");
});
```

### 7. Encadenar Pasos (La fila del súper) 🛒
**Paso a paso:**
1. Primero obtienes tu ticket de usuario (`.then`).
2. CON ese ticket, pides tus rutinas (`.then`).
3. Al final, muestras las rutinas en pantalla. Es como una fila donde un paso sigue al otro.
```typescript
obtenerUsuario()
  .then(user => obtenerRutinas(user.id))
  .then(rutinas => console.log(rutinas));
```

### 8. Carrera de Servidores 🏎️
**Paso a paso:**
1. Mandas dos mensajeros a dos tiendas distintas.
2. `Promise.race` dice: "Me quedo con el primero que llegue".
3. El que llegue segundo es ignorado.
```typescript
Promise.race([fetch("TiendaA"), fetch("TiendaB")])
  .then(res => console.log("Llegó primero: " + res.url));
```

### 9. Todo o Nada (Los Mosqueteros) ⚔️
**Paso a paso:**
1. Necesitas cargar 3 cosas para empezar el gym.
2. `Promise.all` espera a que las TRES estén listas.
3. Si una sola falla, el entrenamiento no empieza. "Uno para todos y todos para uno".
```typescript
Promise.all([musica, ejercicios, cronometro])
  .then(() => console.log("¡Todo listo para empezar!"));
```

### 10. Nivel Harvard: Mi Propia Máquina de Promesas 🧠

Imagina que estás construyendo una **máquina expendedora** desde cero. Vamos a ver qué hace cada **pieza del código** y por qué está escrita así:

#### 🔍 Desmontando el Código (Pieza por Pieza)

*   **`private estado`, `private valor`, `private callbacks`**: 
    *   **`private`**: Es un candado 🔒. Solo el código de adentro puede cambiar estas cosas. Evita que alguien externo "haga trampa" y cambie el estado sin permiso.
    *   **`callbacks: any[] = []`**: Es un **Array** (una lista) que empieza vacío. Guardamos ahí las funciones de la gente que está esperando.
*   **`constructor(ejecutor)`**: Es la función que se activa al hacer `new MiPromesa()`. Recibe la tarea (`ejecutor`) y la pone a funcionar.
*   **`const resolver = (val) => { ... }`**: Es el **Botón de ¡LISTO!** 🔔 que le prestamos al trabajador:
    1. Cambia el estado a `CUMPLIDA`.
    2. Guarda el resultado en `valor`.
    3. Usa un **`forEach`** para recorrer la lista de espera (`callbacks`) y avisarles a todos entregándoles el valor.
*   **`then(callback)`**: Es cuando alguien se anota para recibir el resultado.
    *   Si ya terminamos, se lo damos ahora mismo.
    *   Si no, usamos **`push`** para meter su nombre en la lista de espera (`callbacks`).

```typescript
class MiPromesa {
  // 1. ESTADO: Es como un semáforo interno. Empieza en 'PENDIENTE' (luz amarilla).
  // Usamos 'private' para que NADIE desde fuera de esta clase pueda cambiar la luz.
  private estado = 'PENDIENTE'; 

  // 2. VALOR: Es la caja donde guardaremos el "regalo" o resultado final.
  // Es 'any' porque la promesa puede devolver CUALQUIER cosa (un texto, un número, etc).
  private valor: any; 

  // 3. CALLBACKS: Es una lista de espera (un Array).
  // Aquí anotamos las funciones de todas las personas que han pedido el resultado.
  private callbacks: any[] = []; 

  constructor(ejecutor: any) {
    // 4. RESOLVER: Es una función que nosotros creamos y le damos al trabajador.
    // Es como el botón de "TAREA TERMINADA".
    const resolver = (val: any) => {
      this.estado = 'CUMPLIDA'; // Cambiamos el semáforo a luz verde 🟢.
      this.valor = val;         // Metemos el resultado en la caja de seguridad.

      // 5. AVISAR A TODOS: Como ya terminamos, recorremos la lista de espera (callbacks).
      // A cada función de la lista ('cb') le pasamos el resultado ('val').
      this.callbacks.forEach(cb => cb(val)); 
    };

    // 6. TRABAJAR: Aquí activamos al trabajador ('ejecutor') y le pasamos el botón 'resolver'.
    // Él hará su trabajo y pulsará el botón cuando esté listo.
    ejecutor(resolver); 
  }

  // 7. MÉTODO THEN: Es la forma en que los usuarios se anotan para recibir el resultado.
  then(callback: any) {
    // Si la luz ya está VERDE, significa que el regalo ya está en la caja.
    // Se lo entregamos al usuario inmediatamente.
    if (this.estado === 'CUMPLIDA') {
      callback(this.valor);
    } else {
      // Si la luz sigue AMARILLA, anotamos al usuario en la lista de espera ('push').
      // Así le avisaremos en cuanto el trabajador pulse el botón 'resolver'.
      this.callbacks.push(callback);
    }
  }
}
```

---

## 🔌 Conectando los Cables: El Flujo Real de ObsidiantFit

¿Por qué usamos tantas propiedades? Porque necesitamos conectar piezas de código como si fueran cables de colores en un circuito eléctrico.

### 🧠 La Lógica del Circuito
1. **`new Promise`**: Es el cable que instalamos.
2. **`resolve`**: Es la energía positiva ✅. Dice: "¡Llegó la información!".
3. **`reject`**: Es un cortocircuito ❌. Dice: "Hubo un error".
4. **`.then()`**: Es el siguiente tramo del cable. Solo deja pasar la energía si el paso anterior fue un `resolve`.
5. **`.catch()`**: Es el fusible de seguridad. Atrapa cualquier chispazo o error de todo el circuito.

### 🏋️ Flujo de Inicio de Sesión (Ejemplo Completo)

Imagina que este es el código que se ejecuta cuando abres la App:

```typescript
// PASO 1: Revisar si el teléfono tiene señal
const revisarSeñal = () => {
  return new Promise((resolve, reject) => {
    console.log("📡 Buscando señal...");
    setTimeout(() => {
      const haySeñal = true;
      if (haySeñal) resolve("Señal de Internet OK"); // ✅ Enviamos energía
      else reject("Sin conexión");                   // ❌ Cortamos energía
    }, 1000);
  });
};

// PASO 2: Pedir los datos del usuario Lucas
const pedirPerfil = (mensajeSignal: string) => {
  return new Promise((resolve) => {
    console.log(`Logueando (${mensajeSignal})...`);
    setTimeout(() => {
      resolve({ id: 1, nombre: "Lucas", nivel: "Pro" }); // ✅ Pasamos el perfil
    }, 1000);
  });
};

// PASO 3: Cargar ejercicios según el nivel del usuario
const cargarRutinas = (perfil: any) => {
  return new Promise((resolve) => {
    console.log(`Cargando rutinas para nivel: ${perfil.nivel}...`);
    setTimeout(() => {
      resolve(["Dominadas explosivas", "Flexiones a una mano"]); // ✅ Lista final
    }, 1000);
  });
};

// --- EL CIRCUITO CONECTADO (La Magia) ---
revisarSeñal()
  .then(resultado => pedirPerfil(resultado))    // Si hay señal -> Pedir perfil
  .then(usuario => cargarRutinas(usuario))       // Si hay perfil -> Cargar rutinas
  .then(lista => {                               // Si hay rutinas -> Mostrarlas
    console.log("¡Todo listo! Hoy entrenamos:", lista);
  })
  .catch(error => {                              // Si CUALQUIERA falla...
    console.error("🚨 ERROR EN LA APP:", error);
  });
```

**¿Cómo viaja la información?**
La información es como una pelota que se pasan los jugadores:
1. `revisarSeñal` lanza la pelota (`resolve`).
2. El primer `.then` la atrapa y se la pasa a `pedirPerfil`.
3. `pedirPerfil` la recibe, hace su trabajo y la lanza al siguiente `.then`.
4. El último `.then` la recibe y la muestra al usuario.
5. **Si alguien se cae (error)**, el `.catch` está al final de la cancha para atrapar la pelota y avisar qué pasó.
