# Enums — El Menú de Opciones

### ¿Qué es esto? (La analogía) 📜
Imagina que vas al gimnasio y el recepcionista te pregunta: "¿Qué pase tienes?". Solo hay tres opciones: **Básico**, **Gold** o **Platinum**. No puedes inventar un pase "Súper-Pro". El Enum es esa lista cerrada de opciones permitidas para que nadie se equivoque escribiendo.

### ¿Cuándo lo uso en ObsidiantFit? 🎫
- Para los **Días de la Semana** (Lunes, Martes...).
- Para los **Niveles de Dificultad** (Principiante, Medio, Avanzado).
- Para los **Grupos Musculares** (Pecho, Espalda, Pierna, etc.).

### ¿Cómo se usa? (Paso a paso)
```typescript
enum Nivel {
  Principiante,
  Intermedio,
  Avanzado
}

let miNivel: Nivel = Nivel.Intermedio;

// Es mucho mejor que usar texto común, porque evita errores de dedo como "Intermeidio".
```

### El Truco del Experto 💡
Por defecto, los Enums valen números (0, 1, 2...). Si quieres que valgan texto, puedes escribirlos así: `enum Nivel { Pro = "PROFESIONAL" }`. ¡Es mucho más claro cuando guardas los datos!
