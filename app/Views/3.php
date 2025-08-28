<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3 - My CI App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('template') ?>">My CI App</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('template') ?>">1</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('template/2') ?>">2</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?= base_url('template/3') ?>">3</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        <h1>Contact Page</h1>
        <p>You can reach me at: <b>student@example.com</b></p>
    </div>
</body>
</html>
