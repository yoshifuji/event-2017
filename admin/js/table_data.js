/**
 * Created by YoshitakaFujisawa on 17/11/25.
 */
$(function(){
    $("#btnSearch").click(function(){
        loadData();
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
        deleteData(jsonChecks);

        loadData();
    });
})

function loadData(){
    $.ajax({
        type: "POST",
        url: "./api/search.php",
        data: {
            "chkIncludedLineid"     : $("#chkLineid :checkbox:checked").val(),
            "chkIncludedInactive"   : $("#chkInactive :checkbox:checked").val(),
            "txtDateFrom"           : $("#txtDateFrom :input").val(),
            "txtDateTo"             : $("#txtDateTo :input").val(),
            "slctCategory"          : $("#category option:selected").text(),
            "slctSubCategory"       : $("#subcategory option:selected").text()
        },
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
}

function resetData(){
    $.ajax({
        type: "POST",
        url: "./api/reset.php",
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
}

function deleteData(jsonChecks){
    $.ajax({
        type: "POST",
        url: "./api/disable.php",
        data: {
            "checks": jsonChecks
        },
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
}
