/**
 * Created by yoshi2430jp on 17/12/09.
 */
$(function(){
    //timer: "data load every 30sec"
    setInterval('setStaticData()', 10000);
})

/*
 set static contents
*/
function setStaticData(){

    var isProduction = location.hostname.match(/prd/) ? 1 : 0;
    isProduction = 1;

    var apiUrl = isProduction ?
        "https://prd.fj-log.com/admin/api/retrieve_static_data.php":
        "https://www.fj-log.com/admin/api/retrieve_static_data.php";

    $.ajax({
        type: 'GET', //couldn't use POST in jsonp protocol
        url: apiUrl,
        async:true,
        cache:false,
        dataType: 'jsonp',
        jsonpCallback: 'callbackFunc',
        success: function(data, dataType){
//            console.log(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            //alert('Error : ' + errorThrown);
            $("#XMLHttpRequest").html("XMLHttpRequest : " + XMLHttpRequest.status);
            $("#textStatus").html("textStatus : " + textStatus);
            $("#errorThrown").html("errorThrown : " + errorThrown);
        }
    });
}

callbackFunc = function(jsonData){
    console.log(jsonData);
    if(jsonData.length == 0) return false;

    var isProduction = location.hostname.match(/prd/) ? 1 : 0;
    isProduction = 1;

    var imgPrefix = isProduction ?
        'https://s3-ap-northeast-1.amazonaws.com/prd-fuyufes2017/img/std/':
        'https://s3-ap-northeast-1.amazonaws.com/fuyufes2017/img/std/';

    for(var i = 0; i < jsonData.length; i++){
        if(jsonData[i].length == 0) continue;

        for(var j = 0; j < jsonData[i].length; j++){
            var cat = jsonData[i][0]["category"];
            var sub = jsonData[i][0]["sub_category"];

            if(cat == 'insta'){
                $('.name_'  + cat + '_' + sub + '_'+ j).text(jsonData[i][j]["user_name"]);
                $('.score_' + cat + '_' + sub + '_'+ j).text(jsonData[i][j]["score"]);
                //$('.img_'   + cat + '_' + sub + '_'+ j).attr("data-background", imgPrefix + jsonData[i][j]["image_name"]);
                $('.img_'   + cat + '_' + sub + '_'+ j).css({ 'background-image': 'url(' + imgPrefix + jsonData[i][j]["image_name"] + '-thumbnail.jpeg)' });
            } else {
                $('.name_'  + cat + '_' + sub + '_'+ j).text(jsonData[i][j]["user_name"]);
                $('.score_' + cat + '_' + sub + '_'+ j).text(jsonData[i][j]["score"]);
                //$('.img_'   + cat + '_' + sub + '_'+ j).attr("data-src", imgPrefix + jsonData[i][j]["image_name"]);
                $('.img_'   + cat + '_' + sub + '_'+ j).attr("src", imgPrefix + jsonData[i][j]["image_name"] + '-thumbnail.jpeg');
            }
        }
    }
};