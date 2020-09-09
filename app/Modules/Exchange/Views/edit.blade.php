@extends('Layouts.master')
@section('title', 'عملية استبدال رقم - ' . $data->data->id)
@section('otherhead')

<style type="text/css" media="screen">
    .client-data{
        display: none;
    }
    @if($data->data->type == 1)
    .delegates{
        display: none;
    }
    @else
    .clients{
        display: none;
    }
    @endif
    .select2-container{
        width: 100% !important;
    }
</style>
@endsection 
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/exchanges/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات الاستبدال</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/exchanges') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-exchange'))
                                <button type="submit" class="btn btn-round btn-success">حفظ <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="col-xs-12">
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>الفرع (الايداع)</label>
                                        <select class="form-control" name="shop_id"> 
                                            <option value="">اختر الفرع</option>
                                            @foreach($data->shops as $shop)
                                            <option value="{{ $shop->id }}" {{ $data->data->shop_id == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>الفرع (السحب)</label>
                                        <select class="form-control" name="to_shop_id"> 
                                            <option value="">اختر الفرع</option>
                                            @foreach($data->shops as $shop)
                                            <option value="{{ $shop->id }}" {{ $data->data->to_shop_id == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>نوع العميل</label>
                                        <select class="form-control" name="type"> 
                                            <option value="1" {{ $data->data->type == 1 ? 'selected' : '' }}>عميل</option>
                                            <option value="2" {{ $data->data->type == 2 ? 'selected' : '' }}>مندوب</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 delegates">
                                    <div class="form-group">
                                        <label>المندوب</label>
                                        <select class="form-control" name="delegate_id"> 
                                            <option value="">اختر المندوب</option>
                                            @foreach($data->delegates as $delegate)
                                            <option value="{{ $delegate->id }}" {{ $data->data->user_id == $delegate->id ? 'selected' : '' }}>{{ $delegate->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 clients">
                                    <div class="form-group">
                                        <label>العميل</label>
                                        <select class="form-control" name="client_id"> 
                                            <option value="0">عميل جديد</option>
                                            @foreach($data->clients as $client)
                                            <option value="{{ $client->id }}" {{ $data->data->user_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 client-data">
                                    <div class="form-group">
                                        <label>اسم العميل</label>
                                        <input type="text" class="form-control" name="client_name" placeholder="اسم العميل" value="{{ old('client_name') }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 client-data">
                                    <div class="form-group">
                                        <label>رقم التليفون</label>
                                        <input type="text" class="form-control" name="client_phone" placeholder="رقم التليفون" value="{{ old('client_phone') }}">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>الكمية</label>
                                        <input type="text" name="amount" class="form-control" placeholder="الكمية" value="{{ $data->data->amount }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>التحويل (من) : (الي)</label>
                                        <select class="form-control" name="details_id"> 
                                            <option value="">اختر عملية التحويل</option>
                                            @foreach($data->currencies as $currency)
                                            <option value="{{ $currency->id }}" data-area="{{ $currency->rate }}" data-area2="{{ $currency->to->name }}" {{ $data->data->details_id == $currency->id ? 'selected' : '' }}>{{ $currency->from->name }} الي {{ $currency->to->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>سعر التحويل</label>
                                        <input type="text" name="price" readonly class="form-control" placeholder="سعر التحويل" value="{{ $data->data->convert_price }} {{ $data->data->to->name }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>المطلوب تسليمه للعميل</label>
                                        <input type="text" name="paid" readonly class="form-control" placeholder="المطلوب تسليمه للعميل" value="{{ $data->data->paid }} {{ $data->data->to->name }}"> 
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
    <script src="{{ asset('assets/components/exchanges.js')}}"></script>
@stop()