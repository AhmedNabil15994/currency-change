@extends('Layouts.master')
@section('title', 'تقارير الصندوق')
@section('otherhead')
<style type="text/css" media="screen">
    .select2-container{
        width: 100% !important;
        margin-bottom: 15px !important;
    }
    textarea{
        margin-bottom: 15px;
    }
</style>
@endsection
@section('content')
<div class="row">
        <form method="get" action="{{ URL::current() }}">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <strong>بحث بواسطة</strong>
                        <ul class="nav navbar-left panel_toolbox">
                            <div align="right">
                                <button type="submit" class="btn btn-primary" style="width:110px;"><i class="fa fa fa-search"></i> بحث ..</button>
                                @if(Input::has('shop_id') || Input::has('from') || Input::has('to'))
                                    <a href="{{ URL::to('/reports/storages') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-redo"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-12">
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
                                <div class="clearfix"></div>
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
                            <h3>تقارير الصندوق<small> العدد الكلي : {{ $data->data_count }}</small></h3>
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
                    <div class="panel-body">
                        <table id="tableList" class="table hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>التاريخ</th>
                                    @foreach($data->shops as $shop)
                                    <th>صادر {{ $shop->title }}</th>
                                    <th>وارد {{ $shop->title }}</th>
                                    @endforeach                                
                                </tr>
                            </thead>
                            <tbody>
                                @php $v = 1; @endphp
                                @foreach($data->data as $key => $value)
                                <tr>
                                    <td>{{ $v++ }}</td>
                                    <td><b>{{ $key }}</b></td>
                                    @foreach($data->shops as $oneShop)
                                    @if(isset($value[$oneShop->id]))
                                    @php $shopDetails = $value[$oneShop->id]; @endphp 
                                    <td>
                                        @for($i=0;$i<count($shopDetails[0]);$i++)
                                        @if($shopDetails[0][$i] > 0)
                                        <b>{{ $shopDetails[0][$i] }} {{ $data->currencies[$i] }} <br></b>
                                        @endif
                                        @endfor                                        
                                    </td>
                                    <td>
                                        @for($x=0;$x<count($shopDetails[1]);$x++)
                                        @if($shopDetails[1][$x] > 0)
                                        <b>{{ $shopDetails[1][$x] }} {{ $data->currencies[$x] }} <br></b>
                                        @endif
                                        @endfor                                        
                                    </td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    @endif
                                    @endforeach
                                </tr>
                                @endforeach
                                <tr>
                                    <td>{{ $v++ }}</td>
                                    <td><b>الاجمالي</b></td>
                                    @foreach($data->shops as $oneShop)
                                    @if(isset($data->totals[$oneShop->id]))
                                    @php $shopDetails = $data->totals[$oneShop->id]; @endphp 
                                    <td>
                                        @for($a=0;$a<count($shopDetails[0]);$a++)
                                        @if($shopDetails[0][$a] > 0)
                                        <b>{{ $shopDetails[0][$a] }} {{ $data->currencies[$a] }} <br></b>
                                        @endif
                                        @endfor                                        
                                    </td>
                                    <td>
                                        @for($b=0;$b<count($shopDetails[1]);$b++)
                                        @if($shopDetails[1][$b] > 0)
                                        <b>{{ $shopDetails[1][$b] }} {{ $data->currencies[$b] }} <br></b>
                                        @endif
                                        @endfor                                        
                                    </td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>{{ $v++ }}</td>
                                    <td><b>الرصيد</b></td>
                                    @foreach($data->shops as $oneShop)
                                    @if(isset($data->balances[$oneShop->id]))
                                    @php $shopDetails = $data->balances[$oneShop->id]; @endphp 
                                    <td colspan="2">
                                        @for($c=0;$c<count($shopDetails);$c++)
                                        @if($shopDetails[$c] != 0)
                                        <b>{{ $shopDetails[$c] }} {{ $data->currencies[$c] }} <br></b>
                                        @endif
                                        @endfor                                        
                                    </td>
                                    <td style="display: none;"></td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>{{ $v++ }}</td>
                                    <td colspan="{{ $data->shops_count / 3 }}"><b>الرصيد النهائي للفروع</b></td>
                                    <td colspan="{{ 1 + $data->shops_count  }}">
                                        @for($d=0;$d<count($shopDetails);$d++)
                                        @if($shopDetails[$d] != 0)
                                        <b>{{ $shopDetails[$d] }} {{ $data->currencies[$d] }} <br></b>
                                        @endif
                                        @endfor                                        
                                    </td>
                                    @foreach($data->shops as $shop)
                                    <td style="display: none;"></td>
                                    <td style="display: none;"></td>
                                    @endforeach 
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @include('Partials.pagination')
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
@stop()