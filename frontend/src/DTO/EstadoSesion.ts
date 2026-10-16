export interface EstadoSesion{
    UsuarioConectado: string | null,
    Autenticado: boolean
}

export interface EstadoInicial{
    EstadoSesion: EstadoSesion,
    UsuarioConectado: null,
    Autenticado: false
}