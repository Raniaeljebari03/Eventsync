<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EventSync Platform</title>
    <style>
        /* Your existing CSS here - unchanged */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f0f8ff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: none;
            height: 100vh;
            background: #f0f8ff;
            overflow: hidden;
        }
        .logo-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem 3rem;
            background: #f0f8ff;
            position: relative;
        }
        .logo-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.02) 100%);
        }
        .logo-section img {
            width: 310px;
            height: auto;
            z-index: 1;
            filter: drop-shadow(0 4px 8px rgba(0, 123, 255, 0.1));
        }
        .form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 5rem;
            background: white;
            border-radius: 0 32px 32px 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
        .form-header {
            text-align: left;
            margin-bottom: 3rem;
        }
        .form-header h1 {
            color: #1e40af;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            position: relative;
            letter-spacing: -0.025em;
        }
        .form-header h1::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 0;
            width: 64px;
            height: 4px;
            background: #3b82f6;
            border-radius: 2px;
        }
        .message {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.75rem;
            text-align: center;
            font-weight: 500;
            animation: fadeIn 0.3s ease;
            font-size: 0.875rem;
        }
        .message.success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid #10b981;
            color: #047857;
        }
        .message.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
            color: #dc2626;
        }
        .debug {
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            font-size: 0.75rem;
            line-height: 1.4;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .input-group label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.25rem;
        }
        .input-group input {
            width: 100%;
            padding: 1rem 1.25rem;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            color: #111827;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        .input-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: white;
        }
        .input-group input::placeholder {
            color: #9ca3af;
        }
        .password-group {
            position: relative;
        }
        .password-group input {
            padding-right: 3.5rem;
        }
        .password-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            font-size: 1.25rem;
            user-select: none;
        }
        .remember-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.25rem 0;
            font-size: 0.875rem;
        }
        .remember-group input[type="checkbox"] {
            width: auto;
            accent-color: #3b82f6;
        }
        .remember-group label {
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }
        .login-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 1.125rem 1.75rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin: 0.75rem 0;
        }
        .login-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .login-btn::before {
            content: '🔓';
            font-size: 1.125rem;
        }
        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            font-size: 0.875rem;
        }
        .login-footer a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        .login-footer a:hover {
            color: #2563eb;
            text-decoration: underline;
        }
        .demo-info {
            text-align: center;
            margin-top: 2.5rem;
            font-size: 0.75rem;
            color: #9ca3af;
            font-style: italic;
        }
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                height: auto;
                max-width: none;
            }
            .logo-section, .form-section {
                flex: none;
                padding: 2.5rem 2rem;
                border-radius: 0;
                height: auto;
            }
            .logo-section {
                order: 2;
                padding-top: 1.5rem;
                padding-bottom: 1.5rem;
            }
            .form-section {
                order: 1;
                border-radius: 20px;
                margin: 1.5rem;
                margin-bottom: 0;
            }
            .form-header h1 {
                font-size: 2rem;
            }
            .login-footer {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
    </head>
<body>
    <div class="login-wrapper">
        <div class="logo-section">
            <img src="https://eventsync.io/assets/logo-018c7909b72b80b364152f440f79ea8678c8ff237502415f489f821ed15fe284.svg" alt="EventSync Logo">
        </div>
        <div class="form-section">
          
            <?php if ($debug): ?>
                <div class="debug"><?php echo $debug; ?></div>
            <?php endif; ?>
     
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
     
            <form class="login-form" method="POST" action="login.php">
                <div class="input-group">
                    <label for="caswallet_id">CasWallet ID</label>
                    <input type="text" id="caswallet_id" name="caswallet_id" placeholder="Enter your CasWallet ID" value="<?php echo htmlspecialchars($_POST['caswallet_id'] ?? ''); ?>" required>
                </div>
 