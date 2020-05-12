eventoscompras();

function eventoscompras(){
	$('#formeliminardata').submit(function(e){
		e.preventDefault();
		function confirmar(data) {
			crearselect('#usoarchivoselect',data,'archivos');
		}
		let formdata = new FormData(e.target);
		ejecutarruta(formdata,'/Muestreo/Compras/Destroy',confirmar);
	});
	$('#usoarchivoselect').change(function(e){
		let idarchivo = e.target.value;
		asignarvalor('#idarchivocompras',idarchivo);
		console.log(idarchivo);
	});
	$('#comprasfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#compraslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#formcargacompras').submit(function(event){
		event.preventDefault();
		function setarchivocompras(data) {
			var idarchivo = data.id;
			asignarvalor('#idarchivocompras',idarchivo);
		}
		cargararchivo('#formcargacompras','#cargacomprasfile','/ImportarExcelCompra',setarchivocompras);
	});
	$('#formfiltrocompras').submit(function(event){
		event.preventDefault();
		let botonescompras = [
			{
				texto: '<i class="fas fa-trash-alt"></i>',
				accion: 'borrardetalleliquidacion',
				ruta: '/Destroy/Tuvieja',
				id: 0
			}
		];
		let form = document.querySelector('#formfiltrocompras');
		let formdata = new FormData(form);
		let columnas = ['NroDoc','cliente','Periodo','Correlativo','FecEmision','FecVenci','TipoComp','NumSerie',
		'AnoDua','NumComp','NumTicket','TipoDoc','BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3','IGVIPM3','AdqGrava','ISC',
		'Otros','Total','Moneda','TipoCam','FecOrigenMod','TipoCompMod','NumSerieMod','AnoDuaMod','NumSerComOriMod','FecConstDetrac',
		'NumConstDetrac','Retencion','ClasifBi','Contrato','ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago','Estado'];
		let cabecera = ['NroDoc','cliente','Periodo','Correlativo','FecEmision','FecVenci','TipoComp','NumSerie',
		'AnoDua','NumComp','NumTicket','TipoDoc','BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3','IGVIPM3','AdqGrava','ISC',
		'Otros','Total','Moneda','TipoCam','FecOrigenMod','TipoCompMod','NumSerieMod','AnoDuaMod','NumSerComOriMod','FecConstDetrac',
		'NumConstDetrac','Retencion','ClasifBi','Contrato','ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago','Estado','Opciones'];
		creartablatwo(formdata,'#cargafiltrocompras','table table-bordered table-responsive','tablacompras','#divcomprastable','/FiltrarExcelCompra',cabecera,columnas,true,confirmartabla,botonescompras);
	});
}

function confirmartabla(hola) {
	datacompras = hola;
	console.log('tabla cargada');
}