
<ul class="list-group list-group-flush">
    <li class="list-group-item">USOS DEL APLICATIVO</li>
</ul>
<ul class="list-group list-group-flush">
    <li class="list-group-item">
        <div class="col-12 d-flex">
            <div class="col-8">
                <input type="text" id="inpusoscliente" class="form-control">
            </div>
            <div class="col-4">
                <input type="submit" class="btn btn-success">
            </div>
        </div>
    </li>
    <li class="list-group-item" id="tablausoseguimiento">
        
    </li>
</ul>
<script>
    var cabecerasusoseguimiento = ['referencia','id'];
    var botonesseguimiento = [
	{
		texto: '<i class="fas fa-trash-alt"></i>',
		accion: 'borrardetalleliquidacion',
		ruta: '/Destroy/Tuvieja',
		id: 0
	}
    ];
    
    $('#inpusoscliente').change(function(){
        var seguimientoinputdata = new FormData();
        var referencia = document.querySelector('#inpusoscliente').value;
        seguimientoinputdata.append('referencia',referencia);
        creartablabasica(seguimientoinputdata,'table table-bordered','tablausoseguimiento','#tablausoseguimiento','/Seguimiento/Data',cabecerasusoseguimiento,false,botonesseguimiento,0);
    })
</script>
