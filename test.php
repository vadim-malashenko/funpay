<?php

$text = 'Пароль: 0808
Спишется 123,45р.
Перевод на счет 410018896079825';

function parse_message (string $text, int $code_length = 4, int $account_length = 15) : array {
    
    return array_reduce (
        
        explode (' ', trim (preg_replace ('#[^\d,.]+#', ' ', preg_replace ('#(\D[,.])|([,.]\D)|([,.]$)#u', '', $text)))),
        
        function ($a, $c) use ($code_length, $account_length) {
            
            $a [(strlen ($c) == $code_length) ? 'code' : ((strlen ($c) == $account_length ? 'account' : 'amount'))] = $c;
            
            return $a;
        },
        
        []
    );
}

var_dump (parse_message ($text));
