@extends('Layouts.master')
@section('title', 'فرع جديد')
<style type="text/css" media="screen">
    span.upload_cost{
        font-size: 16px;
        font-weight:  bold;
    }
</style>
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/shops/create/') }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف بيانات الفرع</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/shops') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-shop'))
                                    <button type="submit" class="btn btn-round btn-success">اضافة <i class="fa fa-plus"></i></button>
                                    @endif
                                </div>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row" >
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>الاسم</label>
                                        <input type="text" class="form-control" placeholder="الاسم" name="title" value="{{ old('title') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>رقم التليفون</label>
                                        <input type="text" class="form-control" placeholder="رقم التليفون" name="phone" value="{{ old('phone') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>العنوان</label>
                                        <input type="text" class="form-control" placeholder="العنوان" name="address" value="{{ old('address') }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                @if(\Helper::checkRules('add-shop-image'))
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="row" >
                                        <h3><b>صورة الفرع</b></h3> <br>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <h3 class="">تغيير صورة الفرع</h3>
                                                <h6>اختر صورة مختلفة...</h6>
                                                <input id="fileUpload" name="image" type="file">
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop()
@section('script')
    <script src="{{ asset('assets/components/shop.js')}}"></script>
@stop()
