# Genéricos Avanzados — Constraints y Defaults

### ¿Qué es esto? (La analogía) ⚖️📦
Ya sabemos que un Genérico `<T>` es una caja que acepta cualquier cosa. Pero a veces no quieres **cualquier** cosa.
- **Constraints (`extends`)**: Es como decir: "Acepto cualquier cosa, siempre y cuando sea **metálica**". Pones una regla mínima que el tipo debe cumplir.
- **Defaults (`=`)**: Es como decir: "Si no me dices qué tipo de caja quieres, por defecto usaré una de **cartón**".

### ¿Cuándo lo uso en ObsidiantFit? 🧱
- Tienes una función que imprime el nombre de algo. Necesitas que el tipo tenga al menos la propiedad `nombre`. Usas `extends { nombre: string }`.
- Tienes una función de respuesta de API que por defecto devuelve un `Usuario`, pero puede devolver otras cosas si se lo pides.

### ¿Cómo se usa? (Paso a paso)
```typescript
// CONSTRAINT: T tiene que tener la propiedad 'length'
function contarCosas<T extends { length: number }>(algo: T) {
  return algo.length;
}

// DEFAULT: Si no dices nada, T es un 'string'
function transformar<T = string>(valor: T) {
  return valor;
}
```

### El Truco del Experto 💡
Usar `extends` es la mejor forma de hacer que tus genéricos sean poderosos pero seguros. ¡Es como ponerle barandillas a la magia para que no se descontrole!
