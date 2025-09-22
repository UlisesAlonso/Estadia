# Directorio de Imágenes

## Logo de Cardio Vida

Para agregar el logo de la aplicación:

1. **Coloca tu imagen del logo** en este directorio con el nombre `logo.png`
2. **Formatos recomendados:** PNG, JPG, SVG
3. **Tamaño recomendado:** 200x200 píxeles o similar
4. **El logo se mostrará** en la página de login y en la navegación

### Ubicación del archivo:
```
public/images/logo.png
```

### Características del logo:
- Se mostrará automáticamente en la página de login
- Tamaño: 64px de altura (h-16)
- Responsive: se ajusta automáticamente
- Si no existe el archivo, se oculta automáticamente

### Ejemplo de uso:
```html
<img src="{{ asset('images/logo.png') }}" 
     alt="Cardio Vida Logo" 
     class="h-16 w-auto">
```

¡Una vez que agregues tu imagen, el logo aparecerá automáticamente en la interfaz! 