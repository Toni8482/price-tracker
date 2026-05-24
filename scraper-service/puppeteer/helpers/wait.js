/**
 * Funciones de espera
 */

/**
 * Espera un tiempo determinado
 */
function wait(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

/**
 * Espera a que la página tenga un contenedor de productos
 */
async function waitForProductsContainer(page, maxAttempts = 10) {
  for (let i = 0; i < maxAttempts; i++) {
    const hasProducts = await page.evaluate(() => {
      return document.body.innerText.length > 100;
    });
    
    if (hasProducts) return true;
    await wait(1000);
  }
  return false;
}

module.exports = {
  wait,
  waitForProductsContainer,
};