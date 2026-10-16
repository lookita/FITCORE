// ─────────────────────────────────────────────
//  Roles de usuario en el sistema FITCORE
// ─────────────────────────────────────────────

/**
 * Roles disponibles en FITCORE.
 * - `admin`   → Acceso total al sistema
 * - `trainer` → Acceso a rutinas y usuarios asignados
 * - `user`    → Usuario final (acceso según estado de suscripción)
 */
export type Rol = 'user' | 'trainer' | 'admin';

// ────────────────────────────────────────────────────
//  Estados de suscripción en el sistema FITCORE
// ────────────────────────────────────────────────────

/**
 * Estado de la suscripción del usuario.
 * - `activa`    → Suscripción de pago activa
 * - `trial`     → Período de prueba activo
 * - `expirada`  → Suscripción vencida → redirigir a /subscription
 * - `cancelada` → Suscripción cancelada → redirigir a /subscription
 */
export type EstadoSub = 'activa' | 'expirada' | 'trial' | 'cancelada';

// ─────────────────────────────────────────────────
//  Lógica de control de acceso
// ─────────────────────────────────────────────────

/**
 * Valida si un usuario tiene acceso al sistema según su rol y
 * el estado de su suscripción.
 *
 * @param rol    - Rol del usuario en el sistema
 * @param estado - Estado actual de la suscripción
 * @returns Mensaje indicando el tipo de acceso o la acción requerida
 *
 * @example
 * validarAcceso('user', 'activa');    // '✅ Acceso premium completo'
 * validarAcceso('trainer', 'expirada'); // '🔒 Suscripción inactiva → ir a /subscription'
 */
export function validarAcceso(rol: Rol, estado: EstadoSub): string {
  if (estado === 'expirada' || estado === 'cancelada') {
    return '🔒 Suscripción inactiva → ir a /subscription';
  }
  switch (rol) {
    case 'admin':
      return '✅ Acceso total al sistema';
    case 'trainer':
      return '✅ Acceso a rutinas y usuarios asignados';
    case 'user':
      return estado === 'trial'
        ? '⚠️ Trial activo — convertir a pago'
        : '✅ Acceso premium completo';
  }
}

// ─────────────────────────────────────────────────
//  Constantes de roles y estados
// ─────────────────────────────────────────────────

/** Lista de todos los roles disponibles */
export const ROLES: Rol[] = ['user', 'trainer', 'admin'];

/** Lista de todos los estados de suscripción disponibles */
export const ESTADOS_SUB: EstadoSub[] = ['activa', 'expirada', 'trial', 'cancelada'];

/** Estados que bloquean el acceso al sistema */
export const ESTADOS_BLOQUEANTES: EstadoSub[] = ['expirada', 'cancelada'];
