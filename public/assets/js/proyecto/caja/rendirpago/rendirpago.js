eventosrendirpago();

function eventosrendirpago(){
    
}

var rendirpago = {
    total : 0,
}

var datarendirpago;

var cabecerarendirpago = [
    'liquidacion_id'
    ,'ruc'
    ,'tipodocumento'
    ,'documento'
    ,'fecha'
    ,'moneda'
    ,'concepto'
    ,'contabilidad'
    ,'centrocosto'
    ,'base'
    ,'igv'
    ,'monto'
];

var identificador = 0;

var botonesrendirpago = [
	{
		texto: '<i class="fas fa-trash-alt"></i>',
		accion: 'borrardetalleliquidacion',
		ruta: '/Destroy/Tuvieja',
		id: 0
	}
]

var parametrosrendirpago = [
    {
        header: 'liquidacion_id',
        value: document.querySelector('#liquidaciondetalle_id').value
    }
];

function confirmaciontablarendirpago() {
    console.log('la tabla cargo');
}

function ejecutarvalidacionrendirpago() {
    var eso = [];
    var resultado = true;
    var sunat = false;
    var test = function () {
        return new Promise(function (resolve, reject) {
            let ruc = document.querySelector('#rucrendirpago').value;
            var formData = new FormData();
            formData.append("nruc",ruc);
         
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "http://sunat.innovafashionperu.com/rucServices.php");
            xhr.send(formData);
            xhr.onreadystatechange=function () {
                if (xhr.readyState !== 4) return;
                if (xhr.status >= 200 && xhr.readyState == 4) {
                    console.log(xhr.responseText);
                    resolve(xhr);
                }else{
                    reject({
                        status: xhr.status,
                        statusText: xhr.statusText
                    });
                }
            }
        });
    };
    test().then(function(data) {
        if(data.success == true){
            sunat = true;
            
        } else {
            sunat = false;
        }
        console.log('termino');
        eso.push(sunat);
        eso.push(validacionunitariabasica('#rucrendirpago','#rp-validador-ruc',8,12));
        eso.push(validacionunitariabasica('#codigodocumentorendirpago','#rp-validador-nrodocumento',3,5));
        eso.push(validacionunitariabasica('#numerodocumentorendirpago','#rp-validador-nrodocumento',3,5));
        console.log(eso);
        for (let index = 0; index < eso.length; index++) {
            const element = eso[index];
            if(element != true){
                resultado = false;
            }
        }
    });
    return resultado;
}

function optenertotales() {
    jsonview('#rendirpagototales','/Caja/Totales',confirmaciontablarendirpago,document.querySelector('#liquidaciondetalle_id').value);
}

$(function(){
    creartablavalidada(ejecutarvalidacionrendirpago,'table table-responsive','tablarendirpago','#formrendirpago','#divtablarendirpago','/Caja/Rendirpago/Adicion',cabecerarendirpago,true,optenertotales,botonesrendirpago,identificador);
    creartablaahora(parametrosrendirpago,'table table-responsive','tablarendirpago','#divtablarendirpago','/Caja/Rendirpago/Info',cabecerarendirpago,true,optenertotales,botonesrendirpago,identificador);
})