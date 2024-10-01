<?php
// Controle para retirar acesso do site caso vá para manutenção.
$global_access = true; // Acesso global = Afetará todos menos os com cargo owner.
$user_access = true; // Acesso de usuario = Afetará apenas os com cargo user.

$strong_password = true; // Filtro para senhas fortes.
$txt_logs = true;

$edit_posts = true; // Permitir edição de novels.

$new_accounts = true; // Permitir a criação de novas contas.
$new_posts = true; // Permitir a criação de novas novels
$new_comments = true; // Permitir comentarios

$age_restriction = 13; // Idade minima para criar uma conta
$valid_emails_types = ["gmail", "hotmail", "uol"]; // Tipos de emails válidos para criar uma conta
$valid_emails_ends = ["com", "com.br"];
