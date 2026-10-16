//validaciones
export function validarCodigo(estadoCodigo: number): void{
    if(estadoCodigo < 200 || estadoCodigo >= 300){
        throw new Error("HTTP: " + estadoCodigo);
    }
}

export function validarRespuestaJson(datos: any):void{
    if(datos === null || datos === undefined){
        throw new Error("Respuesta del servidor vacia o desconocida");
    }
}

export function validaURL(url: string):void{
    if(url === ''){
        throw new Error("No existe ninguna url");
        
    }
}

export function validarMetodoPOST(metodo: string):void{
    if(metodo !== 'POST'){
        throw new Error("El servicio solo acepta peticiones");
        
    }
}


//en esta validacion debe ser tipo objeto
export function validarPayload(body: Object): void{
    //aca se cuenta cuantas key o propiedades hay dentro del body, si o si deben ser mas de 1
    //para poder contar y usar length necesito si o si object.keys()
    if(Object.keys(body).length === 0){
        throw new Error("No se pueden enviar datos vacios al servidor");
        
    }
}


export function chequearConexion(online: boolean):void{
    if(online === false){
        throw new Error("Sin conexion");
        
    }
}

export function validarErrorBackend(respuesta: {error?: string} | null | undefined):void{
    if(respuesta?.error){
        throw new Error(respuesta.error);
        
    }
}

export function validarEmail(email: string):void{
    if(!email.includes("@")){
        throw new Error("Ingresa un email valido");
        
    }
}


export function validarPaswoord(password: string): void{
    if(password.length < 8){
        throw new Error("Ingresa una contraseña igual o mayor a 8 caracteres");
        
    }
}

export function tiempoRespuesta(tiempo: number):void{
    if(tiempo > 5000){
        throw new Error("El servidor tarder demasiado en responder");
        
    }
}


