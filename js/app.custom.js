/**
 * Created by dpointk on 18/06/2016.
 */


/*
$('input[name="activity"]').change(function () {
    var $this = $(this);
    url = $this.attr("id");
    container = $(".ajax-notifications");
    loadURL(url, container);
    $this = null
});
$("#activity").click(function (a) {
    var b = $(this);
    b.find(".badge").hasClass("bg-color-red") && (b.find(".badge").removeClassPrefix("bg-color-"),b.find(".badge").text("0"));
    b.next(".ajax-dropdown").is(":visible") ? (b.next(".ajax-dropdown").fadeOut(150), b.removeClass("active")) : (b.next(".ajax-dropdown").fadeIn(150), b.addClass("active"));
    var c = b.next(".ajax-dropdown").find(".btn-group > .active > input").attr("id");
    b = null;
    c = null;
    a.preventDefault()
});*/


var alert_timer;
function checkAlerts()
{
    url = $('.ajax-dropdown button').data('url');
    container = $(".ajax-notifications");

    if (typeof url !== 'undefined') {
        $.ajax({
            "type": "GET",
            "url": url + '?getstatus=1',
            "dataType": "html",
            "cache": !0,
            "beforeSend": function () {
            },
            "success": function (a) {
                result_num = parseInt(a);
                if (result_num > 0) {
                    console.log('ok');
                    $('.activity-dropdown .badge').removeClass('hidden');
                    $('.activity-dropdown .badge').css('backgroundColor', '#ED1C24');
                    $('.activity-dropdown .badge').html(result_num);
                }
                else {
                    $('.activity-dropdown .badge').addClass('hidden');
                    $('.activity-dropdown .badge').css('backgroundColor', '#0091d9');
                    $('.activity-dropdown .badge').html('');
                    console.log('not ok');
                }
            },
            "error": function (c, d, e, f) {
                console.log('Error requesting ' + a + ": " + c.status + ' ' + e + "");
            },
            "async": !0
        });
        alert_timer = setTimeout("checkAlerts()", 60000);
    }
}

function changeLang(lang)
{
    url = '/inc/ajax_lang.php?';
    $.ajax({
        "type": "GET",
        "url": url + 'lang='+lang,
        "dataType": "html",
        "cache": !0,
        "beforeSend": function () {
        },
        "success": function (a) {
            //SUCCESS! reload the page
            //console.log('ok oko ko');
            location.reload();
        }
    });
}


$('.ajax-dropdown button').click(function ($event) {
    var $this = $(this);
    url = $this.data('url');
    loadURL(url, container);
    $this = null;
    $('#ajax_events_last_update').html(moment().format('DD/MM/YYYY HH:mm'));
    checkAlerts();
    $event.preventDefault();

});

$(document).ready(function(){
    alert_timer = setTimeout("checkAlerts()",2000);
});