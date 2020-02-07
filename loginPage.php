<html>
	<head>
		<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/animate.css">
		<link rel="stylesheet" href="css/login.css">
	</head>
	<body>
		<form name="frmUser" method="post" action="user_login_session.php">
			<div class="container">
				<div class="top">
					<h1 id="title" class="hidden"><span>STARLINE</span></h1>
				</div>
				<div class="login-box">
					<div class="box-header">
						<h2>Log In</h2>
					</div>
					<label for="username">Username</label>
					<br/>
					<input type="text" name="user_name" id="username" required>
					<br/>
					<label for="password">Password</label>
					<br/>
					<input type="password" name="password" id="password" required>
					<br/>
					<button type="submit">Sign In</button>
				</div>
			</div>
		</form>
	</body>
</html>

