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
        .btn-logout { display: inline-block; padding: 8px 15px; background-color: #dc3545; color: #fff; border-radius: 5px; text-decoration: none; margin-top: 15px; }
        .btn-upload, .btn-enroll, .btn-download { padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn-upload:hover, .btn-enroll:hover, .btn-download:hover { background: #0056b3; }
        .btn-delete { background: #dc3545; }
        .btn-delete:hover { background: #a71d2a; }
        .material-item { margin-left: 25px; font-size: 14px; }
        .no-materials { color: gray; margin-left: 25px; font-style: italic; }
        .uploadMessage { margin-top: 5px; font-size: 14px; }
    </style>
</head>
<body>

    <?= view('templates/header') ?>

    <div class="container">
        <?= csrf_field() ?>
        <h2>Welcome, <?= esc($user['name']) ?> 🎉</h2>

        <!-- Flash Messages -->
        <?php if (!empty($flash['success'])): ?>
            <div class="flash flash-success"><?= esc($flash['success']) ?></div>
        <?php endif; ?>

        <!-- STUDENT DASHBOARD -->
        <?php if ($user['role'] === 'student'): ?>
            <h3>My Enrolled Courses 📚</h3>
            <?php if (!empty($enrolled)): ?>
                <ul>
                <?php foreach ($enrolled as $course): ?>
                    <li>
                        <strong><?= esc($course['title'] ?? 'Untitled Course') ?></strong>
                        <?= !empty($course['description']) ? ' — ' . esc($course['description']) : '' ?><br>
                        <ul class="materialsList">
                        <?php if (!empty($materials[$course['id']])): ?>
                            <?php foreach ($materials[$course['id']] as $mat): ?>
                                <li class="material-item">
                                    📄 <?= esc($mat['file_name']) ?>
                                    <a href="<?= base_url('materials/download/'.$mat['id']) ?>" class="btn-download">Download</a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="no-materials">📭 No materials yet for this course.</li>
                        <?php endif; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You are not enrolled in any courses yet.</p>
            <?php endif; ?>

            <h3 class="mt-4">Available Courses ➕</h3>
            <!-- Global CSRF token holder for AJAX -->
            <?= csrf_field() ?>
            <?php if (!empty($available)): ?>
                <ul>
                <?php foreach ($available as $course): ?>
                    <li>
                        <strong><?= esc($course['title'] ?? 'Untitled Course') ?></strong>
                        <?= !empty($course['description']) ? ' — ' . esc($course['description']) : '' ?>
                        <button type="button" class="btn-enroll" data-course-id="<?= esc($course['id']) ?>" style="margin-left:10px;">Enroll</button>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No available courses to enroll.</p>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($flash['error'])): ?>
            <div class="flash flash-error"><?= esc($flash['error']) ?></div>
        <?php endif; ?>

        <p><strong>User ID:</strong> <?= esc($user['id']) ?></p>
        <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
        <p><strong>Role:</strong> <?= esc($user['role']) ?></p>

        <hr>

        <!-- TEACHER DASHBOARD -->
        <?php if ($user['role'] === 'teacher'): ?>
            <h3>My Courses 🎓</h3>
            <?php if (!empty($myCourses)): ?>
                <ul>
                <?php foreach ($myCourses as $course): ?>
                    <li id="course-<?= esc($course['id']) ?>">
                        <strong><?= esc($course['title'] ?? 'Untitled Course') ?></strong>
                        <?= !empty($course['description']) ? ' — ' . esc($course['description']) : '' ?><br>

                        <!-- AJAX Upload Form -->
                        <form class="uploadForm" data-course-id="<?= esc($course['id']) ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="file" name="material" required>
                            <button type="submit" class="btn-upload">Upload Material</button>
                        </form>
                        <div class="uploadMessage"></div>

                        <!-- Uploaded materials -->
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
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You don’t have any courses yet.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.uploadForm').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let courseID = form.data('course-id');
        let formData = new FormData(this);

        // Correct CSRF handling
        let csrfInput = form.find('input[name^="<?= csrf_token() ?>"]');
        let csrfName = csrfInput.attr('name');
        let csrfHash = csrfInput.val();
        formData.set(csrfName, csrfHash); // ✅ use set() to replace token

        $.ajax({
            url: "<?= base_url('materials/upload_ajax') ?>/" + courseID,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log('Server response:', response);

                // Update CSRF token for next request
                csrfInput.val(response.csrf_hash);

                if(response.success){
                    form.siblings('.uploadMessage').css('color','green').text('🎉 Material uploaded successfully!');

                    let list = form.siblings('.materialsList');
                    list.find('.no-materials').remove();

                    list.append('<li class="material-item">📄 '+response.file_name+
                        ' <a href="<?= base_url('materials/download/') ?>'+response.id+'" class="btn-download">Download</a>' +
                        ' <a href="<?= base_url('materials/delete/') ?>'+response.id+'" class="btn-delete btn-download">Delete</a>' +
                        '</li>');
                } else {
                    form.siblings('.uploadMessage').css('color','red').text(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr.responseText);
                form.siblings('.uploadMessage').css('color','red').text('❌ Upload failed. Check console.');
            }
        });
    });

    // AJAX Enroll handler for students
    $(document).on('click', '.btn-enroll', function(e){
        e.preventDefault();
        const btn = $(this);
        const courseID = btn.data('course-id');

        // CSRF token (global hidden input rendered above)
        const csrfInput = $('input[name^="<?= csrf_token() ?>"]').first();
        const csrfName = csrfInput.attr('name');
        const csrfHash = csrfInput.val();

        $.ajax({
            url: '<?= base_url('course/enroll') ?>',
            type: 'POST',
            dataType: 'json',
            data: { [csrfName]: csrfHash, course_id: courseID },
            success: function(resp){
                if (resp.csrf_hash) csrfInput.val(resp.csrf_hash);
                if (resp.success) {
                    btn.prop('disabled', true).text('Enrolled');
                    alert(resp.message || 'Enrolled successfully');
                    // Optional: move this course to the enrolled list without reload
                    // Implementation depends on your DOM structure
                } else {
                    alert(resp.message || 'Enrollment failed');
                }
            },
            error: function(xhr){
                alert('Request failed: ' + xhr.status);
            }
        });
    });

    // ========== Notifications (Lab 8) ==========
    const notifBadge = $('#notifBadge');
    const notifDropdown = $('#notifDropdown');
    const notifList = $('#notifList');
    const notifBell = $('#notifBell');

    function fetchNotifications(limit=10){
        $.get('<?= base_url('notifications') ?>', {limit}, function(resp){
            if (!resp || !resp.success) return;
            if (resp.csrf_hash) {
                const csrfInput = $('input[name^="<?= csrf_token() ?>"]').first();
                csrfInput.val(resp.csrf_hash);
            }
            // Badge
            if (resp.unread > 0){
                notifBadge.text(resp.unread).show();
            } else {
                notifBadge.hide();
            }
            // List
            let html = '';
            if (resp.items && resp.items.length){
                resp.items.forEach(function(it){
                    const time = it.created_at ? it.created_at : '';
                    const safeMsg = $('<div>').text(it.message).html();
                    html += '<div class="alert alert-info d-flex justify-content-between align-items-start" role="alert" data-notif-id="'+ it.id +'">'
                         +  '<div><div>'+ safeMsg +'</div><div style="font-size:12px; color:#666;">'+ time +'</div></div>'
                         +  '<button type="button" class="btn btn-sm btn-outline-secondary notif-mark" data-id="'+ it.id +'">Mark as Read</button>'
                         +  '</div>';
                });
            } else {
                html = '<div class="alert alert-light" role="alert">No notifications.</div>';
            }
            notifList.html(html);
        }, 'json');
    }

    function markAllRead(){
        const csrfInput = $('input[name^="<?= csrf_token() ?>"]').first();
        const csrfName = csrfInput.attr('name');
        const csrfHash = csrfInput.val();
        $.post('<?= base_url('notifications/read') ?>', {[csrfName]: csrfHash}, function(resp){
            if (resp && resp.csrf_hash) csrfInput.val(resp.csrf_hash);
            notifBadge.hide();
        }, 'json');
    }

    // Toggle dropdown on bell click (no auto mark-all to follow guide)
    notifBell.on('click', function(e){
        e.preventDefault();
        notifDropdown.toggle();
    });

    // Mark a single notification as read (button in each alert)
    $(document).on('click', '.notif-mark', function(){
        const btn = $(this);
        const id = btn.data('id');
        const csrfInput = $('input[name^="<?= csrf_token() ?>"]').first();
        const csrfName = csrfInput.attr('name');
        const csrfHash = csrfInput.val();
        $.post('<?= base_url('notifications/mark_read') ?>/'+id, {[csrfName]: csrfHash}, function(resp){
            if (resp && resp.csrf_hash) csrfInput.val(resp.csrf_hash);
            if (resp && resp.success){
                // Remove from list and decrement badge visually
                btn.closest('[data-notif-id="'+id+'"]').remove();
                const current = parseInt(notifBadge.text() || '0', 10);
                const next = Math.max(0, current - 1);
                if (next > 0){ notifBadge.text(next).show(); } else { notifBadge.hide(); }
                // Re-fetch to ensure consistency without page refresh
                fetchNotifications(10);
            }
        }, 'json');
    });

    // Initial fetch and polling every 60s as per guide
    fetchNotifications(10);
    setInterval(fetchNotifications, 60000);
});
</script>

</body>
</html>
