/**
 * Extracción de URLs con scroll
 */

const { MAX_SCROLLS, MAX_ITEMS } = require("../../config/constants");
const { detectProductSelector } = require("../detectors/selector-detector");
const { scrollToBottom } = require("../helpers/scroll");
const { wait } = require("../helpers/wait");
const { debugPageInfo, showSampleLinks } = require("../helpers/debug");

/**
 * Extrae URLs de una página con scroll dinámico
 */
async function extractUrlsWithScroll(page, selector, maxItems = MAX_ITEMS) {
  // Detectar selector si no se proporciona
  if (!selector) {
    selector = await detectProductSelector(page);
    if (!selector) {
      console.log("⚠️ No se pudo detectar selector automáticamente");
      await debugPageInfo(page);
      await showSampleLinks(page);
      throw new Error("No se encontró un selector válido para extraer URLs");
    }
  }
  
  console.log(`🔄 Iniciando scroll con selector: ${selector}`);
  console.log(`🎯 Objetivo: extraer hasta ${maxItems} URLs`);

  let lastCount = 0;
  let stableRounds = 0;
  let scrollAttempts = 0;

  while (scrollAttempts < MAX_SCROLLS) {
    await scrollToBottom(page);
    await wait(2500);

    const count = await page.evaluate(
      (sel) => document.querySelectorAll(sel).length,
      selector
    );

    console.log(`🔄 Elementos detectados: ${count} (scroll ${scrollAttempts + 1}/${MAX_SCROLLS})`);

    if (count >= maxItems) {
      console.log(`🎯 Alcanzado límite de ${maxItems} elementos`);
      break;
    }

    if (count === lastCount) {
      stableRounds++;
      if (stableRounds >= 3) break;
    } else {
      stableRounds = 0;
    }

    lastCount = count;
    scrollAttempts++;
  }

  // Extraer URLs únicas
  const urls = await page.evaluate(
    (sel, limit) => {
      const links = Array.from(document.querySelectorAll(sel))
        .map((el) => el.href)
        .filter(href => href && href !== '' && !href.startsWith('javascript:') && !href.startsWith('#'));
      
      return [...new Set(links)].slice(0, limit);
    },
    selector,
    maxItems
  );

  console.log(`✅ URLs finales extraídas: ${urls.length}`);
  
  if (urls.length === 0) {
    console.log("⚠️ No se encontraron URLs válidas");
    await debugPageInfo(page);
  } else {
    urls.slice(0, 5).forEach((u, i) => {
      console.log(`🔗 ${i + 1}: ${u.substring(0, 100)}`);
    });
  }

  return urls;
}

module.exports = { extractUrlsWithScroll };