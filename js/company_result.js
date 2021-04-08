jQuery(document).ready(function () {
    $('html').on('change', '#plan_fact_company_result #month_change', function (e) {
        window.location = '/plan_fact/company_result/' + $(this).val();
    });
});