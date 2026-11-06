// se encarga de comunicarse con PHP y validar la respuesta antes de entregársela a LoginView.vue.

import type { Login, Registro, Usuario, ApiResponse, DatosLogin } from '@/DTO/Auth.DTO';
import {
    validarRespuestaJson,
    validaURL,
    validarPayload
} from './PasoService';

const API_BASE_URL = (import.meta.env.VITE_API_BASE_URL || '/Backend').replace(/\/+$/, '');

async function solicitar(url: string, opciones: RequestInit): Promise<Response> {
    try {
        return await fetch(url, opciones);
    } catch (error) {
        if (error instanceof TypeError) {
            throw new Error(
                `No se pudo conectar con el backend (${url}). ` +
                'Verifica que Apache esté iniciado y que el backend esté disponible.'
            );
        }

        throw error;
    }
}

async function leerRespuesta<T>(respuesta: Response): Promise<ApiResponse<T>> {
    const cuerpo = await respuesta.text();
    let datos: unknown;

    if (!cuerpo.trim()) {
        throw new Error("Respondió sin contenido. HTTP: " + respuesta.status);
    }

    try {
        datos = JSON.parse(cuerpo);
    } catch {
        throw new Error(
            "El servidor no devolvió JSON válido. HTTP: " + respuesta.status
        );
    }

    validarRespuestaJson(datos);

    const resultado = datos as ApiResponse<T>;

    if (!respuesta.ok) {
        throw new Error(
            resultado.mensaje ?? `Error HTTP: ${respuesta.status}`
        );
    }

    return resultado;
}

export async function ejecutarLogin(payload: Login): Promise<ApiResponse<DatosLogin>> {
    const url = `${API_BASE_URL}/login.php`;

    validaURL(url);
    validarPayload(payload);

    const respuesta = await solicitar(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    return leerRespuesta<DatosLogin>(respuesta);
}

export async function ejecutarRegistro(payload: Registro): Promise<ApiResponse<Usuario>> {
    const url = `${API_BASE_URL}/registro.php`;

    validaURL(url);
    validarPayload(payload);

    const respuesta = await solicitar(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    return leerRespuesta<Usuario>(respuesta);
}

export async function verificarSesion(token: string): Promise<ApiResponse<{ usuario: Usuario }>> {
    const url = `${API_BASE_URL}/verificar_sesion.php`;

    validaURL(url);

    const respuesta = await solicitar(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({ token })
    });

    return leerRespuesta<{ usuario: Usuario }>(respuesta);
}

export async function ejecutarLogout(token: string): Promise<ApiResponse<null>> {
    const url = `${API_BASE_URL}/logout.php`;

    try {
        validaURL(url);

        const respuesta = await solicitar(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ token })
        });

        return leerRespuesta<null>(respuesta);
    } catch (e) {
        console.warn('No se pudo revocar la sesión en el servidor:', e);
        return {
            exito: true,
            mensaje: 'Sesión local cerrada',
            datos: null as any
        };
    }
}
