# El Signo de Pregunta `?` — Las Cosas Opcionales

---

## 🧠 ¿Qué significa el `?` en TypeScript?

En la vida real, hay cosas que son **obligatorias** y otras que son **opcionales**.
- Para entrar a un gimnasio, es **obligatorio** tener nombre.
- Pero es **opcional** tener un apodo o una foto de perfil.

En TypeScript, el signo `?` le dice al programa:
> "Esta propiedad o parámetro **puede estar o no estar**. Si no está, no pasa nada, no te quejes."

---

## 📍 ¿Dónde se usa el `?`?

Aparece principalmente en **3 lugares** cuando hablamos de tipos:

---

### 1. En Propiedades de un Objeto (o Interface)

Es el uso más común. Convierte una propiedad en algo que no es obligatorio completar.

```typescript
type Usuario = {
  nombre: string;    // Obligatorio
  edad: number;      // Obligatorio
  apodo?: string;    // OPCIONAL (puede ser string o no estar)
};

// ✅ Válido: Pongo todo
const usuario1: Usuario = {
  nombre: "Lucas",
  edad: 25,
  apodo: "El Rayo"
};

// ✅ Válido: No pongo el apodo y TypeScript no se enoja
const usuario2: Usuario = {
  nombre: "Mateo",
  edad: 20
};

// ❌ Error: Falta la edad, que es obligatoria
const usuario3: Usuario = {
  nombre: "Santi"
};
```

---

### 2. En Parámetros de Funciones

Podés hacer que una función acepte datos que no siempre son necesarios.

```typescript
function saludar(nombre: string, mensaje?: string) {
  if (mensaje) {
    console.log(`${mensaje}, ${nombre}!`);
  } else {
    console.log(`Hola, ${nombre}!`);
  }
}

saludar("Lucas", "Buenos días"); // ✅ Usa el mensaje
saludar("Lucas");                // ✅ No usa mensaje, funciona igual
```

**Regla de oro**: Los parámetros opcionales **siempre deben ir al final**. No podés tener un opcional y después uno obligatorio.

---

### 3. En los Utility Types (como `Partial<T>`)

Como viste en el archivo de Utility Types, `Partial<T>` toma todas las propiedades y les pone un `?` automáticamente.

```typescript
type Usuario = { nombre: string; edad: number };

// Partial<Usuario> es lo mismo que:
type UsuarioOpcional = {
  nombre?: string;
  edad?: number;
};
```

---

## 🔍 ¿Qué pasa por dentro? (undefined)

Cuando ponés `apodo?: string`, TypeScript en realidad está haciendo una "Unión" secreta.
Está diciendo que el apodo puede ser un `string` O puede ser `undefined` (que significa "no definido").

```typescript
// Esto:
apodo?: string

// Es casi lo mismo que esto:
apodo: string | undefined
```

La diferencia es que con el `?` **podés no escribir la propiedad**, mientras que con `| undefined` **tenés que escribirla** aunque le pongas el valor `undefined`.

---

## 🏋️ Ejemplo Real en ObsidiantFit

```typescript
interface Rutina {
  id: number;
  nombre: string;
  descripcion?: string; // Algunas rutinas no tienen descripción
  ejercicios: string[];
}

const miRutina: Rutina = {
  id: 1,
  nombre: "Full Body",
  // No pongo descripción, todo bien ✅
  ejercicios: ["Sentadillas", "Flexiones"]
};
```

---

## ⚠️ No confundir con otros `?`

Hay otros usos del signo de pregunta que NO son para tipos:

1.  **Operador Ternario** (`a > b ? 'si' : 'no'`): Es para tomar decisiones de lógica.
2.  **Optional Chaining** (`usuario?.perfil`): Es para leer datos de forma segura sin que el programa explote.

*Esos los encontrás en la carpeta de **Operadores**.*

---

## 🔑 Resumen para un niño de 10 años

El `?` es como una **invitación opcional**. 
- Si no tiene `?`, es una **orden**: tenés que traerlo sí o sí.
- Si tiene `?`, es un **"si querés"**: si lo traés, genial; si no lo traés, no pasa nada.
