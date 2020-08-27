@extends('Layouts.master')
@section('title', 'تقارير المندوبين')
@section('otherhead')
<style type="text/css" media="screen">
    .select2-container{
        width: 100% !important;
        margin-bottom: 15px !important;
    }
    textarea{
        margin-bottom: 15px;
    }
    .dt-buttons.btn-group,
    #example_filter,
    .dataTables_paginate{
        display: none;
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
                                @if(Input::has('user_id') || Input::has('from') || Input::has('to'))
                                    <a href="{{ URL::to('/reports/delegates') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-redo"></i></a>
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
                                        <label>العميل</label>
                                        <select class="form-control select2" name="user_id">
                                            <option value="0">اختر العميل</option>
                                            @foreach($data->delegates as $user)
                                                <option value="{{ $user->id }}" {{ Input::get('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
                            <h3>تقارير المندوبين<small> العدد الكلي : {{ $data->data_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                <a href="#" class="btn btn-default print" style="color: black;"><i class="fa fa-print"></i> طباعة</a>
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="x_content x_content_table">
                    <div class="panel-body">
                        <table id="example" class="table hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th class="text-right">الحساب</th>
                                <th class="text-right">نوع العملية</th>
                                <th class="text-right">المبلغ</th>
                                <th class="text-right">نسبة التحويل</th>
                                <th class="text-right">العمولة</th>
                                <th class="text-right">دائن</th>
                                <th class="text-right">مدين</th>
                                <th>التاريخ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data->data as $key => $value)
                                <tr id="tableRaw">
                                    <td width="3%">{{ $key+1 }}</td>
                                    <td>{{ $value->user_name }}</td>
                                    <td>{{ $value->type_text }}</td>
                                    <td>{{ $value->amount }} {{ $value->currency }}</td>
                                    <td>{{ $value->rate }}</td>
                                    <td>{{ $value->commssion }}</td>
                                    <td>{{ $value->dayen }}</td>
                                    <td>{{ $value->modein }}</td>
                                    <td>{{ $value->created_at }}</td>
                                </tr>
                            @endforeach
                            @if(Input::has('user_id') && $data->pagination->total_count > 0)
                            <tr>
                                <td>{{ $key+2 }}</td>
                                <td><b>اجمالي الايداع:</b></td>
                                <td colspan="3"><b>{{ @$data->totalDeposit[Input::get('user_id')] }}</b></td>
                                <td><b>اجمالي السحب:</b></td>
                                <td colspan="3"><b>{{ @$data->totalWithdraw[Input::get('user_id')] }}</b></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                            </tr>    
                            @endif
                            @if($data->pagination->total_count == 0)
                                <tr>
                                    <td></td>
                                    <td colspan="8">لا يوجد بيانات</td>
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
                </div>
                @include('Partials.pagination')
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
@stop()

@section('script')
<script src="{{ URL::to('/assets/components/delegates-reports.js') }}"></script>
@endsection