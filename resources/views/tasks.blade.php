<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Tasks UI</title>
  <style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        max-width:900px;
        margin:20px auto;
        padding:0 10px;
    }

    form{
        margin-bottom:20px; 
        background:#f7f7f7; 
        padding:12px; 
        border-radius:8px;
    }

    label{
        display:block;
        margin:8px 0 4px;
    }

    input[type="text"], textarea, select{
        width:100%; 
        padding:8px; 
        box-sizing:border-box;
    }

    button{
        margin-top:8px;
        padding:8px 12px; 
        border-radius:6px; 
        cursor:pointer;
    }

    ul{
        padding-left:20px;
    }

    li{
        margin:8px 0;
        padding:8px;
        border:1px solid #eee;
        border-radius:6px;
        display:flex;
        justify-content:space-between;
        align-items:center;
    }

  </style>
</head>
<body>
  <h1>Управление задачами</h1>

  <form id="task-form">
    <input type="hidden" id="task-id" value="">
    <label for="title">Задание</label>
    <input id="title" name="title" type="text" required>

    <label for="description">Описание</label>
    <textarea id="description" name="description" rows="4"></textarea>

    <label for="status">Статус</label>
    <select id="status" name="status">
      <option value="pending">pending</option>
      <option value="in_progress">in_progress</option>
      <option value="completed">completed</option>
    </select>

    <div>
      <button id="save-btn" type="submit">Сохранить</button>
      <button id="reset-btn" type="button">Очистить</button>
    </div>
  </form>

  <h2>Список заданий</h2>
  <ul id="tasks-list"></ul>

<script>

   // (function () {
        const apiUrl = "{{ url('') }}/api/tasks";

        const form = document.getElementById('task-form');
        const idField = document.getElementById('task-id');
        const titleInput = document.getElementById('title');
        const descInput = document.getElementById('description');
        const statusSelect = document.getElementById('status');
        const tasksList = document.getElementById('tasks-list');
        const resetBtn = document.getElementById('reset-btn');

        async function fetchTasks() {
            try {
                const res = await fetch(apiUrl);
                if (!res.ok) throw new Error('Fetch failed: ' + res.status);

                const tasks = await res.json();
                renderTasks(tasks);
            } catch (err) {
                console.error(err);
                tasksList.innerHTML = '<li style="color:red;">Ошибка загрузки задач</li>';
            }
        }

        function renderTasks(tasks) {
            if (!Array.isArray(tasks) || tasks.length === 0) {
                tasksList.innerHTML = '<li>Задач нет</li>';
                return;
            }

            tasksList.innerHTML = '';
            tasks.forEach(task => {
                const li = document.createElement('li');

                const left = document.createElement('div');
                const title = document.createElement('div');
                title.textContent = task.title;

                const meta = document.createElement('div');
                meta.textContent = `${task.status} • ${new Date(task.created_at).toLocaleString()}`;
                left.appendChild(title);
                left.appendChild(meta);

                const actions = document.createElement('div');
                const editBtn = document.createElement('button');
                editBtn.textContent = 'Edit';
                editBtn.type = 'button';
                editBtn.addEventListener('click', () => loadTaskToForm(task));

                const delBtn = document.createElement('button');
                delBtn.textContent = 'Delete';
                delBtn.type = 'button';
                delBtn.addEventListener('click', () => deleteTask(task.id));

                actions.appendChild(editBtn);
                actions.appendChild(delBtn);

                li.appendChild(left);

                if (task.description) {
                    const desc = document.createElement('div');
                    desc.textContent = task.description;
                    left.appendChild(desc);
                }

                li.appendChild(actions);
                tasksList.appendChild(li);

            });
        }

        function loadTaskToForm(task) {
            idField.value = task.id;
            titleInput.value = task.title || '';
            descInput.value = task.description || '';
            statusSelect.value = task.status || 'pending';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            idField.value = '';
            titleInput.value = '';
            descInput.value = '';
            statusSelect.value = 'pending';
        }

        async function deleteTask(id) {
            try {
                const res = await fetch(apiUrl + '/' + id, { method: 'DELETE' });
                if (!res.ok) {
                    const err = await res.json().catch(()=>({message:res.statusText}));
                    throw new Error(err.message || 'Delete failed');
                }

                await fetchTasks();
                if (idField.value == id) resetForm();

            } catch (err) {
                alert('Ошибка удаления: ' + err.message);
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = {
                title: titleInput.value.trim(),
                description: descInput.value.trim(),
                status: statusSelect.value
            };

            if (!payload.title) {
                alert('Title не должен быть пустым');
                return;
            }

            const editingId = idField.value;
            try {
                let res;
                if (editingId) {
                    res = await fetch(apiUrl + '/' + editingId, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                    });
                } else {
                    res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                    });
                }

                if (!res.ok) {
                    const err = await res.json().catch(()=>({message:res.statusText}));
                    throw new Error(err.message || 'Save failed');
                }

                resetForm();
                await fetchTasks();
            } catch (err) {
                alert('Ошибка сохранения: ' + err.message);
            }
        });

        resetBtn.addEventListener('click', resetForm);

        fetchTasks();
//})();

</script>
</body>
</html>
