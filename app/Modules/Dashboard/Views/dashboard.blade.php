@extends('Layouts.master')
@section('title', 'الرئيسية')
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/dashboard.css') }}">
@endsection
@section('content')
<div class="">
    <div class="row top_tiles">
      <h3>حساب الصناديق</h3>
      @foreach($data->shops as $oneShop)
      @if(isset($data->balances[$oneShop->id]) && !empty($data->balances[$oneShop->id]))
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fas fa-dollar-sign"></i></div>
          <div class="count">
            <h3 style="margin-bottom: 10px;margin-top: 10px;font-size: 20px;">حساب صندوق {{ $oneShop->title }}</h3>
            @php $shopDetails = $data->balances[$oneShop->id]; @endphp 
            @for($c=0;$c<count($shopDetails);$c++)
            @if($shopDetails[$c] != 0)
            <b style="font-size: 16px;display: block;">{{ $shopDetails[$c] }} {{ $data->currencies[$c] }}</b>
            @endif
            @endfor  
          </div>
          <h3 style="font-size: 20px;margin-bottom: 10px;margin-top: 10px;">الرصيد المتاح</h3>
          <p></p>
        </div>
      </div>
      @endif
      @endforeach
    </div>

    <div class="row top_tiles">
      <h3>الحسابات البنكية</h3>
      @foreach($data->bankAccounts as $oneBank)
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fas fa-dollar-sign"></i></div>
          <div class="count">
            <h3 style="margin-bottom: 10px;margin-top: 10px;font-size: 20px;">{{ $oneBank->account_number }} - {{ $oneBank->name }}</h3>
            <b style="font-size: 16px;display: block;">{{ $oneBank->myTotal }} {{ $oneBank->currency_name }}</b>
          </div>
          <h3 style="font-size: 20px;margin-bottom: 10px;margin-top: 10px;">الرصيد المتاح</h3>
          <p></p>
        </div>
      </div>
      @endforeach
    </div>
</div>
@stop()
