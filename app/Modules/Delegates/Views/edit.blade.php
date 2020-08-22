@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->name)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/delegates/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات المندوب</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/delegates') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-delegate'))
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
                                            <label>اسم المندوب</label>
                                            <input type="text" class="form-control" placeholder="اسم المندوب" name="name" value="{{ $data->data->name }}">
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
                                    <div class="col-xs-12 col-sm-6 col-md-12">
                                        <div class="form-group">
                                            <label>الفرع</label>
                                            <select name="shop_id[]" class="form-control" required multiple>
                                                <option value="">اختر..</option>
                                                @foreach($data->shops as $shopKey => $shopValue)
                                                    <option value="{{ $shopValue->id }}" {{ in_array($shopValue->id,$data->data->shops_id) ? "selected=selected" : ''  }}>{{ $shopValue->title }}</option>
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
                                            <input type="text" class="form-control" placeholder="رقم التليفون" name="phone" value="{{ $data->data->phone}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>العنوان</label>
                                            <input type="text" class="form-control" placeholder="العنوان" name="address" value="{{ $data->data->address }}">
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