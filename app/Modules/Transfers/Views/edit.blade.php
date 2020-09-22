@extends('Layouts.master')
@section('title', $data->data->id . ' - حوالة ' . $data->data->type_text)
@section('otherhead')
<style type="text/css" media="screen">
    @if($data->data->type != 2)
    .first{
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
        <form method="post" action="{{ URL::to('/transfers/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات الحوالة البنكية</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/transfers') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-transfer'))
                                <button type="submit" class="btn btn-round btn-success">حفظ <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                                <div class="col-xs-12">
                                    <div class="row" >
                                        <div class="col-xs-12 col-md-3">
                                            <div class="form-group">
                                                <label>النوع</label>
                                                <select name="type" class="form-control" required>
                                                    <option value="1" {{ $data->data->type == 1 ? 'selected' : '' }}>وارد</option>
                                                    <option value="2" {{ $data->data->type == 2 ? 'selected' : '' }}>صادر</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-3">
                                            <div class="form-group">
                                                <label>المندوب</label>
                                                <select name="delegate_id" class="form-control" required>
                                                    <option value="">اختر..</option>
                                                    @foreach($data->delegates as $delegate)
                                                    <option value="{{ $delegate->id }}" {{ $data->data->delegate_id == $delegate->id ? 'selected' : '' }}>{{ $delegate->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-3">
                                            <div class="form-group">
                                                <label>اسم الشركة</label>
                                                <input type="text" class="form-control" placeholder="اسم الشركة" name="company" value="{{ $data->data->company }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-3 first">
                                            <div class="form-group">
                                                <label>حساب الشركة</label>
                                                <input type="text" class="form-control" placeholder="حساب الشركة" name="company_account" value="{{ $data->data->company_account }}">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-xs-12 col-md-1">
                                            <div class="form-group">
                                                <label>مبلغ الحوالة</label>
                                                <input type="number" min="0" class="form-control" placeholder="مبلغ الحوالة" name="balance" value="{{ $data->data->balance }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-1">
                                            <div class="form-group">
                                                <label>العملة</label>
                                                <select class="form-control select2" name="currency_id">
                                                    <option value="0">اختر العملة</option>
                                                    @foreach($data->currencies as $currency)
                                                        <option value="{{ $currency->id }}" {{ $data->data->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <div class="form-group">
                                                <label>الحساب البنكي</label>
                                                <select name="bank_account_id" class="form-control" required>
                                                    <option value="">اختر..</option>
                                                    @foreach($data->accounts as $account)
                                                    <option value="{{ $account->id }}" {{ $data->data->bank_account_id == $account->id ? 'selected' : '' }}>{{ $account->account_number }} - {{ $account->name }} - {{ $account->shop_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-2 first">
                                            <div class="form-group">
                                                <label>التحويل (من) : (الي)</label>
                                                <select class="form-control" name="details_id"> 
                                                    <option value="">اختر عملية التحويل</option>
                                                    @foreach($data->currencies2 as $currency)
                                                    <option value="{{ $currency->id }}" data-area="{{ $currency->rate }}" data-area2="{{ $currency->to->name }}" {{ $data->data->details_id == $currency->id ? 'selected' : '' }}>{{ $currency->from->name }} الي {{ $currency->to->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-2 first">
                                            <div class="form-group">
                                                <label>سعر التحويل</label>
                                                <input type="text" name="price" readonly class="form-control" placeholder="سعر التحويل" value="{{ $data->data->rate }} {{ $data->data->new_currency }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-2 first">
                                            <div class="form-group">
                                                <label>العمولة (%)</label>
                                                <input type="text" name="commission_rate" class="form-control" placeholder="العمولة (%)" value="{{ $data->data->commission_rate }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-2 first">
                                            <div class="form-group">
                                                <label>المطلوب استلامه من العميل</label>
                                                <input type="text" name="paid" readonly class="form-control" placeholder="المطلوب تسليمه للعميل" value="{{ $data->data->total }} {{ $data->data->new_currency }}"> 
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
    <script src="{{ asset('assets/components/transfers.js')}}"></script>
@endsection