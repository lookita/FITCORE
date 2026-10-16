import {createRouter, createWebHistory} from 'vue-router';
import InicioView from '../views/InicioView.vue'
import PerfilView from '@/views/PerfilView.vue';
import RegistroView from '@/views/RegistroView.vue';
import LoginView from '@/views/LoginView.vue';
import RutinasView from '@/views/RutinasView.vue';
import EntrenamientoView from '@/views/EntrenamientoView.vue';
import MiNivel from '@/views/miNivel.vue';

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
        },{
            path:'/perfil',
            name: 'Perfil',
            component: PerfilView
        },{
            path: '/registro',
            name: 'Registro',
            component: RegistroView
            
        },
        {
            path: '/rutinas',
            name: 'Rutinas',
            component: RutinasView
        },
        {
            path: '/entrenamiento',
            name: 'entrenamiento',
            component: EntrenamientoView
        },
        {
            path: '/minivel',
            redirect: '/perfil'
        }
    ]
})

export default router 