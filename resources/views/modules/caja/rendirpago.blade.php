<div class="col-12">
    @include('layouts.uso')
    <div class="col-12 text-center">
        <h3 class="py-3">PAGOS A RENDIR</h1>
    </div>
    <div class="col-12">
        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
            <form id="formrendirpago">
                <input type="hidden" id="liquidaciondetalle_id" name="liquidaciondetalle_id" value="{{ $liquidacion->id }}">
                <div class="col-12 d-flex flex-wrap px-0">
                    <div class="col-xl-4 col-md-4 col-sm-6 col-xs-12 px-1">
                        <div class="form-group my-0">
                            <label>RUC <button onclick="validarsunat('#rucrendirpago','#rp-validador-ruc')" class="btn btn-primary"><i class="fas fa-user-secret"></i></button></label>
                            <input oninput="validacionunitariabasica('#rucrendirpago','#rp-validador-ruc',8,12)" id="rucrendirpago" name="ruc" class="form-control" type="number">
                            <small id="rp-validador-ruc" class="text-danger fade">Campo obligatorio</small>
                        </div>
                        <div class="form-group my-0">
                            <label>TIPO DOCUMENTO</label>
                            <select name="tipodocumento" class="custom-select">
                                <option selected>seleccione..</option>
                                @foreach ($documentos as $documentos)
                                <option value="{{ $documentos->id }}">{{ $documentos->codigo }} {{ $documentos->descripcion }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger fade">Campo obligatorio</small>
                        </div>
                        <div class="form-group my-0">
                            <label >N° DOCUMENTO</label>
                            <div class="col-12 d-flex flex-wrap px-0">
                                <div class="col-6 px-0">
                                    <input oninput="validacionunitariabasica('#codigodocumentorendirpago','#rp-validador-nrodocumento',3,5)" id="codigodocumentorendirpago" placeholder="COD" name="codigodocumento" class="form-control" type="text">
                                </div>
                                <div class="col-6 px-0">
                                    <input oninput="validacionunitariabasica('#numerodocumentorendirpago','#rp-validador-nrodocumento',3,5)" id="numerodocumentorendirpago" placeholder="NRO" name="documento" class="form-control" type="number">
                                </div>
                            </div>
                            <small class="text-danger fade" id="rp-validador-nrodocumento">Campo obligatorio</small>
                        </div>
                        <div class="form-group my-0">
                            <label>FECHA</label>
                            <input name="fecha" class="form-control" type="date">
                            <small class="text-danger fade">Campo obligatorio</small>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-6 col-xs-12 px-1">
                        <div class="form-group my-0">
                            <label>MONEDA</label>
                            <select name="moneda" class="custom-select">
                                <option selected>seleccione..</option>
                                @foreach ($monedas as $monedas)
                                <option value="{{ $monedas->id }}">{{ $monedas->descripcion }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger fade">Campo obligatorio</small>
                        </div>
                        <div class="form-group my-0">
                            <label>CONCEPTO</label>
                            <input oninput="validacionunitariabasica('#conceptorendirpago','#rp-validador-concepto',0,100)" id="conceptorendirpago" name="concepto" class="form-control" type="text">      
                            <small class="text-danger fade" id="rp-validador-concepto">Campo obligatorio</small>
                        </div>
                        <div class="form-group my-0">
                            <label>CONTABILIDAD</label>
                            <select name="contabilidad" class="custom-select">
                                <option selected>seleccione..</option>
                                @foreach ($codigocontable as $codigo)
                                <option value="{{ $codigo->id }}">{{ $codigo->descripcion }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger fade">Campo obligatorio</small>
                        </div>
                        <div class="form-group my-0">
                            <label>CENTRO COSTO</label>
                            <select name="centrocosto" class="custom-select">
                                <option selected>seleccione..</option>
                                @foreach ($centrocostos as $centrocostos)
                                <option value="{{ $centrocostos->id }}">{{ $centrocostos->descripcion }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger fade">Campo obligatorio</small>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-12 col-xs-12 px-1">
                        <div class="form-group my-0">
                            <label>MONTO</label>
                            <input name="monto" class="form-control" type="number">      
                            <small class="text-danger fade">Campo obligatorio</small>
                        </div>
                        <div class="form-check">
                            <input name="igv" class="form-check-input" type="checkbox">
                            <label for="form-check-label">CON IGV</label>
                        </div>
                        <div class="col-12" id="rendirpagototales">
                            
                        </div>
                        <div class="col-12 text-center py-3">
                            <input type="submit" class="btn btn-primary" value="ACEPTAR">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="divtablarendirpago" class="col-12">
            
        </div>
        <script type="text/javascript" src="{{ asset('assets/js/proyecto/caja/rendirpago/rendirpago.js')}}"></script>
    </div>
</div>