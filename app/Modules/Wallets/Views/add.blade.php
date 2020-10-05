@extends('Layouts.master')
@section('title', 'عملية جديدة')
@section('otherhead')
<style type="text/css" media="screen">
    .delegates{
        display: none;
    }
    .select2-container{
        width: 100% !important;
    }
</style>
@endsection 
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/wallets/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف بيانات الاستبدال</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/wallets') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-wallet'))
                                    <button type="submit" class="btn btn-round btn-success">اضافة <i class="fa fa-plus"></i></button>
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
                                            <label>الفرع</label>
                                            <select class="form-control" name="shop_id"> 
                                                <option value="">اختر الفرع</option>
                                                @foreach($data->shops as $shop)
                                                <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
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
                                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2 clients">
                                        <div class="form-group">
                                            <label>نوع العملية</label>
                                            <select class="form-control" name="type"> 
                                                <option value="1" {{ old('type') == 1 ? 'selected':'' }}>ايداع</option>
                                                <option value="2" {{ old('type') == 2 ? 'selected':'' }}>سحب</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2 clients">
                                        <div class="form-group">
                                            <label>نوع العمولة</label>
                                            <select class="form-control" name="commission_type"> 
                                                <option value="1" {{ old('commission_type') == 1 ? 'selected':'' }}>قيمة</option>
                                                <option value="2" {{ old('commission_type') == 2 ? 'selected':'' }}>نسبة</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2 clients">
                                        <div class="form-group">
                                            <label>قيمة العمولة</label>
                                            <input type="text" name="commission_value" class="form-control" placeholder="قيمة العمولة" value="{{ old('commission_value') }}">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>الكمية</label>
                                            <input type="text" name="amount" class="form-control" placeholder="الكمية" value="{{ old('amount') }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>التحويل (من) : (الي)</label>
                                            <select class="form-control" name="details_id"> 
                                                <option value="">اختر عملية التحويل</option>
                                                @foreach($data->currencies as $currency)
                                                <option value="{{ $currency->id }}" data-area="{{ $currency->rate }}" data-area2="{{ $currency->to->name }}" {{ old('details_id') == $currency->id ? 'selected' : '' }}>{{ $currency->from->name }} الي {{ $currency->to->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label>سعر التحويل</label>
                                            <input type="text" name="convert_price" readonly class="form-control" placeholder="سعر التحويل" value="{{ old('convert_price') }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label>الاجمالي</label>
                                            <input type="text" name="total" readonly class="form-control" placeholder="الاجمالي" value="{{ old('total') }}"> 
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label>سعر البنك</label>
                                            <input type="text" name="bank_convert_price" class="form-control" placeholder="سعر البنك" value="{{ old('bank_convert_price') }}"> 
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
