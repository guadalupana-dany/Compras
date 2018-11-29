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
                @if(Auth::user()->hasRole('Administrador') or Auth::user()->hasRole('Departamento'))
                <li @click="menu=3" class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-newspaper-o"></i>Requisición</a>
                </li>
                <li @click="menu=15" class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-newspaper-o"></i>Mis Requi.</a>
                </li>
                @endif
                @if(Auth::user()->hasRole('Administrador') or Auth::user()->hasRole('Verificador'))
                <li @click="menu=6" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-eye"></i>Requi Pendientes</a>
                </li>
                <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Reportes</a>
                        <ul class="nav-dropdown-items">

                            <li @click="menu=11" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-home"></i>Control Bodega</a>
                            </li>
                            <li @click="menu=10" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa  fa-file-text-o"></i>Reporte Conta</a>
                            </li>
                            <li @click="menu=13" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa  fa-file-text-o"></i>Requi. Realizadas</a>
                            </li>
                            <li @click="menu=14" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa  fa-file-text-o"></i>Requi. Rechazadas</a>
                            </li>
                            <li @click="menu=12" class="nav-item">
                                    <a class="nav-link" href="#"><i class="fa  fa-file-text-o"></i>Detalle de Requi.</a>
                            </li>
                        </ul>
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
