<?php
session_start();
require "../dbh.inc.php";

if (!isset($_SESSION['unique_id'])) {
    echo "User not logged in.";
    exit();
}

$usercred = $_SESSION['unique_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE unique_id = :usercred";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':usercred', $usercred);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$userDetails = '';
$query2 = "SELECT * FROM clinics WHERE clinic_id=:usercred";
$stmt2 = $pdo->prepare($query2);
$stmt2->bindParam(":usercred", $usercred);
$stmt2->execute();
$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $userImage = $result['image'];
    $sanitizedUserImage = htmlspecialchars($userImage, ENT_QUOTES, 'UTF-8');
    $userImagePath = "../images/{$sanitizedUserImage}";
    $query_app = "SELECT * FROM appointments WHERE pat_id = :unique_id Order BY id desc";
    $stmt_app = $pdo->prepare($query_app);
    $stmt_app->bindParam(":unique_id", $usercred);
    $stmt_app->execute();
    $result_app = $stmt_app->fetchAll(PDO::FETCH_ASSOC);

    $app_Details = "";
    foreach ($result_app as $row) {
        $appointmentDateTime = $row['date'] . ' ' . substr($row['time'], 0, 9);
        $appointmentTime = new DateTime($appointmentDateTime, new DateTimeZone('UTC'));
        $currentTime = new DateTime('now', new DateTimeZone('UTC'));

        $interval = $appointmentTime->diff($currentTime);

        $remainingTime = '';
        $status_pass = false;
        if ($appointmentTime < $currentTime) {
            $remainingTime = 'The appointment has passed';
            $status_pass = True;
        } else {
            $remainingTime = '';
            if ($interval->y > 0)
                $remainingTime .= $interval->y . ' years ';
            if ($interval->m > 0)
                $remainingTime .= $interval->m . ' months ';
            if ($interval->d > 0)
                $remainingTime .= $interval->d . ' days ';
            if ($interval->h > 0)
                $remainingTime .= $interval->h . ' hours ';
            if ($interval->i > 0)
                $remainingTime .= $interval->i . ' minutes ';
            if ($interval->s > 0)
                $remainingTime .= $interval->s . ' seconds ';
        }
        $status = $row['dosage'];
        $rate = ""; // Initialize the rate variable

        // Determine the status and corresponding status message
        if ($status) {
            $status = "Done";
            $status_fin = "<p class='Done'><strong>Status: $status</strong></p>";
            $status1 = "done";
            $rate2 = "done";
        } else if ($status_pass) {
            $status = "Passed";
            $status_fin = "<p class='passed'><strong>Status: $status</strong></p><br><p>Contact the doctor or book another appointment for further info</p>";
            $status1 = "pass";
            $rate2 = "pass";
        } else {
            $status = "Pending";
            $status_fin = "<p class='notDone'><strong>Status: $status</strong></p>";
            $status1 = "pending";
            $rate2 = "pending";
        }

        // Fetch doctor rating if exists
        $doctor = $row['preferred_doctors'];

        $query3 = "SELECT * FROM rating WHERE patient_id = :usercred AND doctor = :doctor";

        $stmt3 = $pdo->prepare($query3);
        $stmt3->bindParam(":usercred", $usercred);
        $stmt3->bindParam(":doctor", $doctor);
        $stmt3->execute();
        $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);

        if ($result3) {
            $rate2 = "rated";
            // echo "ratedd<br><br>";
        }
        // $rate=$status1;
        // Prepare the HTML content for rating the doctor
        // echo $result3['rating'];
        $name = $row['preferred_doctors'];
        // echo $rate;
        $rating_option = "
<div class='rating-box $rate2 '>
  <h2>Rate the doctor</h2>
  <div class='rating'>
    <form action='rating_sub.php' method='post'>
      <input type='hidden' value='$name' name='doc_name'>
      <div>Rate the doctor out of 5 stars</div>
      <select name='rating' id='rating'>
        <option value='5'>5 - Excellent</option>
        <option value='4'>4 - Good</option>
        <option value='3'>3 - Average</option>
        <option value='2'>2 - Poor</option>
        <option value='1'>1 - Very Poor</option>
      </select>
      <br><br>
      <button type='submit' class='submit-rating'>Submit Rating</button>
    </form>
  </div>
</div>";



        $app_Details .= "<div class='appointment'>
            <div class='appointment-header'>
                <h3>Appointment Details</h3>
            </div>
            <div class='appointment-body $status1'>
                <p><strong>Doctor:</strong> " . htmlspecialchars($row['preferred_doctors']) . "</p>
                <p><strong>Date of Appointment:</strong> " . htmlspecialchars($row['date']) . "</p>
                <p><strong>Time of Appointment:</strong> " . htmlspecialchars(substr($row['time'], 0, 9)) . "</p>
                <p><strong>Message to the doctor:</strong> " . htmlspecialchars($row['message']) . "</p>
                <p><strong>Registered at:</strong> " . htmlspecialchars(substr($row['time_of_reg'], 0, 19)) . "</p>
                <p><strong>Remaining Time:</strong> " . ($remainingTime ?: 'N/A') . "</p>
                $status_fin
            </div>
            $rating_option
        </div><br>";



    }

    $userName = $result['fname'] . " " . $result['lname'];
    $userNumber = $result['number'];
    $userEmail = $result['email'];
    $password = $result['password'];
    $pass = "
                <p><Strong>Password:</strong> {$password} <a href='change.php'>Click to view the password</a> </p>
    
    ";
    $userDetails = "<div class='profile'>
        <div class='profile-header'>
            <div class='profile-info'>
                <h1>{$userName}</h1>
                <p><strong>Email:</strong> {$userEmail}</p>
                <p><strong>Contact Number:</strong> {$userNumber}</p>
            </div>
        </div>
        <div class='appointment_det'>
            <h2>Your Appointments</h2>
                
            " . "
            <h3><a href='../appointments'>Book appointment?</a></h3>
            " . $app_Details . "
        </div>";

    echo $userDetails;
} else if ($result2) {
    $clinic = "<div class='clinic'>";
    // $clinic .= $result2['clinic_id'] . " " . $result2['clinics_name'] . " " . $result2['address'];
    $clinic .= "<div class='first'><div class='clinic-details'>
        <span class='clinic-id'>Your clinic id:" . $result2['clinic_id'] . "</span>
        <span class='clinic-name'> Clinics name:" . $result2['clinics_name'] . "</span>
        <span class='clinic-address'>Clinics address :" . $result2['address'] . "</span>
      </div>";
    $clinic .= '
    <h2>Add doctor</h2>
    <form action="../add_doctors.php" method="post" enctype="multipart/form-data" class="clinicform">
      <input type="text" placeholder="Enter the clinic\'s name" name="clinic" value=' . $result2['clinics_name'] . ' readonly disabled class="preinput">
      <input type="text" placeholder="Enter the address of your clinic" name="address" value=' . $result2['address'] . ' disabled class="preinput">
      <input type="number" value=' . $usercred . ' name="clinic_id" style="display:none">
      <div class="input-row">
          <input type="text" placeholder="Enter doctor\'s first name" name="fname" required>
          <input type="text" placeholder="Enter doctor\'s last name" name="lname" required>
      </div>

      <input type="email" placeholder="Enter the doctor\'s email address" name="email" required>
      <input type="number" placeholder="Enter the doctor\'s phone number" name="number" required>

      <input type="password" placeholder="Enter the doctor\'s password" name="password" required>

      <label for="specialization">Doctor\'s Specialization:</label>
      <div class="specialization">
          <div>
              <input type="text" placeholder="Enter 1st Specialization" name="specialization1" required>
              <input type="text" placeholder="Enter 2nd Specialization" name="specialization2">
          </div>
          <div>
              <input type="text" placeholder="Enter 3rd Specialization" name="specialization3">
              <input type="text" placeholder="Enter 4th Specialization" name="specialization4">
          </div>
      </div>

      <button type="submit">Add Doctor</button>
  </form></div>';
    $querydoctor = "SELECT * FROM doctors WHERE clinic_id=:clinic_id";
    $stmt = $pdo->prepare($querydoctor);
    $stmt->bindParam(':clinic_id', $result2['clinic_id']);
    $stmt->execute();
    $resultdoc = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $doc_details = "<div class='doctordetails'>
        <h1>Doctor details</h1>
    
    ";

foreach ($resultdoc as $res) {
    $doc_details .= "
        <div class='doctor'>
            <h2 class='doctor-name'>" . htmlspecialchars($res['fname']) . " " . htmlspecialchars($res['lname']) . "</h2>
            <p><strong>Contact Number:</strong> " . htmlspecialchars($res['number']) . "</p>
            <p><strong>Password:</strong> " . htmlspecialchars($res['password']) . "</p>
            <p><strong>Login id for doctor : </strong>".$res['unique_id']."</p>
            <p><strong>Specializations:</strong> 
                " . htmlspecialchars($res['specialization1']) . ", 
                " . htmlspecialchars($res['specialization2']) . ", 
                " . htmlspecialchars($res['specialization3']) . ", 
                " . htmlspecialchars($res['specialization4']) . "
            </p>
            <p><strong>Rating:</strong> " . htmlspecialchars($res['rating']) . "</p>
        </div>";
}

$doc_details .= "</div></div>";

    // echo $usercred;
    echo $clinic . $doc_details;

} else {




    $doctorDetails = '';
    $app = '';
    $currentDateTime = new DateTime();


    $query1 = "SELECT * FROM doctors WHERE unique_id = :unique_id ORDER BY id DESC";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(':unique_id', $usercred);
    $stmt1->execute();
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

    if ($result1) {
        // $doctorImage = htmlspecialchars($result1['image'], ENT_QUOTES, 'UTF-8');
        // $doctorImagePath = "../images/{$doctorImage}";

        $doctorName = htmlspecialchars($result1['fname'] . " " . $result1['lname']);
        $doctorNumber = htmlspecialchars($result1['number']);
        $doctorEmail = htmlspecialchars($result1['email']);
        $specialization1 = htmlspecialchars($result1['specialization1']);
        $specialization2 = htmlspecialchars($result1['specialization2']);
        $specialization3 = htmlspecialchars($result1['specialization3']);
        $specialization4 = htmlspecialchars($result1['specialization4']);

        // Fetch the doctor's appointments by name
        $query_fetch = "SELECT * FROM appointments WHERE preferred_doctors = :name ORDER BY id DESC";
        $stmt_fetch = $pdo->prepare($query_fetch);
        $stmt_fetch->bindParam(":name", $doctorName);
        $stmt_fetch->execute();
        $appointments = $stmt_fetch->fetchAll(PDO::FETCH_ASSOC);

        // Build the appointments section
        foreach ($appointments as $row1) {
            $appointmentDateTime = new DateTime($row1['date'] . ' ' . $row1['time']);
            $statusClass = '';

            if ($appointmentDateTime < $currentDateTime) {
                // If appointment time has passed
                $statusClass = 'past-appointment';
                $status = "<p class='status past'>Status: Passed</p>";
            } elseif ($row1['dosage'] != NULL) {
                $status = "<p class='status done'>Status: Done</p>";
            } else {
                $status = "<p class='status pending'>Status: Pending</p>";
            }

            $app .= "<div class='appointment-box {$statusClass}'>
                    <p><strong>Patient Name:</strong> " . htmlspecialchars($row1['name']) . "</p>
                    <p><strong>Appointment Date:</strong> " . htmlspecialchars($row1['date']) . "</p>
                    <p><strong>Time:</strong> " . htmlspecialchars(substr($row1['time'], 0, 9)) . "</p>
                    <p><strong>Appointment Message:</strong> " . htmlspecialchars($row1['message']) . "</p>
                    <p><strong>Registered at:</strong> " . htmlspecialchars(substr($row1['time_of_reg'], 0, 20)) . "</p>
                    $status
                </div><hr>";
        }

        $doctorDetails = "
        <script>console.log('hy')</script>
    <div class='doctor-profile'>
        <h2>Doctor Information</h2>
        
        <div class='doctor-header'>
            <div class='doctor-info'>
                <h3>{$doctorName}</h3>
                <p><strong>Email:</strong> {$doctorEmail}</p>
                <p><strong>Contact Number:</strong> {$doctorNumber}</p>
                <p><strong>Specializations:</strong></p>
                <ul>
                    <li>{$specialization1}</li>
                    <li>{$specialization2}</li>
                    <li>{$specialization3}</li>
                    <li>{$specialization4}</li>
                </ul>
            </div>
        </div>
        
        <form id='searchForm' method='GET' class='search' action='search.php'>
            <input type='text' id='searchInput' name='search' placeholder='Enter the patient name to start the appointment'>
            <button type='submit'>Search</button>
        </form>
        
        <div id='searchResults' class='appointments'>
            <h3>Appointments</h3>
            <p>Search the patient name to start the prescription</p>
            {$app}
        </div>
    </div>";


        echo $doctorDetails;



    }
}
echo "<script>console.log('heyyy')</script>";


