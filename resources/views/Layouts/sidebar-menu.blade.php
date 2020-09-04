  <div class="col-md-3 left_col">
    <div class="scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ URL::to('/') }}" class="site_title"><i class="fas fa-store"></i> <span>{{ IS_ADMIN ? 'ادارة النظام' : \Session::get('shop_name') }} !</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ App\Models\User::getData(App\Models\User::getOne(USER_ID))->image }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>اهلا بك,</span>
                <h2>{{ ucwords(FULL_NAME) }} <i class="label bg-green online">متاح</i></h2>
                <span>{{ ucwords(GROUP_NAME) }}</span>
            </div>
        </div>
        <!-- /menu profile quick info -->
        
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    {{-- <li class="{{ Active(URL::to('/')) }}"><a href="{{ URL::to('/') }}"><i class="fas fa-home"></i> الرئيسية</a></li> --}}

                    @if(\Helper::checkRules('list-users,list-groups,list-shops'))
                    <li class="{{ Active(URL::to('/users*')) }} {{ Active(URL::to('/groups*')) }} {{ Active(URL::to('/shops*')) }}"><a><i class="fas fa-users"></i> المستخدمين و الفروع <span class="fas fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-users'))
                                <li class="{{ Active(URL::to('/users*')) }}"><a href="{{ URL::to('/users') }}">المستخدمين</a></li>
                            @endif

                            @if(\Helper::checkRules('list-groups'))
                                <li class="{{ Active(URL::to('/groups*')) }}"><a href="{{ URL::to('/groups') }}">مجموعات المستخدمين</a></li>
                            @endif

                            @if(\Helper::checkRules('list-shops'))
                                <li class="{{ Active(URL::to('/shops*')) }}"><a href="{{ URL::to('/shops') }}">الفروع</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-delegates,list-clients'))
                    <li class="{{ Active(URL::to('/delegates*')) }} {{ Active(URL::to('/clients*')) }} {{ Active(URL::to('/back-invoices*')) }}"><a><i class="fas fa-user-tie"></i> المندوبين والعملاء <span class="fas fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-delegates'))
                                <li class="{{ Active(URL::to('/delegates*')) }}"><a href="{{ URL::to('/delegates') }}">المندوبين</a></li>
                            @endif
                            @if(\Helper::checkRules('list-delegates,list-commissions'))
                                <li class="{{ Active(URL::to('/commissions*')) }}"><a href="{{ URL::to('/commissions') }}">عمولات المندوبين</a></li>
                            @endif
                            @if(\Helper::checkRules('list-clients'))
                                <li class="{{ Active(URL::to('/clients*')) }}"><a href="{{ URL::to('/clients') }}">العملاء</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-currencies,list-details'))
                    <li class="{{ Active(URL::to('/currencies*')) }} {{ Active(URL::to('/details*')) }}"><a><i class="fas fa-coins"></i> العملات والتحويلات <span class="fas fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-currencies'))
                                <li class="{{ Active(URL::to('/currencies*')) }}"><a href="{{ URL::to('/currencies') }}">العملات</a></li>
                            @endif
                            @if(\Helper::checkRules('list-details'))
                                <li class="{{ Active(URL::to('/details*')) }}"><a href="{{ URL::to('/details') }}">تحويلات العملات</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-exchanges'))
                    <li class="{{ Active(URL::to('/exchanges*')) }}"><a><i class="fas fa-exchange-alt"></i> عمليات الاستبدال <span class="fas fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-exchanges'))
                                <li class="{{ Active(URL::to('/exchanges*')) }}"><a href="{{ URL::to('/exchanges') }}">عمليات الاستبدال</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-bank-account,list-storages,list-storage-transfers'))
                    <li class="{{ Active(URL::to('/bank-accounts*')) }} {{ Active(URL::to('/storages*')) }} {{ Active(URL::to('/storage-transfers*')) }}"><a><i class="fas fa-dollar-sign"></i> الحسابات البنكية والصناديق <span class="fas fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-bank-accounts'))
                                <li class="{{ Active(URL::to('/bank-accounts*')) }}"><a href="{{ URL::to('/bank-accounts') }}">الحسابات البنكية</a></li>
                            @endif
                            @if(\Helper::checkRules('list-storages'))
                                <li class="{{ Active(URL::to('/storages*')) }}"><a href="{{ URL::to('/storages') }}">الصناديق</a></li>
                            @endif
                            @if(\Helper::checkRules('storage-transfers'))
                                <li class="{{ Active(URL::to('/storage-transfers*')) }}"><a href="{{ URL::to('/storage-transfers') }}">التحويل من الصناديق</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-transfers'))
                    <li class="{{ Active(URL::to('/transfers*')) }}"><a><i class="fas fa-retweet"></i> الحوالات البنكية <span class="fas fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-transfers'))
                                <li class="{{ Active(URL::to('/transfers*')) }}"><a href="{{ URL::to('/transfers') }}">الحوالات</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif


                    @if(\Helper::checkRules('list-expenses'))
                    <li class="{{ Active(URL::to('/expenses*')) }}"><a><i class="fas fa-money-bill-alt"></i> المصروفات <span class="fas fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-expenses'))
                                <li class="{{ Active(URL::to('/expenses*')) }}"><a href="{{ URL::to('/expenses') }}">المصروفات</a></li>
                            @endif
                            @if(\Helper::checkRules('list-salaries'))
                                <li class="{{ Active(URL::to('/salaries*')) }}"><a href="{{ URL::to('/salaries') }}">اجور العمال</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <li class="{{ Active(URL::to('/reports*')) }}"><a><i class="fas fa-file"></i> التقارير <span class="fas fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-expenses-reports'))
                                <li class="{{ Active(URL::to('/reports/expenses')) }}"><a href="{{ URL::to('/reports/expenses') }}">تقارير المصروفات العمومية</a></li>
                            @endif
                            @if(\Helper::checkRules('list-storages-reports'))
                                <li class="{{ Active(URL::to('/reports/storages*')) }}"><a href="{{ URL::to('/reports/storages') }}">تقارير حساب الصندوق</a></li>
                            @endif
                            @if(\Helper::checkRules('list-bank-accounts-reports'))
                                <li class="{{ Active(URL::to('/reports/bankAccounts*')) }}"><a href="{{ URL::to('/reports/bankAccounts') }}">تقارير حساب البنك</a></li>
                            @endif
                            @if(\Helper::checkRules('list-delegates-reports'))
                                <li class="{{ Active(URL::to('/reports/delegates*')) }}"><a href="{{ URL::to('/reports/delegates') }}">تقارير المندوبين</a></li>
                            @endif
                            @if(\Helper::checkRules('list-daily-reports'))
                                <li class="{{ Active(URL::to('/reports/daily*')) }}"><a href="{{ URL::to('/reports/daily') }}">التقارير اليومية</a></li>
                            @endif
                            @if(\Helper::checkRules('list-yearly-reports'))
                                <li class="{{ Active(URL::to('/reports/yearly*')) }}"><a href="{{ URL::to('/reports/yearly') }}">التقارير السنوية</a></li>
                            @endif
                        </ul>
                    </li>

                    {{-- @if(\Helper::checkRules('list-variables'))
                    <li class="{{ Active(URL::to('/variables*')) }}"><a><i class="fas fa-cogs"></i> الاعدادات <span class="fas fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-variables'))
                                <li class="{{ Active(URL::to('/variables*')) }}"><a href="{{ URL::to('/variables') }}">المتغيرات</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif --}}

                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
