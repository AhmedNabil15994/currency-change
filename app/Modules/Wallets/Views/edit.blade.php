@extends('Layouts.master')
@section('title', 'عملية رقم - ' . $data->data->id)
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
        <form method="post" action="{{ URL::to('/wallets/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات العملية</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/wallets') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-wallet'))
                                <button type="submit" class="btn btn-round btn-success">حفظ <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="row" >
                                <div class="col-xs-12">
                                    <div class="col-xs-12 col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>الفرع</label>
                                            <select class="form-control" name="shop_id"> 
                                                <option value="">اختر الفرع</option>
                                                @foreach($data->shops as $shop)
                                                <option value="{{ $shop->id }}" {{ $data->data->shop_id == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3 clients">
                                        <div class="form-group">
                                            <label>العميل</label>
                                            <select class="form-control" name="client_id"> 
                                                <option value="">اختر العميل</option>
                                                @foreach($data->clients as $client)
                                                <option value="{{ $client->id }}" {{ $data->data->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2 clients">
                                        <div class="form-group">
                                            <label>نوع العملية</label>
                                            <select class="form-control" name="type"> 
                                                <option value="1" {{ $data->data->type == 1 ? 'selected':'' }}>ايداع</option>
                                                <option value="2" {{ $data->data->type == 2 ? 'selected':'' }}>سحب</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2 clients">
                                        <div class="form-group">
                                            <label>نوع العمولة</label>
                                            <select class="form-control" name="commission_type"> 
                                                <option value="1" {{ $data->data->commission_type == 1 ? 'selected':'' }}>ريت</option>
                                                <option value="2" {{ $data->data->commission_type == 2 ? 'selected':'' }}>عمولة</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2 clients">
                                        <div class="form-group">
                                            <label class="myLabel">ريت</label>
                                            <input type="text" name="commission_value" class="form-control" placeholder="ريت" value="{{ $data->data->commission_value }}">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label>الكمية</label>
                                            <input type="text" name="amount" class="form-control" placeholder="الكمية" value="{{ $data->data->amount }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label>العملة (المحول منها)</label>
                                            <select class="form-control" name="from_id"> 
                                                <option value="">اختر العملة </option>
                                                @foreach($data->currencies as $currency)
                                                <option value="{{ $currency->id }}" data-area2="{{ $currency->name }}" {{ $data->data->from_id == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label>العملة (المحول اليها)</label>
                                            <select class="form-control" name="to_id"> 
                                                <option value="">اختر العملة </option>
                                                @foreach($data->currencies as $currency)
                                                <option value="{{ $currency->id }}" data-area2="{{ $currency->name }}" {{ $data->data->to_id == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label>سعر التحويل</label>
                                            <input type="text" name="convert_price" class="form-control" placeholder="سعر التحويل" value="{{ $data->data->convert_price }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label>سعر البنك</label>
                                            <input type="text" name="bank_convert_price" class="form-control" placeholder="سعر البنك" value="{{ $data->data->bank_convert_price }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label>المطلوب تسليمه للعميل</label>
                                            <input type="text" name="total" readonly class="form-control" placeholder="المطلوب تسليمه للعميل" value="{{ $data->data->total . ' '. $data->data->to->name }}"> 
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
    <script src="{{ asset('assets/components/wallets.js')}}"></script>
@stop()