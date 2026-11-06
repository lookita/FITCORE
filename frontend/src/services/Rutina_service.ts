import type {
    ApiResponse,
    CatalogoRutina,
    CrearRutinaPayload,
    RutinaCreada
} from '@/DTO/Auth.DTO';
import { StorageService } from './StorageService';

const API = (import.meta.env.VITE_API_BASE_URL || '/Backend').replace(/\/+$/, '');

async function pedir<T>(archivo: string, opciones: RequestInit = {}): Promise<T> {
    const token = StorageService.obtener<string>('fitcore-token');
    if (!token) throw new Error('Inicia sesión para continuar.');

    const respuesta = await fetch(`${API}/${archivo}`, {
        ...opciones,
        headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`
        }
    });

    let resultado: ApiResponse<T>;
    try {
        resultado = await respuesta.json() as ApiResponse<T>;
    } catch {
        throw new Error('El servidor no devolvió una respuesta válida.');
    }

    if (!respuesta.ok || !resultado.exito) {
        throw new Error(resultado.mensaje || `Error HTTP ${respuesta.status}.`);
    }
    return resultado.datos;
}

export function obtenerCatalogo(): Promise<CatalogoRutina> {
    return pedir<CatalogoRutina>('GeneradorEntrenamiento.php');
}

export function guardarRutina(datos: CrearRutinaPayload): Promise<RutinaCreada> {
    return pedir<RutinaCreada>('Generador_rutina.php', {
        method: 'POST',
        body: JSON.stringify(datos)
    });
}

// ── Tipos para registrar la sesión ────────────────────────────────────────
// Adaptados a las columnas reales de series_registro:
//   id, sesion_id, numero_serie, peso_kg, repeticiones, completada
export interface SerieLog {
    numero_serie: number;
    repeticiones: number;   // columna real: repeticiones
    completada:   boolean;
    peso_kg?:     number;   // columna real: peso_kg (calistenia → 0)
}

export interface EjercicioLog {
    ejercicio_id?: string;
    series: SerieLog[];
    xp_ganado?: number;
}

// Columnas reales de sesiones_entrenamiento:
//   id, usuario_id, fecha, duracion_segundos, estado, xp_otorgado, numero_dia
export interface RegistrarSesionPayload {
    numero_dia:      number;   // día de la semana entrenado
    xp_total_sesion: number;   // XP calculado en el frontend
    ejercicios:      EjercicioLog[];
}

export interface ResultadoSesion {
    sesion_id:      string;
    xp_ganado:      number;
    xp_total_nuevo: number;
    nivel_anterior: number;
    nivel_nuevo:    number;
    subio_de_nivel: boolean;
}

export function registrarSesion(datos: RegistrarSesionPayload): Promise<ResultadoSesion> {
    return pedir<ResultadoSesion>('registrar_sesion.php', {
        method: 'POST',
        body: JSON.stringify(datos)
    });
}
