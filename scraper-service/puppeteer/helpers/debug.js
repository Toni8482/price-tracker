/**
 * Funciones de depuración
 */

/**
 * Extrae información de depuración de la página
 */
async function debugPageInfo(page) {
  const info = await page.evaluate(() => {
    return {
      title: document.title,
      url: window.location.href,
      totalLinks: document.querySelectorAll('a').length,
      bodyText: document.body.innerText.length,
      hasProducts: document.body.innerText.toLowerCase().includes('producto'),
    };
  });
  
  console.log(`📄 Info página: "${info.title}"`);
  console.log(`🔗 Total enlaces: ${info.totalLinks}`);
  console.log(`📝 Longitud texto: ${info.bodyText}`);
  console.log(`🛍️ Contiene "producto": ${info.hasProducts}`);
  
  return info;
}

/**
 * Muestra ejemplos de enlaces encontrados
 */
async function showSampleLinks(page, limit = 10) {
  const sampleLinks = await page.evaluate((limit) => {
    return Array.from(document.querySelectorAll('a'))
      .slice(0, limit)
      .map(a => ({ href: a.href, text: a.text?.substring(0, 50) }));
  }, limit);
  
  console.log("📎 Ejemplos de enlaces encontrados:");
  sampleLinks.forEach((link, i) => {
    if (link.href) console.log(`   ${i + 1}. ${link.href.substring(0, 100)}`);
  });
}

module.exports = {
  debugPageInfo,
  showSampleLinks,
};