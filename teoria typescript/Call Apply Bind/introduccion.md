# Call, Apply y Bind — Invitando a Funciones

### ¿Qué es esto? (La analogía) 💌🏨
Imagina que tienes una rutina de ejercicios que dice: "Yo uso las máquinas de **MI** gimnasio". 
- **`call`**: Es como invitar a esa rutina a que venga a **OTRO** gimnasio y use sus máquinas **ahora mismo**.
- **`apply`**: Es igual que `call`, pero le pasas las pesas en una cajita (un Array).
- **`bind`**: Es como firmar un contrato. "A partir de ahora, esta rutina siempre se hará en **ESTE** gimnasio específico", pero no la haces todavía, la guardas para después.

### ¿Cuándo lo uso en ObsidiantFit? 🔗
- Cuando tienes una función que usa `this.nombre` y quieres "prestársela" a otro objeto que también tenga un nombre.
- Cuando quieres asegurar que una función siempre sepa quién es el usuario, sin importar quién la llame.

### ¿Cómo se usan? (Paso a paso)
```typescript
const entrenador = {
  nombre: "Marcos",
  saludar: function(frase: string) {
    console.log(`${this.nombre} dice: ${frase}`);
  }
};

const alumno = { nombre: "Lucas" };

// 1. CALL: Lucas usa la función de Marcos ahora mismo
entrenador.saludar.call(alumno, "¡Hola!"); // "Lucas dice: ¡Hola!"

// 2. APPLY: Igual, pero con lista de argumentos
entrenador.saludar.apply(alumno, ["¡Qué tal!"]); // "Lucas dice: ¡Qué tal!"

// 3. BIND: Creamos una función nueva pegada a Lucas para siempre
const saludoDeLucas = entrenador.saludar.bind(alumno);
saludoDeLucas("¡Buenas!"); // "Lucas dice: ¡Buenas!"
```

### El Truco del Experto 💡
La diferencia más importante es que `call` y `apply` ejecutan la función **ya**, mientras que `bind` te devuelve una **función nueva** para usarla cuando tú quieras.
