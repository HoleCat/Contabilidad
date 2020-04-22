eventosventas();

var dataventas = '';

function eventosventas(){
	$('#ventasfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#ventaslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
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

var botonesventas = [
	{
		texto: '<i class="fas fa-trash-alt"></i>',
		accion: 'borrardetalleliquidacion',
		ruta: '/Destroy/Tuvieja',
		id: 0
	}
]

$(function(){
	creartabla('table table-responsive','tablaventas','#formventas','#divventastable','/ImportExcelVentas',cabeceraventas,true,confirmartabla,botonesventas,identificadorventas); 
})

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
 
var cabeceraventas = [
	'IdUso',
	'IdArchivo',
	'Periodo',
	'Correlativo',
	'Ordenado',
	'FecEmision',
	'FecVenci',
	'TipoComp',
	'NumSerie',
	'NumComp',
	'NumTicket',
	'TipoDoc',
	'NroDoc',
	'Nombre',
	'Export',
	'BI',
	'Desci',
	'IGVIPMBI',
	'IGVIPMDesc',
	'ImporteExo',
	'ImporteIna',
	'ISC',
	'BIIGVAP',
	'IGVAP',
	'Otros',
	'Total',
	'Moneda',
	'TipoCam',
	'FecOrigenMod',
	'TipoCompMod',
	'NumSerieMod',
	'NumDocMod',
	'Contrato',
	'ErrorT1',
	'MedioPago',
	'Estado'
];