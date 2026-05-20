# Introducción a Date — El Cronómetro de tus Logros

### ¿Qué es esto? (La analogía) 📅⏱️
Imagina un **reloj digital inteligente** que no solo te dice la hora, sino que también anota el día, el mes, el año y hasta el segundo exacto en que terminaste tu última serie. En TypeScript, el objeto `Date` es ese cronómetro que captura un momento preciso en el tiempo.

### ¿Cuándo lo uso en ObsidiantFit? 📝
- Para guardar **cuándo** se realizó un entrenamiento.
- Para calcular si el usuario ya cumplió su **racha de 24 horas**.
- Para mostrar un calendario con el progreso mensual.

### ¿Cómo se captura el tiempo?

#### 1. El momento de ahora
```typescript
const ahora = new Date(); 
console.log(ahora); // Muestra la fecha y hora de este preciso instante
```

#### 2. Una fecha específica
```typescript
const cumpleanios = new Date("1998-05-15");
```

### El Truco del Experto 💡
Trabajar con fechas es difícil porque los meses en JavaScript **empiezan en 0**. Enero es `0`, Febrero es `1`... ¡y Diciembre es `11`! Ten mucho cuidado con esto al programar tus calendarios.
