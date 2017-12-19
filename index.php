<?php
include("database.php");
if(isset($_GET["join"])){
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING) or die("invalid username");
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) or die("invalid password");
	$password = password_hash($password, PASSWORD_DEFAULT);
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING) or die("invalid username");
	
	$mappenavn = "profile_img/";
	$tidspunkt = round(microtime(true) * 1000);
	$billede = $mappenavn . $tidspunkt . "-" . basename($_FILES["picture"]["name"]);
	$billedetype = strtolower(pathinfo($billede,PATHINFO_EXTENSION));
	$billedefil = $_FILES["picture"];
	
	if($billedetype != "jpeg" && $billedetype != "jpg" && $billedetype != "png"){
		echo '<script>javascript:alert("Image file not supported");</script>';
	}else{
		if(move_uploaded_file($billedefil["tmp_name"], $billede)){
			mysqli_query($con, "INSERT INTO user (username, password, email, image) VALUES ('$username', '$password', '$email', '$billede')");
			if(mysqli_affected_rows($con) > 0){
				echo '<script>javascript:alert("The user has been created");</script>';
			}else{
				echo '<script>javascript:alert("The user could not be created");</script>';
			}
			
		}
	}
}
if(isset($_GET["login"])){
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING) or die("invalid username");
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) or die("invalid password");
	
	$tjeklogin = mysqli_query($con, "SELECT id, username, password FROM user WHERE username='$username'");
	
	if(mysqli_num_rows($tjeklogin) > 0){
		$info = mysqli_fetch_assoc($tjeklogin);
		if(password_verify($password, $info["password"])){
			echo '<script>javascript:alert("Welcome!");</script>';
			$_SESSION["loggetind"] = 1;
			$_SESSION["username"] = $info["username"];
			$_SESSION["userid"] = $info["id"];
		}else{
			echo '<script>javascript:alert("Wrong password");</script>';
		}
	}else{
		echo '<script>javascript:alert("The user could not be found");</script>';
	}
}

if(isset($_GET["upload"])){
	$imagetext = filter_input(INPUT_POST, 'image_text', FILTER_SANITIZE_STRING) or die("invalid image text");
	
	$mappenavn = "uploads/";
	$tidspunkt = round(microtime(true) * 1000);
	$billede = $mappenavn . $tidspunkt . "-" . basename($_FILES["img"]["name"]);
	$billedetype = strtolower(pathinfo($billede,PATHINFO_EXTENSION));
	$billedefil = $_FILES["img"];
	
	if($billedetype != "jpeg" && $billedetype != "jpg" && $billedetype != "png"){
		echo '<script>javascript:alert("Image file not supported");</script>';
	}else{
		if(move_uploaded_file($billedefil["tmp_name"], $billede)){
			mysqli_query($con, "INSERT INTO images (user_id, image_link, image_text) VALUES ('{$_SESSION["userid"]}', '$billede', '$imagetext')");
			if(mysqli_affected_rows($con) > 0){
				echo '<script>javascript:alert("Your moment has been shared!");</script>';
			}else{
				echo '<script>javascript:alert("The moment could not be shared");</script>';
			}
			
		}
	}
}

if(isset($_GET["logout"])){
	session_unset();
	session_destroy();
	echo '<script>javascript:alert("See you soon!");</script>';
}

if(isset($_GET["delete"])){
	$deleteid = $_GET["delete"];
	$hentinfo = mysqli_query($con, "SELECT * FROM images WHERE id='{$deleteid}'");
	$info = mysqli_fetch_assoc($hentinfo);
	if(isset($_SESSION["userid"]) && $info["user_id"] == $_SESSION["userid"]){
		mysqli_query($con, "DELETE FROM images WHERE id='{$deleteid}'");
		echo '<script>javascript:alert("Your moment has now been deleted!");</script>';
	} else {
		echo '<script>javascript:alert("You do not own this picture!");</script>';
	}
}

if(isset($_GET["update"])){
	$billedeid = $_POST["image_id"];
	$billedetext = $_POST["image_text"];
	
	$hentinfo = mysqli_query($con, "SELECT * FROM images WHERE id='{$billedeid}'");
	$info = mysqli_fetch_assoc($hentinfo);
	
	if(isset($_SESSION["userid"]) && $info["user_id"] == $_SESSION["userid"]){
		mysqli_query($con, "UPDATE images SET image_text='{$billedetext}' WHERE id='{$billedeid}'");
		echo '<script>javascript:alert("Your moment has now been edited!");</script>';
	} else {
		echo '<script>javascript:alert("You do not own this picture!");</script>';
	}
}
?>
<!DOCTYPE html>
<html>
   <head>  
<meta name="viewport" content="width=device-width, initial-scale=1">
	  <link href="css/featherlight.min.css" type="text/css" rel="stylesheet" />
      <link rel="stylesheet" href="https://use.typekit.net/xby8jak.css">
      <meta charset="utf-8">
      <title>Tourism Madrid - Madrid in heights</title>
      <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
      <link href="css/simplegrid.css" type="text/css" rel="stylesheet">
      <link href="css/style.css" type="text/css" rel="stylesheet">
      <link href="css/insta.css" type="text/css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
      <script src="js/jquery.slides.min.js"></script>
      <script src="js/script.js" type="text/javascript"></script>
   </head>
   <body>
      <header>
         <div class="grid grid-pad">
            <div class="col-3-12">
               <div class="content">
                  <img src="img/logo.jpg" class="logo" id="menulogo">
                  <img src="img/seek.png" class="soeg">
               </div>
            </div>
            <div class="col-6-12 menudiv">
               <div class="content menucontainer">
                  <ul class="menu">
                     <li><a href="javascript:void(0);" id="menuphotos">Photos</a>
                     </li>
                     <li><a href="javascript:void(0);" id="menuinfo">Info</a>
                     </li>
                     <li><a href="javascript:void(0);" id="menurooftops">Rooftops</a>
                     </li>
                     <li><a href="javascript:void(0);" id="menumap">Map</a>
                     </li>
                     <li><a href="javascript:void(0);" id="menusocial">Social</a>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="col-3-12 knapdiv">
               <div class="contentknap">
				  <?php
				   if(!isset($_SESSION["loggetind"])){
					   ?>
                  <button class="menuknap" id="menulogin">Log in</button>
                  <button class="menuknap" id="menujoin">Join</button>
				   <?php
				   }else{
					   ?>
				   <button class="menuknap" id="menuupload">Upload</button>
				   <a href="?logout"><button class="menuknap">Log out</button></a>
				   <?php
				   }
				   ?>
               </div>
				
            </div>
			 <div class="burgermenu" id="burgermenu">
					<i class="fa fa-bars"></i>
				</div>
			 <div class="mobilmenu" id="mobilmenu" style="display: none;">
	   	<div class="menupunkt" id="mobilphotos">Photos</div>
	   	<div class="menupunkt" id="mobilinfo">Info</div>
	   	<div class="menupunkt" id="mobilroof">Rooftops</div>
	   	<div class="menupunkt" id="mobilmap">Map</div>
	   	<div class="menupunkt" id="mobilsocial">Social</div>
		<div class="menuknapper">
			<?php
				   if(!isset($_SESSION["loggetind"])){
					   ?>
                  <button class="menuknap" id="menulogin2">Log in</button>
                  <button class="menuknap" id="menujoin2">Join</button>
				   <?php
				   }else{
					   ?>
				   <button class="menuknap" id="menuupload2">Upload</button>
				   <a href="?logout"><button class="menuknap">Log out</button></a>
				   <?php
				   }
				   ?>			 
	</div>
	   </div>
         </div>
      </header>
	  <div class="popupbokscontainer" id="logindboks" style="display: none;">
	  	<div class="popupboks">
			<h1 class="overskrift tk-gooddog-newdelete darkblue">LOG IN</h1>
			<p class="darkblue">... to share your Madrid rooftop moments!</p>
			<div class="divider"></div>
			<form class="form" action="?login=1" method="post">
				<input type="text" name="username" placeholder="Username" autofocus>
				<input type="password" name="password" placeholder="Password">
				<input type="submit" name="login" value="Log in">
			</form>
		</div>
	  </div>
	   <div class="popupbokscontainer" id="joinboks" style="display: none;">
	  	<div class="popupboks">
			<h1 class="overskrift tk-gooddog-newdelete darkblue">JOIN TO SHARE MOMENTS</h1>
			<p class="darkblue">Register below to share your personal rooftop experinces in Madrid with others</p>
			<div class="divider"></div>
			<form class="form" action="?join=1" method="post" enctype="multipart/form-data">
			<input type="text" name="username" placeholder="Username" required autofocus>
			<input type="password" name="password" placeholder="Password" required>
			<input type="email" name="email" placeholder="E-mail" required>
			<input type="file" name="picture" required>
			<input type="submit" name="submit" value="Join">
		</form>
		</div>
	  </div>
	   <div class="popupbokscontainer" id="uploadboks" style="display: none;">
	  	<div class="popupboks">
			<h1 class="overskrift tk-gooddog-newdelete darkblue">SHARE YOUR MOMENT</h1>
			<div class="divider"></div>
			<form class="form" action="?upload=1" method="post" enctype="multipart/form-data">
				<input type="text" name="image_text" placeholder="Describe your moment..." required autofocus>
				<input type="file" name="img" required>
				<input type="submit" name="upload" value="Share picture">
			</form>
		</div>
	  </div>
      <div class="grid grid-pad" id="top">
         <div class="col-1-1">
            <div class="content">
               <div class="slider">
				   <h1 class="overskrift tk-gooddog-newdelete sliderImg">EXPERIENCE<br>MADRID IN HEIGHTS</h1>
				</div>
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-1">
            <div class="content">
               <img src="img/skyline.png" alt="skyline" class="skyline">
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-1">
            <div class="content indhold">
               <h1 class="overskrift tk-gooddog-newdelete green">MADRID... IN HEIGHTS?</h1>
               <p class="green info">
                  The tourism of Madrid needs a fresh update. A way to see the city in different ways than expected as usual. There is a million things to see and do in the great city of Spain, Madrid. But why only see all these amazing places from the lower streets of the city, when you can go to the top and get a better and more beautiful experience?
               </p>
               <p class="green info">
                  Imagine standing on the Principal Hotel building having the amazing view of all the monuments in the area while enjoying beautiful art and design at the Circulo de Bellas Artes or having an exotic cocktail on the Sunset Lookers hotel while enjoying the breath taking sunset of Madrid and its incredible skyline.
               </p>
               <p class="green info">
                  Whether you are surrounded by beautiful colours in the day time, or gazing at the million stars shining at night - this rooftop tour guide will give you a magical and unforgettable experience of Madrid in heights.
               </p>
               <hr>
            </div>
         </div>
      </div>
      <div class="grid grid-pad" id="photos">
         <div class="col-1-1">
            <div class="content">
               <h1 class="overskrift tk-gooddog-newdelete yellow">#MADRIDINHEIGHTS</h1>
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
		 <?php
		  	$hentbilleder = mysqli_query($con, "SELECT * FROM images ORDER BY id DESC LIMIT 6");
		  while($billede = mysqli_fetch_array($hentbilleder)){
			  $hentinfo = mysqli_query($con, "SELECT username, image FROM user WHERE id='{$billede["user_id"]}'");
			  $info = mysqli_fetch_assoc($hentinfo);
			  $billedelink = $billede["image_link"];
			  $billedetext = $billede["image_text"];
			  ?>
		  <div class="col-1-3">
            <div class="content">
               <div class="insta">
                  <div class="insta-top">
                     <a class="insta-person-billede"><img src="<?=$info["image"]?>" />
                     </a>
                     <a class="insta-navn" href="#"><?=$info["username"]?></a>
                     <a class="insta-tid"><?=$billede["date"]?></a>
                  </div>
                  <div class="insta-billede">
                     <img src="<?=$billedelink?>" />
                  </div>
                  <div class="insta-indhold">
					  <?=$billedetext?>
					  <?php
				  if(isset($_SESSION["userid"]) && $billede["user_id"] == $_SESSION["userid"]){
					  echo '<a href="javascript:void(0);" class="rediger" id="redigerknap"><span class="redigerknap"><i class="fa fa-pencil"></i></span>';
					  echo '<a class="slet" href="?delete=' . $billede["id"] . '"><span class="deleteknap"><i class="fa fa-times"></i></span></a>';
					  ?>
					  <div class="redigerboks">
					  	  <div class="divider"></div>
						  <form action="?update" method="post" class="updateform">
							  <input type="hidden" name="image_id" value="<?=$billede["id"]?>">
						  <input type="image_text" name="image_text" value="<?=$billedetext?>">
						  <input type="submit" name="rediger" value="Update">
						  </form>
					  </div>
					  <?php
				  }
				  	?>
				   </div>
                  <div class="insta-bund">
                     <i class="fa fa-heart-o nx"></i>
                     <input type="text" class="ny" placeholder="Kommentar..." />
                  </div>
               </div>
            </div>
         </div>
		  <?php
		  }
		  ?>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-1">
            <div class="content">
               <hr>
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-2">
            <div class="content">
               <img class="culture" src="img/a.png" alt="a">
            </div>
         </div>
         <div class="col-1-2" id="info">
            <div class="content">
               <h1 class="overskrift tk-gooddog-newdelete yellow left">THE CULTURAL MADRID</h1>
               <p class="yellow left ctekst">
                  Like any other big city, Madrid has a lot to offer too. 
                  <br>
                  <br>
                  But what's especially great about Madrid, is that a rare amount of people are aware of all the extra ordinairy things the city has to offer at the very top.
                  <br>
                  <br>
                  You can be sure to find many local authentic experiences concerning traditions and foods in the high buildings, but what most people forget to think of, is what you can see from that high of a view.
                  <br>
                  <br>
                  <br>
                  Being able to locate all the different beautiful monuments of the city and at the same time being able to enjoy the amazing sun and skyline is something we should never take for granted. 
                  <br>
                  <br>
                  This is why this tour guide will help you get just the last pinch of magic to your trip to Madrid that you need.
                  <br>
                  <br>
                  So remember... there's always more than you expect if you just remember to take that curiosity and make it come alive.
                  <br>
                  <br>
                  When they go low - WE go high.
               </p>
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-1">
            <div class="content">
               <hr>
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-2">
            <div class="content">
               <h1 class="overskrift tk-gooddog-newdelete darkblue right">LEISURE IN MADRID</h1>
               <p class="darkblue right ctekst">
                  Of course it's individually for everyone which things we like to do for leisure. 
                  <br>
                  <br>
                  Mostly we go eat something special, go to the theatre, go hiking, shopping, to a museum... 
                  <br>
                  <br>
                  The list is long. But one thing we know for sure, is that everyone appreciates a view that takes your breath away.
                  <br>
                  <br>
                  And unfortunately, Madrid is underestimated at this. We need to get people to look up again, make them remember that dreams are supposed to be lived. Therefore a thing we all have in common is to go up and get astonished. 
                  <br>
                  <br>
                  Whether you've just gone through the worst heartbreak of your life, or you just got your dream job or won the lottery, a stunning view can never not help. 
                  <br>
                  <br>
                  Some uses a view to think, someone to gaze, some may explore... but one thing is for sure. 
                  <br>
                  <br>
                  The magic about it never goes away. 
               </p>
            </div>
         </div>
         <div class="col-1-2">
            <div class="content">
               <img src="img/c.png" alt="b" class="culture">
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-1">
            <div class="content">
               <hr>
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-2">
            <div class="content">
               <img src="img/b.png" alt="b" class="culture">
            </div>
         </div>
         <div class="col-1-2">
            <div class="content">
               <h1 class="overskrift tk-gooddog-newdelete orange left">COMBINING BOTH AS HYBRID</h1>
               <p class="orange left ctekst">
                  So in this tour guide, you cannot only experience one thing or another. 
                  <br>
                  <br>
                  Yes, maybe some people are only interested in culture or some in leisure. But what about us, who don't want to settle for less?
                  <br>
                  <br>
                  What about us greedy people who wants to experience culture and leisure at the same time?
                  <br>
                  <br>
                  Madrid has the answer. There are several rooftops that perhaps offers amazing food or drinks, and at the same time have a directly view to one of the most beautiful monuments of the city. 
                  <br>
                  <br>
                  <br>
                  So there you've got it... Everything you will ever need, plus free magic.
                  <br>
                  <br>
                  You're so very welcome.
               </p>
            </div>
         </div>
      </div>
      <div class="grid grid-pad" id="rooftops">
         <div class="col-1-1">
            <div class="content">
               <hr>
               <h1 class="overskrift tk-gooddog-newdelete purple">ROOFTOPS</h1>
            </div>
         </div>
         <div class="col-1-2">
            <div class="content">
               <a href="#" class="rooftopa" data-featherlight="roofa.html">
                  <div class="rooftop rooftoplilla">
                     <div class="rooftopIndhold">
                        <div class="rooftopNummer">
                           1
                        </div>
                        <div class="rooftopTekst">
                           <strong>Nice to meet you DearHotel</strong><br>
                           Plaza de España<br>
                           Gran Vía 80<br>
                           638908559
                        </div>
                     </div>
                     <div class="billede">
                        <img src="img/1.jpg" alt="1">
                     </div>
                  </div>
               </a>
               <a href="#" class="rooftopa" data-featherlight="roofb.html">
                  <div class="rooftop rooftopgul">
                     <div class="rooftopIndhold">
                        <div class="rooftopNummer">
                           2
                        </div>
                        <div class="rooftopTekst">
                           <strong>Sunset Lookers Hotel Santo Domingo</strong><br>
                           Plaza de santo domingo 13 <br>
                           915479911
                        </div>
                     </div>
                     <div class="billede">
                        <img src="img/2.jpg" alt="1">
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofc.html">
                  <div class="rooftop rooftopgroen">
                     <div class="rooftopIndhold">
                        <div class="rooftopNummer">
                           3
                        </div>
                        <div class="rooftopTekst">
                           <strong>Hotel Emparador</strong><br>
                           Gran Via la almudena royal<br>
							Gran via 53
                        </div>
                     </div>
                     <div class="billede">
                        <img src="img/3.jpg" alt="1">
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofd.html">
                  <div class="rooftop rooftoplilla">
                     <div class="rooftopIndhold">
                        <div class="rooftopNummer">
                           4
                        </div>
                        <div class="rooftopTekst">
                           <strong>Corte Inglés Gourmet Experience </strong><br>
                           Callao theater<br>
							Plaza callao 2
                        </div>
                     </div>
                     <div class="billede">
                        <img src="img/4.jpg" alt="1">
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofe.html">
                  <div class="rooftop rooftopgul">
                     <div class="rooftopIndhold">
                        <div class="rooftopNummer">
                           5
                        </div>
                        <div class="rooftopTekst">
                           <strong>Hotel de las Letras </strong><br>
                           Telefónica building<br>
							Calle caballero de García 11 

                        </div>
                     </div>
                     <div class="billede">
                        <img src="img/5.jpg" alt="1">
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="rooff.html">
                  <div class="rooftop rooftopgroen">
                     <div class="rooftopIndhold">
                        <div class="rooftopNummer">
                           6
                        </div>
                        <div class="rooftopTekst">
                           <strong>The Vincci Mint </strong><br>
                           A musical food truck <br>
							Gran Vía 10 
                        </div>
                     </div>
                     <div class="billede">
                        <img src="img/6.jpg" alt="1">
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa"data-featherlight="roofg.html">
                  <div class="rooftop rooftoplilla">
                     <div class="rooftopIndhold">
                        <div class="rooftopNummer">
                           7
                        </div>
                        <div class="rooftopTekst">
                           <strong>The Principal Hotel</strong><br>
                           ACirculo de bellas Artes <br>
							Calle marqués de valdeiglesias 

                        </div>
                     </div>
                     <div class="billede">
                        <img src="img/7.jpg" alt="1">
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofh.html">
                  <div class="rooftop rooftopgul">
                     <div class="rooftopIndhold">
                        <div class="rooftopNummer">
                           8
                        </div>
                        <div class="rooftopTekst">
                           <strong>Casa Suecia</strong><br>
                           Calle marqués de la casa rivera 4 

                        </div>
                     </div>
                     <div class="billede">
                        <img src="img/8.jpg" alt="1">
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofi.html">
                  <div class="rooftop rooftopgroen">
                     <div class="rooftopIndhold">
                        <div class="rooftopNummer">
                           9
                        </div>
                        <div class="rooftopTekst">
                           <strong>Tartan roof circulo de bellas Artes</strong><br>
                         	Calle marqués de la casa riera 2 
						 </div>
                     </div>
                     <div class="billede">
                        <img src="img/9.jpg" alt="1">
                     </div>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-1-2">
            <div class="content">
               <a href="#" class="rooftopa" data-featherlight="roofj.html">
                  <div class="rooftop_right rooftoplilla">
					 <div class="billede_right">
                        <img src="img/10.jpg" alt="1">
                     </div>
                     <div class="rooftopIndhold_right">
						<div class="rooftopTekst_right">
                           <strong>La cocina de san Anton</strong><br>
                           Augusto Figueroa 24
                        </div>
                        <div class="rooftopNummer_right">
                           10
                        </div>
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofk.html">
                  <div class="rooftop_right rooftopgul">
					 <div class="billede_right">
                        <img src="img/11.jpg" alt="1">
                     </div>
                     <div class="rooftopIndhold_right">
                        <div class="rooftopTekst_right">
                           <strong>Terraza del cielo de alcalá </strong><br>
                           La calle de alcalá 66 
                        </div>
						<div class="rooftopNummer_right">
                           11
                        </div>
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofl.html">
                  <div class="rooftop_right rooftopgroen">
					 <div class="billede_right">
                        <img src="img/12.jpg" alt="1">
                     </div>
                     <div class="rooftopIndhold_right">
                        <div class="rooftopTekst_right">
                           <strong>Radio Me</strong><br>
                         	Plaza de Santaana 14 
						 </div>
						<div class="rooftopNummer_right">
                           12
                        </div>
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofm.html">
                  <div class="rooftop_right rooftoplilla">
					 <div class="billede_right">
                        <img src="img/13.jpg" alt="1">
                     </div>
                     <div class="rooftopIndhold_right">
                        <div class="rooftopTekst_right">
                           <strong>Terraza del Thyssen</strong><br>
                           Paseo del Prado 8
                        </div>
						<div class="rooftopNummer_right">
                           13
                        </div>
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofn.html">
                  <div class="rooftop_right rooftopgul">
					 <div class="billede_right">
                        <img src="img/14.jpg" alt="1">
                     </div>
                     <div class="rooftopIndhold_right">
                        <div class="rooftopTekst_right">
                           <strong>Gymage</strong><br>
                           Calle Corredera baia de san Pablo 2 
                        </div>
						<div class="rooftopNummer_right">
                           14
                        </div>
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofo.html">
                  <div class="rooftop_right rooftopgroen">
					 <div class="billede_right">
                        <img src="img/15.jpg" alt="1">
                     </div>
                     <div class="rooftopIndhold_right">
                        <div class="rooftopTekst_right">
                           <strong>El Paracaidista </strong><br>
                         	Calle Palma 10 
						 </div>
						<div class="rooftopNummer_right">
                           15
                        </div>
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofp.html">
                  <div class="rooftop_right rooftoplilla">
					 <div class="billede_right">
                        <img src="img/16.jpg" alt="1">
                     </div>
                     <div class="rooftopIndhold_right">
                        <div class="rooftopTekst_right">
                           <strong>Azotea Forus </strong><br>
                           Barceló 6
                        </div>
						<div class="rooftopNummer_right">
                           16
                        </div>
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofq.html">
                  <div class="rooftop_right rooftopgul">
					 <div class="billede_right">
                        <img src="img/17.jpg" alt="1">
                     </div>
                     <div class="rooftopIndhold_right">
                        <div class="rooftopTekst_right">
                           <strong>Café del Rio</strong><br>
                           Portugal 1
                        </div>
						<div class="rooftopNummer_right">
                           17
                        </div>
                     </div>
                  </div>
               </a>
				<a href="#" class="rooftopa" data-featherlight="roofr.html">
                  <div class="rooftop_right rooftopgroen">
					 <div class="billede_right">
                        <img src="img/18.jpg" alt="1">
                     </div>
                     <div class="rooftopIndhold_right">
                        <div class="rooftopTekst_right">
                           <strong>Apartosuites</strong><br>
                         	Cresta de san vicente 16  
						 </div>
						<div class="rooftopNummer_right">
                           18
                        </div>
                     </div>
                  </div>
               </a>
            </div>
         </div>
      </div>
      <div class="grid grid-pad" id="map">
         <div class="col-1-1">
            <div class="content">
               <hr>
               <h1 class="overskrift tk-gooddog-newdelete orange">THE MAP</h1>
               <img src="img/map.png" alt="map" class="map">
               <hr>
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-1">
            <div class="content">
               <h1 class="overskrift tk-gooddog-newdelete darkblue">SOCIAL</h1>
            </div>
         </div>
      </div>
      <div class="grid grid-pad" id="social">
         <div class="col-1-5">
            <div class="content social">
               <a href="https://www.facebook.com/groups/153348075428756/"><img src="img/fb.png" alt="fb"></a>
            </div>
         </div>
         <div class="col-1-5">
            <div class="content social">
               <a href="https://www.instagram.com/TurismoMadridInHeights/"><img src="img/insta.png" alt="insta"></a>
            </div>
         </div>
         <div class="col-1-5">
            <div class="content social">
               <a href="http://turismomadrid.es/en/#modal1"><img src="img/mail.png" alt="mail"></a>
            </div>
         </div>
         <div class="col-1-5">
            <div class="content social">
               <a href="https://twitter.com/Turismomadrid"><img src="img/twitter.png" alt="twitter"></a>
            </div>
         </div>
         <div class="col-1-5">
            <div class="content social">
               <a href="https://www.youtube.com/user/turismomadrid"><img src="img/youtube.png" alt="yt"></a>
            </div>
         </div>
      </div>
      <div class="grid grid-pad">
         <div class="col-1-1">
            <div class="content">
               <hr>
            </div>
         </div>
      </div>
      <div class="grid grid-pad footer">
         <div class="col-1-2">
            <div class="content">
               <img src="img/logo.jpg" alt="logo" class="logo">
            </div>
         </div>
         <div class="col-1-2">
            <div class="content">
               <span class="footertekst">Please note: this is a school project and NOT a real site</span>
            </div>
         </div>
      </div>
	  <script src="http://cdn.rawgit.com/noelboss/featherlight/1.7.10/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
   </body>
</html>