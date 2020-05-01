eventosventas();

var dataventas = '';

function eventosventas(){
	$('#ventasfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#ventaslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#formcargaventas').submit(function(event){
		event.preventDefault();
		function setarchivoventas(data) {
			var idarchivo = data.id;
			asignarvalor('#idarchivoventas',idarchivo);
			console.log(idarchivo);
		}
		cargararchivo('#formcargaventas','#cargaventasfile','/ImportarExcelVentas',setarchivoventas);
	});
	$('#formfiltroventas').submit(function(event){
		event.preventDefault();
		let botonesventas = [
			{
				texto: '<i class="fas fa-trash-alt"></i>',
				accion: 'borrardetalleliquidacion',
				ruta: '/Destroy/Tuvieja',
				id: 0
			}
		]
		let form = document.querySelector('#formfiltroventas');
		let formdata = new FormData(form);
		let cabecera = 
		['IdUso','IdArchivo','Periodo','Correlativo','Ordenado',
		'FecEmision','FecVenci','TipoComp','NumSerie','NumComp','NumTicket','TipoDoc',
		'NroDoc','Nombre','Export','BI','Desci','IGVIPMBI','IGVIPMDesc','ImporteExo',
		'ImporteIna','ISC','BIIGVAP','IGVAP','Otros','Total','Moneda','TipoCam',
		'FecOrigenMod','TipoCompMod','NumSerieMod','NumDocMod','Contrato','ErrorT1',
		'MedioPago','Estado','Opciones'];
		let columnas = 
		['IdUso','IdArchivo','Periodo','Correlativo','Ordenado',
		'FecEmision','FecVenci','TipoComp','NumSerie','NumComp','NumTicket','TipoDoc',
		'NroDoc','Nombre','Export','BI','Desci','IGVIPMBI','IGVIPMDesc','ImporteExo',
		'ImporteIna','ISC','BIIGVAP','IGVAP','Otros','Total','Moneda','TipoCam',
		'FecOrigenMod','TipoCompMod','NumSerieMod','NumDocMod','Contrato','ErrorT1',
		'MedioPago','Estado'];
		creartablaone(formdata,'#cargafiltroventas','table table-bordered table-responsive','tablaventas','#divventastable','/FiltrarExcelVentas',cabecera,columnas,true,confirmartabla,botonesventas);
	});
	$('#btn-exportar-mayorventas').click(function(e){
		exportarmayorventas();
	});
}

function confirmartabla(hola) {
	dataventas = hola;
	console.log('tabla cargada');
}

var identificadorventas = 0;

function exportarmayorventas() {
	console.log(JSON.stringify(dataventas));
	console.log(dataventas);
	var formdata = new FormData();
	formdata.set('data',JSON.stringify(dataventas));
	$.ajax({
		url: '/ExportExcelVentas',
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