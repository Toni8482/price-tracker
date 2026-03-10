import axios from "axios";


const BASE_URL = "http://localhost:8000/";
const BASE_URL_JSON = "http://localhost:4000/";

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
    console.error("Error al obtener el perfume:", error);
    throw error;
  }
}

export async function CreateUser(user) {
  try {
    await axios
      .post(`${BASE_URL_JSON}register`, {
        email: user.email,
        password: user.password,
      })
      .then(function (response) {
        console.log(response);
        return response.data;
      })
      .catch(function (error) {
        console.log(error);
        return error;
      });
  } catch (error) {
    console.error("Error en crear usuario: ", error);
    throw error;
  }
}
export async function Login(user) {
  try {
    const response = await axios.post(`${BASE_URL}login`, {
      email: user.email,
      password: user.password,
    });

    console.log(response.data);
    return response.data; // o return response si necesitas todo
  } catch (error) {
    console.error("Error en login:", error);
    throw error;
  }
}

export async function getMe(token) {
  try {
    const response = await axios.get(`${BASE_URL}api/me`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    return response.data;
  } catch (error) {
    console.error("Error al obtener el usuario:", error);
    throw error;
  }
}

export async function addFavorito(token, user_id, perfume_id) {
  try {
    const response = await axios.post(
      `${BASE_URL}api/favorites`,
      {
        user_id: user_id,
        perfume_id: perfume_id,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    return response.data;
  } catch (error) {
    console.error("Error al añadir favoritos", error);
    throw error;
  }
}

export async function getUser(id) {
  try {
    const response = await axios.get(`${BASE_URL_JSON}users/${id}`);
    return response.data;
  } catch (error) {
    console.error("Error al obtener el usuario:", error);
    throw error;
  }
}
export async function getFavoritosUser(idUser) {
  try {
    const response = await axios.get(`${BASE_URL_JSON}favoritos`);
    const perfumes = response.data;

    const favoritosId = perfumes.filter(p => p.user_id == idUser);

    const favoritos = await Promise.all(
      favoritosId.map(async (element) => {
        const response = await axios.get(`${BASE_URL_JSON}perfumes/${element.perfume_id}`);
        return response.data;
      })
    );

    return favoritos;

  } catch (error) {
    console.error("Error al obtener favoritos:", error);
    throw error;
  }
}
