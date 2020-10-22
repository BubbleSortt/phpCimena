<?php
require_once "Calculator.php";

$expression = $_GET['expression'];
$_SESSION['calculator']['expression'] = $expression;

$expression = str_replace(' ', '', $expression);

if($expression[0] == "/" || $expression[0] == "×" || $expression[0] == "+" || $expression[0] == "-")
{
    $_SESSION['calculator']['errors'] = "Некоректное выражение у вас. Нельзя начинать выражение с операции";
    header('Location: ../calculator.php');
}
elseif (substr($expression, -1) == "/" || substr($expression, -1) == "×" || substr($expression, -1) == "+" || substr($expression, -1) == "-")
{
    $_SESSION['calculator']['errors'] = "Некоректное выражение у вас. Нельзя заканчивать выражение операцией";
    header('Location: ../calculator.php');
}

$expression = str_replace('÷', ' / ', $expression);
$expression = str_replace('×', ' * ', $expression);
$expression = str_replace('-', ' - ', $expression);
$expression = str_replace('+', " + ", $expression);
$expression = str_replace('(', "( ", $expression);
$expression = str_replace(')', " )", $expression);

$tokens = explode(" ", $expression);

$calculator = new Calculator($tokens);
$_SESSION['calculator']['result'] = $calculator->parse();
$_SESSION['calculator']['result'];

header('Location: ../calculator.php');
