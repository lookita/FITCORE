<script setup lang="ts">
import {ref} from 'vue'; 
import { obtenerTituloVista } from '@/DTO/usuario.dto';
import { PasoFormulario } from '@/DTO/RegistroUsuarioDTO';
const nombre = ref<string>('');
const password = ref<string>('');
const correo = ref<string>('');

const titulo = obtenerTituloVista(PasoFormulario.REGISTRO); 
</script>

<template>
    <section class="separacion">
        <section>
            <p style="font-size: x-small;
             color: rgb(255, 187, 0);"
            >PLAN INTELIGENTE</p>
            <h1 style="color: white">{{ titulo }}</h1>
        </section>
        <section class="fondo-form">
            <form action="submit">
                <div>
                    <p>Nombre</p>
                    <input v-model="nombre" type="text" placeholder="Ingrese su nombre">
                </div>
                <div>
                    <p>Ingrese una contraseña</p>
                    <input v-model="password" type="password" placeholder="Ingrese su contraseña">
                </div>
                <div>
                    <p>Ingrese un correo</p>
                    <input v-model="correo" type="email" placeholder="Ingrese su email">
                </div>
                <button>
                    enviar
                </button>
            </form>
        </section>
    </section>
</template>

<style scoped>
.separacion{
    padding: 40px 30px 40px 30px;
    background-color: #160c0c ;
}
section h1{
    background-color: #160c0c;
    margin: 0;
    padding: 0;
}

.fondo-form{
    background-color: #000000;
    padding: 100px 40px 100px 40px;
    height: 624px;

}

form{
    display: grid;
    grid-template-rows: 60px 60px 60px 60px;
    row-gap: 12px;
    background-color:  rgb(27, 24, 24);
}

.fondo-form{
    background-color:  rgb(27, 24, 24);
    border-radius: 20px;
    margin-top: 40px;
}
h1{
    font-family: "Rubik", sans-serif;
    font-weight: 500;
}
p{
    margin: 0;
    font-family: "Rubik", sans-serif;
    padding-bottom: 10px;
}


input{
    height: 40px;
    width: 100%;
    border-radius: 10px;
    border:  solid 1px #f3efef;
    background-color: #160c0c;
    padding: 0 10px 0 10px;
    
}

input:hover{
    height: 40px;
    width: 100%;
    border-radius: 10px;
    border: solid 1px #f04444;
    background-color: #160c0c;
    padding: 0 10px 0 10px
}

button{
    display: flex;
    align-items: center;
    justify-content: center;
    grid-template-columns: repeat(2, auto);
    background-color: #f04444;
    gap: 10px;
    height: 50px;
    width: 170px;
    border-radius: 70px;
    border: none;
}
</style>