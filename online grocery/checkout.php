<?php
session_start();
include('db.php');
include('express-stk.php'); // Ensure this file processes STK push correctly

$total = 0;
foreach ($_SESSION['cart'] as $id => $quantity) {
    $query = "SELECT * FROM products WHERE id=$id";
    $result = mysqli_query($conn, $query);
    if ($product = mysqli_fetch_assoc($result)) {
        $total += $product['price'] * $quantity;
    }
}

// Handle M-Pesa payment request
$errmsg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['phone_number'])) {
    $phone = trim($_POST['phone_number']);
    if (preg_match('/^07[0-9]{8}|01[0-9]{8}$/', $phone)) {
        $orderNo = "ORDER-" . strtoupper(substr(md5(time()), 0, 6));
        $response = stkPushRequest($phone, $total, $orderNo);
        if ($response['ResponseCode'] !== "0") {
            $errmsg = "Failed to process payment. Please try again.";
        } else {
            $_SESSION['cart'] = []; // Clear cart after payment
            header("Location: success.php");
            exit();
        }
    } else {
        $errmsg = "Invalid phone number. Use 07XX or 01XX format.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment - Online Grocery Shop</title>
    <link rel="stylesheet" href="styles.css">  <!-- Assuming you want to keep your styles consistent -->
    <style>
        @import url(https://fonts.googleapis.com/css?family=Lato:400,100,300,700,900);
        @import url(https://fonts.googleapis.com/css?family=Source+Code+Pro:400,200,300,500,600,700,900);

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
        }

        * {
            box-sizing: border-box;
        }

        html {
            background-color: whitesmoke;
            font-family: 'Lato', sans-serif;
        }

        .price h1 {
            font-weight: 300;
            color: #18C2C0;
            letter-spacing: 2px;
            text-align:center;
        }

        .card {
            margin-top: 30px;
            width: 520px;
        }

        .row {
            width: 100%;
            padding: 1rem 0;
            border-bottom: 1.2px solid #292C58;
        }

        .cardholder .info, .number .info {
            position: relative;
            margin-left: 40px;
        }

        .cardholder .info label, .number .info label {
            display: inline-block;
            letter-spacing: 0.5px;
            color: #8F92C3;
            width: 40%;
        }

        .cardholder .info input, .number .info input {
            display: inline-block;
            width: 55%;
            background-color: transparent;
            font-family: 'Source Code Pro';
            border: none;
            outline: none;
            margin-left: 1%;
            color: white;
        }

        .button button {
            font-size: 1.2rem;
            font-weight: 400;
            letter-spacing: 1px;
            width: 520px;
            background-color: #18C2C0;
            border: none;
            color: #fff;
            padding: 18px;
            border-radius: 5px;
            outline: none;
            cursor:pointer;
            transition: background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .button button:hover {
            background-color: #15aeac;
        }

        .error-msg {
            background: #cc2a24;
            padding: .8rem;
            color: #ffffff;
            text-align: center;
            margin-top: 10px;
        }

        /* Retain previous page footer and header styles */
        footer {
            background-color: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        footer a:hover {
            color: #18C2C0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        header a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
        }

        header a:hover {
            color: #18C2C0;
        }
        .cardholder .info input, .number .info input {
    display: inline-block;
    width: 55%;
    background-color: transparent;
    font-family: 'Source Code Pro';
    border: none;
    outline: none;
    margin-left: 1%;
    color: #333; /* Change the text color to dark (e.g., #333 for black) */
    border-bottom: 1px solid #292C58; /* Optional: Add a bottom border for better visibility */
}

.cardholder .info input::placeholder, .number .info input::placeholder {
    color: #888; /* Darken the placeholder text color if needed */
}

    </style>
</head>
<body>
    <header>
        <h1>Online Grocery Shop</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
            <a href="admin/login.php">Admin</a>
        </nav>
    </header>

    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="price">
                <h1>Total: Ksh <?php echo number_format($total, 2); ?></h1>
            </div>
            <div class="card__container">
                <div class="card">
                    <div class="row">
                        <img src="mpesa.png" style="width:30%;margin: 0 35%;">
                        <p style="color:#8F92C3;line-height:1.7;">
                            1. Enter your <b>phone number</b> and press "<b>Confirm and Pay</b>".<br>
                            2. You will receive a popup on your phone. Enter your <b>M-Pesa PIN</b> to complete the payment.
                        </p>
                        <?php if ($errmsg != ''): ?>
                            <p class="error-msg"><?php echo $errmsg; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="row number">
                        <div class="info">
                            <input type="hidden" name="orderNo" value="<?php echo $orderNo ?? 'ORDER-XXXXXX'; ?>" />
                            <label for="phone_number">Phone number</label>
                            <input id="phone_number" type="text" name="phone_number" maxlength="10" placeholder="07XXXXXXXX"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="button">
                <button type="submit">Confirm and Pay</button>
            </div>
        </form>

    
    </div>

    <footer>
        <div class="footer-content">
            <div class="about">
                <h3>About Us</h3>
                <p>Welcome to Online Grocery Shop, your number one source for fresh and quality groceries.</p>
            </div>
            <div class="contact">
                <h3>Contact Us</h3>
                <p>Email: support@groceryshop.com</p>
                <p>Phone: +254 712 345 678</p>
            </div>
        </div>
        <p class="copyright">© 2025 Online Grocery Shop. All rights reserved.</p>
    </footer>
</body>
</html>
