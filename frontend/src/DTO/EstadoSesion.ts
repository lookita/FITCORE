// import {Usuario} from './Auth.DTO'

export interface EstadoSesion{
    usuarioConectado: Usuario | null,
    autenticado: boolean,
    validacion: boolean | null
}

export const valorInicial: EstadoSesion ={
    usuarioConectado: null,
    autenticado: false,
    validacion: null
}

