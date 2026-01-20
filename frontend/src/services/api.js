import axios from 'axios';

const API_BASE = 'http://localhost:8000';

export function getProducts() {
  return axios.get(`${API_BASE}/api/products`);
}
