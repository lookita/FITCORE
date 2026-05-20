# Parámetros Rest — La Mochila Infinita

### ¿Qué es esto? (La analogía) 🎒✨
Imagina que tienes una mochila mágica. No importa cuántas pesas le metas (1, 5 o 100), la mochila se estira y las guarda todas en una lista organizada. El parámetro Rest (se escribe con tres puntitos `...`) sirve para cuando no sabes cuántas cosas te van a pasar.

### ¿Cuándo lo uso en ObsidiantFit? ♾️
- Una función que recibe **muchos ejercicios** para crear una rutina completa. No sabes si el usuario eligió 3 o 10 ejercicios.
- Una función para calcular el peso total de **muchos discos** puestos en una barra.
- Guardar una lista de etiquetas (tags) como "Fuerza", "Core", "Sudor".

### ¿Cómo se usa? (Paso a paso)
```typescript
// '...ejercicios' junta todo lo que mandes en un solo Array.
function armarRutina(...ejercicios: string[]) {
  console.log("Tu rutina de hoy tiene estos ejercicios:");
  ejercicios.forEach(ej => console.log("- " + ej));
}

armarRutina("Sentadilla", "Prensa"); // Recibe 2
armarRutina("Bici", "Yoga", "Estiramiento", "Ducha"); // Recibe 4
```

### El Truco del Experto 💡
El parámetro Rest siempre tiene que ser **el último** en la lista de parámetros de tu función. ¡No puedes poner nada después de la mochila mágica!

---

### Más ejemplos prácticos para ObsidiantFit 🚀

#### 1. Sumar kilos en la barra (Cálculo de peso)
Ideal para cuando vas agregando discos y quieres saber el peso total sin importar cuántos discos pongas.

```typescript
function calcularCargaTotal(...discos: number[]): number {
  // .reduce suma todos los números del array
  return discos.reduce((total, disco) => total + disco, 0);
}

const pesoBarra = 20;
const total = pesoBarra + calcularCargaTotal(10, 10, 5, 5, 2.5); 
console.log(`Peso total: ${total}kg`); // Peso total: 52.5kg
```

#### 2. Registrar logros del día
Cuando un usuario termina su entrenamiento y quieres guardar todas las medallas que ganó.

```typescript
function guardarLogros(usuario: string, ...logros: string[]) {
  console.log(`¡Felicidades ${usuario}! Ganaste ${logros.length} medallas:`);
  console.log(logros.join(" | ")); 
}

guardarLogros("Lucas", "Primer PR", "10k pasos", "Madrugador");
```

#### 3. Mezclando parámetros fijos con Rest
Recuerda que el Rest va al final. Aquí definimos el tipo de entrenamiento y luego la lista de ejercicios.

```typescript
function crearPlan(objetivo: string, ...ejercicios: string[]) {
  console.log(`Plan enfocado en: ${objetivo.toUpperCase()}`);
  console.log(`Lista de ejercicios: ${ejercicios.length}`);
}

crearPlan("Ganar Fuerza", "Press Militar", "Dominadas", "Peso Muerto");
```
#### 4. Contar repeticiones totales
Si haces varias series de flexiones (push-ups), puedes pasar todas las repeticiones y sumarlas rápidamente.

```typescript
function repsTotales(...series: number[]): number {
  return series.reduce((total, reps) => total + reps, 0);
}

const totalFlexiones = repsTotales(15, 12, 10, 10, 8);
console.log(`Hoy hiciste ${totalFlexiones} flexiones en total.`);
```

#### 5. Etiquetas de equipamiento (o falta de él)
Como no usamos máquinas, podemos etiquetar qué implementos mínimos necesitamos (Kettlebell, Banda, o simplemente el Suelo).

```typescript
function asignarEtiquetas(ejercicio: string, ...tags: string[]) {
  console.log(`El ejercicio [${ejercicio}] requiere: ${tags.join(", ")}`);
}

asignarEtiquetas("Muscle Up", "Barra Fija", "Magnesia", "Explosividad");
asignarEtiquetas("Plancha", "Suelo", "Core", "Resistencia");
```

#### 6. Notificar a compañeros de entrenamiento
Imagina que quieres avisar a varios amigos que ya empezaste tu rutina de calistenia.

```typescript
function avisarAmigos(mensaje: string, ...amigos: string[]) {
  amigos.forEach(amigo => {
    console.log(`Enviando a ${amigo}: "${mensaje}"`);
  });
}

avisarAmigos("¡Vengan al parque a entrenar!", "Carlos", "Marta", "Santi");
```
