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
})