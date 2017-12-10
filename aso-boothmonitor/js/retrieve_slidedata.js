/**
 * Created by yoshi2430jp on 17/12/09.
 */
$(function(){
    //params: type, category, subcategory
    setStaticData('insta', 'food');
    setStaticData('insta', 'human');
    setStaticData('insta', 'facility');
    setStaticData('face', 'md');
    setStaticData('face', 'smgr');
})

/*
 set static contents
*/
function setStaticData(cat, sub){

    var isProduction = location.hostname.match(/prd/) ? 1 : 0;
    var apiUrl = isProduction ?
        "https://prod.fj-log.com/admin/api/retrieve_static_data.php":
        "https://www.fj-log.com/admin/api/retrieve_static_data.php";

    $.ajax({
        type: 'GET', //couldn't use POST in jsonp protocol
        data:{
            "category"     : cat,
            "subcategory"  : sub
        },
        url: apiUrl,
        async:false,
        cache:false,
        dataType: 'jsonp',
        jsonpCallback: 'callbackFunc',
        success: function(data, dataType){
            console.log(data);
            var imgPrefix = isProduction ?
                'https://s3-ap-northeast-1.amazonaws.com/prd-fuyufes2017/img/std/':
                'https://s3-ap-northeast-1.amazonaws.com/fuyufes2017/img/std/';
            if(cat == 'insta'){
                for (var i = 0; i < data.length; i++) {
                    $('.name_'  + cat + '_' + sub + '_'+ i).text(data[i]["user_name"]);
                    $('.score_' + cat + '_' + sub + '_'+ i).text(data[i]["score"]);
                    //$('.img_'   + cat + '_' + sub + '_'+ i).attr("data-background", imgPrefix + data[i]["image_name"]);
                    $('.img_'   + cat + '_' + sub + '_'+ i).css({ 'background-image': 'url(' + imgPrefix + data[i]["image_name"] + ')' });
                }
            } else {
                for (var i = 0; i < data.length; i++) {
                    $('.name_'  + cat + '_' + sub + '_'+ i).text(data[i]["user_name"]);
                    $('.score_' + cat + '_' + sub + '_'+ i).text(data[i]["score"]);
                    //$('.img_'   + cat + '_' + sub + '_'+ i).attr("data-src", imgPrefix + data[i]["image_name"]);
                    $('.img_'   + cat + '_' + sub + '_'+ i).attr("src", imgPrefix + data[i]["image_name"]);
                }
            }
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
    //console.log(jsonData);
    if(jsonData.length == 0) return false;

//    var isProduction = location.hostname.match(/prd/) ? 1 : 0;
//    var imgPrefix = isProduction ?
//        'https://s3-ap-northeast-1.amazonaws.com/prd-fuyufes2017/img/std/':
//        'https://s3-ap-northeast-1.amazonaws.com/fuyufes2017/img/std/';
//    var cat = jsonData[0]['category'];
//    var sub = jsonData[0]['sub_category'];
//
//    if(cat == 'insta'){
//        for (var i = 0; i < jsonData.length; i++) {
//            $('.name_'  + cat + '_' + sub + '_'+ i).text(jsonData[i]["user_name"]);
//            $('.score_' + cat + '_' + sub + '_'+ i).text(jsonData[i]["score"]);
//            //$('.img_'   + cat + '_' + sub + '_'+ i).attr("data-background", imgPrefix + jsonData[i]["image_name"]);
//            $('.img_'   + cat + '_' + sub + '_'+ i).css({ 'background-image': 'url(' + imgPrefix + jsonData[i]["image_name"] + ')' });
//        }
//    } else {
//        for (var i = 0; i < jsonData.length; i++) {
//            $('.name_'  + cat + '_' + sub + '_'+ i).text(jsonData[i]["user_name"]);
//            $('.score_' + cat + '_' + sub + '_'+ i).text(jsonData[i]["score"]);
//            //$('.img_'   + cat + '_' + sub + '_'+ i).attr("data-src", imgPrefix + jsonData[i]["image_name"]);
//            $('.img_'   + cat + '_' + sub + '_'+ i).attr("src", imgPrefix + jsonData[i]["image_name"]);
//        }
//    }
};