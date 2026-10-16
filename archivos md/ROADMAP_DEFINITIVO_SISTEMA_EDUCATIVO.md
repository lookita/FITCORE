# ROADMAP DEFINITIVO - Sistema Educativo Santa Fe
**Enfoque:** Construcción progresiva de criterio DBA Jr. / Analista de Datos sobre una misma base relacional real.  
**Período de ejecución adaptado:** 13 de Octubre al 20 de Diciembre de 2026 *(Período de diseño base original: 6 de Octubre al 15 de Diciembre de 2026)*.

---

```text
                      PROYECTO INTEGRADOR: SISTEMA EDUCATIVO
                                          │
 ┌────────────────────────────────────────┴────────────────────────────────────────┐
 │                                                                                 │
FASE 0: Lectura & Algoritmo (13 - 19 Oct)                         FASE 1: SQL Consolidador (20 Oct - 2 Nov)
- Desglose de consignas en papel                                 - COUNT vs SUM sin dudas
- Definición de agregaciones y filtros                           - WHERE vs HAVING
- Identificación de máximos (global vs grupo)                    - Subconsultas correlacionadas
 │                                                                                 │
 └────────────────────────────────────────┬────────────────────────────────────────┘
                                          │
                                          ▼
                      FASE 2: Window Functions Atómicas (3 - 16 Nov)
                      - Día 1: ROW_NUMBER() por localidad
                      - Día 2: ROW_NUMBER() por departamento
                      - Día 3: RANK() (manejo de empates)
                      - Día 4: DENSE_RANK() (rankings continuos)
                      - Día 5: LAG() (comparación con fila anterior)
                      - Día 6: LEAD() (comparación con fila posterior)
                      - Día 7: SUM() OVER() (% sobre el total)
                      - Día 8: AVG() OVER() (comparación vs media)
                                          │
                                          ▼
                      FASE 3: Entorno PostgreSQL (17 - 23 Nov)
                      - Migración desde SQLite / MySQL
                      - Definición de tipos de datos, PK, FK y CHECK
                                          │
                                          ▼
                      FASE 4: Capa de Vistas (24 - 30 Nov)
                      - Abstracción de reportes complejos
                      - Vistas simples, compuestas y CTEs (CREATE VIEW)
                                          │
                                          ▼
                      FASE 5: Optimización Real & EXPLAIN (1 - 10 Dic)
                      - Diagnóstico de planes (Seq Scan)
                      - Creación estratégica de índices B-Tree
                      - Verificación de mejoras de costo (Index Scan)
                                          │
                                          ▼
                      FASE 6: Roles & Permisos (11 - 14 Dic)
                      - Creación de usuarios y roles
                      - Asignación y revocación de permisos (GRANT / REVOKE)
                                          │
                                          ▼
                      FASE 7: Mantenimiento & Backups (15 - 20 Dic)
                      - Respaldo lógico con pg_dump
                      - Pruebas de restauración y simulación de fallos
                                          │
 ┌────────────────────────────────────────┴────────────────────────────────────────┐
 │                                                                                 │
FASE 8: Analítica con Power BI (Extensión Diciembre)              FASE 9: Bases NoSQL - MongoDB (Enero)
- Conexión a PostgreSQL                                           - Modelado en documentos BSON
- Modelo en estrella (Hechos/Dimensiones)                        - Consultas find() y Aggregation Framework
- Dashboard de Matrícula Educativa                                - Cruces de colecciones con $lookup
```

---

# Desglose Fases y Entregables

---

## FASE 0: LECTURA DE CONSIGNA Y DISEÑO ALGORÍTMICO
**Cronograma de Ejecución:** 13 al 19 de octubre de 2026 *(Referencia de diseño original: 6 al 12 de octubre)*

### 1. ¿Qué estamos entrenando realmente?
Cuando una persona empieza SQL suele pensar:  
*"Me dieron una consigna. ¿Qué código tengo que escribir?"*  
Ese enfoque genera dependencia de la sintaxis.  
Ante una consigna nueva aparece inmediatamente:
- "¿Será JOIN?"
- "¿Será GROUP BY?"
- "¿Necesito un HAVING?"
- "¿Será una subconsulta?"
- "¿Uso ORDER BY DESC LIMIT 1?"
- "¿Uso COUNT o SUM?"

Y ahí empieza el problema.  
Porque SQL no es el problema principal.  
El problema es anterior:  
**¿Qué operación lógica está describiendo la consigna?**  
SQL solamente será el lenguaje mediante el cual expresás esa operación.  

Por eso esta semana no tiene como objetivo hacer 100 consultas.  
Tiene como objetivo que puedas mirar:  
*"¿Qué región tiene más escuelas?"*  
y que tu cabeza inmediatamente pueda desarmarlo:

```text
QUIERO:
    una región

QUE DEBO CALCULAR:
    cantidad de escuelas

CÓMO:
    contar escuelas por región

DESPUÉS:
    comparar las cantidades

RESULTADO:
    región con la cantidad máxima
```

Recién después aparece SQL.

### 2. El principio filosófico de esta fase
Hay una diferencia profunda entre:  
**memorizar soluciones**  
y  
**comprender estructuras.**

*Memorizar:*
```sql
GROUP BY ...
ORDER BY COUNT(*) DESC
LIMIT 1
```
te permite resolver una pregunta.

*Comprender:*  
*"La consigna pide encontrar el elemento cuyo valor agregado es máximo"*  
te permite resolver cien preguntas diferentes.

Por ejemplo:
- escuela con más alumnos
- departamento con más escuelas
- región con más instituciones
- sector con más establecimientos
- localidad con mayor matrícula
- combinación región + sector con más escuelas

Las entidades cambian.  
Los nombres cambian.  
Las columnas cambian.  
Pero la estructura algorítmica puede ser la misma.  
Eso es exactamente lo que queremos desarrollar.

### 3. El modelo mental de toda la semana
Durante estos siete días vas a aprender a traducir una consigna siguiendo esta cadena:

```text
CONSIGNA HUMANA
       ↓
OBJETIVO
       ↓
DATOS NECESARIOS
       ↓
AGRUPAMIENTO
       ↓
MÉTRICA
       ↓
FILTRO
       ↓
COMPARACIÓN
       ↓
RESULTADO ESPERADO
       ↓
PSEUDOCÓDIGO
       ↓
SQL
```

Pero durante la Fase 0 vamos a detenernos antes del último paso.  
Es decir:
```text
CONSIGNA
   ↓
ALGORITMO
   ↓
PSEUDOCÓDIGO
```
Todavía no necesitamos SQL.

### 4. Las cuatro preguntas fundamentales
El roadmap establece cuatro preguntas base.  
Vamos a convertirlas en un sistema mucho más preciso.

#### PREGUNTA 1: ¿Qué se agrupa?
Primero tenés que identificar qué dimensión está organizando los datos.  
Ejemplo:  
*¿Cuántas escuelas hay por departamento?*  
La dimensión es: **DEPARTAMENTO**  
Entonces: Agrupar por departamento.

Pero:  
*¿Cuántas escuelas hay por región y sector?*  
Ya no existe una sola dimensión. Tenemos: **REGIÓN + SECTOR**  
Entonces: Agrupamiento múltiple.  
Conceptualmente:
- Región Norte + Público
- Región Norte + Privado
- Región Sur + Público
- Región Sur + Privado

**Regla mental:**  
Si la consigna dice: *"por X"*, normalmente X representa una dimensión de agrupamiento.  
Si dice: *"por X y Y"*, probablemente necesitás una combinación: **X + Y**.

### 5. Peligro: confundir columnas mostradas con columnas agrupadoras
Supongamos:  
*"Indicar cuántas escuelas hay por región y mostrar el nombre de la región."*  
No significa: `GROUP BY región, cantidad`.  
La cantidad se calcula. La región organiza.  
Pensalo así:
- **REGIÓN** = categoría (dimensión)
- **COUNT** = cálculo (métrica agregada)

Esto parece pequeño, pero es una diferencia conceptual enorme.

### 6. PREGUNTA 2: ¿Qué métrica estoy calculando?
Esta será una de las partes más importantes de la semana.  
El roadmap específicamente plantea trabajar la diferencia entre:  
`COUNT`, `SUM`, `AVG`.  
Pero vamos a incorporar también:  
`MAX`, `MIN`, porque serán fundamentales para las preguntas de máximo.

* **COUNT:** responde a *¿Cuántos elementos hay?*
  - Ejemplo: ¿Cuántas escuelas hay por departamento?
  - Pensamiento: Tomar cada departamento $\rightarrow$ contar escuelas $\rightarrow$ obtener cantidad.
* **SUM:** responde a *¿Cuál es la suma de los valores?*
  - Ejemplo: ¿Cuántos alumnos tiene cada departamento en total?
  - Si Escuela A tiene 500 alumnos, Escuela B 300, Escuela C 200: no contamos escuelas (serían 3), sumamos alumnos: $500 + 300 + 200 = 1000$.
  - Por lo tanto: **COUNT $\neq$ SUM**.
* **AVG:** responde a *¿Cuál es el promedio?*
  - Ejemplo: ¿Cuál es el promedio de alumnos por escuela?
  - Si tenemos 500, 300, 200 $\rightarrow \text{AVG} = 333,33\dots$
* **MAX:** responde a *¿Cuál es el valor más alto?*
  - Ejemplo: entre 500, 300, 200 $\rightarrow \text{MAX} = 500$.
  - Cuestión crítica: MAX no siempre significa "quiero una fila". Puede significar *conocer el valor máximo* o *encontrar quién posee ese valor máximo*.

### 7. La distinción que te tiene que quedar grabada
Hay una diferencia entre:  
**"¿Cuál es el máximo?"** y **"¿Cuál tiene el máximo?"**
- *¿Cuál es la mayor cantidad de alumnos?* $\rightarrow$ Resultado: `850` (número).
- *¿Qué escuela tiene la mayor cantidad de alumnos?* $\rightarrow$ Resultado: `Escuela X, 850 alumnos` (entidad).

La segunda pregunta necesita identificar una entidad, no solamente obtener un número escalar.

### 8. PREGUNTA 3: ¿Dónde se aplica el filtro?
Acá aparece: **WHERE versus HAVING**.  
La diferencia conceptual es:
- **WHERE:** Filtra filas **antes** de agrupar.
- **HAVING:** Filtra grupos **después** de agrupar.

*Ejemplo conceptual 1:*  
Mostrar las escuelas de la región Norte.  
Tengo escuelas $\rightarrow$ descarto las que no son Norte $\rightarrow$ muestro restantes $\rightarrow$ **WHERE**.

*Ejemplo conceptual 2:*  
Mostrar los departamentos que tienen más de 50 escuelas.  
No eliminás escuelas individuales. Primero agrupás por departamento $\rightarrow$ calculás COUNT de escuelas $\rightarrow$ filtrás los totales $\ge 50$ $\rightarrow$ **HAVING**.

### 9. Una regla mental muy poderosa
Preguntate:  
*¿Estoy filtrando individuos o resultados agrupados?*  
- Si filtrás individuos: **WHERE**.  
- Si filtrás grupos: **HAVING**.

### 10. PREGUNTA 4: ¿Es máximo global o máximo por grupo?
Esta será probablemente la pregunta más importante de toda la semana.

* **Máximo global:**  
  *¿Qué escuela tiene más alumnos de todas las escuelas?*  
  Existe un único universo: todas las escuelas. Buscamos el máximo de todas.
* **Máximo por grupo:**  
  *¿Qué escuela tiene más alumnos dentro de cada departamento?*  
  Tenemos varios universos independientes: Departamento A, Departamento B, Departamento C. En cada uno hay un ganador.

Esto prepara el terreno directo para `ROW_NUMBER()`, `RANK()` y `DENSE_RANK()` en la Fase 2.

### 11. El gran mapa mental
```text
                   CONSIGNA
                       │
          ┌────────────┼────────────┐
          ↓            ↓            ↓
       CONTAR       SUMAR       PROMEDIAR
          │            │            │
       COUNT          SUM          AVG
          │            │            │
          └────────────┼────────────┘
                       ↓
                  ¿AGRUPAR?
                       │
              ┌────────┴────────┐
              ↓                 ↓
           SIMPLE            MÚLTIPLE
              │                 │
           región         región + sector
              │                 │
              └────────┬────────┘
                       ↓
                  ¿FILTRAR?
                       │
              ┌────────┴────────┐
              ↓                 ↓
            FILAS             GRUPOS
              │                 │
            WHERE            HAVING
                       │
                       ↓
                 ¿HAY MÁXIMO?
                       │
              ┌────────┴────────┐
              ↓                 ↓
            GLOBAL            POR GRUPO
```

### 12. Organización de la semana día por día

| Día Base | Día Ejecución | Tema Central |
|---|---|---|
| 6 oct. | **13 oct.** | Desarmar consignas lógicamente |
| 7 oct. | **14 oct.** | COUNT vs SUM vs AVG |
| 8 oct. | **15 oct.** | WHERE vs HAVING |
| 9 oct. | **16 oct.** | Máximo global vs máximo por grupo |
| 10 oct. | **17 oct.** | Agrupamientos simples y múltiples |
| 11 oct. | **18 oct.** | Integración de todos los conceptos |
| 12 oct. | **19 oct.** | Simulación + 20 consignas + entregable |

#### 🟦 DÍA 1 (13 de Octubre) — Aprender a leer una consigna como un algoritmo
* **Objetivo:** Dejar de mirar una consigna como una frase y empezar a verla como una estructura lógica.
* **Ejercicio mental guiado:** *"Determinar qué departamento posee la mayor cantidad de escuelas."*
  1. Paso 1: ¿Qué quiero obtener? $\rightarrow$ El departamento ganador.
  2. Paso 2: ¿Qué estoy comparando? $\rightarrow$ Cantidad de escuelas.
  3. Paso 3: ¿Entre qué unidades? $\rightarrow$ Departamentos.
  4. Paso 4: ¿Qué debo calcular? $\rightarrow$ Cantidad de escuelas por departamento.
  5. Paso 5: ¿Qué debo hacer después? $\rightarrow$ Buscar la cantidad máxima e identificar el departamento.
* **Ejercicios del día (5 consignas sin tocar SQL):**
  1. ¿Cuántas escuelas hay por localidad?
  2. ¿Qué localidad tiene más escuelas?
  3. ¿Cuántos alumnos tiene cada escuela?
  4. ¿Qué escuela tiene más alumnos?
  5. ¿Qué departamento tiene más escuelas?

#### 🟩 DÍA 2 (14 de Octubre) — COUNT vs SUM vs AVG
* **Objetivo:** Que nunca vuelvas a elegir una agregación solamente porque "suena bien".
* **5 consignas de entrenamiento:**
  1. Cantidad de escuelas por departamento.
  2. Total de alumnos por departamento.
  3. Promedio de alumnos por escuela.
  4. Mayor cantidad de alumnos de una escuela.
  5. Total de alumnos por región.

#### 🟨 DÍA 3 (15 de Octubre) — WHERE vs HAVING
* **Objetivo:** Distinguir filtrar datos brutos de filtrar resultados agrupados.
* **6 consignas de clasificación:**
  1. Escuelas de la región Norte.
  2. Departamentos con más de 100 escuelas.
  3. Escuelas con más de 500 alumnos.
  4. Localidades con más de 10 escuelas.
  5. Escuelas pertenecientes al sector privado.
  6. Sectores que tengan más de 50 escuelas.

#### 🟥 DÍA 4 (16 de Octubre) — Máximo global vs máximo por grupo
* **Objetivo:** Discernir si existe una única entidad ganadora en todo el universo o un podio por cada categoría.
* **6 ejercicios:**
  1. ¿Qué escuela tiene más alumnos? $\rightarrow$ GLOBAL
  2. ¿Qué escuela tiene más alumnos en cada localidad? $\rightarrow$ POR GRUPO
  3. ¿Qué departamento tiene más escuelas? $\rightarrow$ GLOBAL
  4. ¿Qué departamento tiene más escuelas dentro de cada región? $\rightarrow$ POR GRUPO
  5. ¿Cuál es la mayor cantidad de alumnos de todas las escuelas? $\rightarrow$ GLOBAL
  6. ¿Cuál es la mayor cantidad de alumnos dentro de cada departamento? $\rightarrow$ POR GRUPO

#### 🟪 DÍA 5 (17 de Octubre) — Agrupamiento simple vs múltiple
* **Objetivo:** Identificar la dimensionalidad requerida por la pregunta de negocio.
* **7 ejercicios:**
  1. Escuelas por departamento.
  2. Escuelas por región.
  3. Escuelas por región y sector.
  4. Escuelas por departamento y gestión.
  5. Escuelas por región, sector y ámbito.
  6. Alumnos por localidad.
  7. Alumnos por localidad y sector.

#### 🟧 DÍA 6 (18 de Octubre) — Integración: pensar como diseñador de consultas
* **Objetivo:** Combinar los bloques en problemas de mayor abstracción (máximos combinados y comparación contra promedios generales).

#### 🟫 DÍA 7 (19 de Octubre) — Simulación final y construcción de las 20 consignas
* **Objetivo:** Completar el documento formal de 20 consignas utilizando la plantilla oficial.

### 13. La plantilla oficial para las 20 consignas
```text
CONSIGNA: ________________________________________

1. ¿QUÉ QUIERO OBTENER? ________________________________________
2. ¿CUÁL ES LA ENTIDAD PRINCIPAL? ________________________________________
3. ¿QUÉ DATOS NECESITO? ________________________________________
4. ¿QUÉ SE AGRUPA? ________________________________________
5. ¿EL AGRUPAMIENTO ES SIMPLE O MÚLTIPLE? ________________________________________
6. ¿QUÉ MÉTRICA NECESITO? (COUNT / SUM / AVG / MAX / MIN) - ¿Por qué? ___________________
7. ¿HAY FILTRO? (SÍ / NO) - Si sí: ¿Filtro filas o grupos? _____________________________
8. ¿WHERE O HAVING? ________________________________________
9. ¿HAY UNA COMPARACIÓN? (SÍ / NO) - ¿Cuál? ________________________________________
10. ¿ES MÁXIMO GLOBAL O POR GRUPO? ________________________________________
11. ALGORITMO EN ESPAÑOL:
    1.
    2.
    3.
12. PSEUDOCÓDIGO:
    ...
```

---

## FASE 1: ALGORITMOS SQL CONSOLIDADOS
**Cronograma de Ejecución:** 20 de Octubre al 2 de Noviembre de 2026 *(Referencia de diseño original: 13 al 26 de Octubre)*

### 1. ¿Qué estamos entrenando realmente?
En la Fase 0 aprendiste a desarmar el problema.  
Ahora agregamos: **¿Cómo construyo esa operación correctamente en SQL?**  
Una consulta SQL no está correcta porque ejecuta sin errores; está correcta cuando su resultado representa exactamente el problema de negocio.

### 2. El problema que queremos eliminar
Evitar errores conceptuales como:
- `COUNT(ct_t_alum)` pensando que se están sumando alumnos, cuando en realidad se están contando registros.
- `GROUP BY sector` omitiendo la dimensión de región solicitada.
- Utilizar `ORDER BY ... DESC LIMIT 1` ocultando empates legítimos en máximos.

### 3. Las seis grandes habilidades de la fase
1. COUNT vs SUM fundamentado.
2. GROUP BY con dimensionalidad exacta.
3. WHERE vs HAVING en dos niveles de filtrado.
4. JOINs con análisis de cardinalidad (evitar productos cartesianos).
5. Máximos globales mediante agregación y subconsultas.
6. Máximos por grupo sin recurrir a LIMIT.

### 4. Progresión día por día

* **Día 1 (20 Oct): COUNT vs SUM: La diferencia fundamental**  
  Analizar si la fila representa un alumno o una escuela con matrícula agregada.
* **Día 2 (21 Oct): COUNT(*), COUNT(columna) y COUNT(DISTINCT)**  
  Comprender cómo los valores nulos y los JOINs afectan la cuenta total de registros.
* **Día 3 (22 Oct): GROUP BY: Una dimensión**  
  Mapear cómo el motor agrupa las tuplas en buckets dimensionales.
* **Día 4 (23 Oct): GROUP BY múltiple**  
  Crear granularidad multidimensional `(región, sector)`.
* **Día 5 (24 Oct): WHERE vs HAVING en consultas reales**  
  Filtrado previo de filas vs. filtrado posterior de grupos.
* **Día 6 (25 Oct): JOIN y el problema de multiplicar filas**  
  Análisis de cardinalidad $1:N$. Detectar cuándo una escuela se triplica al cruzar con cursos o alumnos.
* **Día 7 (26 Oct): Máximo global**  
  Separar el cálculo de la métrica de la identificación de la entidad con subconsultas escalares.
* **Día 8 (27 Oct): Máximos por grupo: Introducción**  
  Lógica de partición mental antes de abordar funciones de ventana.
* **Día 9 (28 Oct): Máximos por grupo con subconsultas**  
  Subconsultas correlacionadas y CTEs clásicas para obtener el máximo por categoría.
* **Día 10 (29 Oct): Máximos por grupo sin LIMIT**  
  Manejo estricto de empates utilizando subconsultas:
  ```sql
  WHERE (departamento, cantidad) IN (
      SELECT departamento, MAX(cantidad) ...
  )
  ```
* **Día 11 (30 Oct): Validación matemática de consultas**  
  Auditar que la sumatoria de las partes coincida con el total poblacional y comparar `COUNT(*)` vs `COUNT(DISTINCT CUE)`.
* **Día 12 - 14 (31 Oct - 2 Nov): Auditoría y Entrega**  
  Revisión de scripts anteriores y construcción de `/01_sql_clasico/`.

---

## FASE 2: WINDOW FUNCTIONS PASO A PASO
**Cronograma de Ejecución:** 3 al 16 de Noviembre de 2026 *(Referencia de diseño original: 27 de Octubre al 9 de Noviembre)*

### 1. La idea filosófica: "Analizar sin colapsar"
`GROUP BY` resume y compacta múltiples filas en una sola.  
Las **Window Functions** agregan o calculan métricas de contexto manteniendo intacto el detalle de cada fila individual.

```text
Santa Fe:
Escuela A (800 alum)  ──>  Posición 1  ──>  50% del dpto
Escuela B (500 alum)  ──>  Posición 2  ──>  31.25% del dpto
Escuela C (300 alum)  ──>  Posición 3  ──>  18.75% del dpto
```

### 2. Progresión de funciones atómicas

* **Día 1 (3 Nov): ROW_NUMBER() por localidad**  
  Numeración estricta secuencial: `ROW_NUMBER() OVER (PARTITION BY localidad ORDER BY ct_t_alum DESC)`.
* **Día 2 (4 Nov): ROW_NUMBER() por departamento**  
  Comprensión del desacople: cambiar la dimensión de particionamiento sin alterar la lógica.
* **Día 3 (5 Nov): RANK() y el manejo de empates**  
  Asignación de puestos compartidos con saltos: si dos empatan en el 2°, el siguiente es el 4°.
* **Día 4 (6 Nov): DENSE_RANK() para rankings continuos**  
  Rankings densos sin huecos numéricos: si dos empatan en el 2°, el siguiente es el 3°.
* **Día 5 (7 Nov): LAG() para series temporales**  
  Mirar la fila previa para calcular variaciones interanuales de matrícula.
* **Día 6 (8 Nov): LEAD() para comparaciones futuras**  
  Mirar la fila siguiente dentro de la partición.
* **Día 7 (9 Nov): SUM() OVER()**  
  Cálculo de pesos porcentuales individuales sobre el total departamental sin necesidad de subconsultas adicionales:
  ```sql
  ROUND(100.0 * ct_t_alum / SUM(ct_t_alum) OVER (PARTITION BY departamento), 2) AS pct_departamento
  ```
* **Día 8 (10 Nov): AVG() OVER()**  
  Comparación de cada escuela frente a la media de su localidad o provincia.
* **Día 9 - 14 (11 - 16 Nov): Integración, Comparativas y Entregable**  
  Construcción de `/02_window_functions/` con comparativas lado a lado: *Solución Clásica vs. Solución Window Function*.

---

## FASE 3: ENTORNO POSTGRESQL (DEL SQL ABSTRACTO AL SISTEMA RELACIONAL REAL)
**Cronograma de Ejecución:** 17 al 23 de Noviembre de 2026 *(Referencia de diseño original: 10 al 16 de Noviembre)*

### 1. La idea filosófica
Una base de datos no es un contenedor de archivos; es un sistema de reglas que modela un dominio real y garantiza su integridad.

### 2. Progresión día por día

* **Día 1 (17 Nov): Arquitectura del Motor PostgreSQL**  
  Cliente-servidor, psql, pgAdmin, estructura de `Cluster -> Database -> Schema -> Tables`.
* **Día 2 (18 Nov): Tipos de Datos y Semántica**  
  `INT`, `BIGINT`, `VARCHAR(n)`, `BOOLEAN`, `DATE`, `NUMERIC(p, s)`. Por qué un DNI o CUE no debe ser entero si actúa como identificador.
* **Día 3 (19 Nov): PRIMARY KEY e Identidad**  
  Unicidad y restricción estricta de no nulidad (`NOT NULL + UNIQUE`). Diferencia entre atributo e identidad.
* **Día 4 (20 Nov): FOREIGN KEY e Integridad Referencial**  
  Contratos entre tablas: `REFERENCES`, acciones en cascada (`ON DELETE RESTRICT / CASCADE`).
* **Día 5 (21 Nov): Construcción del Esquema Completo**  
  Definición del orden estricto de creación según árbol de dependencias (`localidad -> escuela -> matricula`).
* **Día 6 (22 Nov): Pruebas de Estrés e Inserción de Datos Inválidos**  
  Validar que el motor rechace referencias huérfanas, valores nulos prohibidos y registros duplicados.
* **Día 7 (23 Nov): Entregable Reproducible**  
  Creación y prueba limpia de `01_esquema_inicial.sql`.

---

## FASE 4: CAPA DE VISTAS (ABSTRACCIÓN Y CONSUMO)
**Cronograma de Ejecución:** 24 al 30 de Noviembre de 2026 *(Referencia de diseño original: 17 al 23 de Noviembre)*

### 1. Objetivo y Criterio Profesional
Encapsular consultas analíticas complejas para consumo de terceros (aplicaciones, analistas de BI, usuarios de negocio) sin exponer la complejidad interna de los JOINs ni comprometer la seguridad.

### 2. Desarrollo Técnico y Entregables
* **Vistas Simples y Compuestas:** Creación de `v_escuelas_completa` integrando escuela, localidad, departamento y región.
* **Vistas Agregadas:** Resúmenes estadísticos predefinidos por región y departamento.
* **CTEs dentro de Vistas:** Modularización interna de cálculos complejos.
* **Vistas Materializadas (Criterio DBA):** Introducción a `CREATE MATERIALIZED VIEW` y `REFRESH MATERIALIZED VIEW` para reportes pesados.
* **Entregable:** Script `/03_vistas/02_vistas.sql`.

---

## FASE 5: OPTIMIZACIÓN REAL CON EXPLAIN ANALYZE E ÍNDICES
**Cronograma de Ejecución:** 1 al 10 de Diciembre de 2026 *(Referencia de diseño original: 24 de Noviembre al 7 de Diciembre)*

### 1. Objetivo y Criterio Profesional
Diagnosticar y mejorar la velocidad de lectura con criterios técnicos medibles, entendiendo el funcionamiento del planificador de costos de PostgreSQL.

### 2. Advertencia Técnica Crítica de Datos
> [!IMPORTANT]
> Si la tabla contiene pocos registros (ej. 2.000 filas), PostgreSQL preferirá realizar un **Sequential Scan (`Seq Scan`)** porque leer las páginas secuenciales en memoria es más económico que recorrer el árbol B-Tree.  
> **Acción requerida:** Utilizar `generate_series()` para poblar las tablas con al menos **150.000 a 300.000 filas de prueba** de modo que el optimizador refleje el comportamiento real de producción.

### 3. Metodología de Trabajo
1. Ejecutar `EXPLAIN (ANALYZE, BUFFERS)` sobre consultas pesadas.
2. Registrar métricas base: `Seq Scan`, costo estimado (`cost=...`), tiempo de ejecución real (`Execution Time`) y buffers leídos.
3. Diseñar y aplicar índices B-Tree específicos (simples o compuestos segun filtros frecuentes).
4. Volver a ejecutar `EXPLAIN (ANALYZE, BUFFERS)` y comprobar la transición a `Index Scan` o `Bitmap Index Scan`.
5. **Entregable:** Documento `/04_optimizacion/04_optimizacion.md` con comparativas antes y después.

---

## FASE 6: ROLES Y PERMISOS (SEGURIDAD Y GOBERNANZA)
**Cronograma de Ejecución:** 11 al 14 de Diciembre de 2026 *(Referencia de diseño original: 8 al 10 de Diciembre)*

### 1. Objetivo y Criterio Profesional
Implementar el principio de menor privilegio (*Least Privilege*) y control de acceso basado en roles (RBAC).

### 2. Implementación
* Creación de roles de grupo: `rol_analista`, `rol_operador`, `rol_admin`.
* Creación de usuarios individuales asignados a grupos (`CREATE USER ... IN ROLE ...`).
* Asignación granular de permisos:
  - Solo lectura sobre vistas: `GRANT SELECT ON v_escuelas_completa TO rol_analista;`
  - Restricción sobre tablas base: `REVOKE ALL ON ALL TABLES IN SCHEMA public FROM rol_analista;`
* **Entregable:** Script `/05_roles_backups/05_seguridad_roles.sql`.

---

## FASE 7: MANTENIMIENTO, BACKUPS Y RECUPERACIÓN ANTE DESASTRES
**Cronograma de Ejecución:** 15 al 20 de Diciembre de 2026 *(Referencia de diseño original: 11 al 13 de Diciembre)*

### 1. Objetivo y Criterio Profesional
Garantizar la continuidad operativa, integridad física y capacidad de restauración ante fallos críticos.

### 2. Implementación
* Respaldos lógicos con `pg_dump`:
  - Formato plano (.sql) y formato custom comprimido (`-Fc`).
* Simulación de desastre: eliminación accidental de tablas críticas (`DROP TABLE`).
* Restauración completa con `pg_restore` y `psql`.
* Tareas de mantenimiento proactivo: `VACUUM ANALYZE` para actualización de estadísticas y recolección de espacio libre.
* **Entregable:** Archivo de copia `backup_sistema_educativo.dump` y reporte de restauración exitosa documentado en Markdown.

---

## RUTAS COMPLEMENTARIAS

### FASE 8: Analítica con Power BI (Extensión Diciembre)
* **Objetivo:** Conectar el esquema relacional optimizado de PostgreSQL y diseñar un modelo dimensional en estrella (*Star Schema*).
* **Entregables:**
  - Tabla de Hechos: Matrícula y Rendimiento.
  - Dimensiones: Dim_Escuela, Dim_Geografia, Dim_Tiempo.
  - Medidas DAX clave y dashboard ejecutivo exportado a `dashboard.pdf`.

### FASE 9: Persistencia NoSQL con MongoDB (Enero)
* **Objetivo:** Analizar las diferencias entre el modelo relacional normalizado y el modelo orientado a documentos BSON.
* **Entregables:** Repositorio `/06_nosql_mongodb` con scripts de migración, consultas con `find()` y pipelines de agregación equivalentes con `$group`, `$project` y `$lookup`.

---

## Estructura Final del Portfolio en GitHub

```text
sistema-educativo-santa-fe/
├── README.md                 # Documentación técnica, arquitectura y análisis con EXPLAIN ANALYZE
├── /01_sql_clasico           # Consultas de agregación, JOINs y subconsultas originales
├── /02_window_functions      # Comparativa de solución clásica vs Window Functions
├── /03_vistas                # Scripts DDL de vistas analíticas
├── /04_optimizacion          # Archivo optimizacion.md con planes de ejecución ANTES / DESPUÉS
├── /05_roles_backups         # Scripts de seguridad, asignación de roles y comandos pg_dump
├── /06_nosql_mongodb         # Agregaciones y colecciones en MongoDB
└── dashboard.pdf             # Visualización del modelo en Power BI
```

### Texto modelo para el README técnico:
> *"Se analizó la consulta mediante EXPLAIN ANALYZE, identificando una búsqueda secuencial (Seq Scan). Se procedió a la creación de un índice compuesto sobre las columnas (departamento, localidad), logrando modificar el plan de ejecución a Index Scan y reduciendo el costo estimado de la consulta de X a Y y el tiempo de ejecución en un Z%."*
