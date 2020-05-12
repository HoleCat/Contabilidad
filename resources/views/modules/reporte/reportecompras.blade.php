<div class="col-12">
    @include('layouts.uso')
    <div class="col-12 text-center">
        <h3 class="py-3">REPORTE</h1>
    </div>
    
    <div class="jumbotron col-12 text-left py-3">
        <h5>1. Importar detracción :</h5>
        <div class="col-12">
            <form id="formeliminardata" class="text-center">
                <input type="hidden" name="iduso" value="{{$uso->id}}">
                <div class="col-12 d-flex flex-wrap  text-left">
                    <label class="col-12">Elimina data de uno de tus archivos :</label>
                    <div class="col-6">
                        <select class="custom-select" name="id_archivo" id="selectarchivo1"></select>
                    </div>
                    <div class="col-6">
                        <div class="form-group text-left d-flex">
                            <input type="submit" class="btn btn-danger" value="ELIMINAR DATA">
                            <div class="ml-1" id="cargaeliminardata">
    
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <form id="formdetraccioncompras" enctype="multipart/form-data">
            <input id="uso_id" type="hidden" name="iduso" value="{{$uso->id}}">
            <div class="col-12 d-flex px-0">
                <div class="col-xl-6 col-md-7 col-sm-8 col-xs-10 pl-0">
                    <div class="form-group">
                        <input type="text" class="form-control" name="nombrearchivo" placeholder="NOMBRE DEL ARCHIVO">
                    </div>
                    <div class="custom-file px-1">
                        <input type="file" class="custom-file-input" id="detraccioncomprasfile" name="myfile">
                        <label id="detraccioncompraslabel" class="custom-file-label" for="detraccioncomprasfile"></label>
                    </div>
                </div>
                <div class="col-xs-2 text-center" id="cargadetraccioncompras">
                    
                </div>
            </div>
            <div class="custom-control custom-radio">
                <input type="checkbox" id="csv" name="csv" class="custom-control-input">
                <label class="custom-control-label" for="csv">CSV</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="checkbox" id="excel" name="excel" class="custom-control-input">
                <label class="custom-control-label" for="excel">EXCEL</label>
            </div>
            <div class="col-12 text-left px-0 py-3">
                <div class="form-group">
                    <input type="submit" value="IMPORTAR" class="btn btn-success">
                </div>
            </div>
        </form>
        <div class="col-12" id="divdetraccioncomprastable">

        </div>
    </div>
    <div class="jumbotron col-12 text-left py-3">
        <h5>2. Importar archivo de compras :</h5>
        <form id="formcargareportecompras" enctype="multipart/form-data">
            <input id="uso_id" type="hidden" name="iduso" value="{{$uso->id}}">
            <div class="col-12 d-flex px-0">
                <div class="col-xl-6 col-md-7 col-sm-8 col-xs-10 pl-0">
                    <div class="form-group">
                        <input type="text" class="form-control" name="nombrearchivo" placeholder="NOMBRE DE ARCHIVO">
                    </div>
                    <div class="custom-file px-1">
                        <input type="file" class="custom-file-input" id="reportecomprasfile" name="myfile">
                        <label id="reportecompraslabel" class="custom-file-label" for="reportecomprasfile"></label>
                    </div>
                </div>
                <div class="col-xs-2 text-center" id="cargareportecomprasfile">
                    
                </div>
            </div>
            <div class="col-12 text-left px-0 py-3">
                <div class="form-group">
                    <input type="submit" value="IMPORTAR" class="btn btn-success">
                </div>
            </div>
        </form>
        <h5>3. Verifique su data :</h5>
        <p>los check de la primera columna nos ayudan a separar la data individualmente, darle check a la letra "X" agrega ese registro a la lista de desestimados, por otro lado la letra "D" agrega ese registro a la lista de aquellos que seran enviados en para la detracción. </p>
        <div class="row">
            <div class="col-12">
                <form id="formeliminardata" class="text-center">
                    <input type="hidden" name="iduso" value="{{$uso->id}}">
                    <div class="col-12 d-flex flex-wrap  text-left">
                        <label class="col-12">Elimina data de uno de tus archivos :</label>
                        <div class="col-6">
                            <select class="custom-select" name="id_archivo" id="selectarchivo2"></select>
                        </div>
                        <div class="col-6">
                            <div class="form-group text-left d-flex">
                                <input type="submit" class="btn btn-danger" value="ELIMINAR DATA">
                                <div class="ml-1" id="cargaeliminardata">
        
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12">
                <div class="col-12" id="divreportecomprastable">
                    
                </div>
                <div class="col-12" id="divreporteeliminadostable">
                    
                </div>   
            </div>
        </div>
    </div>
    <div class="jumbotron col-12 text-left py-3">
        <h5>3. Generar TXT para consulta RUC :</h5>
        <form class="my-2" action="/Reporte/Compras/Txtconsultaruc" method="GET">
            <input id="uso_id" type="hidden" name="iduso" value="{{$uso->id}}">
            <button type="submit" class="btn btn-info">DESCARGAR</button>
        </form>
        <h5>4. Generar TXT para consultar comprobantes :</h5>
        <form class="my-2" action="/Reporte/Compras/Txtcomprobantes" method="GET">
            <input id="uso_id" type="hidden" name="iduso" value="{{$uso->id}}">
            <button type="submit" class="btn btn-info">DESCARGAR</button>
        </form>
    </div>
    
    <div class="jumbotron col-12 text-left py-3">
        <h5>5. Importar Resultado RUC :</h5>
        <div class="col-12">
            <form id="formeliminarresultado1" class="text-center">
                <input type="hidden" name="iduso" value="{{$uso->id}}">
                <div class="col-12 d-flex flex-wrap  text-left">
                    <label class="col-12">Elimina data de uno de tus archivos :</label>
                    <div class="col-6">
                        <select class="custom-select" name="id_archivo" id="selectarchivoresultadoruc"></select>
                    </div>
                    <div class="col-6">
                        <div class="form-group text-left d-flex">
                            <input type="submit" class="btn btn-danger" value="ELIMINAR DATA">
                            <div class="ml-1" id="cargaeliminarresultadoruc">
    
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <form id="formresultadoruc" enctype="multipart/form-data">
            <input id="uso_id" type="hidden" name="iduso" value="{{$uso->id}}">
            <div class="col-12 d-flex px-0">
                <div class="col-xl-6 col-md-7 col-sm-8 col-xs-10 pl-0">
                    <div class="form-group">
                        <input type="text" class="form-control" name="nombrearchivo" placeholder="NOMBRE DEL ARCHIVO">
                    </div>
                    <div class="custom-file px-1">
                        <input type="file" class="custom-file-input" id="resultadorucfile" name="myfile">
                        <label id="resultadoruclabel" class="custom-file-label" for="resultadorucfile"></label>
                    </div>
                </div>
                <div class="col-xs-2 text-center" id="cargaresultadoruc">
                    
                </div>
            </div>
            <div class="custom-control custom-radio">
                <input type="checkbox" id="csv" name="csv" class="custom-control-input">
                <label class="custom-control-label" for="csv">CSV</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="checkbox" id="excel" name="excel" class="custom-control-input">
                <label class="custom-control-label" for="excel">EXCEL</label>
            </div>
            <div class="col-12 text-left px-0 py-3">
                <div class="form-group">
                    <input type="submit" value="IMPORTAR" class="btn btn-success">
                </div>
            </div>
        </form>
        <div class="col-12" id="divresultadoructable">

        </div>
    </div>

    <div class="jumbotron col-12 text-left py-3">
        <h5>5. Importar Resultado Comprobantes :</h5>
        <div class="col-12">
            <form id="formeliminarresultado1" class="text-center">
                <input type="hidden" name="iduso" value="{{$uso->id}}">
                <div class="col-12 d-flex flex-wrap  text-left">
                    <label class="col-12">Elimina data de uno de tus archivos :</label>
                    <div class="col-6">
                        <select class="custom-select" name="id_archivo" id="selectarchivoresultadocomprobante"></select>
                    </div>
                    <div class="col-6">
                        <div class="form-group text-left d-flex">
                            <input type="submit" class="btn btn-danger" value="ELIMINAR DATA">
                            <div class="ml-1" id="cargaeliminarresultadocomprobantes">
    
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <form id="formresultadocomprobante" enctype="multipart/form-data">
            <input id="uso_id" type="hidden" name="iduso" value="{{$uso->id}}">
            <div class="col-12 d-flex px-0">
                <div class="col-xl-6 col-md-7 col-sm-8 col-xs-10 pl-0">
                    <div class="form-group">
                        <input type="text" class="form-control" name="nombrearchivo" placeholder="NOMBRE DEL ARCHIVO">
                    </div>
                    <div class="custom-file px-1">
                        <input type="file" class="custom-file-input" id="resultadocomprobantefile" name="myfile">
                        <label id="resultadocomprobantelabel" class="custom-file-label" for="resultadocomprobantefile"></label>
                    </div>
                </div>
                <div class="col-xs-2 text-center" id="cargaresultadocomprobante">
                    
                </div>
            </div>
            <div class="custom-control custom-radio">
                <input type="checkbox" id="csv" name="csv" class="custom-control-input">
                <label class="custom-control-label" for="csv">CSV</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="checkbox" id="excel" name="excel" class="custom-control-input">
                <label class="custom-control-label" for="excel">EXCEL</label>
            </div>
            <div class="col-12 text-left px-0 py-3">
                <div class="form-group">
                    <input type="submit" value="IMPORTAR" class="btn btn-success">
                </div>
            </div>
        </form>
        <div class="col-12" id="divresultadocomprobantetable">

        </div>
    </div>
    <script type="text/javascript" src="{{ asset('assets/js/proyecto/reporte/reporte.js')}}"></script>
</div>
