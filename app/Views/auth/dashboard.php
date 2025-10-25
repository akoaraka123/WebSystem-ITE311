<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7fb; margin:0; padding:0; }
        .container { max-width: 900px; margin: 60px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px #ccc; }
        h2, h3 { color: #333; margin-bottom: 15px; }
        p { margin: 8px 0; }
        ul { margin-left: 20px; }
        li { margin-bottom: 6px; }
        .flash { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .flash-success { background: #d4edda; color: #155724; }
        .flash-error { background: #f8d7da; color: #721c24; }
        .btn-logout {
            display: inline-block;
            padding: 8px 15px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
        }
        .btn-upload, .btn-enroll, .btn-download {
            padding: 5px 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-upload:hover, .btn-enroll:hover, .btn-download:hover { background: #0056b3; }
        .btn-delete { background: #dc3545; }
        .btn-delete:hover { background: #a71d2a; }
        .material-item { margin-left: 25px; font-size: 14px; }
        .no-materials { color: gray; margin-left: 25px; font-style: italic; }
    </style>
</head>
<body>

    <?= view('templates/header') ?>

    <div class="container">
        <h2>Welcome, <?= esc($user['name']) ?> 🎉</h2>

        <!-- ✅ Flash Messages -->
        <?php if (!empty($flash['success'])): ?>
            <div class="flash flash-success"><?= esc($flash['success']) ?></div>
        <?php endif; ?>

        <?php if (!empty($flash['error'])): ?>
            <div class="flash flash-error"><?= esc($flash['error']) ?></div>
        <?php endif; ?>

        <p><strong>User ID:</strong> <?= esc($user['id']) ?></p>
        <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
        <p><strong>Role:</strong> <?= esc($user['role']) ?></p>

        <hr>

        <!-- ======================== -->
        <!-- ADMIN DASHBOARD -->
        <!-- ======================== -->
        <?php if ($user['role'] === 'admin'): ?>
            <h3>Admin Overview 📊</h3>
            <p><strong>Total Users:</strong> <?= esc($totalUsers ?? 0) ?></p>
            <p><strong>Total Courses:</strong> <?= esc($totalCourses ?? 0) ?></p>
            <p>You can manage users and courses in the Admin Panel.</p>

        <!-- ======================== -->
<!-- TEACHER DASHBOARD -->
<!-- ======================== -->
<?php elseif ($user['role'] === 'teacher'): ?>
    <h3>My Courses 🎓</h3>

    <?php if (!empty($myCourses)): ?>
        <ul>
            <?php foreach ($myCourses as $course): ?>
                <li id="course-<?= esc($course['id']) ?>">
                    <strong><?= esc($course['title'] ?? 'Untitled Course') ?></strong>
                    <?= !empty($course['description']) ? ' — ' . esc($course['description']) : '' ?>
                    <br>

                    <!-- ✅ AJAX Upload Form -->
                    <form class="uploadForm" data-course-id="<?= esc($course['id']) ?>" enctype="multipart/form-data">
                        <input type="file" name="material" required>
                        <button type="submit" class="btn-upload">Upload Material</button>
                    </form>

                    <!-- ✅ Uploaded materials -->
                    <ul class="materialsList">
                        <?php if (!empty($course['materials'])): ?>
                            <?php foreach ($course['materials'] as $mat): ?>
                                <li class="material-item">
                                    📄 <?= esc($mat['file_name']) ?>
                                    <a href="<?= base_url('materials/download/'.$mat['id']) ?>" class="btn-download">Download</a>
                                    <a href="<?= base_url('materials/delete/'.$mat['id']) ?>" class="btn-delete btn-download">Delete</a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="no-materials">📭 No materials uploaded yet.</li>
                        <?php endif; ?>
                    </ul>

                    <div class="uploadMessage" style="color:green; margin-top:5px;"></div>
                </li>
            <?php endforeach; ?>
        </ul>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.uploadForm').submit(function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let courseID = form.data('course-id');
                    let formData = new FormData(this);

                    $.ajax({
                        url: "<?= base_url('materials/upload_ajax') ?>/" + courseID,
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            if(response.success){
                                form.siblings('.uploadMessage').css('color','green').text('🎉 Material uploaded successfully!');

                                // Remove "no materials" message if exists
                                let list = form.siblings('.materialsList');
                                list.find('.no-materials').remove();

                                // Append new material
                                list.append('<li class="material-item">📄 '+response.file_name+
                                    ' <a href="<?= base_url('materials/download/') ?>'+response.id+'" class="btn-download">Download</a></li>');
                            } else {
                                form.siblings('.uploadMessage').css('color','red').text(response.message);
                            }
                        },
                        error: function() {
                            form.siblings('.uploadMessage').css('color','red').text('Upload failed. Please try again.');
                        }
                    });
                });
            });
        </script>
    <?php else: ?>
        <p>You don’t have any courses yet.</p>
    <?php endif; ?>


        <!-- ======================== -->
<!-- STUDENT DASHBOARD -->
<!-- ======================== -->
<?php elseif ($user['role'] === 'student'): ?>
    <h3>My Enrolled Courses ✅</h3>
    <ul id="enrolledList">
        <?php if (!empty($enrolled)): ?>
            <?php foreach ($enrolled as $course): ?>
                <li id="enrolled-<?= esc($course['id']) ?>" data-course-id="<?= esc($course['id']) ?>">
                    <strong><?= esc($course['title'] ?? 'Untitled Course') ?></strong>
                    <?= !empty($course['description']) ? ' — ' . esc($course['description']) : '' ?>

                    <!-- ✅ Course materials -->
                    <ul class="materialsList">
                        <?php if (!empty($materials[$course['id']])): ?>
                            <?php foreach ($materials[$course['id']] as $mat): ?>
                                <li class="material-item">
                                    📄 <?= esc($mat['file_name']) ?>
                                    <a href="<?= base_url('materials/download/'.$mat['id']) ?>" class="btn-download">Download</a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="no-materials">📭 No materials uploaded yet.</li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p id="noEnrolled">You are not enrolled in any course yet.</p>
        <?php endif; ?>
    </ul>

    <h3 style="margin-top:25px;">Available Courses 📚</h3>
    <ul id="availableList">
        <?php if (!empty($available)): ?>
            <?php foreach ($available as $course): ?>
                <li id="available-<?= esc($course['id']) ?>">
                    <strong><?= esc($course['title'] ?? 'Untitled Course') ?></strong>
                    <?= !empty($course['description']) ? ' — ' . esc($course['description']) : '' ?>
                    <button class="btn-enroll" data-course-id="<?= esc($course['id']) ?>">Enroll</button>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>All available courses have been enrolled. 🎉</p>
        <?php endif; ?>
    </ul>

    <div id="materialMessage" style="display:none; padding:10px; margin:10px 0; border-radius:5px; background:#d4edda; color:#155724;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // ✅ AJAX Enroll
            $('#availableList').on('click', '.btn-enroll', function(e) {
                e.preventDefault();
                let courseID = $(this).data('course-id');

                $.ajax({
                    url: "<?= base_url('auth/enroll') ?>/" + courseID,
                    type: "POST",
                    dataType: "json",
                    success: function(response) {
                        if(response.success) {
                            $('#noEnrolled').hide();
                            let courseItem = $('#available-' + courseID).clone();
                            courseItem.attr('id', 'enrolled-' + courseID);
                            courseItem.find('.btn-enroll').remove();
                            courseItem.append('<ul class="materialsList"><li class="no-materials">📭 No materials uploaded yet.</li></ul>');
                            $('#enrolledList').append(courseItem);
                            $('#available-' + courseID).remove();
                        } else {
                            alert(response.message || 'Enrollment failed.');
                        }
                    },
                    error: function() {
                        alert('Enrollment request failed.');
                    }
                });
            });

            // ✅ Polling: fetch latest materials every 5 seconds
            setInterval(function() {
                $('#enrolledList li').each(function() {
                    let courseID = $(this).data('course-id');
                    let materialsList = $(this).find('.materialsList');

                    $.ajax({
                        url: "<?= base_url('materials/getMaterials/') ?>" + courseID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            materialsList.empty();
                            if(data.length > 0) {
                                data.forEach(function(mat) {
                                    materialsList.append('<li class="material-item">📄 ' + mat.file_name + ' <a href="'+mat.download_url+'" class="btn-download">Download</a></li>');
                                });
                            } else {
                                materialsList.append('<li class="no-materials">📭 No materials uploaded yet.</li>');
                            }
                        }
                    });
                });
            }, 5000);
        });
    </script>
<?php endif; ?>


    </div>

</body>
</html>
