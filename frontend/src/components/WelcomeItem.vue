export class AuthService {
  static async registrar(nombre: string): Promise<boolean> {
    console.log("Registrando usuario:", nombre);
    return true;
  }
}
