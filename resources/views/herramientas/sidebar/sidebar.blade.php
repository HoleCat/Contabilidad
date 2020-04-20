<ul id="side-bar" class="side-bar-show list-group position-fixed">
    <li id="" class="list-group-item active">Dashboard</li>
    <li id="nav-muestreo" class="list-group-item">Muestreo</li>
    <li id="nav-caja" class="list-group-item">Caja</li>
    <li id="nav-activos" class="list-group-item">Activos</li>
    <li id="nav-balance" class="list-group-item">Balance</li>
</ul>

<button onclick="sidebar('#side-bar')" class="btn position-fixed btn-tools"><i class="fas fa-bars"></i></button>

<div class="nav flex-column nav-pills options-bar-show position-fixed" id="options-bar" role="tablist" aria-orientation="vertical">
    <a id="opcion-muestras" class="mb-1 nav-link bg-primary" data-toggle="pill" role="tab" aria-controls="v-pills-home" aria-selected="false"><i class="fas fa-flask"></i></a>
    <a id="opcion-caja" class="mb-1 nav-link bg-danger" data-toggle="pill" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fas fa-hand-holding-usd"></i></a>
    <a id="opcion-activos" class="mb-1 nav-link bg-warning" data-toggle="pill" role="tab" aria-controls="v-pills-messages" aria-selected="false"><i class="fas fa-boxes"></i></a>
    <a id="opcion-balance" class="mb-1 nav-link bg-success" data-toggle="pill" role="tab" aria-controls="v-pills-settings" aria-selected="false"><i class="fas fa-balance-scale"></i></a>
</div>

<button onclick="optionsbar('#options-bar')" class="btn btn-primary position-fixed btn-options"><i class="fas fa-tools"></i></button>