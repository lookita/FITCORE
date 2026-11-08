<script setup lang="ts">
import {useRouter} from 'vue-router'
</script>
<template>
    <section class="barra-inferior">
        <div>
            <router-link to="/" style="text-decoration: none;">
                <h3>Inicio</h3>
            </router-link>
        </div>
        <div>
            <router-link to="/perfil" style="text-decoration: none;" >
                <h3>Perfil</h3>
            </router-link>
        </div>
        <div>
            <router-link to="/rutinas" style="text-decoration: none;" >
                <h3>Rutinas</h3>
            </router-link>
        </div>
    </section>
</template>

<style scoped>
.barra-inferior{
    display: none;
}

@media (max-width: 900px) {
    .barra-inferior{
        display: grid;
        grid-template-columns: repeat(3, auto);
        background-color: #ff3b30;
        position: fixed;        /* Lo fija a la ventana del navegador, ignorando el scroll */
        bottom: 0;
        width: 100vw;           /* Ocupa el 100% del ancho de la pantalla */
        height: 80px;          /* Ocupa el 100% de la altura de la pantalla */
        text-align: center;
        align-items: center;
        padding: 0;
        margin: 0;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;

    }

    h3{
        color: #ffffff;
        font-family: 'Rubik', sans-serif;
        font-size: 15px;
    }
}
</style>