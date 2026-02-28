const express = require("express");
const puppeteer = require("puppeteer");
const app = express();

app.use(express.json());
const MAX_PAGES = 4;
let activePages = 0;
let browser;

async function initBrowser() {
  console.log("🚀 Inicializando navegador Puppeteer...");
  try {
    browser = await puppeteer.launch({
      headless: true,
      executablePath: "/usr/bin/chromium",
      args: [
        "--no-sandbox",
        "--disable-setuid-sandbox",
        "--disable-dev-shm-usage",
        "--disable-gpu",
        "--disable-blink-features=AutomationControlled",
      ],
    });
    console.log("✅ Navegador inicializado correctamente");
  } catch (err) {
    console.error("⚠️ Error con chromium del sistema:", err.message);
    console.error("Intentando con Puppeteer descargado...");
    browser = await puppeteer.launch({
      headless: true,
      args: [
        "--no-sandbox",
        "--disable-setuid-sandbox",
        "--disable-dev-shm-usage",
        "--disable-gpu",
        "--disable-blink-features=AutomationControlled",
      ],
    });
    console.log("✅ Navegador inicializado con versión descargada");
  }
}

app.post("/scrape", async (req, res) => {
  try {
    const { url, selector, selectors } = req.body;

    if (!url) {
      return res.status(400).json({ error: "URL requerida" });
    }

    while (activePages >= MAX_PAGES) {
      await new Promise((r) => setTimeout(r, 500));
    }

    activePages++;
    console.log(`📍 Scrapeando: ${url}`);
    const page = await browser.newPage();

    // Configurar headers realistas
    await page.setUserAgent(
      "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
    );

    await page.setExtraHTTPHeaders({
      "Accept-Language": "es-ES,es;q=0.9,en;q=0.8",
      Accept:
        "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
    });

    // Habilitar interceptación de solicitudes
    await page.setRequestInterception(true);

    // Bloquear recursos innecesarios para acelerar
    page.on("request", (request) => {
      if (["image", "stylesheet", "font"].includes(request.resourceType())) {
        request.abort().catch(() => {});
      } else {
        request.continue().catch(() => {});
      }
    });

    // Aumentar timeout y esperar a que cargue
    await page.goto(url, { waitUntil: "networkidle2", timeout: 30000 });

    // Esperar un poco más para que JavaScript se ejecute
    await page.waitForTimeout(5000);

    if (selector) {
      await page.waitForSelector(selector, { timeout: 10000 }).catch(() => {
        console.log(`⚠️ Selector no encontrado: ${selector}`);
      });
    }

    // ===============================
    // MULTI-SELECTORES
    // ===============================
    if (selectors && typeof selectors === "object") {
      // 1️⃣ ESPERAR A QUE LOS SELECTORES EXISTAN
      for (const sel of Object.values(selectors)) {
        await page.waitForSelector(sel, { timeout: 50000 }).catch(() => {
          console.log("⚠️ Selector no encontrado:", sel);
        });
      }

      // 2️⃣ LEER LOS DATOS
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
        return result;
      }, selectors);

      await page.close();
      activePages--;
      // 3️⃣ DEVOLVER
      return res.json({
        success: true,
        type: "multi",
        data,
      });
    }

    // Obtener el HTML completo si no hay selector
    let content;
    if (!selector) {
      content = await page.content();
      console.log(
        `✅ HTML completo capturado, tamaño: ${content.length} bytes`,
      );
      await page.close();
      activePages--;
      res.json({ success: true, html: content, type: "full" });
    } else {
      const MAX_ITEMS = 50;

      console.log("📍 Scrapeando:", url);

      // 1️⃣ Sincronización inicial (MUY IMPORTANTE)
      await page.evaluate(() => ({
        scrollHeight: document.body.scrollHeight,
        innerHeight: window.innerHeight,
      }));

      let lastCount = 0;
      let stableRounds = 0;

      // 2️⃣ Scroll hasta estabilidad o llegar a 150
      while (true) {
        await page.evaluate(() => {
          window.scrollTo(0, document.body.scrollHeight);
        });

        await page.waitForTimeout(2500);

        const count = await page.evaluate(
          (sel) => document.querySelectorAll(sel).length,
          selector,
        );

        console.log(`🔄 Elementos detectados: ${count}`);

        if (count >= MAX_ITEMS) break;

        if (count === lastCount) {
          stableRounds++;
          if (stableRounds >= 2) break;
        } else {
          stableRounds = 0;
        }

        lastCount = count;
      }

      // 3️⃣ Extraer SOLO las primeras 100 URLs únicas
      const urls = await page.evaluate(
        (sel, limit) => {
          const links = Array.from(document.querySelectorAll(sel))
            .map((el) => el.href)
            .filter(Boolean);

          return [...new Set(links)].slice(0, limit);
        },
        selector,
        MAX_ITEMS,
      );

      await page.close();

      console.log(`✅ URLs finales extraídas: ${urls.length}`);
      urls.slice(0, 5).forEach((u, i) => {
        console.log(`🔗 ${i + 1}: ${u}`);
      });
      activePages--;
      res.json({
        success: true,
        type: "urls",
        count: urls.length,
        urls,
      });
    }
  } catch (error) {
    console.error("❌ Error en scraping:", error.message);
    res.status(500).json({ error: error.message });
  }
});

app.get("/health", (req, res) => {
  activePages--;
  res.json({ status: "ok", message: "Scraper service activo" });
});

process.on("SIGTERM", async () => {
  console.log("🛑 Cerrando navegador...");
  if (browser) {
    await browser.close();
  }
  process.exit(0);
});

async function start() {
  try {
    await initBrowser();
    const PORT = process.env.PORT || 3000;
    app.listen(PORT, "0.0.0.0", () => {
      console.log(`🚀 Scraper service escuchando en puerto ${PORT}`);
    });
  } catch (err) {
    console.error("❌ Error al iniciar:", err);
    process.exit(1);
  }
}

start();
