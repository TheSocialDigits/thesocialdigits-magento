(function(j){
  j(document).ready(function(){
    j.get('http://demoshop.thesocialdigits.com/index.php/recommendations/configuration',
    function(data){
      data = JSON.parse(data);
      //datasource is set in the javascript file because of unknwon error
      data.datasource = function(products,callback){
        var url = '/index.php/recommendations/list';
        var data = {'products': products};
        j.getJSON(url,data,callback);
      }
      j.thesocialdigits(data);
      //Sends out an event that thesocialdigits' api is now ready
      j(document).trigger('thesocialdigits.ready');
    });
  });
})(jQuery);
jQuery.noConflict();
