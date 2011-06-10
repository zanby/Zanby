var old_values = new Array();  

  function invertCheckBoxes(formPrefix)
  {  	
  	SForm = document.forms[formPrefix + 'Form'];  	
    if (document.getElementById(formPrefix + '_own_choices').checked) new_value ='0'; else new_value = '1';
  	if (new_value != old_values[formPrefix])
    {
    	SForm.elements[formPrefix + '_group_organizers'].disabled = !SForm.elements[formPrefix + '_group_organizers'].disabled;
    	SForm.elements[formPrefix + '_my_group_organizers'].disabled = !SForm.elements[formPrefix + '_my_group_organizers'].disabled;
    	SForm.elements[formPrefix + '_my_group_members'].disabled = !SForm.elements[formPrefix + '_my_group_members'].disabled;
    	SForm.elements[formPrefix + '_my_friends'].disabled = !SForm.elements[formPrefix + '_my_friends'].disabled;
    	SForm.elements[formPrefix + '_my_network'].disabled = !SForm.elements[formPrefix + '_my_network'].disabled;
    	if ( SForm.elements[formPrefix + '_my_address_book'] ) SForm.elements[formPrefix + '_my_address_book'].disabled = !SForm.elements[formPrefix + '_my_address_book'].disabled;
    }  	
  	old_values[formPrefix] = new_value;
  }
  
  function innerHTMLScript(formPrefix)
  {  	
  	old_values[formPrefix] = "0";
  	invertCheckBoxes(formPrefix);
  }
  
  
  