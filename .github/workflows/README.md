# .github/workflows/

Carpeta de **pipelines CI/CD** de GitHub Actions.

## Estado actual

Los workflows se implementaran en la **Fase 8** del roadmap (semanas 29-32).

## Workflows planificados

```
.github/workflows/
|-- ci.yml          <- Pipeline de integracion continua (tests + lint)
|-- cd-staging.yml  <- Deploy automatico a staging
`-- cd-prod.yml     <- Deploy a produccion (manual, con aprobacion)
```

## ci.yml (a implementar en Fase 8)

Se ejecutara en cada Pull Request:
1. Instalar dependencias
2. Lint de todos los workspaces
3. Build del paquete shared
4. Tests del backend
5. Build del frontend

## cd-staging.yml (a implementar en Fase 9)

Se ejecutara al mergear a `main`:
1. Ejecutar CI completo
2. Build de produccion
3. Deploy a servidor de staging
4. Smoke tests

## Variables de entorno de GitHub Actions (a configurar)

Ir a: GitHub repo -> Settings -> Secrets and variables -> Actions

| Secret | Descripcion |
|---|---|
| `DATABASE_URL` | URL de la BD de staging/prod |
| `JWT_SECRET` | Clave secreta para JWT |
| `REDIS_URL` | URL de Redis |
| `DEPLOY_KEY` | Clave SSH para el servidor |
