<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <img src="/assets/img/logo.png" alt="" style="height: 4.3em;width: 100%;">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ uri === '/' ? 'active' : '' }}">
            <a href="/" class="menu-link">
                <span class="iconify menu-icon" data-icon="ic:round-dashboard"></span>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Content Management -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Project Management</span>
        </li>

        <li class="menu-item {{ uri === 'project' ? 'active' : '' }}">
            <a href="" class="menu-link menu-toggle">
                <span class="iconify menu-icon" data-icon="ic:round-work"></span>
                <div data-i18n="Projects">Projects</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{url('project')}}" class="menu-link">
                        <div data-i18n="All Notes">View</span></div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{url('project/add')}}" class="menu-link">
                        <div data-i18n="Add Cateogry">Add Project</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ uri === 'ticket' ? 'active' : '' }}">
            <a href="" class="menu-link menu-toggle">
                <span class="iconify menu-icon" data-icon="ion:ticket"></span>
                <div data-i18n="Categories">Ticket</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{url('ticket/active')}}" class="menu-link">
                        <div data-i18n="All Categories">Active Ticket <span class="badge badge-center rounded-pill bg-primary ms-2">{{cat}}</span></div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{url('ticket')}}" class="menu-link">
                        <div data-i18n="Add Cateogry">All Ticket</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{url('ticket/answer')}}" class="menu-link">
                        <div data-i18n="Add Cateogry">History Answer</div>
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="menu-item {{ uri === 'client' ? 'active' : '' }}">
            <a href="{{url('client')}}" class="menu-link">
                <span class="iconify menu-icon" data-icon="fa-solid:users"></span>
                <div data-i18n="Dashboard">Clients</div>
            </a>
        </li>

        <!-- Documentation -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Documentation</span>
        </li>

        <li class="menu-item {{ uri === 'note' ? 'active' : '' }}">
            <a href="" class="menu-link menu-toggle">
                <span class="iconify menu-icon" data-icon="ic:round-sticky-note-2"></span>
                <div data-i18n="Notes">Notes</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{url('note')}}" class="menu-link">
                        <div data-i18n="All Notes">View</span></div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{url('note/add')}}" class="menu-link">
                        <div data-i18n="Add Cateogry">Add Note</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ uri === 'knowladge' ? 'active' : '' }}">
            <a href="" class="menu-link menu-toggle">
                <span class="iconify menu-icon" data-icon="fluent:brain-circuit-24-filled"></span>
                <div data-i18n="Projects">Knowladge</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{url('knowladge')}}" class="menu-link">
                        <div data-i18n="All Notes">View</span></div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{url('knowladge/add')}}" class="menu-link">
                        <div data-i18n="Add Cateogry">Add Knowladge</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Site Management -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Site Management</span>
        </li>

        <li class="menu-item {{ uri === 'client' ? 'active' : '' }}">
            <a href="{{url('setting')}}" class="menu-link">
                <span class="iconify menu-icon" data-icon="uiw:setting"></span>
                <div data-i18n="Dashboard">Settings</div>
            </a>
        </li>

    </ul>
</aside>
<!-- / Menu -->