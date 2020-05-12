function borrarregistro(id,ruta) {
	let formData = new FormData();
	formData.set('id',id);
	$.ajax({
		url: ruta,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function(data){
			
		}
	}).done(function(){
		if(datatable == true) {
            $('#' + tabla).DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
		}
		funcion(dataresultado);
	});
}

function creartablavalidada(validacion,clases,tabla,detonador,contenedor,ruta,cabeceras,datatable,funcion,botones,identificador) {
	var dataresultado;
	var elvalidator = false;
	$(detonador).submit(function(e){
		event.preventDefault();
		elvalidator = validacion();
		if(elvalidator==true)
		{
			var formData = new FormData($(this)[0]);
			var elems= document.querySelectorAll('input[type=checkbox]');
			for (var i=0;i<elems.length;i++)
			{
				var isChecked =elems[i].checked;
				var type = elems[i].getAttribute('name');
				if(isChecked == false){
					formData.set(type, 1);
				} else {
					formData.set(type, 0);
				}
			}
			$.ajax({
				url: ruta,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(data){
					dataresultado = data;
					$(contenedor).html('');
					var table;
					var tbody;
					var thead;
					var tr;
					var th;
					var td;
	
					table = document.createElement('table');
					table.setAttribute('id',tabla);
					table.setAttribute('class',clases);
					tbody = document.createElement('tbody');
					thead = document.createElement('thead');
	
					const cantcolumnas = cabeceras.length;
					const columnas = cabeceras;
	
					tr = document.createElement('tr');
					for (let index = 0; index < cantcolumnas; index++) {
						const cabecera = cabeceras[index];
						th = document.createElement('th');
						th.innerHTML = cabecera;
						tr.append(th);
					}
					if(botones){
						th = document.createElement('th');
						th.innerHTML = 'Opciones';
						tr.append(th);
					}
					thead.append(tr);
	
					for (let row = 0; row < data.length; row++) {
						const fila = data[row];
						tr = document.createElement('tr');
						for (let column = 0; column < cantcolumnas; column++) {
							const dato = data[row][columnas[column]];
							td = document.createElement('td');
							td.innerHTML = dato;
							tr.append(td);
						}
						botones.forEach(btn => {
							btn.id = identificador;
							td = document.createElement('td');
							let boton = document.createElement('button');
							boton.setAttribute('class','btn btn-danger');
							boton.innerHTML = btn.texto;
							boton.setAttribute('onclick',btn.accion+'('+btn.id+','+"'"+btn.ruta+"')");
							td.append(boton);
							tr.append(td);
						});
						tbody.append(tr);
					}
					table.append(thead);
					table.append(tbody);
					document.querySelector(contenedor).append(table);
				}
			}).done(function(){
				if(datatable == true) {
                    $('#' + tabla).DataTable({
                        "iDisplayLength": 5,
                        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
                    });
				}
				funcion(dataresultado);
			});
		} else {
			confirmacionok();
		}
	});
	
}

function creartabla(clases,tabla,detonador,contenedor,ruta,cabeceras,datatable,funcion,botones,identificador) {
	let dataresultado;
	$(detonador).submit(function(e){
		event.preventDefault();
		var formData = new FormData($(this)[0]);
		var elems= document.querySelectorAll('input[type=checkbox]');
        for (var i=0;i<elems.length;i++)
        {
            var isChecked =elems[i].checked;
            var type = elems[i].getAttribute('name');
            if(isChecked == false){
                formData.set(type, 1);
            } else {
                formData.set(type, 0);
            }
        }
		$.ajax({
			url: ruta,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(data){
				dataresultado = data;
				$(contenedor).html('');
				var table;
				var tbody;
				var thead;
				var tr;
				var th;
				var td;

				table = document.createElement('table');
				table.setAttribute('id',tabla);
				table.setAttribute('class',clases);
				tbody = document.createElement('tbody');
				thead = document.createElement('thead');

				const cantcolumnas = cabeceras.length;
				const columnas = cabeceras;

				tr = document.createElement('tr');
				for (let index = 0; index < cantcolumnas; index++) {
					const cabecera = cabeceras[index];
					th = document.createElement('th');
					th.innerHTML = cabecera;
					tr.append(th);
				}
				if(botones){
					th = document.createElement('th');
					th.innerHTML = 'Opciones';
					tr.append(th);
				}
				thead.append(tr);

				for (let row = 0; row < data.length; row++) {
					const fila = data[row];
					tr = document.createElement('tr');
					for (let column = 0; column < cantcolumnas; column++) {
						const dato = data[row][columnas[column]];
						td = document.createElement('td');
						td.innerHTML = dato;
						tr.append(td);
					}
					botones.forEach(btn => {
						btn.id = identificador;
						td = document.createElement('td');
						let boton = document.createElement('button');
						boton.setAttribute('class','btn btn-danger');
						boton.innerHTML = btn.texto;
						boton.setAttribute('onclick',btn.accion+'('+btn.id+','+"'"+btn.ruta+"')");
						td.append(boton);
						tr.append(td);
					});
					tbody.append(tr);
				}
				table.append(thead);
				table.append(tbody);
				document.querySelector(contenedor).append(table);
			}
		}).done(function(){
			if(datatable == true) {
                $('#' + tabla).DataTable({
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
                });
			}
			funcion(dataresultado);
		});
	});
}

function creartablaahora(parametros,clases,tabla,contenedor,ruta,cabeceras,datatable,funcion,botones,identificador) {
	let dataresultado;
	var formData = new FormData();
	parametros.forEach(param => {
		formData.set(param.header,param.value);
	});
	$.ajax({
		url: ruta,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function(data){
			dataresultado = data;
			$(contenedor).html('');
			var table;
			var tbody;
			var thead;
			var tr;
			var th;
			var td;

			table = document.createElement('table');
			table.setAttribute('id',tabla);
			table.setAttribute('class',clases);
			tbody = document.createElement('tbody');
			thead = document.createElement('thead');

			const cantcolumnas = cabeceras.length;
			const columnas = cabeceras;

			tr = document.createElement('tr');
			for (let index = 0; index < cantcolumnas; index++) {
				const cabecera = cabeceras[index];
				th = document.createElement('th');
				th.innerHTML = cabecera;
				tr.append(th);
			}
			if(botones){
				th = document.createElement('th');
				th.innerHTML = 'Opciones';
				tr.append(th);
			}
			thead.append(tr);

			for (let row = 0; row < data.length; row++) {
				const fila = data[row];
				tr = document.createElement('tr');
				for (let column = 0; column < cantcolumnas; column++) {
					const dato = data[row][columnas[column]];
					td = document.createElement('td');
					td.innerHTML = dato;
					tr.append(td);
				}
				botones.forEach(btn => {
					btn.id = identificador;
					td = document.createElement('td');
					let boton = document.createElement('button');
					boton.setAttribute('class','btn btn-danger');
					boton.innerHTML = btn.texto;
					boton.setAttribute('onclick',btn.accion+'('+btn.id+','+"'"+btn.ruta+"')");
					td.append(boton);
					tr.append(td);
				});
				tbody.append(tr);
			}
			table.append(thead);
			table.append(tbody);
			document.querySelector(contenedor).append(table);
		}
	}).done(function(){
		if(datatable == true) {
            $('#' + tabla).DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
		}
		funcion(dataresultado);
	});

}

function creartablabasica(formData,clases,tabla,contenedor,ruta,cabeceras,datatable,botones,identificador) {
	var dataresultado = '';
	$.ajax({
		url: ruta,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function(data){
			dataresultado = data;
			$(contenedor).html('');
			var table;
			var tbody;
			var thead;
			var tr;
			var th;
			var td;

			table = document.createElement('table');
			table.setAttribute('id',tabla);
			table.setAttribute('class',clases);
			tbody = document.createElement('tbody');
			thead = document.createElement('thead');

			const cantcolumnas = cabeceras.length;
			const columnas = cabeceras;

			tr = document.createElement('tr');
			for (let index = 0; index < cantcolumnas; index++) {
				const cabecera = cabeceras[index];
				th = document.createElement('th');
				th.innerHTML = cabecera;
				tr.append(th);
			}
			if(botones){
				th = document.createElement('th');
				th.innerHTML = 'Opciones';
				tr.append(th);
			}
			thead.append(tr);

			for (let row = 0; row < data.length; row++) {
				const fila = data[row];
				tr = document.createElement('tr');
				for (let column = 0; column < cantcolumnas; column++) {
					const dato = data[row][columnas[column]];
					td = document.createElement('td');
					td.innerHTML = dato;
					tr.append(td);
				}
				botones.forEach(btn => {
					btn.id = identificador;
					td = document.createElement('td');
					let boton = document.createElement('button');
					boton.setAttribute('class','btn btn-danger');
					boton.innerHTML = btn.texto;
					boton.setAttribute('onclick',btn.accion+'('+btn.id+','+"'"+btn.ruta+"')");
					td.append(boton);
					tr.append(td);
				});
				tbody.append(tr);
			}
			table.append(thead);
			table.append(tbody);
			document.querySelector(contenedor).append(table);
		}
	}).done(function(){
		if(datatable == true) {
            $('#' + tabla).DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
        }
	});

}

function creartablapositiva(formData, clases, tabla, contenedor, ruta, cabeceras, datatable, botones, identificador, funcion) {
    var dataresultado = '';
    $.ajax({
        url: ruta,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            dataresultado = data;
            $(contenedor).html('');
            var table;
            var tbody;
            var thead;
            var tr;
            var th;
            var td;

            table = document.createElement('table');
            table.setAttribute('id', tabla);
            table.setAttribute('class', clases);
            tbody = document.createElement('tbody');
            thead = document.createElement('thead');

            const cantcolumnas = cabeceras.length;
            const columnas = cabeceras;

            tr = document.createElement('tr');
            for (let index = 0; index < cantcolumnas; index++) {
                const cabecera = cabeceras[index];
                th = document.createElement('th');
                th.innerHTML = cabecera;
                tr.append(th);
            }
            if (botones) {
                th = document.createElement('th');
                th.innerHTML = 'Opciones';
                tr.append(th);
            }
            thead.append(tr);

            for (let row = 0; row < data.length; row++) {
                const fila = data[row];
                tr = document.createElement('tr');
                for (let column = 0; column < cantcolumnas; column++) {
                    const dato = data[row][columnas[column]];
                    td = document.createElement('td');
                    td.innerHTML = dato;
                    tr.append(td);
                }
                botones.forEach(btn => {
                    btn.id = identificador;
                    td = document.createElement('td');
                    let boton = document.createElement('button');
                    boton.setAttribute('class', 'btn btn-danger');
                    boton.innerHTML = btn.texto;
                    boton.setAttribute('onclick', btn.accion + '(' + btn.id + ',' + "'" + btn.ruta + "')");
                    td.append(boton);
                    tr.append(td);
                });
                tbody.append(tr);
            }
            table.append(thead);
            table.append(tbody);
            document.querySelector(contenedor).append(table);
        }
    }).done(function () {
        if (datatable == true) {
            $('#' + tabla).DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
        }
        funcion(dataresultado);
    });

}

//creartabla('#divtablacompras','tablacompras','/FiltrarExcelCompra',setarchivocompras);
function jsontabla(cabecera,contenedor,data,datatable,tabla) {
	$(contenedor).html('');
	var table = document.createElement('table');
	var tbody = document.createElement('tbody');
	var thead = document.createElement('thead');
	var tr;
	var th;
	var td;

	tr = document.createElement('tr');
	for (let index = 0; index < data.length; index++) {
		const element = cabecera[index];
		td.innerHTML = element;
		tr.append(td);
	}
	thead.append(tr);
	for (let row = 0; row < data.length; row++) {
		const fila = data[row];
		tr = document.createElement('tr');
		for (let col = 0; col < fila.length; col++) {
			const columna = fila[col];
			td = document.createElement('td');
			td.innerHTML = columna;
			tr.append(td);
		}
		tbody.append(tr);
	}
	table.setAttribute('class','table table-responsive');
	if(datatable == true)
	{
		table.setAttribute('id','reportetabla1');
		$('#' + tabla).DataTable({
			"iDisplayLength": 5,
			"aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
		});
	}
	
	table.append(table);
	contenedor.append(table);
}

function csvtabla(cabecera,contenedor,data) {
	$(contenedor).html('');
	var table = document.createElement('table');
	var tbody = document.createElement('tbody');
	var thead = document.createElement('thead');
	var tr;
	var th;
	var td;

	tr = document.createElement('tr');
	for (let index = 0; index < data.length; index++) {
		const element = cabecera[index];
		td.innerHTML = element;
		tr.append(td);
	}
	thead.append(tr);
	for (let row = 0; row < data.length; row++) {
		const fila = data[row];
		tr = document.createElement('tr');
		for (let col = 0; col < fila.length; col++) {
			const columna = fila[col];
			td = document.createElement('td');
			td.innerHTML = columna;
			tr.append(td);
		}
		tbody.append(tr);
	}
	table.append(table);
	contenedor.append(table);
}
//IMPLEMENTA
function creartablaone(formdata,spinner,clases,tabla,contenedor,ruta,cabecera,columnas,datatable,funcion,botones)
{
	mostrarcarga(spinner,true);
	var resultado;
	$.ajax({
        url: ruta,
        type: 'POST',
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            resultado = data;
            $(contenedor).html('');
            var table;
            var tbody;
            var thead;
            var tr;
            var th;
			var td;
			
			table = document.createElement('table');
            table.setAttribute('id', tabla);
            table.setAttribute('class', clases);
            tbody = document.createElement('tbody');
			thead = document.createElement('thead');
			
			tr = document.createElement('tr');
			for (let index = 0; index < cabecera.length; index++) {
				const head = cabecera[index];
				th = document.createElement('th');
				th.innerHTML = cabecera[index];
				tr.append(th);
			}
			thead.append(tr);

			for (let index = 0; index < resultado.length; index++) {
				//const reg = resultado[index];
				tr = document.createElement('tr');
				for (let col = 0; col < columnas.length; col++) {
					const dato = resultado[index][columnas[col]];
					td = document.createElement('td');
                    td.innerHTML = dato;
                    tr.append(td);
				}
				botones.forEach(btn => {
                    btn.id_columnname = resultado[index][btn.id_columnname];
                    td = document.createElement('td');
                    let boton = document.createElement('button');
                    boton.setAttribute('class', 'btn btn-danger');
                    boton.innerHTML = btn.texto;
                    boton.setAttribute('onclick', btn.accion + '(' + btn.id_columnname + ',' + "'" + btn.ruta + "')");
                    td.append(boton);
                    tr.append(td);
                });
				tbody.append(tr);
			}
			table.append(thead);
            table.append(tbody);
            document.querySelector(contenedor).append(table);
		}
	}).done(function(){
		if (datatable == true) {
            $('#' + tabla).DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
		}
		mostrarcarga(spinner,false);
        funcion(resultado);
	});
}
//IMPLEMENTA
function creartablatwo(formdata,spinner,clases,tabla,contenedor,ruta,cabecera,columnas,datatable,funcion,botones)
{
	mostrarcarga(spinner,true);
	var resultado;
	$.ajax({
        url: ruta,
        type: 'POST',
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            resultado = data;
            $(contenedor).html('');
            var table;
            var tbody;
            var thead;
            var tr;
            var th;
			var td;
			
			table = document.createElement('table');
            table.setAttribute('id', tabla);
            table.setAttribute('class', clases);
            tbody = document.createElement('tbody');
			thead = document.createElement('thead');
			
			tr = document.createElement('tr');
			for (let index = 0; index < cabecera.length; index++) {
				const head = cabecera[index];
				th = document.createElement('th');
				th.innerHTML = cabecera[index];
				tr.append(th);
			}
			thead.append(tr);

			for (let index = 0; index < resultado.length; index++) {
				const reg = JSON.parse(resultado[index].data);
				tr = document.createElement('tr');
				for (let col = 0; col < columnas.length; col++) {
					const dato = reg[columnas[col]];
					td = document.createElement('td');
                    td.innerHTML = dato;
                    tr.append(td);
				}
				botones.forEach(btn => {
                    btn.id_columnname = resultado[index][btn.id_columnname];
                    td = document.createElement('td');
                    let boton = document.createElement('button');
                    boton.setAttribute('class', 'btn btn-danger');
                    boton.innerHTML = btn.texto;
                    boton.setAttribute('onclick', btn.accion + '(' + btn.id_columnname + ',' + "'" + btn.ruta + "')");
                    td.append(boton);
                    tr.append(td);
                });
				tbody.append(tr);
			}
			table.append(thead);
            table.append(tbody);
            document.querySelector(contenedor).append(table);
		}
	}).done(function(){
		if (datatable == true) {
            $('#' + tabla).DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
		}
		mostrarcarga(spinner,false);
        funcion(resultado);
	});
}
//DEVUELE LA DATA PERO NO EL ID DEL ARCHIVO
function creartablathree(checks,formdata,spinner,clases,tabla,contenedor,ruta,cabecera,columnas,datatable,funcion,botones)
{
	mostrarcarga(spinner,true);
	var resultado;
	$.ajax({
        url: ruta,
        type: 'POST',
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            resultado = data;
            $(contenedor).html('');
            var table;
            var tbody;
            var thead;
            var tr;
            var th;
			var td;
			
			table = document.createElement('table');
            table.setAttribute('id', tabla);
            table.setAttribute('class', clases);
            tbody = document.createElement('tbody');
			thead = document.createElement('thead');
			
			tr = document.createElement('tr');
			for (let index = 0; index < cabecera.length; index++) {
				const head = cabecera[index];
				th = document.createElement('th');
				th.innerHTML = cabecera[index];
				tr.append(th);
			}
			thead.append(tr);
			var switches = 0;
			for (let index = 0; index < resultado.length; index++) {
				//const reg = resultado[index];
				tr = document.createElement('tr');
				checks.forEach(btn => {
                    let id = resultado[index][btn.id_columnname];
                    td = document.createElement('td');
                    let div = document.createElement('div');
					div.setAttribute('class', 'custom-control custom-switch');
					let input = document.createElement('input');
                    input.setAttribute('class', 'custom-control-input');
					input.setAttribute('type', 'checkbox');
					input.value = id;
					switches += 1;
					input.setAttribute('id', 'switch' + switches);
					input.addEventListener("change", function(e){
						console.log(e.target.innerHTML);
						console.log(e.target.value);
					});	
					div.append(input);
					let label = document.createElement('label');
                    label.setAttribute('class', 'custom-control-label');
                    label.setAttribute('for', 'switch' + switches);
                    div.append(label);
                    td.append(div);
                    tr.append(td);
                });
				tbody.append(tr);
				for (let col = 0; col < columnas.length; col++) {
					const dato = resultado[index][columnas[col]];
					td = document.createElement('td');
                    td.innerHTML = dato;
                    tr.append(td);
				}
				botones.forEach(btn => {
                    btn.id_columnname = resultado[index][btn.id_columnname];
                    td = document.createElement('td');
                    let boton = document.createElement('button');
                    boton.setAttribute('class', 'btn btn-danger');
                    boton.innerHTML = btn.texto;
                    boton.setAttribute('onclick', btn.accion + '(' + btn.id_columnname + ',' + "'" + btn.ruta + "')");
                    td.append(boton);
                    tr.append(td);
                });
				tbody.append(tr);
			}
			table.append(thead);
            table.append(tbody);
            document.querySelector(contenedor).append(table);
		}
	}).done(function(){
		if (datatable == true) {
            $('#' + tabla).DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
		}
		mostrarcarga(spinner,false);
        funcion(resultado);
	});
}
//SEMI PERFECTA
function creartablafour(dataname,checks,formdata,spinner,clases,tabla,contenedor,ruta,cabecera,columnas,datatable,funcion,botones)
{
	mostrarcarga(spinner,true);
	var resultado;
	var response;
	$.ajax({
        url: ruta,
        type: 'POST',
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
			response = data;
            resultado = data[dataname];
            $(contenedor).html('');
            var table;
            var tbody;
            var thead;
            var tr;
            var th;
			var td;
			
			table = document.createElement('table');
            table.setAttribute('id', tabla);
            table.setAttribute('class', clases);
            tbody = document.createElement('tbody');
			thead = document.createElement('thead');
			
			tr = document.createElement('tr');
			for (let index = 0; index < cabecera.length; index++) {
				const head = cabecera[index];
				th = document.createElement('th');
				th.innerHTML = cabecera[index];
				tr.append(th);
			}
			thead.append(tr);
			var switches = 0;
			for (let index = 0; index < resultado.length; index++) {
				//const reg = resultado[index];
				tr = document.createElement('tr');
				checks.forEach(check => {
                    let id = resultado[index][check.id_columnname];
					td = document.createElement('td');
					let label = document.createElement('label');
					label.innerHTML = check.label;
					td.append(label);
                    let div = document.createElement('div');
					div.setAttribute('class', 'custom-control custom-switch');
					let input = document.createElement('input');
                    input.setAttribute('class', 'custom-control-input');
					input.setAttribute('type', 'checkbox');
					input.value = id;
					switches += 1;
					input.setAttribute('id', 'switch' + switches);
					input.addEventListener("change", function(e){
						check.funcion(check.ruta,id,check.confirm);
					});
					if(resultado[index][check.checkflag] == check.positive)
					{
						input.checked = true;
					}
					div.append(input);
					label = document.createElement('label');
                    label.setAttribute('class', 'custom-control-label');
                    label.setAttribute('for', 'switch' + switches);
                    div.append(label);
                    td.append(div);
                    tr.append(td);
                });
				for (let col = 0; col < columnas.length; col++) {
					const dato = resultado[index][columnas[col]];
					td = document.createElement('td');
                    td.innerHTML = dato;
                    tr.append(td);
				}
				botones.forEach(btn => {
                    btn.id_columnname = resultado[index][btn.id_columnname];
                    td = document.createElement('td');
                    let boton = document.createElement('button');
                    boton.setAttribute('class', 'btn btn-danger');
                    boton.innerHTML = btn.texto;
                    boton.setAttribute('onclick', btn.accion + '(' + btn.id_columnname + ',' + "'" + btn.ruta + "')");
                    td.append(boton);
                    tr.append(td);
                });
				tbody.append(tr);
			}
			table.append(thead);
            table.append(tbody);
            document.querySelector(contenedor).append(table);
		}
	}).done(function(){
		if (datatable == true) {
            $('#' + tabla).DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
		}
		mostrarcarga(spinner,false);
        funcion(response);
	});
}
//SEMI PERFECTA
function creartablafive(dataname,formdata,spinner,clases,tabla,contenedor,ruta,cabecera,columnas,datatable,funcion,botones)
{
	mostrarcarga(spinner,true);
	var resultado;
	var response;
	$.ajax({
        url: ruta,
        type: 'POST',
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
			response = data;
            resultado = data[dataname];
            $(contenedor).html('');
            var table;
            var tbody;
            var thead;
            var tr;
            var th;
			var td;
			
			table = document.createElement('table');
            table.setAttribute('id', tabla);
            table.setAttribute('class', clases);
            tbody = document.createElement('tbody');
			thead = document.createElement('thead');
			
			tr = document.createElement('tr');
			for (let index = 0; index < cabecera.length; index++) {
				const head = cabecera[index];
				th = document.createElement('th');
				th.innerHTML = cabecera[index];
				tr.append(th);
			}
			thead.append(tr);
			var switches = 0;
			for (let index = 0; index < resultado.length; index++) {
				//const reg = resultado[index];
				tr = document.createElement('tr');
				for (let col = 0; col < columnas.length; col++) {
					const dato = resultado[index][columnas[col]];
					td = document.createElement('td');
                    td.innerHTML = dato;
                    tr.append(td);
				}
				botones.forEach(btn => {
                    btn.id_columnname = resultado[index][btn.id_columnname];
                    td = document.createElement('td');
                    let boton = document.createElement('button');
                    boton.setAttribute('class', 'btn btn-danger');
                    boton.innerHTML = btn.texto;
                    boton.setAttribute('onclick', btn.accion + '(' + btn.id_columnname + ',' + "'" + btn.ruta + "')");
                    td.append(boton);
                    tr.append(td);
                });
				tbody.append(tr);
			}
			table.append(thead);
            table.append(tbody);
            document.querySelector(contenedor).append(table);
		}
	}).done(function(){
		if (datatable == true) {
            $('#' + tabla).DataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
		}
		mostrarcarga(spinner,false);
        funcion(response);
	});
}
/* PROTOTIPOS */

function crearselect(selectid,data,pseudenimo) {
	let select = document.querySelector(selectid);
	select.innerHTML = '';
	let option = document.createElement('option');
	option.innerHTML = '-- seleccion --';
	select.append(option);
	archivos = data[pseudenimo];
	archivos.forEach(file => {
		option = document.createElement('option');
		option.innerHTML = file.id + '-' + file.ruta;
		option.value = file.id;
		select.append(option);
	});
}

function calculototal(data) {
    console.log('data caja chica');
    console.log(data);
    let table = document.querySelector('#tablacajachica');
    let tbody = table.querySelector('tbody');
    let filas = tbody.querySelectorAll('tr');
    for(let i=0; i<filas.length; i++){
        let col = filas[i].querySelectorAll('td');
        
        const monto = parseFloat(col[11].textContent);
        console.log(monto);
        cajachica.total += monto;
    }
    let total = document.querySelector('#txttotalcajachica');
    total.value = cajachica.total;
    let montoentregado = document.querySelector('#txtmontoentregadocajachica');
    let neto = document.querySelector('#txtnetocajachica');
    neto.value = montoentregado.value - total.value;
}

