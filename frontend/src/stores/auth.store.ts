import { defineStore } from 'pinia';
import { valorInicial, type EstadoSesion } from '../DTO/EstadoSesion';
import type { Login, Usuario } from '../DTO/Auth.DTO';
import { ejecutarLogin, verificarSesion, ejecutarLogout } from '../services/autenticar.service';
import { StorageService } from '../services/StorageService';

export const useUsuarioVerificacion = defineStore('Autenticacion', {
    state: () => ({
        estadoSesion: { ...valorInicial } as EstadoSesion,
        cargando: false as boolean,
        restaurado: false as boolean
    }),

    getters: {
        estaAutenticado: (state): boolean => state.estadoSesion.autenticado,
        usuario: (state): Usuario | null => state.estadoSesion.usuarioConectado,
        nombre: (state): string => state.estadoSesion.usuarioConectado?.nombre || 'Atleta',
        nivel: (state): number => Number(state.estadoSesion.usuarioConectado?.nivel_actual || 1),
        xpActual: (state): number => Number(state.estadoSesion.usuarioConectado?.xp_total || 0),
        xpSiguienteNivel: (state): number => {
            const nivel = Number(state.estadoSesion.usuarioConectado?.nivel_actual || 1);
            return nivel * 500;
        },
        rango: (state): string => {
            const nivel = Number(state.estadoSesion.usuarioConectado?.nivel_actual || 1);
            if (nivel >= 8) return 'Leyenda';
            if (nivel >= 5) return 'Avanzado';
            if (nivel >= 3) return 'Intermedio';
            return 'Iniciado';
        },
        rachaDias: (): number => 3,
        entrenamientosCompletados: (): number => 5,
        totalXp: (state): number => Number(state.estadoSesion.usuarioConectado?.xp_total || 0),
        horasEntrenadas: (): number => 4
    },

    actions: {
        async iniciarSesion(login: Login) {
            this.cargando = true;
            try {
                const respuesta = await ejecutarLogin(login);
                const { usuario, token } = respuesta.datos;

                // Persistencia local
                StorageService.guardar('fitcore-token', token);
                StorageService.guardar('fitcore-usuario', usuario);

                // Si el usuario no tiene rutina, aseguramos limpiar cualquier residuo de sesiones anteriores
                if (!usuario.tiene_rutina) {
                    StorageService.eliminar('fitcore-tiene-rutina');
                } else {
                    StorageService.guardar('fitcore-tiene-rutina', true);
                }

                // Estado en memoria (Pinia)
                this.estadoSesion.usuarioConectado = usuario;
                this.estadoSesion.autenticado = true;
                this.estadoSesion.validacion = true;
                this.restaurado = true;

                return respuesta;
            } finally {
                this.cargando = false;
            }
        },

        async restaurarSesion() {
            if (this.restaurado) return;

            const token = StorageService.obtener<string>('fitcore-token');
            const usuarioGuardado = StorageService.obtener<Usuario>('fitcore-usuario');

            if (!token) {
                this.estadoSesion = { ...valorInicial };
                this.restaurado = true;
                return;
            }

            // Hidratación inmediata desde almacenamiento local para evitar parpadeos
            if (usuarioGuardado) {
                this.estadoSesion.usuarioConectado = usuarioGuardado;
                this.estadoSesion.autenticado = true;
                this.estadoSesion.validacion = true;
            }

            this.cargando = true;

            try {
                // Verificar con el Backend PHP que el token sigue activo en PostgreSQL
                const respuesta = await verificarSesion(token);

                this.estadoSesion.usuarioConectado = respuesta.datos.usuario;
                this.estadoSesion.autenticado = true;
                this.estadoSesion.validacion = true;

                StorageService.guardar('fitcore-usuario', respuesta.datos.usuario);
                if (!respuesta.datos.usuario.tiene_rutina) {
                    StorageService.eliminar('fitcore-tiene-rutina');
                } else {
                    StorageService.guardar('fitcore-tiene-rutina', true);
                }
            } catch (error) {
                console.warn('La sesión guardada expiró o ya no es válida en el servidor:', error);
                this.cerrarSesionLocal();
            } finally {
                this.cargando = false;
                this.restaurado = true;
            }
        },

        async cerrarSesion() {
            const token = StorageService.obtener<string>('fitcore-token');

            if (token) {
                try {
                    await ejecutarLogout(token);
                } catch (e) {
                    console.warn('No se pudo revocar token en backend:', e);
                }
            }

            this.cerrarSesionLocal();
        },

        cerrarSesionLocal() {
            StorageService.eliminar('fitcore-token');
            StorageService.eliminar('fitcore-usuario');
            StorageService.eliminar('fitcore-tiene-rutina');
            this.estadoSesion = { ...valorInicial };
            this.restaurado = true;
        }
    }
});
