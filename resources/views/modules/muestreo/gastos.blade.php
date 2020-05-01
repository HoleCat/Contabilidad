<div class="col-12">
    @include('layouts.uso')
    <div class="col-12 text-center">
        <h3 class="py-3">MUESTRAS DEL REGISTRO DE GASTOS</h1>
    </div>
    <div class="jumbotron col-12 text-left py-3">
        <h5>1. Importar archivo</h5>
        <form id="formcargagastos" enctype="multipart/form-data">
            <input type="hidden" name="iduso" value="{{$uso->id}}">
            <div class="col-12 d-flex px-0">
                <div class="col-xl-6 col-md-7 col-sm-8 col-xs-10 pl-0">
                    <div class="form-group">
                        <label>NOMBRE PARA EL ARCHIVO</label>
                        <input type="text" class="form-control" name="nombrearchivo">
                    </div>
                    <div class="custom-file px-1">
                        <input type="file" class="custom-file-input" id="gastosfile" name="myfile">
                        <label id="gastoslabel" class="custom-file-label" for="gastosfile"></label>
                    </div>
                </div>
                <div class="col-xs-2 text-center" id="cargagastosfile">
                    
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
                <form id="formfiltrogastos" class="text-center">
                    <input type="hidden" name="iduso" value="{{$uso->id}}">
                    <input type="hidden" id="idarchivogastos" name="id_archivo" value="">
                    <div class="form-group text-left d-flex">
                        <input type="submit" class="btn btn-primary" value="FILTRAR">
                        <div id="cargafiltrogastos">

                        </div>
                    </div>
                    <div class="d-flex flex-wrap text-left px-0">
                        <div class="col-12 py-1 px-0">
                            <label for="">TIPO DE COMPARACION</label>
                            <select class="custom-select" name="comparacion">
                                <option value="1">ENTRE</option>
                                <!--AMBOS-->
                                <option value="1">(>=) MAYOR IGUAL</option>
                                <option value="2">(=) IGUAL</option>
                                <option value="3">(<=) MENOR IGUAL</option>
                                <!--SOLO MINIMO-->
                            </select>
                        </div>
                        <div class="col-12 py-1 px-0">
                            <label for="">CANTIDAD DE REGISTOS</label>
                            <input class="form-control" type="number" name="cantidad">
                        </div>
                        <div class="col-12 py-1 px-0">
                            <label for="">IMPORTE MINIMO</label>
                            <input class="form-control" type="number" name="importeminimo">
                        </div>
                        <div class="col-12 py-1 px-0">
                            <label for="">IMPORTE MAXIMO</label>
                            <input class="form-control" type="number" name="importemaximo">
                        </div>
                        <div class="col-12 py-1 px-0">
                            <label for="">TIPO DE COMPROBANTE</label>
                            <select class="custom-select" name="tipocomprobante">
                                @foreach ($comprobantes as $comprobante)
                                <option value="{{ $comprobante->codigo }}">{{ $comprobante->codigo }} {{ $comprobante->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-9 col-md-8 col-sm-12 col-xs-12">
                <div class="col-12" id="divgastostable">
                    
                </div>    
            </div>
        </div>
    </div>
    <div class="jumbotron col-12 text-left py-3">
        <h5>3. Exportar resultado</h5>
        <form action="/ExportarExcelGastos" method="GET">
            <button type="submit" id="btn-exportar-mayorgastos" class="btn btn-warning">Exportar en excel</button>
        </form>
    </div>
    <script type="text/javascript" src="{{ asset('assets/js/proyecto/muestreo/gastos/gastos.js')}}"></script>
</div>