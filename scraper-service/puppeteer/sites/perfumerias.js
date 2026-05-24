/**
 * Lógica específica para Perfumerias.com
 */

async function extractPerfumerias(page, data) {
  console.log("📖 Procesando datos de Perfumerias...");

  const marca = "div.marca > a";
  const nombre = "div.nombre";
  const precios = "div.col-xs-4.col-sm-4.col-md-3.col-lg-2 > div.precio";
  const urlImagen = "img#imagen_principal";
  const descripcion = "div.tab-pane.active";
  const concentracion = "div.subtitulo";
  const contenidos = "div.nombre_corto";
  const imgUrlContenidos = "img.img-responsive.img_mini.img_propia";

  const newMarca = await page
    .$eval(marca, (marca) => marca.innerText?.trim() || "")
    .catch(() => null);

  const newNombre = await page
    .$eval(nombre, (nombre) => nombre.innerText?.trim() || "")
    .catch(() => null);

  const newDescripcion = await page
    .$eval(descripcion, (descripcion) => descripcion.innerHTML.trim() || "")
    .catch(() => null);

  const newConcentracion = await page
    .$eval(
      concentracion,
      (concentracion) => concentracion.innerText?.trim() || "",
    )
    .catch(() => null);
  const url = page.url();

  const titulo = await page.title();

  const precio = await page.$$(precios);
  const imgUrlContenido = await page.$$(imgUrlContenidos);
  const contenido = await page.$$(contenidos);

  const length = Math.min(
    precio.length,
    contenido.length,
    imgUrlContenido.length,
  );

  let variantes = [];

  for (let i = 0; i < length; i++) {
    let newPrecio = await precio[i]
      .evaluate((el) => el.innerText?.trim() || "")
      .catch(() => null);

    let newContenido = await contenido[i]
      .evaluate((el) => el.innerText?.trim() || "")
      .catch(() => null);

    let newImg = await imgUrlContenido[i]
      .evaluate((el) => el.src || "")
      .catch(() => null);

    variantes.push({
      imagen_url_contenido: newImg,
      precio: newPrecio,
      cantidad: newContenido,
    });
  }


  data.url = url;
  data.titulo = titulo;
  data.marca = newMarca;
  data.nombre = newNombre;
  data.concentracion = newConcentracion;
  data.descripcion = newDescripcion;
  data.variantes = variantes;
 
}

/**
 * Detecta si la URL pertenece a Perfumerias
 */
function isPerfumeriasUrl(url) {
  return url.startsWith("https://perfumerias.com");
}

module.exports = {
  extractPerfumerias,
  isPerfumeriasUrl,
};
