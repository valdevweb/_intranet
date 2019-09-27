var asc = 0;
function sort_table(table, col){
  $('.sortorder').remove();
  if (asc == 2) {asc = -1;} else {asc = 2;}
  var rows = table.tBodies[0].rows;
  var rlen = rows.length;
  var arr = new Array();

  var i, j, cells, clen;
  // fill the array with values from the table
  for(i = 0; i < rlen; i++)
  {
    cells = rows[i].cells;
    clen = cells.length;
    arr[i] = new Array();
    for(j = 0; j < clen; j++) {
      arr[i][j] = cells[j].innerHTML;
    }

  }
// sort the array by the specified column number (col) and order (asc)
arr.sort(function(a, b)
{
  var retval=0;
  var col1 = a[col].toLowerCase()
  var col2 = b[col].toLowerCase()
  // passe cahine de caratère dans fonction qui supprime les liens
  var noLink1=stripLink(col1);
  var noLink2=stripLink(col2);



  var fA=parseFloat(col1);
  var fB=parseFloat(col2);

  if(col1 != col2){
    if((fA==col1) && (fB==col2) ){
      retval=( fA > fB ) ? asc : -1*asc;
    } //numerical
    else{
      if(noLink1 > noLink2 ){
          retval=asc;
      }
      else{
        retval=-1 *asc;
      }
      // retval=(col1 > col2) ? asc : -1 * asc;
    }
  }
  return retval;
// console.log(retval);

});
// console.log(arr);

for(var rowidx=0;rowidx<rlen;rowidx++)
{
  for(var colidx=0;colidx<arr[rowidx].length;colidx++){
    table.tBodies[0].rows[rowidx].cells[colidx].innerHTML=arr[rowidx][colidx];
  }
}

hdr = table.rows[0].cells[col];
if (asc == -1) {
  $(hdr).addClass('asc');
  $(hdr).removeClass('desc');
} else {
  $(hdr).addClass('desc');
  $(hdr).removeClass('asc');
}
}



 function stripLink(txt) {
    return txt.replace(/<a\b[^>]*>/i,"").replace(/<\/a>/i, "");
 }

