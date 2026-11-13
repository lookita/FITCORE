export interface Login {
    email: string;
    password: string;
}

export interface Registro {
    nombre: string;
    password: string;
    email: string;
}

export interface Usuario {
    id: string | number;
    nombre: string;
    email: string;
    rol?: string;
    imagen_url?: string | null;
    xp_total: number;
    nivel_actual: number;
    tiene_rutina?: boolean;
}

export interface ApiResponse<T> {
    exito: boolean;
    mensaje: string;
    datos: T;
}

export interface DatosLogin {
    usuario: Usuario;
    token: string;
}


export interface CatalogoRutina {
    nivel_actual: number;
    categorias: string[];
    tiene_rutina?: boolean;
    rutina_actual?: RutinaCreada | null;
}

export interface CrearRutinaPayload {
    categoria: string;
    dias_semana: number[];
}

export interface EjercicioRutina {
    id: string;
    nombre: string;
    imagen_url?: string | null;
    descripcion: string | null;
    series_objetivo: number;
    reps_objetivo: number;
}

export interface RutinaCreada {
    id: string;
    nombre: string;
    categoria: string;
    nivel_actual: number;
    dias_semana: number[];
    dias_completados?: number[];
    ejercicios: EjercicioRutina[];
    planificacion_id?: string | null;
    semana_actual?: number;
    xp_semana?: number;
    objetivo_xp_semanal?: number;
    estado_semana?: 'activa' | 'completada';
}