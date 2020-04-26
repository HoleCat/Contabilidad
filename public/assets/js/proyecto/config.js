$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function validacionbasica(id,flag,msg,limit) {
    let target = document.querySelector(id);
    
    for (let i = 0; i < inputs.length; i++) {
        const type = inputs[i].getAttribute('type');
        if(type != 'hidden')
        {
            const input = inputs[i];
            const msg = input.nextElementSibling;
            if(input.value == '')
            {
                
                if(msg.classList.contains('fade'))
                {
                    msg.classList.remove('fade');
                }
                if(!input.classList.contains('border-danger'))
                {
                    input.classList.add('border-danger');
                }
            } else {
                
                if(!msg.classList.contains('fade'))
                {
                    input.classList.add('fade');
                }
                if(input.classList.contains('border-danger'))
                {
                    msg.classList.remove('border-danger');
                }
            }
        }
    }

}

function validacionunitariabasica(field,small,min,max) {
    var input = document.querySelector(field);
    var msg = document.querySelector(small);
    var val = input.value;
    var vals = val.toString();

    var count = vals.length;

    var validador = {
        contenido : true,
        cantidad : true
    }
    var text = '';
    if(val == '')
    {   
        validador.contenido = false;
        text = '* campo obligatorio '
    }
    if(count < min)
    {
        validador.cantidad = false;
        text += '* el minimo de caracteres es '+ min;
    }
    if(count > max)
    {
        validador.cantidad = false;
        text += '* el maximo de caracteres es '+ max;
    }

    msg.innerHTML = text;
    var resultado = false;
    
    if(validador.contenido == true && validador.cantidad == true)
    {
        if(!msg.classList.contains('fade'))
        {
            msg.classList.add('fade');
            msg.classList.remove('text-danger');
        }
        if(input.classList.contains('border-danger'))
        {
            input.classList.remove('border-danger');
            msg.classList.remove('text-danger');
        }
        resultado = true;
    }
    else 
    {
        if(msg.classList.contains('fade'))
        {
            msg.classList.remove('fade');
            msg.classList.add('text-danger');
        }
        if(!input.classList.contains('border-danger'))
        {
            input.classList.add('border-danger');
        }
        resultado = false;
    }

    return resultado;
}

function validarsunat(input,msg,valor) {
    let ruc = document.querySelector(input).value;
    let dato = document.querySelector(input);
    let respuesta = document.querySelector(msg);
    let formData = new FormData();
    var resultado = false;
    formData.set('nruc', ruc);
    $.ajax({
		url: 'http://sunat.innovafashionperu.com/rucServices.php',
		type: 'POST',
		data: formData,
		processData: false,
        contentType: false,
        success: function(data){
            console.log(data);
            data = JSON.parse(data);
            if(data.success == true){
                resultado = true;
                respuesta.innerHTML = 'condicion :' + data.result.condicion;
                if(respuesta.classList.contains('text-danger')){
                    respuesta.classList.remove('text-danger');
                    dato.classList.remove('border-danger');
                    respuesta.classList.add('text-primary');
                    dato.classList.add('border-primary');
                } else {
                    respuesta.classList.add('text-primary');
                    dato.classList.add('border-primary');
                    respuesta.classList.remove('text-danger');
                    dato.classList.remove('border-danger');
                }
                if(respuesta.classList.contains('fade'))
                {
                    respuesta.classList.remove('fade');
                }
            } else {
                respuesta.innerHTML = data.message;
                if(!respuesta.classList.contains('text-danger')){
                    respuesta.classList.add('text-danger');
                    dato.classList.add('border-danger');
                    respuesta.classList.remove('text-primary');
                    dato.classList.remove('border-primary');
                } else {
                    respuesta.classList.add('text-danger');
                    dato.classList.add('border-danger');
                    respuesta.classList.remove('text-primary');
                    dato.classList.remove('border-primary');
                }
                if(respuesta.classList.contains('fade'))
                {
                    respuesta.classList.remove('fade');
                }
            }        
        }
    }).done(function(){
        if(valor){
            valor = resultado;
        }
		return resultado;
    });
}

function validartipodecambio(input,contenedor) {
    var fecha = document.querySelector(input).value;
    var campos = fecha.split('.');
    var mes = '';
    var anio = '';
    
    if(campos.length > 2){
        mes = campos[1];
        anio = campos[0];
        if(anio.length < 3)
        {
            anio = campos[3];
        }
    } else {
        campos = fecha.split('/');

        if(campos.length > 2){
            mes = campos[1];
            anio = campos[0];
            if(anio.length < 3)
            {
                anio = campos[3];
            }
        } else {
            campos = fecha.split('-');
            mes = campos[1];
            anio = campos[0];
            if(anio.length < 3)
            {
                anio = campos[3];
            }
        }
    }

    let formData = new FormData();
    formData.set('mes', mes);
    formData.set('anio', anio);

    $.ajax({
		url: 'http://sunat.innovafashionperu.com/tcServices.php',
		type: 'POST',
		data: formData,
		processData: false,
        contentType: false,
        success: function(data){
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data.success === true)
            {
                var registros = data.result;
                for (let index = 0; index < registros.length; index++) {
                    const element = registros[index];
                    if(index == registros.length - 1)
                    {
                        let con = document.querySelector(contenedor);
                        con.innerHTML = 'COMPRA : '+registros[index].compra + 'VENTA : '+registros[index].venta;
                    }
                }
            }
        }
    }).done(function(){
		
	});
}

function validarcomprobantes(input,valor) {
    let ruc = document.querySelector(input).value;
    //let dato = document.querySelector(input);
    //let respuesta = document.querySelector(msg);
    let formData = new FormData();
    var resultado = false;
    formData.set('nruc', ruc);
    $.ajax({
		url: 'http://sunat.innovafashionperu.com/cpeServices.php',
		type: 'POST',
		data: formData,
		processData: false,
        contentType: false,
        success: function(data){
            console.log(data);
            data = JSON.parse(data);
            if(data.success == true){
                resultado = true;
                let respuesta = 'condicion :' + 'encontrado';
                $('#modal-comprobantes').modal('show');
                $('#modal-comprobantes-titulo').html(respuesta);
                var html = '<ul class="list-group">';
                var tipos = data.result;
                
                if(tipos.length>0){
                    tipos.forEach(tipo => {
                        const fecha = tipo.fecha;
                        html += '<li class="list-group-item">';
                        html += fecha;
                        html += '</li>';
                        const comprobantes = tipo.comprobantes;
                        for (let index = 0; index < comprobantes.length; index++) {
                            const element = comprobantes[index];
                            html += '<li class="list-group-item">';
                            html += element;
                            html += '</li>';
                        }
                    });
                } else {
                    html += '<li class="list-group-item">';
                    html += 'SIN REGISTOS QUE MOSTRAR';
                    html += '</li>';
                }
                    
                
                html += '</ul>';
                $('#modal-comprobantes .modal-body').html(html);
            } else {
                resultado = false;
                let respuesta = 'condicion :' + 'sin data';
                $('#modal-comprobantes').modal('show');
                $('#modal-comprobantes-titulo').html(respuesta);
            }        
        }
    }).done(function(){
        if(valor){
            valor = resultado;
        }
		return resultado;
    });
}

function confirmacionpopup(titulo,contenido,botones) {
    $.confirm({
        title: titulo,
        content: contenido,
        buttons: botones
    });
}

function confirmacionok() {
    $.confirm({
        title: 'AVISO :',
        content: 'revise los campos enviados, que las validaciones con sunat den positivo, los  campos con * son obligatorios.',
        buttons: {
            ok: {
                text: 'ok',
                btnClass: 'btn-primary',
                keys: ['enter', 'shift'],
                action: function(){
                    $.alert('ok');
                }
            }
        }
    });
}

function suplicar(veces) {
    for (let index = 0; index < veces; index++) {
        console.log('honto ni gomenasai sadko-chan');
        console.log('watashi baka desu');
    }
}

function dere(nombre,estilo,golpes){
	if(estilo=="loli")
	{ console.log(nombre + ' onichan no baka');}
	else if(estilo=="punk")
    { console.log(nombre + ' aniki no bakaaa');
        for (let index = 0; index < golpes; index++) {
            if(index == 0){
                console.log('ata');            
            } else {
                console.log('ta');            
            }
        }
    }
}

function mostrarelementos(id){
    var tag = document.querySelector(id);
    var elementos = tag.querySelectorAll('.d-none');
    for (let index = 0; index < elementos.length; index++) {
        const element = elementos[index];
        element.classList.remove('d-none');
    }
}

function mostrarcarga(id,siono) {
	var carga = document.querySelector(id);
	if(siono == true)
	{
        let divspinner = document.createElement('div');
        divspinner.setAttribute('class','spinner-border text-warning');
        divspinner.setAttribute('role','status');
        let spinner = document.createElement('span');
        spinner.setAttribute('class','sr-only');
        divspinner.append(spinner)
        carga.innerHTML = '';
        carga.append(divspinner);
    }
    else
    {
        let check = document.createElement('span');
        check.setAttribute('class','fas fa-check-circle text-success fa-2x py-1');
		carga.innerHTML = '';
        carga.append(check);
	}
}

function asignarvalor(id,valor) {
    var tag = document.querySelector(id);
    tag.value = valor;
}

function cargararchivo(form,spinner,ruta,funcion) {
	var formulario = document.querySelector(form);
    var formData = new FormData(formulario);
    var resultado;
	mostrarcarga(spinner,true);
	$.ajax({
		url: ruta,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function(data){
			resultado = data;
		}
	}).done(function(){
        funcion(resultado);
		mostrarcarga(spinner,false);
    });
    return resultado;
}

function ejecutarruta(ruta) {
    event.preventDefault();
	$.ajax({
		url: ruta,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function(data){
			
		}
	}).done(function(){
	});
}
