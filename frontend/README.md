# Frontend

This template should help get you started developing with Vue 3 in Vite.

## Recommended IDE Setup

[VS Code](https://code.visualstudio.com/) + [Vue (Official)](https://marketplace.visualstudio.com/items?itemName=Vue.volar) (and disable Vetur).

## Recommended Browser Setup

- Chromium-based browsers (Chrome, Edge, Brave, etc.):
  - [Vue.js devtools](https://chromewebstore.google.com/detail/vuejs-devtools/nhdogjmejiglipccpnnnanhbledajbpd)
  - [Turn on Custom Object Formatter in Chrome DevTools](http://bit.ly/object-formatters)
- Firefox:
  - [Vue.js devtools](https://addons.mozilla.org/en-US/firefox/addon/vue-js-devtools/)
  - [Turn on Custom Object Formatter in Firefox DevTools](https://fxdx.dev/firefox-devtools-custom-object-formatters/)

## Type Support for `.vue` Imports in TS

TypeScript cannot handle type information for `.vue` imports by default, so we replace the `tsc` CLI with `vue-tsc` for type checking. In editors, we need [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) to make the TypeScript language service aware of `.vue` types.

## Customize configuration

See [Vite Configuration Reference](https://vite.dev/config/).

## Project Setup

```sh
npm install
```

### Conexión con el backend PHP

En desarrollo, las peticiones a `/Backend` se envían mediante el proxy de Vite a
`http://127.0.0.1:80/Backend`. Inicia Apache y asegúrate de que la carpeta
`Backend` esté dentro del `DocumentRoot` configurado en Apache.

Si Apache escucha en otro host o puerto, crea `Frontend/.env.local` con:

```env
VITE_BACKEND_ORIGIN=http://127.0.0.1:8080
```

`VITE_BACKEND_ORIGIN` es el origen de Apache, sin `/Backend`. Para producción,
`VITE_API_BASE_URL` puede definir la ruta completa de la API; si no se define,
se usa `/Backend` en el mismo origen del frontend.

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Type-Check, Compile and Minify for Production

```sh
npm run build
```
