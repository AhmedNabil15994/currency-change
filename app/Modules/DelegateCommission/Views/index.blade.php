@extends('Layouts.master')
@section('title', 'عمولات المندوبين')

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
                                @if(Input::has('delegate_id') || Input::has('commission') || Input::has('valid_until'))
                                    <a href="{{ URL::to('/commissions') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa-redo"></i></a>
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
                                    <label>المندوب</label>
                                    <select class="form-control select2" name="delegate_id">
                                        <option value="0">اختر المندوب</option>
                                        @foreach($data->delegates as $delegate)
                                            <option value="{{ $delegate->id }}" {{ Input::get('delegate_id') == $delegate->id ? 'selected' : '' }}>{{ $delegate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>العمولة (%)</label>
                                    <input type="text" class="form-control" name="commission" placeholder="العمولة" value="{{ Input::get('commission') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>تاريخ الانتهاء</label>
                                    <input type="text" class="form-control datetimepicker" name="valid_until" placeholder="تاريخ الانتهاء" value="{{ Input::get('valid_until') }}">
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
                            <h3>عمولات المندوبين<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-commission'))
                                    <a href="{{URL::to('/commissions/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> عمولة جديدة</a>
                                @endif
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
                            <th class="text-right">المندوب</th>
                            <th class="text-right">العمولة (%)</th>
                            <th class="text-right">تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->delegate_name }}</td>
                                <td>{{ $value->commission }}</td>
                                <td>{{ $value->valid_until }}</td>
                                <td width="3%" align="left"><span class="btn {{ $value->is_active == 1 ? "btn-success" : "btn-danger" }} btn-xs"> {{ $value->active }}</span></td>
                                <td class="actions" width="15%" align="left">
                                    @if(\Helper::checkRules('edit-commission'))
                                        <a href="{{ URL::to('/commissions/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-alt"></i> تعديل </a>
                                    @endif

                                    @if(\Helper::checkRules('delete-commission'))
                                        <a onclick="deleteCommission('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> حذف </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="5">لا يوجد عمولات</td>
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
    <script src="{{ asset('assets/components/commissions.js')}}"></script>
@stop()
