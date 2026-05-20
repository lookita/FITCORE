# .toFixed() — Controlando los Decimales

### ¿Qué es esto? (La analogía) ⚖️
Imagina que vas a comprar suplementos y el precio es `$19.98754`. ¡Nadie paga eso! El `.toFixed()` es como un cajero automático que corta los decimales que sobran y redondea el precio para que quede bonito, por ejemplo, `$19.99`.

### ¿Cuándo lo uso en ObsidiantFit? 💰
- Cuando calculas el **promedio de peso levantado** y te da un número con 10 decimales.
- Cuando muestras el **porcentaje de progreso** (ej: "85.5%").
- Para mostrar precios o medidas exactas sin que se vea feo en la pantalla.

### ¿Cómo se usa? (Paso a paso)
```typescript
const pesoPromedio = 82.555555;

// Queremos solo 2 decimales
const pesoBonito = pesoPromedio.toFixed(2);

console.log(pesoBonito); // "82.56" (¡Ojo! Ahora es un texto, no un número)
```

### El Truco del Experto 💡
⚠️ ¡Atención! `.toFixed()` devuelve un **String** (texto). Si después de redondear quieres seguir sumando o restando ese número, tendrás que convertirlo de nuevo usando `Number(valor)`.
