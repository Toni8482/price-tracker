# TODO - Price Tracker

## Próximo paso (mañana)
- [ ] Revisar URLs de los perfumes

## Pendientes
- [ ] Revisar foreign key de `target_public` en la base de datos
- [ ] Normalizar concentraciones de perfumes
- [ ] Revisar descripción larga (`description`) para que no trunque la BD
- [ ] Validar URLs antes de guardar en la BD
- [ ] Mejorar manejo de errores en scraper
- [ ] Añadir control de stock nulo o vacío
- [ ] Documentar estructura de entidades en README.md
- [ ] Añadir tests básicos para `peticionesUrlSelectores()`

## Ideas / Mejoras futuras
- [ ] Soporte para más tiendas de perfumes
- [ ] Añadir sistema de alertas cuando cambien precios
- [ ] Exportar datos a CSV o Excel
- [ ] Añadir un panel web para consultar precios y stock
- [ ] Optimizar consultas a la base de datos

## Completadas
- [x] Crear entidades básicas: `Perfumes`, `Stores`, `TargetPublic`
- [x] Configurar Doctrine y migraciones iniciales
- [x] Primer scraping funcional para `Perfumerias` y `PerfumesClub`