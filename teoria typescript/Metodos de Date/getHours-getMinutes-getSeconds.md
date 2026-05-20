# Horas, Minutos y Segundos — El Cronómetro

### ¿Qué es esto? (La analogía) ⏱️
Así como miramos el calendario para el día, usamos el cronómetro para el momento exacto. Estas funciones te dicen qué hora era exactamente cuando "sacaste la foto" del entrenamiento:
- **`getHours`**: La hora (0 a 23).
- **`getMinutes`**: Los minutos.
- **`getSeconds`**: Los segundos.

### ¿Cuándo lo uso en ObsidiantFit? 🕒
- Para saber a qué hora suele entrenar más el usuario (ej: "Sueles entrenar a las 18hs").
- Para poner una marca de tiempo exacta en un comentario o mensaje.
- Para calcular cuánto tiempo pasó entre el inicio y el fin de una rutina.

### ¿Cómo se usa? (Paso a paso)
```typescript
const ahora = new Date();

const horaExacta = `${ahora.getHours()}:${ahora.getMinutes()}`;
console.log("Hora de inicio:", horaExacta); // "18:30"
```

### El Truco del Experto 💡
Si quieres mostrar la hora con dos dígitos (ej: "09:05" en vez de "9:5"), puedes usar un truco llamado `.padStart(2, '0')`. ¡Queda mucho más profesional!
