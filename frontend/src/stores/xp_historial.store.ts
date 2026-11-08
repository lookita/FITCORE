import {} from '../'

export const useUsuarioVerificacion = defineStore('Autenticacion',{
    state:() =>({
        
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
        },


        async restaurarSesion() {
            const token = StorageService.obtener<string>('fitcore-token');

            if (!token) {
                this.estadoSesion = {...valorInicial};
                return;
            }


            const respuesta = await verificarSesion(token);

            this.estadoSesion.usuarioConectado = respuesta.datos.usuario;
        },


        cerrarSesion() {
            StorageService.eliminar('fitcore-token');

            this.estadoSesion = {...valorInicial};
        }
    }
});