  $(document).ready(function() {
    // $( "th:first" ).addClass('asc selected');
    // $( "th:first" ).addClass('descdef');
    // $( "th:first" ).addClass('descdef selected');

 $('th.sortable').each(function(col) {
  $(this).hover(
  function() { $(this).addClass('focus'); },
  function() { $(this).removeClass('focus'); }
 );
  $(this).click(function() {
    $(this).removeClass('sortable');
    if ($(this).is('.asc')) {
    $(this).removeClass('asc');
    $(this).addClass('desc selected');
    sortOrder = -1;
   }
   else {
    $(this).addClass('asc selected');
    $(this).removeClass('desc');
    sortOrder = 1;
   }
   $(this).siblings().removeClass('asc selected');
   $(this).siblings().removeClass('desc selected');
       if ($(this).siblings().not('.sortable'))
       {
       $(this).siblings().addClass('sortable');
 }
   var arrData = $('table').find('tbody#tosort >tr:has(td)').get();
   console.log(arrData);
   arrData.sort(function(a, b) {
    var val1 = $(a).children('td').eq(col).text().toUpperCase();
    var val2 = $(b).children('td').eq(col).text().toUpperCase();
    if($.isNumeric(val1) && $.isNumeric(val2))
    return sortOrder == 1 ? val1-val2 : val2-val1;
    else
     return (val1 < val2) ? -sortOrder : (val1 > val2) ? sortOrder : 0;
   });
   $.each(arrData, function(index, row) {
    $('tbody#tosort').append(row);
   });
  });
 });
});