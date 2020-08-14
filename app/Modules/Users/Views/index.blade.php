@extends('Layouts.master')
@section('title', 'المستخدمين')

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
                                @if(Input::has('name') || Input::has('email') || Input::has('phone') || Input::has('group_id') ||  Input::has('shop_id') || Input::has('course_id'))
                                    <a href="{{ URL::to('/users') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa-redo"></i></a>
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
                                    <label>الاسم</label>
                                    <input type="text" class="form-control" name="name" placeholder="الاسم" value="{{ Input::get('name') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>البريد الالكتروني</label>
                                    <input type="email" class="form-control" name="email" placeholder="البريد الالكتروني" value="{{ Input::get('email') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>رقم التليفون</label>
                                    <input type="text" class="form-control" name="phone" placeholder="رقم التليفون" value="{{ Input::get('phone') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
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
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>المجموعة</label>
                                    <select class="form-control select2" name="group_id">
                                        <option value="0">اختر المجموعة</option>
                                        @foreach($data->groups as $group)
                                            <option value="{{ $group->id }}" {{ Input::get('group_id') == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>تاريخ بداية العمل</label>
                                    <input type="text" class="form-control datetimepicker" name="start_date" placeholder="تاريخ بداية العمل" value="{{ Input::get('start_date') }}">
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
                            <h3>المستخدمين<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-user'))
                                    <a href="{{URL::to('/users/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> مستخدم جديد</a>
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
                            <th class="text-right">الاسم</th>
                            <th class="text-right">البريد الالكتروني</th>
                            <th class="text-right">رقم التليفون</th>
                            <th class="text-right">المجموعة</th>
                            <th class="text-right">الفرع</th>
                            <th class="text-right">تاريخ بداية العمل</th>
                            <th>الحالة</th>
                            <th style="padding-left: 50px">العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->email }}</td>
                                <td>{{ $value->phone }}</td>
                                <td>{{ $value->group }}</td>
                                <td>{{ $value->shop }}</td>
                                <td>{{ $value->start_date }}</td>
                                <td width="3%" align="left"><span class="btn {{ $value->is_active == 1 ? "btn-success" : "btn-danger" }} btn-xs"> {{ $value->active }}</span></td>
                                <td class="actions" width="15%" align="left">
                                    @if(\Helper::checkRules('edit-user'))
                                        <a href="{{ URL::to('/users/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-alt"></i> تعديل </a>
                                    @endif

                                    @if(\Helper::checkRules('delete-user') && $value->group_id != 1)
                                        <a onclick="deleteUser('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> حذف </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="7">لا يوجد مستخدمين</td>
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
    <script src="{{ asset('assets/components/users.js')}}"></script>
@stop()
