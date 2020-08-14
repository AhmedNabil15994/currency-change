<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
    <nav>
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{ App\Models\User::getData(App\Models\User::getOne(USER_ID))->image }}" alt="">{{ ucwords(FULL_NAME) }}
                    <span class=" fa fa-angle-down"></span>
                </a>
                {{-- @php
                    $shops = \App\Models\Shop::NotDeleted()->orderBy('id','DESC')->get();
                @endphp --}}
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                    {{-- @if(IS_ADMIN)
                    @foreach($shops as $shop)
                    <li><a href="{{ URL::to('/shops/view/'.$shop->id) }}"><i class="fa fa-user"></i> {{ $shop->title }}</a></li>
                    @endforeach
                    @endif --}}
                    <li><a href="{{ URL::to('/logout') }}"><i class="fa fa-sign-out-alt"></i> تسجيل الخروج</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    </div>
</div>
<!-- /top navigation -->
