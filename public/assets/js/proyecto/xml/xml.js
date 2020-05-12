eventosxml();

var jTable = (() => {
    $('#tablaxml').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 
            {
                extend: 'csvHtml5',
                action: function ( e, dt, node, config ) {
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, node, config)
                },
                title: 'Registros de Archivos XML',
                titleAttr: 'csv',
                exportOptions: {
                    columns: ':visible'
                },
                
                footer: true
            }, {
                extend: 'excelHtml5',
                action: function ( e, dt, node, config ) {
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config)
                },
                title: 'Registros de Archivos XML',
                titleAttr: 'Excel',
                exportOptions: {
                    columns: ':hidden'
                },
                footer: true
            }
        ],
        language: {
            "decimal": ",",
            "thousands": ".",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Documentos",
            "infoEmpty": "Mostrando 0 to 0 of 0 Documentos",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Documentos",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
});

var createTable = ((jData) =>{
    if ($.fn.dataTable.isDataTable('#tablaxml')) {
        console.log("entró");
        $('#tablaxml').DataTable().destroy();
    }

    let tr, td, table, div, th, thead, tbody;

    div = document.querySelector(".grid-result");
    const trs = div.querySelectorAll("tbody>tr");
    table = document.createElement("table");
    table.setAttribute('id','tablaxml');
    table.setAttribute('class','w-100 table table-responsive');
    div.append(table);
    if (trs.length == 0) {
        thead = document.createElement("thead");
        table.appendChild(thead);
        tr = document.createElement("tr");
        thead.appendChild(tr);

        th =document.createElement("th");
        th.textContent =""; //auto increment
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="CÓDIGO";
        tr.appendChild(th);
        
        th =document.createElement("th");
        th.textContent ="SERIE";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="NÚMERO";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="RUC";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="DENOMINACIÓN";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="RAZÓN SOCIAL";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="RUC CLIENTE";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="DESCRIPCIÓN";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="IGV";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="SUB TOTAL";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="TOTAL";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="CONDICIÓN";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="EMISOR CPE";
        tr.appendChild(th);

        th =document.createElement("th");
        th.textContent ="OPCIONES";
        tr.appendChild(th);
        tbody = document.createElement('tbody');
        table.appendChild(tbody);
    }
    
    let suma=0;

    
    const tb = div.querySelector("tbody");
    jData.map((item, index) => {
        tr = document.createElement("tr");

        td = document.createElement("td");
        td.textContent = index + 1;
        tr.appendChild(td);
        
        td = document.createElement("td");
        td.textContent = item.codigo_doc;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.serie;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.numero;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.ruc_proveedor;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.razon_social_proveedor;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.razon_social_cliente;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.ruc_cliente;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.descripcion;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.igv;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.valor_venta;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = item.total;
        tr.appendChild(td);
        suma += item.total;

        td = document.createElement("td");
        td.textContent = item.condicion;
        tr.appendChild(td);

        td = document.createElement("td");
        td.textContent = "";
        tr.appendChild(td);

        td = document.createElement("td");
        let btn = document.createElement('buttom');
        btn.addEventListener("click", function(){
            eliminarregistro('/Factura/Eliminar',item.id,item.uso_id,transferComplete);
        });
        btn.setAttribute('class',"btn btn-danger");
        btn.innerHTML = '<i class="fas fa-trash"></i>';
        td.append(btn);
        tr.appendChild(td);
        tb.appendChild(tr);

        
    });
    jTable();
});

function eventosxml() {
        let file = document.querySelector("#xml");
        let form = document.querySelector("#formcargaxml");
        let btn = document.querySelector(".btn-send");
        let key = document.querySelector("meta[name='csrf-token']").content;
        let xhr = new XMLHttpRequest();

        let formdata = new FormData();
        let uso_id = document.querySelector('#uso_id').value;
        formdata.set('uso_id',uso_id);
        xhr.open('post', '/Xml/Show');
        xhr.setRequestHeader("x-csrf-token", key);
        xhr.addEventListener("load", transferComplete);
        xhr.send(formdata);

        form.addEventListener('submit', function(e){
            e.preventDefault();
            let formdata = new FormData(form);

            xhr.open('post', '/upload');
            xhr.setRequestHeader("x-csrf-token", key);
            xhr.addEventListener("load", transferComplete);
            xhr.send(formdata);
        });

        function transferComplete(data) {
            const res = data.currentTarget.response;
            console.log(res);
            const jData = JSON.parse(res);

            let arr_ruc = [];
            jData.map((value) => {
                value["condicion"] = "";
                if (arr_ruc.indexOf(value.ruc_proveedor) === -1) {
                    arr_ruc.push(value.ruc_proveedor);
                }
            });
            
            //webservice
            if (arr_ruc.length > 0) {
                const tbl = document.querySelector(".grid-result");
                
                for (let i = 0; i < arr_ruc.length; i++) {
                    const element = arr_ruc[i];
                    let url = `http://sunat.innovafashionperu.com/rucServices.php?nruc=${element}`;
                    let req = new XMLHttpRequest();
                    //req.responseType="json";
                    req.open("GET", url);
                    req.onload = function(){
                        if (req.status === 200 && req.readyState === 4) {
                            let jdataTable = $('#tablaxml').DataTable();
                            const jService = JSON.parse(req.responseText);
                           if (jService.success === true) {
                               const trs = tbl.querySelectorAll("tbody>tr");
                               
                               for (let i = 0; i < trs.length; i++) {
                                   const tr = trs[i];
                                   if (tr.children[4].textContent === jService.result.ruc) {
                                    jdataTable.cell(i, 12).data(jService.result.condicion).draw();
                                    //tr.children[12].textContent = jService.result.condicion;
                                        if (jService.result.comprobante_electronico.length > 0) {
                                            const cpes = jService.result.comprobante_electronico;
                                            for (let j = 0; j < cpes.length; j++) {
                                                const doc = cpes[j];
                                                const cp = doc.substr(0, doc.indexOf(" "));
                                                if (cp.substr(0, 1) === tr.children[2].textContent.substr(0, 1)) {
                                                    jdataTable.cell(i, 13).data(doc).draw();
                                                    //tr.children[13].textContent = doc;
                                                break;
                                                }
                                            }
                                        }
                                   }
                               }
                           } else {
                               console.log(JSON.parse(req.responseText));
                           }
                           //restore table
                           //jTable();
                        }
                    }
                    req.send();
                }
            }

            createTable(jData);
        }
        
}