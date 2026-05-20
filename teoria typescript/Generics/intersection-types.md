# Intersection Types (A & B) — Esto Y Aquello

---

## 🧠 ¿Qué es un Intersection Type?

Imaginate un superhéroe que tiene los poderes de **dos héroes al mismo tiempo**.
- Tiene los poderes de Superman (volar, superfuerza).
- Y también los poderes de Batman (detective, gadgets, karate).

Un **Intersection Type** combina dos o más tipos en uno solo. El tipo resultante tiene **TODAS** las propiedades de todos los tipos combinados.

El símbolo es el ampersand: `&` (se lee "y")

---

## 📖 La Sintaxis Básica

```typescript
type SuperPoderes = {
  puedeVolar: boolean;
  fuerza: number;
};

type HabilidadesDetective = {
  coeficienteInteligencia: number;
  conoceArtesMarciales: boolean;
};

// SuperHeroe tiene TODO de ambos tipos
type SuperHeroe = SuperPoderes & HabilidadesDetective;

// ✅ Tengo que poner TODAS las propiedades de ambos
const heroe: SuperHeroe = {
  puedeVolar: true,              // viene de SuperPoderes
  fuerza: 9000,                  // viene de SuperPoderes
  coeficienteInteligencia: 200,  // viene de HabilidadesDetective
  conoceArtesMarciales: true     // viene de HabilidadesDetective
};

// ❌ Si falta cualquier propiedad, TypeScript da error
const heroeMalo: SuperHeroe = {
  puedeVolar: true,
  fuerza: 9000
  // Faltan coeficienteInteligencia y conoceArtesMarciales!
};
```

---

## ⚔️ Diferencia Entre `|` y `&`

Esta es la comparación más importante. Mucha gente las confunde:

| | Union `A \| B` | Intersection `A & B` |
|---|---|---|
| **Significado** | Puede ser A **O** B | Tiene que ser A **Y** B |
| **Propiedades requeridas** | Solo las de uno de los dos | Las de **ambos** |
| **Analogía** | Elegir UNA carta | Tener las DOS cartas |
| **Cuándo usarlo** | Cuando algo puede ser de distintos tipos | Cuando algo debe combinar varios tipos |

```typescript
type A = { nombre: string };
type B = { edad: number };

// Union: puede ser A o B (solo necesita propiedades de uno)
type UnionAB = A | B;
const x1: UnionAB = { nombre: "Lucas" };  // ✅ Solo A
const x2: UnionAB = { edad: 25 };          // ✅ Solo B

// Intersection: tiene que ser A y B (necesita propiedades de ambos)
type InterseccionAB = A & B;
const y1: InterseccionAB = { nombre: "Lucas", edad: 25 }; // ✅ Ambos
const y2: InterseccionAB = { nombre: "Lucas" };            // ❌ Falta edad
```

---

## 🏋️ Ejemplo Real en ObsidiantFit

### Caso 1: Usuario con Permisos de Admin

```typescript
// Los datos básicos de cualquier usuario
type UsuarioBase = {
  id: number;
  nombre: string;
  email: string;
};

// Los permisos especiales del administrador
type PermisosAdmin = {
  puedeEliminarUsuarios: boolean;
  puedeVerEstadisticas: boolean;
  puedeGestionarPlanes: boolean;
};

// Un AdminUsuario tiene TODO: datos de usuario + permisos admin
type AdminUsuario = UsuarioBase & PermisosAdmin;

const admin: AdminUsuario = {
  id: 1,
  nombre: "Lucas",                      // de UsuarioBase
  email: "lucas@obsidianfit.com",       // de UsuarioBase
  puedeEliminarUsuarios: true,          // de PermisosAdmin
  puedeVerEstadisticas: true,           // de PermisosAdmin
  puedeGestionarPlanes: false           // de PermisosAdmin
};
```

### Caso 2: Request de NestJS con Usuario Autenticado

```typescript
// El tipo Request que viene de NestJS (simplificado)
type Request = {
  body: any;
  headers: Record<string, string>;
  params: Record<string, string>;
};

// El usuario que decodificamos del JWT
type UsuarioAutenticado = {
  userId: number;
  email: string;
  rol: string;
};

// Combinamos ambos: el request original + el usuario autenticado
type RequestAutenticado = Request & { usuario: UsuarioAutenticado };

// En el controlador de NestJS:
function obtenerPerfil(req: RequestAutenticado) {
  const userId = req.usuario.userId;  // ✅ Tenemos acceso al usuario
  const body = req.body;              // ✅ Y también al body del request
}
```

### Caso 3: Combinar Datos con Metadatos

```typescript
type DatosRutina = {
  nombre: string;
  ejercicios: string[];
  duracion: number;
};

type MetadatosDB = {
  id: number;
  createdAt: Date;
  updatedAt: Date;
};

// Lo que guardamos en la BD: datos + metadatos generados automáticamente
type RutinaGuardada = DatosRutina & MetadatosDB;
```

---

## ⚠️ El Peligro: Propiedades en Conflicto

Si los dos tipos tienen **la misma propiedad con distintos tipos**, TypeScript crea un tipo **`never`** (imposible):

```typescript
type A = { id: number };
type B = { id: string };  // ¡id también está en A pero con distinto tipo!

type Conflicto = A & B;
// Resultado: { id: never }
// ¿Por qué? Porque id tendría que ser number Y string al mismo tiempo.
// Eso es imposible, así que TypeScript lo marca como "never".

const problema: Conflicto = { id: 1 };    // ❌ Error
const problema2: Conflicto = { id: "a" }; // ❌ Error
// No hay forma válida de crearlo!
```

**La regla**: cuando combinás tipos con `&`, asegurate de que no tengan propiedades con el mismo nombre y distintos tipos.

---

## 🔄 Intersection vs Extends (en Interfaces)

Tanto `&` como `extends` sirven para combinar tipos, pero hay una diferencia:

```typescript
// Con Intersection Type (&)
type Animal = { nombre: string };
type Mascota = Animal & { dueño: string };

// Con extends (en interfaces)
interface Animal2 { nombre: string }
interface Mascota2 extends Animal2 { dueño: string }

// El resultado es prácticamente el mismo
// Diferencia: extends detecta conflictos en el momento de definir
// & los detecta en el momento de usar
```

En la práctica, para **interfaces** usás `extends`, y para **types** usás `&`.

---

## 💡 El Truco del Experto

Los Intersection Types son muy útiles para el patrón **Mixin**: tomar múltiples funcionalidades pequeñas y combinarlas en un objeto más grande sin crear una herencia compleja.

```typescript
// Pequeñas "habilidades" separadas
type PuedeNadar = { nadar(): void };
type PuedeVolar = { volar(): void };
type PuedeCaminar = { caminar(): void };

// Un pato puede hacer todo (el clásico ejemplo del Pato de Donald)
type Pato = PuedeNadar & PuedeVolar & PuedeCaminar;
```
