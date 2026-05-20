# Decoradores — El Sticker Especial

### ¿Qué es esto? (La analogía) 🏷️✨
Imagina que tienes una botella de agua común. Si le pegas un sticker que dice "**¡Agua Bendita!**", la botella sigue siendo de agua, pero ahora tiene un "poder" o una etiqueta especial que todos notan. Un Decorador es un sticker `@` que le pegas a una clase o función para darle una habilidad extra sin cambiar el código de adentro.

### ¿Cuándo lo uso en ObsidiantFit? 🎀
- Cuando usas **NestJS** (el motor de tu servidor). Allí los decoradores están por todos lados: `@Get()` para recibir datos, `@Post()` para enviar, `@Injectable()` para que la clase sea una herramienta útil.
- Para marcar qué funciones de tu app necesitan que el usuario esté logueado (`@Auth`).
- Para guardar automáticamente en un archivo cada vez que alguien usa una función específica.

### ¿Cómo se usa? (Paso a paso)
```typescript
// El decorador es como una función que "envuelve" a otra
function Loguear(target: any, key: string) {
  console.log(`¡Alguien usó la función: ${key}!`);
}

class Gimnasio {
  @Loguear // Pegamos el sticker
  abrirPuerta() {
    console.log("Puerta abierta.");
  }
}
```

### El Truco del Experto 💡
Los decoradores son avanzados. No te preocupes por crearlos tú mismo al principio; lo más importante es que sepas que cuando veas un `@`, estás activando una función mágica que alguien más escribió para ayudarte.
