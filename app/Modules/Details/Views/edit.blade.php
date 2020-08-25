@extends('Layouts.master')
@section('title', $data->data->from->name . ' - ' . $data->data->to->name)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/details/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل البيانات</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/details') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-details'))
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
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label>العملة (من)</label>
                                            <select class="form-control" name="from_id"> 
                                                <option>اختر العملة ...</option>
                                                @foreach($data->currencies as $currency)
                                                <option value="{{ $currency->id }}" data-area="{{ $currency->code }}" {{ $data->data->from_id == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label>العملة (الي)</label>
                                            <select class="form-control" name="to_id"> 
                                                <option>اختر العملة ...</option>
                                                @foreach($data->currencies as $currency2)
                                                <option value="{{ $currency2->id }}" data-area="{{ $currency2->code }}" {{ $data->data->to_id == $currency2->id ? 'selected' : '' }}>{{ $currency2->name }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row" >
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>نوع العمولة</label>
                                            <select class="form-control" name="type"> 
                                                <option value="1" {{ $data->data->type == 1 ? 'selected' : '' }}>نسبة</option>
                                                <option value="2" {{ $data->data->type == 2 ? 'selected' : '' }}>مبلغ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>القيمة</label>
                                            <input type="text" class="form-control" name="rate" placeholder="القيمة" value="{{ $data->data->rate }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>مفعل</label>
                                            <div class="checkbox">
                                                <input type="checkbox" class="flat" name="active" {{ $data->data->is_active == 1 ? 'checked' : '' }}>
                                            </div>
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