Условия задачи не корректны, что легко доказывается построением контрпримера сообщения.

По определению "порядок полей, пунктуация и слова со временем могут быть изменены". Можно без потери общности положить, что ничего кроме искомых значений разделенных пробелами в сообщении нет.

Можно предположить, что сумма списания всегда имеет тип float, то есть содержит один из символов ',' или '.', но это не так, если верить вашему эмулятору: указывая в поле "Сумма" 123.38, в сообщении получим "Спишется 124р.".

Тогда сообщение 123 124 012345678901234 валидно по определению, но исходя только из условий невозможно определить что в нем код подтверждения, а что сумма списания.

Чтобы сделать задачу решаемой необходимо и достаточно:

1. уметь однозначно выделять из сообщения сумму списания;
2. знать длину либо кода подтверждения, либо номера счета.

Преполагая что:

1. сумма списания содержит один из символов ',' или '.';
2. известно количество цифр в номере счета,

то можно предложить следующее решение:

```php
function parse_message (string $text, int $account_number_length = 15) : array {
    
    return array_reduce (
        
        explode (' ', trim (preg_replace ('#[^\d,.]+#', ' ', preg_replace ('#(\D[,.])|([,.]\D)|([,.]$)#u', '', $text)))),
        
        function ($a, $c) use ($account_number_length) {
            
            $a [preg_match ('#[,.]#', $c) ? 'amount' : ((strlen ($c) == $account_number_length ? 'account' : 'code'))] = $c;
            
            return $a;
        },
        
        []
    );
}
