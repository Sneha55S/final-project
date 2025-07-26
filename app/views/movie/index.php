<?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
<main role="main" class="container fade-in">

    <div class="py-4 text-center">
        <h1 class="mb-3 fw-bold"><i class="bi bi-search"></i> Movie Search</h1>
        <p class="lead">Search for your favorite movies and view AI-generated reviews.</p>
    </div>

  
    <?php if (isset($_SESSION['message'])):
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    ?>
        <div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : ($message['type'] === 'error' ? 'danger' : 'warning'); ?> alert-dismissible fade show" role="alert">
            <strong><?php echo ucfirst($message['type']); ?>!</strong>
            <?php echo htmlspecialchars($message['text']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

   
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="/movie/search" method="get" class="card p-4 shadow-sm border-0 bg-white">
                <div class="input-group">
                    <input 
                        required 
                        type="text" 
                        class="form-control form-control-lg" 
                        name="movie" 
                        id="movie" 
                        placeholder="Search for a movie..." 
                        value="<?php echo htmlspecialchars($_GET['movie'] ?? ''); ?>">
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
