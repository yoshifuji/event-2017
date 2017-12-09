/**
 * Created by yoshi2430jp on 17/12/09.
 */
$(function(){
//  loadModalCondition();
//
//  $("#btnSave").click(function(){
//    //alert( "modal close" );
//    updateModalCondition($("#txt-modal-date-from").val(), $("#txt-modal-date-to").val());
//    $("#txt-date-from").val($("#txt-modal-date-from").val());
//    $("#txt-date-to").val($("#txt-modal-date-to").val());
//    $("#modal-example").modal('hide');
//  });

  alert('hoge');
  setImgs();

})

/*
 get images
*/
function setImgs(){
  $.ajax({
    type: "POST",
    url: "./api/retrieve_image.php",
    success: function(data, dataType){
      console.log(data);

      //TODO: add procedure here

      },
    error: function(XMLHttpRequest, textStatus, errorThrown){
      alert('Error : ' + errorThrown);
      $("#XMLHttpRequest").html("XMLHttpRequest : " + XMLHttpRequest.status);
      $("#textStatus").html("textStatus : " + textStatus);
      $("#errorThrown").html("errorThrown : " + errorThrown);
    }
  });
}