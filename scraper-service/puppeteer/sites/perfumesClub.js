/**
 * Lógica específica para PerfumesClub.com
 */

async function extractPerfumesClub(page, data) {
  console.log("📖 Procesando datos de PerfumesClub...");

  const marca = "h1.titleProduct > a";
  const nombre = "h1.titleProduct > span";
  const descripcion = "div#descriptionPFCPropio";
  const concentracion = "h2.titleProduct";

  const newMarca = await page
    .$eval(marca, (marca) => marca.innerText?.trim() || "")
    .catch(() => null);
  console.log("📖 Marca: " + newMarca);

  const newNombre = await page
    .$eval(nombre, (nombre) => nombre.innerText?.trim() || "")
    .catch(() => null);
  console.log("📖 Nombre: " + newNombre);

  const newDescripcion = await page
    .$eval(descripcion, (descripcion) => descripcion.innerHTML || "")
    .catch(() => null);
  console.log("📖 Descripcion(HTML): " + newDescripcion);
  const newConcentracion = await page
    .$eval(
      concentracion,
      (concentracion) => concentracion.innerText?.trim() || "",
    )
    .catch(() => null);
  console.log("📖 Concentración: " + newConcentracion);

  const url = page.url();

  const titulo = await page.title();

  let variantes = await extractPerfumesClubImages(page);

  
  data.url = url;
  data.titulo = titulo;
  data.marca = newMarca;
  data.nombre = newNombre;
  data.concentracion = newConcentracion;
  data.descripcion = newDescripcion;
  data.variantes = variantes;
}
/**
 * Extrae imágenes de galería de PerfumesClub
 */
async function extractPerfumesClubImages(page) {
  console.log("🖼️ Procesando galería de imágenes de PerfumesClub...");
  let data = [];
  const radioSelector = "#divGrupo0 .radio";
  const imageSelector = "#pictureNewZoom > img";

  const radios = await page.$$(radioSelector);

  if (radios.length === 0) {
    console.log("⚠️ No se encontraron radios para galería de imágenes");
    return;
  }

  console.log(`📸 Encontradas ${radios.length} imágenes en galería`);

  for (let i = 0; i < radios.length; i++) {
    const radio = radios[i];
    /** 
    const oldSrc = await page
      .$eval(imageSelector, (img) => img.src)
      .catch(() => null);
*/
    await radio.evaluate((el) => el.click());
    /**
    try {
      await page.waitForFunction(
        (selector, old) => {
          const img = document.querySelector(selector);
          return img && img.src !== old;
        },
        { timeout: 5000 },
        imageSelector,
        oldSrc,
      );
    } catch (error) {
      console.log(`⚠️ La imagen no cambió después del click ${i + 1}`);
    }
 */
    await page.waitForTimeout(300);

    const newSrc = await page
      .$eval(imageSelector, (img) => img.src)
      .catch(() => null);

    const newPrecio = await page
      .$eval(
        `#divGrupo0 > div:nth-child(${i + 1}) > div > div > div.col-3.align-self-center.mainPrices.text-right > div.contPrecioNuevo`,
        (precio) => precio.innerText?.trim() || "",
      )
      .catch(() => null);

    const newCantidad = await page
      .$eval(
        `#divGrupo0 > div:nth-child(${i + 1}) > div > div > div.col-md-2.col-lg-2.col-xl-2.hackName.align-self-center > div`,
        (cantidad) => cantidad.innerText?.trim() || "",
      )
      .catch(() => null);

    if (newSrc) {
      data.push({
        imagen_url_contenido: newSrc,
        precio: newPrecio,
        cantidad: newCantidad,
      });
      console.log(`✅ Imagen ${i + 1}: ${newSrc.substring(0, 100)}`);
    }
  }

  console.log(`🖼️ Total imágenes extraídas: ${data?.length || 0}`);
  return data;
}

/**
 * Detecta si la URL pertenece a PerfumesClub
 */
function isPerfumesClubUrl(url) {
  return url.startsWith("https://www.perfumesclub.com");
}

module.exports = {
  extractPerfumesClub,
  extractPerfumesClubImages,
  isPerfumesClubUrl,
};
