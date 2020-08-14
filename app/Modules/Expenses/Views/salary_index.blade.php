@extends('Layouts.master')
@section('title', 'اجور العمال')

@section('content')

    <div class="row">
        <form method="get" action="{{ URL::current() }}">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <strong>بحث بواسطة</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <button type="submit" class="btn btn-primary" style="width:110px;"><i class="fa fa-search"></i> بحث ..</button>
                                @if(Input::has('status') || Input::has('user_id') || Input::has('shop_id'))
                                    <a href="{{ URL::to('/salaries') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa-redo"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row"> 
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>الحالة</label>
                                    <select class="form-control select2" name="status">
                                        <option>اختر الحالة</option>
                                        <option value="1" {{ Input::get('status') == 1 ? 'selected' : '' }}>لم تتم التصفية</option>
                                        <option value="2" {{ Input::get('status') == 2 ? 'selected' : '' }}>تمت التصفية</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>الفرع</label>
                                    <select class="form-control select2 shop_id" name="shop_id">
                                        <option value="0">اختر الفرع</option>
                                        @foreach($data->shops as $shop)
                                            <option value="{{ $shop->id }}" {{ Input::get('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>العامل</label>
                                    <select class="form-control select2 user_id" name="user_id">
                                        <option value="0">اختر العامل</option>
                                        @foreach($data->users as $user)
                                            <option value="{{ $user->id }}" {{ Input::get('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3>اجور العمال<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="x_content x_content_table">
                    <table id="tableList" class="table hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-right">الفرع</th>
                            <th class="text-right">العامل</th>
                            <th class="text-right">الراتب</th>
                            <th class="text-right">التاريخ من</th>
                            <th class="text-right">التاريخ الي</th>
                            <th class="text-right">المبلغ المستلف</th>
                            <th class="text-right">المبلغ المتبقي</th>
                            <th class="text-right">تاريخ التصفية</th>
                            <th class="text-right">الحالة</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->shop_name }}</td>
                                <td>{{ $value->user_name }}</td>
                                <td>{{ $value->salary }}</td>
                                <td>{{ $value->start_date }}</td>
                                <td>{{ $value->end_date }}</td>
                                <td>{{ $value->took }}</td>
                                <td>{{ $value->rest }}</td>
                                <td>{{ $value->paid_date }}</td>
                                <td>{{ $value->status }}</td>
                                <td class="actions">
                                    @if(\Helper::checkRules('edit-salary') && $value->status_id == 1)
                                        <a href="{{ URL::to('/salaries/update/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-check"></i> تصفية </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="10">لا يوجد اجور حاليا</td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                            </tr>    
                        @endif
                        </tbody>
                    </table>
                </div>
                @include('Partials.pagination')
                <div class="clearfix"></div>
            </div>
        </div>

    </div>

@stop

@section('script')
    <script src="{{ asset('assets/components/salaries.js')}}"></script>
@stop()
