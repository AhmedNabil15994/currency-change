@extends('Layouts.master')
@section('title', 'تحويلة جديدة')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/details/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف البيانات</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/details') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-details'))
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
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label>العملة (من)</label>
                                                <select class="form-control" name="from_id"> 
                                                    <option>اختر العملة ...</option>
                                                    @foreach($data->currencies as $currency)
                                                    <option value="{{ $currency->id }}" data-area="{{ $currency->code }}" {{ old('from_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                                    @endforeach 
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label>العملة (الي)</label>
                                                <select class="form-control" name="to_id"> 
                                                    <option>اختر العملة ...</option>
                                                    @foreach($data->currencies as $currency)
                                                    <option value="{{ $currency->id }}" data-area="{{ $currency->code }}" {{ old('to_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
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
                                                <label>سعر التغيير</label>
                                                <input type="text" class="form-control" name="rate" placeholder="سعر التغيير" value="{{ old('rate') }}">
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