const express = require('express');
const puppeteer = require('puppeteer');
const app = express();

app.use(express.json());

let browser;

async function initBrowser() {
  console.log('🚀 Inicializando navegador Puppeteer...');
  try {
    browser = await puppeteer.launch({
      headless: true,
      executablePath: '/usr/bin/chromium',
      args: [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
        '--disable-gpu',
        '--disable-blink-features=AutomationControlled'
      ]
    });
    console.log('✅ Navegador inicializado correctamente');
  } catch (err) {
    console.error('⚠️ Error con chromium del sistema:', err.message);
    console.error('Intentando con Puppeteer descargado...');
    browser = await puppeteer.launch({
      headless: true,
      args: [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
        '--disable-gpu',
        '--disable-blink-features=AutomationControlled'
      ]
    });
    console.log('✅ Navegador inicializado con versión descargada');
  }
}

app.post('/scrape', async (req, res) => {
  try {
    const { url, selector } = req.body;
    
    if (!url) {
      return res.status(400).json({ error: 'URL requerida' });
    }

    console.log(`📍 Scrapeando: ${url}`);
    const page = await browser.newPage();
    
    // Configurar headers realistas
    await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    
    await page.setExtraHTTPHeaders({
      'Accept-Language': 'es-ES,es;q=0.9,en;q=0.8',
      'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
    });
    
    // Habilitar interceptación de solicitudes
    await page.setRequestInterception(true);
    
    // Bloquear recursos innecesarios para acelerar
    page.on('request', request => {
      if (['image', 'stylesheet', 'font'].includes(request.resourceType())) {
        request.abort().catch(() => {});
      } else {
        request.continue().catch(() => {});
      }
    });
    
    // Aumentar timeout y esperar a que cargue
    await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
    
    // Esperar un poco más para que JavaScript se ejecute
    await page.waitForTimeout(5000);
    
    if (selector) {
      await page.waitForSelector(selector, { timeout: 10000 }).catch(() => {
        console.log(`⚠️ Selector no encontrado: ${selector}`);
      });
    }
    
    // Obtener el HTML completo si no hay selector
    let content;
    if (!selector) {
      content = await page.content();
      console.log(`✅ HTML completo capturado, tamaño: ${content.length} bytes`);
      await page.close();
      res.json({ success: true, html: content, type: 'full' });
    } else {
      const data = await page.evaluate((sel) => {
        const elements = sel ? document.querySelectorAll(sel) : [document.body];
        return Array.from(elements).map(el => ({
          text: el.innerText || '',
          html: el.innerHTML || '',
          classes: el.className || ''
        }));
      }, selector);

      await page.close();
      
      console.log(`✅ Scraping exitoso: ${data.length} elementos encontrados`);
      res.json({ success: true, data, count: data.length, type: 'elements' });
    }
  } catch (error) {
    console.error('❌ Error en scraping:', error.message);
    res.status(500).json({ error: error.message });
  }
});

app.get('/health', (req, res) => {
  res.json({ status: 'ok', message: 'Scraper service activo' });
});

process.on('SIGTERM', async () => {
  console.log('🛑 Cerrando navegador...');
  if (browser) {
    await browser.close();
  }
  process.exit(0);
});

async function start() {
  try {
    await initBrowser();
    const PORT = process.env.PORT || 3000;
    app.listen(PORT, '0.0.0.0', () => {
      console.log(`🚀 Scraper service escuchando en puerto ${PORT}`);
    });
  } catch (err) {
    console.error('❌ Error al iniciar:', err);
    process.exit(1);
  }
}

start();
