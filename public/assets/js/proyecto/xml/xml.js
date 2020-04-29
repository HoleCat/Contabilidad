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
            td.textContent ="OPCIONES";
            tr.appendChild(td);

            let suma=0;

            tbody = document.createElement('tbody');
            jData.map((item) =>{
                tr = document.createElement("tr");

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
            td.textContent ="OPCIONES";
            tr.appendChild(td);

            let suma=0;

            tbody = document.createElement('tbody');
            res.map((item) =>{
                tr = document.createElement("tr");

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

        
