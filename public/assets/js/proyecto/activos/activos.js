eventosactivos();

var dataactivos = '';

function eventosactivos(){
	$('#activosfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#activoslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#formcargaactivos').submit(function(event){
		event.preventDefault();
		function setarchivo(data) {
			var idarchivo = data.id;
			asignarvalor('#idarchivoactivos',idarchivo);
		}
		cargararchivo('#formcargaactivos','#cargaactivosfile','/Activos/Importar',setarchivo);
    });
    $('#formfiltroactivos').submit(function(event){
		event.preventDefault();
		let botonescompras = [
			{
				texto: '<i class="fas fa-trash-alt"></i>',
				accion: 'borrardetalleliquidacion',
				ruta: '/Destroy/Tuvieja',
				id_columnname: 0
			}
		]
		let form = document.querySelector('#formfiltroactivos');
		let formdata = new FormData(form);
        let columnas = ['id','IdUso','IdArchivo','Codigo','CuentaContable','Descipcion','Marca','Modelo','NumeroSeriePlaca','CostoFin','Adquisicion','Mejoras'
        ,'RetirosBajas','Otros','ValorHistorico','AjusteInflacion','ValorAjustado'
        ,'CostoNetoIni','FecAdquisicion','FecInicio','Metodo','NroDoc','PorcDepreciacion','DepreAcumulada','DepreEjercicio','DepreRelacionada','DepreOtros'
        ,'DepreHistorico','DepreAjusInflacion','DepreAcuInflacion','CostoHistorico','DepreAcuTributaria','CostoNetoIniTributaria','DepreEjercicioTributaria'
        ,'FecBaja','created_at','updated_at','RATIO','DEPRESIACION'];
        let cabecera = ['id','IdUso','IdArchivo','Codigo','CuentaContable','Descipcion','Marca','Modelo','NumeroSeriePlaca','CostoFin','Adquisicion','Mejoras',
        'RetirosBajas','Otros','ValorHistorico','AjusteInflacion','ValorAjustado'
        ,'CostoNetoIni','FecAdquisicion','FecInicio','Metodo','NroDoc','PorcDepreciacion','DepreAcumulada','DepreEjercicio','DepreRelacionada','DepreOtros'
        ,'DepreHistorico','DepreAjusInflacion','DepreAcuInflacion','CostoHistorico','DepreAcuTributaria','CostoNetoIniTributaria','DepreEjercicioTributaria'
        ,'FecBaja','created_at','updated_at','RATIO','DEPRESIACION','OPCIONES'];
		creartablaone(formdata,'#cargafiltroactivos','table table-bordered table-responsive','tablaactivos','#divactivostable','/Activos/Filtrar',cabecera,columnas,true,confirmartabla,botonescompras);
	});
}

function confirmartabla() {
    console.log('tabla filtro activos cargada');
}


