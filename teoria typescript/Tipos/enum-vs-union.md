# Enum vs Type Union: El Manual Definitivo de ObsidiantFit ⚔️📜

---

## 🏔️ La Gran Diferencia Filosófica

Aunque ambos sirven para elegir entre opciones, su comportamiento bajo el capó es totalmente distinto.

### 1. Type Union (El Estándar Moderno)
Es una herramienta de **Compilación**. Solo existe mientras escribes código en TypeScript. Cuando se convierte a JavaScript, desaparece por completo.

```typescript
type TipoCuerpo = 'Ectomorfo' | 'Mesomorfo' | 'Endomorfo';
```

### 2. Enum (El Objeto Real)
Es una herramienta de **Runtime**. TypeScript crea un objeto real en JavaScript para guardar estos valores.

```typescript
enum TipoCuerpo {
  Ecto = 'ECTO',
  Meso = 'MESO',
  Endo = 'ENDO'
}
```

---

## 🏋️ Casos de Uso Extensos en el Programa

### Caso A: Dificultad de Rutina (Type Union es Ganador)
Para algo que solo usas para validar texto sencillo, la Type Union es más rápida y limpia.

```typescript
type Dificultad = 'Novato' | 'Intermedio' | 'Pro' | 'Elite';

function asignarRutina(nivel: Dificultad) {
  console.log(`Buscando rutina para nivel ${nivel}...`);
}

asignarRutina('Pro'); // ✅ Sencillo y directo
```

### Caso B: Estados de Suscripción (Enum es Ganador)
Cuando los estados tienen un valor numérico o un código que la Base de Datos entiende mejor, usamos Enums.

```typescript
enum UserStatus {
  INACTIVE = 0,
  ACTIVE = 1,
  SUSPENDED = 2,
  BANNED = 99
}

class Usuario {
  status: UserStatus = UserStatus.ACTIVE;

  estaBloqueado() {
    return this.status === UserStatus.BANNED;
  }
}
```

---

## 🧙 Nivel Experto: ¿Por qué preferir Type Unions?

En la comunidad de TypeScript, se prefiere la Union Type por tres razones:
1.  **Inferencia:** TypeScript entiende mejor qué estás haciendo sin necesidad de importar el Enum en cada archivo.
2.  **Menos código:** Al no generar un objeto en JavaScript, tu archivo final es más pequeño.
3.  **Flexibilidad:** Puedes combinar uniones de strings con otros tipos fácilmente.

### Ejemplo de "Power Union"
```typescript
type ResultadoEntrenamiento = number | 'DESCANSADO' | 'LESIONADO';

function registrarResultado(valor: ResultadoEntrenamiento) {
  if (typeof valor === 'number') {
    console.log(`Quemaste ${valor} calorías`);
  } else {
    console.log(`Estado del día: ${valor}`);
  }
}
```

---

## ⚡ Cuadro Comparativo de Decisión

| Si necesitas... | Usa... |
|---|---|
| Solo validar un texto fijo | `Type Union` |
| Valores que vienen de una API externa | `Enum` |
| Combinar tipos distintos (string + number) | `Type Union` |
| Que el valor tenga un nombre descriptivo | `Enum` |
| Máximo rendimiento y ligereza | `Type Union` |

---

## 🔑 Resumen Maestro para ObsidiantFit

- Usa **Type Union** para casi todo: nombres de ejercicios, dificultades, días de la semana, tipos de músculos.
- Usa **Enum** solo cuando el valor tenga un significado técnico especial (IDs de base de datos, códigos de error, estados de pago).
