
<?php

require "saerch_deatils.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>/* Global font and color setup */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f3f0ff;
    color: #333;
    margin: 0;
    padding: 0;
}

:root {
    --primary-color: #6200ea; /* Purple */
    --primary-hover: #3700b3;
    --secondary-color: #b388ff; /* Light Purple */
    --bg-color: #f3f0ff;
    --text-color: #333;
    --box-bg: #fff;
    --box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Styling for the entire doctor profile section */
.doctor-profile {
    max-width: 800px;
    margin: 40px auto;
    padding: 30px;
    background-color: var(--box-bg);
    border-radius: 10px;
    box-shadow: var(--box-shadow);
    border-left: 5px solid var(--primary-color);
    position: relative;
}

/* Styling the header inside the doctor profile */
.doctor-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.doctor-header .profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
    object-fit: cover;
    border: 2px solid var(--secondary-color);
    background-color: var(--bg-color);
}

/* Styling the doctor info section */
.doctor-info h3 {
    margin: 0;
    font-size: 1.8em;
    color: var(--primary-color);
}

.doctor-info p {
    margin: 5px 0;
    font-size: 1em;
    color: var(--text-color);
}

.doctor-info ul {
    list-style-type: none;
    padding: 0;
}

.doctor-info ul li {
    margin: 5px 0;
    font-size: 0.95em;
    color: var(--secondary-color);
}

/* Styling the search form */
.search {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.search input {
    width: calc(100% - 120px);
    padding: 10px;
    font-size: 1em;
    border: 1px solid var(--primary-color);
    border-radius: 5px;
    margin-right: 10px;
    background-color: #fff;
    color: var(--text-color);
}

.search button {
    padding: 10px 20px;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s ease;
}

.search button:hover {
    background-color: var(--primary-hover);
}

/* Styling the search results (appointments) */
.appointments h3 {
    font-size: 1.4em;
    margin-bottom: 10px;
    color: var(--primary-color);
}

.appointments ul {
    list-style: none;
    padding: 0;
}

/* Styling for each appointment box */
.appointment-box {
    padding: 15px;
    border: 1px solid var(--secondary-color);
    border-radius: 6px;
    background-color: var(--box-bg);
    margin-bottom: 15px;
    box-shadow: var(--box-shadow);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.appointment-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.appointment-box p {
    margin: 10px 0;
    font-size: 1em;
    color: var(--text-color);
}

.appointment-box strong {
    color: var(--primary-color);
}

/* Styling for horizontal rule between appointments */
hr {
    border: none;
    border-top: 1px solid var(--secondary-color);
    margin: 10px 0;
}

/* Styling for error message or no results found */
.appointments p {
    font-size: 1.1em;
    color: #ff1744;
    text-align: center;
}

/* Popup Content */
.popup-content {
    background-color: var(--box-bg);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    position: relative;
}

/* Smooth transitions for hover effects */
button,
.appointment-box {
    transition: all 0.3s ease;
}
h1{
    margin-left:44%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color:grey
}
a{
    text-decoration: underline;
    color:grey
}
.status.Done{
    color:green;
    font-weight:bold
}
.status.Pendin{
    color:grey;
    font-weight:bold
}
a{
    text-decoration: none;
    font-weight: 700;
}

</style>
</head>
<body>
</body>
</html>