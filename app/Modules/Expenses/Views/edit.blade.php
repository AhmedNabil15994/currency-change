@extends('Layouts.master')
@section('title', $data->data->id . ' - مصروف')
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/expenses/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات المصروف</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/expenses') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-expense'))
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
                                    <div class="col-xs-6 col-md-4">
                                        <div class="form-group">
                                            <label>النوع</label>
                                            <select class="form-control select2" name="type">
                                                <option value="1" {{ $data->data->type == 1 ? 'selected' : '' }}>مصروف</option>
                                                <option value="2" {{ $data->data->type == 2 ? 'selected' : '' }}>سلفة لعامل</option>
                                                <option value="3" {{ $data->data->type == 3 ? 'selected' : '' }}>مصاريف انتقالات</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-4">
                                        <div class="form-group">
                                            <label>الفرع</label>
                                            <select class="form-control select2" name="shop_id">
                                                <option value="0">اختر الفرع</option>
                                                @foreach($data->shops as $shop)
                                                    <option value="{{ $shop->id }}" {{ $data->data->shop_id == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-4">
                                        <div class="form-group">
                                            <label>الموظف</label>
                                            <select class="form-control select2" name="user_id" disabled>
                                                <option value="0">اختر الموظف</option>
                                                @foreach($data->users as $user)
                                                    <option value="{{ $user->id }}" {{ $data->data->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-4">
                                        <div class="form-group">
                                            <label>المبلغ</label>
                                            <input type="number" min="1" class="form-control" name="total" placeholder="المبلغ" value="{{ $data->data->total }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-4">
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
                                    <div class="col-xs-6 col-md-4">
                                        <div class="form-group">
                                            <label>التاريخ</label>
                                            <input type="text" class="form-control datetimepicker" name="created_at" placeholder="التاريخ" value="{{ date('Y-m-d',strtotime($data->data->created_at)) }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label>الوصف</label>
                                            <textarea class="form-control" name="description" placeholder="الوصف">{{ $data->data->description }}</textarea>
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
    <script src="{{ asset('assets/components/expenses.js')}}"></script>
@stop()
