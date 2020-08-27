@extends('Layouts.master')
@section('title', 'تقارير حساب البنك')
@section('otherhead')
<style type="text/css" media="screen">
    .select2-container{
        width: 100% !important;
        margin-bottom: 15px !important;
    }
    textarea{
        margin-bottom: 15px;
    }
    td div.col-xs-6:last-of-type,
    th div.col-xs-6:last-of-type{
        border-right: 1px solid #DDD;
    }
</style>
@endsection
@section('content')
<div class="row">
        <form method="get" action="{{ URL::current() }}">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <strong>بحث بواسطة</strong>
                        <ul class="nav navbar-left panel_toolbox">
                            <div align="right">
                                <button type="submit" class="btn btn-primary" style="width:110px;"><i class="fa fa fa-search"></i> بحث ..</button>
                                @if(Input::has('bank_account_id') || Input::has('shop_id') || Input::has('from') || Input::has('to'))
                                    <a href="{{ URL::to('/reports/bankAccounts') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-redo"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-12">
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
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group supplier">
                                        <label>حساب البنك</label>
                                        <select class="form-control select2 bank_account_id" name="bank_account_id">
                                            <option value="0">اختر حساب البنك</option>
                                            @foreach($data->bankAccounts as $bankAccount)
                                                <option value="{{ $bankAccount->id }}" {{ Input::get('bank_account_id') == $bankAccount->id ? 'selected' : '' }}>{{ $bankAccount->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-3">
                                    <div class="form-group">
                                        <label>التاريخ (من)</label>
                                        <input type="text" class="form-control datetimepicker" name="from" placeholder="التاريخ (من)" value="{{ Input::get('from') }}">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-3">
                                    <div class="form-group">
                                        <label>التاريخ (الي)</label>
                                        <input type="text" class="form-control datetimepicker" name="to" placeholder="التاريخ (الي)" value="{{ Input::get('to') }}">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3>تقارير حساب البنك<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="x_content x_content_table">
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                        @foreach($data->data as $key => $value)
                        <div class="panel" id="panel{{ $value->id }}">
                            <a class="panel-heading collapsed" role="tab" data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{ $key }}" aria-expanded="false" aria-controls="collapseOne">
                                <h4 class="panel-title text-right col-xs-12 col-sm-12">
                                    <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                                        <span>{{ $key+1 }}- <i class="fa fa-question-circle"></i> {{ $value->shop_name }}</span>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                                        <span><i class="fa fa-university"></i>الاسم: {{ $value->name }}</span>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                                        <span><i class="fa fa-money-check-alt"></i>رقم الحساب: {{ $value->account_number }}</span>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                                        <span><i class="fa fa-money-bill-wave"></i>اجمالي المتاح: {{ $value->total }}</span>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                                        <span><i class="fa fa-money-bill-wave"></i>العملة: {{ $value->currency_name }}</span>
                                    </div>
                                </h4>
                                <div class="clearfix"></div>
                            </a>
                            <div id="collapseOne{{ $key }}" class="panel-collapse collapse" role="tabpanel" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <table class="data table hover table-striped no-margin">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>التاريخ</th>
                                                <th class="text-center">
                                                    صادر<br>
                                                    <div class="col-xs-4">{{ $value->currency_name }}</div>
                                                    <div class="col-xs-4">سعر الاستبدال</div>
                                                    <div class="col-xs-4">{{ $value->to }}</div>
                                                </th>
                                                <th class="text-center">
                                                    وارد<br>
                                                    <div class="col-xs-4">{{ $value->currency_name }}</div>
                                                    <div class="col-xs-4">سعر الاستبدال</div>
                                                    <div class="col-xs-4">{{ $value->to }}</div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($value->transfers as $keyOne => $one)
                                            <tr>    
                                                <td>{{ 1 }}</td>
                                                <td>{{ $keyOne }}</td>
                                                <td class="text-center">
                                                    <div class="col-xs-4">{{ @$one[0][0] }}</div>
                                                    <div class="col-xs-4">{{ @$one[0][1] }}</div>
                                                    <div class="col-xs-4">{{ @$one[0][2] }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="col-xs-4">{{ @$one[1][0] }}</div>
                                                    <div class="col-xs-4">{{ @$one[1][1] }}</div>
                                                    <div class="col-xs-4">{{ @$one[1][2] }}</div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @include('Partials.pagination')
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
@stop()