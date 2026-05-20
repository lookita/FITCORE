# Datos Binarios: Buffer, Streams y Base64 📦📦📦

---

## 🧠 ¿Qué es un Buffer? (Nivel ELI5)

Imagina que quieres enviarle a un amigo una **Mancuerna** por correo.
- No puedes meterla en un sobre de carta normal (eso sería un `string` o `number`).
- Necesitas una **Caja de Madera** resistente que aguante el peso y la forma de la mancuerna.

En Node.js, el **Buffer** es esa caja de madera. Es un espacio de memoria que guarda datos "crudos" (bytes) que no son necesariamente texto, como **imágenes, audios o archivos PDF**.

---

## 🛡️ Nivel 1: El Buffer (La foto de perfil)

En **ObsidiantFit**, cuando un usuario sube su foto de perfil, el servidor no recibe "texto", recibe un **Buffer**.

```typescript
// Crear un buffer a partir de un string (solo para entender la estructura)
const buf = Buffer.from("ObsidiantFit"); 

console.log(buf); 
// Output: <Buffer 4f 62 73 69 64 69 61 6e 74 46 69 74> 
// ¡Son números hexadecimales! Cada par es un byte.

// Ejemplo Real: Leer una foto del progreso
import fs from 'fs';

const fotoBuffer: Buffer = fs.readFileSync('./fotos/antes.jpg');
console.log(`La foto pesa ${fotoBuffer.length} bytes`);
```

---

## 🏗️ Nivel 2: Streams (El video de entrenamiento)

¿Qué pasa si un usuario sube un video de su rutina que pesa 2GB?
- Si usamos un **Buffer**, el servidor intentará meter esos 2GB en la memoria RAM de golpe. 💥 **¡Crash!**
- Para eso existen los **Streams** (Arroyos).

**La Filosofía del Stream:** No bebas todo el océano de un trago, usa una pajita.

```typescript
import fs from 'fs';

// En lugar de leer todo el video, lo leemos por "pedacitos" (chunks)
const videoStream = fs.createReadStream('./videos/rutina-piernas.mp4');

videoStream.on('data', (pedacito) => {
  console.log(`Recibido un pedacito de video de ${pedacito.length} bytes`);
  // Aquí podemos ir subiéndolo a la nube pedazo a pedazo
});
```

---

## 🧙 Nivel 3: Base64 (El camuflaje de datos)

A veces necesitas enviar una imagen dentro de un objeto JSON (que solo acepta texto). Para eso "disfrazamos" el Buffer como un texto largo usando **Base64**.

```typescript
const fotoBuffer = fs.readFileSync('./logo.png');
const base64String = fotoBuffer.toString('base64');

// Ahora podemos enviarlo en un JSON
const respuesta = {
  usuario: "Lucas",
  avatar: `data:image/png;base64,${base64String}`
};
```
*⚠️ Nota de experto: Base64 hace que el archivo pese un 33% más. Úsalo solo para archivos muy pequeños como iconos.*

---

## 🏋️ Ejemplos Reales en ObsidiantFit

### 1. Exportar Rutina a PDF
Cuando el usuario quiere su plan en PDF, el servidor genera un Buffer con los datos binarios del documento y se lo envía al navegador.

```typescript
class PDFGenerator {
  generate(data: any): Buffer {
    // ... lógica para crear el PDF
    return Buffer.from("%PDF-1.4 ..."); // Retorna el archivo crudo
  }
}
```

### 2. Procesar el pulso de un Smartwatch
Los smartwatches envían datos de frecuencia cardíaca en paquetes binarios muy pequeños para ahorrar batería. El backend de ObsidiantFit usa Buffers para leer esos bytes exactos.

```typescript
function procesarPulso(datosReloj: Buffer) {
  const pulso = datosReloj.readUInt8(0); // Lee el primer byte como un número
  console.log(`Pulso actual: ${pulso} BPM`);
}
```

### 3. Hash de Contraseñas
Las contraseñas no se guardan como texto, se pasan por un proceso criptográfico que genera un Buffer de bytes aleatorios (Salt).

---

## 🔑 Diferencias Clave

| Concepto | Analogía | Cuándo usarlo |
|---|---|---|
| **Buffer** | Una maleta llena. | Archivos pequeños/medianos (imágenes, documentos). |
| **Stream** | Una manguera abierta. | Archivos grandes (videos, logs infinitos, subidas pesadas). |
| **ArrayBuffer** | El cajón de la maleta. | Versión de navegador (estándar de JavaScript) del Buffer. |
| **Base64** | Una foto de la maleta. | Para meter imágenes pequeñas dentro de archivos de texto (HTML/JSON). |

---

## 💡 Resumen Maestro

1.  **Buffer:** Es memoria RAM cruda. Para Node.js, todo lo que no es texto es un Buffer.
2.  **Eficiencia:** El Buffer es mucho más rápido que el String para manipular datos binarios.
3.  **Seguridad:** Cuando subas archivos en NestJS, los recibirás como un objeto con un Buffer adentro.
4.  **Conversión:** Puedes pasar de Buffer a String y viceversa fácilmente: `buf.toString('utf-8')` o `Buffer.from(str)`.
