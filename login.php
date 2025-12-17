<?php
session_start();

// Ambil pesan error / success (pengganti Session::get)
$error   = $_GET['error']   ?? null;
$success = $_GET['success'] ?? null;

// Set intended URL jika belum login
if (!isset($_SESSION['login']) && !isset($_SESSION['intended_url'])) {
    $_SESSION['intended_url'] = $_SERVER['HTTP_REFERER'] ?? 'index.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>SILAB</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="icon" type="image/png" href="/Silab-v3/public/admin_template/img/QC.png"/>
	<link rel="stylesheet" type="text/css" href="/Silab-v3/public/login_template/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/Silab-v3/public/login_template/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/Silab-v3/public/login_template/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="/Silab-v3/public/login_template/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="/Silab-v3/public/login_template/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="/Silab-v3/public/login_template/css/util.css">
	<link rel="stylesheet" type="text/css" href="/Silab-v3/public/login_template/css/main.css">
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="/Silab-v3/public/admin_template/img/QC.png" alt="Logo QC">
				</div>

				<form class="login100-form validate-form" method="POST" action="login_proses.php">

					<span class="login100-form-title">
						Login
					</span>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

					<div class="wrap-input100 validate-input">
						<input class="input100" type="text" name="username" placeholder="Masukkan username" required autofocus>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input">
						<input class="input100" type="password" name="password" placeholder="Masukkan Password" required>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-key" aria-hidden="true"></i>
						</span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							Login
						</button>
					</div>

                    <div class="text-center p-t-136">
                        <p class="txt2">Developed by Andik Kurniawan</p>
                    </div>

                </form>

			</div>
		</div>
	</div>

	<script src="/Silab-v3/public/login_template/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="/Silab-v3/public/login_template/vendor/bootstrap/js/popper.js"></script>
	<script src="/Silab-v3/public/login_template/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="/Silab-v3/public/login_template/vendor/select2/select2.min.js"></script>
	<script src="/Silab-v3/public/login_template/vendor/tilt/tilt.jquery.min.js"></script>
	<script>
		$('.js-tilt').tilt({ scale: 1.1 })
	</script>
	<script src="/Silab-v3/public/login_template/js/main.js"></script>

</body>
</html>
