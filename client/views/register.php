<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="./auth/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="./auth/css/style.css">
</head>

<body>

    <div class="main">
        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Sign up</h2>
                        <form action="./?act=register" method="POST" class="register-form" id="register-form">
                            <div class="form-group">
                                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="name" id="name" placeholder="Your Name" required />
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Your Email" />
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="pass" id="pass" placeholder="Password" required />
                            </div>
                            <div class="form-group">
                                <label for="address"><i class="zmdi zmdi-pin"></i></label>
                                <input type="text" name="address" id="address" placeholder="Address" required />
                            </div>
                            <div class="form-group">
                                <label for="phonenumber"><i class="zmdi zmdi-lock"></i></label>
                                <input type="tel" name="phonenumber" id="phonenumber" placeholder="Phone Number" pattern="[0-9]{10}" title="Vui lòng nhập số điện thoại hợp lệ 10 chữ số" required />
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="submit" id="submit" class="form-submit" value="Register" />
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure>
                            <?php
                            $defaultImage = "./auth/images/default.png"; // Ảnh mặc định
                            $registerImage = "./auth/images/dangki.jpg";
                            $imagePath = file_exists($registerImage) ? $registerImage : $defaultImage;
                            ?>
                            <img src="<?php echo $imagePath; ?>" alt="sign up image" onerror="this.src='<?php echo $defaultImage; ?>'">
                        </figure>
                        <a href="?act=login" class="signup-image-link">I am already member</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>