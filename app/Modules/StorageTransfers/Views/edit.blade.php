@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->storage_name)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/storage-transfers/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات الحساب البنكي</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/storage-transfers') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-storage-transfer'))
                                <button type="submit" class="btn btn-round btn-success">حفظ <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>الصندوق المحول منه</label>
                                    <select name="storage_id" class="form-control" required>
                                        <option value="">اختر..</option>
                                        @foreach($data->storages as $shopKey => $shopValue)
                                            <option value="{{ $shopValue->id }}" {{ $shopValue->id == $data->data->storage_id ? "selected=selected" : ''  }}>صندوق {{ $shopValue->shop_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-2">
                                <div class="form-group">
                                    <label>نوع التحويل</label>
                                    <select name="type" class="form-control" required>
                                        <option value="1" {{ $data->data->type == 1 ? 'selected':'' }}>التحويل الي صندوق</option>
                                        <option value="2" {{ $data->data->type == 2 ? 'selected':'' }}>التحويل الي حساب بنكي</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>المحول اليه</label>
                                    <select class="form-control select2" name="to_id">
                                        <option value="">اختر الصندوق</option>
                                        @foreach($data->storages as $storage)
                                            <option value="{{ $storage->id }}" {{ $data->data->to_shop_id == $storage->shop_id ? 'selected' : '' }}>صندوق {{ $storage->shop_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-2">
                                <div class="form-group">
                                    <label>الرصيد</label>
                                    <input type="number" min="0" class="form-control" placeholder="الرصيد" name="total" value="{{ $data->data->total }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-2">
                                <div class="form-group">
                                    <label>العملة</label>
                                    <select name="currency_id" class="form-control" required>
                                        <option value="">اختر..</option>
                                        @foreach($data->currencies as $currencyKey => $currencyValue)
                                            <option value="{{ $currencyValue->id }}" {{ $currencyValue->id == $data->data->currency_id ? "selected=selected" : ''  }}>{{ $currencyValue->name }}</option>
                                        @endforeach
                                    </select>
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
    <script src="{{ asset('assets/components/storage-transfers.js')}}"></script>
@stop()
