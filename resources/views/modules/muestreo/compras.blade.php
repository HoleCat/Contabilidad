<div class="col-12">
    @include('layouts.uso')
    <div class="col-12 text-center">
        <h3 class="py-3">IMPORTACION DE COMPRAS</h1>
    </div>
    <div class="col-12 py-1 px-0 text-left">
        <form action="/ExportExcelCompra" method="GET">
            <button type="submit" id="btn-exportar-mayorcompras" class="btn btn-warning">Exportar en excel</button>
        </form>
    </div>
    <div class="col-12">
        <div class="row">
            <form id="formcompras" class="text-center">
            <input type="hidden" name="iduso" value="{{$uso->id}}">
            <input type="hidden" id="idarchivo" name="idarchivo" value="">
                <div class="custom-file px-1">
                    <input type="file" class="custom-file-input" id="comprasfile" name="myfile">
                    <label id="compraslabel" class="custom-file-label" for="comprasfile"></label>
                </div>
                <div class="d-flex flex-wrap text-left">
                    <div class="col-6 py-1 px-1">
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
                    <div class="col-6 py-1 px-1">
                        <label for="">IMPORTE MINIMO</label>
                        <input class="form-control" type="number" name="importeminimo">
                    </div>
                    <div class="col-6 py-1 px-1">
                        <label for="">IMPORTE MAXIMO</label>
                        <input class="form-control" type="number" name="importemaximo">
                    </div>
                    <div class="col-6 py-1 px-1">
                        <label for="">TIPO DE COMPROBANTE</label>
                        <select class="custom-select" name="tipocomprobante">
                            @foreach ($comprobantes as $comprobante)
                            <option value="{{ $comprobante->codigo }}">{{ $comprobante->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="flag" id="comprasflag">
                        <label class="custom-control-label" for="comprasflag">Filtrar data actual</label>
                    </div>
                </div>
                <input type="submit" class="btn btn-success">
            </form>
        </div>

        <div class="row">
            <div class="col-12" id="divcomprastable">
            
            </div>    
        </div>

        <script type="text/javascript" src="{{ asset('assets/js/proyecto/muestreo/compras/compras.js')}}"></script>
    </div>
</div>