eventoscompras();

function eventoscompras(){
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
		]
		let form = document.querySelector('#formfiltrocompras');
		let formdata = new FormData(form);
		let columnas = ['NroDoc','cliente','Periodo','Correlativo','FecEmision','FecVenci','TipoComp','NumSerie',
		'AnoDua','NumComp','NumTicket','TipoDoc','BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3','AdqGrava','IGVIPM3','AdqGrava','ISC',
		'Otros','Total','Moneda','TipoCam','FecOrigenMod','TipoCompMod','NumSerieMod','AnoDuaMod','NumSerComOriMod','FecConstDetrac',
		'NumConstDetrac','Retencion','ClasifBi','Contrato','ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago','Estado'];
		let cabecera = ['NroDoc','cliente','Periodo','Correlativo','FecEmision','FecVenci','TipoComp','NumSerie','AnoDua','NumComp',
		'NumTicket','TipoDoc','NroDoc','Nombre','BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3','IGVIPM3','AdqGrava','ISC','Otros','Total',
		'Moneda','TipoCam','FecOrigenMod','TipoCompMod','NumSerieMod','AnoDuaMod','NumSerComOriMod','FecConstDetrac','NumConstDetrac',
		'Retencion','ClasifBi','Contrato','ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago','Estado','Opciones'];
		creartablaone(formdata,'#cargafiltrocompras','table table-bordered table-responsive','tablacompras','#divcomprastable','/FiltrarExcelCompra',cabecera,columnas,true,confirmartabla,botonescompras);
	});
	$('#btn-exportar-mayorcompras').click(function(e){
		exportarmayorcompras();
	});
}

function confirmartabla(hola) {
	datacompras = hola;
	console.log('tabla cargada');
}

function exportarmayorcompras() {
	console.log(JSON.stringify(datacompras));
	console.log(datacompras);
	var formdata = new FormData();
	formdata.set('data',JSON.stringify(datacompras));
	$.ajax({
		url: '/ExportExcelCompra',
		type: 'POST',
		data: formdata,
		processData: false,
		contentType: false,
		success: function(data){
			console.log(data)
		}
	}).done(function(){
		
	});
}