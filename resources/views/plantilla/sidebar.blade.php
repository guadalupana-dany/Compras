<div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <li @click="menu=0" class="nav-item">
                    <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
                </li>
                <li class="nav-title">
                    Mantenimiento
                </li>
                @if(Auth::user()->hasRole('Administrador'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Compras</a>
                    <ul class="nav-dropdown-items">
                        <li @click="menu=1" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-wallet"></i> Ingresos</a>
                        </li>
                        <li @click="menu=2" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Proveedores</a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasRole('Administrador') or Auth::user()->hasRole('Solicitante'))
                <li @click="menu=3" class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-newspaper-o"></i>Solicitante</a>
                </li>
                @endif
                @if(Auth::user()->hasRole('Administrador') or Auth::user()->hasRole('verificador'))
                <li @click="menu=6" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-eye"></i>Solicitud Pendientes</a>
                </li>
                <li @click="menu=9" class="nav-item">
                <a class="nav-link" href="#"><i class="fa  fa-file-text-o"></i>Todas las Solicitudes</a>
                </li>
                @endif
                @if(Auth::user()->hasRole('Administrador'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Acceso</a>
                    <ul class="nav-dropdown-items">
                        <li @click="menu=7" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-user"></i> Usuarios</a>
                        </li>
                        <li @click="menu=8" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-user-following"></i> Roles</a>
                        </li>
                    </ul>
                </li>
                <li @click="menu=4" class="nav-item">
                    <a class="nav-link" href="#"><i class="icon-book-open"></i> Cargas Masivas <span class="badge badge-success">CSV</span></a>
                </li>
                <li @click="menu=5" class="nav-item">
                    <a class="nav-link" href="#"><i class="icon-wallet"></i>Catalogos</a>
                </li>
                @endif

            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
