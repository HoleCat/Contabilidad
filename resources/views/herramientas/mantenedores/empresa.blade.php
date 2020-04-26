<form id="formempresa" enctype="multipart/form-data">
    <div class="col-12 d-flex d-wrap">
        <div class="col-xl-3 col-md-4 col-sm-6 col-xs-12">
            <div class="form-group">
                <label>NOMBRE</label>
                <input type="text" name="nombre" id="" class="form-control">
            </div>
            <div class="form-group">
                <label>RAZON SOCIAL</label>
                <input type="text" name="razonsocial" id="" class="form-control">
            </div>
            <div class="form-group">
                <label>RUC</label>
                <input type="number" name="ruc" id="" class="form-control">
            </div>
            <hr>
        </div>
        <div class="col-xl-3 col-md-4 col-sm-6 col-xs-12">
            <div class="form-group">
                <label>CODIGO</label>
                <input type="text" name="codigo" id="" class="form-control">
            </div>
            <div class="form-group">
                <label>TELEFONO</label>
                <input type="text" name="telefono" id="" class="form-control">
            </div>
            <div class="form-group">
                <label>DIRECCCION</label>
                <input type="text" name="direccion" id="" class="form-control">
            </div>
            <hr>
        </div>
        <div class="col-xl-3 col-md-4 col-sm-6 col-xs-12">
            <div class="form-group">
                <label>PAGINA</label>
                <input type="text" name="pagina" id="" class="form-control">
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="empresafoto" lang="es" name="foto">
                <label id="empresafotolabel" class="custom-file-label" for="empresafoto">Seleccionar Archivo</label>
            </div>
            <hr>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="GUARDAR">
            </div>
        </div>
        <div class="col-xl-3 col-md-4 col-sm-6 col-xs-12">
            
        </div>
    </div>
</form>
<script>
    $('#empresafoto').change(function(e){
        let filename = this.files[0].name;
        let filelabel = document.querySelector('#empresafotolabel');
        filelabel.innerHTML = filename;
        console.log(filename);
    });
    setview('#formempresa','#admin-empresa','/Admin/Empresa');
</script>