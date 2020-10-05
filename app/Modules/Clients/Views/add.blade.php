@extends('Layouts.master')
@section('title', 'عميل جديد')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/clients/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف بيانات العميل</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/clients') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-client'))
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
                                        <div class="col-xs-10 col-sm-5 col-md-10">
                                            <div class="form-group">
                                                <label>اسم العميل</label>
                                                <input type="text" class="form-control" placeholder="اسم العميل" name="name" value="{{ old('name') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-sm-1 col-md-2">
                                            <div class="form-group">
                                                <label>مفعل</label>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" name="active" {{ old('active') ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label>الرصيد الافتتاحي</label>
                                                <input type="number" min="0" class="form-control" placeholder="الرصيد الافتتاحي" name="balance" value="{{ old('balance') }}" required>
                                                
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label>العملة</label>
                                                <select class="form-control" name="currency_id"> 
                                                    <option>اختر العملة ...</option>
                                                    @foreach($data->currencies as $currency)
                                                    <option value="{{ $currency->id }}" data-area="{{ $currency->code }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                                    @endforeach 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="row" >
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>رقم التليفون</label>
                                                <input type="text" class="form-control" placeholder="رقم التليفون" name="phone" value="{{ old('phone') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>رقم الهوية</label>
                                                <input type="text"class="form-control" placeholder="رقم الهوية" name="identity" value="{{ old('identity') }}">
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