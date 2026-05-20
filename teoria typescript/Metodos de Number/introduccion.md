# Introducción a Numbers — Pesos y Medidas

### ¿Qué es esto? (La analogía) ⚖️🔢
En el gimnasio todo se mide: cuántos kilos levantas, cuántas repeticiones haces, cuánto tiempo descansas. Un `number` en TypeScript es cualquier valor numérico. No importa si es un número entero (`10`) o con decimales (`85.5`), ¡para TypeScript todos son hermanos!

### ¿Cuándo lo uso en ObsidiantFit? 📊
- Kilos en la barra: `100` o `102.5`.
- Cantidad de series: `4`.
- Porcentaje de grasa: `15.5`.

### Reglas importantes:

#### 1. Todo es 'number'
A diferencia de otros lenguajes, aquí no tienes que preocuparte si es `int` o `float`.
```typescript
let discos: number = 4;
let pesoTotal: number = 82.5;
```

#### 2. Cuidado con los textos
Si recibes un número de un formulario (como un `input`), a veces viene como texto `"100"`. Tienes que convertirlo antes de sumar, ¡o harás un desastre!
```typescript
let pesoTexto = "100";
let pesoReal = Number(pesoTexto); // Ahora sí es un número
```

### El Truco del Experto 💡
Si quieres que un número sea más fácil de leer, puedes usar guiones bajos: `let caloriasTotales = 1_000_000;`. TypeScript los ignora, pero para tus ojos es mucho mejor.
