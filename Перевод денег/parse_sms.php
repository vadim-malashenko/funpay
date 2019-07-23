<?php // 7+

function parse_sms (string $message) : array {
        
    return array_reduce (
        
        explode (
            ' ',
            trim (
                preg_replace (
                    '#[^\d,.]+#',
                    ' ',
                    preg_replace (
                        '#(^[,.])|(\D[,.])|([,.]\D)|([,.]$)#',
                        ' ',
                        $message
                    )
                )
            )
        ),
        
        function ($result, $value) {
            
            if (preg_match ('#[,.]#', $value))
            
                $result ['amount'] = floatval (str_replace (',', '.', $value));
            
            elseif (strlen ($value) < 11)
            
                $result ['code'] = $value;
                
            elseif (strlen ($value) <= 20)
            
                $result ['account'] = $value;
            
            return $result;
        },
        
        []
    )
    
    + [
        'code' => NULL,
        'account' => NULL,
        'amount' => NULL
    ];
}
