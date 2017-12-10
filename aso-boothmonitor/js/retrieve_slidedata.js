/**
 * Created by yoshi2430jp on 17/12/09.
 */
$(function(){
        //params: type, category, subcategory
        setStaticData('insta', 'food');
//    setStaticData('insta', 'human');
//    setStaticData('insta', 'facility');
//    setStaticData('face', 'md');
//    setStaticData('face', 'smgr');
})

/*
 set static contents
*/
function setStaticData(cat, sub){

    //AJAX通信(ver1.8...)
    var request = $.ajax({
        type: 'GET', //couldn't use POST in jsonp protocol
        data:{
            "category"     : cat,
            "subcategory"  : sub
        },
        url: "https://www.fj-log.com/admin/api/retrieve_static_data.php",
        async:true,
        cache:false,
        dataType: 'jsonp',
        jsonpCallback: 'jsonp_data',
        timeout: 3000
    });

    request.done(function(data, textStatus) {
        console.log(data);
        var img_prefix = 'https://s3-ap-northeast-1.amazonaws.com/fuyufes2017/img/std/';

        for (var i = 0; i < data.length; i++) {
            $('.name_'  + cat + '_' + sub + '_'+ i).text(data[i]["user_name"]);
            $('.score_' + cat + '_' + sub + '_'+ i).text(data[i]["score"]);
            $('.img_'   + cat + '_' + sub + '_'+ i).attr("data-background", img_prefix + data[i]["image_name"]);
        }
    });

    request.fail(function(jqXHR, textStatus, errorThrown) {
        console.dir("----fail.----");
        console.dir("fail-jqXHR"+jqXHR);             //オブジェクト
        console.dir("fail-textStatus:"+textStatus);  //通信ステータス
        console.dir("fail-errorThrown"+errorThrown); //エラーメッセージ
    });

    request.always(function(data, textStatus) {
        console.dir("----always.----");
        console.dir("always-data:"+data);              //データ
        console.dir("always-textStatus:"+textStatus);  //通信ステータス
    });

}