<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task Assigned</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #202124;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #4285f4, #34a853);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .task-card {
            background: #f8f9fa;
            border-left: 4px solid #4285f4;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .task-title {
            font-size: 20px;
            font-weight: 700;
            color: #202124;
            margin-bottom: 15px;
        }
        .task-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #5f6368;
        }
        .meta-label {
            font-weight: 600;
            color: #202124;
        }
        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .priority-high {
            background: rgba(234, 67, 53, 0.1);
            color: #ea4335;
        }
        .priority-medium {
            background: rgba(251, 188, 4, 0.1);
            color: #f57c00;
        }
        .priority-low {
            background: rgba(52, 168, 83, 0.1);
            color: #34a853;
        }
        .description {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #e8eaed;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #4285f4, #34a853);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 24px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #5f6368;
        }
        .footer a {
            color: #4285f4;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 New Task Assigned</h1>
        </div>

        <div class="content">
            <div class="greeting">Hi {{ $assignedTo->name }}! 👋</div>

            <p>You have been assigned a new task by <strong>{{ $assignedBy->name }}</strong>.</p>

            <div class="task-card">
                <div class="task-title">{{ $task->title }}</div>

                <div class="task-meta">
                    <div class="meta-item">
                        <span class="meta-label">Priority:</span>
                        <span class="priority-badge priority-{{ $task->priority }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    @if($task->deadline)
                    <div class="meta-item">
                        <span class="meta-label">📅 Deadline:</span>
                        <span>{{ $task->deadline->format('l, F d, Y') }}</span>
                    </div>
                    @endif

                    @if($task->point_reward)
                    <div class="meta-item">
                        <span class="meta-label">🏆 Reward:</span>
                        <span>{{ $task->point_reward }} points</span>
                    </div>
                    @endif
                </div>

                @if($task->description)
                <div class="description">
                    <strong>Description:</strong><br>
                    {{ $task->description }}
                </div>
                @endif
            </div>

            <center>
                <a href="{{ config('app.url') }}/{{ strtolower($assignedTo->role) }}/task-board" class="button">
                    View Task Details →
                </a>
            </center>

            <p style="margin-top: 30px; font-size: 14px; color: #5f6368;">
                Please complete this task by the deadline to earn your reward points and contribute to your team's success! 🚀
            </p>
        </div>

        <div class="footer">
            <p>
                <strong>GDGoC Gamification System</strong><br>
                Google Developer Groups on Campus - Universitas Pasundan
            </p>
            <p>
                <a href="{{ config('app.url') }}">Visit Dashboard</a> •
                <a href="{{ config('app.url') }}/public/leaderboard">View Leaderboard</a>
            </p>
        </div>
    </div>
</body>
</html>
