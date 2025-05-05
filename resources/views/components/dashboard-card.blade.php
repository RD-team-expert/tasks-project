<style>
    .dashboard {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    .card {
        background-color: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 20px;
        width: 250px;
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    .card-title {
        font-size: 14px;
        color: gray;
        margin-bottom: 5px;
    }
    .card-count {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .card a {
        font-size: 14px;
        color: #1a73e8;
        text-decoration: none;
    }
</style>

<div class="dashboard">
    <div class="card">
        <p class="card-title">My Tasks</p>
        <p class="card-count">0</p>
        <a href="/tasks/myTasks">View</a>
    </div>

    <div class="card">
        <p class="card-title">Completed Tasks</p>
        <p class="card-count">0</p>
        <a href="/tasks/myTasks">View</a>
    </div>
</div>
