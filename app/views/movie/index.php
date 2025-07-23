    <?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
    	<main role="main" class="container">
    			<div class="page-header" id="banner">
    					<div class="row">
    							<div class="col-lg-12">
    									<h1>Movie Search</h1>
    							</div>
    					</div>
    			</div>

    	<div class="row">
    			<div class="col-sm-auto">
    					<?php
    					if (isset($_SESSION['message'])):
    							$message = $_SESSION['message'];
    							unset($_SESSION['message']);
    					?>
    							<div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : ($message['type'] === 'error' ? 'danger' : 'warning'); ?> alert-dismissible fade show" role="alert">
    									<strong><?php echo ucfirst($message['type']); ?>!</strong>
    									<?php echo htmlspecialchars($message['text']); ?>
    									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    							</div>
    					<?php endif; ?>

    			<form action="/movie/search" method="get" > <!-- Changed to clean URL -->
    			<fieldset>
    				<div class="form-group">
    					<label for="movie">Movie</label>
    					<input required type="text" class="form-control" name="movie" id="movie" placeholder="Search for a movie..." value="<?php echo htmlspecialchars($_GET['movie'] ?? ''); ?>">
    				</div>
    							<br>
    					<button type="submit" class="btn btn-primary">Search</button>
    			</fieldset>
    			</form>
    		</div>
    	</div>
    		<br>
    	</main>
    <?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
    