# Utility Types — Las Herramientas Mágicas del Taller

---

## 🧠 ¿Qué son los Utility Types?

Imaginate que tenés un muñeco de LEGO completamente armado: tiene cabeza, brazos, piernas, torso y ropa.

Los **Utility Types** son herramientas especiales que te permiten **transformar ese muñeco** sin tener que diseñarlo desde cero cada vez.

Con ellos podés:

- Hacer que todas sus partes sean opcionales (que puedas armarlo sin cabeza si querés).
- Hacer que solo puedas ver algunas partes.
- Hacer que no puedas cambiarle nada.
- Elegir solo las partes que necesitás.

TypeScript ya trae estas herramientas incluidas. No tenés que importar nada.

---

## 🧸 El Muñeco Base (nuestro tipo de ejemplo)

Primero vamos a definir nuestro "muñeco" base que vamos a usar en todos los ejemplos:

```typescript
type Usuario = {
  id: number; // El número único que identifica al usuario
  nombre: string; // Su nombre
  email: string; // Su correo electrónico
  password: string; // Su contraseña (¡secreto!)
  peso: number; // Su peso en kg
  nivel: string; // "principiante", "intermedio", "avanzado"
  createdAt: Date; // Fecha en que se registró
};
```

Ahora, vamos a ver cómo las herramientas mágicas lo transforman.

---

## 1️⃣ `Partial<T>` — Todo se vuelve Opcional

### ¿Qué hace?

Toma tu tipo y convierte **TODAS** las propiedades en opcionales. Es como decirle: "No tenés que poner todo, podés poner solo lo que quieras."

### ¿Cuándo lo usamos?

Cuando el usuario quiere **actualizar** solo una parte de su perfil. No tiene sentido pedir todos los datos si solo quiere cambiar el email.

```typescript
// El tipo original: todo es OBLIGATORIO
type Usuario = {
  nombre: string; // obligatorio
  email: string; // obligatorio
  peso: number; // obligatorio
};

// Con Partial: todo pasa a ser OPCIONAL (nota el ? en cada propiedad)
type ActualizarUsuario = Partial<Usuario>;
// Es EXACTAMENTE lo mismo que escribir:
// type ActualizarUsuario = {
//   nombre?: string;
//   email?: string;
//   peso?: number;
// }
```

### Ejemplo real en ObsidiantFit:

```typescript
// Esta función actualiza solo lo que el usuario manda
function actualizarPerfil(id: number, cambios: Partial<Usuario>) {
  // El usuario puede mandar solo el email, solo el peso, o lo que quiera
  console.log("Actualizando:", cambios);
}

// ✅ Solo manda el email — perfecto
actualizarPerfil(1, { email: "nuevo@gmail.com" });

// ✅ Solo manda el peso — también válido
actualizarPerfil(1, { peso: 78 });

// ✅ Manda email y peso — también válido
actualizarPerfil(1, { email: "nuevo@gmail.com", peso: 78 });

// ❌ Si manda un campo que no existe en Usuario, TypeScript avisa
actualizarPerfil(1, { apellido: "García" }); // Error!
```

---

## 2️⃣ `Required<T>` — Todo se vuelve Obligatorio

### ¿Qué hace?

Es lo opuesto de `Partial`. Toma un tipo donde algunas cosas son opcionales y las hace **TODAS obligatorias**.

### ¿Cuándo lo usamos?

Cuando necesitás asegurarte de que el usuario completó absolutamente todo antes de guardar en la base de datos.

```typescript
// Un tipo con cosas opcionales (al llenar el formulario)
type FormularioRutina = {
  nombre?: string; // opcional mientras llena el form
  descripcion?: string; // opcional mientras llena el form
  ejercicios?: string[]; // opcional mientras llena el form
  nivel?: string; // opcional mientras llena el form
};

// Al PUBLICAR la rutina, todo tiene que estar completo
type RutinaPublicada = Required<FormularioRutina>;
// TypeScript se asegura de que nada falte

function publicarRutina(rutina: RutinaPublicada) {
  console.log("Publicando rutina completa:", rutina.nombre);
}

// ❌ Falta descripcion, ejercicios y nivel — TypeScript te avisa
publicarRutina({ nombre: "Mi rutina" }); // Error!

// ✅ Todo completo — funciona
publicarRutina({
  nombre: "PPL Calistenia",
  descripcion: "Rutina de 3 días",
  ejercicios: ["Dominadas", "Flexiones"],
  nivel: "intermedio",
});
```

---

## 3️⃣ `Pick<T, K>` — Elegir Solo Algunas Partes

### ¿Qué hace?

Te permite **seleccionar únicamente las propiedades que necesitás** de un tipo más grande. Es como decirle a TypeScript: "Del muñeco completo, dame solo la cabeza y los brazos."

### ¿Cuándo lo usamos?

Cuando mostrás una "card" o "preview" de algo y no necesitás todos los datos, solo algunos.

```typescript
type Usuario = {
  id: number;
  nombre: string;
  email: string;
  password: string;
  peso: number;
  nivel: string;
  createdAt: Date;
};

// Para mostrar en el feed, solo necesito id, nombre y nivel
type UsuarioPreview = Pick<Usuario, "id" | "nombre" | "nivel">;
// Es lo mismo que escribir:
// type UsuarioPreview = {
//   id: number;
//   nombre: string;
//   nivel: string;
// }
```

### Ejemplo real en ObsidiantFit:

```typescript
// La card del usuario en el leaderboard solo muestra nombre y nivel
type CardUsuario = Pick<Usuario, "nombre" | "nivel">;

function renderizarCard(usuario: CardUsuario) {
  console.log(`${usuario.nombre} — Nivel: ${usuario.nivel}`);
  // No tiene acceso a password, email, ni otros datos sensibles
}
```

---

## 4️⃣ `Omit<T, K>` — Quitar Algunas Partes

### ¿Qué hace?

Es lo opuesto de `Pick`. En lugar de elegir qué incluir, **elegís qué excluir**. El resultado tiene todo el tipo original MENOS las propiedades que quitaste.

### ¿Cuándo lo usamos?

El caso más importante: **nunca enviar la contraseña al frontend**. Con `Omit` la sacamos de la respuesta de la API.

```typescript
// Nunca queremos enviar la password al frontend
type UsuarioPublico = Omit<Usuario, "password">;
// Es lo mismo que:
// type UsuarioPublico = {
//   id: number;
//   nombre: string;
//   email: string;
//   peso: number;
//   nivel: string;
//   createdAt: Date;
//   // NO tiene password!
// }

// Podés quitar múltiples propiedades
type CreateUserDto = Omit<Usuario, "id" | "createdAt">;
// Cuando creás un usuario, el id y la fecha los genera la base de datos sola
// El usuario no los manda, así que no los necesitamos en el formulario
```

### Ejemplo real en ObsidiantFit:

```typescript
// Al crear un usuario nuevo, NO mandamos id ni createdAt (los genera la BD)
type CreateUserDto = Omit<Usuario, "id" | "createdAt">;

// Al actualizar, NO se puede cambiar el id
type UpdateUserDto = Partial<Omit<Usuario, "id">>;
// (Partial + Omit combinados: todo opcional, pero sin el id)

// La respuesta de la API nunca incluye la contraseña
type UserResponse = Omit<Usuario, "password">;
```

---

## 5️⃣ `Readonly<T>` — El Muñeco de Piedra

### ¿Qué hace?

Convierte todas las propiedades en **intocables**. Una vez que creás el objeto, nadie puede modificar sus valores.

### ¿Cuándo lo usamos?

Para datos de configuración que no deberían cambiar durante la ejecución de la app.

```typescript
type Configuracion = Readonly<{
  apiUrl: string;
  version: string;
  maxUsuarios: number;
}>;

const CONFIG: Configuracion = {
  apiUrl: "https://api.obsidianfit.com",
  version: "1.0.0",
  maxUsuarios: 10000,
};

// ❌ Nadie puede cambiar la configuración
CONFIG.version = "2.0.0"; // Error: Cannot assign to 'version' because it is a read-only property.

// ✅ Solo se puede leer
console.log(CONFIG.apiUrl);
```

---

## 🔗 Combinando Utility Types (El Poder Real)

La magia ocurre cuando los combinás. Estos son los DTOs reales de ObsidiantFit:

```typescript
type Usuario = {
  id: number;
  nombre: string;
  email: string;
  password: string;
  peso: number;
  nivel: string;
  createdAt: Date;
};

// DTO = Data Transfer Object (el "paquete de datos" que viaja entre capas)

// 1. CREAR usuario: sin id (lo genera la BD) y sin fecha (también la BD)
type CreateUserDto = Omit<Usuario, "id" | "createdAt">;

// 2. ACTUALIZAR usuario: todo opcional, pero no se puede cambiar el id
type UpdateUserDto = Partial<Omit<Usuario, "id">>;

// 3. RESPUESTA de la API: todo el usuario pero SIN la contraseña
type UserResponse = Omit<Usuario, "password">;

// 4. PREVIEW en el feed: solo nombre y nivel
type UserPreview = Pick<Usuario, "nombre" | "nivel">;
```

---

## 🎓 Tabla Resumen

| Utility Type  | ¿Qué hace?                | Analogía                          |
| ------------- | ------------------------- | --------------------------------- |
| `Partial<T>`  | Hace todo opcional        | El muñeco con piezas sueltas      |
| `Required<T>` | Hace todo obligatorio     | El muñeco debe estar completo     |
| `Pick<T, K>`  | Elige solo algunas partes | Tomar solo la cabeza y los brazos |
| `Omit<T, K>`  | Quita algunas partes      | Sacar las piernas del muñeco      |
| `Readonly<T>` | Nada se puede cambiar     | El muñeco convertido en estatua   |

---

## 💡 El Truco del Experto

Cuando veas en NestJS o en el backend de ObsidiantFit palabras como:

- `CreateUserDto`
- `UpdateUserDto`
- `UserResponse`

Casi siempre están construidas con `Omit`, `Partial` o `Pick` combinados. ¡Ya sabés exactamente qué hacen!
