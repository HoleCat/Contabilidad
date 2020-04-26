<div class="col-12">
    @include('layouts.uso')
    <div class="col-12 text-center">
        <h3 class="py-3">ACTIVOS</h1>
        <h2 class="py-0">depresiacion de activos</h2>
    </div>
    <div class="col-12">
        <div class="row">
            <form id="formactivos" class="text-center">
            <input type="hidden" name="iduso" value="{{$uso->id}}">
            <input type="hidden" id="idarchivo" name="idarchivo" value="">
                <div class="custom-file px-1">
                    <input type="file" class="custom-file-input" id="activosfile" name="myfile">
                    <label id="activoslabel" class="custom-file-label" for="activosfile"></label>
                </div>
                <div class="d-flex flex-wrap text-left">
                    <div class="col-6 py-1 px-1">
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
                    <div class="col-6 py-1 px-1">
                        <label for="">CANTIDAD DE REGISTOS</label>
                        <input class="form-control" type="number" name="cantidad">
                    </div>
                    <div class="col-6 py-1 px-1">
                        <label for="">FECHA INICIAL</label>
                        <input class="form-control" type="date" name="fechainicial">
                    </div>
                    <div class="col-6 py-1 px-1">
                        <label for="">FECHA FINAL</label>
                        <input class="form-control" type="date" name="fechafin">
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="flag" id="activosflag">
                        <label class="custom-control-label" for="activosflag">Filtrar data actual</label>
                    </div>
                </div>
                <input type="submit" class="btn btn-success">
            </form>
        </div>
        <div class="row">
            <div class="col-12" id="divactivostable">
            
            </div>    
        </div>
        <script type="text/javascript" src="{{ asset('assets/js/proyecto/activos/activos.js')}}"></script>
    </div>
</div>