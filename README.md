# ObsidiantFit — Monorepo

SaaS de fitness construido con **NestJS** (backend) + **React + Vite** (frontend) en una arquitectura **monorepo** con npm workspaces.

## 📁 Estructura

```
ObsidiantFit/
├── package.json            ← Raíz del monorepo (npm workspaces)
├── .gitignore
├── setup-monorepo.ps1      ← Script de configuración inicial
│
├── Back/                   ← @obsidiantfit/backend (NestJS)
│   └── src/
│       ├── main.ts
│       ├── app.module.ts
│       ├── app.controller.ts
│       ├── app.service.ts
│       └── logica.ts
│
├── frontend/               ← @obsidiantfit/frontend (React + Vite)
│   └── src/
│       ├── main.tsx
│       └── App.tsx
│
└── packages/
    └── shared/             ← @obsidiantfit/shared (tipos compartidos)
        └── src/
            ├── index.ts
            └── roles.ts
```

## 🚀 Inicio rápido

### Primera vez (configuración del monorepo)

```powershell
# Desde la carpeta ObsidiantFit/
.\setup-monorepo.ps1
```

Esto mueve el `.git` a la raíz, limpia `node_modules` y corre `npm install`.

### Desarrollo diario

```bash
# Desde la raíz del monorepo:

# Arrancar backend (NestJS con hot-reload, puerto 3000)
npm run dev:backend

# Arrancar frontend (Vite con HMR)
npm run dev:frontend
```

## 📦 Paquetes

| Nombre | Directorio | Descripción |
|---|---|---|
| `@obsidiantfit/backend` | `Back/` | API REST con NestJS |
| `@obsidiantfit/frontend` | `frontend/` | SPA con React 19 + Vite |
| `@obsidiantfit/shared` | `packages/shared/` | Tipos y lógica compartida |

## 🔧 Comandos

| Comando | Descripción |
|---|---|
| `npm run dev:backend` | Backend en modo desarrollo |
| `npm run dev:frontend` | Frontend en modo desarrollo |
| `npm run build` | Build completo (shared → backend → frontend) |
| `npm run build:shared` | Compilar solo el paquete shared |
| `npm run test` | Tests del backend |
| `npm run lint` | Lint de todos los workspaces |

## 🗂️ Paquete Shared

El paquete `@obsidiantfit/shared` contiene los tipos y la lógica de negocio que se comparte entre el backend y el frontend.

```typescript
import { Rol, EstadoSub, validarAcceso } from '@obsidiantfit/shared';

// Tipos disponibles:
type Rol = 'user' | 'trainer' | 'admin';
type EstadoSub = 'activa' | 'expirada' | 'trial' | 'cancelada';

// Función de control de acceso:
validarAcceso('user', 'activa');      // ✅ Acceso premium completo
validarAcceso('trainer', 'expirada'); // 🔒 Suscripción inactiva
```

> **Nota:** Cada vez que se modifique `packages/shared/`, ejecutar `npm run build:shared` para regenerar los tipos compilados antes de usar el paquete en backend o frontend.

## ⚙️ Stack

- **Backend:** NestJS 11, TypeScript 5.9, Jest
- **Frontend:** React 19, Vite 8, TypeScript 6
- **Shared:** TypeScript 5.9
- **Gestión de monorepo:** npm Workspaces (Node ≥ 20, npm ≥ 10)
