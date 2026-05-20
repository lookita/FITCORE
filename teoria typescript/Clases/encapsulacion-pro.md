# Encapsulación Pro — Las Paredes de Cristal

### ¿Qué es esto? (La analogía) 🏢🧤
Imagina un gimnasio con una gran pared de cristal. Puedes ver cómo funcionan las máquinas desde afuera, pero no puedes entrar a tocarlas ni a cambiarles el aceite si no eres el técnico. La **Encapsulación** es ocultar los detalles internos y complejos de tu código y mostrar solo unos pocos botones sencillos ("La API Pública").

### ¿Cuándo lo uso en ObsidiantFit? 🔐
- Para que nadie pueda cambiar el **ID de un ejercicio** por accidente.
- Para ocultar la lógica difícil de cómo se calculan las calorías y solo mostrar el resultado final.
- Para proteger los datos privados de los usuarios.

### ¿Cómo se usa? (Paso a paso)
```typescript
class Entrenamiento {
  private _volumenTotal: number = 0; // Secreto interno 🤫

  // Solo dejamos que lo vean, no que lo toquen directamente
  get volumen() {
    return this._volumenTotal;
  }

  // Solo se puede cambiar a través de este "botón" seguro
  registrarSerie(peso: number, reps: number) {
    const nuevo = peso * reps;
    if (nuevo > 0) this._volumenTotal += nuevo; // Validamos antes de cambiar
  }
}
```

### El Truco del Experto 💡
La regla de oro: **Haz todo PRIVADO por defecto**. Solo haz público lo que sea absolutamente necesario. Así, si decides cambiar cómo funciona algo por dentro, ¡nada de afuera se romperá!
