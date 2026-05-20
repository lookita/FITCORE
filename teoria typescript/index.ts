class MiPromesa {
  private estado = 'PENDIENTE';
  private valor: any;
  private callbacks: any[] = [];

  constructor(ejecutor: any) {
    const resolver = (val: any) => {
      this.estado = 'CUMPLIDA';
      this.valor = val;
      this.callbacks.forEach(cb => cb(val));
    };
    ejecutor(resolver);
  }

  then(callback: any) {
    if (this.estado === 'CUMPLIDA') callback(this.valor);
    else this.callbacks.push(callback);
  }
}