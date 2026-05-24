/**
 * Constantes globales del servicio
 */

module.exports = {
  // Configuración de Puppeteer
  MAX_PAGES: 4,
  MAX_SCROLLS: 20,
  MAX_ITEMS: 3,
  TIMEOUT: {
    NAVIGATION: 30000,
    SELECTOR: 10000,
    WAIT_FOR_FUNCTION: 5000,
  },
  
  // User Agents
  USER_AGENTS: {
    CHROME_WINDOWS: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
    CHROME_MAC: "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
  },
  
  // Headers comunes
  HEADERS: {
    "Accept-Language": "es-ES,es;q=0.9,en;q=0.8",
    Accept: "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
    "Accept-Encoding": "gzip, deflate, br",
    Connection: "keep-alive",
    "Upgrade-Insecure-Requests": "1",
  },
  
  // Argumentos para Puppeteer
  PUPPETEER_ARGS: [
    "--no-sandbox",
    "--disable-setuid-sandbox",
    "--disable-dev-shm-usage",
    "--disable-gpu",
    "--disable-blink-features=AutomationControlled",
    "--disable-web-security",
    "--disable-features=IsolateOrigins,site-per-process",
  ],
};