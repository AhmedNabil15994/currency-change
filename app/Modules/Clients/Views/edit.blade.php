@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->name)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/clients/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات العميل</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/clients') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-client'))
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
                                            <label>اسم العميل</label>
                                            <input type="text" class="form-control" placeholder="اسم العميل" name="name" value="{{ $data->data->name }}">
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
                                    <div class="col-xs-12 col-sm-4 col-md-5">
                                        <div class="form-group">
                                            <label>الرصيد الافتتاحي</label>
                                            <input type="number" min="0" class="form-control" placeholder="الرصيد الافتتاحي" name="balance" value="{{ $data->data->balance }}" required>
                                            
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-5">
                                        <div class="form-group">
                                            <label>العملة</label>
                                            <select class="form-control" name="currency_id"> 
                                                <option>اختر العملة ...</option>
                                                @foreach($data->currencies as $currency)
                                                <option value="{{ $currency->id }}" data-area="{{ $currency->code }}" {{ $data->data->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-2">
                                        <div class="form-group">
                                            <label>تم الدفع </label>
                                            <div class="checkbox">
                                                <input type="checkbox" class="flat" name="paid" {{ $data->data->is_paid == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row" >
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>رقم التليفون</label>
                                            <input type="text" class="form-control" placeholder="رقم التليفون" name="phone" value="{{ $data->data->phone}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>رقم الهوية</label>
                                            <input type="text" class="form-control" placeholder="رقم الهوية" name="identity" value="{{ $data->data->identity }}">
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