<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
	<!DOCTYPE html>
	<html lang="es">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="{main_description}">
		<meta name="author" content="Julio César Márquez Martínez">

		<link rel="apple-touch-icon" sizes="57x57" href="/assets/favicons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/assets/favicons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/assets/favicons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/assets/favicons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/assets/favicons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/assets/favicons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/assets/favicons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/assets/favicons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192" href="/assets/favicons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/assets/favicons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png">
		<link rel="manifest" href="/assets/favicons/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/assets/favicons/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">

		<!-- Custom styles for this template -->
		<link href="https://fonts.googleapis.com/css?family=VT323" rel="stylesheet">

		<title>Jule de Hule | Error 404</title>
		<style type="text/css">
			*{
				box-sizing: border-box;
			}
			body{
				font-family: 'VT323', monospace;
			}
			#container{
				background-image: url('/assets/img/error/jeremy-bishop-194149-unsplash.jpg');
				background-size:cover;
				background-repeat: no-repeat;
				position:absolute;
				top:0;
				left:0;
				width:100%;
				height:100%;
				width: 100%;
    			padding: 100px 15px;
			}
			h1 {
				color: #000;
				position: relative;
				width:100%;
				display:block;
			}

			h1 span{
				background: white;
			}

			h2{
				color: #000;
				position: relative;
				width:100%;
				display:block;
				text-align:right;
			}

			h2 a{
				background: white;
				text-decoration: none;
				color: #000;
			}
		</style>
	</head>

	<body>
		<div id="container">
			<h1><span id="app"></span></h1>
			<h2><a href="/blog/">Regresar al blog</a></h2>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/TypewriterJS/1.0.0/typewriter.min.js"></script>
		<script>
			
			setTimeout(function(){
				var app = document.getElementById('app');

				var typewriter = new Typewriter(app, {
					loop: true
				});

				typewriter.typeString('No todos aquellos que divagan están *perdidos*')
				.pauseFor(2500)
				.deleteAll()
				.typeString('Pero en esta ocasión...')
				.pauseFor(2500)
				.deleteChars(3)
				.typeString(' ¡Lo estás!')
				.pauseFor(2500)
				.start();
			},1000)
			
		</script>
	</body>

	</html>