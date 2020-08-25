@extends('Layouts.master')
@section('title', 'تحويلات العملات')

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
                                @if(Input::has('from_id') || Input::has('to_id'))
                                    <a href="{{ URL::to('/details') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa-redo"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>العملة (من)</label>
                                        <select class="form-control" name="from_id"> 
                                            <option>اختر العملة ...</option>
                                            @foreach($data->currencies as $currency)
                                            <option value="{{ $currency->id }}" data-area="{{ $currency->code }}" {{ Input::has('from_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                            @endforeach 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>العملة (الي)</label>
                                    <select class="form-control" name="to_id"> 
                                        <option>اختر العملة ...</option>
                                        @foreach($data->currencies as $currency)
                                        <option value="{{ $currency->id }}" data-area="{{ $currency->code }}" {{ Input::has('to_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>الحالة</label>
                                    <select class="form-control" name="is_active">
                                        <option value="-1">اختر الحالة</option>
                                        <option value="0" {{ Input::has('is_active') && Input::get('is_active') == 0 ? 'selected': '' }}>مفعلة</option>
                                        <option value="1" {{ Input::get('is_active') == 1 ? 'selected': '' }}>غير مفعلة</option>
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
                            <h3>تحويلات العملات<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-details'))
                                    <a href="{{URL::to('/details/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> تحويلة جديدة</a>
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
                            <th class="text-right">العملة (من)</th>
                            <th class="text-right">العملة (الي)</th>
                            <th class="text-right">قيمة التحويل اليومية (العالمية)</th>
                            <th class="text-right">نوع العمولة</th>
                            <th class="text-right">قيمة العمولة</th>
                            <th class="text-right">قيمة التحويل اليومية (النظام)</th>
                            <th>الحالة</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->from->name }}</td>
                                <td>{{ $value->to->name }}</td>
                                <td>1 {{ $value->from->name }} == {{ $value->daily_value }} {{ $value->to->name }}</td>
                                <td>{{ $value->type_name }}</td>
                                <td>{{ $value->rate . ($value->type == 1 ? ' %':'') }}</td>
                                <td>{{ $value->convert }}</td>
                                <td width="3%" align="left"><span class="btn {{ $value->is_active == 1 ? "btn-success" : "btn-danger" }} btn-xs"> {{ $value->active }}</span></td>
                                <td class="actions" width="15%" align="left">
                                    @if(\Helper::checkRules('edit-details'))
                                        <a href="{{ URL::to('/details/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-alt"></i> تعديل </a>
                                    @endif

                                    @if(\Helper::checkRules('delete-details'))
                                        <a onclick="deleteDetails('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> حذف </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="7">لا يوجد عملاء</td>
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
    <script src="{{ asset('assets/components/details.js')}}"></script>
@stop()
