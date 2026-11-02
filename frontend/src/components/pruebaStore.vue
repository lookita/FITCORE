<script setup lang="ts">
import {useRutinasStore} from '../stores/auth.store'

const rutinasContadas = useRutinasStore();

console.log("La cantidad de ejercicios" + rutinasContadas)
</script>