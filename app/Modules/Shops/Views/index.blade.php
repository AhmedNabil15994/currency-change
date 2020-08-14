@extends('Layouts.master')
@section('title', 'الافرع')
@section('content')
<div class="row">
        <form method="get" action="{{ URL::current() }}">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <strong>بحث بواسطة</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <button type="submit" class="btn btn-primary" style="width:110px;"><i class="fa fa fa-search"></i> بحث ..</button>
                                @if(Input::has('title') || Input::has('phone') || Input::has('address'))
                                    <a href="{{ URL::to('/shops') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-redo"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-6 col-md-4">
                                    <div class="form-group">
                                        <label>الاسم</label>
                                        <input type="text" class="form-control" name="title" placeholder="الاسم" value="{{ Input::get('title') }}">
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
                                        <label>العنوان</label>
                                        <input type="text" class="form-control" name="address" placeholder="العنوان" value="{{ Input::get('address') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3>الافرع<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-shop'))
                                    <a href="{{URL::to('/shops/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> فرع جديد</a>
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
                            <th width="3%">ID</th>
                            <th>الفرع</th>
                            <th>رقم التليفون</th>
                            <th>العنوان</th>
                            <th width="20%">العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td>{{ $value->id }}</td>
                                <td> <img src="{{ $value->image }}"> {{ $value->title }}</td>
                                <td>{{ $value->phone }}</td>
                                <td>{{ $value->address }}</td>
                                <td>
                                    @if(\Helper::checkRules('edit-shop'))
                                        <a href="{{ URL::to('/shops/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-alt"></i> تعديل </a>
                                    @endif
                                    @if(\Helper::checkRules('delete-shop'))
                                        <a onclick="deleteShop('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> حذف </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="4">لا توجد بيانات</td>
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
@stop()

@section('script')
    <script src="{{ asset('assets/components/shop.js')}}"></script>
@stop()
