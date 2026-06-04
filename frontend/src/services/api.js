import axios from "axios";

const BASE_URL = "http://localhost:8000/";
const BASE_URL_JSON = "http://localhost:4000/";

/**
 *
 * Todos los perfumes
 */

export async function getAllPerfumes() {
  try {
    const response = await axios.get(BASE_URL + "api/perfumes");
    return response.data.datos;
  } catch (error) {
    console.error("Error al obtener perfumes:", error);
    throw error;
  }
}

/**
 *
 * Perfume buscado por su id
 *
 */
export async function getPerfume(id) {
  try {
    const response = await axios.get(`${BASE_URL}api/perfumes/${id}`);
    return response.data;
  } catch (error) {
    console.error("Error al obtener el perfume:", error);
    throw error;
  }
}

/**
 * Crear usuario
 */
export async function CreateUser(user) {
  try {
    const response = await axios.post(`${BASE_URL}users`, {
      email: user.email,
      password: user.password,
    });

    return response.data;

  } catch (error) {
    throw error;
  }
}

/**
 * Login de usuario
 */
export async function Login(user) {
  try {
    const response = await axios.post(`${BASE_URL}login`, {
      email: user.email,
      password: user.password,
    });

    console.log(response.data);
    return response.data;
  } catch (error) {
    console.error("Error en login:", error);
    alert(error.response.data.message);
    throw error;
  }
}






/**
 * Datos de usuario logueado
 */
export async function getMe(token) {
  try {
    const response = await axios.get(`${BASE_URL}api/me`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    return response.data;
  } catch (error) {

    if (error.response?.status === 401) {
      logout();
      alert("Tu sesión ha expirado");

      window.location.href = "/login";
      return;
    }

    console.error("Error al obtener el usuario:", error);
    throw error;
  }
}

/**
 * Añadir perfume favorito a usuario
 */
export async function addFavorito(token, perfume_id) {
  try {
    const response = await axios.post(
      `${BASE_URL}api/favorites/${perfume_id}`,
      {
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

/**
 *
 *
 * Lista de usuarios
 *
 */
export async function getUsers(token) {
  try {
    const response = await axios.get(`${BASE_URL}api/all/users`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    console.log(response.data);
    return response.data;

  } catch (error) {
    console.error("Error al obtener lista de usuarios:", error);


     if (error.response?.status === 401) {
      logout();
      alert("Tu sesión ha expirado");

      window.location.href = "/login";
      return;
    }
    throw error;
  }
}

/**
 * Listar perfumes favoritos de usuario
 */
export async function getFavoritosUser(token) {
  try {
    const response = await axios.get(`${BASE_URL}api/favorites/users`, {
      headers: {
        Authorization: `Bearer ${token}`,
      }
    });

    return response.data;

  } catch (error) {
    console.error("Error al obtener favoritos:", error);
    throw error;
  }
}






export async function getFavoritosVariableUser(token) {
  try {
    const response = await axios.get(`${BASE_URL}api/favorites/variables/users`, {
      headers: {
        Authorization: `Bearer ${token}`,
      }
    });

    return response.data;

  } catch (error) {
    console.error("Error al obtener favoritos:", error);
    throw error;
  }
}


export async function editarUsuario(token, user) {
  try {

    const response = await axios.put(`${BASE_URL}api/user/${user.id}`,
      {
        password: user.password,
        email: user.email,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },);
    const data = response.data;
    console.log(data);
  } catch (error) {
    console.error("Error al editar usuario:", error);
    throw error;
  }
}

export async function eliminarUsuario(token, id) {
  try {
    const response = await axios.delete(`${BASE_URL}api/user/${id}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },);
    const data = response.data;
    console.log(data);
  } catch (error) {
    console.error("Error al eliminar usuario:", error);
    throw error;
  }
}

export async function eliminarFavorito(token, id) {
  try {
    const response = await axios.delete(`${BASE_URL}api/favorites/${id}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },);
    const data = response.data;
    console.log(data);
  } catch (error) {
    console.error("Error al eliminar favorito:", error);
    throw error;
  }

}

export function getToken() {
  return localStorage.getItem("token");
}

export function logout() {
  localStorage.removeItem("token");
  localStorage.removeItem("user_id");
  localStorage.removeItem("user_email");
  localStorage.removeItem("roles");
}

export function getRoles() {
  return JSON.parse(localStorage.getItem("roles") || "[]");
}

export function isAdmin() {
  return getRoles().includes("ROLE_ADMIN");
}