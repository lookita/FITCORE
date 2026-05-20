# Modificadores de Acceso — El VIP del Gimnasio

---

## 🧠 ¿Qué son los modificadores?

Son palabras que ponemos antes de las propiedades o métodos para decidir **quién puede verlos y quién puede tocarlos**.

Imagina que en tu gimnasio hay distintas áreas:

---

## 📍 Los 3 niveles de acceso

### 1. `public` (La recepción) 🔓
Todo el mundo puede entrar y ver lo que hay. Si no pones nada, TypeScript asume que es público.
```typescript
class Ejercicio {
  public nombre: string = "Flexiones";
}
const ex = new Ejercicio();
console.log(ex.nombre); // ✅ Se puede leer desde afuera
```

### 2. `private` (La oficina del dueño) 🔒
**Solo la propia clase** puede entrar. Ni siquiera los "hijos" (clases que heredan) pueden verlo.
```typescript
class Usuario {
  private clave: string = "secreto123";

  validarClave(intento: string) {
    return this.clave === intento; // ✅ Adentro de la clase sí funciona
  }
}
const u = new Usuario();
// console.log(u.clave); // ❌ Error: "Es privado, no tenés la llave"
```

### 3. `protected` (El Secreto de Familia) 👤
Aquí es donde se pone interesante. Es un punto medio entre público y privado.

#### 🧒 Nivel 5 años: "El Tesoro Familiar"
Imagina que tu abuelo tiene un **cofre con monedas de oro**.
- Si fuera `public`, cualquier vecino podría entrar y llevarse una.
- Si fuera `private`, solo el abuelo podría abrirlo. ¡Ni siquiera tú podrías!
- Pero es **`protected`**: El abuelo puede usarlo, y **tú (su hijo) también puedes**, porque eres de la familia. Pero el vecino... ¡el vecino sigue sin poder tocarlo!

#### 🧑‍💻 Nivel Desarrollador: "Herencia y Reutilización"
`protected` permite que las subclases accedan a miembros de la clase base, manteniendo la encapsulación frente al resto del mundo.

```typescript
class Motor {
  protected temperatura: number = 0;

  protected alertarSobrecalentamiento() {
    console.log("¡Cuidado, motor caliente!");
  }
}

class MotorDeCarreras extends Motor {
  public correr() {
    this.temperatura += 50; // ✅ OK: Puede acceder porque hereda de Motor
    if (this.temperatura > 100) {
      this.alertarSobrecalentamiento(); // ✅ OK: Método protegido disponible para el hijo
    }
  }
}

const miMotor = new MotorDeCarreras();
// miMotor.temperatura = 200; // ❌ Error: El usuario del motor no debe tocar la temperatura directamente
```

#### 🧙 Nivel Experto: "Template Method & Arquitectura"
En arquitecturas complejas, `protected` es la clave para el **Patrón Template Method**. Permite definir un "esqueleto" de algoritmo en una clase base, dejando que los hijos rellenen los pasos específicos sin exponer esos pasos al exterior.

**Diferencia clave con `private`:**
- `private` bloquea la extensibilidad. Si haces algo privado, obligas a tus herederos a usar tus métodos públicos para interactuar con ese estado.
- `protected` fomenta la **colaboración entre clases del mismo linaje**.

```typescript
abstract class RepositorioBase<T> {
  // Solo los hijos (ej: UsuariosRepo) pueden usar la conexión
  protected dbConnection: string = "DATABASE_URL_SECRET";

  // Método público: El mundo exterior solo ve esto
  public async save(data: T) {
    this.validar(data);
    this.conectar();
    console.log("Guardando en base de datos...");
  }

  // Pasos internos: Protegidos para que el hijo pueda personalizarlos si quiere
  protected validar(data: T) {
    console.log("Validación genérica");
  }

  private conectar() {
    console.log(`Conectando a ${this.dbConnection}`);
  }
}

class UsuariosRepo extends RepositorioBase<{name: string}> {
  // Sobrescribimos la validación (Personalización del hijo)
  protected override validar(data: { name: string }): void {
      if (data.name.length < 3) throw new Error("Nombre muy corto");
      console.log("Validación de usuario exitosa");
  }
}
```

#### 🛡️ Dato Pro: ¿Es realmente "seguro"?
A nivel de **TypeScript**, `protected` y `private` son bloqueos de compilación. Pero cuando el código se convierte a JavaScript, ¡desaparecen! Si alguien usa tu código en JS, podrá ver todo.

Si quieres privacidad **real y total** (incluso en el navegador/consola), usa los campos privados de JavaScript (`#`):
```typescript
class SecretoTotal {
  #claveReal = "12345"; // Privacidad de runtime (JS nativo)
}
```
*`protected` no existe en JS nativo, es una herramienta puramente de TypeScript para ayudarnos a organizar mejor nuestro código.*

---

## ❄️ El modificador `readonly` (Solo lectura)

Esto es como un **trofeo en una vitrina**. Puedes verlo, pero no puedes cambiarle la placa.
Se puede combinar con los anteriores (ej: `private readonly`).

```typescript
class Usuario {
  public readonly id: number;

  constructor(id: number) {
    this.id = id; // ✅ Se puede asignar en el constructor
  }
}

const u = new Usuario(5);
console.log(u.id); // ✅ Leer se puede
// u.id = 10;      // ❌ Error: "Es de solo lectura, no seas travieso"
```

---

## 🏋️ Ejemplo Real en ObsidiantFit

```typescript
class Rutina {
  public nombre: string;              // Todos ven el nombre
  private readonly idInterno: string; // Secreto y nunca cambia
  protected ejercicios: string[] = []; // Solo esta rutina y las que hereden de ella

  constructor(nombre: string) {
    this.nombre = nombre;
    this.idInterno = Math.random().toString();
  }
}
```

---

## 🎓 Resumen para recordar

| Modificador | ¿Se ve desde afuera? | ¿Se ve desde el hijo? | ¿Se ve desde la clase? |
|---|---|---|---|
| `public` | ✅ | ✅ | ✅ |
| `protected` | ❌ | ✅ | ✅ |
| `private` | ❌ | ❌ | ✅ |
| `readonly` | ✅ (solo lectura) | ✅ (solo lectura) | ✅ (solo lectura) |

---

## 💡 El Truco del Experto (Atajo de Constructor)

En TypeScript puedes crear la propiedad e inicializarla **todo en una sola línea** dentro del constructor:

```typescript
// ESTO:
class Usuario {
  constructor(public nombre: string, private edad: number) {}
}

// ES LO MISMO QUE ESTO (pero más corto):
class Usuario {
  public nombre: string;
  private edad: number;
  constructor(nombre: string, edad: number) {
    this.nombre = nombre;
    this.edad = edad;
  }
}
```
*Este atajo se usa muchísimo en NestJS para la inyección de dependencias.*
