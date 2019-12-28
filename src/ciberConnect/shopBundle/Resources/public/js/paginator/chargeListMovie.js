
class GetListMovie{
  constructor(paActual=0,paDestino=0){
    this.chargeList(paActual,paDestino);
  }
  chargeList(paActual,paDestino){
    console.log("Hola mundo desde otro lugar jaja");
    // $.ajax({
    //   url: '/modalList/'+id,
    //   type: 'GET',
    //   beforeSend: function() {
    //     $('body').css('height', "100%");
    //     $('body').css('overflow', "hidden");
    //     $('.loading').removeClass('hide');
    //   },
    //   success: function send(data) {
    //   },
    //   complete: function() {
    //
    //   },
    //   error: function (xhr, thrownError) {
    //     $('.loading').addClass('hide');
    //     $('body').css('overflow', "visible");
    //     if (!window.history.state) {
    //       history.replaceState(null, null, '/');
    //     }
    //     window.history.back();
    //     alert("ERROR: "+xhr.status+", Seccion "+thrownError);
    //   }
    // });
  }
}

// new GetListMovie();
export default GetListMovie;
