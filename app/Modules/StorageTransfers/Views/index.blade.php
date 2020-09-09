@extends('Layouts.master')
@section('title', 'التحويلات من الصناديق')

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
                                @if(Input::has('storage_id') || Input::has('type') || Input::has('currency_id') || Input::has('to_id'))
                                    <a href="{{ URL::to('/storage-transfers') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa-redo"></i></a>
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
                                    <label>الصندوق المحول منه</label>
                                    <select class="form-control select2" name="storage_id">
                                        <option value="0">اختر الصندوق</option>
                                        @foreach($data->storages as $storage)
                                            <option value="{{ $storage->id }}" {{ Input::get('storage_id') == $storage->id ? 'selected' : '' }}>صندوق {{ $storage->shop_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>نوع التحويل</label>
                                    <select class="form-control select2" name="type">
                                        <option value="1">التحويل الي صندوق</option>
                                        <option value="2">التحويل الي حساب بنكي</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>المحول اليه</label>
                                    <select class="form-control select2" name="to_id">
                                        <option value="">اختر الصندوق</option>
                                        @foreach($data->storages as $storage)
                                            <option value="{{ $storage->id }}" {{ Input::get('to_id') == $storage->id ? 'selected' : '' }}>صندوق {{ $storage->shop_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>العملة</label>
                                    <select class="form-control select2" name="currency_id">
                                        <option value="0">اختر العملة</option>
                                        @foreach($data->currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ Input::get('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
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
                            <h3>التحويلات من الصناديق<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-storage-transfer'))
                                    <a href="{{URL::to('/storage-transfers/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> تحويل جديد</a>
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
                            <th class="text-right">الصندوق</th>
                            <th class="text-right">نوع التحويل</th>
                            <th class="text-right">المحول اليه</th>
                            <th class="text-right">الرصيد المحول</th>
                            <th class="text-right">العملة</th>
                            <th class="text-right">التاريخ</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->storage_name }}</td>
                                <td>{{ $value->type_text }}</td>
                                <td>{{ $value->to_text }}</td>
                                <td>{{ $value->total }}</td>
                                <td>{{ $value->currency_name }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td class="actions" width="15%" align="left">
                                    @if(\Helper::checkRules('edit-storage-transfer'))
                                        <a href="{{ URL::to('/storage-transfers/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-alt"></i> تعديل </a>
                                    @endif

                                    @if(\Helper::checkRules('delete-storage-transfer'))
                                        <a onclick="deleteStorageTransfer('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> حذف </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="7">لا يوجد حسابات بنكية</td>
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
    <script src="{{ asset('assets/components/storage-transfers.js')}}"></script>
@stop()
