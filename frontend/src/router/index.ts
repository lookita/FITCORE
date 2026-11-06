import { createRouter, createWebHistory } from 'vue-router';
import InicioView from '../views/InicioView.vue';
import PerfilView from '@/views/PerfilView.vue';
import RegistroView from '@/views/RegistroView.vue';
import LoginView from '@/views/LoginView.vue';
import RutinasView from '@/views/RutinasView.vue';
import EntrenamientoView from '@/views/EntrenamientoView.vue';
import DashboardView from '@/views/DashboardView.vue';
import { useUsuarioVerificacion } from '@/stores/auth.store';
import EntrenamientoTiempoReal from '@/views/EntrenamientoTiempoReal.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'inicio',
            component: InicioView
        },
        {
            path: '/login',
            name: 'Login',
            component: LoginView
        },
        {
            path: '/registro',
            name: 'Registro',
            component: RegistroView
        },
        {
            path: '/dashboard',
            name: 'Dashboard',
            component: DashboardView,
            meta: { requiereAuth: true }
        },
        {
            path: '/perfil',
            name: 'Perfil',
            component: PerfilView,
            meta: { requiereAuth: true }
        },
        {
            path: '/rutinas',
            name: 'Rutinas',
            component: RutinasView,
            meta: { requiereAuth: true }
        },
        {
            path: '/entrenamiento',
            name: 'entrenamiento',
            component: EntrenamientoView,
            meta: { requiereAuth: true }
        },
        {
            path: '/minivel',
            redirect: '/perfil'
        },
        {
            path: '/entrenamientoEjercicio',
            name: 'entrenamientoEjercicio',
            component: EntrenamientoTiempoReal,
            meta: { requiereAuth: true }
        },
    ]
});

router.beforeEach(async (to) => {
    const auth = useUsuarioVerificacion();

    // 1. Restaurar la sesión almacenada si aún no se evaluó
    if (!auth.restaurado) {
        await auth.restaurarSesion();
    }

    // 2. Proteger rutas que requieren autenticación
    if (to.meta.requiereAuth && !auth.estadoSesion.autenticado) {
        return { name: 'Login' };
    }

    // 3. Si el usuario logueado intenta entrar a Login o Registro → Dashboard
    if ((to.name === 'Login' || to.name === 'Registro') && auth.estadoSesion.autenticado) {
        return { name: 'Dashboard' };
    }
});

export default router;
