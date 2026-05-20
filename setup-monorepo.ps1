# ObsidiantFit - Monorepo Setup Script
# Ejecutar desde: ObsidiantFit/
# Uso: .\setup-monorepo.ps1

Write-Host "Configurando monorepo ObsidiantFit..." -ForegroundColor Cyan

# 1. Verificar que estamos en la raiz correcta
if (-not (Test-Path "package.json") -or -not (Test-Path "Back") -or -not (Test-Path "frontend")) {
    Write-Error "Ejecuta este script desde la carpeta raiz ObsidiantFit/"
    exit 1
}

# 2. Mover .git de Back/ a la raiz del monorepo
if (Test-Path "Back\.git") {
    if (Test-Path ".git") {
        Write-Host "Ya existe un .git en la raiz. Saltando migracion de git." -ForegroundColor Yellow
    } else {
        Write-Host "Moviendo repositorio git a la raiz del monorepo..." -ForegroundColor Yellow
        Move-Item -Path "Back\.git" -Destination ".git"
        Write-Host "OK: .git movido correctamente." -ForegroundColor Green
    }
} elseif (Test-Path ".git") {
    Write-Host "OK: El .git ya esta en la raiz." -ForegroundColor Green
} else {
    Write-Host "No se encontro .git. Inicializando nuevo repositorio..." -ForegroundColor Yellow
    git init
}

# 3. Limpiar node_modules de cada workspace
Write-Host "Limpiando node_modules de cada workspace..." -ForegroundColor Yellow

$workspaces = @("Back", "frontend", "packages\shared")
foreach ($ws in $workspaces) {
    $nm = Join-Path $ws "node_modules"
    if (Test-Path $nm) {
        Write-Host "   Eliminando $nm..." -ForegroundColor Gray
        Remove-Item -Recurse -Force $nm
    }
}

if (Test-Path "node_modules") {
    Write-Host "   Eliminando node_modules raiz..." -ForegroundColor Gray
    Remove-Item -Recurse -Force "node_modules"
}

Write-Host "OK: node_modules limpiados." -ForegroundColor Green

# 4. Instalar todas las dependencias desde la raiz
Write-Host "Instalando dependencias del monorepo..." -ForegroundColor Yellow
npm install

if ($LASTEXITCODE -ne 0) {
    Write-Error "Error durante npm install. Revisa los errores arriba."
    exit 1
}

Write-Host "OK: Dependencias instaladas." -ForegroundColor Green

# 5. Build del paquete shared
Write-Host "Compilando paquete shared..." -ForegroundColor Yellow
npm run build:shared

if ($LASTEXITCODE -ne 0) {
    Write-Error "Error al compilar @obsidiantfit/shared."
    exit 1
}

Write-Host "OK: Paquete shared compilado." -ForegroundColor Green

# 6. Resumen final
Write-Host ""
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host "  Monorepo ObsidiantFit configurado!" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Comandos disponibles desde la raiz:" -ForegroundColor White
Write-Host "  npm run dev:backend   -> NestJS con hot-reload" -ForegroundColor Gray
Write-Host "  npm run dev:frontend  -> React + Vite" -ForegroundColor Gray
Write-Host "  npm run build         -> Build completo" -ForegroundColor Gray
Write-Host "  npm run test          -> Tests del backend" -ForegroundColor Gray
Write-Host "  npm run lint          -> Lint de todos los workspaces" -ForegroundColor Gray
Write-Host ""
Write-Host "Paquetes en el monorepo:" -ForegroundColor White
Write-Host "  @obsidiantfit/backend  -> Back/" -ForegroundColor Gray
Write-Host "  @obsidiantfit/frontend -> frontend/" -ForegroundColor Gray
Write-Host "  @obsidiantfit/shared   -> packages/shared/" -ForegroundColor Gray
Write-Host ""
