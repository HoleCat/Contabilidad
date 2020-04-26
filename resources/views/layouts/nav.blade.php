<nav class="nav-index navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" id="nav-muestreo">Muestreo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-caja">Caja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-activos">Activos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-balance">Balance</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        
                            <a class="dropdown-item" id="seguimiento-btn" onclick="getview('#content','/Seguimiento',confirmacion)">
                                <i class="fas fa-microscope"></i> SEGUIMIENTO
                            </a>
                            <a class="dropdown-item" id="datauser-btn" onclick="getview('#content','/Userdata',confirmacion)">
                                <i class="fas fa-address-card"></i> USUARIO
                            </a>
                            <a class="dropdown-item" id="admin-btn" onclick="getview('#content','/Admin',confirmacion)">
                                <i class="fas fa-user-tie"></i> ADMIN
                            </a>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
