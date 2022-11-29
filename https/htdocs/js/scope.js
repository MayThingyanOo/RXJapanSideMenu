if (!$.cps_scopes) {
    $.cps_scopes = {};
}

$('script[type="text/json"]._scope').remove().each(function () {
    var $this = $(this);
    var name = $this.data('scope');
    if (!$.cps_scopes[name]) {
        $.cps_scopes[name] = {};
    }
    var scope = JSON.parse($this.text());
    for (var vars in scope) {
        if (scope.hasOwnProperty(vars)) {
            $.cps_scopes[name][vars] = scope[vars];
        }
    }
});
