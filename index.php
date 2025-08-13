<?php
include 'connect.php';

// Start session to handle user login status
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Land Sale Website</title>
    <link rel="stylesheet" href="style.css"> <!-- Add your CSS file here -->
    <style>
        /* Add your styles here or link to your stylesheet */
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            color: #333;
        }
        header, footer {
            background-color: #004d00;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-around;
        }
        nav ul li {
            display: inline;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 10px ;
        }
        section {
            margin: 20px;
        }
        .post-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px;
            background-color: #f9f9f9;
        }
        .post-card img {
            width: 100%;
            height: auto;
        }
        form {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
        }
        form input, form textarea, form button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style> 
</head>
<body>
    <header>
        <h1>LandNest - Land Sale Website</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#addland">Add Land</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="#feedback">Feedback</a></li>
                <li><a href="#" id="profileIcon">Profile</a></li>
				<?php if (isset($_SESSION['user_id'])): ?>
					<!-- User is logged in, show Logout -->
					<li><a href="/land/logout.php">Logout</a></li>
				<?php else: ?>
					<!-- User is not logged in, show Login and Register -->
					<li><a href="/land/login.php">Login</a></li>
					<li><a href="/land/register.php">SignUp</a></li>
				<?php endif; ?>
            </ul>
        </nav>
    </header>

    <section id="lands">
        <h2>Lands for Sale</h2>
        <div>
            <?php
            // Fetch posts from addpost table
            $query = "SELECT * FROM addpost";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)) {
                echo '<div class="post-card">';
               
                echo '<h2>'.$row['heading'].'</h2>';
                echo '<p>'.$row['description'].'</p>';
                echo '<p>Location: '.$row['location'].'</p>';
                echo '<p>Price: $'.$row['price'].'</p>';
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <section id="about">
        <h2>About Us</h2>
        <p>Welcome to LandNest, your online platform for buying and selling land. We offer an easy-to-use interface where users can post and browse land for sale. Our goal is to connect buyers and sellers seamlessly.</p>
    </section>

    <section id="addLand">
<h2>Add Land</h2>
<form action="" method="POST" enctype="multipart/form-data">
<label for="heading">Heading</label>
<input type="text" name="heading" required minlength="5" maxlength="100" 
               placeholder="Enter a descriptive heading">
 
        <label for="description">Description</label>
               <textarea name="description" required minlength="20" maxlength="1000" 
                  placeholder="Provide a detailed description"></textarea>
 
        <label for="location">Location</label>
         <input type="text" name="location" required pattern="^[a-zA-Z0-9\s,.-]+$" 
               placeholder="e.g., 123 Main St, City, State" title="Please enter a valid location.">
 
        <label for="price">Price</label>
       <input type="number" name="price" required min="1" step="0.01" 
               placeholder="Enter the price in rupees">
 
        <button type="submit" name="post">Post</button>
</form>
</section>
        <?php
        if(isset($_POST['post'])) {
          
            $heading = $_POST['heading'];
            $description = $_POST['description'];
            $location = $_POST['location'];
            $price = $_POST['price'];
          

           

            $query = "INSERT INTO addpost (  heading, description, location, price)
                      VALUES ( '$heading', '$description', '$location', '$price')";
            if(mysqli_query($conn, $query)) {
                echo "Post added successfully!";
            } else {
                echo "Error adding post.";
            }
        }
        ?>
        <include 'connect.php';>
    </section>

    <section id="contact">
        <h2>Contact Us</h2>
        <form action="" method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <textarea name="message" placeholder="Message" required></textarea>
            <button type="submit" name="contactSubmit">Submit</button>
        </form>
        <?php
        if(isset($_POST['contactSubmit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $message = $_POST['message'];

            $query = "INSERT INTO contact (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
            if(mysqli_query($conn, $query)) {
                echo "Your message has been sent successfully!";
            } else {
                echo "Error sending message.";
            }
        }
        ?>
		
		<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#contact form');
        const nameInput = document.querySelector('input[name="name"]');
        const emailInput = document.querySelector('input[name="email"]');
        const phoneInput = document.querySelector('input[name="phone"]');
        const messageInput = document.querySelector('textarea[name="message"]');
        
        form.addEventListener('submit', function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Simple validation
            let hasError = false;

            // Name validation
            if (nameInput.value.trim() === "") {
                alert("Please enter your name.");
                hasError = true;
            }

            // Email validation (basic regex)
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                alert("Please enter a valid email address.");
                hasError = true;
            }

            // Phone number validation (basic numeric check)
            const phonePattern = /^[0-9]{10,15}$/;
            if (!phonePattern.test(phoneInput.value)) {
                alert("Please enter a valid phone number.");
                hasError = true;
            }

            // Message validation
            if (messageInput.value.trim() === "") {
                alert("Please enter a message.");
                hasError = true;
            }

            // If no errors, proceed with submission
            if (!hasError) {
                // Optionally, you can add a loader or disable the submit button while waiting for the response

                // Submit the form
                form.submit();
            }
        });
    });
</script>

		<style>
		#contact {
    background-color: #f9f9f9;
    padding: 50px;
    max-width: 600px;
    margin: 0 auto;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

#contact h2 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 20px;
    color: #333;
}

#contact form {
    display: flex;
    flex-direction: column;
}

#contact input[type="text"],
#contact input[type="email"],
#contact input[type="tel"],
#contact textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

#contact input[type="text"]:focus,
#contact input[type="email"]:focus,
#contact input[type="tel"]:focus,
#contact textarea:focus {
    border-color: #0066cc;
    outline: none;
}

#contact textarea {
    resize: vertical;
    height: 150px;
}

#contact button {
    padding: 12px 20px;
    background-color: #0066cc;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

#contact button:hover {
    background-color: #004999;
}

#contact p {
    text-align: center;
    font-size: 1rem;
    color: #333;
}

#contact .success,
#contact .error {
    text-align: center;
    margin-top: 15px;
    font-size: 1.2rem;
}

#contact .success {
    color: green;
}

#contact .error {
    color: red;
}
</style>
    </section>

    <section id="feedback">
        <h2>Feedback</h2>
        <form action="" method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="feedback" placeholder="Feedback" required></textarea>
            <button type="submit" name="feedbackSubmit">Submit</button>
        </form>
        <?php
        if(isset($_POST['feedbackSubmit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $feedback = $_POST['feedback'];

            $query = "INSERT INTO feedback (name, email, feedback) VALUES ('$name', '$email', '$feedback')";
            if(mysqli_query($conn, $query)) {
                echo "Thank you for your feedback!";
            } else {
                echo "Error submitting feedback.";
            }
        }
        ?>
        <script>
        document.querySelector('form').addEventListener('submit', function (e) {
    var name = document.querySelector('input[name="name"]').value.trim();
    var email = document.querySelector('input[name="email"]').value.trim();
    var feedback = document.querySelector('textarea[name="feedback"]').value.trim();
 
   
    if (name === '' || email === '' || feedback === '') {
        e.preventDefault();
        alert('All fields are required.');
        return;
    }
 
   
    if (name.length < 3) {
        e.preventDefault();
        alert('Name must be at least 3 characters long.');
        return;
    }
 
   
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
    }
 
   
    if (feedback.length < 10) {
        e.preventDefault();
        alert('Feedback must be at least 10 characters long.');
        return;
    }
 
 
});

    </Script>
}

<style>
#feedback {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
 
#feedback h2 {
    text-align: center;
    margin-bottom: 20px;
}
 
#feedback input,
#feedback textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}
 
#feedback button {
    width: 100%;
    padding: 10px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
 
#feedback button:hover {
    background-color: #218838;
}
 
#feedback p {
    text-align: center;
    font-size: 16px;
    color: #28a745;
}
</style>
    </section>

    <footer>
        <p>&copy; 2024 LandNest</p>
        <p>thanks come agin</p>
        <p> phone number:0777718183</p>
        <p>email:-<a> landnest@gmail.com</a></p>
    </footer>

    <script>
        document.getElementById('profileIcon').addEventListener('click', function() {
            var loggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            if(loggedIn) {
                window.location.href = 'profile.php';
            } else {
                window.location.href = 'login.php';
            }
        });
    </script>
</body>
</html>
