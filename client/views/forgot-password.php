<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="./auth/fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="./auth/css/style.css">
    <style>
        .password-display {
            color: #ff0000;
            margin-top: 10px;
            font-size: 14px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="main">
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure>
                            <img src="./auth/images/forgot-password.jpg" alt="forgot password image">
                        </figure>
                        <a href="?act=login" class="signup-image-link">Return to login</a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Forgot Password</h2>
                        <form method="POST" class="register-form" id="forgot-form">
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Your Email" required/>
                            </div>
                            <?php if(isset($password)): ?>
                                <span class="password-display">Your Password is: <?php echo $password; ?></span>
                            <?php endif; ?>
                            <?php if(isset($error)): ?>
                                <span class="password-display"><?php echo $error; ?></span>
                            <?php endif; ?>
                            <div class="form-group form-button">
                                <input type="submit" name="submit" id="submit" class="form-submit" value="Get Password"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html> 