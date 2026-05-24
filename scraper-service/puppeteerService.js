/**
 * Servicio de Puppeteer para scraping web
 * Maneja toda la lógica de navegación y extracción de datos
 * VERSIÓN MEJORADA - Mayor robustez y mejor manejo de páginas dinámicas
 */

const puppeteer = require("puppeteer");

// Configuración global
const MAX_PAGES = 4;           // Máximo de páginas simultáneas
let browser = null;            // Instancia global del navegador
let activePages = 0;           // Contador de páginas activas en Puppeteer

/**
 * Inicializa el navegador Puppeteer
 * Intenta usar Chromium del sistema, fallback a versión descargada
 */
async function initBrowser() {
  console.log("🚀 Inicializando navegador Puppeteer...");
  
  try {
    // Intentar con Chromium del sistema
    browser = await puppeteer.launch({
      headless: true,          // Modo sin interfaz gráfica
      executablePath: "/usr/bin/chromium",
      args: [
        "--no-sandbox",                      // Necesario en entornos Docker/container
        "--disable-setuid-sandbox",          // Seguridad para entornos restringidos
        "--disable-dev-shm-usage",           // Evita problemas de memoria compartida
        "--disable-gpu",                     // Deshabilitar GPU (innecesario en headless)
        "--disable-blink-features=AutomationControlled", // Evitar detección de bot
        "--disable-web-security",             // Ayuda con algunos sitios
        "--disable-features=IsolateOrigins,site-per-process",
      ],
    });
    console.log("✅ Navegador inicializado con Chromium del sistema");
  } catch (err) {
    // Fallback a la versión de Puppeteer
    console.error("⚠️ Error con chromium del sistema:", err.message);
    console.log("🔄 Intentando con versión descargada de Puppeteer...");
    
    browser = await puppeteer.launch({
      headless: true,
      args: [
        "--no-sandbox",
        "--disable-setuid-sandbox",
        "--disable-dev-shm-usage",
        "--disable-gpu",
        "--disable-blink-features=AutomationControlled",
        "--disable-web-security",
        "--disable-features=IsolateOrigins,site-per-process",
      ],
    });
    console.log("✅ Navegador inicializado con versión descargada");
  }
}

/**
 * Cierra el navegador de manera graceful
 */
async function closeBrowser() {
  if (browser) {
    console.log("🛑 Cerrando navegador...");
    await browser.close();
    console.log("✅ Navegador cerrado correctamente");
  }
}

/**
 * Configura una página con headers realistas y optimizaciones
 * @param {Object} page - Página de Puppeteer
 */
async function configurePage(page) {
  // User-Agent realista (Chrome en Windows)
  await page.setUserAgent(
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
  );

  // Headers HTTP realistas
  await page.setExtraHTTPHeaders({
    "Accept-Language": "es-ES,es;q=0.9,en;q=0.8",
    Accept: "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
    "Accept-Encoding": "gzip, deflate, br",
    "Connection": "keep-alive",
    "Upgrade-Insecure-Requests": "1",
  });

  // Configurar viewport
  await page.setViewport({
    width: 1920,
    height: 1080,
    deviceScaleFactor: 1,
  });

  // Interceptar solicitudes para bloquear recursos innecesarios
  await page.setRequestInterception(true);
  
  page.on("request", (request) => {
    // Bloquear recursos pesados pero permitir JS que puede ser necesario
    const resourceType = request.resourceType();
    if (["image", "stylesheet", "font", "media"].includes(resourceType)) {
      request.abort().catch(() => {});
    } else {
      request.continue().catch(() => {});
    }
  });
}

/**
 * Espera a que la página cargue completamente y los elementos estén disponibles
 * @param {Object} page - Página de Puppeteer
 * @param {string} selector - Selector opcional para esperar
 */
async function waitForPageReady(page, selector = null) {
  // Esperar a que la página esté en estado "complete"
  await page.waitForFunction(
    () => document.readyState === "complete",
    { timeout: 10000 }
  );

  // Esperar un tiempo adicional para contenido dinámico
  await page.waitForTimeout(2000);

  // Si hay selector específico, esperarlo
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
 * Detecta automáticamente selectores comunes para productos/enlaces
 * @param {Object} page - Página de Puppeteer
 * @returns {string|null} Selector encontrado o null
 */
async function detectProductSelector(page) {
  const commonSelectors = [
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

  for (const selector of commonSelectors) {
    try {
      const exists = await page.evaluate((sel) => {
        const elements = document.querySelectorAll(sel);
        return elements.length > 0;
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

/**
 * Extrae información de depuración de la página
 * @param {Object} page - Página de Puppeteer
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
 * Extrae URLs de una página con scroll dinámico y detección automática
 * @param {Object} page - Página de Puppeteer
 * @param {string} selector - Selector CSS opcional (si no se proporciona, se detecta)
 * @param {number} maxItems - Número máximo de URLs a extraer
 * @returns {Array} Lista de URLs únicas
 */
async function extractUrlsWithScroll(page, selector, maxItems = 100) {
  // Si no hay selector, intentar detectarlo automáticamente
  if (!selector) {
    selector = await detectProductSelector(page);
    if (!selector) {
      console.log("⚠️ No se pudo detectar selector automáticamente");
      
      // Mostrar información de depuración
      await debugPageInfo(page);
      
      // Mostrar algunos enlaces de ejemplo
      const sampleLinks = await page.evaluate(() => {
        const links = Array.from(document.querySelectorAll('a'))
          .slice(0, 10)
          .map(a => ({ href: a.href, text: a.text?.substring(0, 50) }));
        return links;
      });
      
      console.log("📎 Ejemplos de enlaces encontrados:");
      sampleLinks.forEach((link, i) => {
        if (link.href) console.log(`   ${i + 1}. ${link.href.substring(0, 100)}`);
      });
      
      throw new Error("No se encontró un selector válido para extraer URLs");
    }
  }
  
  console.log(`🔄 Iniciando scroll con selector: ${selector}`);
  console.log(`🎯 Objetivo: extraer hasta ${maxItems} URLs`);

  let lastCount = 0;
  let stableRounds = 0;
  let scrollAttempts = 0;
  const MAX_SCROLLS = 20; // Límite de scrolls para evitar loops infinitos

  // Scroll hasta alcanzar el límite o estabilizarse
  while (scrollAttempts < MAX_SCROLLS) {
    // Scroll al fondo
    await page.evaluate(() => {
      window.scrollTo(0, document.body.scrollHeight);
    });

    await page.waitForTimeout(2500); // Esperar a que carguen nuevos elementos

    // Contar elementos actuales
    const count = await page.evaluate(
      (sel) => document.querySelectorAll(sel).length,
      selector
    );

    console.log(`🔄 Elementos detectados: ${count} (scroll ${scrollAttempts + 1}/${MAX_SCROLLS})`);

    // Verificar si alcanzamos el límite
    if (count >= maxItems) {
      console.log(`🎯 Alcanzado límite de ${maxItems} elementos`);
      break;
    }

    // Verificar si el conteo se estabilizó
    if (count === lastCount) {
      stableRounds++;
      if (stableRounds >= 3) {
        console.log(`📊 Conteo estabilizado en ${count} elementos después de ${scrollAttempts + 1} scrolls`);
        break;
      }
    } else {
      stableRounds = 0;
    }

    lastCount = count;
    scrollAttempts++;
  }

  // Extraer URLs únicas hasta el límite
  const urls = await page.evaluate(
    (sel, limit) => {
      const links = Array.from(document.querySelectorAll(sel))
        .map((el) => el.href)
        .filter(href => href && href !== '' && !href.startsWith('javascript:') && !href.startsWith('#'));
      
      // Eliminar duplicados y limitar
      return [...new Set(links)].slice(0, limit);
    },
    selector,
    maxItems
  );

  console.log(`✅ URLs finales extraídas: ${urls.length}`);
  
  if (urls.length === 0) {
    console.log("⚠️ No se encontraron URLs válidas. Verificando estructura de la página...");
    await debugPageInfo(page);
  } else {
    urls.slice(0, 5).forEach((u, i) => {
      console.log(`🔗 ${i + 1}: ${u.substring(0, 100)}`);
    });
  }

  return urls;
}

/**
 * Extrae múltiples selectores de una página
 * @param {Object} page - Página de Puppeteer
 * @param {Object} selectors - Objeto con selectores a extraer
 * @returns {Object} Datos extraídos
 */
async function extractMultipleSelectors(page, selectors) {
  // Esperar a que todos los selectores estén disponibles
  for (const [key, sel] of Object.entries(selectors)) {
    console.log(`🔍 Esperando selector "${key}": ${sel}`);
    await page.waitForSelector(sel, { timeout: 10000 }).catch(() => {
      console.log(`⚠️ Selector no encontrado: ${key} -> ${sel}`);
    });
  }

  // Extraer datos de cada selector
  const data = await page.evaluate((selectors) => {
    const result = {};

    for (const key in selectors) {
      const sel = selectors[key];
      const elements = document.querySelectorAll(sel);

      result[key] = Array.from(elements).map((el) => ({
        text: el.innerText?.trim() || "",
        url: el.href || "",
        src: el.getAttribute("src") || "",
        html: el.innerHTML?.substring(0, 500) || "", // Limitar HTML para no saturar
      }));
    }
    result["url"] = window.location.href;
    result["title"] = document.title;
    return result;
  }, selectors);

  console.log(`✅ Extraídos datos para ${Object.keys(selectors).length} selectores`);
  return data;
}

/**
 * Caso especial para perfumesclub.com - extrae imágenes de galería
 * @param {Object} page - Página de Puppeteer
 * @param {Object} data - Datos ya extraídos
 */
async function extractPerfumesClubImages(page, data) {
  console.log("🖼️ Procesando galería de imágenes de PerfumesClub...");
  
  const radioSelector = "#divGrupo0 .radio";
  const imageSelector = "#pictureNewZoom > img";
  
  const radios = await page.$$(radioSelector);
  
  if (radios.length === 0) {
    console.log("⚠️ No se encontraron radios para galería de imágenes");
    return;
  }
  
  console.log(`📸 Encontradas ${radios.length} imágenes en galería`);

  for (let i = 0; i < radios.length; i++) {
    const radio = radios[i];
    const oldSrc = await page
      .$eval(imageSelector, (img) => img.src)
      .catch(() => null);

    // Click en el div.radio para cambiar imagen
    await radio.evaluate((el) => el.click());

    // Esperar a que la imagen cambie
    try {
      await page.waitForFunction(
        (selector, old) => {
          const img = document.querySelector(selector);
          return img && img.src !== old;
        },
        { timeout: 5000 },
        imageSelector,
        oldSrc
      );
    } catch (error) {
      console.log(`⚠️ La imagen no cambió después del click ${i + 1}`);
    }

    await page.waitForTimeout(300);

    const newSrc = await page
      .$eval(imageSelector, (img) => img.src)
      .catch(() => null);

    if (newSrc && newSrc !== oldSrc) {
      if (!data["imagen_url_contenido"]) {
        data["imagen_url_contenido"] = [];
      }
      data["imagen_url_contenido"].push({ src: newSrc });
      console.log(`✅ Imagen ${i + 1}: ${newSrc.substring(0, 100)}`);
    }
  }

  console.log(`🖼️ Total imágenes extraídas: ${data["imagen_url_contenido"]?.length || 0}`);
}

/**
 * Función principal de scraping
 * @param {string} url - URL a scrapear
 * @param {string} selector - Selector opcional para extraer URLs
 * @param {Object} selectors - Selectores múltiples opcionales
 * @returns {Object} Resultado del scraping
 */
async function scrapeWithPuppeteer(url, selector, selectors) {
  // Control de concurrencia a nivel de Puppeteer
  while (activePages >= MAX_PAGES) {
    await new Promise((resolve) => setTimeout(resolve, 500));
  }

  activePages++;
  let page = null;

  try {
    // Crear nueva página
    page = await browser.newPage();
    await configurePage(page);

    // Navegar a la URL
    console.log(`🌐 Navegando a: ${url}`);
    
    try {
      await page.goto(url, { 
        waitUntil: "networkidle2", 
        timeout: 30000 
      });
    } catch (error) {
      console.log("⚠️ Error en carga inicial, continuando...");
    }
    
    // Esperar a que la página esté lista
    await waitForPageReady(page, selector || (selectors ? Object.values(selectors)[0] : null));
    
    // ===============================
    // CASO 1: Múltiples selectores
    // ===============================
    if (selectors && typeof selectors === "object") {
      const data = await extractMultipleSelectors(page, selectors);

      // Caso especial para perfumesclub.com
      if (url.startsWith("https://www.perfumesclub.com")) {
        await extractPerfumesClubImages(page, data);
      }

      await page.close();
      activePages--;
      
      return {
        type: "multi",
        data,
      };
    }

    // ===============================
    // CASO 2: HTML completo
    // ===============================
    if (!selector) {
      const content = await page.content();
      console.log(`✅ HTML completo capturado, tamaño: ${content.length} bytes`);
      
      await page.close();
      activePages--;
      
      return {
        type: "full",
        html: content,
      };
    }

    // ===============================
    // CASO 3: Extraer URLs con scroll
    // ===============================
    const MAX_ITEMS = 100;
    const urls = await extractUrlsWithScroll(page, selector, MAX_ITEMS);

    await page.close();
    activePages--;
    
    return {
      type: "urls",
      count: urls.length,
      urls,
    };

  } catch (error) {
    console.error("❌ Error en scraping:", error.message);
    if (page) {
      try {
        await page.close();
      } catch (closeError) {
        console.error("Error cerrando página:", closeError.message);
      }
    }
    activePages--;
    throw error;
  }
}

// Exportar funciones y constantes
module.exports = {
  scrapeWithPuppeteer,
  initBrowser,
  closeBrowser,
  getActivePages: () => activePages,
  MAX_PAGES,
};