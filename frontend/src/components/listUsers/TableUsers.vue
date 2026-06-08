<template>
    <BaseTable>
        <template #header>
            <tr>
                <th>Id</th>
                <th>Email</th>
                <th>Roles</th>

                <th></th>

            </tr>
        </template>
        <template #body>
            <tr v-for="user in users">
                <td>{{ user.id }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.roles.join(', ') }}</td>

                <td>
                    <button @click="editarUsuario(user.id)">Editar</button>
                    <button @click="eliminarUsuario(user.id)">Eliminar</button>
                </td>
            </tr>
        </template>
    </BaseTable>
</template>

<script>
import BaseTable from '@/components/base/BaseTable.vue';


export default {
    name: 'TableUsers',
    components: {
        BaseTable
    },
    props: {
        users: Array
    },
    data() {
        return {

        }
    },

    computed: {

    },

    methods: {
        editarUsuario(id) {
            this.$router.push({ name: "edit-register", params: { id } });

        },

        eliminarUsuario(id) {

            this.$emit("id", id);
        }
    },

    mounted() {

    }
}
</script>

<style scoped>
table {
    width: 90%;
    max-width: 900px;
    margin: 30px auto;
    border-collapse: collapse;
    display: table;
    /* para que funcione como tabla real */
    color: var(--table-text);
    background: var(--table-bg);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

/* CABECERA */
th {
    padding: 12px 20px;
    background: var(--table-header-bg);
    color: var(--table-header-text);
    font-weight: bold;
    text-align: center;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

/* CELDAS */
td {
    padding: 10px 15px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

/* FILA HOVER */
tr:hover {
    background: var(--table-row-hover);
    transform: translateY(0px);
    transition: background 0.2s;
}

/* BORDES */
th,
td {
    border: 1px solid rgba(124, 58, 237, 0.3);
    border-radius: 0px;
    /* bordes redondeados solo en tabla completa */
}

/* FILAS IMPARES */
tr:nth-child(odd) td {
    background: var(--table-row-odd);
}

/* FILAS PARES */
tr:nth-child(even) td {
    background: var(--table-row-even);
}

button {
    margin: 5px 8px 5px 0;

    border: none;

    background-color: var(--btn-bg);
    color: var(--btn-text);
    padding: 10px;
    cursor: pointer;

    border-radius: 12px;
    transition: all 0.3s ease;

    position: relative;
    overflow: hidden;
}


button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(212, 175, 55, 0.4);

}
</style>