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
function creartablachevere(clases,tabla,contenedor,ruta,cabecera,columnas,datatable)
{
	var resultado;
	$.ajax({
        url: ruta,
        type: 'POST',
        data: formData,
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

            const cantcolumnas = cabeceras.length;
			const columnas = cabeceras;
		}
	}).done(function(){
		
	});
}

/* PROTOTIPOS */

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
