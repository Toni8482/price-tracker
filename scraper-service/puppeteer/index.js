/**
 * Servicio principal de Puppeteer
 * Punto de entrada unificado
 */

const { MAX_PAGES } = require("../config/constants");
const {
  initBrowser,
  closeBrowser,
  getNewPage,
  incrementActivePages,
  decrementActivePages,
  getActivePages,
} = require("./browser");
const { configurePage, waitForPageReady, navigateToUrl } = require("./page-config");
const { extractUrlsWithScroll } = require("./extractors/urls");
const { extractMultipleSelectors } = require("./extractors/selectors");
const { extractFullHtml } = require("./extractors/html");
const { processSiteSpecificData } = require("./sites");
const { wait } = require("./helpers/wait");

let activePages = 0;

/**
 * Función principal de scraping
 */
async function scrapeWithPuppeteer(url, selector, selectors) {
  // Control de concurrencia
  while (activePages >= MAX_PAGES) {
    await wait(500);
  }

  activePages++;
  let page = null;

  try {
    page = await getNewPage();
    await configurePage(page);
    await navigateToUrl(page, url);
    await waitForPageReady(page, selector || (selectors ? Object.values(selectors)[0] : null));
    
    // CASO 1: Múltiples selectores
    if (selectors && typeof selectors === "object") {
      //let data = await extractMultipleSelectors(page, selectors);
     
     let data = await processSiteSpecificData(url, page);
      
      await page.close();
      activePages--;
    
      return { type: "multi", data };
    }

    // CASO 2: HTML completo
    if (!selector) {
      const html = await extractFullHtml(page);
      
      await page.close();
      activePages--;
      
      return { type: "full", html };
    }

    // CASO 3: Extraer URLs con scroll
    const urls = await extractUrlsWithScroll(page, selector);

    await page.close();
    activePages--;
    
    return { type: "urls", count: urls.length, urls };

  } catch (error) {
    console.error("❌ Error en scraping:", error.message);
    if (page) {
      await page.close().catch(() => {});
    }
    activePages--;
    throw error;
  }
}

module.exports = {
  scrapeWithPuppeteer,
  initBrowser,
  closeBrowser,
  getActivePages: () => activePages,
  MAX_PAGES,
};