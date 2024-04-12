<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhotoApp</title>
    <link rel="icon" href="../assets/appicon.png">
    
    <style>
       
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-image: url('../assets/image.jpeg');
            background-size: cover;
            background-position: center;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 100px 0;
        }

        .header-content {
            position: relative;
            z-index: 1;
            color: brown;
        }
        .main-content {
            position: relative;
            padding: 50px 0;
        }

        #video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover;
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 40px;
        }

        .feature {
            flex-basis: calc(50% - 40px);
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .feature h2 {
            margin-top: 0;
        }
        .testimonial {
            background-color: #f9f9f9;
            padding: 50px 0;
            text-align: center;
        }
        .footer {
            background-color: #333;
            color: #fff;
            padding: 50px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
            .feature {
                flex-basis: 100%;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>Welcome to My PhotoApp</h1>
            <p><strong>Book Sessions with Your Favorite Photographers!<br>Meet Your Customers</strong></p>
            <a href="../login/signup.php" class="btn">Sign Up</a>
            <p>Already have an account? <a href="../login/login.php">Login Here</a></p>
        </div>
    </header>

    <section class="main-content">
        <video autoplay muted loop id="video-background">
            <source src="../assets/background.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="container">
            <div class="features">
                <div class="feature">
                    <h2>Session Booking</h2>
                    <p>Easily book a Session with your favorite photographer. Our interface makes it easy for photographers to list sessions, and users to book.</p>
                </div>
                <div class="feature">
                    <h2>Photo Uploading</h2>
                    <p>An opportunity for photographers to showcase their favorite work and get feedback from our community.</p>
                </div>
            </div>
            <h2>Why Choose Us?</h2>
            <ul>
                <li>User-friendly interface</li>
                <li>Secure and private</li>
            </ul>
        </div>
    </section>

    <section class="testimonial">
        <div class="container">
            <h2>Testimonials</h2>
            <p>"I love how easy it is to book sessions" - Nicole</p>
            <p>"Thanks to My PhotoApp, I am able to meet my customers easily" - INEZA</p>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <h2>Contact Us</h2>
            <p>Have questions or feedback? We're here to help!</p>
            <p>Email: nicole.inezaz@ashesi.edu.gh</p>
            <p>Phone: 0234738924</p>
        </div>
    </footer>

</body>
</html>
