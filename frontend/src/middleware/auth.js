import { getToken } from '../services/api.js';

export const authMiddleware = async (to, from, next) => {
  const token = getToken();

  if (!token) {
    // No hay token, redirigir a la página de inicio de sesión
    next('/login');
  } else {
    // Token presente, permitir el acceso a la ruta
    next();
  }
};