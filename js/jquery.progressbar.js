(function() {

var bar = $('.bar');
var percent = $('.percent');

$('form').ajaxForm({

    /*if( !$('#files').val()) { //check empty input filed
        $("#imageRows").html("Are you kidding me?");
            return false;
    }*/

    beforeSend: function() {
        $('#progressbar').show();
        var percentVal = '0%';
        bar.width(percentVal)
        percent.html(percentVal); 
    },
    uploadProgress: function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        bar.width(percentVal)
        percent.html(percentVal);
    },
    complete: function(xhr) {
     bar.width("100%");
     percent.html("100%");
    }
}); 

})();   