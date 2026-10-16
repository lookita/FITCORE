export enum metodoHttp{
    GET = 'GET',
    POST = 'POST',
    PUT = 'PUT', 
    DELETE = 'DELETE'
}

export enum EstadoRespuesta{
    EXITO = 'EXITO',
    ERROR = 'ERROR',
    PENDIENTE = 'PENDIENTE'
}

export function EvaluarCarga(e: EstadoRespuesta):boolean{
    if(e === 'PENDIENTE'){
        return true; 
    }
    return false;
}


export function esMetodoLectura(e: metodoHttp): boolean{
    if(e === 'GET'){
        return true;
    }
    return false; 
}