/**
 * Created by YoshitakaFujisawa on 17/11/25.
 */
$(function(){
    $("#btnReload").click(function(){
        $.ajax({
            type: "POST",
            url: "./api/reload.php",
            //data: postData,
            success: function(data, dataType){
                console.log(data);
                //tbodyを置換
                $("table#ranking tbody").html(data);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error : ' + errorThrown);
                $("#XMLHttpRequest").html("XMLHttpRequest : " + XMLHttpRequest.status);
                $("#textStatus").html("textStatus : " + textStatus);
                $("#errorThrown").html("errorThrown : " + errorThrown);
            }
        });
    });

    $("#btnDisable").click(function(){
        var arrChecks = new Array();
        $("input:checked").each(function() {
            arrChecks.push($(this)[0].id);
        });

        if(arrChecks.length == 0){
            return true;
        }
        var jsonChecks = JSON.stringify(arrChecks);

        $.ajax({
            type: "POST",
            //dataType: "json",
            url: "./api/disable.php",
            data: {"checks": jsonChecks},
            success: function(data, dataType){
                console.log(data);
                //tbodyを置換
                $("table#ranking tbody").html(data);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert('Error : ' + errorThrown);
                $("#XMLHttpRequest").html("XMLHttpRequest : " + XMLHttpRequest.status);
                $("#textStatus").html("textStatus : " + textStatus);
                $("#errorThrown").html("errorThrown : " + errorThrown);
            }
        });
    });
})