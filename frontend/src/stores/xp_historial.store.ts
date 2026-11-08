import { defineStore } from 'pinia';

export interface XpRegistro {
  id: string;
  usuario_id: string;
  xp_ganado: number;
  motivo: string;
  created_at: string;
}

export const useXpHistorialStore = defineStore('XpHistorial', {
  state: () => ({
    historial: [] as XpRegistro[],
    cargando: false as boolean,
    error: '' as string
  }),

  getters: {
    totalXpGanado: (state): number =>
      state.historial.reduce((total, reg) => total + reg.xp_ganado, 0),
    ultimosRegistros: (state): XpRegistro[] =>
      state.historial.slice(0, 10)
  },

  actions: {
    setHistorial(registros: XpRegistro[]) {
      this.historial = registros;
    },
    limpiar() {
      this.historial = [];
      this.error = '';
    }
  }
});
