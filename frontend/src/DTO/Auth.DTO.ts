export interface Login{
    email: string,
    password: string
}

export interface Registro{
    nombre: string,
    password: string,
    email: string
}


export interface Usuario{
    id: string | number,
    nombre: string,
    email: string, 
    xp_total: number,
    nivel_actual: number
}

export interface ApiResponse<T>{
    'exito': boolean,
    'mensaje': string,
    'datos' : T
}


export interface DatosLogin{
    usuario: Usuario,
    token: string
}
