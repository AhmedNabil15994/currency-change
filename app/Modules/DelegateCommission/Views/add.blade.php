@extends('Layouts.master')
@section('title', 'عمولة جديدة')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/commissions/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف بيانات العمولة</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/commissions') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-commission'))
                                    <button type="submit" class="btn btn-round btn-success">اضافة <i class="fa fa-plus"></i></button>
                                    @endif
                                </div>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row" >
                                <div class="col-xs-12">
                                    <div class="row" >
                                        <div class="col-xs-12 col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>المندوب</label>
                                                <select name="delegate_id" class="form-control" required>
                                                    <option value="">اختر المندوب..</option>
                                                    @foreach($data->delegates as $key => $value)
                                                        <option value="{{ $value->id }}" {{ $value->id == old('delegate_id') ? "selected=selected" : ''  }}>{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>العمولة (%)</label>
                                                <input type="text" class="form-control" placeholder="العمولة (%)" name="commission" value="{{ old('commission') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>تاريخ الانتهاء</label>
                                                <input type="text" class="form-control datetimepicker" placeholder="تاريخ الانتهاء" name="valid_until" value="{{ old('valid_until') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-3">
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