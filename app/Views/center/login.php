<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Slysis Academy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #1d2671, #c33764);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #fff;
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,.4);
        }
        .btn-primary {
            background: #1d2671;
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: #c33764;
            transform: translateY(-2px);
        }
        .form-control {
            height: 45px;
            border-radius: 8px;
        }
        .error-box {
            background: #fff5f5;
            color: #e53e3e;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #feb2b2;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* --- New Letter Captcha Styling --- */
        .captcha-box {
            background: #f1f3f5;
            border: 1px solid #ced4da;
            padding: 10px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        .captcha-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #1d2671;
            text-decoration: line-through; /* Text ke beech mein line */
            user-select: none; /* User copy na kar sake */
            display: inline-block;
            transform: skewX(-10deg); /* Text thoda tedha dikhega */
        }
        /* Background noise effect */
        .captcha-box::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0.1;
            background-image: radial-gradient(#000 1px, transparent 1px);
            background-size: 4px 4px;
            pointer-events: none;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="text-center mb-4">
        <h3 style="font-weight: 800; color: #1d2671;">SLYSIS</h3>
        <p class="text-muted small">Secure Access Portal</p>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="error-box text-center">
            ⚠️ <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('login-process') ?>">
        <?= csrf_field() ?> 

        <div class="form-group mb-3">
            <label class="small font-weight-bold">Email Address</label>
            <input type="email" name="email" class="form-control"
                   placeholder="example@mail.com" value="<?= old('email') ?>" required autofocus>
        </div>

        <div class="form-group mb-3">
            <label class="small font-weight-bold">Password</label>
            <input type="password" name="password" class="form-control"
                   placeholder="••••••••" required>
        </div>

        <div class="form-group mb-4">
            <label class="small font-weight-bold">Security Code</label>
            <div class="captcha-box text-center mb-2">
                <span class="captcha-text"><?= $captcha_text ?></span>
            </div>
            <input type="text" name="captcha_answer" class="form-control text-center font-weight-bold"
                   placeholder="Enter the code shown above" 
                   required autocomplete="off" 
                   style="text-transform: uppercase;">
        </div>

        <button class="btn btn-primary btn-block shadow-sm">
            Sign In
        </button>
        
        <div class="text-center mt-3">
            <small class="text-muted">Protected by Alphanumeric Captcha</small>
        </div>
    </form>

</div>

</body>
</html>