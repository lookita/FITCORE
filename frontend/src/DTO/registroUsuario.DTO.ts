export enum PasoFormulario{
    REGISTRO = 'REGISTRO',
    LOGIN = 'LOGIN',
    PERFIL = 'PERFIL'
}

const EstadoActual = PasoFormulario.LOGIN; 

export function PasoFinal(p: PasoFormulario){
    if(p === 'PERFIL'){
        return true
    }
    return false
}