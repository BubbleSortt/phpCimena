
<!doctype html>
<html lang="ru">
<head>
<?php
session_start();
include_once "includes/metaInf.php";
include_once "includes/links.php"?>
</head>
<body class="calculator-main">
<main data-table="true">
    <div class="container">
        <div class="calculator">
            <div class="error">
                <span><?php echo $_SESSION['calculator']['error'] ?? ""?></span>
            </div>
            <form class="calculator__form" action="includes/CalculatorController.php" method="get">
                <input class="calculator__input" id="expression" name="expression" readonly="readonly" type="text" placeholder="0"
                value ="<?php  echo (isset($_SESSION['calculator']['error'])) ? ($_SESSION['calculator']['expression'] ?? "") : ($_SESSION['calculator']['result']) ?? "";
                unset($_SESSION['calculator']['error']); unset($_SESSION['calculator']['expression']); unset($_SESSION['calculator']['result']);
                ?>">
                <div class="keyboard">
                    <div class="keyboard__tr">
                        <div class="keyboard__td" data-keyboard="(">(</div>
                        <div class="keyboard__td" data-keyboard=")">)</div>
                        <div class="keyboard__td" data-keyboard="delete-all">AC</div>
                        <div class="keyboard__td keyboard__td_operation" data-keyboard="&divide;">&divide;</div>
                    </div>
                    <div class="keyboard__tr">
                        <div class="keyboard__td" data-keyboard="7">7</div>
                        <div class="keyboard__td" data-keyboard="8">8</div>
                        <div class="keyboard__td" data-keyboard="9">9</div>
                        <div class="keyboard__td keyboard__td_operation" data-keyboard="&times;">&times;</div>
                    </div>
                    <div class="keyboard__tr">
                        <div class="keyboard__td" data-keyboard="4">4</div>
                        <div class="keyboard__td" data-keyboard="5">5</div>
                        <div class="keyboard__td" data-keyboard="6">6</div>
                        <div class="keyboard__td keyboard__td_operation" data-keyboard="-">-</div>
                    </div>
                    <div class="keyboard__tr">
                        <div class="keyboard__td" data-keyboard="1">1</div>
                        <div class="keyboard__td" data-keyboard="2">2</div>
                        <div class="keyboard__td" data-keyboard="3">3</div>
                        <div class="keyboard__td keyboard__td_operation" data-keyboard="&#43;">&#43;</div>
                    </div>
                    <div class="keyboard__tr">
                        <div class="keyboard__td" data-keyboard="0">0</div>
                        <div class="keyboard__td" data-keyboard=",">,</div>
                        <div class="keyboard__td" data-keyboard="delete">C</div>
                        <button type="submit" class="keyboard__td keyboard__td_operation" data-keyboard="">=</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include_once "includes/js.php"?></body>
</html>