var doAjax = function(e){
  e.stop(); // prevent the form from submitting

  new Request({
    url: window.location + '?isAjax=1',
    method: 'post',
    data: this,
    onRequest: function(){
      $('generate_number').fade('out');
    },
    onSuccess: function(r){
      // the 'r' is response from server
      var resObj = JSON.decode(r);
      
      $('number_box').setStyle('display','block');
      $('generate_number').set('html', resObj.zahl).fade('in');
      $('form_random_number').getElements('input[name=REQUEST_TOKEN]').set('value',resObj.token);
    }
  }).send();
}

addEvent('domready', function(){
  $('form_random_number').addEvent('submit', doAjax);
});