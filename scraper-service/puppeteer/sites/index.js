/**
 * Registro de sitios especiales
 */

const perfumesclub = require("./perfumesClub");
const perfumerias = require("./perfumerias");

const sites = {
  perfumesclub,
  perfumerias
};

/**
 * Procesa datos específicos del sitio si es necesario
 */
async function processSiteSpecificData(url, page) {
  let data = {
     url: String,
    titulo: String,
    marca: String,
    nombre: String,
    concentracion: String,
    descripcion: String,
    variantes: Array,
  };
  if (perfumesclub.isPerfumesClubUrl(url)) {
    await perfumesclub.extractPerfumesClub(page, data);
  //  await perfumesclub.extractPerfumesClubImages(page, data);
  }


  if (perfumerias.isPerfumeriasUrl(url)) {
    await perfumerias.extractPerfumerias(page,data);
  }
  return data;
}

module.exports = {
  processSiteSpecificData,
  sites,
};