eventosactivos();

var dataactivos = '';

function eventosactivos(){
	$('#activosfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#activoslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#btn-exportar-mayoractivos').click(function(e){
		exportarmayoractivos();
	});
}

function confirmartabla(hola) {
	dataactivos = hola;
	console.log('tabla cargada');
}

var identificadoractivos = 0;

var botonesactivos = [
	{
		texto: '<i class="fas fa-trash-alt"></i>',
		accion: 'borrardetalleliquidacion',
		ruta: '/Destroy/Tuvieja',
		id: 0
	}
]

$(function(){
	creartabla('table table-responsive','tablaactivos','#formactivos','#divactivostable','/Activos/Importar',cabeceraactivos,true,confirmartabla,botonesactivos,identificadoractivos); 
})

function exportarmayoractivos() {
	console.log(JSON.stringify(dataactivos));
	console.log(dataactivos);
	var formdata = new FormData();
	formdata.set('data',JSON.stringify(dataactivos));
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
 
var cabeceraactivos = [
    'id',
    'IdUso',
    'IdArchivo',
    'Codigo',
    'CuentaContable',
    'Descipcion',
    'Marca',
    'Modelo',
    'NumeroSeriePlaca',
    'CostoFin',
    'Adquisicion',
    'Mejoras',
    'RetirosBajas',
    'Otros',
    'ValorHistorico',
    'AjusteInflacion',
    'ValorAjustado',
    'CostoNetoIni',
    'FecAdquisicion',
    'FecInicio',
    'Metodo',
    'NroDoc',
    'PorcDepreciacion',
    'DepreAcumulada',
    'DepreEjercicio',
    'DepreRelacionada',
    'DepreOtros',
    'DepreHistorico',
    'DepreAjusInflacion',
    'DepreAcuInflacion',
    'CostoHistorico',
    'DepreAcuTributaria',
    'CostoNetoIniTributaria',
    'DepreEjercicioTributaria',
    'FecBaja',
    'FecAdquisicion',
    'procentaje',
    'meses_util',
    'DispMes',
    'DispMes_Cal',
    'diferencia_meses',
    'diferencia_meses_cal',
    'diferencia_dias',
    'depAnul',
    'depMensual',
    'depDiara',
    'depActual',
    'depActualDays'
];


