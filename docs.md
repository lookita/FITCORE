# ObsidiantFIT — Documentacion Tecnica Completa

---

## 1. Introduccion

**ObsidiantFIT** es un ecosistema digital de alto rendimiento para la gestion del fitness, disenado bajo un modelo de Software como Servicio (SaaS). Su funcion principal no es solo registrar ejercicios, sino sistematizar toda la experiencia de un atleta o usuario de gimnasio mediante una arquitectura robusta y escalable.

Aqui tienes los pilares que definen su funcion:

### 1.1 El Cerebro: Gestion Inteligente de Entrenamiento

La funcion central es el **Tablero Kanban de Entrenamiento**. A diferencia de una simple lista, este sistema permite:

- **Flujo Dinamico:** Mover rutinas y ejercicios a traves de estados (Pendiente, En Ejecucion, Completado).
- **Validacion de Progreso:** No solo anota que hiciste el ejercicio, sino que procesa la logica de **Gamificacion** (otorgando XP o puntos de experiencia) para incentivar la constancia.

### 1.2 El Motor: Administracion de un Negocio SaaS

El programa funciona como una plataforma empresarial completa:

- **Gestion de Usuarios y Roles:** Diferencia entre administradores, entrenadores y clientes.
- **Control de Suscripciones y Pagos:** Automatiza el acceso al contenido segun el estado del pago del usuario, manejando la persistencia de datos financieros de forma segura.

### 1.3 La Infraestructura: Escalabilidad y Resiliencia

A diferencia de una aplicacion sencilla, la funcion de ObsidiantFIT es demostrar que puede soportar miles de usuarios simultaneos:

- **Microservicios:** Cada parte (pagos, rutinas, usuarios) funciona de forma independiente para que, si una falla, el resto siga activo.
- **Sincronizacion en Tiempo Real:** Utiliza herramientas como **Kafka** y **Redis** para que la informacion (como un cambio en el tablero Kanban) se refleje al instante sin recargar la pagina.

### 1.4 Proposito Final: Gamificacion del Esfuerzo

El programa trata de convertir la disciplina fisica en un sistema de progresion visual. Utiliza una estetica **"Neo-Dark Premium"** (vidrios, sombras profundas y contrastes) para que el usuario sienta que esta dentro de un videojuego de productividad, donde cada rutina completada es un nivel superado en su desarrollo personal.

---

## 2. Stack Tecnologico

| Capa | Tecnologia | Version |
|---|---|---|
| Backend Framework | NestJS | ^11.0.1 |
| Backend Lenguaje | TypeScript | ^5.9.3 |
| Backend Runtime | Node.js | >=20.0.0 |
| Frontend Framework | React | ^19.2.5 |
| Frontend Bundler | Vite | ^8.0.9 |
| Frontend Lenguaje | TypeScript | ~6.0.2 |
| Test Runner | Jest + ts-jest | ^30.0.0 |
| Linting | ESLint + typescript-eslint | ^9.x |
| Formateo | Prettier | ^3.4.2 |
| Gestion de monorepo | npm Workspaces | npm >=10.0.0 |

---

## 3. Arquitectura del Monorepo

ObsidiantFIT usa una arquitectura **monorepo con npm Workspaces**. Esto significa que todo el codigo vive en un unico repositorio git, organizado en paquetes independientes que pueden compartir codigo entre si.

### 3.1 Estructura de Carpetas

```
ObsidiantFit/                    <- Raiz del monorepo
|
|-- .git/                        <- Repositorio git unificado
|-- .gitignore                   <- Gitignore global del monorepo
|-- package.json                 <- Workspaces raiz + scripts globales
|-- package-lock.json            <- Lockfile unificado de dependencias
|-- node_modules/                <- Dependencias hoisteadas (compartidas)
|-- README.md                    <- Documentacion de inicio rapido
|-- docs.md                      <- Este archivo (documentacion completa)
|-- setup-monorepo.ps1           <- Script de configuracion inicial
|
|-- Back/                        <- @obsidiantfit/backend
|   |-- src/
|   |   |-- main.ts              <- Punto de entrada de NestJS
|   |   |-- app.module.ts        <- Modulo raiz de la aplicacion
|   |   |-- app.controller.ts   <- Controlador HTTP principal
|   |   |-- app.controller.spec.ts  <- Test unitario del controlador
|   |   |-- app.service.ts       <- Servicio de la app (logica de negocio)
|   |   `-- logica.ts            <- Script standalone: roles y acceso
|   |-- test/                    <- Tests end-to-end
|   |-- nest-cli.json            <- Configuracion del CLI de NestJS
|   |-- package.json             <- Dependencias del backend
|   |-- tsconfig.json            <- TypeScript config del backend
|   |-- tsconfig.build.json      <- TypeScript config para produccion
|   |-- .gitignore               <- Gitignore heredado (ya cubierto por raiz)
|   `-- .prettierrc              <- Configuracion de formato de codigo
|
|-- frontend/                    <- @obsidiantfit/frontend
|   |-- src/
|   |   |-- main.tsx             <- Punto de entrada de React
|   |   |-- App.tsx              <- Componente raiz de la aplicacion
|   |   |-- App.css              <- Estilos del componente App
|   |   |-- index.css            <- Variables CSS globales y reset
|   |   `-- assets/              <- Imagenes y recursos estaticos
|   |       |-- react.svg
|   |       |-- vite.svg
|   |       `-- hero.png
|   |-- public/                  <- Archivos publicos (no procesados por Vite)
|   |-- index.html               <- HTML de entrada de la SPA
|   |-- vite.config.ts           <- Configuracion de Vite
|   |-- package.json             <- Dependencias del frontend
|   |-- tsconfig.json            <- TypeScript config raiz (referencias)
|   |-- tsconfig.app.json        <- TypeScript config para el codigo React
|   `-- tsconfig.node.json       <- TypeScript config para vite.config.ts
|
`-- packages/                    <- Paquetes compartidos del monorepo
    `-- shared/                  <- @obsidiantfit/shared
        |-- src/
        |   |-- index.ts         <- Barrel export (punto de entrada)
        |   `-- roles.ts         <- Tipos Rol, EstadoSub y validarAcceso()
        |-- dist/                <- Codigo compilado (generado, no editar)
        |-- package.json         <- Configuracion del paquete shared
        `-- tsconfig.json        <- TypeScript config del paquete shared
```

---

## 4. Paquetes del Monorepo

### 4.1 @obsidiantfit/backend (`Back/`)

API REST construida con **NestJS**, el framework de Node.js orientado a modulos con inyeccion de dependencias.

#### Archivos principales

| Archivo | Proposito |
|---|---|
| `src/main.ts` | Arranca el servidor NestJS. Puerto por defecto: `3000` (configurable via `PORT`) |
| `src/app.module.ts` | Modulo raiz. Registra controladores y servicios |
| `src/app.controller.ts` | Expone `GET /` → retorna `"Hello World!"` |
| `src/app.service.ts` | Servicio inyectable. Contiene la logica del controlador |
| `src/logica.ts` | Script standalone con los tipos de roles y la funcion de acceso |
| `src/app.controller.spec.ts` | Test unitario: verifica que `GET /` retorna el string correcto |

#### API actual

| Metodo | Ruta | Respuesta |
|---|---|---|
| `GET` | `/` | `"Hello World!"` |

#### Scripts disponibles

| Script | Comando |
|---|---|
| `npm run start:dev` | NestJS con hot-reload (desarrollo) |
| `npm run start:prod` | Ejecutar build de produccion |
| `npm run build` | Compilar a `/dist` |
| `npm run test` | Ejecutar tests unitarios |
| `npm run test:cov` | Tests con reporte de cobertura |
| `npm run test:e2e` | Tests end-to-end |
| `npm run lint` | Linting y autofix |
| `npm run logica` | Ejecutar `logica.ts` como script standalone |

#### Dependencias de produccion

| Paquete | Version | Funcion |
|---|---|---|
| `@nestjs/common` | ^11.0.1 | Decoradores, pipes, guards, etc. |
| `@nestjs/core` | ^11.0.1 | Nucleo del framework NestJS |
| `@nestjs/platform-express` | ^11.0.1 | Adaptador HTTP con Express |
| `@obsidiantfit/shared` | * | Tipos compartidos del monorepo |
| `reflect-metadata` | ^0.2.2 | Requerido por los decoradores de NestJS |
| `rxjs` | ^7.8.1 | Programacion reactiva (usado internamente por NestJS) |

#### Dependencias de desarrollo

| Paquete | Version | Funcion |
|---|---|---|
| `@nestjs/cli` | ^11.0.0 | CLI para generar modulos, servicios, etc. |
| `@nestjs/schematics` | ^11.0.0 | Generadores de codigo del CLI |
| `@nestjs/testing` | ^11.0.1 | Utilidades para tests unitarios |
| `typescript` | ^5.9.3 | Compilador de TypeScript |
| `ts-node` | ^10.9.2 | Ejecucion directa de TypeScript |
| `ts-jest` | ^29.2.5 | Adaptador de Jest para TypeScript |
| `jest` | ^30.0.0 | Framework de testing |
| `prettier` | ^3.4.2 | Formateador de codigo |
| `eslint` | ^9.39.4 | Linter de codigo |
| `tsconfig-paths` | ^4.2.0 | Soporte de alias de paths en TS |

#### Configuracion TypeScript (`tsconfig.json`)

| Opcion | Valor | Motivo |
|---|---|---|
| `module` | `nodenext` | Compatibilidad con ES Modules en Node |
| `target` | `ES2023` | Features modernas de JavaScript |
| `emitDecoratorMetadata` | `true` | **Requerido por NestJS** |
| `experimentalDecorators` | `true` | **Requerido por NestJS** |
| `strictNullChecks` | `true` | Seguridad de tipos en nulls |
| `noImplicitAny` | `false` | Permisivo con `any` (desarrollo inicial) |
| `outDir` | `./dist` | Carpeta de salida del compilador |
| `incremental` | `true` | Compilaciones incrementales mas rapidas |

---

### 4.2 @obsidiantfit/frontend (`frontend/`)

SPA (Single Page Application) construida con **React 19** y **Vite 8**.

#### Archivos principales

| Archivo | Proposito |
|---|---|
| `src/main.tsx` | Monta `<App />` en el elemento `#root` del HTML |
| `src/App.tsx` | Componente raiz (actualmente: pantalla de inicio por defecto) |
| `src/index.css` | Variables CSS globales, modo oscuro, tipografia, layout |
| `src/App.css` | Estilos especificos del componente App |
| `index.html` | HTML de entrada de la SPA |
| `vite.config.ts` | Configuracion de Vite (plugin React activo) |

#### Sistema de diseno (`index.css`)

Variables CSS disponibles en toda la aplicacion:

| Variable | Modo claro | Modo oscuro | Uso |
|---|---|---|---|
| `--text` | `#6b6375` | `#9ca3af` | Texto general |
| `--text-h` | `#08060d` | `#f3f4f6` | Headings |
| `--bg` | `#ffffff` | `#16171d` | Fondo de pagina |
| `--border` | `#e5e4e7` | `#2e303a` | Bordes |
| `--accent` | `#aa3bff` | `#c084fc` | Color principal (morado) |
| `--accent-bg` | `rgba(170,59,255,0.1)` | `rgba(192,132,252,0.15)` | Fondo de elementos accent |

> El color de acento morado coincide con la identidad visual "Obsidian" del proyecto.

#### Scripts disponibles

| Script | Comando |
|---|---|
| `npm run dev` | Servidor de desarrollo con HMR en `http://localhost:5173` |
| `npm run build` | Build de produccion (`tsc -b && vite build`) |
| `npm run preview` | Preview del build de produccion |
| `npm run lint` | Linting del codigo |

#### Dependencias de produccion

| Paquete | Version | Funcion |
|---|---|---|
| `@obsidiantfit/shared` | * | Tipos compartidos del monorepo |
| `react` | ^19.2.5 | Libreria de UI |
| `react-dom` | ^19.2.5 | Renderizado de React en el DOM |

#### Dependencias de desarrollo

| Paquete | Version | Funcion |
|---|---|---|
| `vite` | ^8.0.9 | Bundler y servidor de desarrollo |
| `@vitejs/plugin-react` | ^6.0.1 | Soporte React en Vite (Babel + HMR) |
| `typescript` | ~6.0.2 | Compilador de TypeScript |
| `eslint` | ^9.39.4 | Linter de codigo |
| `eslint-plugin-react-hooks` | ^7.1.1 | Reglas de linting para Hooks de React |
| `eslint-plugin-react-refresh` | ^0.5.2 | Compatibilidad con HMR de Vite |

---

### 4.3 @obsidiantfit/shared (`packages/shared/`)

Paquete de **tipos, interfaces y logica compartida** entre el backend y el frontend. Es la fuente unica de verdad para los contratos de datos del sistema.

#### Archivos principales

| Archivo | Proposito |
|---|---|
| `src/roles.ts` | Tipos `Rol`, `EstadoSub`, funcion `validarAcceso()` y constantes |
| `src/index.ts` | Barrel export: re-exporta todo lo de `roles.ts` |
| `dist/` | Codigo JavaScript compilado (generado por `tsc`) |

#### Tipos exportados

```typescript
// Roles del sistema
type Rol = 'user' | 'trainer' | 'admin';

// Estados de suscripcion
type EstadoSub = 'activa' | 'expirada' | 'trial' | 'cancelada';

// Constantes
const ROLES: Rol[] = ['user', 'trainer', 'admin'];
const ESTADOS_SUB: EstadoSub[] = ['activa', 'expirada', 'trial', 'cancelada'];
const ESTADOS_BLOQUEANTES: EstadoSub[] = ['expirada', 'cancelada'];
```

#### Logica de acceso exportada

```typescript
function validarAcceso(rol: Rol, estado: EstadoSub): string
```

| Rol | Estado | Resultado |
|---|---|---|
| cualquiera | `expirada` o `cancelada` | "Suscripcion inactiva -> ir a /subscription" |
| `admin` | `activa` o `trial` | "Acceso total al sistema" |
| `trainer` | `activa` o `trial` | "Acceso a rutinas y usuarios asignados" |
| `user` | `activa` | "Acceso premium completo" |
| `user` | `trial` | "Trial activo - convertir a pago" |

#### Como importar el paquete shared

Desde el backend (`Back/`):
```typescript
import { Rol, EstadoSub, validarAcceso } from '@obsidiantfit/shared';
```

Desde el frontend (`frontend/`):
```typescript
import { Rol, EstadoSub } from '@obsidiantfit/shared';
```

> **Importante:** Cada vez que se modifique `packages/shared/src/`, ejecutar
> `npm run build:shared` desde la raiz para regenerar el `dist/`.

#### Dependencias de desarrollo

| Paquete | Version | Funcion |
|---|---|---|
| `typescript` | ^5.9.3 | Compilador de TypeScript |

#### Configuracion TypeScript (`tsconfig.json`)

| Opcion | Valor | Motivo |
|---|---|---|
| `target` | `ES2020` | Compatibilidad amplia |
| `module` | `CommonJS` | Compatible con NestJS |
| `declaration` | `true` | Genera `.d.ts` para los tipos |
| `declarationMap` | `true` | Source maps de los tipos |
| `outDir` | `./dist` | Carpeta de salida |
| `strict` | `true` | Maxima seguridad de tipos |

---

## 5. Configuracion del Monorepo Raiz

### 5.1 `package.json` raiz

```json
{
  "name": "obsidiantfit",
  "workspaces": ["Back", "frontend", "packages/*"],
  "engines": { "node": ">=20.0.0", "npm": ">=10.0.0" }
}
```

Los **workspaces** le dicen a npm que:
- `Back/`, `frontend/` y `packages/*` son paquetes independientes
- Las dependencias se hoistean a `node_modules/` en la raiz (se comparten)
- Se pueden ejecutar scripts en todos los workspaces desde la raiz

### 5.2 Scripts globales disponibles desde la raiz

| Script | Descripcion |
|---|---|
| `npm run dev:backend` | Arranca NestJS en modo desarrollo (puerto 3000) |
| `npm run dev:frontend` | Arranca React + Vite en modo desarrollo (puerto 5173) |
| `npm run build` | Build completo: shared -> backend -> frontend |
| `npm run build:shared` | Solo compila el paquete shared |
| `npm run build:backend` | Solo compila el backend |
| `npm run build:frontend` | Solo compila el frontend |
| `npm run test` | Ejecuta los tests del backend |
| `npm run lint` | Linting de todos los workspaces |
| `npm run format` | Formatea el codigo del backend |

### 5.3 `node_modules/` hoisteado

Con npm workspaces, las dependencias comunes se instalan **una sola vez** en la raiz en lugar de duplicarse en cada paquete. Esto significa:

- Menos espacio en disco
- Una sola version de TypeScript, ESLint, etc. para todo el proyecto
- `npm install` desde la raiz instala TODO el monorepo de una sola vez

---

## 6. Relacion entre Paquetes

```
@obsidiantfit/backend
        |
        +-- importa --> @obsidiantfit/shared
        
@obsidiantfit/frontend
        |
        +-- importa --> @obsidiantfit/shared

@obsidiantfit/shared
        |
        +-- no tiene dependencias internas
```

El paquete `shared` es la **base de la piramide**: no depende de nada del monorepo, solo de TypeScript. Tanto el backend como el frontend pueden importar sus tipos y funciones.

---

## 7. Estado Actual del Proyecto

| Componente | Estado | Notas |
|---|---|---|
| Monorepo configurado | Listo | npm Workspaces, .git en raiz |
| @obsidiantfit/shared | Listo | Tipos y logica compilados |
| NestJS boilerplate | Listo | Arrancar con `npm run dev:backend` |
| React + Vite boilerplate | Listo | Arrancar con `npm run dev:frontend` |
| Logica de roles/suscripciones | Definida | En `shared/src/roles.ts` |
| Tablero Kanban | Pendiente | A implementar en frontend |
| Gamificacion (XP) | Pendiente | A implementar en backend |
| Base de datos | Pendiente | Sin ORM ni BD configurada |
| Autenticacion | Pendiente | Sin JWT ni guards |
| API real de negocio | Pendiente | Solo `GET /` disponible |
| Gestion de suscripciones | Pendiente | Logica de tipos definida |
| Microservicios | Pendiente | Fase avanzada del roadmap |
| Kafka / Redis | Pendiente | Fase avanzada del roadmap |
| Variables de entorno | Pendiente | Sin `.env.example` todavia |
| CORS configurado | Pendiente | Backend sin CORS |
| Proxy Vite -> NestJS | Pendiente | Sin configurar en `vite.config.ts` |

---

## 8. Como Configurar el Proyecto desde Cero

```powershell
# 1. Clonar el repositorio
git clone <url-del-repo>
cd ObsidiantFit

# 2. Instalar todas las dependencias del monorepo
npm install

# 3. Compilar el paquete shared (siempre primero)
npm run build:shared

# 4. Arrancar en desarrollo
npm run dev:backend    # Terminal 1: NestJS en http://localhost:3000
npm run dev:frontend   # Terminal 2: React en http://localhost:5173
```

---

*Documentacion generada el 06/05/2026 — Version 1.0*
