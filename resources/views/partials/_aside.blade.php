<!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="{{ asset('images/user.png') }}" alt="User Image" width="40" height="40">
        <div>
          <p class="app-sidebar__user-name">{{ Auth::user()->name }}</p>
          <p class="app-sidebar__user-designation">{{ Auth::user()->role }}</p>
        </div>
      </div>
      <ul class="app-menu">

        <li><a class="app-menu__item" href="{{ route('home') }}"><i class="mr-2 fa-2 fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>

        <li><a class="app-menu__item" href="{{ route('categories.index') }}"><i class="mr-2 fa-2 fa fa-folder-open-o"></i><span class="app-menu__label">Categories</span></a></li>

        <li><a class="app-menu__item" href="{{ route('products.index') }}"><i class="mr-2 fa-2 fa fa fa-university"></i><span class="app-menu__label">Products</span></a></li>

        <li><a class="app-menu__item" href="{{ route('sales.index') }}"><i class="mr-2 fa-2 fa fa fa-money"></i><span class="app-menu__label">Make Sakes</span></a></li>

        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="mr-2 fa-2 fa fa fa-bed"></i><span class="app-menu__label">Accomodation</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            
            <li><a class="app-menu__item" href="{{ route('rooms') }}"><i class="mr-2 fa-2 fa fa fa-list"></i><span class="app-menu__label">Rooms List</span></a></li>

            <li><a class="app-menu__item" href="{{ route('rooms.class') }}"><i class="mr-2 fa-2 fa fa fa-users"></i><span class="app-menu__label">Room Classes</span></a></li>

            <li><a class="app-menu__item" href="{{ route('rooms.allocate') }}"><i class="mr-2 fa-2 fa fa fa-book"></i><span class="app-menu__label">Room Allocation</span></a></li>

          </ul>
        </li>
      </ul>
    </aside>