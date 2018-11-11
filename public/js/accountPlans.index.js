String.prototype.replaceAll = function(search, replacement) {
    let target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

function numberFormat(pNumber,pDecimalPoints){
    return pNumber.toFixed(pDecimalPoints);
}

$(document).ready(function () {

    let openModalWindowId = '';

    let opportunities = [
        {
            index: 0,
            opportunityId: 1,
            opportunityDivId: 'opportunity1',
            companyId: 1,
            companyClass: 'company-1',
            companyName: 'Dynamic Automotive Group',
            productName: 'Trx Banking Platform',
            status: 'Recommended',
            opportunityText: 'This is a target customer for Transaction Banking Platform',
            opportunityReason: 'Pushed by TB Team',
            opportunityStatus: 'Awaiting approval',
            opportunityAcceptButtonWindowId: 'standardOpportunityAcceptWindow',
            opportunityRejectButtonWindowId: 'standardOpportunityRejectWindow',
            opportunityProceedButtonWindowId: 'opportunityFirstProceedStepWindow',
            hiddenElements: ['proceedButton'],
            visibleElements: [],
            opportunityAcceptStatus: {
                status: 'Accepted',
                opportunityStatus: 'Accepted by xx',
                hiddenElements: ['acceptButton'],
                visibleElements: ['proceedButton']
            },
            opportunityRejectStatus: {
                status: 'Inactivated',
                opportunityStatus: 'Rejected by xx',
                hiddenElements: ['rejectButton', 'proceedButton', 'acceptButton']
            },
            opportunityFirstStepForwardStatus: {
                status: 'Client Contacted',
                opportunityStatus: 'Client contacted on xxx',
                hiddenElements: ['acceptButton'],
                visibleElements: ['proceedButton'],
                opportunityProceedButtonWindowId: 'opportunitySecondProceedStepWindow'
            },
            opportunitySecondStepForwardStatus: {
                status: 'Proposal Submitted',
                opportunityStatus: 'Proposal Submitted on xxx',
                hiddenElements: ['acceptButton'],
                visibleElements: ['proceedButton'],
                opportunityProceedButtonWindowId: 'opportunityThirdProceedStepWindow'
            },
            opportunityThirdStepForwardStatus: {
                status: 'Success',
                opportunityStatus: 'Sales done on xx',
                hiddenElements: ['acceptButton', 'proceedButton']
            },
        },
        {
            index: 1,
            opportunityId: 2,
            opportunityDivId: 'opportunity2',
            companyId: 1,
            companyClass: 'company-1 company-2',
            companyName: 'Dynamic Manufacturing A.Ş',
            productName: 'Spot Loan',
            status: 'Recommended',
            opportunityText: 'Cross-sell - it is likely that this customer uses this product from other banks',
            opportunityReason: 'Analytics Engine Recommendation',
            opportunityStatus: 'Awaiting approval',
            hiddenElements: ['proceedButton'],
            visibleElements: [],
            opportunityAcceptButtonWindowId: 'standardOpportunityAcceptWindow',
            opportunityRejectButtonWindowId: 'standardOpportunityRejectWindow',
            opportunityProceedButtonWindowId: 'opportunityFirstProceedStepWindow',
            opportunityAcceptStatus: {
                status: 'Accepted',
                opportunityStatus: 'Accepted by xx',
                hiddenElements: ['acceptButton'],
                visibleElements: ['proceedButton']
            },
            opportunityRejectStatus: {
                status: 'Inactivated',
                opportunityStatus: 'Rejected by xx',
                hiddenElements: ['rejectButton', 'proceedButton', 'acceptButton']
            },
            opportunityFirstStepForwardStatus: {
                status: 'Client Contacted',
                opportunityStatus: 'Client contacted on xxx',
                hiddenElements: ['acceptButton'],
                visibleElements: ['proceedButton'],
                opportunityProceedButtonWindowId: 'opportunitySecondProceedStepWindow'
            },
            opportunitySecondStepForwardStatus: {
                status: 'Proposal Submitted',
                opportunityStatus: 'Proposal Submitted on xxx',
                hiddenElements: ['acceptButton'],
                visibleElements: ['proceedButton'],
                opportunityProceedButtonWindowId: 'opportunityThirdProceedStepWindow'
            },
            opportunityThirdStepForwardStatus: {
                status: 'Success',
                opportunityStatus: 'Sales done on xx',
                hiddenElements: ['acceptButton', 'proceedButton']
            },

        },
        {
            index: 2,
            opportunityId: 3,
            opportunityDivId: 'opportunity3',
            companyId: 1,
            companyClass: 'company-1 company-3',
            companyName: 'Dynamic Sales Ltd',
            productName: 'Non-Cash Loan',
            status: 'Inactivated',
            opportunityText: 'Cross-sell - it is likely that this customer uses this product from other banks',
            opportunityReason: 'Analytics Engine Recommendation',
            opportunityStatus: 'Awaiting approval',
            hiddenElements: ['rejectButton', 'acceptButton', 'proceedButton'],
            visibleElements: [],
            opportunityAcceptButtonWindowId: 'standardOpportunityAcceptWindow',
            opportunityAcceptStatus: {
                status: 'Accepted',
                opportunityStatus: 'Accepted by xx',
                hiddenElements: ['acceptButton'],
                visibleElements: ['proceedButton']
            }
        }


    ];

    let companies = {
        'company-1': {
            id: 'company-1',
            name: 'Dynamic Automotive Group',
            strategy: 'Recover',
            penetratedProductIds: [1, 2, 3, 4],
            kpis: {
                raoak: {name:'Raoak', pastYear:"3.41", ytd:"3.75", target:"4.00"},
                sow: {name:'Sow', pastYear:"2.4", ytd:"11.2", target:"15.0"},
                nlr : {name:'Non Lending Ratio',pastYear:"36.0", ytd:"34.3", target:"40.0"},
                pv : {name:'Product Variety',pastYear:"5", ytd:"6", target:"7"}
            }
        },
        'company-2': {id: 'company-2', name: 'Dynamic Manufacturing A.Ş', strategy: 'Nurture',
            kpis: {
                raoak: {name:'Raoak', pastYear:"2.25", ytd:"4.12", target:"4.00"},
                sow: {name:'Sow', pastYear:"26.4", ytd:"28.2", target:"20.0"},
                nlr : {name:'Non Lending Ratio',pastYear:"45.0", ytd:"48.3", target:"40.0"},
                pv : {name:'Product Variety',pastYear:"9", ytd:"12", target:"7"}
            }

        },
        'company-3': {id: 'company-3', name: 'Dynamic Sales Ltd', strategy: 'Acquire',
            kpis: {
                raoak: {name:'Raoak', pastYear:"15.42", ytd:"18.31", target:"4.00"},
                sow: {name:'Sow', pastYear:"1.1", ytd:"0.2", target:"5.0"},
                nlr : {name:'Non Lending Ratio',pastYear:"87.0", ytd:"86.3", target:"40.0"},
                pv : {name:'Product Variety',pastYear:"3", ytd:"3", target:"7"}
            }

        },
        'company-4': {id: 'company-4', name: 'Dynamic Insurance Ltd', strategy: 'Grow',
            kpis: {
                raoak: {name:'Raoak', pastYear:"5.20", ytd:"5.12", target:"4.00"},
                sow: {name:'Sow', pastYear:"8.6", ytd:"11.2", target:"15.0"},
                nlr : {name:'Non Lending Ratio',pastYear:"35.0", ytd:"36.3", target:"40.0"},
                pv : {name:'Product Variety',pastYear:"4", ytd:"5", target:"7"}
            }

        },
    };

    let products = [
        {
            id: 1,
            name: 'TL Demand Deposits',
            penetrationActionAllowed: false,
            volumeActionAllowed: true,
            spreadActionAllowed: true
        },
        {
            id: 2,
            name: 'FX Demand Deposits',
            penetrationActionAllowed: false,
            volumeActionAllowed: true,
            spreadActionAllowed: true
        },
        {
            id: 3,
            name: 'Term Deposits',
            penetrationActionAllowed: true,
            volumeActionAllowed: true,
            spreadActionAllowed: true
        },
        {
            id: 4,
            name: 'Spot Loans',
            penetrationActionAllowed: true,
            volumeActionAllowed: true,
            spreadActionAllowed: true
        },
        {
            id: 5,
            name: 'Investment Loans',
            penetrationActionAllowed: true,
            volumeActionAllowed: true,
            spreadActionAllowed: true
        },
        {
            id: 6,
            name: 'Overdraft',
            penetrationActionAllowed: true,
            volumeActionAllowed: true,
            spreadActionAllowed: true
        },
        {
            id: 7,
            name: 'Transaction Banking Platform',
            penetrationActionAllowed: true,
            volumeActionAllowed: false,
            spreadActionAllowed: false
        },
    ];

    let activeOpportunityIndex = 0;

    let replacementKeys = {
        '%opportunityDivId%': 'opportunityDivId',
        '%productName%': 'productName',
        '%companyName%': 'companyName',
        '%companyClass%': 'companyClass',
        '%opportunityText%': 'opportunityText',
        '%opportunityReason%': 'opportunityReason',
        '%opportunityStatus%': 'opportunityStatus',
        '%opportunityAcceptButtonWindowId%': 'opportunityAcceptButtonWindowId',
        '%opportunityRejectButtonWindowId%': 'opportunityRejectButtonWindowId',
        '%opportunityProceedButtonWindowId%': 'opportunityProceedButtonWindowId',
        '%deadLine%': 'deadLine'
    };

    function renderOpportunity(hostDivId, opportunity, refresh = false) {
        let html = '';
        if (refresh) {
            html = $('#opportunityTemplate .opportunity-card').html();
            document.getElementById(hostDivId).innerHTML = '';
        }
        else
            html = $('#opportunityTemplate').html();

        Object.keys(replacementKeys).forEach(function (key) {
                let newText = '';
                if (opportunity.hasOwnProperty(replacementKeys[key]))
                    newText = opportunity[replacementKeys[key]];
                html = html.replace(key, newText);
            }
        );
        html = html.replace(/%opportunityIndex%/g, opportunity['index']);
        $('#' + hostDivId).append(html);
        //hide required elements
        for (let elementId of opportunity['hiddenElements']) {
            $('#' + opportunity.opportunityDivId + ' #' + elementId).hide()
        }
        //show required elements
        for (let elementId of opportunity['visibleElements']) {
            $('#' + opportunity.opportunityDivId + ' #' + elementId).show()
        }
        //show inactive wrapper if opportunity status is inactivated
        if (opportunity.status === 'Inactivated')
            $('#' + opportunity.opportunityDivId + ' .inactivated-wrapper').removeClass('d-none');

        //show success wrapper if opportunity status is success
        if (opportunity.status === 'Success') {
            $('#' + opportunity.opportunityDivId + ' .successfulClosure-wrapper').removeClass('d-none');
            $('#' + opportunity.opportunityDivId + ' .opportunity-card-content-detail').hide();
            $('#' + opportunity.opportunityDivId + ' .opportunity-button').hide();
        }

        //show success wrapper if opportunity status is success
        if (opportunity.status === 'Failed') {
            $('#' + opportunity.opportunityDivId + ' .failClosure-wrapper').removeClass('d-none');
            $('#' + opportunity.opportunityDivId + ' .opportunity-card-content-detail').hide();
            $('#' + opportunity.opportunityDivId + ' .opportunity-button').hide();
        }
    }

    for (let i = 0; i < opportunities.length; i++) {
        renderOpportunity('opportunity-wrapper', opportunities[i]);
    }

    //fill in companies
    let companySliderWrapper = $('#company-swiper .swiper-wrapper');
    let companySliderTemplate = companySliderWrapper.html();
    companySliderWrapper.empty();

    let companySlideKeys = {
        '%COMPANY-NAME%': 'name',
        '%COMPANY-ID%': 'id',
        '%STRATEGY%': 'strategy'
    };

    let selectedCompanyKey = Object.keys(companies)[0];

    Object.keys(companies).forEach(function (companyKey) {
        let slideText = companySliderTemplate;
        Object.keys(companySlideKeys).forEach(function (searchKey) {
            if (companies[companyKey].hasOwnProperty(companySlideKeys[searchKey]))
                slideText = slideText.replaceAll(searchKey, companies[companyKey][companySlideKeys[searchKey]])
        });
        companySliderWrapper.append(slideText);

    });

    $('.company-wrapper[data-company-id="' + selectedCompanyKey + '"]').addClass('selected');


    //fill in KPIs
    let kpiZone=$('.kpi-zone');
    let kpiTemplate=kpiZone.html();
    kpiZone.empty();

    let kpiKeys = {
        '%KPI-NAME%': 'name',
        '%YEARTODATE-VALUE%': 'ytd',
        '%TARGET-VALUE%': 'target',
        '%PAST-YEAR-VALUE%': 'pastYear'
    };

    Object.keys(companies).forEach(function(companyKey) {
        let i=0;
        if (companies[companyKey].hasOwnProperty('kpis'))
            Object.keys(companies[companyKey]['kpis']).forEach(function(kpiKey) {
                let kpiText = kpiTemplate;
                kpiText = kpiText.replaceAll('%COMPANY-ID%',companyKey);
                Object.keys(kpiKeys).forEach(function(searchKey){
                    kpiText = kpiText.replaceAll(searchKey,companies[companyKey]['kpis'][kpiKey][kpiKeys[searchKey]]);
                });
                if (i===0) kpiText=$(kpiText).addClass('first'); //add first class to every company
                i++;
                if (companyKey!=selectedCompanyKey)
                {
                    kpiText=$(kpiText).addClass('d-none');
                }
                kpiZone.append(kpiText);
            });
    });

    let companySwiper = new Swiper('#company-swiper', {
        slidesPerView: 'auto',
        centeredSlides: false,
        spaceBetween: 20,
        freeMode: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });
    let opportunitySwiper = new Swiper('#opportunity-swiper', {
        slidesPerView: 'auto',
        centeredSlides: false,
        spaceBetween: 0,
        freeMode: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });

    $(document.body).on('click touch', '.opportunity-button', function () {
        let opportunity = opportunities[$(this).data('opportunityindex')];
        activeOpportunityIndex = opportunity.index;
        openModalWindowId = $(this).data('target-window');
        if (openModalWindowId !== '') {
            $('#' + openModalWindowId + ' #modalWindowTitle').html(opportunity.productName);
            $('#' + openModalWindowId).modal('show');
        }
    });

    $('.company-wrapper').on('click touch', function () {
        if (!$(this).hasClass('selected')) {
            var currentCompanyWrapper = $('.company-wrapper.selected');
            var currentCompany = currentCompanyWrapper.data('company-id');

            var targetCompany = $(this).data('company-id');

            //mark company as selected visually
            currentCompanyWrapper.removeClass('selected');
            $(this).addClass('selected');

            //hide current companys's kpis and show selecteds
            $('.' + currentCompany).addClass('d-none');
            $('.' + targetCompany).removeClass('d-none');
        }
    });

    $('.window-accept-button').on('click touch', function () {
        let requiredFieldSelector = '';
        let cancelOperation = false;
        if ($(this).data('required-field-selector')) {
            requiredFieldSelector = $(this).data('required-field-selector');
            $(requiredFieldSelector).each(function () {
                if ($(this).val() == null || $(this).val() === '') {
                    alert('Some fields are missing');
                    cancelOperation = true;
                }
            })
        }
        ;
        if (cancelOperation)
            return;

        let activeOpportunity = opportunities[activeOpportunityIndex];
        let targetStatus = $(this).data('target-status');
        let elementsTargetStatus = $(this).data('update-status');
        let userInputStatuses = {};
        let userInputs = document.querySelectorAll('#' + openModalWindowId + ' .user-input');
        for (let i = 0; i < userInputs.length; i++) {
            userInputStatuses[userInputs[i].dataset.property] = userInputs[i].value;
        }
        //this is in descending order of priority
        let newStatus = {...userInputStatuses, ...activeOpportunity[targetStatus], ...elementsTargetStatus};
        Object.keys(newStatus).forEach(function (key) {
                activeOpportunity[key] = newStatus[key];
            }
        );


        renderOpportunity(activeOpportunity.opportunityDivId, activeOpportunity, true);
        $('.modal').modal('hide');
    });

    $('#buttonAddOpportunity').on('click touch', function () {
        $('.hide-if-no-target').hide(); //hide target elements

        //fill in companies
        let companySelector = $('#company-selector');
        companySelector.empty();
        let optionsTextCompanies = '<option value="" disabled selected hidden>Company...</option>';
        companies.forEach(function (e) {
            let penetratedProductIds = (e.hasOwnProperty('penetratedProductIds')) ? e['penetratedProductIds'] : [];
            optionsTextCompanies += '<option data-company-id="' + e.id + '" data-penetrated-product-ids="[' +
                penetratedProductIds.toString()
                + ']">' + e.name + '</option>';
        });
        companySelector.append(optionsTextCompanies);

        $('#product-selector').prop('disabled', 'disabled');

        $('#addOpportunityWindow').modal('show');
    });

    selectedProductId = 0;

    $('#company-selector').on('change', function () {
        let productSelector = $('#product-selector');
        productSelector.prop('disabled', false);
        productSelector.empty();
        //fill in products
        let optionsTextProducts = '<option value="" disabled selected hidden>Product...</option>';
        products.forEach(function (e) {
            let alreadyPenetratedProducts = $('#company-selector option:selected').data('penetrated-product-ids');
            let penetrationAllowed = (e.penetrationActionAllowed && alreadyPenetratedProducts.indexOf(e.id) === -1);
            let volumeAllowed = e.volumeActionAllowed;
            let spreadAllowed = e.spreadActionAllowed;
            optionsTextProducts += '<option value="' + e.id + '"' +
                ' data-penetration-allowed="' + penetrationAllowed + '"' +
                ' data-volume-allowed="' + volumeAllowed + '"' +
                ' data-spread-allowed="' + spreadAllowed + '"' +
                '">' + e.name + '</option>';
        });
        productSelector.append(optionsTextProducts);
        if (selectedProductId > 0) {
            productSelector.val(selectedProductId);
        }
        renderActionTypeButtons();
    });

    $('#product-selector').on('change', function () {
        selectedProductId = $('#product-selector').val();
        renderActionTypeButtons();
    });

    function renderActionTypeButtons() {
        let activeActionId = $('#btn-action-type .active').attr('id'),
            selectedOption = $('#product-selector option:selected'),
            penetrationAllowed = selectedOption.data('penetration-allowed'),
            volumeAllowed = selectedOption.data('volume-allowed'),
            spreadAllowed = selectedOption.data('spread-allowed'),
            penetrationButton = $('#btn-Penetration'),
            volumeButton = $('#btn-Volume'),
            spreadButton = $('#btn-Spread');

        (penetrationAllowed === false ? penetrationButton.addClass('disabled') : penetrationButton.removeClass('disabled'));
        (volumeAllowed === false ? volumeButton.addClass('disabled') : volumeButton.removeClass('disabled'));
        (spreadAllowed === false ? spreadButton.addClass('disabled') : spreadButton.removeClass('disabled'));

        if ((activeActionId === 'btn-Penetration') && penetrationAllowed === false) {
            if (!volumeButton.hasClass('disabled'))
                volumeButton.button('toggle');
            if (!spreadButton.hasClass('disabled'))
                spreadButton.button('toggle');
        }

        if ((activeActionId === 'btn-Volume') && volumeAllowed === false) {
            if (!penetrationButton.hasClass('disabled'))
                penetrationButton.button('toggle');
            if (!spreadButton.hasClass('disabled'))
                spreadButton.button('toggle');
        }

        if ((activeActionId === 'btn-Spread') && spreadAllowed === false) {
            if (!penetrationButton.hasClass('disabled'))
                penetrationButton.button('toggle');
            if (!spreadButton.hasClass('disabled'))
                spreadButton.button('toggle');
        }

    }

    $('#btn-Penetration').on('change', function () {
        if (!($(this).hasClass('disabled')))
            $('.hide-if-no-target').hide()
    });

    $('#btn-Volume, #btn-Spread').on('click touch change', function () {
        $('.hide-if-no-target').show()
    });

    $('#loading-splash').hide();

});