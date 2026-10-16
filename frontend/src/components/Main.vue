<template>
    <main>
        <!-- titulo -->
        <section class="color-rojo">
            <div class="nueva-plataforma">
                <div style="padding-top: 10px; padding-left: 5px;">
                    <img src="../assets/fondo-cuadrado-verde-oscuro-abstracto_7954-26020.avif" alt="" class="cirulo-verde">
                </div>
                <div>
                    <p style="font-size:x-small;">NUEVA PLATAFORMA 4.0</p>
                </div>
            </div>

            <div>
                <h1 style="color: white; font-size: 50px; margin: 0;">Convierte el rendimiento en una ventaja competitiva</h1>
            </div>

            <div>
                <h4 style="color: white; opacity: 50%;"> Entrenamiento, bienestar y analitica empresarial en una experiencia que tu equipo querra completar cada dia</h4>
            </div>

            <div>
                <div>
                    <p>Activar prueba gratuita</p>
                </div>

                <div>
                <p>Ver como funciona</p>
                </div>
            </div>

            <div>
                
            </div>
        </section>

        <section class="color-verde">
            <div class="usuario">
                <div>
                    <img src="../assets/" alt=""> logo
                </div>

                <div class="usuario-saludos">
                    <div>
                        <p style="opacity: 50%;">BUENOS DIAS</p>
                    </div>
                    <div>
                        <p> Lucas{{ nombre }}</p>
                    </div>
                </div>
                
                <div>
                    <p>NIVEL 10 {{ nivel }}</p>
                </div>
            </div>

            <div class="foto-comercial">
                <img src="https://jdtrainingcenter.com/wp-content/uploads/2024/06/GymLorca-00907-scaled.jpg" alt="">
            </div>

            <div class="potencia-total">

            </div>

            <div class="progreso-total">

            </div>
        </section>
    </main>
</template>

<style>
p{
    color: white;
}

main{
    background-color: #160c0c;
    display: grid;
    grid-template-columns: repeat(2, 50%) ;
    padding-top: 90px;
    padding-left: 50px;
    padding-right: 50px;
    padding-bottom: 50px;
    column-gap: 20px;
}


.nueva-plataforma{
    background-color:  rgb(27, 24, 24);
    display: grid;
    grid-template-columns: 10px auto;
    color: white;
    column-gap: 10px;
    width: 150px;
    border-radius: 30px;
    height: 30px;
}

.cirulo-verde{
    display: flex;
    align-items: center;
    align-self: center;
    align-content: center;
    height: 10px;
    border-radius: 100px;
}

.color-rojo{
    padding-right: 100px;
}

.color-verde{
    padding-top: 20px ;
    padding-left: 20px;
    padding-right: 20px;
    padding-bottom: 20px;
    background-color: rgb(27, 24, 24);
    box-shadow: 10px 10px 10px 5px red;
    border-radius: 20px;
    border: solid 0.04ch white;
    display: grid;
}


.usuario{
    background-color: red;
    height: 70px;
    display: grid;
    grid-template-columns: 60px auto 70px;
}

.usuario-saludos{
    display: grid;
    grid-template-rows: 20px 20px ;
}

.foto-comercial img{
    background-color: rgb(255, 145, 0);
    height: 300px;
    width: 100%;
}

.potencia-total{
    background: yellow;
}

.progreso-total{
    background-color: blue;
}
</style>

<script>
</script>