@extends('Layouts.master')
@section('title', 'صندوق جديد')
@section('otherhead')
<style type="text/css" media="screen">
    .select2-container{
        width: 100% !important;
    }
    .dataTables_info{
        display: none !important;
    }
    .second-content{
        display: none;
    }
    .new-prod{
        display: block;
        margin-left: 15px;
        margin-top: 23px;
        padding: 7px 10px;

    }
    .first-supply h4{
        font-size: 22px;
        margin-right: 10px;
        margin-bottom: 20px;
        color: #999;
    }
    .x_title h3 {
        color:#999;
        margin: 5px 0 6px;
        float: right;
        display: block;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        min-height: 30px;
    }
    table{
        margin-bottom: 5px !important;
    }
    p.total-invoice{
        font-size: 16px;
        font-weight: bold;
    }
    #invoice{
        padding-bottom: 10px;
    }
    #invoice h1{
        letter-spacing: 2px;
    }
    #invoice span.pull-right{
        margin-left: 10px;
    }
    #invoice .dates{
        padding-bottom: 5px;
        border-bottom: 1px solid #000;
    }
    #invoice .heading,
    #invoice .data{
        padding: 5px;
        border: 1px solid #000;
        margin-bottom: 1px;
    }
    #invoice .quantity{
        border-right: 1px solid #000;
        border-left: 1px solid #000;
    }
    #invoice .row .col-xs-2{
        text-align: center;
    }
    #invoice .data{
        border-radius: 5px;
    }
    #invoice .restore{
        padding: 5px;
        border-bottom: 1px solid #000;
    }
    #invoice .values{
        padding-bottom: 1px;
    }
    #invoice .values,
    #invoice .seller{
        border-bottom: 1px solid #000;
    }
    #invoice .values .col-xs-6.phone,
    #invoice .seller .col-xs-6{
        padding: 5px 0;
    }
    #invoice .address{
        padding: 5px;
    }
    #invoice .values .phone{
        border-radius: 5px;
        border: 1px solid #000;
    }
    #invoice .values .phone span{
        display: block;
        width: 100%;
        margin-bottom: 5px;
    }
    #invoice .total{
        margin-bottom: 7px;
    }
    #invoice .myFontSize{
        font-size: 12px;
    }
    #invoice .footer{
        margin-bottom: 10px;
    }
    .bold{
        font-weight: bold;
    }
</style>
@endsection
@section('content')
    <div class="mainPAge">
        <div class="row first-row" >
            <form method="post" action="{{ URL::to('/storages/create/') }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف بيانات الصندوق</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/storages') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-storage'))
                                    <button type="submit" class="btn btn-round btn-success addSupply">اضافة <i class="fa fa-plus"></i></button>
                                    @endif
                                </div>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row first-supply">
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>الفرع</label>
                                        <select class="form-control shop_id select2">
                                            <option value="0">اختر الفرع</option>
                                            @foreach($data->shops as $shop)
                                                <option value="{{ $shop->id }}" {{ Input::get('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>مفعل</label>
                                        <div class="checkbox">
                                            <input type="checkbox" class="flat" name="active" {{ old('active') ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row second-row">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h3><i class="fa fa-shopping-cart"></i> العملات المتاحة :</h3>
                        <ul class="nav navbar-right panel_toolbox">
                            <li class="pull-left"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content x_content_playlist">
                        <div class="col-xs-12 col-sm-12 col-md-12 first-content">
                            <div class="col-xs-12 col-md-6">
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>العملة</label>
                                        <select class="form-control currency_id select2">
                                            <option value="0">اختر العملة</option>
                                            @foreach($data->currencies as $currency)
                                                <option value="{{ $currency->id }}" {{ Input::get('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>الرصيد</label>
                                        <input type="number" name="balance" min="1" class="form-control" placeholder="الرصيد" value="1">
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-6" style="padding: 0;">
                                    <div class="form-group">
                                        <label></label>
                                        <button class="btn btn-xs btn-success form-control addNewShoe" style="margin-top: 5px;"><i class="fa fa-plus"></i> اضف العملة</button>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-xs-12 col-md-6 data-table">
                                <table class="table hover first-table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>الفرع</th>
                                        <th>الرصيد</th>
                                        <th>العملة</th>
                                        <th>العمليات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="empty">
                                            <td></td>
                                            <td colspan="3">لا توجد بيانات</td>
                                            <td style="display: none;"></td>
                                            <td style="display: none;"></td>
                                        </tr>    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop()
@section('script')
    <script src="{{ asset('assets/components/add-storage.js')}}"></script>
@stop()
