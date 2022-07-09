const API = 'http://localhost:8080/restcantante'

// evento para registrar un nuevo cantante
document.querySelector("#form_agregar").addEventListener("submit", function(e) {

    e.preventDefault(); //stop form from submitting

    // disabled the submit button
    $("#btn_agregar").prop("disabled", true);

    let data = {
        nombre_natural: document.getElementById('nombre_natural').value,
        nombre_artistico: document.getElementById('nombre_artistico').value,
        genero_musical: document.getElementById('genero_musical').value,
        edad: document.getElementById('edad').value,
        pais_nacimiento: document.getElementById('pais_nacimiento').value,
    }

    let formBody = [];
    for (let property in data) {
        let encodedKey = encodeURIComponent(property);
        let encodedValue = encodeURIComponent(data[property]);
        formBody.push(encodedKey + "=" + encodedValue);
    }
    formBody = formBody.join("&");

    sendData(API, formBody, 'POST')
        .then(response => response.json())
        .then(data => {
            $("#btn_agregar").prop("disabled", false);
            clearFormRegister()
            getData()
            toastr.success(`Cantante agregado con éxito`);
        })
        .catch(error => console.log(error))
    $("#btnSubmit").prop("disabled", false);
})

// evento en el formulario para actualizar los datos de un cantante
document.querySelector("#form_update").addEventListener("submit", function(e) {
    e.preventDefault(); //stop form from submitting

    $("#btn_update").prop("disabled", true);

    let data = {
        nombre_natural: document.getElementById('nombre_natural_update').value,
        nombre_artistico: document.getElementById('nombre_artistico_update').value,
        genero_musical: document.getElementById('genero_musical_update').value,
        edad: document.getElementById('edad_update').value,
        pais_nacimiento: document.getElementById('pais_nacimiento_update').value,
    }

    let id_update = document.getElementById('id_update').value

    let formBody = [];
    for (let property in data) {
        let encodedKey = encodeURIComponent(property);
        let encodedValue = encodeURIComponent(data[property]);
        formBody.push(encodedKey + "=" + encodedValue);
    }
    formBody = formBody.join("&");

    sendData(`${API}/${id_update}`, formBody, 'PUT')
        .then(response => response.json())
        .then(data => {
            getData()
            toastr.success(`Cantante actualizado con éxito`);
        })
        .catch(error => console.log(error))

    $("#btn_update").prop("disabled", false);
    $('#ModalUpdate').modal('hide')
})

// función que consulta los cantantes y los lista en el datatable
const getData = async() => {

    const dataFull = await fetch("http://localhost:8080/restcantante")
        .then(response => response.json())
        .then(cantantes => {
            return cantantes
        })

    let datatable = $("#table-results").DataTable()
    datatable.destroy()

    datatable = $("#table-results").DataTable({
        select: true,
        data: dataFull['data'],
        columnDefs: [{
            "click": false,
            "targets": [6],
            "width": "24%"
        }],

        columns: [
            { "data": "id", "visible": false },
            { "data": "nombre_natural" },
            { "data": "nombre_artistico" },
            { "data": "genero_musical" },
            { "data": "edad" },
            { "data": "pais_nacimiento" },
            {
                "data": "id",
                "render": function(data) {
                    return '<a class="btn btn-primary" style="margin-left:30px"  onclick="editdetails(' + data + ')">Edit</a>   <a class="btn btn-danger" style="margin-left:5px; margin-right:-15x" onclick="deletedetails(' + data + ')">Delete</a>'
                }
            }
        ],
    })
}

getData()

// función recursiva que envía los datos a la api para hacer update, delete y registrar cantantes
function sendData(urlApi, data, method) {
    const response = fetch(urlApi, {
        method: method,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
        },
        body: data
    })
    return response
}

// función que consulta los detalles de un cantante y los muestra en el formulario para editar
async function editdetails(id) {

    $('#ModalUpdate').modal('show')
    const dataFull = await fetch(`http://localhost:8080/restcantante/${id}`)
        .then(response => response.json())
        .then(cantantes => {
            cantantes = cantantes.data
            $('#id_update').val(cantantes.id)
            $('#nombre_natural_update').val(cantantes.nombre_natural)
            $('#nombre_artistico_update').val(cantantes.nombre_artistico)
            $('#genero_musical_update').val(cantantes.genero_musical)
            $('#edad_update').val(cantantes.edad)
            $('#pais_nacimiento_update').val(cantantes.pais_nacimiento)
            return cantantes
        })
}

// funcion para editar los datos de un cantante
function deletedetails(id) {
    sendData(`${API}/${id}`, null, "DELETE")
        .then(response => response.json())
        .then(data => {
            toastr.success('Cantante eliminado con éxito');
            getData()
        })
        .catch(error => console.log(error))
}

// funcion para limpiar el formulario de registro
function clearFormRegister() {
    document.getElementById('nombre_natural').value = ""
    document.getElementById('nombre_artistico').value = ""
    document.getElementById('genero_musical').value = ""
    document.getElementById('edad').value = ""
    document.getElementById('pais_nacimiento').value = ""
}