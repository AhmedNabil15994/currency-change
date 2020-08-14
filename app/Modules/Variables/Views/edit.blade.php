@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->key)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/variables/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات المتغير</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/variables') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-variable'))
                                <button type="submit" class="btn btn-round btn-success">حفظ <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>المتغير</label>
                                        <input type="text" class="form-control" name="key" placeholder="المتغير" value="{{ $data->data->key }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>القيمة</label>
                                        <input type="text" class="form-control" name="value" placeholder="القيمة" value="{{ $data->data->value }}">
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
