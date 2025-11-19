Fetch RAWG covers
=================

Este script ayuda a descargar imágenes (carátulas / renders) de la API RAWG para poblar `public/assets/products/`.

Requisitos
---------
- PHP CLI (ejecutar `php -v` para comprobar)
- Conexión a Internet
- API Key de RAWG: https://rawg.io/apidocs

Pasos
-----
1) Regístrate en RAWG y obtén una API key según sus indicaciones: https://rawg.io/apidocs

2) Exporta la variable de entorno RAWG_API_KEY en tu sistema.
   - En PowerShell (Windows):

```powershell
setx RAWG_API_KEY "tu_api_key_aqui"
# Cierra y vuelve a abrir PowerShell para que la variable quede disponible
```

   - En Linux/macOS (bash):

```bash
export RAWG_API_KEY="tu_api_key_aqui"
```

3) Ejecuta el script desde la raíz del proyecto:

```powershell
php scripts/fetch_rawg_covers.php
```

Qué hace el script
------------------
- Busca cada nombre de juego en RAWG (primera coincidencia) y obtiene el campo `background_image`.
- Descarga esa imagen y la guarda en `public/assets/products/` con el nombre indicado en el array interno.

Personalización
----------------
- Edita `scripts/fetch_rawg_covers.php` para cambiar el mapeo `filename => game name` o añadir/remover juegos.
- Si quieres usar carátulas oficiales o imágenes específicas, puedes reemplazar las URLs manualmente o modificar el script para aceptar una lista de URLs.

Alternativa: IGDB / Steam
------------------------
- IGDB ofrece carátulas y es muy completa, pero requiere autenticación con Twitch (más pasos). Si prefieres IGDB, puedo preparar un script equivalente.
- Para imágenes estrictamente oficiales (box art de editores) lo ideal es solicitar press kits o usar los recursos proporcionados por los distribuidores.

Notas legales
-------------
- Aunque RAWG facilita metadatos y enlaces a imágenes, las carátulas y renders pueden estar bajo copyright. Para fines educativos y prototipos locales suele ser aceptable, pero revisa condiciones si el proyecto se publica o comercializa.

Si quieres, puedo:
- (A) Ejecutar el script yo mismo y guardar las imágenes en el repo (necesito permiso explícito para ejecutar comandos en tu entorno y/o que me confirmes que puedo usar `run_in_terminal`).
- (B) Preparar un script IGDB en vez de RAWG si prefieres esa base de datos.
- (C) Añadir lógica para bajar versiones grandes y generar thumbnails (WebP) automáticamente.

Dime cómo quieres proceder.