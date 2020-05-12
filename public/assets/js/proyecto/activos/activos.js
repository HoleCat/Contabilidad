eventosactivos();

var dataactivos = '';

function eventosactivos(){
	$('#formeliminardata').submit(function(e){
		e.preventDefault();
		function confirmar(data) {
			crearselect('#usoarchivoselect',data)
		}
		let formdata = new FormData(e.target);
		ejecutarruta(formdata,'/Activos/Destroy',confirmar);
	});
	$('#usoarchivoselect').change(function(e){
		let idarchivo = e.target.value;
		asignarvalor('#idarchivoactivos',idarchivo);
		console.log(idarchivo);
	});
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
        let columnas = ['Codigo','CuentaContable','Descipcion','Marca','Modelo','NumeroSeriePlaca','CostoFin','Adquisicion','Mejoras'
        ,'RetirosBajas','Otros','ValorHistorico','AjusteInflacion','ValorAjustado'
        ,'CostoNetoIni','FecAdquisicion','FecInicio','Metodo','NroDoc','PorcDepreciacion','DepreAcumulada','DepreEjercicio','DepreRelacionada','DepreOtros'
        ,'DepreHistorico','DepreAjusInflacion','DepreAcuInflacion','CostoHistorico','DepreAcuTributaria','CostoNetoIniTributaria','DepreEjercicioTributaria'
        ,'FecBaja','RATIO','DEPRESIACION','DEPRESIACION_VALIDADA','ANALISISn1','ANALISISn2',];
        let cabecera = ['Codigo','CuentaContable','Descipcion','Marca','Modelo','NumeroSeriePlaca','CostoFin','Adquisicion','Mejoras',
        'RetirosBajas','Otros','ValorHistorico','AjusteInflacion','ValorAjustado'
        ,'CostoNetoIni','FecAdquisicion','FecInicio','Metodo','NroDoc','PorcDepreciacion','DepreAcumulada','DepreEjercicio','DepreRelacionada','DepreOtros'
        ,'DepreHistorico','DepreAjusInflacion','DepreAcuInflacion','CostoHistorico','DepreAcuTributaria','CostoNetoIniTributaria','DepreEjercicioTributaria'
        ,'FecBaja','RATIO','DEPRESIACION','DEPRESIACION_VALIDADA','ANALISISn1','ANALISISn2','OPCIONES'];
		creartablaone(formdata,'#cargafiltroactivos','table table-bordered table-responsive','tablaactivos','#divactivostable','/Activos/Filtrar',cabecera,columnas,true,confirmartabla,botonescompras);
	});
}

function confirmartabla() {
    console.log('tabla filtro activos cargada');
}


