
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>DIY History</title>
<style>	
	html {		
		height: 100%;	
	}
	body {
		width: 100%;
		height: 100%;
		margin: 0px;		
	}
	.smooth_zoom_preloader {
		background-image: url(../../plugins/Scriptus/views/public/index/img/preloader.gif);
	}	
	.smooth_zoom_icons {
		background-image: url(../../plugins/Scriptus/views/public/index/img/icons.png);
	}
</style>


<?php 
	echo js_tag('jquery-1.9.1'); 
	echo js_tag('jquery.smoothZoom'); 	
	echo js_tag('modernizr.custom');
?>

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-hover-dropdown/2.0.10/bootstrap-hover-dropdown.min.js"></script>
<link href="../../plugins/Scriptus/views/public/css/component.css" rel="stylesheet">
 
</head>

	<body class="cbp-spmenu-slide">	

		<img id="ImageID" src="<?php echo $this->imageUrl; ?>"/>	

		<nav class="cbp-spmenu cbp-spmenu-horizontal cbp-spmenu-bottom cbp-spmenu-open" id="cbp-spmenu-2">
			
	   		<a href="http://s-lib018.lib.uiowa.edu/omeka" alt="Home"><span class="glyphicon glyphicon-home"></span>home</a>
	   		<br /><br />
	   		<ul>
	   			<li><?php echo '<p><span class="fa fa-file-text fa-lg"></span><strong>' . $this->dc_file_title . '</strong></p>'; ?></li>
	   			<li><?php echo '<p><span class="fa fa-book fa-lg"></span>' . $this->dc_item_link . "</p>"; ?></li>
	   			<li><?php echo '<p><span class="fa fa-archive fa-lg"></span>' . $this->collection_link . "</p>"; ?></li>
   			</ul>

   			<div class="dropdown">
   				<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">More information<span class="caret"></span></a>
   			    <ul class="dropdown-menu" role="menu">
   			      <li><a href="<?php echo $this->idl_link; ?>">digital collection</a></li>
   			      <li><a href="<?php echo $this->collguide_link; ?>">archival collection guide</a></li>
   			    </ul>
   			</div>   			
			<?php echo $this->form; ?>
		</nav>	


		<!-- Classie - class helper functions by @desandro https://github.com/desandro/classie -->
		<script src="../../plugins/Scriptus/views/public/javascripts/classie.js"></script>
		<script>
			var 
				menuBottom = document.getElementById( 'cbp-spmenu-2' ),							
				body = document.body;

			window.onload = function() {				
				classie.toggle( this, 'active' );
				classie.toggle( menuBottom, 'cbp-spmenu-open' );		
			};
		</script>

		<script>
			jQuery(function($){
				$('#ImageID').smoothZoom({
					width: '100%',
					height: '100%',
					responsive: true
				});				
			});
		</script>



		<script>
		$('form').submit(function(event) {

				// get the form data				
				var formData = {
					'transcription'	: $('#transcribebox').val()
				};

				// process the form
				$.ajax({
					type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
					url 		: '<?php echo Zend_Controller_Front::getInstance()->getRequest()->getRequestUri(); ?>/save', // the url where we want to POST
					data 		: formData, // our data object
					dataType 	: 'json', // what type of data do we expect back from the server
		            encode          : true
				})
					// using the done promise callback
					.done(function(data) {

						// log data to the console so we can see
						//console.log(data); 

						// here we will handle errors and validation messages
					});

				// stop the form from submitting the normal way and refreshing the page
				event.preventDefault();
			});
		</script>	

		<script>
		/* simulates async login activity */
		var doLogin = function(ms,cb) {
		  setTimeout(function() {
		    if(typeof cb == 'function')
		    cb();
		  }, ms);
		};

		$('#save-button').click(function(){
		  var btn = $(this);
		  
		  btn.button("loading");
		  btn.children().each(function(idx,ele){
		    var icon = $(ele);
		    icon.animate({},2000, 'linear', function() {
		        icon.hide().fadeIn(300*idx).addClass('big');
		     });
		  });
		  
		  // perform login / async callback here
		  doLogin(3000,function(){
		  	btn.button("reset"); // reset button after login callback returns
		  });	  
		})
		</script>

	</body>
</html>

