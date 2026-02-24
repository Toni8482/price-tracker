import axios from "axios";

const BASE_URL = "http://localhost:8000/";

export async function getBooks() {
  try {
    const response = await axios.get(BASE_URL + "api/books");
    return response.data;
  } catch (error) {
    console.error("Error al obtener libros:", error);
    throw error;
  }
}

export async function getBook(id) {
  try {
    const response = await axios.get(`${BASE_URL}/${id}`);
    return response.data;
  } catch (error) {
    console.error("Error al obtener el libro:", error);
    throw error;
  }
}


export async function getAllPerfumes() {
  try {
    const response = await axios.get(BASE_URL + "api/perfumes");
    return response.data.datos;
  } catch (error) {
    console.error("Error al obtener perfumes:", error);
    throw error;
  }
}



export async function getPerfume(id) {
  try {
    const response = await axios.get(`${BASE_URL}api/perfumes/${id}`);
    return response.data;
  } catch (error) {
    console.error("Error al obtener el libro:", error);
    throw error;
  }
}
