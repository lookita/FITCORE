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
}
</style>