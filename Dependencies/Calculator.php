<?php
session_start();
class Calculator
{
    private $tokens = array();
    private $pos = 0;

    function  __construct($tokens){
        $this->tokens = $tokens;
    }
    public function parse(){
        $result = $this->expression();
        if($this->pos != count($this->tokens))
        {
            $_SESSION['calculator']['error'] = "Ошибка в выражении ".$this->tokens[$this->pos];
            header('Location: ../calculator.php');
        }
        return $result;
    }

    private function expression()
    {
        $first = $this->term();

        while($this->pos < count($this->tokens)) {
            $operator = $this->tokens[$this->pos];
            if (!($operator == "+") && !($operator == "-")) {
                break;
            } else {
                $this->pos++;
            }


            //Второе слагаемое
            $second = $this->term();

            if ($operator == "+")
            {
                $first+= $second;
            }
            else{
                $first -= $second;
            }
        }
        return $first;
    }

    private function term()
    {
        $first = $this->factor();

        while($this->pos < count($this->tokens))
        {
            $operator = $this->tokens[$this->pos];
            if(!($operator == "*") && !($operator == "/")){
                break;
            }
            else{
                $this->pos++;
            }

            //Второй множитель
            $second = $this->factor();

            if($operator == "*")
            {
                $first *= $second;
            }
            else
            {
                $first /= $second;
            }
        }
        return $first;
    }

    private function factor()
    {
        $next = $this->tokens[$this->pos];
        $result = 0;
        if($next == '(')
        {
            $this->pos++;
            //Если выражение в скобках, то переходим на обработку этого выражения
            $result = $this->expression();
            $closingBrackets = "";
            if($this->pos < count($this->tokens))
            {
                $closingBrackets = $this->tokens[$this->pos];
            }
            else
            {
                $_SESSION['calculator']['error'] = "Неожиданный конец выражения";
                header('Location: ../calculator.php');
            }
            if($this->pos < count($this->tokens) && $closingBrackets == ")")
            {
                $this->pos++;
                return $result;
            }
            $_SESSION['calculator']['error'] = "Ожидалась '(', но ".$closingBrackets;
            header('Location: ../calculator.php');

        }
        $this->pos++;
        return $next;
    }
}
