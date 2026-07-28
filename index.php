<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - Smart Methods</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f6f9; margin: 0; padding: 40px 20px; }
        .container { max-width: 650px; margin: 0 auto; background: #ffffff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #007bff; margin-top: 0; margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 15px; }
        .btn-submit { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; }
        .btn-submit:hover { background-color: #218838; }
        hr { border: none; border-top: 1px solid #eee; margin: 25px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #007bff; color: white; }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 15px; font-weight: bold; font-size: 14px; }
        .status-0 { background-color: #f8d7da; color: #721c24; }
        .status-1 { background-color: #d4edda; color: #155724; }
        .btn-toggle { background-color: #ffc107; color: #212529; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-toggle:hover { background-color: #e0a800; }
    </style>
</head>
<body>

<div class="container">
    <h2>إضافة مستخدم جديد</h2>
    <form id="userForm">
        <div class="form-group">
            <label for="name">الاسم:</label>
            <input type="text" id="name" placeholder="أدخل الاسم..." required>
        </div>
        <div class="form-group">
            <label for="age">العمر:</label>
            <input type="number" id="age" placeholder="أدخل العمر..." required min="1">
        </div>
        <button type="submit" class="btn-submit">إرسال</button>
    </form>

    <hr>

    <h2>قائمة المستخدمين</h2>
    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>العمر</th>
                <th>الحالة</th>
                <th>الإجراء</th>
            </tr>
        </thead>
        <tbody id="usersTable">
        </tbody>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", fetchUsers);

    function fetchUsers() {
        let formData = new FormData();
        formData.append('action', 'fetch');

        fetch('api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            let tableBody = document.getElementById('usersTable');
            tableBody.innerHTML = '';

            if (!data || data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4">لا توجد بيانات حالياً</td></tr>';
                return;
            }

            data.forEach(user => {
                let statusClass = user.status == 1 ? 'status-1' : 'status-0';
                tableBody.innerHTML += `
                    <tr>
                        <td>${escapeHtml(user.name)}</td>
                        <td>${user.age}</td>
                        <td><span class="status-badge ${statusClass}">${user.status}</span></td>
                        <td>
                            <button class="btn-toggle" onclick="toggleStatus(${user.id}, ${user.status})">Toggle</button>
                        </td>
                    </tr>
                `;
            });
        });
    }

    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let nameInput = document.getElementById('name');
        let ageInput = document.getElementById('age');

        let formData = new FormData();
        formData.append('action', 'add');
        formData.append('name', nameInput.value);
        formData.append('age', ageInput.value);

        fetch('api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                nameInput.value = '';
                ageInput.value = '';
                fetchUsers();
            }
        });
    });

    function toggleStatus(id, currentStatus) {
        let formData = new FormData();
        formData.append('action', 'toggle');
        formData.append('id', id);
        formData.append('current_status', currentStatus);

        fetch('api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                fetchUsers();
            }
        });
    }

    function escapeHtml(text) {
        let div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }
</script>

</body>
</html>