@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->name)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/bank-accounts/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات الحساب البنكي</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/bank-accounts') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-bank-account'))
                                <button type="submit" class="btn btn-round btn-success">حفظ <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="col-md-6 col-xs-12">
                                <div class="row" >
                                    <div class="col-xs-10 col-sm-5 col-md-10">
                                        <div class="form-group">
                                            <label>الفرع</label>
                                            <select name="shop_id" class="form-control" required>
                                                <option value="">اختر..</option>
                                                @foreach($data->shops as $shopKey => $shopValue)
                                                    <option value="{{ $shopValue->id }}" {{ $shopValue->id == $data->data->shop_id ? "selected=selected" : ''  }}>{{ $shopValue->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-1 col-md-2">
                                        <div class="form-group">
                                            <label>مفعل</label>
                                            <div class="checkbox">
                                                <input type="checkbox" class="flat" {{ $data->data->is_active == 1 ? "checked" : ""  }} name="active">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>اسم البنك</label>
                                            <input type="text" class="form-control" placeholder="اسم البنك" name="name" value="{{ $data->data->name }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>رقم الحساب</label>
                                            <input type="text" class="form-control" placeholder="رقم الحساب" name="account_number" value="{{ $data->data->account_number }}">
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row" >
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>الرصيد</label>
                                            <input type="number" min="0" class="form-control" placeholder="الرصيد" name="balance" value="{{ $data->data->balance }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>العملة</label>
                                            <select name="currency_id" class="form-control" required>
                                                <option value="">اختر..</option>
                                                @foreach($data->currencies as $currencyKey => $currencyValue)
                                                    <option value="{{ $currencyValue->id }}" {{ $currencyValue->id == $data->data->currency_id ? "selected=selected" : ''  }}>{{ $currencyValue->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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