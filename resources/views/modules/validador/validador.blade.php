<div class="col-12">
    @include('layouts.uso')
    <div class="col-12 text-center">
        <h3 class="py-3">VALIDADOR DE TEXTOS</h1>
    </div>
    <div class="jumbotron col-12 text-left py-3">
        <h5>1. Importar archivo</h5>
        <form id="formcargavalidador" enctype="multipart/form-data">
            <input type="hidden" name="iduso" value="{{$uso->id}}">
            <div class="col-12 d-flex px-0">
                <div class="col-xl-6 col-md-7 col-sm-8 col-xs-10 pl-0">
                    <div class="form-group">
                        <label>NOMBRE PARA EL ARCHIVO</label>
                        <input type="text" class="form-control" name="nombrearchivo">
                    </div>
                    <div class="form-group">
                        <label>TIPO DE ARCHIVO</label>
                        <select class="form-control">
                            <option value="0">Seleccione</option>
                            <option value="1">Archivo de compras</option>
                            <option value="2">Archivo de ventas</option>
                            <option value="3">Archivo de gastos</option>
                        </select>
                    </div>
                    <div class="custom-file px-1">
                        <input type="file" class="custom-file-input" id="validadorfile" name="myfile">
                        <label id="validadorlabel" class="custom-file-label" for="validadorfile"></label>
                    </div>
                </div>
                <div class="col-xs-2 text-center" id="cargavalidadorfile">
                    
                </div>
            </div>
            <div class="col-12 text-left px-0 py-3">
                <div class="form-group">
                    <input type="submit" value="IMPORTAR" class="btn btn-success">
                </div>
            </div>
        </form>
    </div>
    <div class="jumbotron col-12 text-left py-3">
        <h5>2. Vista previa</h5>
        <div class="row">
            <div class="col-xl-9 col-md-8 col-sm-12 col-xs-12">
                <div class="col-12" id="divvalidadortable">
                    
                </div>    
            </div>
        </div>
    </div>
    <div class="jumbotron col-12 text-left py-3">
        <h5>3. Exportar resultado</h5>
        <form action="/ExportExcelCompra" method="GET">
            <button type="submit" id="btn-exportar-mayorvalidador" class="btn btn-warning">Exportar en excel</button>
        </form>
    </div>
    <script type="text/javascript" src="{{ asset('assets/js/proyecto/validador/validador.js')}}"></script>
</div>
