/**
 * 
 */
var tid = setInterval(mycode, 2000);
function mycode() {
	$.ajax(
			type: 'GET',
			url: 'test.php',
			data: { name: 'Ivan' }
	)
	.done(function(data) {
		alert(data); 
		})
	.fail(function() {
		alert('error');
		})
}
function abortTimer() {
	      clearInterval(tid);
	    }