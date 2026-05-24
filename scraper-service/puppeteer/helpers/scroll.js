/**
 * Funciones de scroll
 */

/**
 * Realiza scroll hasta el fondo de la página
 */
async function scrollToBottom(page) {
  await page.evaluate(() => {
    window.scrollTo(0, document.body.scrollHeight);
  });
}

/**
 * Scroll progresivo para cargar contenido dinámico
 */
async function progressiveScroll(page, options = {}) {
  const { distance = 100, interval = 100, maxScrolls = 50 } = options;
  
  await page.evaluate(async ({ distance, interval, maxScrolls }) => {
    let scrolls = 0;
    let lastHeight = document.body.scrollHeight;
    
    while (scrolls < maxScrolls) {
      window.scrollBy(0, distance);
      await new Promise(resolve => setTimeout(resolve, interval));
      
      const newHeight = document.body.scrollHeight;
      if (newHeight === lastHeight) break;
      
      lastHeight = newHeight;
      scrolls++;
    }
  }, { distance, interval, maxScrolls });
}

module.exports = {
  scrollToBottom,
  progressiveScroll,
};