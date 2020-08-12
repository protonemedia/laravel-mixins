@if(isset($code) && isset($locale))
    @money($cents, $code, $locale)
@elseif(isset($code))
    @money($cents, $code)
@else
    @money($cents)
@endif