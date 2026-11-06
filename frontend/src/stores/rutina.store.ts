import { defineStore } from 'pinia';
import type { RutinaCreada } from '@/DTO/Auth.DTO';
import { guardarRutina, obtenerCatalogo } from '@/services/Rutina_service';
import { StorageService } from '@/services/StorageService';

export const useRutina = defineStore('rutina', {
    state: () => ({
        categorias: [] as string[],
        nivelActual: 1,
        diasSeleccionados: [] as number[],
        rutinaCreada: null as RutinaCreada | null,
        tieneRutina: false,
        cargando: false,
        error: ''
    }),

    actions: {
        // Carga la lista de categorías, nivel y verifica si ya tiene rutina creada
        async cargarCatalogo() {
            this.cargando = true;
            this.error = '';
            try {
                const catalogo = await obtenerCatalogo();
                this.categorias = catalogo.categorias;
                this.nivelActual = catalogo.nivel_actual;

                // Si el servidor confirma que ya tiene rutina, activamos la persistencia
                if (catalogo.tiene_rutina && catalogo.rutina_actual) {
                    this.tieneRutina = true;
                    this.rutinaCreada = catalogo.rutina_actual;
                    if (catalogo.rutina_actual?.dias_semana) {
                        this.diasSeleccionados = [...catalogo.rutina_actual.dias_semana];
                    }
                    StorageService.guardar('fitcore-tiene-rutina', true);
                } else {
                    this.tieneRutina = false;
                    this.rutinaCreada = null;
                    this.diasSeleccionados = [];
                    StorageService.eliminar('fitcore-tiene-rutina');
                }
            } catch (error) {
                this.error = 'No se pudieron cargar las categorías.';
            } finally {
                this.cargando = false;
            }
        },

        // Permite marcar o desmarcar un día de la semana
        seleccionarDia(dia: number) {
            const indice = this.diasSeleccionados.indexOf(dia);
            if (indice !== -1) {
                // Si ya estaba seleccionado, lo quitamos
                this.diasSeleccionados.splice(indice, 1);
            } else {
                // Si no estaba, lo agregamos y ordenamos
                this.diasSeleccionados.push(dia);
                this.diasSeleccionados.sort((a, b) => a - b);
            }
        },

        // Envía la rutina seleccionada a la base de datos
        async crearRutina(categoria: string) {
            this.cargando = true;
            this.error = '';
            try {
                this.rutinaCreada = await guardarRutina({
                    categoria: categoria,
                    dias_semana: this.diasSeleccionados
                });
                this.tieneRutina = true;
                StorageService.guardar('fitcore-tiene-rutina', true);
            } catch (error) {
                this.error = error instanceof Error ? error.message : 'No se pudo crear la rutina.';
            } finally {
                this.cargando = false;
            }
        }
    }
});
