<?php
session_start(); // Inicia a sessão (se já não estiver iniciada)

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Destruir a sessão
session_destroy();

// Redirecionar para a página de login
header("Location: ../html/login.php");
exit;
?>
