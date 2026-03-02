# TODO - Price Tracker

## Próximo paso (mañana)


## Pendientes

- [ ] Normalizar concentraciones de perfumes
- [ ] Validar URLs antes de guardar en la BD
- [ ] Mejorar manejo de errores en scraper
- [ ] Documentar estructura de entidades en README.md
- [ ] Añadir tests básicos para `peticionesUrlSelectores()`

## Ideas / Mejoras futuras
- [ ] Soporte para más tiendas de perfumes
- [ ] Añadir sistema de alertas cuando cambien precios
- [ ] Exportar datos a CSV o Excel
- [ ] Optimizar consultas a la base de datos

## Completadas
- [x] Crear entidades básicas: `Perfumes`, `Stores`, `TargetPublic`
- [x] Configurar Doctrine y migraciones iniciales
- [x] Primer scraping funcional para `Perfumerias` y `PerfumesClub`
- [x] Revisar URLs de los perfumes
- [x] Revisar foreign key de `target_public` en la base de datos
- [x] Revisar descripción larga (`description`) para que no trunque la BD
- [x] Añadir precios y contenidos a la API de Perfumes