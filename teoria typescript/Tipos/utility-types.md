# Utility Types — Las Herramientas Mágicas

### ¿Qué es esto? (La analogía) 🛠️✨
Imagina que tienes un molde para hacer tortas completo. Pero a veces solo quieres hacer la **mitad** de la torta, o quieres que la torta sea **intocable**. Los Utility Types son como "hechizos" que aplicas a un tipo que ya tienes para transformarlo rápidamente sin tener que escribir uno nuevo.

### ¿Cuándo lo uso en ObsidiantFit? 🧙‍♂️
- **`Partial<T>`**: Tienes un `Usuario` con 10 datos, pero el usuario solo quiere actualizar **uno** (ej: su peso). El `Partial` hace que todos los campos sean opcionales de golpe.
- **`Readonly<T>`**: Quieres que los datos de un entrenamiento **no se puedan cambiar** una vez guardados.
- **`Omit<T, 'K'>`**: Tienes un `Ejercicio` completo pero quieres crear una versión que **no tenga** el campo "ID".

### ¿Cómo se usa? (Paso a paso)
```typescript
interface Usuario {
  id: number;
  nombre: string;
  peso: number;
}

// Partial: Todos son opcionales ahora
const actualizacion: Partial<Usuario> = { peso: 85 }; 

// Omit: Usuario sin el ID
const nuevoUsuario: Omit<Usuario, 'id'> = { nombre: "Ana", peso: 60 };

// Readonly: No se puede tocar
const usuarioFijo: Readonly<Usuario> = { id: 1, nombre: "Lucas", peso: 80 };
// usuarioFijo.peso = 82; // ❌ Error! Es de solo lectura.
```

### El Truco del Experto 💡
`Pick<T, 'K'>` es lo contrario a `Omit`. En vez de decir qué quitar, dices **qué quieres quedarte**. ¡Úsalos para no repetir código!
