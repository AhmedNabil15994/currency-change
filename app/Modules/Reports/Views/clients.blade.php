@extends('Layouts.master')
@section('title', 'تقارير العملاء')

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
                                @if(Input::has('from_id') || Input::has('to_id') || Input::has('type') || Input::has('client_id') ||  Input::has('shop_id') ||  Input::has('from') ||  Input::has('to'))
                                    <a href="{{ URL::to('/wallets') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa-redo"></i></a>
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
                                    <label>الفرع</label>
                                    <select class="form-control" name="shop_id"> 
                                        <option value="0">اختر الفرع ...</option>
                                        @foreach($data->shops as $shop)
                                        <option value="{{ $shop->id }}" {{ Input::get('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->title }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>العميل</label>
                                    <select class="form-control" name="client_id"> 
                                        <option value="0">اختر العميل ...</option>
                                        @foreach($data->clients as $client)
                                        <option value="{{ $client->id }}" {{ Input::get('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>العملة (من)</label>
                                        <select class="form-control" name="from_id"> 
                                            <option value="0">اختر العملة ...</option>
                                            @foreach($data->currencies as $currency)
                                            <option value="{{ $currency->id }}" data-area="{{ $currency->code }}" {{ Input::get('from_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                            @endforeach 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                <div class="form-group">
                                    <label>العملة (الي)</label>
                                    <select class="form-control" name="to_id"> 
                                        <option value="0">اختر العملة ...</option>
                                        @foreach($data->currencies as $currency)
                                        <option value="{{ $currency->id }}" data-area="{{ $currency->code }}" {{ Input::get('to_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3">
                                    <div class="form-group">
                                        <label>التاريخ (من)</label>
                                        <input type="text" class="form-control datetimepicker" name="from" placeholder="التاريخ (من)" value="{{ Input::get('from') }}">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-3">
                                    <div class="form-group">
                                        <label>التاريخ (الي)</label>
                                        <input type="text" class="form-control datetimepicker" name="to" placeholder="التاريخ (الي)" value="{{ Input::get('to') }}">
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
                            <h3>تقارير العملاء<small> العدد الكلي : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                <a href="#" class="btn btn-default print" style="color: black;"><i class="fa fa-print"></i> طباعة</a>
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="x_content x_content_table">
                    <table id="example" class="table hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-right">الفرع</th>
                            <th class="text-right">العميل</th>
                            <th class="text-right">نوع العملية</th>
                            <th class="text-right">العملة (من)</th>
                            <th class="text-right">العملة (الي)</th>
                            <th class="text-right">سعر التحويل</th>
                            <th class="text-right">الكمية</th>
                            <th class="text-right">نوع العمولة</th>
                            <th class="text-right">قيمة العمولة</th>
                            <th class="text-right">سعر البنك</th>
                            <th class="text-right">الرصيد المتبقي</th>
                            <th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->shop_name }}</td>
                                <td>{{ $value->client_name }}</td>
                                <td>{{ $value->type_text . ' ( '. $value->type_report_text . ' )' }}</td>
                                <td>{{ $value->from->name }}</td>
                                <td>{{ $value->to->name }}</td>
                                <td>1 {{ $value->from->name }} == {{ $value->convert_price }} {{ $value->to->name }}</td>
                                <td>{{ $value->amount }} {{ $value->from->name }}</td>
                                <td>{{ $value->commission_type_text . ' ( ' . $value->commission_value . ' )'  }}</td>
                                <td>{{ $value->commission . ' ' . $value->from->name }}</td>
                                <td>{{ $value->bank_convert_price }}</td>
                                <td>{{ $value->remain_balance . ' ' . $value->to->name }}</td>
                                <td>{{ $value->created_at }}</td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="12">لا يوجد عمليات</td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
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
    <script src="{{ asset('assets/components/clients-reports.js')}}"></script>
@stop()
