import type { Login, Registro, Usuario, ApiResponse } from '@/DTO/Auth.DTO';

import {
    validarCodigo,
    validarRespuestaJson,
    validaURL,
    validarMetodoPOST,
    validarPayload,
    chequearConexion,
    validarErrorBackend,
    validarEmail,
    validarPaswoord,
    tiempoRespuesta
} from './PasoService';

const API_BASE_URL = 'http://localhost/Backend';

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


export async function ejecutarLogin(payload: Login): Promise<ApiResponse<Usuario>>{
    const url = `${API_BASE_URL}/login.php`;
    

    validaURL(url);
    validarPayload(payload); 

    const respuesta = await fetch(url, {
        method: 'POST',
        headers: {
            'content-type' : 'application/json'
        },
        body: JSON.stringify(payload)
    });

    return leerRespuesta<Usuario>(respuesta); 
}


export async function ejecutarRegistro(payload: Registro):Promise<ApiResponse<Usuario>>{
    const url = `${API_BASE_URL}/registro.php`;
    

    validaURL(url);
    validarPayload(payload); 

    const respuesta = await fetch(url, {
        method: 'POST',
        headers: {
            'content-type' : 'application/json'
        },
        body: JSON.stringify(payload)
    });

    return leerRespuesta<Usuario>(respuesta);
}

