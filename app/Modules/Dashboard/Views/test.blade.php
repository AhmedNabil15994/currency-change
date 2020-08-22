@extends('Layouts.master')
@section('title', 'الرئيسية')
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/dashboard.css') }}">
@endsection
@section('content')

@stop()

@section('script')
<script src="{{ URL::to('/assets/components/money.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
 
    $(function(){
        var myData;
        $.getJSON(
          // NB: using Open Exchange Rates here, but you can use any source!
            'https://openexchangerates.org/api/latest.json?base=USD&app_id=a2332f4c3a93491897a11206fb3abf0c',
            function(data) {
                console.log(data);
                myData = data;
                // Check money.js has finished loading:
                // if ( typeof fx !== "undefined" && fx.rates ) {
                //     fx.rates = data.rates;
                //     fx.base = data.base;
                // } else {
                //     // If not, apply to fxSetup global:
                //     var fxSetup = {
                //         rates : data.rates,
                //         base : data.base
                //     }
                // }
            }
        );
    });
</script>
@stop()

