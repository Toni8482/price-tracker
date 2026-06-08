import { isAdmin } from '../services/api.js';

export const adminMiddleware = async (to, from, next) => {
  

  if (!isAdmin()) {
   
    next('/login');
  } else {
  
    next();
  }
};