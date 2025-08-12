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

    .meta{
        font-size:0.9em;
        color:#555;
    }

    .actions button{
        margin-left:6px;
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

</script>
</body>
</html>
