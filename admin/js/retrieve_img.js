/**
 * Created by yoshi2430jp on 17/12/09.
 */
$(function(){
    $("#btnReset").click(function(){
        //params: type, category, subcategory
        setStaticData(0, 'insta', 'food');
//    setStaticData(1, 'insta', 'human');
//    setStaticData(2, 'insta', 'facility');
//    setStaticData(3, 'face', 'md');
//    setStaticData(4, 'face', 'smgr');
    });
})

/*
 get images
*/
function setStaticData($type, $cat, $sub){
  $.ajax({
    type: "POST",
    url: "./api/retrieve_image.php",
    data: {
      "category"     : $cat,
      "subcategory"  : $sub
    },
    success: function(data, dataType){
      console.log(data);
        var img_prefix = 'https://s3-ap-northeast-1.amazonaws.com/fuyufes2017/img/std/';

        for (var i = 0; i < data.length; i++) {
            $('.name'  + $type + i).text(data[i]["user_name"]);
            $('.score' + $type + i).text(data[i]["score"]);
            $('.img'   + $type + i).attr("data-background", img_prefix + data[i]["image_name"]);
        }
      },
    error: function(XMLHttpRequest, textStatus, errorThrown){
      alert('Error : ' + errorThrown);
      $("#XMLHttpRequest").html("XMLHttpRequest : " + XMLHttpRequest.status);
      $("#textStatus").html("textStatus : " + textStatus);
      $("#errorThrown").html("errorThrown : " + errorThrown);
    }
  });
}