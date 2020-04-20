<div class="col-12">
    @include('layouts.uso')
    <div class="col-12 text-center">
        <h3 class="py-3">IMPORTACION DE BALANCE</h1>
    </div>
    <div class="col-12 py-1 px-0 text-left">
        <form action="/Balance/Exportar" method="GET">
            <button type="submit" id="btn-exportar-mayorbalance" class="btn btn-warning">Exportar en excel</button>
        </form>
    </div>
    <div class="col-12">
        <div class="row">
            <form id="formbalance" class="text-center" enctype="multipart/form-data">
            <input type="hidden" name="iduso" value="{{$uso->id}}">
            <input type="hidden" id="idarchivobalance" name="idarchivo" value="">
                <div class="custom-file px-1">
                    <input type="file" class="custom-file-input" id="balancefile" name="myfile">
                    <label id="balancelabel" class="custom-file-label" for="balancefile"></label>
                </div>
                <div class="d-flex flex-wrap text-left">
                    <div class="col-6 py-1 px-1">
                        <label for="">IMPORTE MINIMO</label>
                        <input class="form-control" type="number" name="importeminimo">
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="flag" id="balanceflag">
                        <label class="custom-control-label" for="balanceflag">Filtrar data actual</label>
                    </div>
                </div>
                <input type="submit" class="btn btn-success">
            </form>
        </div>

        <div class="row">
            <div class="col-12" id="divbalancetable">
            
            </div>    
        </div>

        <script type="text/javascript" src="{{ asset('assets/js/proyecto/balance/balance.js')}}"></script>
    </div>
</div>