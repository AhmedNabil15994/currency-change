@extends('Layouts.master')
@section('title', $data->data->id . ' - حوالة ' . $data->data->type_text)
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
                            <div class="col-md-6 col-xs-12">
                                    <div class="row" >
                                        <div class="col-xs-10 col-sm-5 col-md-10">
                                            <div class="form-group">
                                                <label>النوع</label>
                                                <select name="type" class="form-control" required>
                                                    <option value="">اختر..</option>
                                                    <option value="1" {{ $data->data->type == 1 ? 'selected' : '' }}>وارد</option>
                                                    <option value="2" {{ $data->data->type == 2 ? 'selected' : '' }}>صادر</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-sm-1 col-md-2">
                                            <div class="form-group">
                                                <label>مفعل</label>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" name="active" {{ $data->data->is_active == 1 ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>الرصيد</label>
                                                <input type="number" min="0" class="form-control" placeholder="الرصيد" name="balance" value="{{ $data->data->balance }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>الشركة</label>
                                                <input type="text" class="form-control" placeholder="الشركة" name="company" value="{{ $data->data->company }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="row" >
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <div class="form-group">
                                                <label>العميل</label>
                                                <select name="client_id" class="form-control" required>
                                                    <option value="">اختر..</option>
                                                    @foreach($data->clients as $client)
                                                    <option value="{{ $client->id }}" {{ $data->data->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-12">
                                            <div class="form-group">
                                                <label>الحساب البنكي</label>
                                                <select name="bank_account_id" class="form-control" required>
                                                    <option value="">اختر..</option>
                                                    @foreach($data->accounts as $account)
                                                    <option value="{{ $account->id }}" {{ $data->data->bank_account_id == $account->id ? 'selected' : '' }}>{{ $account->account_number }} - {{ $account->name }}</option>
                                                    @endforeach
                                                </select>
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