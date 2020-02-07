function godown_check() 
{
    var godown = document.forms["frm"]["godown"].value;
    var godown_slip_number = document.forms["frm"]["godown_slip_number"].value.toUpperCase();
	var invoice_number = document.forms["frm"]["invoice_number"].value;
	var invoice_date = document.forms["frm"]["invoice_date"].value;
	
	if (	godown == '' && godown_slip_number != ''	) 
	{
		alert("Please select the godown");
		return false;
	}
	
	if (	godown != '' && godown_slip_number == ''	) 
	{
		alert("Please enter godown slip");
		return false;
	}	
		
	if (	invoice_number == '' && godown_slip_number == ''	)
	{
		alert("Please enter at least one of the following :  Invoice Number or Godown Slip Number");
		return false;
	}
	
	if (	invoice_number != '' && invoice_date == ''	)
	{
		alert("Please enter invoice date");
		return false;
	}	
}