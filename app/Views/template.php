<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeIgniter + Bootstrap</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional Bootstrap JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <!-- Test Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('template') ?>">My CI App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
             <li class="nav-item">
                  <a class="nav-link <?= url_is('template') ? 'active' : '' ?>" href="<?= base_url('template') ?>">Home</a>
             </li>
              <li class="nav-item">
                  <a class="nav-link <?= url_is('template/page2') ? 'active' : '' ?>" href="<?= base_url('template/page2') ?>">About</a>
             </li>
                 <li class="nav-item">
                  <a class="nav-link <?= url_is('template/page3') ? 'active' : '' ?>" href="<?= base_url('template/page3') ?>">Contact</a>
             </li>
            </ul>
        </div>

        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-primary">Hello CodeIgniter + Bootstrap!</h1>
        <p class="lead">This is a test page using Bootstrap.</p>
    </div>

</body>
</html>
