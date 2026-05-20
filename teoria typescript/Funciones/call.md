# Call, Apply y Bind — Invitando a Funciones

### ¿Qué es esto? (La analogía) 💌🏨
Imagina que tienes una rutina de ejercicios que dice: "Yo uso las máquinas de **MI** gimnasio". 
- **`call`**: Es como invitar a esa rutina a que venga a **OTRO** gimnasio y use sus máquinas **ahora mismo**.
- **`apply`**: Es igual que `call`, pero le pasas las pesas en una cajita (un Array).
- **`bind`**: Es como firmar un contrato. "A partir de ahora, esta rutina siempre se hará en **ESTE** gimnasio específico", pero no la haces todavía, la guardas para después.

---

## 10 Ejemplos de Call, Apply y Bind (De Bebé a Harvard) 💌💪

### 1. Nivel 5 años: Prestar el Juguete
Usamos el juguete de otro niño por un momento.
```typescript
const niño1 = { juguete: "Auto" };
function jugar() { console.log("Jugando con " + this.juguete); }

jugar.call(niño1); // "Jugando con Auto"
```

### 2. Pedir prestado el sombrero
Nos ponemos el sombrero de un amigo para una foto.
```typescript
const amigo = { sombrero: "Gorra Roja" };
function tomarFoto() { console.log("Tengo puesta una " + this.sombrero); }

tomarFoto.call(amigo);
```

### 3. La Cuchara ajena
Comer sopa con la cuchara de otra persona.
```typescript
const otro = { cubierto: "Cuchara de oro" };
function comer() { console.log("Comiendo con " + this.cubierto); }

comer.apply(otro);
```

### 4. Saludar como un Atleta
Usar la voz de un atleta para decir hola.
```typescript
const atleta = { nombre: "Lucas" };
function saludar() { console.log("Hola, soy " + this.nombre); }

saludar.call(atleta);
```

### 5. Actualizar Peso (Contexto ObsidiantFit)
Prestar la función de pesar a distintos alumnos.
```typescript
function pesar(kilos: number) { this.peso = kilos; }

const alumno = { nombre: "Santi", peso: 70 };
pesar.call(alumno, 72); 
```

### 6. Convertir Argumentos a Array
Un truco clásico para usar métodos de array en cosas que no son arrays.
```typescript
function listarEjercicios() {
  const lista = Array.prototype.slice.call(arguments);
  console.log("Tienes: " + lista.length + " ejercicios");
}
```

### 7. Fijar un Usuario (Bind)
Hacer que una función siempre pertenezca a un usuario, no importa quién la llame.
```typescript
const usuario = { id: 123 };
function guardar() { console.log("Guardando para ID: " + this.id); }

const guardarDeLucas = guardar.bind(usuario);
guardarDeLucas(); 
```

### 8. Currying (Pasar argumentos poco a poco)
Usar `bind` para dejar una función medio preparada.
```typescript
function entrenar(tipo: string, intensidad: string) {
  console.log(`Haciendo ${tipo} al ${intensidad}`);
}

const modoBestia = entrenar.bind(null, "Fuerza"); 
modoBestia("100%"); // "Haciendo Fuerza al 100%"
```

### 9. Decorador de Métodos
Cambiar el comportamiento de una función "prestándola" para envolverla en otra.
```typescript
const logger = function(original) {
  return function() {
    console.log("Iniciando...");
    return original.apply(this, arguments);
  };
};
```

### 10. Nivel Harvard: Inyección de Contexto Dinámico
Implementar un sistema de plugins donde cada plugin se ejecuta con el contexto del núcleo del sistema (Core).
```typescript
class ObsidiantCore {
  data = "Secreto";
  ejecutarPlugin(pluginFn) {
    // El plugin se siente como si fuera parte del Core
    pluginFn.call(this); 
  }
}
```
