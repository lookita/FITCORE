# Introducción a Peticiones HTTP — El Mensajero del Gym

### ¿Qué es esto? (La analogía) 📮🚴‍♂️
Imagina que tu gimnasio se queda sin discos. No puedes fabricarlos tú mismo, así que llamas a un **Mensajero**. El mensajero va hasta la fábrica, pide los discos y te los trae de vuelta. Las **Peticiones HTTP** son esos viajes que hace tu código para buscar datos en internet (en el servidor) y traerlos a tu pantalla.

### ¿Cuándo lo uso en ObsidiantFit? 🌐
- Para traer la lista de ejercicios que están guardados en la nube.
- Para enviar el progreso del usuario al servidor para que no se pierda si cambia de celular.

### El Truco del Experto 💡
Como el mensajero tarda un tiempo en ir y volver, siempre usamos `async` y `await`. Es como decirle a tu código: "Espera aquí sentado a que el mensajero vuelva con el paquete antes de seguir".
