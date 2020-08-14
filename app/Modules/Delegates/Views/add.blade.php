@extends('Layouts.master')
@section('title', 'مندوب جديد')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/delegates/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف بيانات المندوب</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/delegates') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-delegate'))
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
                                                <label>اسم المندوب</label>
                                                <input type="text" class="form-control" placeholder="اسم المندوب" name="name" value="{{ old('name') }}" required>
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
                                        <div class="col-xs-12 col-sm-6 col-md-12">
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
                                                <label>العمولة (%)</label>
                                                <input type="number" min="0" class="form-control" placeholder="العمولة (%)" name="commission" value="{{ old('commission') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <div class="form-group">
                                                <label>العنوان</label>
                                                <input type="text" class="form-control" placeholder="العنوان" name="address" value="{{ old('address') }}">
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