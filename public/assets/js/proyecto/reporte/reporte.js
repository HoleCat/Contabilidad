eventosreporte();

function agregarliberado(data) {
	if(document.querySelector('#tablareporteeliminadostable'))
	{
		let tabla = document.querySelector('#tablareporteeliminadostable');
		let tbody = tabla.querySelector('tbody');
		let tr = document.createElement('tr');

		let columnas =
		[
			'Periodo','Correlativo','Orden','FecEmision','FecVenci','TipoComp','NumSerie','AnoDua','NumComp',
			'NumTicket','TipoDoc','NroDoc','Nombre','BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3','IGVIPM3',
			'AdqGrava','ISC','Otros','Total','Moneda','TipoCam','FecOrigenMod','TipoCompMod','NumSerieMod',
			'AnoDuaMod','NumSerComOriMod','FecConstDetrac','NumConstDetrac','Retencion','ClasifBi','Contrato',
			'ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago','Estado'
		];
		let td = document.createElement('td');
		let btn = document.createElement('button');
		btn.setAttribute('class','btn btn-sucess');
		btn.addEventListener("click", function(e){
			$('#tablareporteeliminadostable').DataTables().destroy();
			cambiarregistro('/Reporte/Compra/Estimar',data.id,funcion)
			e.target.parent.parent.remove();
			$('#tablareporteeliminadostable').DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
		});	
		let icon = document.createElement('i');
		icon.setAttribute('class','fas fa-redo');
		btn.append(icon);
		td = data[element];
		tr.append(td);

		for (let index = 0; index < columnas.length; index++) {
			const element = columnas[index];
			td = document.createElement('td');
			td = data[element];
			tr.append(td);
		}

		tbody.append(tr);
	}
}

function confirmar(data){
	console.log(data);
}

function tablareportecomprasvalidacion(id)
{
	let botonesreporte = [
		{
			id_columnname: 'id',
		}
	];
	let botonesdetraccion = [
		{
			id_columnname: 'id',
		}
	];
	let checks = [
		{
			label: 'Liberar :',
			funcion: cambiarregistro,
			ruta: '/Reporte/Compras/Liberar',
			id_columnname: 'IdCool',
			confirm: confirmar,
			checkflag: 'Liberar',
			negative: 'no',
			positive: 'si',
		},
		{
			label: 'Excluir :',
			funcion: cambiarregistro,
			ruta: '/Reporte/Compras/Status',
			id_columnname: 'IdCool',
			confirm: confirmar,
			checkflag: 'Status',
			negative: 'no',
			positive: 'si',
		}
	];
	let formdata = new FormData();
	formdata.set('iduso',id);
	function confirmartabla(data) {
		crearselect('#selectarchivo1',data,'detracciones');
		crearselect('#selectarchivo2',data,'compras');
	}

	let cabecera1 = 
	[
		'IdUso','IdArchivo','Cuo','TipoCuenta','NumeroCuenta','NumeroConstancia','PeriodoTributario','RucProveedor',
		'NombreProveedor','TipoDocumentoAdquiriente','NumeroDocumentoAdquiriente','RazonSocialAdquiriente','FechaPago',
		'MontoDeposito','TipoBien','TipoOperacion','TipoComprobante','SerieComprobante','NumeroComprobante','NumeroPagoDetraciones',
		'ValidacionPorcentual','Base','ValidacionBase','TipoServicio','Opciones'
	]
	let columnas1 = 
	[
		'IdUso','IdArchivo','Cuo','TipoCuenta','NumeroCuenta','NumeroConstancia','PeriodoTributario','RucProveedor',
		'NombreProveedor','TipoDocumentoAdquiriente','NumeroDocumentoAdquiriente','RazonSocialAdquiriente','FechaPago',
		'MontoDeposito','TipoBien','TipoOperacion','TipoComprobante','SerieComprobante','NumeroComprobante','NumeroPagoDetraciones',
		'Porcentaje','Base','ValidacionBase','Denominacion'
	]

	let cabecera2 = 
	[	
		'LIBERAR','EXCLUIR','PERIODO','CUO','ORD','FECHA EMISION','FECHA VENCIMIENTO','T. COMPR.','N. SERIE','Año de DUA',
		'N. COMPROBANTE','N. TICKETS','T. DOC. IDENTIDAD','N. DOCUMENTO','APELLIDOS Y NOMBRE, DENOMINACIÓN SOCIAL',
		'B.Imponible OG','IGV Y/o IPM','B. Imponible OG y ONG','IGV Y/o IPM','B. Imponible ONG','IGV Y/o IPM',
		'Adquisiciones no gravadas','ISC','OTROS','TOTAL','MONEDA','TIPO DE CAMBIO','FECHA DOC. ORIGINAL',
		'T. COMPROBANTE DOC. ORIGINAL','N. SERIE DOC. ORIGINAL','Año de DUA','N. SERIE DOC. ORIGINAL',
		'Detracción: Fecha','Detracción: Número','Retención','Clasif. Bienes y servicios','Contrato','Error tipo 1',
		'Error tipo 2','Error tipo 3','Error tipo 4','Indicador de medio de pago','ESTADO','NumeroConstancia','FechaPago','Comentario','OPCIONES'
	]
	let columnas2 =
	[
		'Periodo','Correlativo','Orden','FecEmision','FecVenci','TipoComp','NumSerie','AnoDua','NumComp',
		'NumTicket','TipoDoc','NroDoc','Nombre','BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3','IGVIPM3',
		'AdqGrava','ISC','Otros','Total','Moneda','TipoCam','FecOrigenMod','TipoCompMod','NumSerieMod',
		'AnoDuaMod','NumSerComOriMod','FecConstDetrac','NumConstDetrac','Retencion','ClasifBi','Contrato',
		'ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago','Estado','NumeroConstancia','FechaPago','Comentario',
	]

	creartablafour('validacion',checks,formdata,'#cargareportecomprasfile','table table-bordered table-responsive','tablareporte1','#divreportecomprastable','/Reporte/Compras/Data',cabecera2,columnas2,true,confirmartabla,botonesreporte);
	creartablafive('dtr',formdata,'#cargadetraccioncompras','table table-bordered table-responsive','tablareporte2','#divdetraccioncomprastable','/Reporte/Compras/Data',cabecera1,columnas1,true,confirmartabla,botonesdetraccion);
}

function eventosreporte(){
	let id = document.querySelector('#uso_id').value;
	tablareportecomprasvalidacion(id);
	$('#reportecomprasfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#reportecompraslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#detraccioncomprasfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#detraccioncompraslabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#resultadorucfile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#resultadoruclabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#resultadocomprobantefile').change(function(e){
		let filename = this.files[0].name;
		let filelabel = document.querySelector('#resultadocomprobantelabel');
		filelabel.innerHTML = filename;
		console.log(filename);
	});
	$('#formresultadoruc').submit(function(event){
		event.preventDefault();
		let botonesdetraccion = [
			{
				id_columnname: 'id',
			}
        ];
		let form = document.querySelector('#formresultadoruc');
		let formdata = new FormData(form);
		function confirmartabla(data) {
			crearselect('#selectarchivoresultadoruc',data,'resultadosruc');
		}
		let cabecera = 
		[
			'NumeroRuc','RazonSocial','TipoContribuyente',
			'ProfesionOficio','NombreComercial','CondicionContribuyente',
			'EstadoContribuyente','FechaInscripcion','FechaInicioActividades',
			'Departamento','Provincia','Distrito','Direccion',
			'Telefono','Fax','ActividadComercioExterior','PrincipalCIIU',
			'CIIU1','CIIU2','RUS','BuenContribuyente','AgenteRetencion',
			'AgentePercepcionVtaInt','AgentePercepcionComLiq','Opciones'
		]
		
		let columnas = 
		[
			'NumeroRuc','RazonSocial','TipoContribuyente',
			'ProfesionOficio','NombreComercial','CondicionContribuyente',
			'EstadoContribuyente','FechaInscripcion','FechaInicioActividades',
			'Departamento','Provincia','Distrito','Direccion',
			'Telefono','Fax','ActividadComercioExterior','PrincipalCIIU',
			'CIIU1','CIIU2','RUS','BuenContribuyente','AgenteRetencion',
			'AgentePercepcionVtaInt','AgentePercepcionComLiq',
			
		]
		
		creartablafive('resultadoruc',formdata,'#cargaresultadoruc','table table-bordered table-responsive','tablareporte3','#divresultadoructable','/Reporte/Reporte/Consultaruc',cabecera,columnas,true,confirmartabla,botonesruc);
	});
	$('#formresultadocomprobantes').submit(function(event){
		event.preventDefault();
		let botonesdetraccion = [
			{
				id_columnname: 'id',
			}
        ];
		let form = document.querySelector('#formresultadocomprobantes');
		let formdata = new FormData(form);
		function confirmartabla(data) {
			crearselect('#selectarchivoresultadocomprobantes',data,'resultadoscomprobantes');
		}
		let cabecera = 
		[
			
		]
		
		let columnas = 
		[
			
		]
		
		creartablafive('resultadocomprobantes',formdata,'#cargaresultadocomprobantes','table table-bordered table-responsive','tablareporte4','#divresultadocomprobantestable','/Reporte/Reporte/Consultacomprobantes',cabecera,columnas,true,confirmartabla,botonescomprobantes);
	});
	$('#formdetraccioncompras').submit(function(event){
		event.preventDefault();
		let botonesdetraccion = [
			{
				id_columnname: 'id',
			}
        ];
		let form = document.querySelector('#formdetraccioncompras');
		let formdata = new FormData(form);
		function confirmartabla(data) {
			crearselect('#selectarchivo1',data,'detracciones');
			crearselect('#selectarchivo2',data,'compras');
		}
		let cabecera = 
		[
			'IdUso','IdArchivo','Cuo','TipoCuenta','NumeroCuenta','NumeroConstancia','PeriodoTributario','RucProveedor',
            'NombreProveedor','TipoDocumentoAdquiriente','NumeroDocumentoAdquiriente','RazonSocialAdquiriente','FechaPago',
            'MontoDeposito','TipoBien','TipoOperacion','TipoComprobante','SerieComprobante','NumeroComprobante','NumeroPagoDetraciones',
			'ValidacionPorcentual','Base','ValidacionBase','TipoServicio','Opciones'
		]
		
		let columnas = 
		[
			'IdUso','IdArchivo','Cuo','TipoCuenta','NumeroCuenta','NumeroConstancia','PeriodoTributario','RucProveedor',
            'NombreProveedor','TipoDocumentoAdquiriente','NumeroDocumentoAdquiriente','RazonSocialAdquiriente','FechaPago',
            'MontoDeposito','TipoBien','TipoOperacion','TipoComprobante','SerieComprobante','NumeroComprobante','NumeroPagoDetraciones',
			'Porcentaje','Base','ValidacionBase','Denominacion'
		]
		
		creartablafive('data',formdata,'#cargadetraccioncompras','table table-bordered table-responsive','tablareporte2','#divdetraccioncomprastable','/Reporte/Compras/Detraccion',cabecera,columnas,true,confirmartabla,botonesdetraccion);
	});
	$('#formcargareportecompras').submit(function(event){
		event.preventDefault();
		let botonesreporte = [
			{
				id_columnname: 'id',
			}
        ];
        let checks = [
			{
				label: 'Liberar :',
				funcion: cambiarregistro,
				ruta: '/Reporte/Compras/Liberar',
				id_columnname: 'IdCool',
				confirm: confirmar,
				checkflag: 'Liberar',
				negative: 'no',
				positive: 'si',
			},
			{
				label: 'Excluir :',
				funcion: cambiarregistro,
				ruta: '/Reporte/Compras/Status',
				id_columnname: 'IdCool',
				confirm: confirmar,
				checkflag: 'Status',
				negative: 'no',
				positive: 'si',
			}
		];
		let form = document.querySelector('#formcargareportecompras');
		let formdata = new FormData(form);
		function confirmartabla(data) {
			crearselect('#selectarchivo1',data,'detracciones');
			crearselect('#selectarchivo2',data,'compras');
		}
		let cabecera = 
		[	
			'LIBERAR','EXCLUIR','PERIODO','CUO','ORD','FECHA EMISION','FECHA VENCIMIENTO','T. COMPR.','N. SERIE','Año de DUA',
			'N. COMPROBANTE','N. TICKETS','T. DOC. IDENTIDAD','N. DOCUMENTO','APELLIDOS Y NOMBRE, DENOMINACIÓN SOCIAL',
			'B.Imponible OG','IGV Y/o IPM','B. Imponible OG y ONG','IGV Y/o IPM','B. Imponible ONG','IGV Y/o IPM',
			'Adquisiciones no gravadas','ISC','OTROS','TOTAL','MONEDA','TIPO DE CAMBIO','FECHA DOC. ORIGINAL',
			'T. COMPROBANTE DOC. ORIGINAL','N. SERIE DOC. ORIGINAL','Año de DUA','N. SERIE DOC. ORIGINAL',
			'Detracción: Fecha','Detracción: Número','Retención','Clasif. Bienes y servicios','Contrato','Error tipo 1',
			'Error tipo 2','Error tipo 3','Error tipo 4','Indicador de medio de pago','ESTADO','OPCIONES'
		]
		let columnas =
		[
			'Periodo','Correlativo','Orden','FecEmision','FecVenci','TipoComp','NumSerie','AnoDua','NumComp',
			'NumTicket','TipoDoc','NroDoc','Nombre','BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3','IGVIPM3',
			'AdqGrava','ISC','Otros','Total','Moneda','TipoCam','FecOrigenMod','TipoCompMod','NumSerieMod',
			'AnoDuaMod','NumSerComOriMod','FecConstDetrac','NumConstDetrac','Retencion','ClasifBi','Contrato',
			'ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago','Estado'
		]
		creartablafour('data',checks,formdata,'#cargareportecomprasfile','table table-bordered table-responsive','tablareporte1','#divreportecomprastable','/Reporte/Compras/Importar',cabecera,columnas,true,confirmartabla,botonesreporte);
	});
	$('#btn-exportar-mayorreporte').click(function(e){
		exportarmayorreporte();
	});
}