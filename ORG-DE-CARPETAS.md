src/
├── assets/         <-- Imágenes, logos, archivos CSS globales o estilos.
├── components/     <-- Componentes reutilizables pequeños (Botones, Tarjetas, Inputs, Navbars).
├── views/          <-- Las "Páginas" principales de tu web (ej. HomeView.vue, LoginView.vue).
├── router/         <-- Configuración de las rutas (index.ts) para navegar entre views.
├── composables/    <-- Lógica reutilizable de Vue (ej. useAuth.ts, useFetch.ts).
├── DTO/            <-- ¡AQUÍ VAN TUS ARCHIVOS TS DE DATOS! (Modelos de datos / Interfaces).
├── services/       <-- ¡AQUÍ VA LA CONEXIÓN A PHP! (Funciones fetch/axios hacia el backend).
├── App.vue         <-- Componente raíz donde se monta toda la aplicación.
└── main.ts         <-- Archivo de entrada que arranca Vue y registra el Router.