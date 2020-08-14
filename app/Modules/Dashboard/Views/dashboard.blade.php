@extends('Layouts.master')
@section('title', 'الرئيسية')
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/dashboard.css') }}">
@endsection
@section('content')
<div class="">
    <div class="row top_tiles">
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fas fa-shopping-bag"></i></div>
          <div class="count"> {{ $data->sold_products }}</div>
          <h3>المنتجات المباعة</h3>
          <p></p>
        </div>
      </div>

      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fas fa-file-invoice"></i></div>
          <div class="count"> {{ $data->salesInvoices }}</div>
          <h3>فواتير المبيعات</h3>
          <p></p>
        </div>
      </div>

      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fas fa-money-check"></i></div>
          <div class="count"> {{ $data->income }}</div>
          <h3>الدخل اليومي</h3>
          <p></p>
        </div>
      </div>

      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fas fa-money-bill-alt"></i></div>
          <div class="count"> {{ $data->expense }}</div>
          <h3>المصروفات اليومية</h3>
          <p></p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <div class="x_panel">
          <div class="x_title">
          	@if(\Session::get('group_id') != 2)
            <h2>حجم المبيعات <small>المبيعات الاسبوعية</small></h2>
            @else
            <h2>عدد المنتجات المباعة <small>المبيعات الاسبوعية</small></h2>
            @endif
            <div class="filter">
              <div id="reportrange20" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div style="text-align: center; overflow: hidden; margin: 10px 5px 3px;">
                  <canvas id="canvas_line" height="100"></canvas>
                </div>
          		@if(\Session::get('group_id') != 2)
	              <div class="tiles">
	                <div class="col-md-4 col-xs-12 tile">
	                  <span>اجمالي المبيعات</span>
	                  <h3>{{ $data->totalIncome }}</h3>
	                  <span class="sparkline11 graph" style="height: 160px;">
	                       <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
	                  </span>
	                </div>
	                <div class="col-md-4 col-xs-12 tile">
	                  <span>اجمالي المصروفات</span>
	                  <h3>{{ $data->totalExpense }}</h3>
	                  <span class="sparkline22 graph" style="height: 160px;">
	                        <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
	                  </span>
	                </div>
	                <div class="col-md-4 col-xs-12 tile">
	                  <span>صافي الخزنة</span>
	                  <h3> {{ $data->totalSafe }}</h3>
	                  <span class="sparkline22 graph" style="height: 160px;">
	                        <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
	                  </span>
	                </div>
	              </div>
          		@endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="x_panel">
          <div class="x_title">
            <h2>المنتجات الاكثر مبيعا</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li class="pull-left"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          	@foreach($data->most_sold_products as $product)
            <article class="media event">
              <a class="pull-right date" href="#">
                <img src="{{ $product->product_image }}" alt="">
              </a>
              <div class="media-body">
                <a class="title" href="#">{{ $product->product_code.' - '.$product->title }}</a>
                <p>سعر البيع: {{ $product->sell_price }}</p>
                <p>{{ $product->category_name }}</p>
                @if(\Session::get('group_id') != 2)
                <p>{{ $product->supplier_name }}</p>
                <p>عدد المباع: {{ $product->product_count }}</p>
                @endif
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </div>

      @if(\Session::get('group_id') != 2)
      <div class="col-md-4">
        <div class="x_panel">
          <div class="x_title">
            <h2>الفروع الاكثر مبيعا</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li  class="pull-left"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          	@foreach($data->most_sold_shop_products as $shopProduct)
            <article class="media event">
              <a class="pull-right date" href="#">
                <img src="{{ $shopProduct->shop_image }}" alt="">
              </a>
              <div class="media-body">
                <a class="title" href="#">{{ $shopProduct->shop_name }}</a>
                <p>عدد فواتير المبيعات: {{ $shopProduct->invoice_count }}</p>
                <p>عدد المنتجات المباعة: {{ $shopProduct->product_count }}</p>
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </div>
      @endif

      <div class="col-md-4">
        <div class="x_panel">
          <div class="x_title">
            <h2>العمال الاكثر مبيعا</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li  class="pull-left"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          	@foreach($data->most_sold_workers as $worker)
            <article class="media event">
              <a class="pull-right date" href="#">
                <img src="{{ $worker->user_image }}" alt="">
              </a>
              <div class="media-body">
                <a class="title" href="#">{{ $worker->user_name }}</a>
                <p>الفرع: {{ $worker->shop_name }}</p>
                <p>عدد فواتير المبيعات: {{ $worker->invoice_count }}</p>
                <p>عدد المنتجات المباعة: {{ $worker->product_count }}</p>
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </div>

      @if(\Session::get('group_id') == 2)
      <div class="col-md-4">
        <div class="x_panel">
          <div class="x_title">
            <h2>اخر السلفات</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li  class="pull-left"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          	@foreach($data->most_last_expenses as $oneWorker)
            <article class="media event">
              <a class="pull-right date" href="#">
                <img src="{{ $oneWorker->user_image }}" alt="">
              </a>
              <div class="media-body">
                <a class="title" href="#">{{ $oneWorker->user_name }}</a>
                <p>السلفة: {{ $oneWorker->total }}</p>
                <p>التاريخ: {{ $oneWorker->created_at }}</p>
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </div>
      @endif

    </div>

</div>
<div class="dropup add-more">
  <button class="dropdown-toggle" type="button" data-toggle="dropdown">
    <i class="fa fa-plus"></i>
  </button>
  <ul class="dropdown-menu">
    <li><a href="{{ URL::to('/sales-invoices/add') }}">فاتورة مبيعات جديدة</a></li>
    <li><a href="{{ URL::to('/expenses/add') }}">مصروف جديد</a></li>
    @if(\Session::get('group_id') == 1)
    <li><a href="{{ URL::to('/supplies/add') }}">فاتورة توريد جديدة</a></li>
  	@endif
  </ul>
</div>
@stop()

@section('script')
<script type="text/javascript">
 
    $(function(){
          
        var myLabels = [];
        var myValues = [];
        var myLabel = '';
        @foreach($data->chartData[0] as $chartKey => $one)
         	myLabels[{{ $chartKey }}] = "{{ $one }}";
        @endforeach
        @foreach($data->chartData[1] as $chartKeys => $ones)
         	myValues[{{ $chartKeys }}] = "{{ $ones }}";
        @endforeach

        @if(\Session::get('group_id') != 2)
        myLabel = "اجمالي المبيعات ";
        @else
        myLabel = "عدد المنتجات المباعة ";
        @endif

        function init_chart_plot(myLabels,myValues){             
            if ($('#canvas_line').length ){
				
				var canvas_line_00 = new Chart(document.getElementById("canvas_line"), {
				  type: 'line',
				  data: {
					labels: myLabels,
					datasets: [{
					  label: myLabel,
					  backgroundColor: "rgba(38, 185, 154, 0.31)",
					  borderColor: "rgba(38, 185, 154, 0.7)",
					  pointBorderColor: "rgba(38, 185, 154, 0.7)",
					  pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
					  pointHoverBackgroundColor: "#fff",
					  pointHoverBorderColor: "rgba(220,220,220,1)",
					  pointBorderWidth: 1,
					  data: myValues
					}]
				}
				});
				
			}
        }

        function init_date_rangepicker() {

            if( typeof ($.fn.daterangepicker) === 'undefined'){ return; }
            // console.log('init_daterangepicker');
          
            var cb = function(start, end, label) {
              // console.log(start.toISOString(), end.toISOString(), label);
              $('#reportrange20 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            };

            var optionSet1 = {
              startDate: moment().subtract(29, 'days'),
              endDate: moment(),
              minDate: '01/01/2019',
              maxDate: '12/31/2030',
              dateLimit: {
              days: 60
              },
              showDropdowns: true,
              showWeekNumbers: true,
              timePicker: false,
              timePickerIncrement: 1,
              timePicker12Hour: true,
              ranges: {
              'اليوم': [moment(), moment()],
              'امس': [moment().subtract(1, 'days'), moment()],
              'منذ 7 ايام': [moment().subtract(6, 'days'), moment()],
              'منذ 30 يوم': [moment().subtract(29, 'days'), moment()],
              'هذا الشهر': [moment().startOf('month'), moment().endOf('month')],
              'الشهر الماضي': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              opens: 'right',
              buttonClasses: ['btn btn-default'],
              applyClass: 'btn-small btn-primary',
              cancelClass: 'btn-small',
              format: 'MM/DD/YYYY',
              separator: ' to ',
              locale: {
              applyLabel: 'اختيار',
              cancelLabel: 'الغاء',
              fromLabel: 'من',
              toLabel: 'الي',
              customRangeLabel: 'فترة محددة',
              daysOfWeek: ['الاحد', 'الاثنين', 'الثلاثاء', 'الاربعاء', 'الخميس', 'الجمعة', 'السبت'],
              monthNames: ['يناير', 'فبراير', 'مارس', 'ابريل', 'مايو', 'يونيو', 'يوليو', 'اغسطس', 'سبتمبر', 'اكتوبر', 'نوفمبر', 'ديسمبر'],
              firstDay: 1
              }
            };
            
            $('#reportrange20 span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            $('#reportrange20').daterangepicker(optionSet1, cb);
            $('#options1').click(function() {
              $('#reportrange20').data('daterangepicker').setOptions(optionSet1, cb);
            });
            $('#options2').click(function() {
              $('#reportrange20').data('daterangepicker').setOptions(optionSet2, cb);
            });
            $('#destroy').click(function() {
              $('#reportrange20').data('daterangepicker').remove();
            });

            $('#reportrange20').on('apply.daterangepicker', function(ev, picker) {
              var from = picker.startDate.format('YYYY-MM-DD');
              var to = picker.endDate.format('YYYY-MM-DD');
              $formData = new FormData();
              $formData.append('from', from);
              $formData.append('to', to);
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '/getChartData',
                  type: 'POST',
                  data: $formData ,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  success: function (data) {
                    init_chart_plot(data[0],data[1]);
                  },        
              });



            });
         
        }

        init_chart_plot(myLabels,myValues);
        init_date_rangepicker();


    });
</script>
@stop()

