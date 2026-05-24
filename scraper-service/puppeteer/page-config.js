/**
 * Configuración de páginas de Puppeteer
 */

const { USER_AGENTS, HEADERS } = require("../config/constants");

/**
 * Configura una página con headers realistas y optimizaciones
 */
async function configurePage(page) {
  // User-Agent realista
  await page.setUserAgent(USER_AGENTS.CHROME_WINDOWS);

  // Headers HTTP
  await page.setExtraHTTPHeaders(HEADERS);

  // Configurar viewport
  await page.setViewport({
    width: 1920,
    height: 1080,
    deviceScaleFactor: 1,
  });

  // Interceptar solicitudes para bloquear recursos innecesarios
  await page.setRequestInterception(true);
  
  page.on("request", (request) => {
    const resourceType = request.resourceType();
    if (["image", "stylesheet", "font", "media"].includes(resourceType)) {
      request.abort().catch(() => {});
    } else {
      request.continue().catch(() => {});
    }
  });
}

/**
 * Espera a que la página cargue completamente
 */
async function waitForPageReady(page, selector = null) {
  await page.waitForFunction(
    () => document.readyState === "complete",
    { timeout: 10000 }
  );

  await page.waitForTimeout(2000);

  if (selector) {
    try {
      await page.waitForSelector(selector, { timeout: 10000 });
      console.log(`✅ Selector encontrado: ${selector}`);
      return true;
    } catch (error) {
      console.log(`⚠️ Selector no encontrado: ${selector}`);
      return false;
    }
  }
  
  return true;
}

/**
 * Navega a una URL con manejo de errores
 */
async function navigateToUrl(page, url) {
  console.log(`🌐 Navegando a: ${url}`);
  
  try {
    await page.goto(url, { 
      waitUntil: "networkidle2", 
      timeout: 30000 
    });
  } catch (error) {
    console.log("⚠️ Error en carga inicial, continuando...");
  }
}

module.exports = {
  configurePage,
  waitForPageReady,
  navigateToUrl,
};