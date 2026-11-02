export class StorageService{
    static guardar<S>(clave: string, valor: S ):void{
        const textoJson = JSON.stringify(valor)
        localStorage.setItem(clave, textoJson );
    }

    static obtener<T>(clave: string): T | null{
        const texto = localStorage.getItem(clave);

        if(texto === null){
            return null;
        }else{
            return JSON.parse(texto) as T
        }

    }

    static eliminar (clave: string):void{
        localStorage.removeItem(clave)
    }    
}



