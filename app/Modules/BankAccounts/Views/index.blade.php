@extends('Layouts.master')
@section('title', 'الحسابات البنكية')

@section('content')

    <div class="row">
        <form method="get" action="{{ URL::current() }}">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <strong>بحث بواسطة</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <button type="submit" class="btn btn-primary" style="width:110px;"><i class="fa fa-search"></i> بحث ..</button>
                                @if(Input::has('name') || Input::has('account_number') || Input::has('currency_id') || Input::has('shop_id'))
                                    <a href="{{ URL::to('/bank-accounts') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa-redo"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>الفرع</label>
                                    <select class="form-control select2" name="shop_id">
                                        <option value="0">اختر الفرع</option>
                                        @foreach($data->shops as $shop)
                                            <option value="{{ $shop->id }}" {{ Input::get('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>اسم البنك</label>
                                    <input type="text" class="form-control" name="name" placeholder="اسم البنك" value="{{ Input::get('name') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>رقم الحساب</label>
                                    <input type="text" class="form-control" name="account_number" placeholder="رقم الحساب" value="{{ Input::get('account_number') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>العملة</label>
                                    <select class="form-control select2" name="currency_id">
                                        <option value="0">اختر العملة</option>
                                        @foreach($data->currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ Input::get('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3>الحسابات البنكية<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-bank-account'))
                                    <a href="{{URL::to('/bank-accounts/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> حساب بنكي جديد</a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="x_content x_content_table">
                    <table id="tableList" class="table hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-right">الفرع</th>
                            <th class="text-right">اسم البنك</th>
                            <th class="text-right">رقم الحساب</th>
                            <th class="text-right">الرصيد</th>
                            <th class="text-right">العملة</th>
                            <th>الحالة</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->shop_name }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->account_number }}</td>
                                <td>{{ $value->balance }}</td>
                                <td>{{ $value->currency_name }}</td>
                                <td width="3%" align="left"><span class="btn {{ $value->is_active == 1 ? "btn-success" : "btn-danger" }} btn-xs"> {{ $value->active }}</span></td>
                                <td class="actions" width="15%" align="left">
                                    @if(\Helper::checkRules('edit-bank-account'))
                                        <a href="{{ URL::to('/bank-accounts/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-alt"></i> تعديل </a>
                                    @endif

                                    @if(\Helper::checkRules('delete-bank-account'))
                                        <a onclick="deleteBankAccount('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> حذف </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="7">لا يوجد حسابات بنكية</td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                            </tr>    
                        @endif
                        </tbody>
                    </table>
                </div>
                @include('Partials.pagination')
                <div class="clearfix"></div>
            </div>
        </div>

    </div>

@stop

@section('script')
    <script src="{{ asset('assets/components/bank-accounts.js')}}"></script>
@stop()
