<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-university"></i>
        </div>
        <div class="sidebar-brand-text mx-3">TRESORERIE BSITEAM</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Tableau de bord</span></a>
    </li>


    
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#taTpDropDown12"
                aria-expanded="true" aria-controls="taTpDropDown12">
                <i class="fa fa-line-chart" aria-hidden="true"></i>
                <span>Statistiques</span>
            </a>
            <div id="taTpDropDown12" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Statistiques:</h6>
                    <a class="collapse-item" href="{{ route('achats.charts') }}">Débit/Crédit</a>
                    <!-- <a class="collapse-item" href="{{ route('bancaires.charts') }}"></a> -->
                    
                </div>
            </div>
        </li>
        <!-- Nav Item - Pages Collapse Menu -->
        <div class="sidebar-heading">
            Réglement bancaires
        </div>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#taTpDropDown44"
                aria-expanded="true" aria-controls="taTpDropDown44">
                <i class="fa fa-university" aria-hidden="true"></i>
                <span>Débit</span>
            </a>
            <div id="taTpDropDown44" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Crédit:</h6>
                    <a class="collapse-item" href="{{ route('achats.index') }}">Liste</a>
                    <a class="collapse-item" href="{{ route('achats.create') }}">Ajouter un nouveau</a>
                    <a class="collapse-item" href="{{ route('achats.import') }}">Importer des données</a>
                </div>
            </div>
        </li>
    
        <!-- Heading -->
        <div class="sidebar-heading">
            Réglement d'achats
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#taTpDropDown11"
                aria-expanded="true" aria-controls="taTpDropDown11">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                <span>Crédit</span>
            </a>
            <div id="taTpDropDown11" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Débit:</h6>
                    <a class="collapse-item" href="{{ route('bancaires.index') }}">Liste</a>
                    <a class="collapse-item" href="{{ route('bancaires.create') }}">Ajouter un nouveau</a>
                    <a class="collapse-item" href="{{ route('bancaires.import') }}">Importer des données</a>
                </div>
            </div>
        </li>
        

        <div class="sidebar-heading">
            fournisseurs-clients
        </div>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#taTpDropDown88"
                aria-expanded="true" aria-controls="taTpDropDown88">
                <i class="fas fa-user-alt"></i>
                <span>fournisseurs</span>
            </a>
            <div id="taTpDropDown88" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">fournisseurs:</h6>
                    <a class="collapse-item" href="{{ route('fournisseurs.index') }}">Liste</a>
                    <a class="collapse-item" href="{{ route('fournisseurs.create') }}">Ajouter un nouveau</a>
                    <!-- <a class="collapse-item" href="{{ route('fournisseurs.import') }}">Importer des données</a> -->
                </div>
            </div>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#taTpDropDown30"
                aria-expanded="true" aria-controls="taTpDropDown30">
                <i class="fas fa-user-alt"></i>
                <span>clients</span>
            </a>
            <div id="taTpDropDown30" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">clients:</h6>
                    <a class="collapse-item" href="{{ route('clients.index') }}">Liste</a>
                    <a class="collapse-item" href="{{ route('clients.create') }}">Ajouter un nouveau</a>
                    <!-- <a class="collapse-item" href="{{ route('clients.import') }}">Importer des données</a> -->
                </div>
            </div>
        </li>

        
        <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Gestion
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#taTpDropDown"
                    aria-expanded="true" aria-controls="taTpDropDown">
                    <i class="fas fa-user-alt"></i>
                    <span>Gestion des utilisateurs</span>
                </a>
                <div id="taTpDropDown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Gestion des utilisateurs:</h6>
                        <a class="collapse-item" href="{{ route('users.index') }}">Liste</a>
                        <a class="collapse-item" href="{{ route('users.create') }}">Ajouter un nouveau</a>
                        <a class="collapse-item" href="{{ route('users.import') }}">Importer des données</a>
                    </div>
                </div>
            </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">

    @hasrole('Admin')
        <!-- Heading -->
        <div class="sidebar-heading">
            Section D'administration
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Maître</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Role & Permissions</h6>
                    <a class="collapse-item" href="{{ route('roles.index') }}">Roles</a>
                    <a class="collapse-item" href="{{ route('permissions.index') }}">Permissions</a>
                </div>
            </div>
        </li>
        @endhasrole



    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt"></i>
            <span>Se déconnecter</span>
        </a>
    </li>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
    


</ul>