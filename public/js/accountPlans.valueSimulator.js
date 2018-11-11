var raoakSlider = document.getElementById('raoakSlider');
var raoakSliderValue = document.getElementById('txtRaoakValue');

var stringReplacementDefinitions ={
    '[value]': 'values[handle]',
    '[revenuesSlider]': 'document.getElementById(\'revenuesSlider\').noUiSlider.get()',
    '[rwaSlider]': 'document.getElementById(\'rwaSlider\').noUiSlider.get()',
    '[sowSlider]':'document.getElementById(\'sowSlider\').noUiSlider.get()',
    '[sowCashSlider]':'document.getElementById(\'sowCashSlider\').noUiSlider.get()',
    '[sowNonCashSlider]':'document.getElementById(\'sowNonCashSlider\').noUiSlider.get()',
    '[cashRwaOverLoanVolSlider]':'document.getElementById(\'cashRwaOverLoanVolSlider\').noUiSlider.get()',
    '[nonCashRwaOverLoanVolSlider]':'document.getElementById(\'nonCashRwaOverLoanVolSlider\').noUiSlider.get()',
};

var slidersLoaded=false;
var updateInProgress=false;

function replaceStrings(mainString, replaceArray){
    Object.keys(replaceArray).forEach(function (key) {
        mainString=mainString.replace(key,replaceArray[key]);
    });
    return mainString;
}

$('.slider').each(function(){
    noUiSlider.create(this, {
        start: [$(this).data('startvalue')],
        connect: true,
        step: $(this).data('step'),
        range: {
            'min': $(this).data('minvalue'),
            'max': $(this).data('maxvalue')
        }
    });

    this.noUiSlider.on('update', function( values, handle ){
        var formatString =this.target.dataset.format;
        if (formatString==null)
            formatString='0,0';
        $('#'+$('#'+this.target.id).data('textvalue')).html(numeral(values[handle]).format(formatString));
        if (updateInProgress)
            return;
        var affectedItemString=this.target.dataset.affects.split(',').map(function(item) {
            return item.trim();
        });
        var affectformulaStrings=this.target.dataset.affectformula.split(',');

        if ((this.target.id==='sowSlider')&&(slidersLoaded)) {
            syncronizeSowSliders();
            // document.getElementById('previousSowValue').value=values[handle];
        }

        for (i=0; i<affectedItemString.length;i++) {
            var affectedItem=document.getElementById(affectedItemString[i]);
            var evalMetric=affectformulaStrings[i];
            evalMetric=replaceStrings(evalMetric,stringReplacementDefinitions);
            if (slidersLoaded){
                updateInProgress=true;
                var targetValue=eval(evalMetric);
                if (targetValue > affectedItem.dataset.maxvalue && targetValue!== Infinity) {
                    affectedItem.noUiSlider.updateOptions({range: {min:0, max:targetValue}});
                    affectedItem.dataset.maxvalue=targetValue;
                }
                affectedItem.noUiSlider.set(targetValue);
                if ((affectedItemString[i]==='sowSlider' && (this.target.id!=='sowCashSlider' && this.target.id!=='sowNonCashSlider')))
                    syncronizeSowSliders();
                // if ((affectedItemString[i]==='sowSlider'))
                //     document.getElementById('previousSowValue').value=targetValue;
                updateInProgress=false;
            }
        }


    });
});
slidersLoaded=true;

function syncronizeSowSliders() {
    var inputItem = document.getElementById('initialSowValue');
    var initialValue = parseFloat(inputItem.value);
    var newValue = parseFloat(document.getElementById('sowSlider').noUiSlider.get());
    var cashSlider = document.getElementById('sowCashSlider');
    var nonCashSlider = document.getElementById('sowNonCashSlider');
    var oldCashValue = parseFloat(document.getElementById('initialCashSowValue').value);
    var oldNonCashValue = parseFloat(document.getElementById('initialNonCashSowValue').value);

    var newCashValue, newNonCashValue;
    if (newValue !== initialValue) {
        if (newValue > initialValue) {
            newCashValue = oldCashValue + (((newValue - initialValue) / (100 - initialValue)) * (100 - oldCashValue));
            newNonCashValue = oldNonCashValue + (((newValue - initialValue) / (100 - initialValue)) * (100 - oldNonCashValue));
        }
        else {
            newCashValue = oldCashValue - (((initialValue - newValue) / (initialValue)) * (oldCashValue));
            newNonCashValue = oldNonCashValue - (((initialValue - newValue) / (initialValue)) * (oldNonCashValue));
        }

        cashSlider.noUiSlider.set(newCashValue);
        nonCashSlider.noUiSlider.set(newNonCashValue);

    }
}


$('#sowDetails').hide();
$('#rwaDetails').hide();


$('#sowExpand').on('click touch', function(e) {
    var item=$('#sowDetails');
    if (item.css('display')==='none')
        item.fadeIn();
    else
        item.fadeOut();
    }
);

$('#rwaExpand').on('click touch', function(e) {
    var item=$('#rwaDetails');
    if (item.css('display')==='none')
        item.fadeIn();
    else
        item.fadeOut();
    }
);