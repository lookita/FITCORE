# Slice y Splice — Cortar y Modificar

### ¿Qué es esto? (La analogía) ✂️
- **`.slice()` (Cortar un trozo)**: Imagina que tienes una barra de cereal de proteínas y cortas un pedazo para dárselo a un amigo. Tú te quedas con tu barra original intacta, y tu amigo tiene su trozo nuevo.
- **`.splice()` (Cirugía)**: Imagina que estás operando la lista. Puedes sacar algo de en medio, poner algo nuevo en su lugar, o simplemente borrar una parte. **Esto cambia la lista original para siempre.**

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- **Slice**: Quieres mostrar solo los **3 primeros ejercicios** de una rutina de 10, pero sin borrar los otros 7 de la base de datos.
- **Splice**: El usuario quiere **eliminar el ejercicio número 2** de su lista porque no hay esa máquina en el gym, o quiere **reemplazar** "Cinta" por "Bici".

### ¿Cómo se usa? (Paso a paso)
```typescript
// --- SLICE (Copia un trozo) ---
const todosLosDias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
const finDeSemana = todosLosDias.slice(3); // Agarra desde la posición 3 hasta el final.
// todosLosDias sigue igual. finDeSemana es ["Jueves", "Viernes"].

// --- SPLICE (Modifica la original) ---
const ejercicios = ["Pecho", "Espalda", "Pierna"];
// Queremos sacar "Espalda" (posición 1) y poner "Hombro".
ejercicios.splice(1, 1, "Hombro"); 
// El primer 1 es la posición. El segundo 1 es cuántos borrar.
console.log(ejercicios); // ["Pecho", "Hombro", "Pierna"]
```

### El Truco del Experto 💡
Si solo quieres **borrar** algo con `.splice()`, no pongas el tercer parámetro. Ejemplo: `ejercicios.splice(1, 1)` borra el elemento en la posición 1 y listo.
