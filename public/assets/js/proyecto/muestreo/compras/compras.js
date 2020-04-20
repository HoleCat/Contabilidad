eventoscompras();

var datacompras = '';

function eventoscompras(){
	$('#comprasfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#compraslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#btn-exportar-mayorcompras').click(function(e){
		exportarmayorcompras();
	});
}

function confirmartabla(hola) {
	datacompras = hola;
	console.log('tabla cargada');
}

var identificadorcompras = 0;

var botonescompras = [
	{
		texto: '<i class="fas fa-trash-alt"></i>',
		accion: 'borrardetalleliquidacion',
		ruta: '/Destroy/Tuvieja',
		id: 0
	}
]

$(function(){
	creartabla('table table-responsive','tablacompras','#formcompras','#divcomprastable','/ImportExcelCompra',cabeceracompras,true,confirmartabla,botonescompras,identificadorcompras); 
})

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
			/*let link = document.createElement('a');
			link.setAttribute('href',data);
			link.click();*/
		}
	}).done(function(){
		
	});
}
 
var cabeceracompras = [
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