@extends('Layouts.master')
@section('title', 'الحوالات البنكية')

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
                                @if(Input::has('company') || Input::has('bank_account_id') || Input::has('type') || Input::has('currency_id') || Input::has('delegate_id'))
                                    <a href="{{ URL::to('/transfers') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa-redo"></i></a>
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
                                    <label>النوع</label>
                                    <select class="form-control select2" name="type">
                                        <option value="0">اختر النوع</option>
                                        <option value="1" {{ Input::get('type') == 1 ? 'selected' : '' }}>وارد</option>
                                        <option value="2" {{ Input::get('type') == 2 ? 'selected' : '' }}>صادر</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>المندوب</label>
                                    <select class="form-control select2" name="delegate_id">
                                        <option value="0">اختر المندوب</option>
                                        @foreach($data->delegates as $delegate)
                                            <option value="{{ $delegate->id }}" {{ Input::get('delegate_id') == $delegate->id ? 'selected' : '' }}>{{ $delegate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>الحساب البنكي</label>
                                    <select class="form-control select2" name="bank_account_id">
                                        <option value="0">اختر الحساب البنكي</option>
                                        @foreach($data->accounts as $account)
                                            <option value="{{ $account->id }}" {{ Input::get('bank_account_id') == $account->id ? 'selected' : '' }}>{{ $account->account_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>اسم الشركة</label>
                                    <input type="text" class="form-control" name="company" placeholder="اسم الشركة" value="{{ Input::get('company') }}">
                                </div>
                            </div>
                            <div class="clearfix"></div>
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
                            <h3>الحوالات البنكية<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-transfer'))
                                    <a href="{{URL::to('/transfers/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> حوالة بنكية جديدة</a>
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
                            <th class="text-right">النوع</th>
                            <th class="text-right">المندوب</th>
                            <th class="text-right">الحساب البنكي</th>
                            <th class="text-right">الشركة</th>
                            <th class="text-right">حساب الشركة</th>
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
                                <td>{{ $value->type_text }}</td>
                                <td>{{ $value->delegate_name }}</td>
                                <td>{{ $value->bank_account }}</td>
                                <td>{{ $value->company }}</td>
                                <td>{{ $value->company_account }}</td>
                                <td>{{ $value->balance }}</td>
                                <td>{{ $value->currency_name }}</td>
                                <td width="3%" align="left"><span class="btn {{ $value->is_active == 1 ? "btn-success" : "btn-danger" }} btn-xs"> {{ $value->active }}</span></td>
                                <td class="actions" width="15%" align="left">
                                    @if(\Helper::checkRules('edit-transfer'))
                                        <a href="{{ URL::to('/transfers/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-alt"></i> تعديل </a>
                                    @endif

                                    @if(\Helper::checkRules('delete-transfer'))
                                        <a onclick="deleteTransfer('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> حذف </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="9">لا يوجد حوالات بنكية</td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
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
    <script src="{{ asset('assets/components/transfers.js')}}"></script>
@stop()
