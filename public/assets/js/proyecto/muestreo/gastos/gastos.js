eventosgastos();

var datagastos = '';

function eventosgastos(){
	$('#gastosfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#gastoslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#formcargagastos').submit(function(event){
		event.preventDefault();
		function setarchivogastos(data) {
			var idarchivo = data.id;
			asignarvalor('#idarchivogastos',idarchivo);
			console.log(idarchivo);
		}
		cargararchivo('#formcargagastos','#cargagastosfile','/ImportarExcelVentas',setarchivogastos);
	});
	$('#formfiltrogastos').submit(function(event){
		event.preventDefault();
		let botonesgastos = [
			{
				texto: '<i class="fas fa-trash-alt"></i>',
				accion: 'borrardetalleliquidacion',
				ruta: '/Destroy/Tuvieja',
				id: 0
			}
		]
		let form = document.querySelector('#formfiltrogastos');
		let formdata = new FormData(form);
		let cabecera = [
			'NroDoc','cliente','IdUso','IdArchivo','Periodo','Correlativo',
			'FecEmision','FecVenci','TipoComp','NumSerie','AnoDua','NumComp',
			'NumTicket','TipoDoc','BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3',
			'AdqGrava','IGVIPM3','AdqGrava','ISC','Otros','Total','Moneda',
			'TipoCam','FecOrigenMod','TipoCompMod','NumSerieMod','AnoDuaMod',
			'NumSerComOriMod','FecConstDetrac','NumConstDetrac','Retencion','ClasifBi',
			'Contrato','ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago','Estado','Opciones'
		];
		let columnas = [
			'NroDoc','cliente','IdUso','IdArchivo','Periodo','Correlativo',
			'FecEmision','FecVenci','TipoComp','NumSerie','AnoDua','NumComp',
			'NumTicket','TipoDoc','BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3',
			'AdqGrava','IGVIPM3','AdqGrava','ISC','Otros','Total','Moneda',
			'TipoCam','FecOrigenMod','TipoCompMod','NumSerieMod','AnoDuaMod',
			'NumSerComOriMod','FecConstDetrac','NumConstDetrac','Retencion','ClasifBi',
			'Contrato','ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago','Estado'
		];
		creartablaone(formdata,'#cargafiltrogastos','table table-bordered table-responsive','tablagastos','#divgastostable','/FiltrarExcelGastos',cabecera,columnas,true,confirmartabla,botonesgastos);
	});
	$('#btn-exportar-mayorgastos').click(function(e){
		exportarmayorgastos();
	});
}

function confirmartabla(hola) {
	datagastos = hola;
	console.log('tabla cargada');
}

function exportarmayorgastos() {
	console.log(JSON.stringify(datagastos));
	console.log(datagastos);
	var formdata = new FormData();
	formdata.set('data',JSON.stringify(datagastos));
	$.ajax({
		url: '/ExportExcelGastos',
		type: 'POST',
		data: formdata,
		processData: false,
		contentType: false,
		success: function(data){
			console.log(data)
			/*let link = document.createElement('a');
			link.setAttribute('href',data);
			link.click();*/
		}
	}).done(function(){
		
	});
}
 
