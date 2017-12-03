/**
 * Created by YoshitakaFujisawa on 17/11/25.
 */
$(function(){
    loadModalCondition();

    $("#btnSave").click(function(){
        //alert( "modal close" );
        updateModalCondition($("#txt-modal-date-from").val(), $("#txt-modal-date-to").val());
        $("#txt-date-from").val($("#txt-modal-date-from").val());
        $("#txt-date-to").val($("#txt-modal-date-to").val());
        $("#modal-example").modal('hide');
    });
})

function loadModalCondition(){
    $.ajax({
        type: "POST",
        url: "./api/modal_condition.php",
        data: {
            "type" : "load"
        },
        success: function(data, dataType){
            console.log(data);
            $("#txt-date-from").val(data[0]['created_from']);
            $("#txt-date-to").val(data[0]['created_to']);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert('Error : ' + errorThrown);
            $("#XMLHttpRequest").html("XMLHttpRequest : " + XMLHttpRequest.status);
            $("#textStatus").html("textStatus : " + textStatus);
            $("#errorThrown").html("errorThrown : " + errorThrown);
        }
    });
}

function updateModalCondition($created_from, $created_to){
    $.ajax({
        type: "POST",
        url: "./api/modal_condition.php",
        data: {
            "type" : "update",
            "created_from"  : $created_from,
            "created_to"    : $created_to
        },
        success: function(data, dataType){
            console.log(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert('Error : ' + errorThrown);
            $("#XMLHttpRequest").html("XMLHttpRequest : " + XMLHttpRequest.status);
            $("#textStatus").html("textStatus : " + textStatus);
            $("#errorThrown").html("errorThrown : " + errorThrown);
        }
    });
}