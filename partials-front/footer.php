<!-- Include this CSS -->
<style>
    /* Social Section */
    .social {
        background-color: #f8f9fa; /* Light background for modern look */
        padding: 20px 0;
    }
    .social ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    .social ul li {
        display: inline-block;
    }
    .social ul li a img {
        width: 40px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .social ul li a img:hover {
        transform: scale(1.2);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Footer Section */
    .footer {
        background-color: #343a40; /* Dark background */
        color: #ffffff; /* White text for contrast */
        padding: 15px 0;
    }
    .footer a {
        color: #17a2b8; /* Bootstrap info color */
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s;
    }
    .footer a:hover {
        color: #1e90ff; /* Change on hover for better interaction */
    }
    .footer p {
        margin: 0;
        font-size: 14px;
    }
</style>

<!-- Social Section Starts Here -->
<section class="social">
    <div class="container text-center">
        <ul>
            <li>
                <a href="#"><img src="https://img.icons8.com/fluent/50/000000/facebook-new.png" alt="Facebook"></a>
            </li>
            <li>
                <a href="#"><img src="https://img.icons8.com/fluent/48/000000/instagram-new.png" alt="Instagram"></a>
            </li>
            <li>
                <a href="#"><img src="https://img.icons8.com/fluent/48/000000/twitter.png" alt="Twitter"></a>
            </li>
        </ul>
    </div>
</section>
<!-- Social Section Ends Here -->

<!-- Footer Section Starts Here -->
<section class="footer">
    <div class="container text-center">
        <p>&copy; 2024 All rights reserved. Designed by <a href="#">MOUMIN AHMAD</a></p>
    </div>
</section>
<!-- Footer Section Ends Here -->
