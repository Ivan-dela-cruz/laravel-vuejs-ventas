<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorios</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Entregas
                    <button type="button" @click="abrirModal('entrega','registrar')" class="btn btn-success">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="cargarPdf()" class="btn btn-info">
                        <i class="icon-doc"></i>&nbsp;Reporte
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarEntradas(1,buscar,criterio)"
                                       class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarEntradas(1,buscar,criterio)"
                                        class="btn btn-primary"><i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Código</th>
                            <th>Nombre Proveedor</th>
                            <th>Mañana</th>
                            <th>Tarde</th>
                            <th>Total litros</th>
                            <th>Precio</th>
                            <th>Valor cancelado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="entrega in arrayEntrega" :key="entrega.id">
                            <td>
                                <button type="button" @click="abrirModal('entrega','actualizar',entrega)"
                                        class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <template v-if="entrega.condicion">
                                    <button type="button" class="btn btn-danger btn-sm"
                                            @click="desactivarEntrada(entrega.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm"
                                            @click="activarEntrada(entrega.id)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="entrega.id"></td>
                            <td v-text="entrega.nombre_provedor"></td>
                            <td v-text="entrega.cantidadM"></td>
                            <td v-text="entrega.cantidadT"></td>
                            <td v-text="entrega.total"></td>
                            <td v-text="entrega.precio"></td>
                            <td v-text="entrega.valor"></td>
                            <td>
                                <div v-if="entrega.condicion">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Desactivado</span>
                                </div>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#"
                                   @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page"
                                :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)"
                                   v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#"
                                   @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="id_provedor">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="provedor in arrayProveedor" :key="provedor.id"
                                                :value="provedor.id" v-text="provedor.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Cantidad mañana</label>
                                <div class="col-md-9">
                                    <input  type="number" v-model="cantidadM"
                                           class="form-control"
                                           placeholder="">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Cantidad Tarde</label>
                                <div class="col-md-9">
                                    <input  type="number" v-model="cantidadT"
                                           class="form-control"
                                           placeholder="">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label hidden class="col-md-3 form-control-label" for="text-input">Total litros del día</label>
                                <div class="col-md-9">
                                    <input hidden  type="number" v-model="total" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">precio</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="precio" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label hidden class="col-md-3 form-control-label" for="text-input">valor a pagar</label>
                                <div class="col-md-9">
                                    <input hidden type="number" v-model="valor" class="form-control"
                                           placeholder="">
                                </div>
                            </div>
                            <div v-show="errorEntrada" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjEntrada" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEntrada()">
                            Guardar
                        </button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary"
                                @click="actualizarEntrada()">Actualizar
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';



    export default {
        data() {
            return {
                entrega_id: 0,
                id_provedor: 0,
                cantidadM: 0,
                cantidadT: 0,
                total: 0,
                precio: 0,
                valor: 0,
                arrayEntrega: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorEntrada: 0,
                errorMostrarMsjEntrada: [],
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,
                criterio: 'nombre',
                buscar: '',
                arrayProveedor: []
            }
        },
        components: {
            'barcode': VueBarcode
        },
        computed: {
            isActived: function () {
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;

            }
        },
        methods: {
            listarEntradas(page, buscar, criterio) {
                let me = this;
                var url = '/entrega?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEntrega = respuesta.entregas.data;
                    me.pagination = respuesta.pagination;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            cargarPdf() {
                window.open('/entrega/listarPdf', '_blank');
            },
            selectProvedor() {
                let me = this;
                var url = 'proveedor/selectProveedores';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta = response.data;
                    me.arrayProveedor = respuesta.proveedores;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            cambiarPagina(page, buscar, criterio) {
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEntradas(page, buscar, criterio);
            },
            registrarEntrada() {
                if (this.validarEntrada()) {
                    return;
                }

                let me = this;

                axios.post('/entrega/registrar', {
                    'id_provedor': this.id_provedor,
                    'cantidadM': this.cantidadM,
                    'cantidadT': this.cantidadT,
                    'total': this.total,
                    'precio': this.precio,
                    'valor': this.valor
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEntradas(1, '', 'nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarEntrada() {
                if (this.validarEntrada()) {
                    return;
                }

                let me = this;

                axios.put('/entrega/actualizar', {
                    'id_provedor': this.id_provedor,
                    'cantidadM': this.cantidadM,
                    'cantidadT': this.cantidadT,
                    'total': this.total,
                    'precio': this.precio,
                    'valor': this.valor,
                    'id': this.entrega_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEntradas(1, '', 'nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            desactivarEntrada(id) {
                swal({
                    title: 'Esta seguro de desactivar entrega?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let me = this;

                        axios.put('/entrega/desactivar', {
                            'id': id
                        }).then(function (response) {
                            me.listarEntradas(1, '', 'nombre');
                            swal(
                                'Desactivado!',
                                'El registro ha sido desactivado con éxito.',
                                'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });


                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {

                    }
                })
            },
            activarEntrada(id) {
                swal({
                    title: 'Esta seguro de activar este entrega?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let me = this;

                        axios.put('/entrega/activar', {
                            'id': id
                        }).then(function (response) {
                            me.listarEntradas(1, '', 'nombre');
                            swal(
                                'Activado!',
                                'El registro ha sido activado con éxito.',
                                'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });


                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {

                    }
                })
            },
            validarEntrada() {
                this.errorEntrada = 0;
                this.errorMostrarMsjEntrada = [];

                if (this.id_provedor == 0) this.errorMostrarMsjEntrada.push("Seleccione un proveedor");
                if (!this.cantidadM) this.errorMostrarMsjEntrada.push("El cantidad esta vacía");
                if (!this.cantidadT) this.errorMostrarMsjEntrada.push("El cantidad tarde esta vacía");
                if (!this.precio) this.errorMostrarMsjEntrada.push("El precio venta del artículo debe ser un número y no puede estar vacío.");

                if (this.errorMostrarMsjEntrada.length) this.errorEntrada = 1;

                return this.errorEntrada;
            },
            cerrarModal() {
                this.modal = 0;
                this.tituloModal = '';
                this.id_provedor = 0;
                this.nombre_provedor = '';
                this.cantidadM = 0;
                this.cantidadT = 0;
                this.total = 0;
                this.precio = 0;
                this.valor = 0;
                this.errorEntrada = 0;
            },
            abrirModal(modelo, accion, data = []) {
                switch (modelo) {
                    case "entrega": {
                        switch (accion) {
                            case 'registrar': {
                                this.modal = 1;
                                this.tituloModal = 'Entrega de Leche';
                                this.id_provedor = 0;
                                this.nombre_provedor = '';
                                this.cantidadM = 0;
                                this.cantidadT = 0;
                                this.total = 0;
                                this.precio = 0;
                                this.valor = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar': {
                                //console.log(data);
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Entrega';
                                this.tipoAccion = 2;
                                this.entrega_id = data['id'];
                                this.id_provedor = data['id_provedor'];
                                this.cantidadM = data['cantidadM'];
                                this.cantidadT = data['cantidadT'];
                                this.total = data['total'];
                                this.precio = data['precio'];
                                this.valor = data['valor'];
                                break;
                            }
                        }
                    }
                }
                this.selectProvedor();
            }
        },
        mounted() {
            this.listarEntradas(1, this.buscar, this.criterio);
        }
    }
</script>
<style>
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }

    .div-error {
        display: flex;
        justify-content: center;
    }

    .text-error {
        color: red !important;
        font-weight: bold;
    }
</style>
