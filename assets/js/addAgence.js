/**
 * File : addAgence.js
 * 
 * This file contain the validation of add Agence form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 */

$(document).ready(function(){
	
	var addAgenceForm = $("#addAgence");
	
	var validator = addAgenceForm.validate({
		
		rules:{
            raison_sociale :{ required : true },
            officeID :{ required : true, digits : true },
            typeAgence :{ required : true, selected : true },
			email : { required : true, email : true, remote : { url : baseURL + "checkEmailExists", type :"post"} },
            username : { required : true },
            password : { required : true },
			cpassword : {required : true, equalTo: "#password"}
		},
		messages:{
            raison_sociale :{ required : "This field is required" },
            officeID :{ required : "This field is required" },
            typeAgence :{ required : "This field is required" },
			email : { required : "This field is required", email : "Please enter valid email address", remote : "Email already taken" },
            username :{ required : "This field is required" },
            password : { required : "This field is required" },
			cpassword : {required : "This field is required", equalTo: "Please enter same password" }	
		}
	});
});
