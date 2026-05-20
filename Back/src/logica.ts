type Rol = 'user' | 'trainer' | 'admin';
type EstadoSub = 'activa' | 'expirada' | 'trial' | 'cancelada';

function validarAcceso(rol: Rol, estado: EstadoSub): string {
  if (estado === 'expirada' || estado === 'cancelada') {
    return '🔒 Suscripción inactiva → ir a /subscription';
  }
  switch (rol) {
    case 'admin':   return '✅ Acceso total al sistema';
    case 'trainer': return '✅ Acceso a rutinas y usuarios asignados';
    case 'user':    return estado === 'trial'
                      ? '⚠️ Trial activo — convertir a pago'
                      : '✅ Acceso premium completo';
  }
}

console.log(validarAcceso('user', 'activa'));    // ✅ Acceso premium
console.log(validarAcceso('trainer', 'expirada')); // 🔒 Suscripción inactiva
