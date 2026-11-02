import {defineStore} from 'pinia'
import {valorInicial} from '../DTO/EstadoSesion'
import type {Login} from '../Auth.DTO.ts'
import {ejecutarLogin} from '../services/autenticar.service.ts'
import {StorageService} from '../services/StorageService.ts'
import type {EstadoSesion} from '../DTO/EstadoSesion.ts'
 

export const useUsuarioVerificacion = defineStore('Autenticacion',{
    state:() =>({
        //hacer una copia
        estadoSesion:{...valorInicial}
    }), 

    actions:{
        //llamo a la funcion, establezco parametro
        async iniciarSesion(login: Login){
            const datos = await ejecutarLogin(login);

            //guardo token sin crear ninguna variable
            StorageService.guardar('fitcore-token', datos.datos.token);


            // utilizo this para llamara a las propiedades
            this.estadoSesion.usuarioConectado = datos.datos.usuario;
            this.estadoSesion.autenticado = true;
            this.estadoSesion.validacion = true;
        }
    }
});