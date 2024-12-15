<?php
session_start();
?>

<aside class="sidebar">
  <h2>Perfect Smile Dental Clinic</h2>
  <div class="profile_pick">    
    <?php 
      $menuPage = '';
      switch ($_SESSION['role']) {
        case 'dentist':
          $menuPage = 'menuDenAss.php';
          break;
        case 'assistant':
          $menuPage = 'menuDenAss.php';
          break;
        case 'admin':
          $menuPage = 'menuAdm.php';
          break;}
      echo '<a href="' . $menuPage . '">';
      echo '<img src="' . $_SESSION['photo'] . '" alt="photo">'; 
    ?>
  </div>
  <h1><?php echo $_SESSION['person_name']?></h1>
  <nav class='nav_menu'>
    <?php
      $role=$_SESSION['role'];
      if (($role=='dentist')or($role=='assistant')) {
        echo '<a href="profile.php" class="update">Update Profile</a>';
      }
      if (($role=='dentist')or($role=='assistant')) {
        echo '<a href="schedule_month.php" class="schedule">Schedule</a>';
      }
      if (($role=='dentist')or($role=='assistant')) {
        echo '<a href="appointmentsMenu.php" class="appointments">Appointments</a>';
      }
      if ($role=='admin') {
        echo '<a href="dentists.php" class="dentists">Dentists</a>';
      }
      if ($role=='admin') {
        echo '<a href="assistants.php" class="assistants">Assistants</a>';
      }
      if (($role=='dentist')or($role=='admin')) {
        echo '<a href="patients.php" class="patients">Patients</a>';
      }
      if ($role=='admin') {
        echo '<a href="machines.php" class="machines">Machines</a>';
      }
    ?>
    <a href="../php/logOut.php" class='logOut'>Log Out</a>
  </nav>

  <div class='div_bottom'>
    <div class= 'text'>
      <h4>POWERED BY</h4>
    </div>
    <div class= 'logo'>
      <img src="../img/logoWhite.png" alt="logo">
    </div>
  </div>
</aside>

