По определению "порядок полей, пунктуация и слова со временем могут быть изменены". Можно без потери общности положить, что ничего кроме искомых значений разделенных пробелами в сообщении нет.

Можно предположить, что сумма списания всегда имеет тип float, то есть содержит один из символов ',' или '.', но это не так, если верить вашему эмулятору: указывая в поле "Сумма" 123.38, в сообщении получим "Спишется 124р.".

Тогда сообщение "123 124 012345678901234" валидно по определению, но исходя только из условий невозможно определить где в нем код подтверждения, а где сумма списания.

Предполагая что:

1. сумма списания содержит один из символов ',' или '.';
2. известно количество цифр в номере счета,

можно предложить следующее решение:

```php
<?php // 7+
function parse_message (string $message, int $account_number_length = 15) : array {
    
    if ( ! $message)
    
        throw new Exception ('Invalid argument: $message.');
    
    if ($account_number_length < 1)
    
        throw new Exception ('Invalid argument: $account_number_length.');
    
    $data = explode (
        ' ',
        trim (
            preg_replace (
                '#[^\d,.]+#',
                ' ',
                preg_replace (
                    '#(\D[,.])|([,.]\D)|([,.]$)#u',
                    '',
                    $message
                )
            )
        )
    );
    
    $data_count = count ($data);
    
    if ($data_count < 3)
        
        throw new Exception ('Message malformed: too few params.');
        
    elseif ($data_count > 3)
    
        throw new Exception ('Message malformed: too much params.');
    
    $data = array_reduce (
        
        $data,
        
        function ($a, $c) use ($account_number_length) {
            
            $a [
                preg_match ('#[,.]#', $c)
                    ? 'amount'
                    : (
                        (strlen ($c) == $account_number_length)
                            ? 'account'
                            : 'code'
                    )
            ] = $c;
            
            return $a;
        },
        
        []
    );
    
    if (count ($data) != 3)
    
        throw new Exception ('Message malformed: invalid param(s) value.');
    
    return $data;
}
