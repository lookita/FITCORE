# Decoradores Avanzados — Metaprogramación

### ¿Qué es esto? (La analogía) 🏷️✨
Ya vimos que los decoradores son como stickers mágicos. En modo "Avanzado", son funciones que se ejecutan **antes** de que tu programa empiece a correr de verdad. Pueden cambiar cómo funciona una clase, espiar qué valores entran a una función o incluso prohibir que alguien use una propiedad.

### ¿Cuándo lo uso en ObsidiantFit? 🎀
- En **NestJS** para decir: "Esta función solo la pueden usar Admins" (`@Roles('admin')`).
- Para validar automáticamente que un peso nunca sea negativo (`@Min(0)`).
- Para registrar en una base de datos cada vez que alguien inicia un entrenamiento.

### Ejemplo de Decorador de Método:
```typescript
function Log(target: any, key: string, descriptor: PropertyDescriptor) {
  const original = descriptor.value;
  descriptor.value = function(...args: any[]) {
    console.log(`--- Ejecutando: ${key} ---`);
    return original.apply(this, args);
  };
}

class Gym {
  @Log
  abrir() { console.log("Gimnasio abierto."); }
}
```

### El Truco del Experto 💡
Los decoradores son geniales para quitar "ruido" de tu código. En vez de escribir `console.log` en todas tus funciones, ¡le pegas un sticker `@Log` y listo! Recuerda que para usarlos en TypeScript debes activar `experimentalDecorators` en tu `tsconfig.json`.
