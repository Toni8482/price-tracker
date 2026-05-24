/**
 * Gestión del navegador Puppeteer
 */

const puppeteer = require("puppeteer");
const { PUPPETEER_ARGS } = require("../config/constants");

let browser = null;
let activePages = 0;

/**
 * Inicializa el navegador Puppeteer
 */
async function initBrowser() {
  console.log("🚀 Inicializando navegador Puppeteer...");
  
  try {
    browser = await puppeteer.launch({
      headless: true,
      executablePath: "/usr/bin/chromium",
      args: PUPPETEER_ARGS,
    });
    console.log("✅ Navegador inicializado con Chromium del sistema");
  } catch (err) {
    console.error("⚠️ Error con chromium del sistema:", err.message);
    console.log("🔄 Intentando con versión descargada de Puppeteer...");
    
    browser = await puppeteer.launch({
      headless: true,
      args: PUPPETEER_ARGS,
    });
    console.log("✅ Navegador inicializado con versión descargada");
  }
}

/**
 * Cierra el navegador
 */
async function closeBrowser() {
  if (browser) {
    console.log("🛑 Cerrando navegador...");
    await browser.close();
    console.log("✅ Navegador cerrado correctamente");
  }
}

/**
 * Obtiene una nueva página
 */
async function getNewPage() {
  if (!browser) {
    throw new Error("Browser no inicializado");
  }
  return await browser.newPage();
}

/**
 * Incrementa/Decrementa contador de páginas activas
 */
function incrementActivePages() { activePages++; }
function decrementActivePages() { activePages--; }
function getActivePages() { return activePages; }

module.exports = {
  initBrowser,
  closeBrowser,
  getNewPage,
  incrementActivePages,
  decrementActivePages,
  getActivePages,
};