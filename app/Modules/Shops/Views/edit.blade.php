@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->title)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/shops/update/' . $data->data->id) }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات الفرع</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/shops') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-shop'))
                                <button type="submit" class="btn btn-round btn-success">حفظ <i class="fa fa-check"></i></button>
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
                                    <input type="text" class="form-control" placeholder="الاسم" name="title" value="{{ $data->data->title }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>رقم التليفون</label>
                                    <input type="text" class="form-control" placeholder="رقم التليفون" name="phone" value="{{ $data->data->phone }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>العنوان</label>
                                    <input type="text" class="form-control" placeholder="العنوان" name="address" value="{{ $data->data->address }}">
                                </div>
                            </div>                            
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-xs-12 images">
                                <div class="row" >
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
                                    <div class="col-xs-12">
                                        <div class="imagesHolder">
                                            <h3 class="">المعرض</h3>
                                            @if($data->data->image != '')
                                                <figure id="imgRaw{{$data->data->id}}">
                                                    @if(\Helper::checkRules('delete-shop-image'))
                                                        <a onclick="deleteImage('{{$data->data->id}}')" class="remove fa fa-times btn btn-xs"></a>
                                                    @endif
                                                    <a href="{{ $data->data->image }}" class="fancybox" rel="gallery" title="">
                                                        <img src="{{ $data->data->image }}" class="avatar" alt="avatar" style="width: 200px;height: 200px;">
                                                    </a>
                                                </figure>
                                            @else
                                                <div class="empty">
                                                    <img src="{{ URL::to('/assets/images/not-available.jpg') }}" class="avatar img-circle" alt="avatar" style="width: 250px;height: 250px;">
                                                </div>
                                            @endif
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
@section('script')
    <script src="{{ asset('assets/components/shop.js')}}"></script>
@stop()
