//used to navigate through dashboard items
$("a.nav-link").on('click',function(){
	$(".tab-pane").hide();
	$($(this).attr("href")).show();
});

//to switch cards in admin dashboard
$("a.cardlink").on('click',function(){
	$(".tab-pane").hide();
	$($(this).attr("href")).show();
});

//to render datatable
$(document).ready(function() {
	$('.dataTable').DataTable();
} );

//for scrollable table
$('.DataTable').dataTable( {
	"scrollX": true
});



//page specific js
//index.php, contains login and sign up js
//======================================login js
$(document).ready(function ()
	{
	 
	$("#loginform").submit(function(event) 
	{
		/*prevent the action from excecuting to the php file*/
		event.preventDefault();

		/*get data from the inputs*/
		/*variable and the data in the inputs*/
		var txtusername = $("#txtusername").val();
		var txtpassword = $("#txtpassword").val();
		var btnlogin = $("#btnlogin").val();

		
		/*select the message notifier*/
		$(".message").load("scriptlogin.php",
		{
		// create a variable to be passed in the login

			txtusername:txtusername,
			txtpassword:txtpassword,
			btnlogin:btnlogin
		});

	});
});

//======================================sign up js
//script for add modal
//=======================================script for sending modal data to scriptlogin
$(document).ready(function ()
			{
				 
				$("#frmCreateAccount").submit(function(event) 
				{
					/*prevent the action from excecuting to the php file*/
					event.preventDefault();

					/*get data from the inputs*/
					/*variable and the data in the inputs*/
					var txtsstudentid = $("#txtsstudentid").val();
					var txtsfname = $("#txtsfname").val();
					var txtscourse = $("#txtscourse").val();
					var txtsyear = $("#txtsyear").val();
					var txtssection = $("#txtssection").val();
					var txtsusername = $("#txtsusername").val();
					var txtspassword = $("#txtspassword").val();
					var txtsconfirmpassword = $("#txtsconfirmpassword").val();
					var btnsignup = $("#btnsignup").val();

					/*select the message notifier*/
					$(".signupmessage").load("scriptsignup.php",
					{
					// create a variable to be passed in the login

						txtsstudentid:txtsstudentid,
						txtsfname:txtsfname,
						txtscourse:txtscourse,
						txtsyear:txtsyear,
						txtssection:txtssection,
						txtsusername:txtsusername,
						txtspassword:txtspassword,
						txtsconfirmpassword:txtsconfirmpassword,
						btnsignup:btnsignup
						
					});

				});
			});


//adminhome
//=============================script to send data from edit admin modal to scripteditadminprofile.php

$(document).ready(function ()
			{
				 
				$("#frmEditAdmin").submit(function(event) 
				{
					/*prevent the action from excecuting to the php file*/
					event.preventDefault();

					/*get data from the inputs*/
					
					var recordid = $("#txthiddenrecordid").val();
					var oldadminid = $("#txthiddenadminid").val();
					var oldadminuname = $("#txthiddenadminuname").val();
					var adminid = $("#txtadminid").val();
					var adminusername = $("#txtadminusername").val();
					var adminnewpassword = $("#txtadminnewpassword").val();
					var adminconfirmpassword = $("#txtadminconfirmpassword").val();
					var adminoldpassword = $("#txtadminoldpassword").val();
					var btneditadmin = $("#btnEditAdmin").val();


					/*select the message notifier*/
					 $(".editprofilemessage").load("scripteditadminprofile.php",
					 {
					 // create a variable to be passed in the  scriptadminprofile.php
					 	recordid:recordid,
						oldadminid:oldadminid,
						oldadminuname:oldadminuname,
						adminid:adminid,
						adminusername:adminusername,
						adminnewpassword:adminnewpassword,
						adminconfirmpassword:adminconfirmpassword,
						adminoldpassword:adminoldpassword,
						btneditadmin:btneditadmin
						
					 });

				});
			});

/*===================edit student modal================*/

$(document).ready(function ()
			{
				 
				$("#frmEditStudentAccount").submit(function(event) 
				{
					/*prevent the action from excecuting to the php file*/
					event.preventDefault();

					/*get data from the inputs*/
					var recordid = $("#studenthiddenid").val();
					var studenthiddenusername = $("#studenthiddenusername").val();
					var studenthiddenaccountid = $("#studenthiddenaccountid").val();
					var studentid = $("#txteditstudentid").val();
					var editfname = $("#txteditfname").val();
					var editcourse = $("#txteditcourse").val();
					var edityear = $("#txtedityear").val();
					var editsection = $("#txteditsection").val();
					var editusername = $("#txteditusername").val();
					var editpassword = $("#txteditpassword").val();
					var editconfirmpassword = $("#txteditconfirmpassword").val();
					var spassword = $("#txtspassword").val();
					var btneditstudentaccount = $("#btneditstudentaccount").val();

					// select the message notifier
					 $(".editstudentprofilemessage").load("scripteditstudentprofile.php",
					 {
						recordid : recordid,
						dbusername: studenthiddenusername,
						dbaccountid : studenthiddenaccountid,
						studentid : studentid,
						editfname : editfname,
						editcourse : editcourse,
						edityear : edityear,
						editsection : editsection,
						editusername : editusername,
						editpassword : editpassword,
						editconfirmpassword : editconfirmpassword,
						spassword : spassword,
						btneditstudentaccount : btneditstudentaccount
					 });

				});
			});



//=========================add admin account=======================

$(document).ready(function ()
{
	 
	$("#frmAddAdmin").submit(function(event) 
	{
		/*prevent the action from excecuting to the php file*/
		event.preventDefault();

		/*get data from the inputs*/
		var adminid = $("#txtaddadminid").val();
		var adminusername = $("#txtaddadminusername").val();
		var adminpassword = $("#txtaddadminpassword").val();
		var adminconfirmpassword = $("#txtaddadminconfirmpassword").val();
		var adminoldpassword = $("#txtaddaddminoldpassword").val();
		var btnaddadmin = $("#btnAddAdmin").val();
		
		
		// select the message notifier
		 $(".addprofilemessage").load("scriptaddadmin.php",
		 {
			
			adminid : adminid,
			adminusername : adminusername,
			adminpassword : adminpassword,
			adminconfirmpassword : adminconfirmpassword,
			adminoldpassword : adminoldpassword,
			btnaddadmin : btnaddadmin
		 });

	});
});





//code snippet for opening item

// $(document).ready(function(){
// .openItem refers to the button. the #show-dialog refers to the modal id
// $(".openAddPublisher").on("click", function(){ 
// 	$("#modalAddPublisher").modal();
// 	});
// }



//=================================add books

$(document).ready(function ()
{
	 
	$("#frmAddBook").submit(function(event) 
	{
		/*prevent the action from excecuting to the php file*/
		event.preventDefault();
	
        var booktitle = $("#txtbooktitle").val();
        var subject = $("#txtsubject").val();
        var author = $("#txtauthor").val();
        var stock = $("#txtstock").val();
        var publisher = $("#selpublisher").val();
        var yearpublished = $("#txtyearpublished").val();
        var btn = $("#btnSaveAddBook").val();

		
		// select the message notifier
		 $(".addBookMessage").load("scriptaddbook.php",
		 {
			
			booktitle : booktitle,
			subject : subject,
			author : author,
			stock : stock,
			publisher : publisher,
			yearpublished : yearpublished,
			btn : btn
		 });

	});
});

//publisher
$(document).ready(function ()
{
	 
	$("#frmAddPublisher").submit(function(event) 
	{
		/*prevent the action from excecuting to the php file*/
		event.preventDefault();
	
        // var booktitle = $("#txtbooktitle").val();
        var txtpublishername = $("#txtpublishername").val();
        var txtpublisheraddress = $("#txtpublisheraddress").val();
        var btnSavePublisher = $("#btnSavePublisher").val();
      
			
			
			
		
		// select the message notifier
		 $(".createpublishermessage").load("scriptaddpublisher.php",
		 {
			
			txtpublishername : txtpublishername,
			txtpublisheraddress : txtpublisheraddress,
			btnSavePublisher : btnSavePublisher
		 });

	});
});


//edit book

$(document).ready(function ()
{
	 
	$("#frmEditBook").submit(function(event) 
	{
		/*prevent the action from excecuting to the php file*/
		event.preventDefault();

	

// 
	
        // var booktitle = $("#txtbooktitle").val();
        var bookid = $("#txthiddeneditbookid").val();
        var title = $("#txteditbooktitle").val();
        var subject = $("#txteditsubject").val();
        var author = $("#txteditauthor").val();
        var stock = $("#txteditstock").val();
        var publisherid = $("#seleditpublisher").val();
        var yearpublished = $("#txtedityearpublished").val();
        var btn = $("#btnSaveEditBook").val();
        
       
		// select the message notifier
		 $(".editBookMessage").load("scripteditbook.php",
		 {
			
			bookid : bookid,
			title : title,
			subject : subject,
			author : author,
			stock : stock,
			publisherid : publisherid,
			yearpublished : yearpublished,
			btn : btn
		 });

	});
});

//edit publisher

$(document).ready(function ()
{
	 
	$("#frmEditPublisher").submit(function(event) 
	{
		/*prevent the action from excecuting to the php file*/
		event.preventDefault();

	

// 
	
        // var booktitle = $("#txtbooktitle").val();
        var id = $("#txthiddeneditpubid").val();
        var name = $("#txteditpubname").val();
        var address = $("#txteditpubaddress").val();
        var btn = $("#btnSaveEditPublisher").val();
       
		// select the message notifier
		 $(".editPublisherMessage").load("scripteditpublisher.php",
		 {
			
			id : id,
			name : name,
			address : address,
			btn : btn
		 });

	});
});

//adminborrowform





$(document).ready(function ()
{
	 
	$("#frmAdminBorrow").submit(function(event) 
	{
		/*prevent the action from excecuting to the php file*/
		event.preventDefault();
        
        var bookid = $("#txtadminborrowbookid").val();
        var studentid = $("#txtborrowstudentid").val();  
        var btn = $("#btnadminborrow").val();
       
		// select the message notifier
		 $(".adminborrowmessage").load("scriptborrow.php",
		 {
			
			bookid : bookid,
			studentid : studentid,
			btn : btn
		 });

	});
});

//return







$(document).ready(function ()
{
	 
	$("#frmAdminReturn").submit(function(event) 
	{
		/*prevent the action from excecuting to the php file*/
		event.preventDefault();
        
        var bookid = $("#txtadminreturnbookid").val();
        var studentid = $("#txtreturnstudentid").val();  
        var btn = $("#btnadminreturn").val();
      
       
		// select the message notifier
		 $(".adminreturnmessage").load("scriptadminformreturn.php",
		 {
			
			bookid : bookid,
			studentid : studentid,
			btn : btn
		 });

	});
});

//clear data
$(document).ready(function ()
{
	 
	$("#frmClearData").submit(function(event) 
	{
		/*prevent the action from excecuting to the php file*/
		event.preventDefault();
        
        var tablename = $("#seltable").val();
        var password = $("#cleardataadminpassword").val();  
        var btn = $("#btncleardata").val();
      
       
		// select the message notifier
		 $(".cleardatamessage").load("scriptadmincleardata.php",
		 {
			
			tablename : tablename,
			password : password,
			btn : btn
		 });

	});
});

//refresh browser when modals are closed
$('#modalAddBook').on('hidden.bs.modal', function () { 
    location.reload();
});

$('#modalAddAdmin').on('hidden.bs.modal', function () { 
    location.reload();
});

$('#addPublisherModal').on('hidden.bs.modal', function () { 
    location.reload();
});





// dataTable scroll
// $(document).ready(function() {
//     $('#dtborrowedbooks').DataTable( {
//         "scrollX": true
//     } );
// } );













