-- ============================================================
-- FitCore DB — Core con Gamificación y Bloqueo por Nivel
-- PostgreSQL DDL Schema
-- ============================================================

CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- ============================================================
-- FUNCIÓN AUXILIAR: actualiza updated_at automáticamente
-- Aplicada a todas las tablas que tienen esa columna.
-- ============================================================
CREATE OR REPLACE FUNCTION fn_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


-- ============================================================
-- 1. GAMIFICACIÓN: SISTEMA DE NIVELES
-- ============================================================
CREATE TABLE niveles (
    id           UUID    PRIMARY KEY DEFAULT uuid_generate_v4(),
    numero_nivel INTEGER NOT NULL UNIQUE, -- Ej: 1, 2, 3...
    nombre       VARCHAR(100) NOT NULL,   -- Ej: 'Novato', 'Atleta', 'Leyenda'
    xp_requerido INTEGER NOT NULL DEFAULT 0, -- XP mínimo para alcanzar este nivel
    created_at   TIMESTAMP NOT NULL DEFAULT NOW()
);


-- ============================================================
-- 2. AUTENTICACIÓN Y USUARIOS
-- ============================================================
CREATE TABLE usuarios (
    id               UUID         PRIMARY KEY DEFAULT uuid_generate_v4(),
    nombre           VARCHAR(80)  NOT NULL,
    email            VARCHAR(255) NOT NULL UNIQUE,
    password_hash    VARCHAR(255) NOT NULL,
    rol              VARCHAR(20)  NOT NULL DEFAULT 'usuario'
                     CHECK (rol IN ('usuario', 'admin')), -- Agregado: requerido por UsuarioDTO
    xp_total         INTEGER      NOT NULL DEFAULT 0,
    nivel_actual     INTEGER      NOT NULL DEFAULT 1 REFERENCES niveles(numero_nivel),
    ultima_sesion_at TIMESTAMP,  -- Para calcular rachas diarias (motivo: 'racha_diaria')
    created_at       TIMESTAMP    NOT NULL DEFAULT NOW(),
    updated_at       TIMESTAMP    NOT NULL DEFAULT NOW()
);

CREATE TRIGGER trg_usuarios_updated_at
BEFORE UPDATE ON usuarios
FOR EACH ROW EXECUTE FUNCTION fn_updated_at();

-- -------------------------------------------------------
-- Sesiones de autenticación (tokens de login)
-- -------------------------------------------------------
CREATE TABLE sesiones_auth (
    id         UUID      PRIMARY KEY DEFAULT uuid_generate_v4(),
    usuario_id UUID      NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    token      TEXT      NOT NULL UNIQUE,
    expira_en  TIMESTAMP NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Índice crítico: cada request autenticado busca por token
CREATE INDEX idx_sesiones_auth_token     ON sesiones_auth(token);
-- Índice para limpieza periódica de tokens vencidos
CREATE INDEX idx_sesiones_auth_expira_en ON sesiones_auth(expira_en);

-- -------------------------------------------------------
-- Historial de XP — registro inmutable (auditoría)
-- -------------------------------------------------------
CREATE TABLE xp_historial (
    id         UUID        PRIMARY KEY DEFAULT uuid_generate_v4(),
    usuario_id UUID        NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    xp_ganado  INTEGER     NOT NULL CHECK (xp_ganado > 0),
    motivo     VARCHAR(50) NOT NULL
               CHECK (motivo IN ('sesion_completada', 'ejercicio_nuevo', 'racha_diaria')),
    created_at TIMESTAMP   NOT NULL DEFAULT NOW()
);


-- ============================================================
-- 3. CATÁLOGO DE EJERCICIOS
-- ============================================================
CREATE TABLE ejercicios (
    id             UUID         PRIMARY KEY DEFAULT uuid_generate_v4(),
    nombre         VARCHAR(200) NOT NULL UNIQUE,
    descripcion    TEXT,
    grupo_muscular VARCHAR(50)  NOT NULL,
    creado_por_id  UUID REFERENCES usuarios(id) ON DELETE SET NULL, -- NULL = sistema; UUID = personalizado
    created_at     TIMESTAMP    NOT NULL DEFAULT NOW()
);


-- ============================================================
-- 4. RUTINAS CON CONDICIÓN DE DESBLOQUEO POR NIVEL
-- ============================================================
CREATE TABLE rutinas (
    id              UUID         PRIMARY KEY DEFAULT uuid_generate_v4(),
    usuario_id      UUID         NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    nombre          VARCHAR(200) NOT NULL,
    descripcion     TEXT,
    nivel_requerido INTEGER      NOT NULL DEFAULT 1 REFERENCES niveles(numero_nivel),
    created_at      TIMESTAMP    NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP    NOT NULL DEFAULT NOW()
);

CREATE TRIGGER trg_rutinas_updated_at
BEFORE UPDATE ON rutinas
FOR EACH ROW EXECUTE FUNCTION fn_updated_at();

-- -------------------------------------------------------
-- Ejercicios dentro de una rutina
-- -------------------------------------------------------
CREATE TABLE rutina_ejercicios (
    id                UUID    PRIMARY KEY DEFAULT uuid_generate_v4(),
    rutina_id         UUID    NOT NULL REFERENCES rutinas(id)    ON DELETE CASCADE,
    ejercicio_id      UUID    NOT NULL REFERENCES ejercicios(id) ON DELETE RESTRICT, -- No borrar un ejercicio si está en uso
    orden             INTEGER NOT NULL,
    series_objetivo   INTEGER NOT NULL DEFAULT 3,
    reps_objetivo     INTEGER,
    descanso_segundos INTEGER DEFAULT 60,
    UNIQUE (rutina_id, orden),
    UNIQUE (rutina_id, ejercicio_id)
);

-- Índice para buscar todas las rutinas que usan un ejercicio específico
CREATE INDEX idx_rutina_ejercicios_ejercicio ON rutina_ejercicios(ejercicio_id);

-- -------------------------------------------------------
-- Agenda semanal del usuario
-- -------------------------------------------------------
CREATE TABLE agenda_semanal (
    id         UUID    PRIMARY KEY DEFAULT uuid_generate_v4(),
    usuario_id UUID    NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    dia_semana INTEGER NOT NULL CHECK (dia_semana BETWEEN 1 AND 7), -- 1: Lunes, 7: Domingo
    rutina_id  UUID    REFERENCES rutinas(id) ON DELETE SET NULL,   -- SET NULL = día libre si se borra la rutina
    UNIQUE (usuario_id, dia_semana)
);


-- ============================================================
-- 5. TRACKING Y REGISTRO DE SESIONES
-- ============================================================
CREATE TABLE sesiones_entrenamiento (
    id                UUID        PRIMARY KEY DEFAULT uuid_generate_v4(),
    usuario_id        UUID        NOT NULL REFERENCES usuarios(id)  ON DELETE CASCADE,
    rutina_id         UUID        REFERENCES rutinas(id)            ON DELETE SET NULL,
    fecha             TIMESTAMP   NOT NULL DEFAULT NOW(),
    duracion_segundos INTEGER     CHECK (duracion_segundos >= 0),
    estado            VARCHAR(20) NOT NULL DEFAULT 'en_progreso'
                      CHECK (estado IN ('en_progreso', 'completada', 'cancelada')),
    xp_otorgado       INTEGER     NOT NULL DEFAULT 0 CHECK (xp_otorgado >= 0),
    notas             TEXT,
    created_at        TIMESTAMP   NOT NULL DEFAULT NOW()
);

-- -------------------------------------------------------
-- Series individuales registradas en una sesión
-- -------------------------------------------------------
CREATE TABLE series_registro (
    id           UUID        PRIMARY KEY DEFAULT uuid_generate_v4(),
    sesion_id    UUID        NOT NULL REFERENCES sesiones_entrenamiento(id) ON DELETE CASCADE,
    ejercicio_id UUID        NOT NULL REFERENCES ejercicios(id)             ON DELETE RESTRICT,
    numero_serie INTEGER     NOT NULL CHECK (numero_serie > 0),
    peso_kg      DECIMAL(6,2) CHECK (peso_kg >= 0),
    repeticiones INTEGER     CHECK (repeticiones > 0),
    completada   BOOLEAN     NOT NULL DEFAULT TRUE,
    created_at   TIMESTAMP   NOT NULL DEFAULT NOW(),
    UNIQUE (sesion_id, ejercicio_id, numero_serie)
);


-- ============================================================
-- ÍNDICES OPTIMIZADOS
-- ============================================================

-- Usuarios
CREATE INDEX idx_usuarios_nivel         ON usuarios(nivel_actual);

-- XP
CREATE INDEX idx_xp_historial_usuario   ON xp_historial(usuario_id);

-- Rutinas
CREATE INDEX idx_rutinas_usuario        ON rutinas(usuario_id);
CREATE INDEX idx_rutinas_nivel_req      ON rutinas(nivel_requerido);

-- Agenda
CREATE INDEX idx_agenda_usuario         ON agenda_semanal(usuario_id);

-- Sesiones
CREATE INDEX idx_sesiones_usuario_fecha ON sesiones_entrenamiento(usuario_id, fecha DESC);
CREATE INDEX idx_sesiones_rutina        ON sesiones_entrenamiento(rutina_id);

-- Series
CREATE INDEX idx_series_sesion          ON series_registro(sesion_id);
