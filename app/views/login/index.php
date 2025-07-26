<?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header">
                    <h3 class="card-title text-center">Login</h3>
                </div>
                <div class="card-body">
                    <?php
                   
                    if (isset($_SESSION['register_message'])):
                        $message = $_SESSION['register_message'];
                        unset($_SESSION['register_message']); 
                    ?>
                        <div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : ($message['type'] === 'error' ? 'danger' : 'warning'); ?> alert-dismissible fade show" role="alert">
                            <strong><?php echo ucfirst($message['type']); ?>!</strong>
                            <?php echo htmlspecialchars($message['text']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form action="/login/verify" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    <p class="text-center mt-3">
                        Don't have an account? <a href="/register">Sign up here</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
