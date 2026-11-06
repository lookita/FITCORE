import { defineStore } from 'pinia';
import { useUsuarioVerificacion } from './auth.store';

export interface NivelInfo {
  id: number;
  codigo: string;       // "01", "02", etc.
  titulo: string;       // "NIVEL 1"
  nombre: string;       // "Fundamentos y Fuerza Básica"
  xpRequerido: number;  // 100
  ejerciciosCount: number;
  duracionMin: number;
  descripcion: string;
}

export const useEntrenamientoStore = defineStore('Entrenamiento', {
  state: () => ({
    minutosSemanales: 142,
    entrenosSemanales: 3,
    intereses: [
      { id: 'fuerza', nombre: 'Fuerza funcional', icono: 'bi-lightning-charge-fill' },
      { id: 'rendimiento', nombre: 'Rendimiento', icono: 'bi-activity' },
      { id: 'hiit', nombre: 'HIIT express', icono: 'bi-stopwatch-fill' }
    ],
    amigos: [
      { id: 1, nombre: 'Mara', activo: true, foto: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' },
      { id: 2, nombre: 'Leo', activo: true, foto: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80' },
      { id: 3, nombre: 'Inés', activo: true, foto: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&auto=format&fit=crop&q=80' },
      { id: 4, nombre: 'Dani', activo: true, foto: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=80' },
      { id: 5, nombre: 'Noa', activo: true, foto: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80' },
      { id: 6, nombre: 'Álex', activo: true, foto: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=100&auto=format&fit=crop&q=80' }
    ],
    catalogoNiveles: [
      // Niveles 1–5: máximo 6 ejercicios por día (regla FITCORE)
      { id: 1,  codigo: '01', titulo: 'NIVEL 1',  nombre: 'Fundamentos y Fuerza Básica',      xpRequerido: 1000, ejerciciosCount: 4, duracionMin: 30, descripcion: 'Dominio de flexiones estrictas, fondos y dominadas asistidas.' },
      { id: 2,  codigo: '02', titulo: 'NIVEL 2',  nombre: 'Resistencia y Control de Core',    xpRequerido: 1000, ejerciciosCount: 5, duracionMin: 35, descripcion: 'L-sit básico, planchas dinámicas y fondos en paralelas.' },
      { id: 3,  codigo: '03', titulo: 'NIVEL 3',  nombre: 'Fuerza Isométrica y Tracción',     xpRequerido: 1000, ejerciciosCount: 6, duracionMin: 40, descripcion: 'Dominadas pronas y supinas con tempo controlado.' },
      { id: 4,  codigo: '04', titulo: 'NIVEL 4',  nombre: 'Potencia y Flexiones Explosivas',  xpRequerido: 1000, ejerciciosCount: 6, duracionMin: 45, descripcion: 'Flexiones con palmada y transiciones dinámicas.' },
      { id: 5,  codigo: '05', titulo: 'NIVEL 5',  nombre: 'Muscle-Up Mastery',                xpRequerido: 1000, ejerciciosCount: 6, duracionMin: 50, descripcion: 'Transiciones explosivas en barra y fuerza máxima.' },
      // Niveles 6–10: el límite sube progresivamente
      { id: 6,  codigo: '06', titulo: 'NIVEL 6',  nombre: 'Pliometría y Empuje Avanzado',     xpRequerido: 1000, ejerciciosCount: 7, duracionMin: 50, descripcion: 'Fondos profundos y equilibrio dinámico en anillas.' },
      { id: 7,  codigo: '07', titulo: 'NIVEL 7',  nombre: 'Palancas y Front Lever Básico',    xpRequerido: 1000, ejerciciosCount: 7, duracionMin: 55, descripcion: 'Tuck front lever e iniciación a palanca invertida.' },
      { id: 8,  codigo: '08', titulo: 'NIVEL 8',  nombre: 'Handstand y Equilibrio Vertical',  xpRequerido: 1000, ejerciciosCount: 8, duracionMin: 55, descripcion: 'Pino estricto contra pared y flexiones verticales.' },
      { id: 9,  codigo: '09', titulo: 'NIVEL 9',  nombre: 'Resistencia Extrema y Circuitos',  xpRequerido: 1000, ejerciciosCount: 8, duracionMin: 60, descripcion: 'Super-series de alta intensidad sin descanso intermedio.' },
      { id: 10, codigo: '10', titulo: 'NIVEL 10', nombre: 'Élite FitCore: Dominio Absoluto',  xpRequerido: 1000, ejerciciosCount: 8, duracionMin: 60, descripcion: 'Combinación de todas las habilidades en rutinas continuas.' }
    ] as NivelInfo[]
  }),

  getters: {
    nivelUsuario: () => {
      const auth = useUsuarioVerificacion();
      return auth.nivel || 1;
    },
    xpUsuario: () => {
      const auth = useUsuarioVerificacion();
      return auth.xpActual || 80;
    },
    rachaUsuario: () => {
      const auth = useUsuarioVerificacion();
      return auth.rachaDias || 6;
    },
    nivelesDesbloqueados(): number {
      return this.catalogoNiveles.filter(n => n.id <= this.nivelUsuario).length;
    },
    nivelesBloqueados(): number {
      return this.catalogoNiveles.filter(n => n.id > this.nivelUsuario).length;
    }
  },

  actions: {
    estaDesbloqueado(nivelId: number): boolean {
      return nivelId <= this.nivelUsuario;
    },
    esNivelActual(nivelId: number): boolean {
      return nivelId === this.nivelUsuario;
    }
  }
});
