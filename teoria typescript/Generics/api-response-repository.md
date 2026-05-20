# ApiResponse<T> y Repository<T> — El Sobre y la Biblioteca

---

## 📬 PARTE 1: ApiResponse<T> — El Sobre de Respuesta

### ¿Qué problema resuelve?

Imaginate que tu app manda mensajeros a buscar información al servidor.
Cada mensajero puede traer cosas muy distintas:
- A veces trae **un usuario**
- A veces trae **una lista de rutinas**
- A veces trae **un número** (cuántos ejercicios hay)
- A veces trae **un error**

El problema: si cada mensajero llega de forma distinta, tu código no sabe cómo recibirlos.

**La solución**: que TODOS los mensajeros lleguen en el **mismo tipo de sobre**. El sobre siempre tiene la misma forma afuera, pero el contenido de adentro cambia.

---

### La Forma del Sobre

```typescript
type ApiResponse<T> = {
  data: T;              // El contenido que cambia (el Generic <T>)
  success: boolean;     // ¿La operación fue exitosa? true o false
  message?: string;     // Mensaje opcional: puede ser de éxito o error
};
```

¿Ves el `<T>`? Acá es donde entra el Generic.
- Si pedís un usuario: `T = Usuario`
- Si pedís una lista: `T = Rutina[]`
- Si pedís un número: `T = number`

---

### Cómo se usa en la práctica

```typescript
// El tipo base del usuario
type Usuario = {
  id: number;
  nombre: string;
  email: string;
};

// Definimos el tipo del sobre
type ApiResponse<T> = {
  data: T;
  success: boolean;
  message?: string;
};

// ---- Diferentes respuestas posibles ----

// 1. El sobre que trae UN usuario
type RespuestaUsuario = ApiResponse<Usuario>;
// Equivale a:
// { data: Usuario, success: boolean, message?: string }

// 2. El sobre que trae una LISTA de rutinas
type RespuestaRutinas = ApiResponse<Rutina[]>;
// Equivale a:
// { data: Rutina[], success: boolean, message?: string }

// 3. El sobre que trae un NÚMERO (ej: total de usuarios)
type RespuestaConteo = ApiResponse<number>;
// Equivale a:
// { data: number, success: boolean, message?: string }
```

---

### Ejemplo Real: Función del Backend

```typescript
async function obtenerPerfil(id: number): Promise<ApiResponse<Usuario>> {
  try {
    // Buscamos al usuario en la base de datos
    const usuario = await buscarEnBD(id);

    // ✅ Éxito: enviamos el sobre lleno
    return {
      data: usuario,        // El usuario que encontramos
      success: true,
      message: "Perfil cargado correctamente"
    };

  } catch (error) {
    // ❌ Error: enviamos el sobre con error
    return {
      data: null,           // No hay datos
      success: false,
      message: "No se encontró el usuario con ese ID"
    };
  }
}
```

### Ejemplo Real: Cómo lo usa el Frontend

```typescript
async function cargarPerfil() {
  const respuesta = await obtenerPerfil(1);

  // Primero verificamos el sobre
  if (respuesta.success) {
    // Si el sobre llegó bien, abrimos el contenido
    console.log("Nombre:", respuesta.data.nombre);
    console.log("Email:", respuesta.data.email);
  } else {
    // Si hubo error, mostramos el mensaje
    console.error("Error:", respuesta.message);
  }
}
```

---

### ¿Por qué no devolver directamente el Usuario?

```typescript
// ❌ Sin ApiResponse: no sabés si hubo error ni qué pasó
async function obtenerPerfil(id: number): Promise<Usuario> {
  return await buscarEnBD(id);
  // ¿Y si falla? ¿Cómo avisás al frontend?
}

// ✅ Con ApiResponse: siempre tenés toda la información
async function obtenerPerfil(id: number): Promise<ApiResponse<Usuario>> {
  // Siempre devolvés el sobre, con éxito o error
}
```

---

## 📚 PARTE 2: Repository<T> — La Biblioteca Mágica

### ¿Qué problema resuelve?

En ObsidiantFit tenés muchos tipos de datos para guardar:
- Usuarios
- Rutinas
- Ejercicios
- Suscripciones
- Pagos

Cada uno necesita las mismas operaciones básicas:
- Buscar uno por ID
- Buscar todos
- Guardar
- Actualizar
- Eliminar

Sin Generics, tendrías que escribir esto **5 veces** (una para cada entidad). Con el `Repository<T>` Generic, lo escribís **una sola vez**.

---

### La Interfaz del Repository

```typescript
// La interfaz genérica: el "contrato" que todo repositorio debe cumplir
interface Repository<T> {
  encontrarPorId(id: number): Promise<T | null>;    // Buscar uno
  encontrarTodos(): Promise<T[]>;                    // Buscar todos
  guardar(entidad: T): Promise<T>;                   // Crear o actualizar
  eliminar(id: number): Promise<void>;               // Eliminar
}
```

---

### Implementaciones Concretas

```typescript
// Repositorio de Usuarios: implementa Repository con T = Usuario
class UsuarioRepository implements Repository<Usuario> {

  async encontrarPorId(id: number): Promise<Usuario | null> {
    // Busca en la tabla 'usuarios' de la base de datos
    return await db.usuarios.findOne({ where: { id } });
  }

  async encontrarTodos(): Promise<Usuario[]> {
    return await db.usuarios.findAll();
  }

  async guardar(usuario: Usuario): Promise<Usuario> {
    return await db.usuarios.save(usuario);
  }

  async eliminar(id: number): Promise<void> {
    await db.usuarios.delete({ id });
  }
}

// Repositorio de Rutinas: exactamente el mismo patrón, distinto tipo
class RutinaRepository implements Repository<Rutina> {

  async encontrarPorId(id: number): Promise<Rutina | null> {
    return await db.rutinas.findOne({ where: { id } });
  }

  async encontrarTodos(): Promise<Rutina[]> {
    return await db.rutinas.findAll();
  }

  async guardar(rutina: Rutina): Promise<Rutina> {
    return await db.rutinas.save(rutina);
  }

  async eliminar(id: number): Promise<void> {
    await db.rutinas.delete({ id });
  }
}
```

---

### La Magia: Un Repository Genérico Universal

Si querés llevar esto al máximo nivel, podés crear un repositorio base que ya implementa todo y solo lo extendés:

```typescript
// Una clase base que ya tiene todo implementado
class BaseRepository<T> implements Repository<T> {
  constructor(private readonly tabla: string) {}

  async encontrarPorId(id: number): Promise<T | null> {
    return await db[this.tabla].findOne({ where: { id } });
  }

  async encontrarTodos(): Promise<T[]> {
    return await db[this.tabla].findAll();
  }

  async guardar(entidad: T): Promise<T> {
    return await db[this.tabla].save(entidad);
  }

  async eliminar(id: number): Promise<void> {
    await db[this.tabla].delete({ id });
  }
}

// Ahora los repositorios específicos son de 1 sola línea
class UsuarioRepository extends BaseRepository<Usuario> {
  constructor() { super("usuarios"); }
}

class RutinaRepository extends BaseRepository<Rutina> {
  constructor() { super("rutinas"); }
}

class EjercicioRepository extends BaseRepository<Ejercicio> {
  constructor() { super("ejercicios"); }
}
```

¿Ves la diferencia? En vez de repetir 30 líneas de código por entidad, cada repositorio es de **1 sola línea**.

---

## 🔗 Combinando Todo: El Flujo Completo

Así es cómo ApiResponse y Repository trabajan juntos en ObsidiantFit:

```typescript
class UsuarioService {
  constructor(private usuarioRepo: UsuarioRepository) {}

  async obtenerUsuario(id: number): Promise<ApiResponse<Usuario>> {
    try {
      // 1. Usamos el repository para buscar en la BD
      const usuario = await this.usuarioRepo.encontrarPorId(id);

      if (!usuario) {
        // 2a. Si no existe, respondemos con error
        return {
          data: null,
          success: false,
          message: "Usuario no encontrado"
        };
      }

      // 2b. Si existe, respondemos con éxito
      return {
        data: usuario,
        success: true,
        message: "Usuario encontrado"
      };

    } catch (error) {
      // 3. Si hay error de BD, lo capturamos
      return {
        data: null,
        success: false,
        message: "Error al conectar con la base de datos"
      };
    }
  }
}
```

---

## 🎓 Resumen

| Concepto | ¿Qué hace? | ¿Cuándo usarlo? |
|---|---|---|
| `ApiResponse<T>` | Envuelve cualquier respuesta en un "sobre" estándar | Siempre que el backend responda al frontend |
| `Repository<T>` | Define las operaciones CRUD para cualquier entidad | Para conectar con la base de datos |

---

## 💡 El Truco del Experto

En NestJS, TypeORM ya tiene su propio `Repository<T>` incorporado. Cuando inyectás:
```typescript
constructor(@InjectRepository(Usuario) private repo: Repository<Usuario>) {}
```
¡Estás usando exactamente este patrón! Solo que TypeORM ya lo tiene implementado internamente.
