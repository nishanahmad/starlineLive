function return_check() 
{
    var client = document.forms["frm"]["client"].value;
    var godown_slip_number = document.forms["frm"]["godown_slip_number"].value;
	
	godown_slip_number = godown_slip_number.toUpperCase();

		
if ( godown_slip_number.contains('RTN')  && client == ''	 ) 
	{
        alert("Please select the client of the return bag ");
        return false;
    }
	
if (	!godown_slip_number.contains('RTN')  && client != ''	 ) 
	{
        alert("Please select client for the return bags");
        return false;
    }	

}