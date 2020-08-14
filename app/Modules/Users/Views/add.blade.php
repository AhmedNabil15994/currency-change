@extends('Layouts.master')
@section('title', 'مستخدم جديد')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/users/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف بيانات المستخدم</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/users') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-user'))
                                    <button type="submit" class="btn btn-round btn-success">اضافة <i class="fa fa-plus"></i></button>
                                    @endif
                                </div>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row" >
                                <div class="col-md-6 col-xs-12">
                                    <div class="row" >
                                        <div class="col-xs-6 col-sm-3 col-md-6">
                                            <div class="form-group">
                                                <label>الاسم الاول</label>
                                                <input type="text" class="form-control" placeholder="الاسم الاول" name="first_name" value="{{ old('first_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-3 col-md-6">
                                            <div class="form-group">
                                                <label>الاسم الاخير</label>
                                                <input type="text" class="form-control" placeholder="الاسم الاخير" name="last_name" value="{{ old('last_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <div class="form-group">
                                                <label>البريد الالكتروني</label>
                                                <input type="email" class="form-control" placeholder="البريد الالكتروني" name="email" value="{{ old('email') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <div class="form-group">
                                                <label>كلمة المرور</label>
                                                <input type="password" class="form-control" placeholder="كلمة المرور" name="password" required>
                                            </div>
                                        </div>
                                        @if(\Session::get('group_id') != 3)
                                        <div class="col-xs-12 col-sm-3 col-md-6">
                                            <div class="form-group">
                                                <label>الفرع</label>
                                                <select name="shop_id" class="form-control" required>
                                                    <option value="">اختر..</option>
                                                    @foreach($data->shops as $shopKey => $shopValue)
                                                        <option value="{{ $shopValue->id }}" {{ $shopValue->id == old('shop_id') ? "selected=selected" : ''  }}>{{ $shopValue->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-xs-12 col-sm-3 col-md-6">
                                            <div class="form-group">
                                                <label>تاريخ بداية العمل</label>
                                                <input type="text" class="form-control datetimepicker" placeholder="تاريخ بداية العمل" name="start_date" value="{{ old('start_date') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="row" >
                                        <div class="col-xs-6 col-sm-3 col-md-6">
                                            <div class="form-group">
                                                <label>المجموعة</label>
                                                <select name="group_id" class="form-control" required>
                                                    <option value="">اختر..</option>
                                                    @foreach($data->groups as $groupKey => $groupValue)
                                                        <option value="{{ $groupValue->id }}" {{ $groupValue->id == old('group_id') ? "selected=selected" : ''  }}>{{ $groupValue->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-3 col-md-6">
                                            <div class="form-group">
                                                <label>مفعل</label>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" name="active" {{ old('active') ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <div class="form-group">
                                                <label>رقم التليفون</label>
                                                <input type="text" class="form-control" placeholder="رقم التليفون" name="phone" value="{{ old('phone') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <div class="form-group">
                                                <label>العنوان</label>
                                                <input type="text" class="form-control" placeholder="العنوان" name="address" value="{{ old('address') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-6">
                                            <div class="form-group">
                                                <label>الراتب</label>
                                                <input type="number" min="1" class="form-control" placeholder="الراتب" name="salary" value="{{ old('salary') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-3 col-md-6">
                                            <div class="form-group">
                                                <label>العملة</label>
                                                <select name="currency_id" class="form-control" required>
                                                    <option value="">اختر..</option>
                                                    @foreach($data->currencies as $currencyKey => $currencyValue)
                                                        <option value="{{ $currencyValue->id }}" {{ $currencyValue->id == old('currency_id') ? "selected=selected" : ''  }}>{{ $currencyValue->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <h3><b>الصلاحيات الاضافية</b></h3> <br>
                                        <select id='custom-headers' class="searchable" name="permissions[]" multiple='multiple'>
                                            @forelse($data->permissions as $permissionKey => $permissionValue)
                                                <option value="{{$permissionValue}}">{{$permissionValue}}</option>
                                            @empty
                                                <option value="0" disabled selected>-- لا توجد بيانات -- </option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop()

@section('script')
    <script src="{{asset('assets/components/users.js')}}"></script>
@stop
