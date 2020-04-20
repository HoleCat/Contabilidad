eventosgastos();

var datagastos = '';

function eventosgastos(){
	$('#gastosfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#gastoslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#btn-exportar-mayorgastos').click(function(e){
		exportarmayorgastos();
	});
}

function confirmartabla(hola) {
	datagastos = hola;
	console.log('tabla cargada');
}

var identificadorgastos = 0;

var botonesgastos = [
	{
		texto: '<i class="fas fa-trash-alt"></i>',
		accion: 'borrardetalleliquidacion',
		ruta: '/Destroy/Tuvieja',
		id: 0
	}
]

$(function(){
	creartabla('table table-responsive','tablagastos','#formgastos','#divgastostable','/ImportExcelGastos',cabeceragastos,true,confirmartabla,botonesgastos,identificadorgastos); 
})

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
 
var cabeceragastos = [
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