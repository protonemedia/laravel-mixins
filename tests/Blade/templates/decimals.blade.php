@isset($code)
    @decimals($cents, $code)
@else
    @decimals($cents)
@endisset