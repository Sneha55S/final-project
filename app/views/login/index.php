<?php require_once 'app/views/templates/headerPublic.php'?>
<main role="main" class="container">
		<div class="page-header" id="banner">
				<div class="row">
						<div class="col-lg-12">
								<h1>You are not logged in</h1>
						</div>
				</div>
		</div>

<div class="row">
		<div class="col-sm-auto">
		<!-- Display any flash messages from the session -->
				<?php
				if (isset($_SESSION['message'])):
						$message = $_SESSION['message'];
						unset($_SESSION['message']); // Clear the message after displaying
				?>
						<div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : ($message['type'] === 'error' ? 'danger' : 'warning'); ?> alert-dismissible fade show" role="alert">
								<strong><?php echo ucfirst($message['type']); ?>!</strong>
								<?php echo htmlspecialchars($message['text']); ?>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
				<?php endif; ?>

				<?php
				// Display failed authentication count if available
				if (isset($_SESSION['failedAuth']) && $_SESSION['failedAuth'] > 0):
				?>
						<div class="alert alert-warning" role="alert">
								<strong>Login Failed!</strong>
								You have failed to log in <?php echo htmlspecialchars($_SESSION['failedAuth']); ?> time(s).
						</div>
				<?php endif; ?>

		<form action="/login/verify" method="post" >
		<fieldset>
			<div class="form-group">
				<label for="username">Username</label>
				<input required type="text" class="form-control" name="username">
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input required type="password" class="form-control" name="password">
			</div>
						<br>
				<button type="submit" class="btn btn-primary">Login</button>
		</fieldset>
		</form> 
	</div>
</div>
</main>
<?php require_once 'app/views/templates/footer.php' ?>
