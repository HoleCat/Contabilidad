<div class="col-12">
    @include('layouts.uso')
    <div class="col-12 text-center">
        <h3 class="py-3">ACTIVOS</h1>
        <h2 class="py-0">depreciacion de activos</h2>
    </div>
    <div class="jumbotron col-12 text-left py-3">
        <h5>1. Importar archivo</h5>
        <form id="formcargaactivos" enctype="multipart/form-data">
            <input type="hidden" name="iduso" value="{{$uso->id}}">
            <div class="col-12 d-flex px-0">
                <div class="col-xl-6 col-md-7 col-sm-8 col-xs-10 pl-0">
                    <div class="form-group">
                        <input placeholder="NOMBRE DE LA ACTIVIDAD" type="text" class="form-control" name="nombrearchivo">
                    </div>
                    <div class="custom-file px-1">
                        <input type="file" class="custom-file-input" id="activosfile" name="myfile">
                        <label id="activoslabel" class="custom-file-label" for="activosfile"></label>
                    </div>
                </div>
                <div class="col-xs-2 text-center" id="cargaactivosfile">
                    
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
        <h5>2. Filtra la data</h5>
        <div class="row">
            <div class="col-xl-3 col-md-4 col-sm-12 col-xs-12">
                <form id="formfiltroactivos" class="text-center">
                    <input type="hidden" name="iduso" value="{{$uso->id}}">
                    <input type="hidden" id="idarchivoactivos" name="id_archivo" value="">
                    <div class="form-group text-left d-flex">
                        <input type="submit" class="btn btn-primary" value="FILTRAR">
                        <div id="cargafiltroactivos">

                        </div>
                    </div>
                    <div class="d-flex flex-wrap text-left px-0">
                        <div class="col-12 py-1 px-0">
                            <label for="">DESDE QUE DIA SE CONSIDERA EL MES ACTUAL ?</label>
                            <select class="custom-select" name="check">
                                <option value="1">SI ES CUALQUIER DIA SE SUMA UNO</option>
                                <!--AMBOS-->
                                <option value="2">SI ES MENOR A 16 SE SUMA UNO</option>
                                <option value="3">SI EL DIA ES IGUAL 1 SE AUMENTA UNO</option>
                                <option value="4">DIFERENCIA DE DIAS</option>
                                <!--SOLO MINIMO-->
                            </select>
                        </div>
                        <div class="col-12 py-1 px-0">
                            <label for="">CANTIDAD DE REGISTOS</label>
                            <input class="form-control" type="number" name="cantidad">
                        </div>
                        <div class="col-12 py-1 px-0">
                            <label for="">FECHA DE REVISION</label>
                            <input class="form-control" type="date" name="fechafin">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-9 col-md-8 col-sm-12 col-xs-12">
                <div class="col-12" id="divactivostable">
                    
                </div>    
            </div>
        </div>
    </div>
    <div class="jumbotron col-12 text-left py-3">
        <h5>3. Exportar resultado</h5>
        <form action="/Activos/Exportar" method="GET">
            <button type="submit" id="btn-exportar-activos" class="btn btn-warning">Exportar en excel</button>
        </form>
    </div>
    <script type="text/javascript" src="{{ asset('assets/js/proyecto/activos/activos.js')}}"></script>
</div>