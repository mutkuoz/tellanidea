var debugMode=1;

function selectElementContents(el) {
    var body = document.body, range, sel;
    if (document.createRange && window.getSelection) {
        range = document.createRange();
        sel = window.getSelection();
        sel.removeAllRanges();
        try {
            range.selectNodeContents(el);
            sel.addRange(range);
        } catch (e) {
            range.selectNode(el);
            sel.addRange(range);
        }
        document.execCommand("copy");

    } else if (body.createTextRange) {
        range = body.createTextRange();
        range.moveToElementText(el);
        range.select();
        range.execCommand("Copy");
    }
};

var quotation ={};

quotation.maturity=6;
quotation.dealSpread=0;
quotation._dealVolume=0;
quotation._dealSideBusinessValue=0;
quotation.sideBusinessValueStartYearMonth=0;
quotation.sideBusinessValueEndYearMonth=0;
quotation.PV=0;
quotation.sideBusinessValueWithMaturity=true;

quotation.startingYearMonth=201701;
quotation.currentYearMonth=201801;
quotation.currentYearMonthIndex=null;
quotation.thisYearStartIndex=null;
quotation.thisyearEndIndex=null;
quotation.nextYearStartIndex=null;
quotation.nextYearEndIndex=null;
quotation.dealSideBusinessSliderLocked=false;

quotation.accountPlanRorwaTarget=null;
quotation.accountPlanSowTarget=null;

quotation.seriesRORWA=[];
quotation.seriesSOW=[];
quotation.seriesCSR=[];

quotation.seriesRORWA_woDeal=[];
quotation.seriesSOW_woDeal=[];
quotation.seriesCSR_woDeal=[];

quotation.seriesRORWA_target=[];
quotation.seriesSOW_target=[];
quotation.seriesCSR_target=[];

quotation.targetsCalculated=false;

quotation.minimumAcceptableRoRWA=0.015;

quotation.targetTimeFrameIndex=0;
quotation.years=[];

quotation.existingCashLendingRevenues=[];
quotation.existingNonCashLendingRevenues=[];

quotation.existingCashLendingVolume=[];
quotation.existingNonCashLendingVolume=[];
quotation.existingLendingVolume=[];

quotation.minimumSpread=0;
quotation.maximumSpread=0;

quotation.addTimeSeries=function(ts1,ts2){
    var newts=[];
    for (var i=0; i<ts1.length;i++)
        newts[i]=ts1[i]+ts2[i];
    return newts;
};

quotation.calculateIndexes=function(){
    var firstYear=Math.floor(this.startingYearMonth/100);
    var currentYear=Math.floor(this.currentYearMonth/100);
    var startingIndex=(currentYear-firstYear)*12-1;
    this.currentYearMonthIndex=startingIndex+this.currentYearMonth%100;
    this.thisYearStartIndex=startingIndex+1;
    this.thisyearEndIndex=this.thisYearStartIndex+11;
    this.nextYearStartIndex=this.thisyearEndIndex+1;
    this.nextYearEndIndex=this.nextYearStartIndex+11
};

quotation.getBreakPoints=function(){

};

quotation.setCustomer=function(customer){
    this.existingSideBusinessRevenues=customer.existingSideBusinessRevenues;

    this.existingCashLendingRevenues=customer.existingCashLendingRevenues;
    this.existingNonCashLendingRevenues=customer.existingNonCashLendingRevenues;
    this.existingLendingRevenues=this.addTimeSeries(this.existingCashLendingRevenues,this.existingNonCashLendingRevenues);

    this.existingCashLendingVolume=customer.existingCashLendingVolume;
    this.existingNonCashLendingVolume=customer.existingNonCashLendingVolume;
    this.existingLendingVolume=this.addTimeSeries(this.existingCashLendingVolume,this.existingNonCashLendingVolume);

    this.existingRWA=customer.existingRWA;
    this.existingRSNBank=customer.existingRSNBank;
    this.existingRSNMarket=customer.existingRSNMarket;
    this.PV=customer.existingProductVariety;

    this.accountPlanRorwaTarget=customer.accountPlanRorwaTarget;
    this.accountPlanSowTarget=customer.accountPlanSowTarget;
    this.accountPlanCrossSellRatioTarget=customer.accountPlanCrossSellRatioTarget;

    this.minimumSpread=customer.minimumSpread;
    this.maximumSpread=customer.maximumSpread;

    this.calculateIndexes();

    this.targetTimeFrameIndex=Math.min(quotation.currentYearMonthIndex+quotation.maturity-1,35);
    this.years=[201701,201702,201703,201704,201705,201706,201707,201708,201709,201710,201711,201712,201801,201802,201803,201804,201805,201806,201807,201808,201809,201810,201811,201812,201901,201902,201903,201904,201905,201906,201907,201908,201909,201910,201911,201912];

    if (this.existingLendingVolume[this.currentYearMonthIndex]>100000000)
        $('#volumeRangeSliderRange').attr({min:0,max:250000000,step:1000000});
    else
        if (this.existingLendingVolume[this.currentYearMonthIndex]>10000000)
            $('#volumeRangeSliderRange').attr({min:0,max:25000000,step:100000});
            else
                if (this.existingLendingVolume[this.currentYearMonthIndex]>1000000)
                    $('#volumeRangeSliderRange').attr({min:0,max:2500000,step:10000});
                else
                    $('#volumeRangeSliderRange').attr({min:0,max:250000,step:1000});

    if (this.existingSideBusinessRevenues[this.currentYearMonthIndex]>1000000)
        $('#sideBusinessRangeSliderRange').attr({min:0,max:25000000,step:1});
    else
        if (this.existingSideBusinessRevenues[this.currentYearMonthIndex]>100000)
            $('#sideBusinessRangeSliderRange').attr({min:0,max:2500000,step:1});
        else
            if (this.existingSideBusinessRevenues[this.currentYearMonthIndex]>10000)
                $('#sideBusinessRangeSliderRange').attr({min:0,max:250000,step:1});

    var x=0;
    if (quotation.maturity>0)
        x=$('#sideBusinessRangeSliderRange').attr('value')/quotation.maturity;

    var txt=customer.name;
    txt+= (' - Strategy: ' + customer.strategy);
    txt+= (' - Rating: ' + customer.pdRating);
    $('#btnCustomer').html(txt);

    var y=0;
    for (i=quotation.currentYearMonthIndex;i<=quotation.targetTimeFrameIndex;i++)
        y+=quotation.existingSideBusinessRevenues[i];
    y=y/quotation.maturity;

    var currentSpread=0;
    if(quotation.existingCashLendingVolume[quotation.currentYearMonthIndex-1]>0)
        currentSpread=(quotation.existingCashLendingRevenues[quotation.currentYearMonthIndex-1]/quotation.existingCashLendingVolume[quotation.currentYearMonthIndex-1])*12;

    var currentVolume=quotation.existingCashLendingVolume[quotation.currentYearMonthIndex-1];
    var currentRSNMarket=quotation.existingRSNMarket[quotation.currentYearMonthIndex-1];

    $('#sideBusinessExplanation').html(Highcharts.numberFormat(x,0)+' TL per month on top of existing '+Highcharts.numberFormat(y,0)+' TL per month');
    $('#maturityExplanation').html('Target end date : '+this.years[this.targetTimeFrameIndex]);
    $('#spreadExplanation').html('Current spread (cash lending only, last monthX) : ' + Highcharts.numberFormat(currentSpread*100,2) + '<i id="marginDetailsButton" class="fas fa-chart-line ml-2" style="color:grey"></i>');
    $('#volumeExplanation').html('Current volume (cash lending only, last month) : ' + Highcharts.numberFormat(currentVolume /1000000,2) + ' mln TL - RSN Market : ' + Highcharts.numberFormat(currentRSNMarket/1000000,2) + ' mln TL');

};
quotation.setCustomer(customers[0]);

quotation.dealRevenues=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
quotation.dealLendingRevenues=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
quotation.dealSideBusinessRevenues=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
quotation.dealRWA=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
quotation.dealVolume=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

quotation.volumeToRwaMultiplier=1;
quotation.targetGrowthStepPerMonth=0.0065/12;

quotation.calculateTargets=function(){
    this.seriesRORWA_target=[];
    this.seriesSOW_target=[];
    this.seriesCSR_target=[];
    target=0;

    if(quotation.existingRWA[quotation.currentYearMonthIndex-1]>=10000000)
        this.targetGrowthStepPerMonth=0.004/12;

	if(this.seriesRORWA_woDeal[this.currentYearMonthIndex-1]>this.accountPlanRorwaTarget){
		for (i=0;i<this.currentYearMonthIndex;i++)
            this.seriesRORWA_target.push(null);
		for (i=this.currentYearMonthIndex;i<this.dealVolume.length;i++)
            this.seriesRORWA_target.push(this.accountPlanRorwaTarget);
	}
	else {
		for (i=0;i<this.currentYearMonthIndex;i++)
            this.seriesRORWA_target.push(null);
        var target=this.seriesRORWA_woDeal[this.currentYearMonthIndex-1];
        var delta=Math.max((this.accountPlanRorwaTarget-this.seriesRORWA_woDeal[this.currentYearMonthIndex-1])/24,this.targetGrowthStepPerMonth);
		for (i=this.currentYearMonthIndex;i<this.dealVolume.length;i++) {
            target+=delta;
            this.seriesRORWA_target.push(Math.min(target,this.accountPlanRorwaTarget));
        }
	}
    //Set SOW and CSR Targets
	for(i=0; i<this.seriesRORWA_target.length;i++){
	    if (this.seriesRORWA_target[i]==null){
	        this.seriesSOW_target.push(null);
            this.seriesCSR_target.push(null);
        }
        else {
	        this.seriesSOW_target.push(this.accountPlanSowTarget);
	        this.seriesCSR_target.push(this.accountPlanCrossSellRatioTarget);
        }
    }
    this.targetsCalculated=true;
};

quotation.calculateSeries=function() {
    this.seriesRORWA=[];
    this.seriesSOW=[];
    this.seriesCSR=[];

    this.seriesRORWA_woDeal=[];
    this.seriesSOW_woDeal=[];
    this.seriesCSR_woDeal=[];
    
    var i=0;
    var totalRevenues=0;
    var totalRWA=0;
    var sideBusinessRevenues=0;
    var rsnBank=0;
    var rsnMarket=0;

    var totalRevenues_woDeal=0;
    var totalRWA_woDeal=0;
    var sideBusinessRevenues_woDeal=0;
    var rsnBank_woDeal=0;
    var rsnMarket_woDeal=0;

    while (i<this.dealLendingRevenues.length)
    {
        if (i%12===0) {
            totalRevenues=0;
            totalRWA=0;
            sideBusinessRevenues=0;
            totalRevenues_woDeal=0;
            totalRWA_woDeal=0;
            sideBusinessRevenues_woDeal=0;
        }
        totalRevenues+=this.existingLendingRevenues[i]+this.existingSideBusinessRevenues[i]+this.dealRevenues[i];
        totalRWA+=this.existingRWA[i]+this.dealRWA[i];
        var roc=0;
        if(totalRWA!==0)
            roc=(totalRevenues/(totalRWA/(i%12+1)))*12/(i%12+1);
        this.seriesRORWA.push(roc);

        totalRevenues_woDeal+=this.existingLendingRevenues[i]+this.existingSideBusinessRevenues[i];
        totalRWA_woDeal+=this.existingRWA[i];
        var roc_woDeal=0;
        if(totalRWA_woDeal!==0)
            roc_woDeal=(totalRevenues_woDeal/(totalRWA_woDeal/(i%12+1)))*12/(i%12+1);
        this.seriesRORWA_woDeal.push(roc_woDeal);

        rsnBank = this.existingRSNBank[i]+this.dealVolume[i];
        rsnMarket = this.existingRSNMarket[i]+this.dealVolume[i];
        var sow=0;
        if(rsnMarket!==0)
            sow=(rsnBank/rsnMarket);
        this.seriesSOW.push(sow);

        rsnBank_woDeal = this.existingRSNBank[i];
        rsnMarket_woDeal = this.existingRSNMarket[i];
        var sow_woDeal=0;
        if(rsnMarket_woDeal!==0)
            sow_woDeal=(rsnBank_woDeal/rsnMarket_woDeal);
        this.seriesSOW_woDeal.push(sow_woDeal);

        sideBusinessRevenues+=this.existingSideBusinessRevenues[i]+this.dealSideBusinessRevenues[i];
        var csr=0;
        if (totalRevenues!==0)
            csr=(sideBusinessRevenues/totalRevenues);
        this.seriesCSR.push(csr);

        sideBusinessRevenues_woDeal+=this.existingSideBusinessRevenues[i];
        var csr_woDeal=0;
        if (totalRevenues_woDeal!==0)
            csr_woDeal=(sideBusinessRevenues_woDeal/totalRevenues_woDeal);
        this.seriesCSR_woDeal.push(csr_woDeal);

        i++
    }
    if (this.targetsCalculated===false) {
        this.calculateTargets();
    }
};
quotation.calculateSeries();

quotation.nextYear=function() {
    return Math.floor(this.currentYearMonth/100)+1;
};
quotation.thisYear=function() {
    return Math.floor(this.currentYearMonth/100);
};
quotation.yearMonths=function() {
    var listOfYearMonths=[];
    for (var i=0;i<this.existingRWA.length;i++)
        listOfYearMonths.push(quotation.startingYearMonth+i%12+Math.floor(i/12)*100);
    return listOfYearMonths;
};

quotation.setDealLendingVolume= function (volume){
    this._dealVolume = volume;

    for(var i=this.currentYearMonthIndex;i<=this.targetTimeFrameIndex;i++){
        this.dealVolume[i]=parseFloat(volume);
        this.dealRWA[i]=this.dealVolume[i]*this.volumeToRwaMultiplier;
    }
    for(var j=(this.targetTimeFrameIndex+1);j<this.dealVolume.length;j++) {
        this.dealVolume[j]=0;
        this.dealRWA[j]=this.dealVolume[j]*this.volumeToRwaMultiplier;
    }
    this.calculateDealRevenues();
};
quotation.calculateDealRevenues=function(){
    for (var i=0;i<this.dealVolume.length;i++){
        this.dealLendingRevenues[i]=this.dealVolume[i]*this.dealSpread/12;
        this.dealRevenues[i]=this.dealLendingRevenues[i]+this.dealSideBusinessRevenues[i];
    }
    this.calculateSeries();
};
quotation.setDealLendingSpread=function (spread){
    this.dealSpread=spread;
    this.calculateDealRevenues();
};
quotation.setMaturity=function (maturity) {
    this.maturity=maturity;
    this.targetTimeFrameIndex=Math.min(this.currentYearMonthIndex+this.maturity-1,35);
    this.setDealLendingVolume(this._dealVolume);
    this.setDealSideBusinessRevenues(this._dealSideBusinessValue);
    $('#maturityExplanation').html('Target End Date : '+this.years[this.targetTimeFrameIndex]);
};

quotation.setDealSideBusinessRevenues=function(sideBusinessRevenue) {
    this._dealSideBusinessValue=sideBusinessRevenue;
    if (this.sideBusinessValueWithMaturity){
        if (this.maturity===0)
            monthlysideBusinessRevenue=0;
        else
            monthlysideBusinessRevenue=sideBusinessRevenue/this.maturity;

        var i=0;
        for (i=this.currentYearMonthIndex;i<=this.targetTimeFrameIndex;i++)
            this.dealSideBusinessRevenues[i]=monthlysideBusinessRevenue;
        for (;i<this.dealSideBusinessRevenues.length;i++)
            this.dealSideBusinessRevenues[i]=0;
        this.calculateDealRevenues();
    }
    var x=sideBusinessRevenue/quotation.maturity;
    var y=0;

    for (i=quotation.currentYearMonthIndex;i<quotation.targetTimeFrameIndex;i++)
        y+=quotation.existingSideBusinessRevenues[i];
    y=y/(quotation.targetTimeFrameIndex-quotation.currentYearMonthIndex);
    $('#sideBusinessExplanation').html(Highcharts.numberFormat(x,0)+' TL per month on top of existing '+Highcharts.numberFormat(y,0)+' TL per month')
};

var volumeRangeSlider = function() {
    $('#volumeRangeSliderValue').html($('#volumeRangeSliderRange').attr('value'));
    $('#volumeRangeSliderRange').on('input', function () {
        $('#volumeRangeSliderValue').html(Highcharts.numberFormat(this.value,0,'.',','));
        quotation.setDealLendingVolume(this.value);
        updateCharts();
    });
};
volumeRangeSlider();

var spreadRangeSlider = function() {
    $('#spreadRangeSliderValue').html($('#spreadRangeSliderRange').attr('value'));
    $('#spreadRangeSliderRange').on('input', function () {
        var val=parseFloat(this.value);
        $('#spreadRangeSliderValue').html(Highcharts.numberFormat(val,2));
        quotation.setDealLendingSpread(this.value/100);
        updateCharts();
    });
};
spreadRangeSlider();

var maturityRangeSlider = function() {
    $('#maturityRangeSliderValue').html($('#maturityRangeSliderRange').attr('value'));
    $('#maturityRangeSliderRange').on('input', function () {
        var val=parseInt(this.value);
        $('#maturityRangeSliderValue').html(val);
        quotation.setMaturity(val);
        updateCharts();
        updateChartStops();
    });
};
maturityRangeSlider();

var sideBusinessRangeSlider = function() {
    $('#sideBusinessRangeSliderValue').html($('#sideBusinessRangeSliderRange').attr('value'));
    $('#sideBusinessRangeSliderRange').on('input', function () {
        $('#sideBusinessRangeSliderValue').html(Highcharts.numberFormat(this.value,0,'.',','));
        quotation.setDealSideBusinessRevenues(parseInt(this.value));
        updateCharts();
    });
};
sideBusinessRangeSlider();

var gaugeOptions = {

    chart: {
        type: 'solidgauge'
    },

    title: null,

    pane: {
        center: ['50%', '85%'],
        size: '140%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#55BF3B'], // green
            [0.5, '#DDDF0D'], // yellow
            [0.9, '#DF5353'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -70
        },
        labels: {
            y: 16
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 15,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};

// The  gauges
var titleStyle={'font-size': '2.0em', 'font-weight':'bold'};
var chartRoRWA = Highcharts.chart('container-rorwa', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: 5/100,
        tickInterval:0.0001,
        title: {
            text: 'RORC',
            style: titleStyle
        },
        subtitle:{text: '201806'},
        labels: {
            formatter: function () {
                var label = this.axis.defaultLabelFormatter.call(this);
                return (Math.floor((label*100)))+ '%';
            }
        }
    },
    chart:{marginTop:20},
    credits: {
        enabled: false
    },

    series: [{
        name: 'Speed',
        data: [0],
        dataLabels: {
            formatter: function () {
                return '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">'+Highcharts.numberFormat(Math.round(this.y*10000)/100,2,'.',',')+'</span><br/>' +
                    '<span style="font-size:12px;color:silver">%</span></div>';
            }
        },
        tooltip: {
            valueSuffix: '%'
        }
    }]

}));
var chartSOW = Highcharts.chart('container-sow', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: 50/100,
        tickInterval:0.0001,
        title: {
            text: 'SOW',
            style: titleStyle
        },
        labels: {
            formatter: function () {
                var label = this.axis.defaultLabelFormatter.call(this);
                return (Math.floor((label * 100))) + '%';
            }
        }
    },
    chart: {
        marginTop: 20
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'RPM',
        data: [1],
        dataLabels: {
            formatter: function () {
                return '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">'+Highcharts.numberFormat(Math.round(this.y*1000)/10,1,'.',',')+'</span><br/>' +
                    '<span style="font-size:12px;color:silver">%</span></div>';
            }
        },
        tooltip: {
            valueSuffix: ' revolutions/min'
        }
    }]

}));
var chartCSR = Highcharts.chart('container-CSR', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: 50/100,
        tickInterval:0.0001,
        title: {
            text: 'NLR',
            style: titleStyle
        },
        labels: {
            formatter: function () {
                var label = this.axis.defaultLabelFormatter.call(this);
                return (Math.floor((label * 100))) + '%';
            }
        }
    },
    chart:{marginTop:20},
    credits: {
        enabled: false
    },
    series: [{
        name: 'RPM',
        data: [1],
        dataLabels: {
            formatter: function () {
                return '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">'+Highcharts.numberFormat(Math.round(this.y*1000)/10,1,'.',',')+'</span><br/>' +
                    '<span style="font-size:12px;color:silver">%</span></div>';
            }
        },
        tooltip: {
            valueSuffix: ' revolutions/min'
        }
    }]

}));
var chartProductVariety = Highcharts.chart('container-productVariety', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: 16,
        title: {
            text: 'PV',
            style: titleStyle
        }
    },
    chart:{marginTop:20},
    credits: {
        enabled: false
    },
    series: [{
        name: 'RPM',
        data: [1],
        dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
            ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.0f}</span><br/>' +
            '<span style="font-size:12px;color:silver">#</span></div>'
        },
        tooltip: {
            valueSuffix: ' revolutions/min'
        }
    }]

}));

var printResults = function(){
    var htmlText='';
    htmlText+='<table id="tableFinancialDetails" class="table table-hover">';

    var listOfYearMonths=quotation.yearMonths();
    htmlText+='<thead>';
    htmlText+='<tr>';
    htmlText+='<th class="freeze">Metric</th>';
    for (var i=0; i<listOfYearMonths.length;i++)
        htmlText+= ('<th  class="text-right">'+listOfYearMonths[i]+'</th>');
    htmlText+='</tr>';
    htmlText+='</thead>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">RORWA - YTD</th>';
    for (var i=0; i<quotation.seriesRORWA.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.seriesRORWA[i]*100,2,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">RORWA - target</th>';
    for (var i=0; i<quotation.seriesRORWA_target.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.seriesRORWA_target[i]*100,2,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze align-middle">SOW - YTD</th>';
    for (var i=0; i<quotation.seriesSOW.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.seriesSOW[i]*100,2,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">CSR - YTD</th>';
    for (var i=0; i<quotation.seriesCSR.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.seriesCSR[i]*100,2,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">Existing Lending Revenues</th>';
    for (var i=0; i<quotation.existingLendingRevenues.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.existingLendingRevenues[i],1,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze align-middle">Existing Side Business Revenues</th>';
    for (var i=0; i<quotation.existingSideBusinessRevenues.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.existingSideBusinessRevenues[i],1,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">Deal Revenues</th>';
    for (var i=0; i<quotation.dealRevenues.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.dealRevenues[i],1,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">Deal Lending Revenues</th>';
    for (var i=0; i<quotation.dealLendingRevenues.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.dealLendingRevenues[i],1,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">Deal Side Business Revenues</th>';
    for (var i=0; i<quotation.dealSideBusinessRevenues.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.dealSideBusinessRevenues[i],1,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">Existing RWA</th>';
    for (var i=0; i<quotation.existingRWA.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.existingRWA[i],1,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">Deal RWA</th>';
    for (var i=0; i<quotation.dealRWA.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.dealRWA[i],1,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">RSN Bank</th>';
    for (var i=0; i<quotation.existingRSNBank.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.existingRSNBank[i],1,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='<tr>';
    htmlText+='<th class="freeze">RSN Market</th>';
    for (var i=0; i<quotation.existingRSNMarket.length;i++)
        htmlText+= ('<td class="text-right">'+Highcharts.numberFormat(quotation.existingRSNMarket[i],1,'.',',')+'</td>');
    htmlText+='</tr>';

    htmlText+='</table>';
    return htmlText;
};

updateChartStops = function() {
    //update RoRWA Colors
    var rorwaColor='#bf000c';
    if (quotation.seriesRORWA[quotation.targetTimeFrameIndex]>quotation.minimumAcceptableRoRWA) rorwaColor='#dedf00';
    if (quotation.seriesRORWA[quotation.targetTimeFrameIndex]>quotation.seriesRORWA_target[quotation.targetTimeFrameIndex]) rorwaColor='#37bf1f';
    chartRoRWA.yAxis[0].update({stops: [[0.0, rorwaColor],[1.0, rorwaColor]]}, false);
    chartRoRWA.series[0].bindAxes();
    chartRoRWA.redraw(true);

    //update SOW Colors
    var sowColor='#dedf00';
    if (quotation.seriesSOW[quotation.targetTimeFrameIndex]>=quotation.seriesSOW_target[quotation.targetTimeFrameIndex]) sowColor='#37bf1f';
    chartSOW.yAxis[0].update({stops: [[0.0, sowColor],[1.0, sowColor]]}, false);
    chartSOW.series[0].bindAxes();
    chartSOW.redraw(true);

    //update CSR Colors
    var csrColor='#dedf00';
    if (quotation.accountPlanCrossSellRatioTarget!==null)
        if (quotation.seriesCSR[quotation.targetTimeFrameIndex]>=quotation.seriesSOW_target[quotation.targetTimeFrameIndex]) csrColor='#37bf1f';
    chartCSR.yAxis[0].update({stops: [[0.0, csrColor],[1.0, csrColor]]}, false);
    chartCSR.series[0].bindAxes();
    chartCSR.redraw(true);
};

updateCharts = function () {

    if (chartRoRWA && chartSOW && chartCSR) {
        pointRWA = chartRoRWA.series[0].points[0];
        pointSOW = chartSOW.series[0].points[0];
        pointCSR = chartCSR.series[0].points[0];
        pointPV= chartProductVariety.series[0].points[0];
        pointRWA.update(quotation.seriesRORWA[quotation.targetTimeFrameIndex]);
        pointSOW.update(quotation.seriesSOW[quotation.targetTimeFrameIndex]);
        pointCSR.update(quotation.seriesCSR[quotation.targetTimeFrameIndex]);
        pointPV.update(quotation.PV);
        //chartRoRWA.yAxis[0].setTitle({text: "RoRWA ("+quotation.years[quotation.targetTimeFrameIndex]+")"});
        //chartSOW.yAxis[0].setTitle({text: "SOW ("+quotation.years[quotation.targetTimeFrameIndex]+")"});
        //chartRoRWA.yAxis[0].setTitle({text: "RORC"});
        //chartSOW.yAxis[0].setTitle({text:'SOW'});
        //chartCSR.yAxis[0].setTitle({text: "CSR ("+quotation.years[quotation.targetTimeFrameIndex]+")"});
        //chartProductVariety.yAxis[0].setTitle({text: "Product Variety"});
    }


    var counter=0;
    var okText='<i class="far fa-smile ml-2" style="color:green"></i>';
    var badText='<i class="far fa-frown ml-2" style="color:red"></i>';
    for(var i=11;i<quotation.targetTimeFrameIndex;i+=12){
        counter++;
        txt=quotation.years[i]+' : '+Highcharts.numberFormat(quotation.seriesRORWA[i]*100,2);
        if (quotation.seriesRORWA_target[i]!=null) {
            if (quotation.seriesRORWA[i]>=quotation.seriesRORWA_target[i])
                txt+=okText;
            else
                txt+=badText;
        }
        $('#rorwa-text-'+counter).html(txt);

        txt=quotation.years[i]+' : '+Highcharts.numberFormat(quotation.seriesSOW[i]*100,2);
        if (quotation.seriesSOW_target[i]!=null) {
            if (quotation.seriesSOW[i]>=quotation.seriesSOW_target[i])
                txt+=okText;
            else
                txt+=badText;
        }
        $('#sow-text-'+counter).html(txt);

        txt=quotation.years[i]+' : '+Highcharts.numberFormat(quotation.seriesCSR[i]*100,1);
        if (quotation.seriesCSR_target[i]!=null) {
            if (quotation.seriesCSR[i]>=quotation.seriesCSR_target[i])
                txt+=okText;
            else
                txt+=badText;
        }
        $('#csr-text-'+counter).html(txt);

    }
    for (;counter<=3;counter++){
        counter++;
        $('#rorwa-text-'+counter).html('');
        $('#sow-text-'+counter).html('');
        $('#csr-text-'+counter).html('');
    }
    updateChartStops();
    //quotation.calculateSeries();
};
updateCharts();


var rorwaDetailChart=null;
var sowDetailChart=null;
var csrDetailChart=null;

$('#container-rorwa').on('click', function () {
    $('#rorwaDetailChart').modal('show');
    rorwaDetailChart=Highcharts.chart('rorwaDetailChartContainer', {
        chart: {
            type: 'line',
            zoomType: 'x'
        },
        title: {
            text: 'RoRWA - YTD'
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories: [{
                name: "2017",
                categories: [1,2,3,4,5,6,7,8,9,10,11,12]
            }, {
                name: "2018",
                categories: [1,2,3,4,5,6,7,8,9,10,11,12]
            }, {
                name: "2019",
                categories: [1,2,3,4,5,6,7,8,9,10,11,12]
            }],
            labels: {
                groupedOptions: [{
                    style: {color: 'blue' // set red font for labels in 1st-Level
                    }
                }, {
                    rotation: -0, // rotate labels for a 2nd-level
                    align: 'center'
                }],
                rotation: 0 // 0-level options aren't changed, use them as always
            },
            plotBands: [{
                color: '#CED8F6', // Color value
                from: quotation.currentYearMonthIndex - 0.5, // Start of the plot band
                to: quotation.currentYearMonthIndex+quotation.maturity - 0.5 // End of the plot band
            }],
        },
        tooltip: {
            shared: true,
            formatter: function () {
                var s = '<b>' + this.x + '</b>';
                $.each(this.points, function () {
                    s += '<br/>' + this.series.name + ': ' +
                        Highcharts.numberFormat(this.y*100,2) + '%';
                });
                return s;
            },
            crosshairs: true
        },
        yAxis: {
            title: {
                text: null
            },
            labels:{
                formatter:function() {
                    var pcnt = (this.value) * 100;
                    return Highcharts.numberFormat(pcnt,2) + '%';
                }
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true,
                    formatter:function() {
                        var pcnt = (this.y) * 100;
                        return Highcharts.numberFormat(pcnt,2) + '%';
                    }
                }
            }
        },
        series: [{
            name: 'RORWA with Deal',
            data: quotation.seriesRORWA
        }, {
            name: 'RORWA w/o Deal',
            data: quotation.seriesRORWA_woDeal
        },{
            name: 'RORWA target',
            data: quotation.seriesRORWA_target,
            color: 'red',
            dataLabels: {
                enabled: false
            },
            marker: {enabled: false}
        }
        ]
    });
});
$('#container-sow').on('click', function () {
    $('#sowDetailChart').modal('show');
    sowDetailChart=Highcharts.chart('sowDetailChartContainer', {
        chart: {
            type: 'line',
            zoomType: 'x'
        },
        title: {
            text: 'SOW - YTD'
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories:
                [{name: "2017", categories: [1,2,3,4,5,6,7,8,9,10,11,12]},
                {name: "2018", categories: [1,2,3,4,5,6,7,8,9,10,11,12]},
                {name: "2019", categories: [1,2,3,4,5,6,7,8,9,10,11,12]}],
            labels: {
                groupedOptions: [{
                    style: {color: 'blue' // set red font for labels in 1st-Level
                    }
                }, {
                    rotation: -0, // rotate labels for a 2nd-level
                    align: 'center'
                }],
                rotation: 0 // 0-level options aren't changed, use them as always
            },
            plotBands: [{
                color: '#CED8F6', // Color value
                from: quotation.currentYearMonthIndex -0.5, // Start of the plot band
                to: quotation.currentYearMonthIndex+quotation.maturity -0.5 // End of the plot band
            }],
        },
        tooltip: {
            shared: true,
            formatter: function () {
                var s = '<b>' + this.x + '</b>';
                $.each(this.points, function () {
                    s += '<br/>' + this.series.name + ': ' +
                        Highcharts.numberFormat(this.y*100,1) + '%';
                });
                return s;
            },
            crosshairs: true
        },
        yAxis: {
            title: {
                text: null
            },
            labels:{
                formatter:function() {
                    var pcnt = (this.value) * 100;
                    return Highcharts.numberFormat(pcnt,1) + '%';
                }
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true,
                    formatter:function() {
                        var pcnt = (this.y) * 100;
                        return Highcharts.numberFormat(pcnt,1) + '%';
                    }
                }
            }
        },
        series: [
            {name: 'SOW with Deal', data: quotation.seriesSOW},
            {name: 'SOW w/o Deal', data: quotation.seriesSOW_woDeal},
            {name: 'SOW target', data: quotation.seriesSOW_target}
        ]
    });
});
$('#container-CSR').on('click', function () {
    $('#csrDetailChart').modal('show');
    csrDetailChart=Highcharts.chart('csrDetailChartContainer', {
        chart: {
            type: 'line',
            zoomType: 'x'
        },
        title: {
            text: 'CSR - YTD'
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories: [{
                name: "2017",
                categories: [1,2,3,4,5,6,7,8,9,10,11,12]
            }, {
                name: "2018",
                categories: [1,2,3,4,5,6,7,8,9,10,11,12]
            }, {
                name: "2019",
                categories: [1,2,3,4,5,6,7,8,9,10,11,12]
            }],
            labels: {
                groupedOptions: [{
                    style: {color: 'blue' // set red font for labels in 1st-Level
                    }
                }, {
                    rotation: -0, // rotate labels for a 2nd-level
                    align: 'center'
                }],
                rotation: 0 // 0-level options aren't changed, use them as always
            },
            plotBands: [{
                color: '#CED8F6', // Color value
                from: quotation.currentYearMonthIndex - 0.5, // Start of the plot band
                to: quotation.currentYearMonthIndex+quotation.maturity - 0.5 // End of the plot band
            }],
        },
        tooltip: {
            shared: true,
            formatter: function () {
                var s = '<b>' + this.x + '</b>';
                $.each(this.points, function () {
                    s += '<br/>' + this.series.name + ': ' +
                        Highcharts.numberFormat(this.y*100,1) + '%';
                });
                return s;
            },
            crosshairs: true
        },
        yAxis: {
            title: {
                text: null
            },
            labels:{
                formatter:function() {
                    var pcnt = (this.value) * 100;
                    return Highcharts.numberFormat(pcnt,1) + '%';
                }
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true,
                    formatter:function() {
                        var pcnt = (this.y) * 100;
                        return Highcharts.numberFormat(pcnt,1) + '%';
                    }
                }
            }
        },
        series: [{
            name: 'CSR with Deal',
            data: quotation.seriesCSR
        }, {
            name: 'CSR w/o Deal',
            data: quotation.seriesCSR_woDeal
        }, {
            name: 'CSR Target',
            data: quotation.seriesCSR_target
        }]
    });
});

$('#btnShowDetails').on('click', function () {
    $('#financialDetailsContent').html(printResults());
});

$('#customerSelector').on('show.bs.modal', function (e) {
    htmlText='';
    for (i=0;i<customers.length;i++) {
        htmlText+='<button type="button" class="btn btn-light btnCustomerSelect px-2" customerIndex="'+i+'">'+customers[i].name+'</button>';
    }
    $('#customerList').html(htmlText);
});

$('body').on('click', 'button.btnCustomerSelect', function() {
    //alert($(this).attr('customerIndex'));
    quotation.targetsCalculated=false;
    quotation.setCustomer(customers[parseInt($(this).attr('customerIndex'))]);
    quotation.calculateSeries();
    var id=parseInt($(this).attr('customerIndex'));

    var txt=customers[id].name;
    txt+= (' - Strategy: ' + customers[id].strategy);
    txt+= (' - Rating: ' + customers[id].pdRating);

    $('#btnCustomer').html(txt);
    $('#customerSelector').modal('hide');
    updateCharts();
});

function selectMaximumOfSpreads (spreads){
	var maxObj=spreads[0];
	for (var i=0; i<spreads.length; i++){
		if ((spreads[i].spread>maxObj.spread) || ((spreads[i].spread===maxObj.spread && spreads[i].extraSideBusinessRequired>maxObj.extraSideBusinessRequired)))
			var maxObj=spreads[i];
	}
	return maxObj;
}

function calculateMinimumDealRORWA( csrFixed, csr ){
    csrFixed = typeof csrFixed !== 'undefined' ? csrFixed : true;
    var spread=quotation.minimumSpread;
    var targetMonths=[];
    targetMonths.push(quotation.targetTimeFrameIndex);
    if (quotation.targetTimeFrameIndex>quotation.thisyearEndIndex)
        targetMonths.push(quotation.thisyearEndIndex);
    if (quotation.targetTimeFrameIndex>quotation.nextYearEndIndex)
        targetMonths.push(quotation.nextYearEndIndex);

    var originalDealVolume=parseFloat(quotation._dealVolume);
    var spreads=[];
    var extraSideBusinessRequiredTotal=0;
    for (var i=0;i<targetMonths.length;i++){
        var targetMonth=targetMonths[i];
        if (quotation.seriesRORWA_woDeal[targetMonth]>quotation.seriesRORWA_target[targetMonth]){
            var newVolume=Math.max(quotation._dealVolume,quotation.existingRSNMarket[targetMonth]*quotation.seriesSOW_target[targetMonth]-quotation.existingRSNBank[targetMonth]);
            quotation.setDealLendingVolume(newVolume);
        }
        while (spread<quotation.maximumSpread){
            quotation.setDealLendingSpread(spread);
            if (!csrFixed) {
                // extraSideBusinessRequiredTotal=Math.ceil(
                //     (((originalDealVolume * spread / 12 * csr )  / (1-csr)) * quotation.maturity)/1000
                // )*1000 ;
                extraSideBusinessRequiredTotal=Math.ceil(
                     (((originalDealVolume * spread / 12 * csr )  / (1-csr)) * quotation.maturity)
                );
                quotation.setDealSideBusinessRevenues(extraSideBusinessRequiredTotal);
            }
            if (quotation.seriesRORWA[targetMonth]>quotation.seriesRORWA_target[targetMonth])
                break;
            spread=spread+0.0001000;
        }
        if (spread>=quotation.maximumSpread) {
            spread=quotation.maximumSpread;
			startingIndex=targetMonth-(targetMonth%12)
			var averageRWA=0;
			var counter=0;
			for (i=startingIndex;i<=targetMonth;i++){
				counter++;
				averageRWA+=(quotation.existingRWA[i]+quotation.dealRWA[i])
			};
			averageRWA= (averageRWA/counter);
			var extraSideBusinessRequired=(quotation.seriesRORWA_target[targetMonth]-quotation.seriesRORWA[targetMonth])*(counter/12)*averageRWA;
			var extraSideBusinessRequiredperMonth=extraSideBusinessRequired/counter;
			extraSideBusinessRequiredTotal=extraSideBusinessRequiredperMonth*quotation.maturity;
			//extraSideBusinessRequiredTotal=Math.ceil(extraSideBusinessRequiredTotal/1000)*1000;
            extraSideBusinessRequiredTotal=Math.ceil(extraSideBusinessRequiredTotal);

        }
        spreads.push({spread:spread, extraSideBusinessRequired:extraSideBusinessRequiredTotal});
		quotation.setDealLendingVolume(originalDealVolume);
    }
    spread=selectMaximumOfSpreads(spreads);
    var rangeSlider=$('#spreadRangeSliderRange');
    rangeSlider.val(spread.spread*100);
    rangeSlider.trigger('input');
    return spread;
}

//Calculate Price Click
quotation.scenarios=[];

quotation.AddScenario=function(s){
	var scenarioExists=false;
	for (i=0;i<this.scenarios.length;i++)
		if (Math.abs(this.scenarios[i].spread-s.spread)<0.0002 && Math.abs(this.scenarios[i].sideBusinessValue-s.sideBusinessValue)<1000)
			scenarioExists=true;
	if (!scenarioExists)
		this.scenarios.push(s);
};

$('#btnCalculatePrice').on('click',function(){
    if (quotation._dealVolume===0){ // do not process if lending volume is 0
        alert ('Lending volume can\'t be 0');
        return;
    }
    quotation.scenarios=[];
    var spread=calculateMinimumDealRORWA();
    quotation.AddScenario({spread:spread.spread,sideBusinessValue:quotation._dealSideBusinessValue+spread.extraSideBusinessRequired});

    var dealRevenues=0;
    for (i=0;i<quotation.dealRevenues.length;i++)
        dealRevenues+=quotation.dealRevenues[i];

    if (quotation._dealSideBusinessValue>0) {
        $('#sideBusinessRangeSliderRange').val(0);
        $('#sideBusinessRangeSliderRange').trigger('input');
        spread=calculateMinimumDealRORWA();
        quotation.AddScenario({spread:spread.spread,sideBusinessValue:quotation._dealSideBusinessValue+spread.extraSideBusinessRequired});

        dealRevenues=0; //reset deal revenues
        for (i=0;i<quotation.dealRevenues.length;i++)
            dealRevenues+=quotation.dealRevenues[i];
    }

    targetCSR=Math.min(quotation.seriesCSR[quotation.currentYearMonthIndex-1],0.5);
    /*targetSidebusinessRevenues=(quotation._dealVolume*quotation.dealSpread)*(quotation.maturity/12)*targetCSR;
    targetSidebusinessRevenues=Math.ceil(targetSidebusinessRevenues/1000)*1000;
    $('#sideBusinessRangeSliderRange').val(targetSidebusinessRevenues);
    $('#sideBusinessRangeSliderRange').trigger('input');*/
    spread=calculateMinimumDealRORWA(false, targetCSR);
    //quotation.AddScenario({spread:spread.spread,sideBusinessValue:quotation._dealSideBusinessValue+spread.extraSideBusinessRequired});
    quotation.AddScenario({spread:spread.spread,sideBusinessValue:quotation._dealSideBusinessValue});

    htmlText="";
    for (i=0;i<quotation.scenarios.length;i++) {
        htmlText+='<div class="card my-2 scenario" scenarioNumber="'+i+'"><div class="card-body">Scenario '+ (i+1) +': Spread: '+
            Highcharts.numberFormat(quotation.scenarios[i].spread*100,2)+
            ' + Entered SideBusiness: '+
            Highcharts.numberFormat(quotation.scenarios[i].sideBusinessValue,0)+
            '</div></div>';
    }
    $('#scenarioSelectorBody').html(htmlText);
    $('#scenarioSelector').modal('show');
});

//settings Open
$('.volumeSettings').on('click',function(){
    $('#settingsVolumeValue').val(quotation._dealVolume);
    $('#settingsVolume').modal('show');
});
$('.sideBusinessSettings').on('click',function(){
    $('#settingsSideBusinessValue').val(quotation._dealSideBusinessValue);
    $('#settingsSideBusiness').modal('show');
});

//settings Save
$('#settingsSideBusinessSave').on('click',function(){
    var value=$('#settingsSideBusinessValue').val();
    if (!isNumeric(value)){
        alert('Side business value must be a number!');
        return;
    }
    var rangeSlider=$('#sideBusinessRangeSliderRange');
    var newSideBusinessValue=parseFloat(value);
    var maxValue=parseFloat(rangeSlider.attr('max'));
    if(newSideBusinessValue>maxValue)
        rangeSlider.attr('max',newSideBusinessValue);
    rangeSlider.val(newSideBusinessValue);
    rangeSlider.trigger('input');
    $('#settingsSideBusiness').modal('hide');
});
$('#settingsVolumeSave').on('click',function(){
    var value=$('#settingsVolumeValue').val();
    if (!isNumeric(value)){
        alert('Volume must be a number!');
        return;
    }
    var rangeSlider=$('#volumeRangeSliderRange');
    var newVolumeValue=parseFloat(value);
    var maxValue=parseFloat(rangeSlider.attr('max'));
    if(newVolumeValue>maxValue)
        rangeSlider.attr('max',newVolumeValue);
    rangeSlider.val(newVolumeValue);
    rangeSlider.trigger('input');
    $('#settingsVolume').modal('hide');
});

$('#scenarioSelector').on('click', 'div.scenario', function() {
    selectedScenario=$(this).attr('scenarioNumber');
    var spreadRengeSlider=$('#spreadRangeSliderRange');
    spreadRengeSlider.val(quotation.scenarios[selectedScenario].spread*100);
    spreadRengeSlider.trigger('input');
    var sideBusinessRangeSlider=$('#sideBusinessRangeSliderRange');
    if (quotation.scenarios[selectedScenario].sideBusinessValue>$('#sideBusinessRangeSliderRange').attr('max'))
        $('#sideBusinessRangeSliderRange').attr({max:quotation.scenarios[selectedScenario].sideBusinessValue});
    sideBusinessRangeSlider.val(quotation.scenarios[selectedScenario].sideBusinessValue);
    sideBusinessRangeSlider.trigger('input');
    $('#scenarioSelector').modal('hide');
});

$('.sliderExplanation').on('click','#marginDetailsButton',function(){
        margins_woDeal=[];
        margins_wDeal=[];
        for (i=0;i<quotation.existingCashLendingRevenues.length;i++) {
            if(quotation.existingCashLendingVolume[i]>0 || quotation.dealVolume[i]>0)
                margins_wDeal.push(((quotation.existingCashLendingRevenues[i]+quotation.dealLendingRevenues[i])/(quotation.existingCashLendingVolume[i]+quotation.dealVolume[i]))*12);
            else
                margins_wDeal.push(null);
            if(quotation.existingCashLendingVolume[i]>0)
                margins_woDeal.push((quotation.existingCashLendingRevenues[i]/quotation.existingCashLendingVolume[i])*12);
            else
                margins_wDeal.push(null);
        }

        $('#marginDetailChart').modal('show');
        marginDetailChart=Highcharts.chart('marginDetailChartContainer', {
            chart: {
                type: 'line',
                zoomType: 'x'
            },
            title: {
                text: 'Margin (Cash Lending Only - Monthly)'
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: [{
                    name: "2017",
                    categories: [1,2,3,4,5,6,7,8,9,10,11,12]
                }, {
                    name: "2018",
                    categories: [1,2,3,4,5,6,7,8,9,10,11,12]
                }, {
                    name: "2019",
                    categories: [1,2,3,4,5,6,7,8,9,10,11,12]
                }],
                labels: {
                    groupedOptions: [{
                        style: {color: 'blue' // set red font for labels in 1st-Level
                        }
                    }, {
                        rotation: -0, // rotate labels for a 2nd-level
                        align: 'center'
                    }],
                    rotation: 0 // 0-level options aren't changed, use them as always
                },
                plotBands: [{
                    color: '#CED8F6', // Color value
                    from: quotation.currentYearMonthIndex - 0.5, // Start of the plot band
                    to: quotation.currentYearMonthIndex+quotation.maturity - 0.5 // End of the plot band
                }],
            },
            tooltip: {
                shared: true,
                formatter: function () {
                    var s = '<b>' + this.x + '</b>';
                    var counter=0;
                    $.each(this.points, function () {
                        s += '<br/>' + this.series.name + ': ' +
                            Highcharts.numberFormat(this.y*100,2) + '%';
                        var idx=this.series.data.indexOf( this.point );
                        if (counter===0) {
                            s += '<br/>--Revenues:' + Highcharts.numberFormat(quotation.existingCashLendingRevenues[idx],0);
                            s += '<br/>--Volume:' + Highcharts.numberFormat(quotation.existingCashLendingVolume[idx],0);
                        }
                        else {
                            s += '<br/>--Revenues:' + Highcharts.numberFormat(quotation.existingCashLendingRevenues[idx]+quotation.dealLendingRevenues[idx],0);
                            s += '<br/>--Volume:' + Highcharts.numberFormat(quotation.existingCashLendingVolume[idx]+quotation.dealVolume[idx],0);
                        }
                        counter++;
                    });

                    return s;
                },
                crosshairs: true
            },
            yAxis: {
                title: {
                    text: null
                },
                labels:{
                    formatter:function() {
                        var pcnt = (this.value) * 100;
                        return Highcharts.numberFormat(pcnt,2) + '%';
                    }
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true,
                        formatter:function() {
                            var pcnt = (this.y) * 100;
                            return Highcharts.numberFormat(pcnt,2) + '%';
                        }
                    }
                }
            },
            series: [{
                name: 'Margin with Deal',
                data: margins_wDeal
            }, {
                name: 'Margin w/o Deal',
                data: margins_woDeal
            }
            ]
        });

});

function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}