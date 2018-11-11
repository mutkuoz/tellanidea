,@extends('layouts.app')

@section('scripts')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

@endsection
@php
    $raoak=0.05;
    $cashLoanVolume=1000000;
    $nonCashLoanVolume=200000;
    $cashRwa=750000;
    $nonCashRwa=100000;
    $rwa=$cashRwa+$nonCashRwa;
    $revenues=$raoak*$rwa;
    $cshRwaToLoans=$cashRwa/$cashLoanVolume;
    $nonCashRwaToLoans=$nonCashRwa/$nonCashLoanVolume;
    $rwaToLoans=($cashRwa+$nonCashRwa)/($cashLoanVolume+$nonCashLoanVolume);
    $loanVolume=$cashLoanVolume+$nonCashLoanVolume;
    $cashSow=0.20;
    $nonCashSow=0.25;
    $cashMarketLoanVolume=$cashLoanVolume/$cashSow;
    $nonCashMarketLoanVolume=$nonCashLoanVolume/$nonCashSow;
    $marketLoanVolume=$cashMarketLoanVolume+$nonCashMarketLoanVolume;
    $sow=$loanVolume/$marketLoanVolume;
@endphp
@section('content')
    <div class="mx-5">
        <div class="my-3">
            <div>RAOAK: <span id="txtRaoakValue"></span></div>
            <div id="raoakSlider" class="slider" data-format="0.0" data-textvalue="txtRaoakValue" data-step="0.05" data-affects="revenuesSlider" data-affectformula="([value]/100.0)*[rwaSlider]"  data-startvalue="{{$raoak*100}}" data-minvalue="0" data-maxvalue="{{min(max(10,$raoak*2),15)}}"></div>
        </div>
        <div class="my-3">
            <div>Revenues: <span id="txtRevenuesValue"></span></div>
            <div id="revenuesSlider" class="slider" data-textvalue="txtRevenuesValue"
                 data-affects="raoakSlider"
                 data-affectformula="([value]/[rwaSlider])*100.0"
                 data-step="1000" data-startvalue="{{$revenues}}"
                 data-minvalue="0" data-maxvalue="{{min($revenues*3,1500000)}}">
            </div>
        </div>
        <div class="my-3">
            <div id="rwaExpand"><span style="cursor: pointer">+</span>RWA: <span id="txtRwaValue"></span></div>
            <div id="rwaSlider" class="slider" data-textvalue="txtRwaValue"
                 data-step="10000"
                 data-affects="raoakSlider, sowSlider"
                 data-affectformula="([revenuesSlider]/[value])*100.0, (([value]/{{$rwaToLoans}})/{{$marketLoanVolume}})*100.0"
                 data-startvalue="{{$rwa}}"
                 data-minvalue="0"
                 data-maxvalue="{{min($rwa/$sow,1500000)}}">
            </div>
        </div>

        <div id="rwaDetails" class="py-3 px-2" style="background-color: #b6bec2">
            <div class="my-3">
                <div> Cash RWA / Cash Lending Volume: <span id="txtCashRwaOverLoanVolValue"></span></div>
                <div id="cashRwaOverLoanVolSlider" class="slider" data-format="0.00" data-textvalue="txtCashRwaOverLoanVolValue"
                     data-step="0.01"
                     data-affects="rwaSlider, raoakSlider"
                     data-affectformula="([value]*[sowCashSlider]*{{$cashMarketLoanVolume}}/100)+([nonCashRwaOverLoanVolSlider]*[sowNonCashSlider]*{{$nonCashMarketLoanVolume}}/100)
                        ,([revenuesSlider]/[rwaSlider])*100"
                     data-startvalue="{{$cshRwaToLoans}}" data-minvalue="0" data-maxvalue="1"></div>
            </div>
            <div class="my-3">
                <div> NonCash RWA / NonCash Lending Volume:: <span id="txtNonCashRwaOverLoanVolValue"></span></div>
                <div id="nonCashRwaOverLoanVolSlider" class="slider" data-format="0.00" data-textvalue="txtNonCashRwaOverLoanVolValue"
                     data-step="0.01"
                     data-affects="rwaSlider, raoakSlider"
                     data-affectformula="([cashRwaOverLoanVolSlider]*[sowCashSlider]*{{$cashMarketLoanVolume}}/100)+([value]*[sowNonCashSlider]*{{$nonCashMarketLoanVolume}}/100)
                        ,([revenuesSlider]/[rwaSlider])*100"
                     data-startvalue="{{$nonCashRwaToLoans}}" data-minvalue="0" data-maxvalue="1"></div>

            </div>
        </div>


        <div class="my-3">
            <div id="sowExpand"> <span style="cursor: pointer">+</span> SoW: <span id="txtSowValue"></span></div>
            <div id="sowSlider" class="slider" data-format="0.0" data-textvalue="txtSowValue"
                 data-step="1"
                 data-affects="rwaSlider, raoakSlider"
                 data-affectformula="([value]/100)*{{$marketLoanVolume}}*{{$rwaToLoans}}, ([revenuesSlider]/[rwaSlider])*100"
                 data-startvalue="{{$sow*100}}"
                 data-minvalue="0"
                 data-maxvalue="100">
            </div>
        </div>

        <div id="sowDetails" class="py-3 px-2" style="background-color: #b6bec2">
        <div class="my-3">
            <div>SoW Cash: <span id="txtSowCashValue"></span></div>
            <div id="sowCashSlider" class="slider" data-format="0.0" data-textvalue="txtSowCashValue"
                 data-step="1"
                 data-affects="sowSlider, rwaSlider, raoakSlider"
                 data-affectformula="(([value]*{{$cashMarketLoanVolume}})+([sowNonCashSlider]*{{$nonCashMarketLoanVolume}}))/{{$marketLoanVolume}} , ([sowSlider]/100)*{{$marketLoanVolume}}*{{$rwaToLoans}}, ([revenuesSlider]/[rwaSlider])*100"
                 data-startvalue="{{$cashSow*100}}" data-minvalue="0" data-maxvalue="100"></div>
        </div>
        <div class="my-3">
            <div>SoW Non-Cash: <span id="txtSowNonCashValue"></span></div>
            <div id="sowNonCashSlider" class="slider" data-format="0.0" data-textvalue="txtSowNonCashValue"
                 data-step="1"
                 data-affects="sowSlider, rwaSlider, raoakSlider"
                 data-affectformula="(([value]*{{$nonCashMarketLoanVolume}})+([sowCashSlider]*{{$cashMarketLoanVolume}}))/{{$marketLoanVolume}} , ([sowSlider]/100)*{{$marketLoanVolume}}*{{$rwaToLoans}}, ([revenuesSlider]/[rwaSlider])*100"
                 data-startvalue="{{$nonCashSow*100}}" data-minvalue="0" data-maxvalue="100"></div>
        </div>
        </div>


    </div>
    <input type="hidden" id="initialSowValue" value={{$sow*100}} />
    <input type="hidden" id="initialCashSowValue" value={{$cashSow*100}} />
    <input type="hidden" id="initialNonCashSowValue" value={{$nonCashSow*100}} />


    <script type="text/javascript">
        var previousSowFigure={{$sow*100}}
    </script>
    <script src="{{asset('public/js/accountPlans.valueSimulator.js')}}"></script>
@endsection
