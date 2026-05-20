# Testing y Mocks: El Arte del Engaño Controlado 🎭

---

## 🧘 La Filosofía: El "Doble de Acción" (Stunt Double)

Filosóficamente, un **Mock** es un objeto que **pretende ser otro**. 

En la vida real, tu programa está lleno de "peligros":
- Enviar un email real cuesta dinero y molesta al usuario.
- Borrar una base de datos real es una catástrofe.
- Cobrar una tarjeta de crédito real requiere permisos y dinero de verdad.

El **Mock** nace de la necesidad de **Aislamiento**. Queremos probar si nuestra lógica de "Calcular nivel de entrenamiento" funciona, pero sin que esa prueba dependa de si el servidor de Amazon está caído o si el usuario tiene internet.

**El Mock es un "Egoísta Colaborador":** No hace nada real, pero le dice a tu código exactamente lo que este quiere escuchar para poder seguir ejecutándose.

---

## 🔄 El Flujo de Ejecución con Mocks (Workflow)

Imagina que estamos testeando el proceso de **"Suscripción Premium"** en ObsidiantFit.

### 1. El Escenario (La Realidad)
Normalmente, el flujo sería:
`Usuario -> SuscripcionService -> PasarelaStripe -> BANCO REAL`

### 2. El Flujo con Mock (El Test)
Para el test, el flujo cambia a:
`Test -> SuscripcionService -> MOCK de Stripe (Falso)`

#### Pasos del programa durante el Mocking:

1.  **Configuración (The Setup):**
    Le decimos al Mock cómo debe comportarse. *"Si alguien te pregunta por el pago de Lucas, dile que fue EXITOSO"*.
    ```typescript
    const stripeMock = {
      procesar: () => { return { status: 'ok', id: 'fake_123' } }
    };
    ```

2.  **Inyección (The Injection):**
    Usamos la **Inyección de Dependencias** para "engañar" al servicio. Le damos el impostor en lugar del real.
    ```typescript
    const service = new SuscripcionService(stripeMock);
    ```

3.  **Ejecución (The Action):**
    Llamamos a la función que queremos probar. El servicio corre su lógica normal, pero cuando llega al momento de cobrar, usa nuestro Mock.
    ```typescript
    service.contratarPlan("Lucas", "Plan_Pro");
    ```

4.  **Verificación / El "Soplón" (The Assertion):**
    Aquí es donde el Mock se vuelve útil. Le preguntamos: *"Oye, ¿el servicio te llamó de verdad? ¿Y te pasó el monto correcto de $29.99?"*.
    ```typescript
    expect(stripeMock.procesar).toHaveBeenCalledWith(29.99);
    ```

---

## 🏋️ Ejemplo Real en ObsidiantFit: "El Detector de Récords"

Queremos probar una función que felicita al usuario si rompe un récord, pero **no queremos enviar correos de verdad** cada vez que corremos el test (que son 50 veces al día).

### El Código a Probar
```typescript
class RecordService {
  constructor(private mailer: IMailer) {}

  evaluarSesion(puntosActuales: number, recordAnterior: number) {
    if (puntosActuales > recordAnterior) {
      this.mailer.send("¡Nuevo Récord!", "usuario@gym.com");
      return "FELICITADO";
    }
    return "SIGUE_INTENTANDO";
  }
}
```

### El Test con Mock
```typescript
// 1. Creamos el impostor
const mockMailer = {
  send: jest.fn() // Una función especial que anota todo lo que le pasa
};

// 2. Inyectamos
const service = new RecordService(mockMailer);

// 3. Ejecutamos un caso donde SÍ hay récord
const resultado = service.evaluarSesion(100, 80);

// 4. Verificamos la "filosofía" del mock
// A) ¿La lógica del servicio devolvió lo correcto?
console.log(resultado === "FELICITADO"); // true

// B) ¿El mailer fue llamado? (Sin enviar el mail real)
console.log(mockMailer.send).toHaveBeenCalled(); // true
```

---

## 🎭 Diferencias Clave (El Diccionario del Engaño)

No todos los "objetos falsos" son Mocks, aunque solemos llamarles así a todos:

1.  **Stub (El Callado):** Solo devuelve datos fijos. *"Si te preguntan la edad, di 25"*. No le importa cuántas veces lo llames.
2.  **Spy (El Espía):** Es un objeto real, pero se queda en la esquina anotando quién lo llamó y con qué datos.
3.  **Mock (El Estricto):** Es como un Stub, pero además se queja si no lo llamas como él esperaba. *"Me dijiste que me llamarías una vez, y me llamaste dos. ¡TEST FALLIDO!"*.

---

## 🔑 Resumen Maestro

- **Independencia:** El Mock te permite testear tu código aunque el resto del mundo esté caído.
- **Velocidad:** Un Mock de base de datos tarda 0.001s. Una base de datos real tarda 0.5s. En 1000 tests, eso es la diferencia entre 1 segundo y 8 minutos.
- **Predicibilidad:** Puedes forzar errores. *"Mock, ahora simula que la tarjeta de crédito NO tiene fondos"*. Es muy difícil probar eso con tarjetas reales.
- **Propósito:** El Mock no está para probar que la base de datos funciona, sino para probar que **TU CÓDIGO** sabe qué hacer cuando la base de datos le responde algo.
