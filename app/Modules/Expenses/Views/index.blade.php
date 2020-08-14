@extends('Layouts.master')
@section('title', 'المصروفات')

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
                                @if(Input::has('type') || Input::has('user_id') || Input::has('created_at'))
                                    <a href="{{ URL::to('/expenses') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa-redo"></i></a>
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
                                    <label>النوع</label>
                                    <select class="form-control select2" name="type">
                                        <option value="0" {{ Input::get('type') == 0 ? 'selected' : '' }}>اختر النوع</option>
                                        <option value="1" {{ Input::get('type') == 1 ? 'selected' : '' }}>مصروف</option>
                                        <option value="2" {{ Input::get('type') == 2 ? 'selected' : '' }}>سلفة لعامل</option>
                                        <option value="3" {{ Input::get('type') == 3 ? 'selected' : '' }}>مصاريف انتقالات</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>الفرع</label>
                                    <select class="form-control select2" name="shop_id">
                                        <option value="0">اختر الفرع</option>
                                        @foreach($data->shops as $shop)
                                            <option value="{{ $shop->id }}" {{ Input::get('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>الموظف</label>
                                    <select class="form-control select2" name="user_id">
                                        <option value="0">اختر الموظف</option>
                                        @foreach($data->users as $user)
                                            <option value="{{ $user->id }}" {{ Input::get('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(\Session::get('group_id') != 2)
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>التاريخ</label>
                                    <input type="text" class="form-control datetimepicker" name="created_at" placeholder="التاريخ" value="{{ Input::get('created_at') }}">
                                </div>
                            </div>
                            @endif
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
                            <h3>المصروفات<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-expense'))
                                    <a href="{{URL::to('/expenses/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> مصروف جديد</a>
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
                            <th class="text-right">النوع</th>
                            <th class="text-right">الفرع</th>
                            <th class="text-right">العامل</th>
                            <th class="text-right">المبلغ</th>
                            <th class="text-right">الوصف</th>
                            <th>التاريخ</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->type_name }}</td>
                                <td>{{ $value->shop_name }}</td>
                                <td>{{ $value->user_name }}</td>
                                <td>{{ $value->total.' '.$value->currency_name }}</td>
                                <td>{{ $value->description }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td class="actions" width="15%" align="left">
                                    @if(\Helper::checkRules('edit-expense'))
                                        <a href="{{ URL::to('/expenses/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-alt"></i> تعديل </a>
                                    @endif

                                    @if(\Helper::checkRules('delete-expense'))
                                        <a onclick="deleteExpense('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> حذف </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="7">لا يوجد مصروفات</td>
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
    <script src="{{ asset('assets/components/expenses.js')}}"></script>
@stop()
