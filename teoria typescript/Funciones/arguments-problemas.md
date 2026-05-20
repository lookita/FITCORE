# El Problema de Arguments — El Cajón Desordenado

### ¿Qué es esto? (La analogía) 📦🧺
Imagina que tienes una caja llamada `arguments`. Cada vez que alguien te tira un objeto, lo metes ahí sin mirar. No sabes si es una pesa, una toalla o una manzana. 
El problema de `arguments` es que es un "Cajón Desordenado":
1. No te dice qué hay adentro (no tiene tipos).
2. Parece una lista (Array), pero no lo es del todo (no puedes usar `.map()` o `.filter()` directamente).
3. Es muy fácil cometer errores y que la app explote.

### ¿Cuándo lo uso en ObsidiantFit? 🛑
**¡NUNCA!** En el pasado se usaba mucho, pero hoy en día es como usar un teléfono de disco. En TypeScript preferimos usar los **Rest Parameters** (`...muchasCosas`).

### ¿Por qué es malo? (Ejemplo)
```typescript
function sumarTodoViejito() {
  // 'arguments' existe por arte de magia, pero no sabemos qué tipos tiene (es 'any')
  let total = 0;
  for (let i = 0; i < arguments.length; i++) {
    total += arguments[i]; // ❌ ¡Peligro! ¿Y si arguments[i] es un texto?
  }
  return total;
}
```

### La Solución Moderna: Rest Parameters ✅
Como vimos en su archivo, los tres puntitos `...` sí te dejan decir qué tipo de cosas vas a recibir.
```typescript
function sumarTodoPro(...numeros: number[]) {
  return numeros.reduce((acc, n) => acc + n, 0); // ✅ Seguro y ordenado.
}
```

### El Truco del Experto 💡
Si ves `arguments` en un tutorial viejo, cámbialo por `...args`. Tu código será mucho más seguro ("Type Safe") y TypeScript dejará de estar preocupado por tu cajón desordenado.
