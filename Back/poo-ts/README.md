# poo-ts/

Carpeta de ejercicios de **Fase 2** del roadmap: Programacion Orientada a Objetos en TypeScript.

## Contenido esperado

Ejercicios de POO aplicados al dominio del SaaS de fitness:

- `01-clases-basicas.ts` — clases, constructores, metodos
- `02-herencia.ts` — extends, super, override
- `03-interfaces-poo.ts` — implements, contratos
- `04-abstractas.ts` — clases abstractas, metodos abstractos
- `05-encapsulamiento.ts` — public, private, protected, readonly
- `06-patrones.ts` — Singleton, Factory, Repository (introduccion)
- `07-usuario.ts` — modelar la entidad Usuario con todos los conceptos
- `08-suscripcion.ts` — modelar Suscripcion, herencia de planes

## Como ejecutar un ejercicio

```bash
cd Back/poo-ts
npx ts-node nombre-del-archivo.ts
```

## Relacion con el proyecto real

Los patrones aprendidos aqui se aplicaran directamente en los modulos de NestJS:
- `Usuario` -> modulo `users/`
- `Suscripcion` -> modulo `subscriptions/`
- `Rutina` -> modulo `routines/`
