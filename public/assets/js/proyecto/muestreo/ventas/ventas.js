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
	'NroDoc',
	'cliente',
	'IdUso',
	'IdArchivo',
	'Periodo',
	'Correlativo',
	'FecEmision',
	'FecVenci',
	'TipoComp',
	'NumSerie',
	'AnoDua',
	'NumComp',
	'NumTicket',
	'TipoDoc',
	'BIAG1',
	'IGVIPM1',
	'BIAG2',
	'IGVIPM2',
	'BIAG3',
	'AdqGrava',
	'IGVIPM3',
	'AdqGrava',
	'ISC',
	'Otros',
	'Total',
	'Moneda',
	'TipoCam',
	'FecOrigenMod',
	'TipoCompMod',
	'NumSerieMod',
	'AnoDuaMod',
	'NumSerComOriMod',
	'FecConstDetrac',
	'NumConstDetrac',
	'Retencion',
	'ClasifBi',
	'Contrato',
	'ErrorT1',
	'ErrorT2',
	'ErrorT3',
	'ErrorT4',
	'MedioPago',
	'Estado'
];