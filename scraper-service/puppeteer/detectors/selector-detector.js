/**
 * Detección automática de selectores
 */

/**
 * Selectores comunes para detectar productos
 */
const COMMON_SELECTORS = [
  'a[href*="/producto"]',
  'a[href*="/p-"]',
  'a.product-link',
  'a.product-item',
  '.product a',
  '.item a',
  'article a',
  '.card a',
  '.grid-item a',
  'a[class*="product"]',
  'a[class*="item"]',
];

/**
 * Detecta automáticamente selectores comunes para productos
 */
async function detectProductSelector(page) {
  for (const selector of COMMON_SELECTORS) {
    try {
      const exists = await page.evaluate((sel) => {
        return document.querySelectorAll(sel).length > 0;
      }, selector);
      
      if (exists) {
        console.log(`🔍 Selector detectado automáticamente: ${selector}`);
        return selector;
      }
    } catch (error) {
      // Continuar con siguiente selector
    }
  }
  
  return null;
}

module.exports = {
  detectProductSelector,
  COMMON_SELECTORS,
};