export class StorageService {
    static guardar<S>(clave: string, valor: S): void {
        try {
            const textoJson = typeof valor === 'string' ? valor : JSON.stringify(valor);
            localStorage.setItem(clave, textoJson);
        } catch (e) {
            console.error(`Error al guardar en StorageService [${clave}]:`, e);
        }
    }

    static obtener<T>(clave: string): T | null {
        try {
            const texto = localStorage.getItem(clave);
            if (texto === null) {
                return null;
            }
            try {
                return JSON.parse(texto) as T;
            } catch {
                // Si no era JSON (ej: string plano guardado previamente), devolver el texto
                return texto as unknown as T;
            }
        } catch (e) {
            console.error(`Error al obtener de StorageService [${clave}]:`, e);
            return null;
        }
    }

    static eliminar(clave: string): void {
        try {
            localStorage.removeItem(clave);
        } catch (e) {
            console.error(`Error al eliminar de StorageService [${clave}]:`, e);
        }
    }

    static limpiar(): void {
        try {
            localStorage.clear();
        } catch (e) {
            console.error('Error al limpiar StorageService:', e);
        }
    }
}
