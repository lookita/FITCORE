<script setup lang="ts">
import type router from './router';

</script>

<template>
    <div id="App">
        <router-view />
    </div>
</template>


<style>
*, *::before, *::after {
    box-sizing: border-box;
}
body {
    margin: 0;
    background-color: #0c0c0e;
    color: #ffffff;
    font-family: 'Rubik', sans-serif;
}
#App {
    min-height: 100vh;
    background-color: #0c0c0e;
}
</style>