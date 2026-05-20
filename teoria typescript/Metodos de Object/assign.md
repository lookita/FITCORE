# Object.assign() — El Fusionador

### ¿Qué es esto? (La analogía) 🧪
Imagina que tienes un batido de proteínas básico y le agregas creatina y fruta. El `Object.assign()` agarra un objeto y le "pega" las propiedades de otros objetos. Al final tienes un súper-objeto que tiene todo lo que tenían los anteriores.

### ¿Cuándo lo uso en ObsidiantFit? 🧬
- Cuando tienes los datos de un **Usuario** y quieres agregarle sus **Preferencias** (que están en otro objeto).
- Para crear una copia de un objeto sin romper el original (aunque hoy en día se usa más el spread `...`).
- Para actualizar varios datos de un perfil de una sola vez.

### ¿Cómo se usa? (Paso a paso)
```typescript
const usuario = { nombre: "Lucas" };
const extras = { nivel: "Pro", racha: 10 };

// Fusionamos 'extras' dentro de 'usuario'
Object.assign(usuario, extras);

console.log(usuario); 
// { nombre: "Lucas", nivel: "Pro", racha: 10 }
```

### El Truco del Experto 💡
⚠️ ¡Cuidado! `Object.assign` cambia el primer objeto que le pases. Si quieres crear uno nuevo sin tocar los viejos, usa: `Object.assign({}, obj1, obj2)`.
