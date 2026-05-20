# Clases Abstractas — El Fantasma del Plano

### ¿Qué es esto? (La analogía) 👻📐
Imagina que diseñas un plano de "Vehículo". El plano dice que tiene ruedas y motor. Pero... **nadie puede comprar un "Vehículo"**. Tú compras un Auto o una Moto. El "Vehículo" es solo una idea abstracta para que los autos y motos sepan qué deben tener. Una Clase Abstracta es una clase que **no se puede crear directamente** (`new`), solo sirve para que otras hereden de ella.

### ¿Cuándo lo uso en ObsidiantFit? ☁️
- Tienes una clase `BaseDatos`. No quieres que nadie cree una "Base de Datos" vacía, sino que creen una `BaseDatosUsuarios` o una `BaseDatosEjercicios`.
- Tienes una clase `Notificacion`. Nadie manda una "Notificación" a secas, mandas un `Mail` o un `WhatsApp`.

### ¿Cómo se usa? (Paso a paso)
```typescript
abstract class Notificacion {
  abstract enviar(mensaje: string): void; // Obliga a los hijos a tener esta función
}

class Mail extends Notificacion {
  enviar(mensaje: string) {
    console.log("Enviando mail: " + mensaje);
  }
}

// const alerta = new Notificacion(); // ❌ Error! No puedes crear un "fantasma".
const miMail = new Mail(); // ✅ ¡Esto sí!
miMail.enviar("¡Entrenamiento completado!");
```

### El Truco del Experto 💡
Las clases abstractas son geniales para definir "reglas" que todos los hijos deben cumplir. Si un hijo no escribe la función `enviar`, ¡TypeScript no lo deja funcionar!
