// int to idr format
function idr(e) {
	var rp = parseInt(e);
    return new Intl.NumberFormat('ID').format(rp);
}
//  using select2 package
function initSelect(idDom) {
    $(idDom).select2();
}
// loading page
function loading(e, interval='') {
    if(interval == ''){
       interval = 3000;
    }
    switch(e){
        case 'show': $( "#overlay" ).show(interval);break;
        case 'hide': $( "#overlay" ).hide(interval);break;
        
    }
}
// checking error
function checkErrorCounter(){
    currentError += 1;
    if(currentError >= 10){
        return false;
    }
    return true;
}
// date / time format 
function dateTime(f, d) {
	// * f for format date_time
	// * d for value for convert
	return $.datepicker.formatDate(f, new Date(d));
}