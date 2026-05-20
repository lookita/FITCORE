# Call Signatures — El Plano de la Función

### ¿Qué es esto? (La analogía) 📐
Imagina que vas a comprar una máquina para el gimnasio y te dan un manual que dice: "Esta máquina tiene un lugar para **sentarse** y una **palanca** para empujar". El Call Signature es ese manual: describe exactamente cómo es la forma de la función (qué recibe y qué devuelve) sin decirte cómo funciona por dentro.

### ¿Cuándo lo uso en ObsidiantFit? 🛠️
- Cuando quieres definir el tipo de una función que vas a usar muchas veces.
- Para crear objetos que no solo tienen datos, sino que **ellos mismos se pueden llamar como una función**.
- Para describir funciones complejas que tienen propiedades extra (como un contador de usos).

### ¿Cómo se usa? (Paso a paso)
```typescript
// Definimos la "firma" de la función
type CalculadoraKilos = (reps: number, peso: number) => number;

// Ahora usamos esa firma para crear la función real
const calcularVolumen: CalculadoraKilos = (r, p) => r * p;

// También se puede escribir con llaves si la función tiene propiedades:
interface FuncionConPropiedad {
  (mensaje: string): void; // La firma
  contador: number;        // Una propiedad extra
}
```

### El Truco del Experto 💡
Diferenciar la **firma** (el tipo) de la **implementación** (el código real) te permite reutilizar tipos y hace que tu código sea mucho más limpio y profesional.
