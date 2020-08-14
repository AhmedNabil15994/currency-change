@extends('Layouts.master')
@section('title', 'عملة جديدة')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/currencies/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف بيانات العملة</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/currencies') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-currency'))
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
                                                <label>اسم العملة</label>
                                                <input type="text" class="form-control" placeholder="اسم العملة" name="name" value="{{ old('name') }}" required>
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
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="row" >
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <div class="form-group">
                                                <label>كود العملة (ISO CODE)</label>
                                                <input type="text" class="form-control" placeholder="كود العملة" name="code" value="{{ old('code') }}">
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