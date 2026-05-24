/**
 * Extracción de HTML completo
 */

/**
 * Extrae el HTML completo de la página
 */
async function extractFullHtml(page) {
  const content = await page.content();
  console.log(`✅ HTML completo capturado, tamaño: ${content.length} bytes`);
  return content;
}

module.exports = { extractFullHtml };