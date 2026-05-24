/**
 * Extracción de múltiples selectores
 */

/**
 * Extrae múltiples selectores de una página
 */
async function extractMultipleSelectors(page, selectors) {
  // Esperar a que todos los selectores estén disponibles
  for (const [key, sel] of Object.entries(selectors)) {
    console.log(`🔍 Esperando selector "${key}": ${sel}`);
    await page.waitForSelector(sel, { timeout: 10000 }).catch(() => {
      console.log(`⚠️ Selector no encontrado: ${key} -> ${sel}`);
    });
  }

  // Extraer datos
  const data = await page.evaluate((selectors) => {
    const result = {};

    for (const key in selectors) {
      const sel = selectors[key];
      const elements = document.querySelectorAll(sel);

      result[key] = Array.from(elements).map((el) => ({
        text: el.innerText?.trim() || "",
        url: el.href || "",
        src: el.getAttribute("src") || "",
        html: el.innerHTML || "",
      }));
    }
    result["url"] = window.location.href;
    result["title"] = document.title;
    return result;
  }, selectors);

  console.log(`✅ Extraídos datos para ${Object.keys(selectors).length} selectores`);
  return data;
}

module.exports = { extractMultipleSelectors };