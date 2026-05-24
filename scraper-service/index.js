/**
 * Servidor Express para el servicio de scraping
 * Maneja las rutas HTTP y la gestión de peticiones
 */

const express = require("express");
const { scrapeWithPuppeteer, initBrowser, closeBrowser, getActivePages, MAX_PAGES } = require("./puppeteer/index.js");

const app = express();

// Middleware para parsear JSON
app.use(express.json());

// Variable para controlar páginas activas en este servidor
let activePages = 0;

/**
 * Endpoint principal de scraping
 * POST /scrape
 * Body: { url, selector, selectors }
 */
app.post("/scrape", async (req, res) => {
  try {
    const { url, selector, selectors } = req.body;

    // Validación básica
    if (!url) {
      return res.status(400).json({ error: "URL requerida" });
    }

    // Control de concurrencia - límite de páginas simultáneas
    while (activePages >= MAX_PAGES) {
      await new Promise((resolve) => setTimeout(resolve, 500));
    }

    // Incrementar contador de páginas activas
    activePages++;
    console.log(`📊 Páginas activas: ${activePages}/${MAX_PAGES}`);
    console.log(`📍 Procesando: ${url}`);

    // Llamar al servicio de Puppeteer
    const result = await scrapeWithPuppeteer(url, selector, selectors);

    // Decrementar contador al finalizar
    activePages--;
    console.log(`✅ Procesado: ${url} - Tipo: ${result.type}`);

    // Devolver respuesta exitosa
    res.json({
      success: true,
      ...result
    });

  } catch (error) {
    console.error("❌ Error en scraping:", error.message);
    activePages--; // Asegurar decremento incluso en error
    res.status(500).json({ 
      success: false,
      error: error.message 
    });
  }
});

/**
 * Endpoint de verificación de salud
 * GET /health
 */
app.get("/health", (req, res) => {
  res.json({ 
    status: "ok", 
    message: "Scraper service activo",
    activePages,
    maxPages: MAX_PAGES
  });
});

/**
 * Inicialización del servidor
 */
async function startServer() {
  try {
    // Inicializar el navegador Puppeteer
    await initBrowser();
    
    const PORT = process.env.PORT || 3000;
    app.listen(PORT, "0.0.0.0", () => {
      console.log(`🚀 Servidor de scraping escuchando en puerto ${PORT}`);
      console.log(`📋 Endpoints disponibles:`);
      console.log(`   POST http://localhost:${PORT}/scrape`);
      console.log(`   GET  http://localhost:${PORT}/health`);
    });
  } catch (err) {
    console.error("❌ Error al iniciar servidor:", err);
    process.exit(1);
  }
}

// Manejo de cierre graceful
process.on("SIGTERM", async () => {
  console.log("🛑 Recibida señal SIGTERM, cerrando servicios...");
  await closeBrowser();
  process.exit(0);
});

process.on("SIGINT", async () => {
  console.log("🛑 Recibida señal SIGINT, cerrando servicios...");
  await closeBrowser();
  process.exit(0);
});

// Iniciar el servidor
startServer();