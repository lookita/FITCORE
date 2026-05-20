# .filter() — El Guardián de la Puerta

### ¿Qué es esto? (La analogía) 👮‍♂️
Imagina que estás en la puerta de un gimnasio y solo dejas pasar a los que traen toalla. El `.filter()` hace exactamente eso: mira cada elemento de tu lista y pregunta: "¿Tú cumples esta regla?". Si dice que sí, pasa a la nueva lista. Si dice que no, se queda fuera.

### ¿Cuándo lo uso en ObsidiantFit? 🎯
Cuando quieres quedarte solo con una parte de la información.
- Mostrar solo los ejercicios que son de **"Pierna"**.
- Ver qué usuarios tienen más de **30 días de racha**.
- Filtrar las rutinas que duran menos de **45 minutos**.

### ¿Cómo se usa? (Paso a paso)
```typescript
const entrenamientos = [
  { nombre: "Full Body", minutos: 60 },
  { nombre: "Cardio Express", minutos: 20 },
  { nombre: "Pierna Pesado", minutos: 90 }
];

// Queremos solo los entrenamientos rápidos (menos de 45 min).
const rutinasRapidas = entrenamientos.filter((rutina) => {
  return rutina.minutos < 45; // Solo los que cumplan esto pasan la puerta.
});

console.log(rutinasRapidas); 
// [{ nombre: "Cardio Express", minutos: 20 }]
```

### El Truco del Experto 💡
Si nadie cumple la regla, `.filter()` no explota, simplemente te da una lista vacía `[]`. ¡Es muy seguro de usar!
