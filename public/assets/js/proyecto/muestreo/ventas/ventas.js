eventosventas();

var dataventas = '';

function eventosventas(){
	$('#formeliminardata').submit(function(e){
		e.preventDefault();
		function confirmar(data) {
			crearselect('#usoarchivoselect',data)
		}
		let formdata = new FormData(e.target);
		ejecutarruta(formdata,'/Muestreo/Ventas/Destroy',confirmar);
	});
	$('#usoarchivoselect').change(function(e){
		let idarchivo = e.target.value;
		asignarvalor('#idarchivoventas',idarchivo);
		console.log(idarchivo);
	});
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
		['Periodo','Correlativo','Ordenado',
		'FecEmision','FecVenci','TipoComp','NumSerie','NumComp','NumTicket','TipoDoc',
		'NroDoc','Cliente','Export','BI','Desci','IGVIPMBI','IGVIPMDesc','ImporteExo',
		'ImporteIna','ISC','BIIGVAP','IGVAP','Otros','Total','Moneda','TipoCam',
		'FecOrigenMod','TipoCompMod','NumSerieMod','NumDocMod','Contrato','ErrorT1',
		'MedioPago','Estado','Opciones'];
		let columnas = 
		['Periodo','Correlativo','Ordenado',
		'FecEmision','FecVenci','TipoComp','NumSerie','NumComp','NumTicket','TipoDoc',
		'NroDoc','cliente','Export','BI','Desci','IGVIPMBI','IGVIPMDesc','ImporteExo',
		'ImporteIna','ISC','BIIGVAP','IGVAP','Otros','Total','Moneda','TipoCam',
		'FecOrigenMod','TipoCompMod','NumSerieMod','NumDocMod','Contrato','ErrorT1',
		'MedioPago','Estado'];
		function confirmartabla(data) {
			console.log('tabla cargada');
			crearselect('#usoarchivoselect',data);
		}
		creartablatwo(formdata,'#cargafiltroventas','table table-bordered table-responsive','tablaventas','#divventastable','/FiltrarExcelVentas',cabecera,columnas,true,confirmartabla,botonesventas);
	});
}

var identificadorventas = 0;