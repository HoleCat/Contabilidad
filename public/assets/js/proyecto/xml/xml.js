eventosxml();
function eventosxml() {
        let file = document.querySelector("#xml");
        let form = document.querySelector("#formcargaxml");
        let btn = document.querySelector(".btn-send");
        let key = document.querySelector("meta[name='csrf-token']").content;
        let xhr = new XMLHttpRequest();

        form.addEventListener('submit', function(e){
        e.preventDefault();
        let formdata = new FormData(form);

        xhr.open('post', '/upload');
        xhr.setRequestHeader("x-csrf-token", key);
        xhr.addEventListener("load", transferCompletem);
        xhr.send(formdata);
        });

        function transferCompletem(data){
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
                            const jService = JSON.parse(req.responseText);
                           if (jService.success === true) {
                               const trs = tbl.querySelectorAll("tbody>tr");
                               for (let i = 0; i < trs.length; i++) {
                                   const tr = trs[i];
                                   if (tr.children[4].textContent === jService.result.ruc) {
                                    tr.children[12].textContent = jService.result.condicion;
                                    if (jService.result.comprobante_electronico.length > 0) {
                                        const cpes = jService.result.comprobante_electronico;
                                        for (let j = 0; j < cpes.length; j++) {
                                            const doc = cpes[j];
                                            const cp = doc.substr(0, doc.indexOf(" "));
                                            if (cp.substr(0, 1) === tr.children[2].textContent.substr(0, 1)) {
                                             tr.children[13].textContent = doc;
                                             break;
                                            }
                                        }
                                    }
                                   }
                               }
                           } else {
                               console.log(JSON.parse(req.responseText));
                           }
                        }
                    }
                    req.send();
                }
            }

            let tr, td, table, div, footer, thead, tbody;
            
            div = document.querySelector(".grid-result");
            table = document.createElement("table");
            table.setAttribute('id','tablaxml');
            table.setAttribute('class','w-100 table table-responsive');
            thead = document.createElement("thead");
            table.appendChild(thead);
            tr = document.createElement("tr");
            thead.appendChild(tr);

            td =document.createElement("td");
            td.textContent =""; //auto increment
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="CÓDIGO";
            tr.appendChild(td);
            
            td =document.createElement("td");
            td.textContent ="SERIE";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="NÚMERO";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="RUC";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="DENOMINACIÓN";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="RAZÓN SOCIAL";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="RUC CLIENTE";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="DESCRIPCIÓN";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="IGV";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="SUB TOTAL";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="TOTAL";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="CONDICIÓN";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="EMISOR CPE";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="OPCIONES";
            tr.appendChild(td);
            let suma=0;

            tbody = document.createElement('tbody');
            
            jData.map((item, index) =>{
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
                    eliminarregistro('/Factura/Eliminar',item.id,transferComplete);
                });
                btn.setAttribute('class',"btn btn-danger");
                btn.innerHTML = '<i class="fas fa-trash"></i>';
                td.append(btn);
                tr.appendChild(td);
                tbody.appendChild(tr);

            });
            footer = document.createElement("footer");
            tr = document.createElement("tr")
            td= document.createElement("td");
            tr.appendChild(td);
            td.textContent = "Total: " + suma;
            footer.appendChild(tr);
            table.appendChild(tbody);
            table.appendChild(footer);
            div.innerHTML = '';
            div.append(table);
            $('#tablaxml').DataTable();
        }
        function transferComplete(data){
            const res = data;

            const jData = res;
            
            //get ruc
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
                    req.open("GET", url);
                    req.onload = function(){
                        if (req.status === 200 && req.readyState === 4) {
                            const jService = JSON.parse(req.responseText);
                           if (jService.success === true) {
                               const trs = tbl.querySelectorAll("tbody>tr");
                               for (let i = 0; i < trs.length; i++) {
                                   const tr = trs[i];
                                   if (tr.children[4].textContent === jService.result.ruc) {
                                    tr.children[12].textContent = jService.result.condicion;
                                    if (jService.result.comprobante_electronico.length > 0) {
                                        const cpes = jService.result.comprobante_electronico;
                                        for (let j = 0; j < cpes.length; j++) {
                                            const doc = cpes[j];
                                            const cp = doc.substr(0, doc.indexOf(" "));
                                            if (cp.substr(0, 1) === tr.children[2].textContent.substr(0, 1)) {
                                             tr.children[13].textContent = doc;
                                             break;
                                            }
                                        }
                                    }
                                   }
                               }
                           } else {
                               console.log(JSON.parse(req.responseText));
                           }
                        }
                    }
                    req.send();
                }
            }

            let tr, td, table, div, footer, thead, tbody;
            
            div = document.querySelector(".grid-result");
            table = document.createElement("table");
            table.setAttribute('id','tablaxml');
            table.setAttribute('class','w-100 table table-responsive');
            thead = document.createElement("thead");
            table.appendChild(thead);
            tr = document.createElement("tr");
            thead.appendChild(tr);

            td =document.createElement("td");
            td.textContent =""; //auto increment
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="CÓDIGO";
            tr.appendChild(td);
            
            td =document.createElement("td");
            td.textContent ="SERIE";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="NÚMERO";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="RUC";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="DENOMINACIÓN";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="RAZÓN SOCIAL";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="RUC CLIENTE";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="DESCRIPCIÓN";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="IGV";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="SUB TOTAL";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="TOTAL";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="CONDICIÓN";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="EMISOR CPE";
            tr.appendChild(td);

            td =document.createElement("td");
            td.textContent ="OPCIONES";
            tr.appendChild(td);

            let suma=0;

            tbody = document.createElement('tbody');
            res.map((item, index) =>{
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

                td = document.createElement("td");
                td.textContent = item.condicion;
                tr.appendChild(td);

                td = document.createElement("td");
                td.textContent = "";
                tr.appendChild(td);

                suma += item.total;

                td = document.createElement("td");
                let btn = document.createElement('buttom');
                btn.addEventListener("click", function(){
                    eliminarregistro('/Factura/Eliminar',item.id,transferComplete);
                });
                btn.setAttribute('class',"btn btn-danger");
                btn.innerHTML = '<i class="fas fa-trash"></i>';
                td.append(btn);
                tr.appendChild(td);
                tbody.appendChild(tr);

            });
            footer = document.createElement("footer");
            tr = document.createElement("tr")
            td= document.createElement("td");
            tr.appendChild(td);
            td.textContent = "Total: " + suma;
            footer.appendChild(tr);
            table.appendChild(tbody);
            table.appendChild(footer);
            div.innerHTML = '';
            div.append(table);
            $('#tablaxml').DataTable();
        }
}

        
