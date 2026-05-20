# Lógicos y Comparación — Los Jueces

### ¿Qué es esto? (La analogía) ⚖️
Son los jueces que comparan dos cosas:
- **`===` (Igualdad estricta)**: ¿Son exactamente iguales? (Mismo valor y mismo tipo).
- **`&&` (Y)**: Solo paso si **ambas** cosas son verdad.
- **`||` (O)**: Paso si **al menos una** es verdad.
- **`!` (No)**: El rebelde que dice lo contrario a la realidad.

### ¿Cuándo lo uso en ObsidiantFit? 🚦
- **`&&`**: Si el usuario está logueado **Y** tiene suscripción, déjalo entrar.
- **`||`**: Si es lunes **O** es jueves, toca entrenar pierna.
- **`===`**: Si el nombre de usuario es exactamente igual al que guardamos.

### ¿Cómo se usa? (Paso a paso)
```typescript
const tieneToalla = true;
const tieneZapatillas = false;

// Solo entra si tiene AMBAS (&&)
const puedeEntrar = tieneToalla && tieneZapatillas; // false

// Entra si tiene AL MENOS UNA (||)
const puedeEntrarAlVestuario = tieneToalla || tieneZapatillas; // true

// Lo contrario (!)
console.log(!tieneToalla); // false (porque era true)
```

### El Truco del Experto 💡
Usa siempre `===` en lugar de `==`. El triple igual es mucho más seguro porque también revisa si el tipo de dato es el mismo.
